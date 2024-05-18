<?php $title = ($item->id ? 'Edit' : 'Tambah') . ' Kategori'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'product-category',
    'back_button_link' => url('/admin/product-category/'),
])

@section('content')
  <div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST"
      action="{{ url('admin/product-category/edit/' . (int) $item->id) }}">
      @csrf
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="name">Nama Kategori</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
              placeholder="Masukkan Nama Kategori" name="name" value="{{ old('name', $item->name) }}">
            @error('name')
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
