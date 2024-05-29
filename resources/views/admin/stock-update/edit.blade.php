<?php
use App\Models\StockUpdate;
$title = 'Stok Opname';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'stock-adjustment',
    'back_button_link' => url('/admin/stock-adjustment/'),
])
@section('content')
  <form method="POST" id="editor" action="{{ url('admin/stock-adjustment/edit/' . (int) $item->id) }}">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
    <div class="card card-primary">
      <div class="card-body">
        <div style="font-weight:bold;font-size:60px;" class="row">
          <div class="text-right" id="total">0</div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" id="product_code_textedit" autofocus class="form-control"
                placeholder="Input Kode/Barcode">
              <div class="input-group-append">
                <button type="submit" class="btn btn-default" title="OK"> <i class="fa fa-check"></i> </button>
                <a href="#" class="btn btn-default"> <i class="fa fa-search" title="Cari Produk"></i> </a>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col col-md-12">
            <table id="product-list" class="table table-sm table-bordered table-hover">
              <thead>
                <th>No</th>
                <th>Produk</th>
                <th>Jml</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Subtotal</th>
              </thead>
              <tbody>
                <tr id="empty-item-row">
                  <td colspan="7" class="text-center text-muted"><i>Belum ada item.</i></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
@endSection
