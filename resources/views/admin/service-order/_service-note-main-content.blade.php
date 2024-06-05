<table class="info" style="width:100%;margin:3px 0;">
  <tr>
    <td style="width:50%">
      <table style="width:100%">
        <tr>
          <td style="width:2cm;">No. Servis</td>
          <td style="width:1%;">:</td>
          <td>{{ $item->idFormatted() }}</td>
        </tr>
        <tr>
          <td>Diterima</td>
          <td>:</td>
          <td>{{ Carbon\Carbon::parse($item->date_received)->translatedFormat('l, j F Y') }}</td>
        </tr>
        <tr>
          <td>Atas Nama</td>
          <td>:</td>
          <td>{{ $item->customer_name }}</td>
        </tr>
        <tr>
          <td>No Telepon</td>
          <td>:</td>
          <td>{{ $item->customer_phone }}</td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td>{{ $item->address }}</td>
        </tr>
        <tr>
          <td>Catatan</td>
          <td>:</td>
          <td>
            {{ $item->notes }}
          </td>
        </tr>
      </table>
    </td>
    <td style="width:50%">
      <table style="width:100%">
        <tr>
          <td style="width:2.6cm;">Perangkat</td>
          <td style="width:1%;">:</td>
          <td>{{ $item->device }}</td>
        </tr>
        <tr>
          <td>Perlengkapan</td>
          <td>:</td>
          <td>{{ $item->equipments }}</td>
        </tr>
        <tr>
          <td>Keluhan</td>
          <td>:</td>
          <td>{{ $item->problems }}</td>
        </tr>
        <tr>
          <td>Tindakan</td>
          <td>:</td>
          <td>{{ $item->actions }}</td>
        </tr>
        <tr>
          <td>Biaya Perkiraan</td>
          <td>:</td>
          <td>{{ $item->estimated_cost ? 'Rp.' . format_number($item->estimated_cost) : '-' }}</td>
        </tr>
        <tr>
          <td>DP</td>
          <td>:</td>
          <td>{{ $item->down_payment ? 'Rp.' . format_number($item->down_payment) : '-' }}</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
