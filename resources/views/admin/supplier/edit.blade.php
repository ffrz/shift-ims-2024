<?php $title = ($item->id ? 'Edit' : 'Tambah') . ' Pemasok'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'supplier',
    'form_action' => url('admin/supplier/edit/' . (int) $item->id),
])
@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a onclick="return confirm('Batalkan perubahan?')" class="btn btn-default" href="{{ url('/admin/supplier/') }}"><i
        class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection
@section('content')
  <div class="row">
    <div class="col-lg-5">
      <div class="card">
        <input type="hidden" name="id" value="{{ $item->id }}">
        <div class="card-body">
          <div class="form-group">
            <label for="id">Kode</label>
            <input type="text" readonly class="form-control" id="id" value="{{ $item->idFormatted() }}">
            @error('id')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="name">Nama Pemasok</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
              placeholder="Masukkan nama pemasok" name="name" value="{{ old('name', $item->name) }}">
            @error('name')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="phone">No. Telepon</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
              placeholder="Masukkan no telepon pemasok" name="phone" value="{{ old('phone', $item->phone) }}">
            @error('phone')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="address">Alamat</label>
            <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" cols="30"
              rows="4">{{ old('address', $item->address) }}</textarea>
            @error('address')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input " id="active" name="active" value="1"
                {{ old('active', $item->active) ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="active" title="Akun aktif dapat digunakan">Aktif</label>
            </div>
            <div class="text-muted">Pemasok aktif bisa digunakan di transaksi.</div>
          </div>
          <div class="form-group">
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
    </div>
  </div>
@endSection
