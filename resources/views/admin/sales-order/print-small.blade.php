@php
  use App\Models\Setting;
  $title = '#' . $item->id2Formatted();
@endphp
@extends('admin._layouts.print-receipt-58')
@section('content')
  <div class="no-print text-center">
      <a class="btn" href="{{ url('admin/sales-order/create') }}">+ Order Baru</a>
      <a class="btn" href="{{ url('admin/sales-order') }}">&leftarrow; List Order Penjualan</a>
      <a class="btn" href="{{ url('admin/sales-order/detail/' . $item->id) }}">&leftarrow; Rincian</a>
      <br><br><br>
  </div>
  <table style="width:100%">
    <tr>
      <td class="text-center">
        <b>
          {{ Setting::value('company.name') }}<br>
          @if (!empty(Setting::value('company.headline')))
            {{ Setting::value('company.headline') }}<br>
          @endif
        </b>
        @if (!empty(Setting::value('company.address')))
          {{ Setting::value('company.address') }}<br>
        @endif
        @if (!empty(Setting::value('company.phone')))
          Tlp. {{ Setting::value('company.phone') }}<br>
        @endif
        <hr>
      </td>
    </tr>
    <tr>
      <td class="text-center">
        <b>{{ $title }}</b><br>
        @if (!empty($item->party_name))
          Yth. {{ $item->party_name }}<br>
          {{ $item->party_address }}
        @endif
      </td>
    </tr>
    <tr>
      <td>
        <hr>
        @php
          $total_cost = 0;
          $total_price = 0;
        @endphp
        @foreach ($details as $i => $detail)
          @php
            $subtotal_cost = $detail->cost * $detail->quantity;
            $subtotal_price = $detail->price * $detail->quantity;
            $total_cost += $subtotal_cost;
            $total_price += $subtotal_price;
          @endphp
          <div class="text-center">
            {{ $i + 1 }}) {{ $detail->product->code }}<br>
            {{ format_number(abs($detail->quantity)) }} {{ $detail->product->uom }} X Rp.
            {{ format_number($detail->price) }} <br>
            = Rp. {{ format_number(abs($subtotal_price)) }}
          </div>
        @endforeach
        <hr>
      </td>
    </tr>
    <tr>
      <td class="text-center">
        <b>Total: Rp. {{ format_number(abs($total_price)) }}</b>
      </td>
    </tr>
    <tr>
      <td>
        <hr>
        <div><i>{{ Setting::value('pos.receipt.footnote', 'Terima kasih sudah berbelanja di toko kami.') }}</i></div>
        <hr>
        <div class="text-center">
          Dicetak oleh {{ Auth::user()->username }} | {{ format_datetime(current_datetime()) }} -
          {{ env('APP_NAME') . ' v' . env('APP_VERSION_STR') }}
        </div>
      </td>
    </tr>
  </table>
@endSection
