<?php use App\Models\SalesOrder; ?>

@extends('admin._layouts.default', [
    'title' => 'Riwayat Pembaruan Stok',
    'menu_active' => 'inventory',
    'nav_active' => 'stock-update',
])

@section('right-menu')
  <li class="nav-item">
    <a href="<?= url('/admin/stock-update/create') ?>" class="btn plus-btn btn-primary mr-2" title="Baru"><i
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
                <th>Jenis</th>
                <th>Total Modal</th>
                <th>Total Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($items as $item)
                <tr>
                  <td>{{ $item->id2Formatted() }}</td>
                  <td class="text-center">{{ format_date($item->date) }}</td>
                  <td class="text-center">{{ $item->typeFormatted() }}</td>
                  <td class="text-right">{{ format_number($item->total_cost) }}</td>
                  <td class="text-right">{{ format_number($item->total_price) }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="<?= url("/admin/stock-update-history/detail/$item->id") ?>" class="btn btn-default btn-sm"><i
                          class="fa fa-eye" title="View"></i></a>
                      {{-- <a href="<?= url("/admin/stock-update-history/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                          class="fa fa-edit" title="Edit"></i></a>
                      <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                        href="<?= url("/admin/stock-update-history/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                          class="fa fa-trash" title="Hapus"></i></a> --}}
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="text-center text-muted font-italic" colspan="5">Tidak ada rekaman untuk ditampilkan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
@endSection
