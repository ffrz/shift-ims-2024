<?php

use App\Models\Product;

$title = ($item->id ? 'Edit' : 'Tambah') . ' Produk';

?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'produt',
    'back_button_link' => url('/admin/prouct/'),
])

@section('content')
  <div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST" action="{{ url('admin/product/edit/' . (int) $item->id) }}">
      @csrf
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="id">Kode Produk</label>
            <input type="text" class="form-control @error('code') is-invalid @enderror" id="id" readonly
              value="{{ $item->id ? $item->formattedId() : '-otomatis-' }}">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="type">Jenis</label>
            <select class="custom-select select2 form-control" id="type" name="order_status">
              <option value="-1" <?= $item->type == Product::NON_STOCKED ? 'selected' : '' ?>>Barang Non Stok</option>
              <option value="-1" <?= $item->type == Product::STOCKED ? 'selected' : '' ?>>Barang Stok</option>
              <option value="-1" <?= $item->type == Product::SERVICE ? 'selected' : '' ?>>Servis</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="code">Nama Produk</label>
            <input type="text" class="form-control @error('code') is-invalid @enderror" autofocus id="code"
              placeholder="Masukkan nama produk" name="code" value="{{ old('code', $item->code) }}">
            @error('code')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
              placeholder="Masukkan deskripsi produk" name="description"
              value="{{ old('description', $item->description) }}">
            @error('description')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="barcode">Barcode</label>
            <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode"
              placeholder="Masukkan barcode produk" name="barcode" value="{{ old('barcode', $item->barcode) }}">
            @error('barcode')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="category_id">Kategori</label>
            <select class="custom-select select2" id="category_id" name="category_id">
              <option value="-1" {{ !$item->category_id ? 'selected' : '' }}>-- Pilih Kategori --</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                  {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="supplier_id">Supplier Tetap</label>
            <select class="custom-select select2" id="supplier_id" name="supplier_id">
              <option value="-1" {{ !$item->supplier_id ? 'selected' : '' }}>-- Pilih Supplier --</option>
              @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                  {{ old('supplier_id', $item->supplier_id) == $supplier->id ? 'selected' : '' }}>
                  {{ $supplier->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="stock">Stok</label>
            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock"
              placeholder="Masukkan stok produk" name="stock" value="{{ old('stock', $item->stock) }}">
            @error('stock')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="uom">Satuan</label>
            <input type="text" class="form-control @error('uom') is-invalid @enderror" id="uom"
              placeholder="Masukkan satuan produk" name="uom" value="{{ old('uom', $item->uom) }}">
            @error('uom')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="cost">Modal / Harga Beli</label>
            <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost"
              placeholder="Masukkan modal / harga beli produk" name="cost" value="{{ old('cost', $item->cost) }}">
            @error('cost')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="price">Harga Jual</label>
            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
              placeholder="Masukkan harga jual produk" name="price" value="{{ old('price', $item->price) }}">
            @error('price')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input " id="active" name="active" value="1"
                {{ old('active', $item->active) ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="active" title="Akun aktif dapat login">Aktif</label>
            </div>
            <div class="text-muted">Produk aktif dapat dijual.</div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="notes">Catatan</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" cols="30"
              rows="4">{{ old('notes', $item->notes) }}</textarea>
            @error('notes')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
      </div>
    </form>
  </div>
@endSection
