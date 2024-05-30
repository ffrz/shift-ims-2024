<?php $title = ($item->id ? 'Edit' : 'Tambah') . ' Pelanggan'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'sales',
    'nav_active' => 'customer',
    'form_action' => url('admin/customer/edit/' . (int) $item->id),
])

@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a onclick="return confirm('Batalkan perubahan?')" class="btn btn-default" href="{{ url('/admin/customer/') }}"><i
        class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection

@section('content')
  <div class="row">
    <div class="col-lg-4">
      <div class="card card-primary">
        <div class="card-body">
          <div class="form-group">
            <label for="id">Kode Pelanggan</label>
            <input type="text" readonly class="form-control" id="id" value="{{ $item->idFormatted() }}">
            @error('id')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="name">Nama Pelanggan</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
              placeholder="Masukkan nama pelanggan" name="name" value="{{ old('name', $item->name) }}">
            @error('name')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="phone">No. Telepon</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
              placeholder="Masukkan no telepon pelanggan" name="phone" value="{{ old('phone', $item->phone) }}">
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
            <div class="text-muted">Pelanggan tidak aktif disembunyikan di transaksi.</div>
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
