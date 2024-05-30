<?php use App\Models\StockUpdate; ?>

@extends('admin._layouts.default', [
    'title' => 'Stok Opname',
    'menu_active' => 'inventory',
    'nav_active' => 'stock-adjustment',
])

@section('right-menu')
  <li class="nav-item">
    <a href="<?= url('/admin/stock-adjustment/create') ?>" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th style="width:15%">#</th>
                  <th>Dibuat</th>
                  <th>Selesai</th>
                  <th>Status</th>
                  <th>Selisih Modal</th>
                  <th>Selisih Harga</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $item)
                  <tr class="{{ $item->status == 0 ? 'table-warning' : '' }}">
                    <td>{{ $item->id2Formatted() }}</td>
                    <td class="text-center">{{ format_datetime($item->creation_datetime) }} -
                      {{ $item->creation_user->username }}</td>
                    <td class="text-center">
                      @if ($item->status != 0)
                        {{ format_datetime($item->closing_datetime) }} - {{ $item->closing_user->username }}
                      @endif
                    </td>
                    <td class="text-center">{{ $item->statusFormatted() }}</td>
                    <td class="text-right">{{ format_number($item->total_cost) }}</td>
                    <td class="text-right">{{ format_number($item->total_price) }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        @if ($item->status == StockUpdate::STATUS_COMPLETED)
                          <a href="<?= url("/admin/stock-update/detail/$item->id") ?>" class="btn btn-default btn-sm"><i
                              class="fa fa-eye" title="View"></i></a>
                        @else
                          <a href="<?= url("/admin/stock-adjustment/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                              class="fa fa-edit" title="Edit"></i></a>
                        @endif
                        <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                          href="<?= url("/admin/stock-adjustment/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                            class="fa fa-trash" title="Hapus"></i></a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-center text-muted font-italic" colspan="7">Tidak ada rekaman untuk ditampilkan.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection
