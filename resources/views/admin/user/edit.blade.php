<?php $title = (!$user->id ? 'Tambah' : 'Edit') . ' Pengguna'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'system',
    'nav_active' => 'user',
    'form_action' => url('/admin/user/edit/' . (int) $user->id),
])
@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a onclick="return confirm('Batalkan perubahan?')" class="btn btn-default" href="{{ url('/admin/user/') }}"><i
        class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection
@section('content')
  <div class="row">
    <div class="col-lg-5">
      <div class="card card-primary">
        <input type="hidden" name="id" value="{{ (int) $user->id }}">
        <div class="card-body">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" autofocus id="username"
              placeholder="Username" name="username" {{ $user->id ? 'readonly' : '' }}
              value="{{ old('username', $user->username) }}">
            @if (!$user->id)
              <p class="text-muted">Setelah disimpan username tidak bisa diganti.</p>
              @error('username')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            @endif
          </div>
          <div class="form-group">
            <label for="fullname">Nama Lengkap</label>
            <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname"
              placeholder="Nama Lengkap" name="fullname" value="{{ old('fullname', $user->fullname) }}">
            @error('fullname')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label for="group_id">Grup Pengguna</label>
            <select class="custom-select select2" id="group_id" name="group_id">
              <option value="" {{ !$user->group_id ? 'selected' : '' }}>-- Pilih Grup Pengguna --</option>
              @foreach ($groups as $group)
                <option value="{{ $group->id }}" {{ old('group_id', $user->group_id) == $group->id ? 'selected' : '' }}
                  title="{{ $group->description }}">
                  {{ $group->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="text" class="form-control @error('password') is-invalid @enderror" id="password"
              placeholder="Kata Sandi" name="password" value="{{ old('password') }}">
            <div class="text-muted">Isi untuk mengganti kata sandi.</div>
            @error('password')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input " id="active" name="is_active" value="1"
                {{ old('is_active', $user->is_active) ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="active" title="Akun aktif dapat login">Aktif</label>
            </div>
            <div class="text-muted">Akun aktif dapat login.</div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input " id="is_admin" name="is_admin" value="1"
                {{ old('is_admin', $user->is_admin) ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="is_admin" title="Akun pengguna pengelola">Administrator</label>
            </div>
            <p class="text-muted">Akun administrator memiliki hak akses penuh pada sistem.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection
