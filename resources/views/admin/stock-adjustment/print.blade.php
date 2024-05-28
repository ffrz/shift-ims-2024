<?php use App\Models\Setting; ?>
@extends('admin._layouts.print-invoice', [
    'title' => 'Cetak Kartu Servis #' . $item->orderId(),
])

@section('content')
  <style>
    hr {
      border: 1px solid black;
    }

    *,
    h4 {
      font-weight: bold;
    }
  </style>
  <h4>{{ Setting::value('app.business_name') }}</h4>
  <div>{{ Setting::value('app.business_address') }}</div>
  <div>{{ Setting::value('app.business_phone') }}</div>
  @for ($i = 1; $i <= 2; $i++)
    <hr>
    <div style="width:58mm">
      <div>
        #{{ $item->orderId() }} | {{ format_date($item->date_received) }}<br>
        Atas Nama: {{ $item->customer_name }} - {{ $item->customer_address }}<br>
        HP/WA: {{ $item->customer_contact }}
      </div>
      <div>
        Perangkat: {{ $item->device }} <br>
        Kelengkapan: {{ $item->equipments }} <br>
        Kendala: {{ $item->problems }} <br>
        Tindakan: {{ $item->actions }} <br>
        @if ($item->down_payment > 0)
          Uang Muka: {{ format_number($item->down_payment) }}
        @endif
        @if ($item->estimated_cost > 0)
          Biaya Perkiraan: Rp. {{ format_number($item->estimated_cost) }}
        @endif
        @if ($item->notes > 0)
          !! {{ format_number($item->notes) }}
        @endif
      </div>
    </div>
  @endfor
@endSection
