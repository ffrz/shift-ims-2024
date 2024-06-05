@php
  use App\Models\Setting;
  $title = 'Kartu Servis: #' . $item->idFormatted();
@endphp
@extends('admin._layouts.print-service-note', ['title' => $title])

@section('content')
  <div class="no-print text-center">
    <br>
    <a class="btn" href="{{ url('admin/service-order/edit') }}">+ Order Baru</a>
    <a class="btn" href="{{ url('admin/service-order') }}">&leftarrow; List Order Servis</a>
    <a class="btn" href="{{ url('admin/service-order/detail/' . $item->id) }}">&leftarrow; Rincian</a>
    <br><br><br>
  </div>
  <table style="width:100%">
    <tr>
      <td>
        <img src="{{ Setting::value('company.logo_path', url('/dist/img/logo.png')) }}" alt="" width="42"
          height="42">
      </td>
      <td>
        <h5 class="m-0 text-primary">{{ Setting::value('company.name') }}</h5>
        @if (!empty(Setting::value('company.headline')))
          <h6 class="m-0">{{ Setting::value('company.headline') }}</h6>
        @endif
        <i>
          @if (!empty(Setting::value('company.address')))
            {{ Setting::value('company.address') }}
          @endif
          @if (!empty(Setting::value('company.phone')))
            - Telp. {{ Setting::value('company.phone') }}
          @endif
          @if (!empty(Setting::value('company.website')))
            - {{ Setting::value('company.website') }}
          @endif
        </i>
      </td>
      <td class="text-right va-bottom">
        <h6 class="text-primary">TANDA TERIMA SERVIS</h6>
        {{ Auth::user()->username }} | {{ format_datetime(current_datetime()) }}
        <small><br>{{ env('APP_NAME') . ' v' . env('APP_VERSION_STR') }}</small>
      </td>
    </tr>
  </table>
  <hr style="border-style:double;margin:3px 0;">
  @include('admin.service-order._service-note-main-content')
  <table style="width:100%;font-size:9pt;">
    <tr>
      <td style="vertical-align:top;">
        <div class="warning-notes">
          CATATAN:<br>
          - Barang yang dititipkan dan tidak diambil dalam waktu 30 hari diluar tanggung jawab kami.<br>
          - Garansi mulai dihitung sejak tanggal selesai servis.
          <br>
        </div>
      </td>
      <td style="width:4cm;text-align:center;">
        Diterima,<br><br>
        <b>{{ Auth::user()->fullname }}</b>
      </td>
    </tr>
  </table>
  <hr style="margin:5px 0;">
  <h6>TANDA TERIMA SERVIS - {{ Str::upper(Setting::value('company.name')) }}</h6>
  <div> <small>Dicetak oleh {{ Auth::user()->username }} | {{ format_datetime(current_datetime()) }} -
      {{ env('APP_NAME') . ' v' . env('APP_VERSION_STR') }}</small></div>
  <div style="font-size:9pt;">
    @include('admin.service-order._service-note-main-content')
  </div>
@endSection
