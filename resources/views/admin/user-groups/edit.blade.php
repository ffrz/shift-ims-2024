<?php $title = ($group->id ? 'Edit' : 'Tambah') . ' Grup Pengguna'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'system',
    'nav_active' => 'user-groups',
    'back_button_link' => url('/admin/user-groups/'),
])

@section('content')
  <div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST" action="{{ url('admin/user-groups/edit/' . (int) $group->id) }}">
      @csrf
      @include('admin._components.card-header', ['title' => $title])
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="name">Nama Grup</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" autofocus id="name"
              placeholder="Masukkan Nama Grup" name="name" value="{{ old('name', $group->name) }}">
            @error('name')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group col-md-8">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
              placeholder="Masukkan deskripsi grup" name="description"
              value="{{ old('description', $group->description) }}">
            @error('description')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <style>
          custom-control label.acl {
            font-weight: normal;
          }
        </style>
        <div class="form-row col-md-12">
          <h4>Hak Akses</h4>
        </div>
        <div class="form-row col-md-12">
          <p class="mt-16">Maaf, saat ini fitur kustomisasi hak akses belum tersedia.</p>
        </div>
        <div class="container">
          <div class="row">
            {{-- @foreach ($acl_resources as $category => $resource) : ?>
                <div class="col">
                <div class="form-row mt-2">
                    <b>{{ $category }}</b>
            </div>
                @foreach ($resource as $name => $label) : ?>
                    <div class="form-row">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="<?= $name ?>" name="acl[<?= $name ?>]" value="1" <?= $group->acl[$name] ? 'checked="checked"' : '' ?>>
                            <label class="custom-control-label" style="font-weight:normal; white-space: nowrap;" for="<?= $name ?>"><?= $label ?></label>
                        </div>
                    </div>
                @endforeach ?>
                </div>
            @endforeach ?> --}}
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
      </div>
    </form>
  </div>
@endSection
