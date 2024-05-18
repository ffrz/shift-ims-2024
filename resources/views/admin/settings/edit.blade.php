@extends('admin._layouts.default', [
    'title' => 'Pengaturan',
    'menu_active' => 'system',
    'nav_active' => 'settings',
])
@section('content')
  <div class="card card-light">
    <form class="form-horizontal quick-form" method="POST" action="{{ url('admin/settings/save') }}">
      @csrf
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="business_name">Nama Usaha</label>
            <input type="text" autofocus class="form-control @error('business_name') is-invalid @enderror"
              id="business_name" placeholder="Nama Usaha" name="business_name" value="{{ $data['business_name'] }}">
            @error('business_name')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="business_phone">No. Telepon</label>
            <input type="text" autofocus class="form-control @error('business_phone') is-invalid @enderror"
              id="business_phone" placeholder="Nomor Telepon / HP" name="business_phone"
              value="{{ $data['business_phone'] }}">
            @error('business_phone')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="business_address">Alamat</label>
            <textarea class="form-control" id="business_address" name="business_address">{{ $data['business_address'] }}</textarea>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="business_owner">Nama Pemilik</label>
            <input type="text" autofocus class="form-control @error('business_owner') is-invalid @enderror"
              id="business_owner" placeholder="Nama Pemilik" name="business_owner" value="{{ $data['business_owner'] }}">
            @error('business_owner')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i> Simpan</button>
      </div>
    </form>
  </div>
@endSection
