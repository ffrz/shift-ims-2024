<?php use App\Models\SalesOrder; ?>

@extends('admin._layouts.default', [
    'title' => 'Riwayat Pembaruan Stok',
    'menu_active' => 'inventory',
    'nav_active' => 'stock-update',
])

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="table-responsive">
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>#</th>
                <th style="width:20%">Dibuat</th>
                <th style="width:20%">Selesai</th>
                <th style="width:10%">Jenis</th>
                <th style="width:10%">Selisih Modal</th>
                <th style="width:10%">Selisih Harga</th>
                <th style="width:5%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($items as $item)
                <tr>
                  <td>{{ $item->id2Formatted() }}</td>
                  <td class="text-center">{{ format_datetime($item->creation_datetime) }} -
                    {{ $item->creation_user->username }}</td>
                  <td class="text-center">{{ format_datetime($item->closing_datetime) }} -
                    {{ $item->closing_user->username }}</td>
                  <td class="text-center">{{ $item->typeFormatted() }}</td>
                  <td class="text-right">{{ format_number($item->total_cost) }}</td>
                  <td class="text-right">{{ format_number($item->total_price) }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="<?= url("/admin/stock-update/detail/$item->id") ?>" class="btn btn-default btn-sm"><i
                          class="fa fa-eye" title="View"></i></a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="text-center text-muted font-italic" colspan="7">Tidak ada rekaman untuk ditampilkan.</td>
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
