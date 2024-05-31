<?php use App\Models\Setting; ?>
@extends('admin._layouts.print-report', [
    'title' => 'Cetak Kartu Stok #' . $item->id2Formatted(),
])

@section('content')
  <div class="page">
    <h3 class="m-0">Kartu Stok Opname</h3>
    <h5 class="m-0">{{ Setting::value('app.business_name', 'Toko Saya') }}</h5>
    <div>{{ Setting::value('app.business_address', 'Jl. Sana Sini No. 123') }} -
      {{ Setting::value('app.business_phone', '081234567890') }}</div>
    <div class="mt-2">Dibuat oleh <b>{{ $item->created_by->username }}</b> pada {{ format_datetime($item->created_datetime) }}</div>
    <div>Dicetak oleh <b>{{ Auth::user()->username }}</b> pada {{ format_datetime(date('Y-m-d H:i:s')) }}</div>
    <table class="report-table mt-3">
      <thead>
        <th>No</th>
        <th>Produk</th>
        <th>Satuan</th>
        <th>Stok Tercatat</th>
        <th>Stok Sebenarnya</th>
      </thead>
      @foreach ($details as $num => $detail)
        <tbody>
          <td class="text-right">{{ $num + 1 }}</td>
          <td>{{ $detail->product->idFormatted() . ' - ' . $detail->product->code }}</td>
          <td class="text-center">{{ $detail->product->uom }}</td>
          <td class="text-right">{{ $detail->product->stock }}</td>
          <td></td>
        </tbody>
      @endforeach
    </table>
    <div class="mt-3">Dicatat Oleh: ...</div>
  </div>
@endSection
