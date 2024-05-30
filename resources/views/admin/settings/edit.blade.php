<?php use App\Models\Setting; ?>

@extends('admin._layouts.default', [
    'title' => 'Pengaturan',
    'menu_active' => 'system',
    'nav_active' => 'settings',
    'form_action' => url('admin/settings/save'),
])

@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
  </li>
@endSection

@section('content')
  <div class="row">
    <div class="col-lg-6">
      <div class="card card-light">
        <div class="card-header" style="padding:0;border-bottom:0;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="company-profile-tab" data-toggle="tab" href="#company-profile" role="tab"
                aria-controls="company-profile" aria-selected="false">Profil Perusahaan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="inventory-tab" data-toggle="tab" href="#inventory" role="tab"
                aria-controls="inventory" aria-selected="true">Inventori</a>
            </li>
          </ul>
        </div>
        <div class="tab-content card-body" id="myTabContent">
          <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="inv-show-desc" name="inv_show_description"
                  {{ Setting::value('inv.show_description') ? 'checked' : '' }}>
                <label class="custom-control-label" for="inv-show-desc">Tampilkan deskripsi produk</label>
              </div>
            </div>
            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="inv-show-barcode" name="inv_show_barcode"
                  {{ Setting::value('inv.show_barcode') ? 'checked' : '' }}>
                <label class="custom-control-label" for="inv-show-barcode">Tampilkan barcode produk</label>
              </div>
            </div>
          </div>
          <div class="tab-pane fade show active" id="company-profile" role="tabpanel"
            aria-labelledby="company-profile-tab">
            <div class="form-horizontal">
              <div class="form-group">
                <label for="company_name">Nama Perusahaan</label>
                <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name"
                  placeholder="Nama Usaha" name="company_name" value="{{ Setting::value('company.name', 'Toko Saya') }}">
                @error('company_name')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="company_owner">Nama Pemilik</label>
                <input type="text" class="form-control @error('company_owner') is-invalid @enderror" id="company_owner"
                  placeholder="Nama Pemilik" name="company_owner" value="{{ Setting::value('company.owner') }}">
                @error('company_owner')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="company_phone">No. Telepon</label>
                <input type="text" class="form-control @error('company_phone') is-invalid @enderror" id="company_phone"
                  placeholder="Nomor Telepon / HP" name="company_phone" value="{{ Setting::value('company.phone') }}">
                @error('company_phone')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="company_address">Alamat</label>
                <textarea class="form-control" id="company_address" name="company_address">{{ Setting::value('company.address') }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection
