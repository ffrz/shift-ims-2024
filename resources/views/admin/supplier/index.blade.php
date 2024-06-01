@extends('admin._layouts.default', [
    'title' => 'Pemasok',
    'menu_active' => 'purchasing',
    'nav_active' => 'supplier',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/supplier/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <form action="?">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group form-inline">
                  <label class="mr-2" for="active">Status:</label>
                  <select class="form-control custom-select mr-4" name="active" id="active"
                    onchange="this.form.submit();">
                    <option value="-1">Semua</option>
                    <option value="1" {{ $filter['active'] == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $filter['active'] == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6 d-flex justify-content-end">
                <div class="form-group form-inline">
                  <label class="mr-2" for="search">Cari:</label>
                  <input type="text" class="form-control" name="search" id="search" value="{{ $filter['search'] }}"
                    autofocus placeholder="Cari pemasok">
                </div>
              </div>
            </div>
          </form>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th style="width:10%">Kode</th>
                  <th style="width:30%">Nama</th>
                  <th>No Telepon</th>
                  <th>Alamat</th>
                  <th style="width:5%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $i => $item)
                  <tr class="{{ $filter['active'] == -1 && !$item->active ? 'table-danger' : '' }}">
                    <td>{{ $item->idFormatted() }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->address }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="<?= url("/admin/supplier/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                            class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                          href="<?= url("/admin/supplier/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                            class="fa fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-center" colspan="5">Tidak ada rekaman untuk ditampilkan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @include('admin._components.paginator', ['items' => $items])
    </div>
  </div>
@endSection
