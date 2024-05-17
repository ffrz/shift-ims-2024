@extends('admin._layouts.default', [
    'title' => 'Pelanggan',
    'menu_active' => 'sales',
    'nav_active' => 'customer',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/customer/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="display table table-bordered table-striped table-condensed" style="width:100%">
            <thead>
              <tr>
                <th style="width:5%">Kode</th>
                <th style="width:30%">Nama</th>
                <th>No Telepon</th>
                <th>Alamat</th>
                <th style="width:5%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $i => $item) : ?>
              <tr>
                <td>{{ $item->idFormatted() }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->address }}</td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="<?= url("/admin/customer/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                        class="fa fa-edit"></i></a>
                    <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                      href="<?= url("/admin/customer/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                        class="fa fa-trash"></i></a>
                  </div>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endSection
