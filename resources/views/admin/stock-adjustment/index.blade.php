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
          <table class="display table table-bordered table-striped table-condensed center-th table-sm" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Nilai Modal</th>
                <th>Nilai Harga Jual</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($items as $item)
                <tr>
                  <td>{{ $item->id2Formatted() }}</td>
                  <td class="text-center">{{ format_date($item->date) }}</td>
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
                  <td class="text-center text-muted font-italic" colspan="6">Tidak ada rekaman untuk ditampilkan.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endSection
