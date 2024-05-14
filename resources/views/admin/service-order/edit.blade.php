<?php
use App\Models\ServiceOrder;
$title = ($item->id ? 'Edit' : 'Buat') . ' Service Order';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'service',
    'nav_active' => 'service_order',
    'back_button_link' => url('/admin/service-order/'),
])

@section('content')
  <div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST" action="{{ url('admin/user-groups/edit/' . (int) $item->id) }}">
      @csrf
      <input type="hidden" name="id" value="{{ $item->id }}">

      @include('admin._components.card-header', ['title' => $title])

      <div class="card-body">
        <h4>Info Penerimaan</h4>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="date_received">Tanggal:</label>
            <input type="date" class="form-control @error('date_received') is-invalid @enderror" autofocus
              id="date_received" name="date_received" value="{{ old('date_received', $item->date_received) }}">
            @error('date_received')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="order_status">Status:</label>
            <select class="custom-select select2 form-control" id="order_status" name="order_status">
              <option value="{{ ServiceOrder::ORDER_STATUS_ACTIVE }}"
                {{ $item->order_status == ServiceOrder::ORDER_STATUS_ACTIVE ? 'selected' : '' }}>
                {{ ServiceOrder::formatOrderStatus(ServiceOrder::ORDER_STATUS_ACTIVE) }}</option>
              <option value="{{ ServiceOrder::ORDER_STATUS_COMPLETED }}"
                {{ $item->order_status == ServiceOrder::ORDER_STATUS_COMPLETED ? 'selected' : '' }}>
                {{ ServiceOrder::formatOrderStatus(ServiceOrder::ORDER_STATUS_COMPLETED) }}</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="id">#No Order:</label>
            <input type="text" class="form-control" id="id" name=""
              value="{{ $item->id ? ServiceOrder::formatOrderId($item->id) : '-- otomatis --' }}" readonly>
          </div>
        </div>
        <h4 class="mt-3">Info Pelanggan</h4>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="customer_name">Nama Pelanggan:</label>
            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name"
              placeholder="Masukkan Nama Pelanggan" name="customer_name"
              value="{{ old('customer_name', $item->customer_name) }}">
            @error('customer_name')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="customer_contact">Kontak:</label>
            <input type="text" class="form-control @error('customer_contact') is-invalid @enderror"
              id="customer_contact" placeholder="Masukkan Kontak Pelanggan" name="customer_contact"
              value="{{ old('customer_contact', $item->customer_contact) }}">
            @error('customer_contact')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="customer_address">Alamat Pelanggan:</label>
            <input type="text" class="form-control @error('customer_address') is-invalid @enderror"
              id="customer_address" placeholder="Masukkan Alamat Pelanggan" name="customer_address"
              value="{{ old('customer_address', $item->customer_address) }}">
            @error('customer_address')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <h4 class="mt-3">Info Perangkat</h4>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="device_type">Jenis Perangkat:</label>
            <input type="text" class="form-control @error('device_type') is-invalid @enderror" id="device_type"
              placeholder="Masukkan Jenis Perangkat" name="device_type" list="device_type_options"
              value="{{ old('device_type', $item->device_type) }}">
            <datalist id="device_type_options">
              @foreach ($device_types as $type)
                <option value="{{ $type }}">
              @endforeach
            </datalist>
            @error('device_type')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="device">Perangkat:</label>
            <input type="text" class="form-control @error('device') is-invalid @enderror" id="device"
              placeholder="Masukkan Perangkat" name="device" list="device_options"
              value="{{ old('device', $item->device) }}">
            <datalist id="device_options">
              @foreach ($devices as $device)
                <option value="{{ $device }}">
              @endforeach
            </datalist>
            @error('device_type')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="equipments">Kelengkapan:</label>
            <input type="text" class="form-control @error('equipments') is-invalid @enderror" id="equipments"
              placeholder="Kelengkapan" name="equipments" value="{{ old('equipments', $item->equipments) }}">
            @error('equipments')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="serial">SN / IMEI:</label>
            <input type="text" class="form-control @error('serial') is-invalid @enderror" id="serial"
              placeholder="SN" name="serial" value="{{ old('serial', $item->serial) }}">
            @error('serial')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <h4 class="mt-3">Info Service</h4>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="problems">Keluhan:</label>
            <input type="text" class="form-control @error('problems') is-invalid @enderror" id="problems"
              name="problems" value="{{ old('problems', $item->problems) }}">
            @error('problems')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="actions">Tindakan:</label>
            <input type="text" class="form-control @error('actions') is-invalid @enderror" id="actions"
              name="actions" value="{{ old('actions', $item->actions) }}">
            @error('actions')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="service_status">Status Servis:</label>
            <select class="custom-select select2 form-control" id="service_status" name="service_status">
              <option value="{{ ServiceOrder::SERVICE_STATUS_RECEIVED }}"
                {{ $item->service_status == ServiceOrder::SERVICE_STATUS_RECEIVED ? 'selected' : '' }}>
                {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_RECEIVED) }}</option>
              <option value="{{ ServiceOrder::SERVICE_STATUS_CHECKED }}"
                {{ $item->service_status == ServiceOrder::SERVICE_STATUS_CHECKED ? 'selected' : '' }}>
                {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_CHECKED) }}</option>
              <option value="{{ ServiceOrder::SERVICE_STATUS_WORKED }}"
                {{ $item->service_status == ServiceOrder::SERVICE_STATUS_WORKED ? 'selected' : '' }}>
                {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_WORKED) }}</option>
              <option value="{{ ServiceOrder::SERVICE_STATUS_SUCCESS }}"
                {{ $item->service_status == ServiceOrder::SERVICE_STATUS_SUCCESS ? 'selected' : '' }}>
                {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_SUCCESS) }}</option>
              <option value="{{ ServiceOrder::SERVICE_STATUS_FAILED }}"
                {{ $item->service_status == ServiceOrder::SERVICE_STATUS_FAILED ? 'selected' : '' }}>
                {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_FAILED) }}</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="date_completed">Tanggal Selesai:</label>
            <input type="date" class="form-control @error('date_completed') is-invalid @enderror" autofocus
              id="date_completed" name="date_completed" value="{{ old('date_completed', $item->date_completed) }}">
            @error('date_completed')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="technician">Teknisi:</label>
            <input type="text" class="form-control @error('technician') is-invalid @enderror" id="technician"
              name="technician" value="{{ old('technician', $item->technician) }}">
            @error('technician')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <h4 class="mt-3">Info Biaya</h4>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="estimated_cost">Perkiraan Biaya:</label>
            <input type="text" class="form-control text-right @error('estimated_cost') is-invalid @enderror"
              id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $item->estimated_cost) }}">
            @error('estimated_cost')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="down_payment">Uang Muka:</label>
            <input type="text" class="form-control text-right @error('down_payment') is-invalid @enderror"
              id="down_payment" name="down_payment" value="{{ old('down_payment', $item->down_payment) }}">
            @error('down_payment')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="total_cost">Total Biaya:</label>
            <input type="text" class="form-control text-right @error('total_cost') is-invalid @enderror"
              id="total_cost" name="total_cost" value="{{ old('total_cost', $item->total_cost) }}">
            @error('total_cost')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="payment_status">Status Pembayaran:</label>
            <select class="custom-select select2 form-control" id="payment_status" name="payment_status">
              <option value="{{ ServiceOrder::PAYMENT_STATUS_UNPAID }}"
                {{ $item->payment_status == ServiceOrder::PAYMENT_STATUS_UNPAID ? 'selected' : '' }}>
                {{ ServiceOrder::formatPaymentStatus(ServiceOrder::PAYMENT_STATUS_UNPAID) }}</option>
              <option value="{{ ServiceOrder::PAYMENT_STATUS_FULLY_PAID }}"
                {{ $item->payment_status == ServiceOrder::PAYMENT_STATUS_FULLY_PAID ? 'selected' : '' }}>
                {{ ServiceOrder::formatPaymentStatus(ServiceOrder::PAYMENT_STATUS_FULLY_PAID) }}</option>
            </select>
          </div>
        </div>
        <h4 class="mt-3"><label for="notes">Catatan:</label></h4>
        <div class="form-row">
          <div class="form-group col-md-12">
            <textarea class="form-control @error('textarea') is-invalid @enderror" id="textarea" name="textarea">{{ old('textarea', $item->textarea) }}</textarea>
            @error('textarea')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
      </div> {{-- .card-body --}}
  </div>

  <div class="card-footer">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
  </div>
  </form>
  </div>
@endSection

@section('footscript')
  <script>
    $(function() {
      $('.select2').select2({
        minimumResultsForSearch: -1
      });
    });
  </script>
@endsection
