<?php
use App\Models\Product;
?>
@extends('admin._layouts.default', [
    'title' => 'Produk',
    'menu_active' => 'inventory',
    'nav_active' => 'product',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/product/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="accordion" id="filterBox">
    <div class="card">
      <form mmethod="GET" action="?">
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
                <label for="type">Jenis Produk:</label>
                <select class="custom-select select2 form-control" id="type" name="type">
                  <option value="-1" <?= $filter['type'] == -1 ? 'selected' : '' ?>>Semua</option>
                  <option value="{{ Product::NON_STOCKED }}"
                    {{ $filter['type'] == Product::NON_STOCKED ? 'selected' : '' }}>
                    {{ Product::formatType(Product::NON_STOCKED) }}</option>
                  <option value="{{ Product::STOCKED }}" {{ $filter['type'] == Product::STOCKED ? 'selected' : '' }}>
                    {{ Product::formatType(Product::STOCKED) }}</option>
                  <option value="{{ Product::SERVICE }}" {{ $filter['type'] == Product::SERVICE ? 'selected' : '' }}>
                    {{ Product::formatType(Product::SERVICE) }}</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="active">Akitf / Nonaktif:</label>
                <select class="custom-select select2 form-control" id="active" name="active">
                  <option value="-1" {{ $filter['active'] == -1 ? 'selected' : '' }}>Semua</option>
                  <option value="0" {{ $filter['active'] == 0 ? 'selected' : '' }}>Non Aktif</option>
                  <option value="1" {{ $filter['active'] == 1 ? 'selected' : '' }}>Aktif</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="category_id">Kategori:</label>
                <select class="custom-select select2 form-control" id="category_id" name="category_id">
                  <option value="-1" {{ $filter['category_id'] == -1 ? 'selected' : '' }}>Semua</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $filter['category_id'] == $category->id ? 'selected' : '' }}>
                      {{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="supplier_id">Supplier:</label>
                <select class="custom-select select2 form-control" id="supplier_id" name="supplier_id">
                  <option value="-1" {{ $filter['supplier_id'] == -1 ? 'selected' : '' }}>Semua</option>
                  @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $filter['supplier_id'] == $supplier->id ? 'selected' : '' }}>
                      {{ $supplier->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="stock_status">Status Stok:</label>
                <select class="custom-select select2 form-control" id="stock_status" name="stock_status">
                  <option value="-1" {{ $filter['stock_status'] == -1 ? 'selected' : '' }}>Semua</option>
                  <option value="0" {{ $filter['stock_status'] == 0 ? 'selected' : '' }}>Kosong</option>
                  <option value="1" {{ $filter['stock_status'] == 1 ? 'selected' : '' }}>Stok Minimum</option>
                </select>
              </div>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Terapkan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="card card-light">
    <div class="card-body">
      <form action="?">
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
      </form>
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Kategori</th>
                  <th>Stok</th>
                  <th>Satuan</th>
                  <th>Harga Beli</th>
                  <th>Harga Jual</th>
                  <th style="width:5%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $item)
                  @php $is_at_low_stock = $item->stock < $item->minimum_stock @endphp
                  <tr class="{{ $filter['active'] == -1 && !$item->active ? 'table-danger' : '' }}">
                    <td>{{ $item->idFormatted() }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{!! $item->category ? e($item->category->name) : '<i>Tanpa Kategori</i>' !!}</td>
                    <td class="text-right {{ $is_at_low_stock ? 'text-danger' : '' }}">
                      {{ format_number($item->stock) }}
                    </td>
                    <td>{{ $item->uom }}</td>
                    <td class="text-right">{{ format_number($item->cost) }}</td>
                    <td class="text-right">{{ format_number($item->price) }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        @if (!$item->deleted_at)
                          <a href="<?= url("/admin/product/detail/$item->id") ?>" class="btn btn-default btn-sm"><i
                              class="fa fa-eye" title="Rincian"></i></a>
                          <a href="<?= url("/admin/product/duplicate/$item->id") ?>" class="btn btn-default btn-sm"><i
                              class="fa fa-copy" title="Duplikat"></i></a>
                          <a href="<?= url("/admin/product/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                              class="fa fa-edit"></i></a>
                          <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                            href="<?= url("/admin/product/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                              class="fa fa-trash"></i></a>
                        @else
                          <a onclick="return confirm('Anda yakin akan memulihkan rekaman ini?')"
                            href="<?= url("/admin/product/restore/$item->id") ?>" class="btn btn-warning btn-sm"><i
                              class="fa fa-trash-arrow-up" title="Pulihkan"></i></a>
                          <a onclick="return confirm('Anda yakin akan menghapus rekaman ini selamanya?')"
                            href="<?= url("/admin/product/delete/$item->id?force=true") ?>"
                            class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr class="empty">
                    <td colspan="8">Tidak ada rekaman yang dapat
                      ditampilkan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @include('admin._components.paginator', ['items' => $items])
        </div>
      </div>
    </div>
  </div>
@endSection
