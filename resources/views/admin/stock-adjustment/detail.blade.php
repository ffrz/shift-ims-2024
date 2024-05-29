<?php
use App\Models\ServiceOrder;
$title = 'Rincian Stok Opname';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'stock-adjustment',
    'back_button_link' => url('/admin/stock-adjustment/'),
])

@section('content')
  <div class="card card-primary">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
         <table class="table table-sm no-border" style="width:100%">
            <tr>
              <td style="width:5%">Kode</td>
              <td style="width:2%">:</td>
              <td>{{ $item->id2Formatted() }}</td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>:</td>
              <td>{{ format_date($item->date) }}</td>
            </tr>
            <tr>
              <td>Tanggal Dibuat</td>
              <td>:</td>
              <td>{{ format_datetime($item->creation_datetime) }}</td>
            </tr>
            <tr>
              <td>Status</td>
              <td>:</td>
              <td>{{ $item->statusFormatted() }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-condensed center-th table-sm" style="width:100%">
            <thead>
              <th>No</th>
              <th>Produk</th>
              <th>Selisih</th>
              <th>Satuan</th>
              <th>Selisih Modal</th>
              <th>Selisih Harga Jual</th>
            </thead>
            <tbody>
              @foreach ($details as $detail)
                <tr>
                  <td class="text-right">{{ $detail->id }}</td>
                  <td>{{ $detail->product->code }}</td>
                  <td class="text-right">{{ format_number($detail->quantity) }}</td>
                  <td>{{ $detail->product->uom }}</td>
                  <td class="text-right">{{ format_number($detail->cost * $detail->quantity) }}</td>
                  <td class="text-right">{{ format_number($detail->price * $detail->quantity) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div> {{-- .card-body --}}
  </div>
@endSection
