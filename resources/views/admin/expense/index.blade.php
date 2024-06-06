@extends('admin._layouts.default', [
    'title' => 'Pengeluaran',
    'menu_active' => 'expense',
    'nav_active' => 'expense',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/expense/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <style>
    .form-inline .select2 {
      width: auto !important;
    }
  </style>
  <div class="card card-light">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <form action="?">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group form-inline">
                  <select class="form-control custom-select mr-2" name="y" onchange="this.form.submit();">
                    @foreach ($years as $year)
                      <option value="{{ $year }}" {{ $filter['y'] == $year ? 'selected' : '' }}>
                        {{ $year }}</option>
                    @endforeach
                  </select>
                  <select class="form-control custom-select mr-2" name="m" onchange="this.form.submit();">
                    <option value="" {{ $filter['m'] == 0 ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach ($months as $month => $name)
                      <option value="{{ $month }}" {{ $filter['m'] == $month ? 'selected' : '' }}>
                        {{ $name }}</option>
                    @endforeach
                  </select>
                  <select class="form-control custom-select select2 mr-4" name="category_id" id="category_id"
                    onchange="this.form.submit();">
                    <option value="" {{ $filter['category_id'] == -1 ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}"
                        {{ $filter['category_id'] == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6 d-flex justify-content-end">
                <div class="form-group form-inline">
                  <label class="mr-2" for="search">Cari:</label>
                  <input type="text" class="form-control" name="search" id="search"
                    value="{{ $filter['search'] }}" placeholder="Cari">
                </div>
              </div>
            </div>
          </form>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th style="width:5%">No</th>
                  <th style="width:10%">Tanggal</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Jumlah</th>
                  <th style="width:5%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $item)
                  <tr>
                    <td class="text-nowrap">{{ $item->idFormatted() }}</td>
                    <td class="text-nowrap">{{ format_date($item->date) }}</td>
                    <td class="text-nowrap">{{ $item->category ? $item->category->name : 'Tanpa Kategori' }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ format_number($item->amount) }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="<?= url("/admin/expense/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                            class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                          href="<?= url("/admin/expense/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                            class="fa fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr class="empty">
                    <td colspan="6">Tidak ada rekaman untuk ditampilkan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection

@section('footscript')
  <script>
    $('.select2').select2();
  </script>
@endsection
