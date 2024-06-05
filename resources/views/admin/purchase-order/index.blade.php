<?php use App\Models\StockUpdate; ?>

@extends('admin._layouts.default', [
    'title' => 'Order Pembelian',
    'menu_active' => 'purchasing',
    'nav_active' => 'purchase-order',
])

@section('right-menu')
  <li class="nav-item">
    <a href="<?= url('/admin/purchase-order/create') ?>" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <form action="?">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group form-inline">
              <label class="mr-2" for="status">Status Order:</label>
              <select class="form-control" id="status" name="status" onchange="this.form.submit()">
                <option value="-1" <?= $filter['status'] == -1 ? 'selected' : '' ?>>Semua Status</option>
                <option value="{{ StockUpdate::STATUS_OPEN }}"
                  {{ $filter['status'] == StockUpdate::STATUS_OPEN ? 'selected' : '' }}>
                  {{ StockUpdate::formatStatus(StockUpdate::STATUS_OPEN) }}</option>
                <option value="{{ StockUpdate::STATUS_COMPLETED }}"
                  {{ $filter['status'] == StockUpdate::STATUS_COMPLETED ? 'selected' : '' }}>
                  {{ StockUpdate::formatStatus(StockUpdate::STATUS_COMPLETED) }}</option>
                <option value="{{ StockUpdate::STATUS_CANCELED }}"
                  {{ $filter['status'] == StockUpdate::STATUS_CANCELED ? 'selected' : '' }}>
                  {{ StockUpdate::formatStatus(StockUpdate::STATUS_CANCELED) }}</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 d-flex justify-content-end">
            <div class="form-group form-inline">
              <label class="mr-2" for="search">Cari:</label>
              <input type="text" class="form-control" name="search" id="search" value="{{ $filter['search'] }}"
                autofocus placeholder="Cari pemasok">
            </div>
          </div>
        </div>
      </form>
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Pemasok</th>
                  <th>Total</th>
                  <th>Piutang</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $item)
                  <tr
                    class="{{ $item->status == StockUpdate::STATUS_CANCELED ? 'table-danger' : ($item->status == StockUpdate::STATUS_OPEN ? 'table-warning' : '') }}">
                    <td>{{ $item->idFormatted() }}</td>
                    <td>{{ format_datetime($item->datetime) }}</td>
                    <td>{{ $item->statusFormatted() }}</td>
                    <td>{{ $item->party ? $item->party->idFormatted() . ' - ' . $item->party->name : '' }}</td>
                    <td class="text-right">{{ format_number(abs($item->total_cost)) }}</td>
                    <td class="text-right">{{ format_number($item->total_receivable) }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        @if ($item->status != StockUpdate::STATUS_OPEN)
                          <a href="<?= url("/admin/purchase-order/detail/$item->id") ?>" class="btn btn-default btn-sm"><i
                              class="fa fa-eye" title="View"></i></a>
                        @else
                          <a href="<?= url("/admin/purchase-order/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                              class="fa fa-edit" title="Edit"></i></a>
                        @endif
                        <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                          href="<?= url("/admin/stock-update/delete/$item->id?goto=sales-order") ?>"
                          class="btn btn-danger btn-sm"><i class="fa fa-trash" title="Hapus"></i></a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr class="empty">
                    <td colspan="7">Tidak ada rekaman untuk ditampilkan.
                    </td>
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
@endSection
