@extends('admin._layouts.default', [
    'title' => 'Produk',
    'menu_active' => 'inventory',
    'nav_active' => 'product'
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/product/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
<div class="card card-light">
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <table class="display table table-bordered table-striped table-condensed"
          style="width:100%">
          <thead>
            <tr>
              <th style="width:30%">Nama</th>
              <th>Jenis</th>
              <th style="width:5%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item) : ?>
            <tr>
              <td>{{ $item->code }}</td>
              <td>{{ $item->description }}</td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="<?= url("/admin/product/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                      class="fa fa-edit"></i></a>
                  <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                    href="<?= url("/admin/product/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
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