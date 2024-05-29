<?php
use App\Models\StockUpdate;
$title = 'Stok Opname';
?>

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'form_action' => url('admin/stock-adjustment/edit/' . $item->id),
    'nav_active' => 'stock-adjustment',
    'back_button_link' => url('/admin/stock-adjustment/'),
])

@section('right-menu')
  <li class="nav-item">
    <div class="btn-group">
      <button type="submit" name="action" value="save" class="btn btn-primary"><i class="fa fa-save mr-1"></i>
        Simpan</button>
      <button onclick="return confirm('Anda yakin akan menyelesaikan stok opname?')" type="submit" name="action"
        value="complete" class="btn btn-warning"><i class="fa fa-check mr-1"></i>
        Selesai</button>
      <button onclick="return confirm('Anda yakin akan membatalkan stok opname?')" type="submit" name="action"
        value="cancel" class="btn btn-default"><i class="fa fa-cancel mr-1"></i>
        Batal</button>
    </div>
  </li>
@endSection

@section('content')
  <input type="hidden" name="id" value="{{ $item->id }}">
  <div class="card card-primary">
    <div class="card-body">
      <div class="row mt-3">
        <div class="col col-md-12">
          <table id="product-list" class="table table-sm table-bordered table-hover">
            <thead>
              <th style="width:1%">No</th>
              <th>Produk</th>
              <th style="width:5%">Stok Tercatat</th>
              <th style="width:5%">Stok Sebenarnya</th>
              <th style="width:5%">Selisih</th>
              <th style="width:5%">Selisih Modal</th>
              <th style="width:5%">Selisih Harga</th>
            </thead>
            <tbody>
              @php
                $total_cost = 0;
                $total_price = 0;
              @endphp
              @foreach ($subitems as $num => $subitem)
                <tr id="item-{{ $item->id }}" data-id="{{ $item->id }}">
                  <td class="text-right">{{ $num + 1 }}</td>
                  <td>{{ $subitem->product->idFormatted() . ' - ' . $subitem->product->code }}</td>
                  <td data-stock="{{ $subitem->product->stock }}" class="stock text-right">
                    {{ format_number($subitem->product->stock) }}</td>
                  <td class="text-center"><input type="number" class="text-right stock-edit" style="width:75px;"
                      name="stocks[{{ $subitem->id }}]"
                      value="{{ format_number($subitem->product->stock + $subitem->quantity) }}">
                  <td data-diff="0" class="diff text-right">{{ format_number($subitem->quantity) }} </td>
                  <td data-cost="{{ $subitem->product->cost }}" class="cost text-right" class="text-right">
                    {{ format_number($subitem->product->cost * $subitem->quantity) }}</td>
                  <td data-price="{{ $subitem->product->price }}" class="price text-right" class="text-right">
                    {{ format_number($subitem->product->price * $subitem->quantity) }}</td>
                  </td>
                </tr>
                @php
                  $total_cost += $subitem->product->cost * $subitem->quantity;
                  $total_price += $subitem->product->price * $subitem->quantity;
                @endphp
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5" class="text-right">Total</th>
                <th id="total-cost" class="text-right">{{ format_number($total_cost) }}</th>
                <th id="total-price" class="text-right">{{ format_number($total_price) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
@endSection

@section('footscript')
  <script>
    $(document).ready(function() {
      $total_cost = $('#total-cost');
      $total_price = $('#total-price');
      $('.stock-edit').change(function() {
        $tr = $(this).closest('tr');

        let newStock = $(this).val();
        let oldStock = $tr.find('.stock').data('stock');
        let diff = newStock - oldStock;

        $diff = $tr.find('.diff');
        $diff.text(toLocaleNumber(diff));
        $diff.data('diff', diff);

        $cost = $tr.find('.cost');
        $price = $tr.find('.price');
        let cost = $cost.data('cost');
        let price = $price.data('price');

        $cost.text(toLocaleNumber(diff * cost));
        $price.text(toLocaleNumber(diff * price));

        let total_cost = 0;
        let total_price = 0;
        $tr.closest('tbody').children().each(function() {
          let diff = $(this).find('.diff').data('diff');
          let cost = $(this).find('.cost').data('cost');
          let price = $(this).find('.price').data('price');
          console.log(diff, cost, price);
          total_cost += diff * cost;
          total_price += diff * price;
        });
        $('#total-cost').text(toLocaleNumber(total_cost));
        $('#total-price').text(toLocaleNumber(total_price));
      });
    });
  </script>
@endSection
