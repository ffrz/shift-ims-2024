<?php use App\Models\ServiceOrder; ?>

@extends('admin._layouts.default', [
    'title' => 'Order Servis',
    'menu_active' => 'sales',
    'nav_active' => 'service-order',
])

@section('right-menu')
  <li class="nav-item">
    <a href="<?= url('/admin/service-order/edit/0') ?>" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
        
  </li>
@endSection

@section('content')
  <form action="?">
    <div class="accordion" id="filterBox">
      <div class="card">
        <div class="card-header" id="filterHeading">
          <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
            data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
            <i class="fa fa-filter mr-2"> </i> Penyaringan
          </button>
        </div>
        <div id="filterCollapse" class="collapse" aria-labelledby="filterHeading" data-parent="#filterBox">
          <div class="card-body">
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="order_status">Status Order:</label>
                <select class="custom-select select2 form-control" id="order_status" name="order_status">
                  <option value="-1" <?= $filter['order_status'] == -1 ? 'selected' : '' ?>>Semua Status</option>
                  <option value="{{ ServiceOrder::ORDER_STATUS_ACTIVE }}"
                    {{ $filter['order_status'] == ServiceOrder::ORDER_STATUS_ACTIVE ? 'selected' : '' }}>
                    {{ ServiceOrder::formatOrderStatus(ServiceOrder::ORDER_STATUS_ACTIVE) }}</option>
                  <option value="{{ ServiceOrder::ORDER_STATUS_COMPLETED }}"
                    {{ $filter['order_status'] == ServiceOrder::ORDER_STATUS_COMPLETED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatOrderStatus(ServiceOrder::ORDER_STATUS_COMPLETED) }}</option>
                  <option value="{{ ServiceOrder::ORDER_STATUS_CANCELED }}"
                    {{ $filter['order_status'] == ServiceOrder::ORDER_STATUS_CANCELED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatOrderStatus(ServiceOrder::ORDER_STATUS_CANCELED) }}</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="service_status">Status Servis:</label>
                <select class="custom-select select2 form-control" id="service_status" name="service_status">
                  <option value="-1" <?= $filter['service_status'] == -1 ? 'selected' : '' ?>>Semua Status</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_NOT_YET_CHECKED }}"
                    {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_NOT_YET_CHECKED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_NOT_YET_CHECKED) }}</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_CHECKED }}"
                    {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_CHECKED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_CHECKED) }}</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_WORKED }}"
                    {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_WORKED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_WORKED) }}</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_SUCCESS }}"
                    {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_SUCCESS ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_SUCCESS) }}</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_FAILED }}"
                    {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_FAILED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_FAILED) }}</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="payment_status">Status Pembayaran:</label>
                <select class="custom-select select2 form-control" id="payment_status" name="payment_status">
                  <option value="-1" <?= $filter['payment_status'] == -1 ? 'selected' : '' ?>>Semua Status</option>
                  <option value="{{ ServiceOrder::PAYMENT_STATUS_UNPAID }}"
                    {{ $filter['payment_status'] == ServiceOrder::PAYMENT_STATUS_UNPAID ? 'selected' : '' }}>
                    {{ ServiceOrder::formatPaymentStatus(ServiceOrder::PAYMENT_STATUS_UNPAID) }}</option>
                  <option value="{{ ServiceOrder::PAYMENT_STATUS_PARTIALLY_PAID }}"
                    {{ $filter['payment_status'] == ServiceOrder::PAYMENT_STATUS_PARTIALLY_PAID ? 'selected' : '' }}>
                    {{ ServiceOrder::formatPaymentStatus(ServiceOrder::PAYMENT_STATUS_PARTIALLY_PAID) }}</option>
                  <option value="{{ ServiceOrder::PAYMENT_STATUS_FULLY_PAID }}"
                    {{ $filter['payment_status'] == ServiceOrder::PAYMENT_STATUS_FULLY_PAID ? 'selected' : '' }}>
                    {{ ServiceOrder::formatPaymentStatus(ServiceOrder::PAYMENT_STATUS_FULLY_PAID) }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Terapkan</button>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-light">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
          </div>
          <div class="col-md-6 d-flex justify-content-end">
            <div class="form-group form-inline">
              <label class="mr-2" for="search">Cari:</label>
              <input type="text" class="form-control" name="search" id="search" value="{{ $filter['search'] }}"
                placeholder="Cari produk">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tgl Masuk</th>
                    <th>Atas Nama</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Perangkat</th>
                    <th>Status Order</th>
                    <th>Status Servis</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    <tr>
                      <td>{{ $item->formatOrderId($item->id) }}</td>
                      <td>{{ $item->date_received }}</td>
                      <td>{{ $item->customer_name }}</td>
                      <td>{{ $item->customer_phone }}</td>
                      <td>{{ $item->customer_address }}</td>
                      <td>{{ $item->device }}</td>
                      <td>{{ $item->formatOrderStatus($item->order_status) }}</td>
                      <td>{{ $item->formatServiceStatus($item->service_status) }}</td>
                      <td>{{ $item->formatPaymentStatus($item->payment_status) }}</td>
                      <td class="text-center">
                        <div class="btn-group">
                          @if (empty($item->deleted_at))
                            <a href="<?= url("/admin/service-order/detail/$item->id") ?>"
                              class="btn btn-default btn-sm"><i class="fa fa-eye" title="View"></i></a>
                            <a href="<?= url("/admin/service-order/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                                class="fa fa-edit" title="Edit"></i></a>
                            <a href="<?= url("/admin/service-order/duplicate/$item->id") ?>"
                              class="btn btn-default btn-sm"><i class="fa fa-copy" title="Duplikat"></i></a>
                            <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                              href="<?= url("/admin/service-order/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                                class="fa fa-trash" title="Hapus"></i></a>
                          @else
                            <a onclick="return confirm('Anda yakin akan mengembalikan rekaman ini?')"
                              href="<?= url("/admin/service-order/restore/$item->id") ?>"
                              class="btn btn-default btn-sm"><i class="fa fa-trash-arrow-up" title="Pulihkan"></i></a>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr class="empty">
                      <td colspan="10">Tidak ada rekaman yang dapat ditampilkan.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @include('admin._components.paginator', ['items' => $items])
      </div>
    </div>
  </form>
@endSection
