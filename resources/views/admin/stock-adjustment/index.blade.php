<?php use App\Models\SalesOrder; ?>

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
                <th>Nilai Modal</th>
                <th>Nilai Harga Jual</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($items as $item)
                <tr>
                  <td>{{ $item->idFormatted() }}</td>
                  <td>{{ $item->date }}</td>
                  <td>{{ $item->customer ? $item->customer->name : '' }}</td>
                  <td>{{ $item->total }}</td>
                  <td>{{ $item->total_receivable }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="<?= url("/admin/stock-adjustment/detail/$item->id") ?>" class="btn btn-default btn-sm"><i
                          class="fa fa-eye" title="View"></i></a>
                      <a href="<?= url("/admin/stock-adjustment/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                          class="fa fa-edit" title="Edit"></i></a>
                      <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                        href="<?= url("/admin/stock-adjustment/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                          class="fa fa-trash" title="Hapus"></i></a>
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
