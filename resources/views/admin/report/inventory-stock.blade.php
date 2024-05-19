<?php use App\Models\Setting; ?>
@extends('admin._layouts.print-report', [
    'title' => 'Laporan Stok Inventori',
])

@section('content')
  <h5 class="text-center">LAPORAN STOK INVENTORI</h5>
  <h5 class="text-center">{{ Setting::value('app.business_name') }}</h5>
  <h6 class="text-center">Per Tanggal: {{ date('d-m-Y') }}</h6>
  <table class="report-table">
    <thead style="background:#08e;color:#fff;">
      <th>No</th>
      <th>Kode</th>
      <th>Nama Produk</th>
      <th>Stok</th>
      <th>Satuan</th>
      <th>Modal</th>
      <th>Harga Jual</th>
      <th>Jml Modal</th>
      <th>Jml Harga</th>
    </thead>
    <tbody>
      <?php $sum_cost = 0;
      $sum_price = 0; ?>
      @foreach ($items as $num => $item)
        <tr>
          <td class="text-right">{{ $num + 1 }}</td>
          <td>{{ $item->idFormatted() }}</td>
          <td>{{ $item->code }}</td>
          <td class="text-right">{{ format_number($item->stock) }}</td>
          <td>{{ $item->uom }}</td>
          <td class="text-right">{{ format_number($item->cost) }}</td>
          <td class="text-right">{{ format_number($item->price) }}</td>
          <td class="text-right">{{ format_number($sub_cost = $item->stock * $item->cost) }}</td>
          <td class="text-right">{{ format_number($sub_price = $item->stock * $item->price) }}</td>
          <?php $sum_cost += $sub_cost; ?>
          <?php $sum_price += $sub_price; ?>
        </tr>
      @endforeach
    </tbody>
    <tfoot style="background:#08e;color:#fff;">
      <th colspan="7"></th>
      <th class="text-right">{{ format_number($sum_cost) }}</th>
      <th class="text-right">{{ format_number($sum_price) }}</th>
    </tfoot>
  </table>
@endSection
