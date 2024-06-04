@extends('admin._layouts.default', [
    'title' => 'Rincian Order Penjualan',
    'menu_active' => 'sales',
    'nav_active' => 'sales-order',
])
@section('right-menu')
  <li class="nav-item">
    <a href="?print=1" class="btn btn-default"><i class="fa fa-print mr-1"></i>Nota</a>
    <a href="?print=2" class="btn btn-default"><i class="fa fa-print mr-1"></i>Struk Kecil</a>
  </li>
@endSection
@section('content')
  <div class="card card-primary">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <h3>{{ '#' . $item->id2Formatted() }}</h3>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <table class="table info table-sm">
            <tr>
              <td style="width:5%;white-space:nowrap;">Kode Pelanggan</td>
              <td style="width:1%">:</td>
              @if ($item->party)
                <td><a href="{{ url('admin/customer/detail/' . $item->party_id) }}">{{ $item->party->idFormatted() }}</a></td>
              @else
                <td></td>
              @endif
            </tr>
            <tr>
              <td style="width:5%;white-space:nowrap;">Nama Pelanggan</td>
              <td style="width:1%">:</td>
              <td>{{ $item->party_name }}</td>
            </tr>
            <tr>
              <td>No Telepon</td>
              <td>:</td>
              <td>{{ $item->party_phone }}</td>
            </tr>
            <tr>
              <td>Alamat</td>
              <td>:</td>
              <td>{{ $item->party_address }}</td>
            </tr>
            <tr>
              <td>Catatan</td>
              <td>:</td>
              <td>{{ $item->notes }}</td>
            </tr>
          </table>
        </div>
        <div class="col">
          <table class="table info table-sm">
            <tr>
              <td style="width:5%;white-space:nowrap;">No. Invoice</td>
              <td style="width:1%">:</td>
              <td>{{ $item->id2Formatted() }}</td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>:</td>
              <td>{{ format_datetime($item->datetime) }}</td>
            </tr>
            <tr>
              <td>Status</td>
              <td>:</td>
              <td>{{ $item->statusFormatted() }}</td>
            </tr>
            <tr>
              <td>Dibuat</td>
              <td>:</td>
              <td>{{ format_datetime($item->created_datetime) }} oleh {{ $item->created_by->username }}</td>
            </tr>
            <tr>
              <td>Selesai</td>
              <td>:</td>
              <td>{{ format_datetime($item->closed_datetime) }} oleh {{ $item->closed_by->username }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-condensed center-th table-sm" style="width:100%">
            <thead class="bg-primary">
              <th>No</th>
              <th>Produk</th>
              <th>Qty</th>
              <th>Satuan</th>
              <th>Harga</th>
              <th>Subtotal</th>
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
                  <td>{{ $detail->product->idFormatted() }} - {{ $detail->product->code }}</td>
                  <td class="text-right">{{ format_number(abs($detail->quantity)) }}</td>
                  <td>{{ $detail->product->uom }}</td>
                  <td class="text-right">{{ format_number($detail->price) }}</td>
                  <td class="text-right">{{ format_number(abs($detail->price * $detail->quantity)) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr class="bg-primary">
                <th colspan="5" class="text-right">Grand Total</th>
                <th class="text-right">{{ format_number(abs($total_price)) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div> {{-- .card-body --}}
  </div>
@endSection
