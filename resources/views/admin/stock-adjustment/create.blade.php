<?php
use App\Models\StockUpdate;
$title = 'Buat Kartu Stok';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'stock-adjustment',
    'form_action' => url('admin/stock-adjustment/create/'),
])

@section('right-menu')
  <li class="nav-item">
    <button type="submit" name="action" value="save" class="btn btn-primary mr-2"><i class="fa fa-check mr-1"></i>
      Lanjutkan</button>
    <a href="./" class="btn btn-default"><i class="fa fa-xmark mr-1"></i>Batal</a>
  </li>
@endSection

@section('content')
  <div class="card card-primary">
    <div class="card-body">
      <div class="row mt-3">
        <div class="col col-md-12">
          <p>Silahkan pilih produk yang akan dilakukan stok opname.</p>
          <div class="table-responsive">
            <table id="product-list" class="table table-sm table-bordered table-hover">
              <thead>
                <th><input id="check-all" type="checkbox" checked></th>
                <th>Produk</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Modal (Rp.)</th>
                <th>Harga (Rp.)</th>
              </thead>
              <tbody>
                @foreach ($items as $item)
                  <tr>
                    <td class="text-center"><input class="check" type="checkbox" checked
                        name="product_ids[{{ $item->id }}]">
                    </td>
                    <td>{{ $item->code }}</td>
                    <td class="text-right">{{ $item->stock }}</td>
                    <td>{{ $item->uom }}</td>
                    <td class="text-right">{{ format_number($item->cost) }}</td>
                    <td class="text-right">{{ format_number($item->price) }}</td>
                    </td>
                  </tr>
                @endforeach
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
    $(document).ready(function() {
      $check_all = $('#check-all');
      $check_all.change(function() {
        $('.check').prop('checked', $(this).prop('checked'));
      });
      $('.check').change(function() {
        if (!$(this).prop('checked')) {
          $check_all.prop('checked', false);
        }
      });
    });
  </script>
@endSection
