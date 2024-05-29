<?php
use App\Models\ServiceOrder;
$title = 'Rincian Pembaruan Stok';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'stock-update',
    'back_button_link' => url('/admin/stock-update/'),
])

@section('content')
  <div class="card card-primary">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table no-border table-sm" style="width:100%">
            <tr>
              <td style="width:10%">Kode Update</td>
              <td style="width:2%">:</td>
              <td>{{ $item->id2Formatted() }}</td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>:</td>
              <td>{{ format_date($item->date) }}</td>
            </tr>
            <tr>
              <td>Dibuat</td>
              <td>:</td>
              <td>{{ format_datetime($item->creation_datetime) }} oleh
                #{{ $item->creation_user->id . '-' . $item->creation_user->username }}</td>
            </tr>
            @if ($item->status != 0)
              <tr>
                <td>Ditutup</td>
                <td>:</td>
                <td>{{ format_datetime($item->closing_datetime) }} oleh
                  #{{ $item->closing_user->id . '-' . $item->closing_user->username }}</td>
              </tr>
            @endif
            <tr>
              <td>Jenis</td>
              <td>:</td>
              <td>{{ $item->typeFormatted() }}</td>
            </tr>
            <tr>
              <td>Status</td>
              <td>:</td>
              <td>{{ $item->statusFormatted() }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-condensed center-th table-sm" style="width:100%">
            <thead>
              <th>No</th>
              <th>Produk</th>
              <th>Selisih</th>
              <th>Satuan</th>
              <th>Selisih Modal</th>
              <th>Selisih Harga Jual</th>
            </thead>
            <tbody>
              @php
                $total_cost = 0;
                $total_price = 0;
              @endphp
              @foreach ($details as $detail)
                @php
                  $subtotal_cost = $detail->cost * $detail->quantity;
                  $subtotal_price = $detail->price * $detail->quantity;
                  $total_cost += $subtotal_cost;
                  $total_price += $subtotal_price;
                @endphp
                <tr>
                  <td class="text-right">{{ $detail->id }}</td>
                  <td>{{ $detail->product->code }}</td>
                  <td class="text-right">{{ format_number($detail->quantity) }}</td>
                  <td>{{ $detail->product->uom }}</td>
                  <td class="text-right">{{ format_number($subtotal_cost) }}</td>
                  <td class="text-right">{{ format_number($subtotal_price) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4">Total</th>
                <th class="text-right">{{ format_number($total_cost) }}</th>
                <th class="text-right">{{ format_number($total_price) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div> {{-- .card-body --}}
  </div>
@endSection
