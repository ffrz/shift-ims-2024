@extends('admin._layouts.default', [
    'title' => 'Profil Saya',
    'nav_active' => 'profile',
    'form_action' => url('admin/user/profile'),
])

@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
  </li>
@endSection

@section('content')
  <div class="row">
    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-1">Info Akun</h4>
          <hr class="mb-3 mt-0">
          <div class="form-group">
            <label for="username">ID Pengguna</label>
            <input type="text" class="form-control @error('username') 'is-invalid' @enderror" id="username" readonly
              value="{{ $user->username }}">
          </div>
          <div class="form-group">
            <label for="fullname">Nama Lengkap</label>
            <input type="text" class="form-control @error('fullname') 'is-invalid' @enderror" autofocus id="fullname"
              placeholder="Nama Lengkap" name="fullname" value="{{ old('fullname', $user->fullname) }}">
            @error('fullname')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="group_id">Grup Pengguna</label>
            <input type="text" class="form-control" id="group" name="group"
              value="{{ $user->group ? $user->group->name : '' }}" readonly>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input disabled type="checkbox" class="custom-control-input " id="active"
                {{ $user->is_active ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="active">Akun Aktif</label>
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input disabled type="checkbox" class="custom-control-input " id="is_admin"
                {{ $user->is_admin ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="is_admin">Hak Akses Administrator</label>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="mb-1">Ganti Kata Sandi</h4>
          <hr class="mb-3 mt-0">
          <div class="form-group">
            <label for="password">Kata Sandi Baru</label>
            <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
            <p class="text-muted">Hanya diisi apabila anda ingin mengganti kata sandi.</p>
            @error('password')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label for="password_confirmation">Ulangi Kata Sandi Baru</label>
            <input type="password" class="form-control @error('password_confirmation') 'is-invalid' @enderror"
              id="password_confirmation" placeholder="Kata Sandi" name="password_confirmation"
              value="{{ old('password_confirmation') }}">
            @error('password_confirmation')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="mb-1">Verifikasi Akun</h4>
          <hr class="mb-3 mt-0">
          <div class="form-group">
            <label for="current_password">Kata Sandi Saat Ini</label>
            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
              id="current_password" placeholder="Kata Sandi sekarang" name="current_password"
              value="{{ old('current_password') }}">
            @error('current_password')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('footscript')
  <script>
    $(document).ready(function(){
      $('.is-invalid').focus();
    });
  </script>
@endsection
