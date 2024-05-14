@extends('admin._layouts.default', [
    'title' => 'Log Aktivitas',
    'menu_active' => 'system',
    'nav_active' => 'sys-events',
])

@section('right-menu')
  {{-- <li class="nav-item">
    <a href="{{ url('/admin/users/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i class="fa fa-plus"></i></a>
  </li> --}}
@endsection

@section('content')
  <div class="card card-light">
    @include('admin._components.card-header', [
        'title' => 'Pengguna',
        'description' => 'Daftar pengguna sistem',
    ])
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <form method="POST" action="{{ url('admin/sys-events/delete') }}" onsubmit="return confirm('Hapus rekaman?')">
            @csrf
            <table class="data-table display table table-bordered table-striped table-condensed center-th"
              style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Waktu</th>
                  <th>Pengguna</th>
                  <th>Tipe</th>
                  <th>Aktivitas</th>
                  <th>Deskripsi</th>
                  <th class="text-center" style="max-width:10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($items as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->datetime }}</td>
                    <td>{{ $item->username }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <a href="{{ url("/admin/sys-events/show/$item->id") }}" class="btn btn-default btn-sm"
                          title="Lihat"><i class="fa fa-eye"></i></a>
                        <button href="{{ url("/admin/sys-events/delete") }}" class="btn btn-danger btn-sm"
                          type="submit" title="Hapus"><i class="fa fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('footscript')
  <script>
    $(function() {
      DATATABLES_OPTIONS.order = [
        [0, 'desc']
      ];
      DATATABLES_OPTIONS.columnDefs = [{
        orderable: false,
        targets: 6
      }];
      $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
  </script>
@endsection
