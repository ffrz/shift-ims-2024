<?php
use App\Models\ServiceOrder;
$title = 'Rincian Order Servis';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'service',
    'nav_active' => 'service_order',
    'back_button_link' => url('/admin/service-order/'),
])

@section('content')
  <form class="form-horizontal quick-form" method="POST"
    action="{{ url('admin/service-order/action/' . (int) $item->id) }}">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
    <div class="card card-primary">
      @include('admin._components.card-header', ['title' => $title])

      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <h4>Info Order</h4>
            <table class="table table-sm" style="width:100%">
              <tr>
                <td style="width:30%"># Order</td>
                <td style="width:2%">:</td>
                <td>{{ $item->orderId() }}</td>
              </tr>
              <tr>
                <td>Tanggal diterima</td>
                <td>:</td>
                <td>{{ format_date($item->date_received) }}</td>
              </tr>
              <tr>
                <td>Tanggal diambil</td>
                <td>:</td>
                <td>{{ $item->date_taken ? format_date($item->date_taken) : '-' }}</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td>{{ ServiceOrder::formatOrderStatus($item->order_status) }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <h4>Info Pelanggan</h4>
            <table class="table table-sm" style="width:100%">
              <tr>
                <td style="width:30%">Nama Pelanggan</td>
                <td style="width:2%">:</td>
                <td>{{ $item->customer_name }}</td>
              </tr>
              <tr>
                <td>Kontak</td>
                <td>:</td>
                <td>{{ $item->customer_contact }}</td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $item->customer_address }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <h4>Info Perangkat</h4>
            <table class="table table-sm" style="width:100%">
              <tr>
                <td style="width:30%">Jenis</td>
                <td style="width:2%">:</td>
                <td>{{ $item->device_type }}</td>
              </tr>
              <tr>
                <td>Perangkat</td>
                <td>:</td>
                <td>{{ $item->device }}</td>
              </tr>
              <tr>
                <td>Perlengkapan</td>
                <td>:</td>
                <td>{{ $item->equipments }}</td>
              </tr>
              <tr>
                <td>SN</td>
                <td>:</td>
                <td>{{ $item->device_sn }}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-4">
            <h4>Info Servis</h4>
            <table class="table table-sm" style="width:100%">
              <tr>
                <td style="width:30%">Keluhan</td>
                <td style="width:2%">:</td>
                <td>{{ $item->problems }}</td>
              </tr>
              <tr>
                <td>Tindakan</td>
                <td>:</td>
                <td>{{ $item->actions }}</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td>{{ ServiceOrder::formatServiceStatus($item->service_status) }}</td>
              </tr>
              <tr>
                <td>Tanggal Selesai</td>
                <td>:</td>
                <td>{{ $item->date_completed ? format_date($item->date_completed) : '-' }}</td>
              </tr>
              <tr>
                <td>Teknisi</td>
                <td>:</td>
                <td>{{ $item->technician }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <h4>Info Biaya</h4>
            <table class="table table-sm" style="width:100%">
              <tr>
                <td style="width:30%">Biaya Perkiraan</td>
                <td style="width:2%">:</td>
                <td class="text-right">Rp. {{ format_number($item->estimated_cost) }}</td>
              </tr>
              <tr>
                <td>Uang Muka</td>
                <td>:</td>
                <td class="text-right">Rp. {{ format_number($item->down_payment) }}</td>
              </tr>
              <tr>
                <td>Total Biaya</td>
                <td>:</td>
                <td class="text-right">Rp. {{ format_number($item->total_cost) }}</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td>{{ ServiceOrder::formatPaymentStatus($item->payment_status) }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <h4>Catatan</h4>
            <p>{{ empty($item->notes) ? '- tidak ada catatan -' : $item->notes }}</p>
          </div>
        </div>
      </div> {{-- .card-body --}}
    </div>

    <div class="card-footer">
      @if ($item->service_status < ServiceOrder::SERVICE_STATUS_SUCCESS)
        <div class="btn-group mr-3">
          <button type="submit" class="btn btn-success" name="action" value="service_success"><i
              class="fas fa-check"></i> Sukses</button>
          <button type="submit" class="btn btn-warning" name="action" value="service_failed"><i
              class="fas fa-xmark"></i> Gagal</button>
        </div>
      @endif
      @if ($item->payment_status != ServiceOrder::PAYMENT_STATUS_FULLY_PAID)
        <button type="submit" class="btn btn-success mr-3" name="action" value="fully_paid"><i
            class="fas fa-check"></i> Lunas</button>
      @endif
      @if ($item->order_status < ServiceOrder::ORDER_STATUS_COMPLETED)
        <div class="btn-group mr-3">
          <button type="submit" class="btn btn-success" name="action" value="complete_order"><i
              class="fas fa-check"></i> Selesai</button>
          <button type="submit" class="btn btn-warning" name="action" value="cancel_order"><i class="fas fa-xmark"></i>
            Batalkan</button>
        </div>
      @endif
      <div class="btn-group">
        <a href="/admin/service-order/print/{{ $item->id }}" class="btn btn-default"><i class="fas fa-print"></i>
          Cetak</a>
        <a href="/admin/service-order/edit/{{ $item->id }}" class="btn btn-default"><i class="fas fa-edit"></i>
          Edit</a>
        <a href="/admin/service-order/delete/{{ $item->id }}" onclick="return confirm('Hapus?')"
          class="btn btn-danger"><i class="fas fa-edit"></i> Hapus</a>
      </div>
    </div>
  </form>
@endSection
