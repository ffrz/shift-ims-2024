<?php
use App\Models\StockUpdate;
$title = 'Buat Kartu Stok';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'stock-adjustment',
    'back_button_link' => url('/admin/stock-adjustment/'),
])
@section('content')
  <form method="POST" id="editor" action="{{ url('admin/stock-adjustment/create/') }}">
    @csrf
    <div class="card card-primary">
      <div class="card-body">
        <div class="row mt-3">
          <div class="col col-md-12">
            <p>Silahkan pilih produk yang stoknya akan disesuaikan lalu klik tombol simpan atau cetak.</p>
            <div>
              <div class="mb-3 mt-3 mr-2 btn-group">
                <button type="submit" name="action" value="print" class="btn btn-primary"><i
                    class="fa fa-print mr-1"></i>Cetak</button>
                <button type="submit" name="action" value="save" class="btn btn-warning"><i
                    class="fa fa-save mr-1"></i>
                  Simpan</button>
              </div>
              <a href="./" class="btn btn-default"><i class="fa fa-cancel mr-1"></i>Batal</a>
            </div>
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
  </form>
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
