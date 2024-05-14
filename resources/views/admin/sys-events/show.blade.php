@extends('admin._layouts.default', [
    'title' => 'Log Aktivitas',
    'menu_active' => 'system',
    'nav_active' => 'sys-events',
])

@section('content')
  <div class="card card-light">
    @csrf
    @include('admin._components.card-header', ['title' => 'Hapus Pengguna'])
    <div class="card-body">
      <h5>Rincian Log Aktivitas Pengguna</h5>
      <table class="table" style="width='100%;'">
        <tr>
          <td>#ID Aktivitas</td>
          <td>:</td>
          <td>{{ $item->id }}</td>
        </tr>
        <tr>
          <td>Waktu & Tanggal</td>
          <td>:</td>
          <td>{{ $item->datetime }}</td>
        </tr>
        <tr>
          <td>Username</td>
          <td>:</td>
          <td>{{ $item->username }}</td>
        </tr>
        <tr>
          <td>Tipe Aktivitas</td>
          <td>:</td>
          <td>{{ $item->type }}</td>
        </tr>
        <tr>
          <td>Aktivitas</td>
          <td>:</td>
          <td>{{ $item->name }}</td>
        </tr>
        <tr>
          <td>Deskripsi / Pesan</td>
          <td>:</td>
          <td>{!! $item->description !!}</td>
        </tr>
        <tr>
          <td>Data</td>
          <td>:</td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3">
            @if($item->data)
              <pre>{{ json_encode($item->data, JSON_PRETTY_PRINT) }}</pre>
            @else
              <i class="text-muted">Tidak ada data</i>
            @endif
          </td>
        </tr>
      </table>
    </div>
    <div class="card-footer">
      <div>
        <a href="{{ url('/admin/sys-events') }}" class="btn btn-default mr-2">
          <i class="fas fa-arrow-left mr-1"></i>
          Kembali
        </a>
      </div>
    </div>
  </div>
  </div>
@endsection
