@php use Illuminate\Support\Facades\Auth; @endphp
@extends('admin._layouts.print-report', [
    'title' => 'Kartu Stok #' . $item->id2Formatted(),
])

@section('content')
  <p class="m-0">Dibuat: {{ $item->created_by->username }} |
    {{ format_datetime($item->created_datetime) }}</p>
  <p class="m-0">Dicetak: {{ Auth::user()->username }} |
    {{ format_datetime(current_datetime()) }}</p>
  <p class="m-0">Dikerjakan Oleh: ...</p>
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
@endSection
