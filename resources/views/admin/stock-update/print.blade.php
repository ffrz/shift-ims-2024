@extends('admin._layouts.print-report', [
    'title' => 'Rincian ' . $item->typeFormatted() . ' #' . $item->id2Formatted(),
])
@section('content')
  <p class="m-0">Dibuat oleh {{ $item->created_by->username }} pada
    {{ format_datetime($item->created_datetime) }}</p>
  @if ($item->status != 0)
    <p class="m-0">Ditutup oleh {{ $item->closed_by->username }} pada
      {{ format_datetime($item->closed_datetime) }}</p>
  @endif
  <p class="m-0">Status: {{ $item->statusFormatted() }}</p>
  <table class="mt-3 table table-bordered table-striped table-condensed center-th table-sm" style="width:100%">
    <thead>
      <th>No</th>
      <th>Produk</th>
      <th>Satuan</th>
      <th>Stok Lama</th>
      <th>Stok Baru</th>
      <th>Selisih</th>
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
        <tr class="{{ $detail->quantity > 0 ? 'text-success' : ($detail->quantity < 0 ? 'text-danger' : '') }}">
          <td class="text-right">{{ $detail->id }}</td>
          <td>{{ $detail->product->code }}</td>
          <td>{{ $detail->product->uom }}</td>
          <td class="text-right">{{ format_number($detail->stock_before) }}</td>
          <td class="text-right">{{ format_number($detail->stock_before + $detail->quantity) }}</td>
          <td class="text-right">{{ ($detail->quantity > 0 ? '+' : '') . format_number($detail->quantity) }}</td>
          <td class="text-right">{{ format_number($subtotal_cost) }}</td>
          <td class="text-right">{{ format_number($subtotal_price) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th colspan="6" class="text-right">Total</th>
        <th class="text-right">{{ format_number($total_cost) }}</th>
        <th class="text-right">{{ format_number($total_price) }}</th>
      </tr>
    </tfoot>
  </table>
@endSection
