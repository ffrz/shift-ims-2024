@extends('admin._layouts.default', [
    'title' => 'Kategori Biaya',
    'menu_active' => 'cost',
    'nav_active' => 'cost-category',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/cost-category/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>Kategori</th>
                  <th style="width:5%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $item)
                  <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="<?= url("/admin/cost-category/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                            class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                          href="<?= url("/admin/cost-category/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                            class="fa fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr class="empty">
                    <td colspan="3">Tidak ada rekaman untuk ditampilkan.</td>
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
