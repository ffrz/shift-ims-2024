<?php
use App\Models\SalesOrder;
$title = 'Order Penjualan';
?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'sales',
    'nav_active' => 'sales_order',
    'back_button_link' => url('/admin/sales-order/'),
])
@section('content')
  <form method="POST" id="editor" action="{{ url('admin/service-order/edit/' . (int) $item->id) }}">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
    <div class="card card-primary">
      <div class="card-body">
        <div style="font-weight:bold;font-size:60px;" class="row">
          <div class="text-right" id="total">0</div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" id="product_code_textedit" autofocus class="form-control"
                placeholder="Input Kode/Barcode">
              <div class="input-group-append">
                <button type="submit" class="btn btn-default" title="OK"> <i class="fa fa-check"></i> </button>
                <a href="#" class="btn btn-default"> <i class="fa fa-search" title="Cari Produk"></i> </a>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col col-md-12">
            <table id="product-list" class="table table-sm table-bordered table-hover">
              <thead>
                <th>No</th>
                <th>Produk</th>
                <th>Jml</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Subtotal</th>
              </thead>
              <tbody>
                <tr id="empty-item-row">
                  <td colspan="7" class="text-center text-muted"><i>Belum ada item.</i></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="accordion" id="filterBox">
      <div class="card">
        <div class="card-header" id="filterHeading">
          <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
            data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
            <b>Info Order</b>
          </button>
        </div>
        <div id="filterCollapse" class="collapse" aria-labelledby="filterHeading" data-parent="#filterBox">
          <div class="card-body">
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="customer_id">Pelanggan</label>
                <select class="custom-select select2" id="customer_id" name="customer_id">
                  <option value="-1" {{ !$item->customer_id ? 'selected' : '' }}>-- Pilih Pelanggan --</option>
                  @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}"
                      {{ old('customer_id', $item->customer_id) == $customer->id ? 'selected' : '' }}>
                      {{ $customer->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-3">
                <label for="date">Tanggal:</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" autofocus id="date"
                  name="date" value="{{ old('date', $item->date) }}">
                @error('date')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group col-md-3">
                <label for="order_status">Status:</label>
                <select class="custom-select select2 form-control" id="order_status" name="order_status" disabled>
                  <option value="{{ SalesOrder::ORDER_STATUS_OPEN }}"
                    {{ $item->status == SalesOrder::ORDER_STATUS_OPEN ? 'selected' : '' }}>
                    {{ SalesOrder::formatOrderStatus(SalesOrder::ORDER_STATUS_OPEN) }}</option>
                  <option value="{{ SalesOrder::ORDER_STATUS_CLOSED }}"
                    {{ $item->status == SalesOrder::ORDER_STATUS_CLOSED ? 'selected' : '' }}>
                    {{ SalesOrder::formatOrderStatus(SalesOrder::ORDER_STATUS_CLOSED) }}</option>
                  <option value="{{ SalesOrder::ORDER_STATUS_CANCELED }}"
                    {{ $item->status == SalesOrder::ORDER_STATUS_CANCELED ? 'selected' : '' }}>
                    {{ SalesOrder::formatOrderStatus(SalesOrder::ORDER_STATUS_CANCELED) }}</option>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label for="id">#No Invoice:</label>
                <input type="text" class="form-control" id="id" name=""
                  value="{{ $item->idFormatted() }}" readonly>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="notes">Catatan:</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes">{{ old('notes', $item->notes) }}</textarea>
                @error('notes')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-footer">
        @if ($item->status == SalesOrder::ORDER_STATUS_OPEN)
          <button id="save-button" class="btn btn-primary mr-2"><i class="fa fa-check mr-1"></i>Simpan</button>
          <button type="submit" name="save" class="btn btn-warning mr-2"><i class="fas fa-check mr-1"></i>
            Selesai</button>
          <button type="submit" name="save" class="btn btn-danger mr-2"><i class="fas fa-cancel mr-1"></i>
            Batalkan</button>
        @else
          <button type="submit" name="reopen" class="btn btn-warning"><i class="fas fa-folder-open mr-1"></i>
            Reopen</button>
        @endif
      </div>
    </div>
  </form>
@endSection

@section('footscript')
  <script>
    let products = {!! json_encode($products) !!};
    let barcodes = {!! json_encode($barcodes) !!};
    let total = 0;
    let cart_item_by_ids = {};
    let cart_items = [];

    $(function() {
      $('.select2').select2({});
    });

    function addProduct() {
      let code_text_edit = $('#product_code_textedit');
      let text = code_text_edit.val();
      text = text.replace(/\s/g, "");
      let texts = text.split('*');
      if (texts.length == 0) {
        return;
      }

      let qty = 1;
      let code = texts[0];
      if (texts.length == 2) {
        code = texts[1];
        qty = Number(texts[0]);
      }
      let product = products[code];
      if (!product && barcodes[code]) {
        product = products[barcodes[code]];
      }

      if (!product) {
        alert('Produk tidak ditemukan!');
        code_text_edit.select();
        code_text_edit.focus();
        return;
      }

      let item = cart_item_by_ids[product.id];
      if (cart_item_by_ids[product.id]) {
        item.qty += qty;
        $('#qty-' + product.id).text(toLocaleNumber(item.qty));
        $('#subtotal-' + product.id).text(toLocaleNumber(item.qty * product.price));
      } else {
        item = {
          product: product,
          qty: qty,
          price: Number(product.price),
        };
        $('#empty-item-row').hide();
        cart_item_by_ids[product.id] = item;
        cart_items.push(item);
        $('#product-list tbody').append(
          '<tr id=' + product.id + '>' +
          '<td class="text-right">' + cart_items.length + '</td>' +
          '<td>' + product.code + '</td>' +
          '<td id="qty-' + product.id + '" class="text-right">' + toLocaleNumber(qty) + '</td> ' +
          '<td>' + product.uom + '</td>' +
          '<td class="text-right">' + toLocaleNumber(product.price) + '</td>' +
          '<td id="subtotal-' + product.id + '">' + toLocaleNumber(product.price * qty) + '</td>' +
          '<td><button type="button" class="btn btn-sm btn-default"><i class="fa fa-cancel"></i></button></td>'+
          '</tr>');
      }
      total += product.price * qty;
      $('#total').text(toLocaleNumber(total));

      code_text_edit.val('');
    }

    $('#save-button').click(function() {
      if (cart_items.length == 0) {
        $('#product_code_textedit').focus();
        alert('Item masih kosong');
        return;
      }

      let data = {
        _token: $('[name="csrf-token"]').attr('content'),
        order_id: {{ $item->id }},
        items: [],
      };

      console.log(cart_items);
      for (let i = 0; i < cart_items.length; i++) {
        let item = cart_items[i];
        let tmpItems = {
          id: item.product.id,
          qty: item.qty,
          price: item.price,
        };
        data.items.push(tmpItems);
      }

      $.ajax({
        type: "POST",
        url: '{{ url('admin/sales-order/save-detail/' . $item->id) }}',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
          alert('success', response)
        }
      });

    });
    $('#editor').submit(function(event) {
      event.preventDefault();
      addProduct();
    });

    Inputmask("decimal", Object.assign({
      allowMinus: false
    }, INPUTMASK_OPTIONS)).mask("#down_payment,#estimated_cost,#total_cost");
  </script>
@endsection
