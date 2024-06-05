@php
  use App\Models\Setting;
  $title = '#' . $item->id2Formatted();
@endphp
@extends('admin._layouts.print-receipt-a4')
@section('content')
  <div class="no-print text-center">
    <a class="btn" href="{{ url('admin/sales-order/create') }}">+ Order Baru</a>
    <a class="btn" href="{{ url('admin/sales-order') }}">&leftarrow; List Order Penjualan</a>
    <a class="btn" href="{{ url('admin/sales-order/detail/' . $item->id) }}">&leftarrow; Rincian</a>
    <br><br><br>
  </div>

  <table style="width:100%">
    <tr>
      <td>
        <h5 class="m-0">{{ Setting::value('company.name') }}</h5>
        @if (!empty(Setting::value('company.headline')))
          <h6 class="m-0">{{ Setting::value('company.headline') }}</h6>
        @endif
        @if (!empty(Setting::value('company.address')))
          <i>{{ Setting::value('company.address') }}<br>
        @endif
        @if (!empty(Setting::value('company.phone')))
          HP/WA {{ Setting::value('company.phone') }}<br>
        @endif
        @if (!empty(Setting::value('company.website')))
          HP/WA {{ Setting::value('company.website') }}
        @endif
        </i>
      </td>
      <td style="width:40%;border-left:1px solid #888;padding-left:10px;">
        <div><b>{{ $title }}</b></div>
        <table>
          <tr>
            <td>Pelanggan</td>
            <td>:</td>
            <td>{{ $item->party_name }}</td>
          </tr>
          <tr>
            <td>No Telepon</td>
            <td>:</td>
            <td>{{ $item->party_phone }}</td>
          </tr>
          <tr>
            <td>Aalamat</td>
            <td>:</td>
            <td>{{ $item->party_address }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <table class="table table-bordered table-striped table-condensed center-th table-sm" style="width:100%">
    <thead>
      <th style="width:1%">No</th>
      <th>Produk</th>
      <th style="width:3cm">Qty</th>
      <th style="width:3cm">Harga (Rp)</th>
      <th style="width:3cm">Subtotal (Rp)</th>
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
          <td class="text-right">{{ format_number(abs($detail->quantity)) }} {{ $detail->product->uom }}</td>
          <td class="text-right">{{ format_number($detail->price) }}</td>
          <td class="text-right">{{ format_number(abs($subtotal_price)) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th colspan="4" class="text-right">Total</th>
        <th class="text-right">{{ format_number(abs($total_price)) }}</th>
      </tr>
    </tfoot>
  </table>
  <br>
  <table style="width:100%;">
    <tr>
      <td style="font-style:italic;">
        Catatan:<br>
        - Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.<br>
        - Garansi hangus jika segel rusak, human error atau force majeure.<br>
        <br>
        <small>Dicetak oleh {{ Auth::user()->username }} | {{ format_datetime(current_datetime()) }} -
          {{ env('APP_NAME') . ' v' . env('APP_VERSION_STR') }}</small>
      </td>
      <td style="width:4cm;text-align:center;">
        Hormat Kami,<br><br><br><br>
        {{ Auth::user()->fullname }}
      </td>
    </tr>
  </table>
@endSection
