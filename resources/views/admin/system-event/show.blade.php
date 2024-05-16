@extends('admin._layouts.default', [
    'title' => 'Log Aktivitas',
    'menu_active' => 'system',
    'nav_active' => 'sys-events',
])

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <h5>Rincian Log Aktivitas Pengguna</h5>
      <table class="table table-sm" style="width='100%;'">
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
          <td>{{ $item->formattedType() }}</td>
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
          <td colspan="3">
            @if ($item->data)
              @if (!empty($item->data['Old Data']))
                <h5 class="mt-3">Data Sebelumnya:</h5>
                <table class="table-sm table">
                  @foreach ($item->data['Old Data'] as $key => $data)
                  <tr>
                    <td>{{ $key }}</td><td>:</td><td>{{ $data }}</td>
                  </tr>
                  @endforeach
                </table>
              @endif
              @if (!empty($item->data['New Data']))
                <h5 class="mt-3">Data Baru:</h5>
                <table class="table table-sm">
                  @foreach ($item->data['New Data'] as $key => $data)
                  <tr>
                    <td>{{ $key }}</td><td>:</td><td>{{ $data }}</td>
                  </tr>
                  @endforeach
                </table>
              @endif
            @else
              <i class="text-muted">Tidak ada data.</i>
            @endif
          </td>
        </tr>
      </table>
    </div>
    <div class="card-footer">
      <div>
        <a href="{{ url('/admin/system-event') }}" class="btn btn-default mr-2">
          <i class="fas fa-arrow-left mr-1"></i>
          Kembali
        </a>
      </div>
    </div>
  </div>
  </div>
@endsection
