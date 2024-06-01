<?php $title = ($item->id ? 'Edit' : 'Tambah') . ' Kategori'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'product-category',
    'form_action' => url('admin/product-category/edit/' . (int) $item->id),
])

@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a onclick="return confirm('Batalkan perubahan?')" class="btn btn-default" href="{{ url('/admin/product-category/') }}"><i
        class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection

@section('content')
  <div class="row">
    <div class="col-md-4">
      <div class="card card-primary">
        <div class="card-body">
          <div class="form-group">
            <label for="name">Nama Kategori</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
              placeholder="Masukkan nama kategori" name="name" value="{{ old('name', $item->name) }}">
            @error('name')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" autofocus id="description"
              placeholder="Uraikan dengan deskripsi" name="description" value="{{ old('description', $item->description) }}">
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection
