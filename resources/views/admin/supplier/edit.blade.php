<?php $title = ($item->id ? 'Edit' : 'Tambah') . ' Pemasok'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'supplier',
    'back_button_link' => url('/admin/supplier/'),
])

@section('content')
  <div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST" action="{{ url('admin/supplier/edit/' . (int) $item->id) }}">
      @csrf
      <input type="hidden" name="id" value="{{ $item->id }}">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="id">Kode</label>
            <input type="text" readonly class="form-control" id="id" value="{{ $item->id ? $item->idFormatted() : '--otomatis terisi--' }}">
            @error('id')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="name">Nama Pemasok</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
              placeholder="Masukkan nama pemasok" name="name" value="{{ old('name', $item->name) }}">
            @error('name')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="phone">No. Telepon</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
              placeholder="Masukkan no telepon pemasok" name="phone" value="{{ old('phone', $item->phone) }}">
            @error('phone')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="address">Alamat</label>
            <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" cols="30"
              rows="4">{{ old('address', $item->address) }}</textarea>
            @error('address')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="col-sm-43">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input " id="active" name="active" value="1"
                {{ old('active', $item->active) ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="active" title="Akun aktif dapat digunakan">Aktif</label>
            </div>
            <div class="text-muted">Pemasok aktif bisa digunakan di transaksi.</div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
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
