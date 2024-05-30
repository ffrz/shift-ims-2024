<?php $title = ($item->id ? 'Edit' : 'Tambah') . ' Grup Pengguna'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'system',
    'nav_active' => 'user-group',
    'form_action' => url('admin/user-group/edit/' . (int) $item->id),
])
@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a onclick="return confirm('Batalkan perubahan?')" class="btn btn-default"
      href="{{ url('/admin/user-group/') }}"><i class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection
@section('content')
  <div class="row">
    <div class="col-lg-5">
      <div class="card card-primary">
        <div class="card-body">
          <div class="form-group">
            <label for="name">Nama Grup</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
              placeholder="Masukkan Nama Grup" name="name" value="{{ old('name', $item->name) }}">
            @error('name')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
              placeholder="Masukkan deskripsi grup" name="description"
              value="{{ old('description', $item->description) }}">
            @error('description')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <style>
            custom-control label.acl {
              font-weight: normal;
            }
          </style>
          <div class="form-row col-md-12 mt-4">
            <h5>Hak Akses Grup</h5>
          </div>
          @foreach ($resources as $category => $resource)
            <div style="border: 1px solid #ddd;border-radius:5px;" class="p-2 mt-2 mb-2">
              <h5 class="mb-0">{{ $category }}</h5>
              @foreach ($resource as $name => $label)
                @if (is_array($label))
                  <h6 class="mt-3 mb-0">{{ $name }}</h6>
                  <div class="d-flex flex-row flex-wrap">
                    @foreach ($label as $subname => $sublabel)
                      <div class="mr-3 custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="{{ $subname }}"
                          name="acl[{{ $subname }}]" value="1"
                          @if (isset($item->acl()[$subname]) && $item->acl()[$subname] == true) {{ 'checked="checked"' }} @endif>
                        <label class="custom-control-label" style="font-weight:normal; white-space: nowrap;"
                          for="{{ $subname }}">{{ $sublabel }}</label>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="{{ $name }}"
                      name="acl[{{ $name }}]" value="1"
                      @if (isset($item->acl()[$name]) && $item->acl()[$name] == true) {{ 'checked="checked"' }} @endif>
                    <label class="custom-control-label" style="font-weight:normal; white-space: nowrap;"
                      for="{{ $name }}">{{ $label }}</label>
                  </div>
                @endif
              @endforeach
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endSection
