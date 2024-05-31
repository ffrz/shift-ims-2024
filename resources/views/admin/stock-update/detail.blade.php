<?php
use App\Models\ServiceOrder;
$title = 'Rincian Pembaruan Stok';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'stock-update',
])
@section('right-menu')
<li class="nav-item">
  <a href="?print=1" class="btn btn-default"><i class="fa fa-print mr-1"></i>Cetak</a>
</li>
@endSection
@section('content')
  <div class="card card-primary">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <h4>Rincian {{ $item->typeFormatted() . ' #' . $item->id2Formatted() }}</h4>
          <table class="table no-border table-sm" style="width:100%">
            <p class="mt-0 mb-0">Dibuat oleh {{ $item->created_by->username }} pada {{ format_datetime($item->created_datetime) }}</p>
            @if ($item->status != 0)
              <p class="mt-0 mb-0">Ditutup oleh {{ $item->closed_by->username }} pada {{ format_datetime($item->closed_datetime) }}</p>
            @endif
            <p class="mt-0 mb-0">Status: {{ $item->statusFormatted() }}</p>
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
              @php
                $total_cost = 0;
                $total_price = 0;
              @endphp
              @foreach ($details as $detail)
                @php
                  $subtotal_cost = $detail->cost * $detail->quantity;
                  $subtotal_price = $detail->price * $detail->quantity;
                  $total_cost += $subtotal_cost;
                  $total_price += $subtotal_price;
                @endphp
                <tr>
                  <td class="text-right">{{ $detail->id }}</td>
                  <td>{{ $detail->product->code }}</td>
                  <td class="text-right">{{ format_number($detail->quantity) }}</td>
                  <td>{{ $detail->product->uom }}</td>
                  <td class="text-right">{{ format_number($subtotal_cost) }}</td>
                  <td class="text-right">{{ format_number($subtotal_price) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4">Total</th>
                <th class="text-right">{{ format_number($total_cost) }}</th>
                <th class="text-right">{{ format_number($total_price) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div> {{-- .card-body --}}
  </div>
@endSection
