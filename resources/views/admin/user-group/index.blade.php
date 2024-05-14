@extends('admin._layouts.default', [
    'title' => 'Grup Pengguna',
    'menu_active' => 'system',
    'nav_active' => 'user-groups'
])

@section('right-menu')
  <li class="nav-item">
    <a href="<?= url('/admin/user-groups/edit/0') ?>" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
<div class="card card-light">
  @include('admin._components.card-header', ['title' => 'Grup Pengguna', 'description' => 'Daftar grup pengguna pada sistem'])
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <table class="data-table display table table-bordered table-striped table-condensed center-th"
          style="width:100%">
          <thead>
            <tr>
              <th style="width:30%">Nama Grup</th>
              <th>Deskripsi</th>
              <th style="width:5%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item) : ?>
            <tr>
              <td>{{ $item->name }}</td>
              <td>{{ $item->description }}</td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="<?= url("/admin/user-groups/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                      class="fa fa-edit"></i></a>
                  <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                    href="<?= url("/admin/user-groups/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
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
@section('footscript')
<script>
  $(function() {
    DATATABLES_OPTIONS.order = [
      [0, 'asc']
    ];
    DATATABLES_OPTIONS.columnDefs = [{
      orderable: false,
      targets: 2
    }];
    $('.data-table').DataTable(DATATABLES_OPTIONS);
  });
</script>
@endSection
