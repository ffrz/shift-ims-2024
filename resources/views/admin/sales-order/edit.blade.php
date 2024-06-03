<?php
use App\Models\StockUpdate;
?>
@extends('admin._layouts.default', [
    'title' => 'Order Penjualan',
    'menu_active' => 'sales',
    'nav_active' => 'sales_order',
])
@section('content')
  <form method="POST" id="editor" action="{{ url('admin/sales-order/edit/' . (int) $item->id) }}">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <div class="text-right" id="total">0</div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <div class="input-group">
              <input type="text" list="products" id="product_code_textedit" autofocus class="form-control"
                placeholder="Masukkan barcode atau kode produk">
              <datalist id="products">
                @foreach ($products as $product)
                  <option value="{{ $product['pid'] . ' | ' . $product['code'] }}">
                @endforeach
              </datalist>
              <div class="input-group-append">
                <button type="submit" id="add-item" class="btn btn-default" title="OK"> <i class="fa fa-check"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
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
                <label for="party_id">Pelanggan</label>
                <select class="custom-select select2" id="party_id" name="party_id">
                  <option value="" {{ !$item->party_id ? 'selected' : '' }}>-- Pilih Pelanggan --</option>
                  @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}"
                      {{ old('party_id', $item->party_id) == $customer->id ? 'selected' : '' }}>
                      {{ $customer->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-3">
                <label for="datetime">Tanggal:</label>
                <input type="datetime-local" class="form-control @error('datetime') is-invalid @enderror" id="datetime"
                  name="datetime" value="{{ old('datetime', $item->datetime) }}">
                @error('datetime')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group col-md-3">
                <label for="order_status">Status:</label>
                <select class="custom-select select2 form-control" id="order_status" name="order_status" disabled>
                  <option value="{{ StockUpdate::STATUS_OPEN }}"
                    {{ $item->status == StockUpdate::STATUS_OPEN ? 'selected' : '' }}>
                    {{ $item->statusFormatted() }}</option>
                  <option value="{{ StockUpdate::STATUS_COMPLETED }}"
                    {{ $item->status == StockUpdate::STATUS_COMPLETED ? 'selected' : '' }}>
                    {{ $item->statusFormatted() }}</option>
                  <option value="{{ StockUpdate::STATUS_CANCELED }}"
                    {{ $item->status == StockUpdate::STATUS_CANCELED ? 'selected' : '' }}>
                    {{ $item->statusFormatted() }}</option>
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
        @if ($item->status == StockUpdate::STATUS_OPEN)
          <button type="submit" id="complete" onclick="return confirm('Selesaikan Transaksi?')" name="action" value="complete" class="btn btn-primary mr-1"><i
              class="fas fa-check mr-1"></i> Selesai</button>
          <button type="submit" id="save" name="action" value="save" class="btn btn-default mr-1"><i
              class="fa fa-save mr-1"></i> Simpan</button>
          <button type="submit" id="cancel" onclick="return confirm('Batalkan Transaksi?')" name="action" value="cancel" class="btn btn-default"><i
              class="fas fa-cancel mr-1"></i> Batalkan</button>
        @else
          <button type="submit" name="reopen" class="btn btn-default"><i class="fas fa-folder-open mr-1"></i>
            Reopen</button>
        @endif
      </div>
    </div>
  </form>
@endSection

@section('footscript')
  <script>
    let cart_item_by_ids = {};
    let products = {!! json_encode($products) !!};
    let barcodes = {!! json_encode($barcodes) !!};
    let details = {!! json_encode($details) !!};
    let product_code_by_ids = {!! json_encode($product_code_by_ids) !!};
    let total = 0;
    let submit = false;

    function updateSubtotal() {
      total = 0;
      let $tbody = $('#product-list tbody');
      let children = $tbody.children();
      children.each(function(i, el) {
        if (i == 0) {
          return;
        }
        let qty = $(el).find('.qty').val();
        let price = $(el).find('.price').val();
        let subtotal = localeNumberToNumber(qty) * localeNumberToNumber(price);
        $(el).find('.subtotal').text(toLocaleNumber(subtotal));
        total += subtotal;
      });

      updateTotal();
    }

    function updateTotal() {
      $('#total').text(toLocaleNumber(total));
    }

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
      code = code.split('|')[0];

      if (texts.length == 2) {
        code = texts[1];
        qty = Number(texts[0]);
      }
      let product = products[code];
      if (!product && barcodes[code]) {
        product = products[barcodes[code]];
      }

      if (!product) {
        code_text_edit.select();
        code_text_edit.focus();
        return;
      }

      addToCart(product, qty);
      updateTotal();
      code_text_edit.val('');
      code_text_edit.focus();
    }

    function addToCart(product, qty) {
      let item = cart_item_by_ids[product.id];
      if (cart_item_by_ids[product.id]) {
        item.qty += qty;
        $('#qty-' + product.id + ' input').val(toLocaleNumber(item.qty));
        $('#subtotal-' + product.id).text(toLocaleNumber(item.qty * product.price));
      } else {
        item = {
          product: product,
          qty: qty,
          price: Number(product.price),
        };
        $('#empty-item-row').hide();
        cart_item_by_ids[product.id] = item;
        let $tbody = $('#product-list tbody');
        let row = $tbody.children().length;
        $tbody.append(
          '<tr id="' + product.id + '" data-id="' + product.id + '">' +
          '<td class="text-right num">' + row + '</td>' +
          '<td>' + product.code + '<input type="hidden" class="product_id" name="product_id[' + row + ']" value="' +
          product.id + '"></td>' +
          '<td id="qty-' + product.id +
          '" class="text-right"><input class="text-right qty" onchange="updateSubtotal()" name="qty[' + row +
          ']" value="' + toLocaleNumber(Math.abs(item.qty)) + '"></td> ' +
          '<td>' + product.uom + '</td>' +
          '<td class="text-right"><input class="text-right price" onchange="updateSubtotal()" name="price[' + row +
          ']" value="' + toLocaleNumber(item.price) + '"></td>' +
          '<td id="subtotal-' + product.id + '" class="subtotal text-right">' + toLocaleNumber(product.price * Math.abs(qty)) + '</td>' +
          '<td><button onclick="removeCartItem(this)" type="button" class="btn btn-sm btn-default"><i class="fa fa-cancel"></i></button></td>' +
          '</tr>');
      }
      total += product.price * qty;
    }

    $('#add-item').click(function(e) {
      e.preventDefault();
      addProduct();
    });

    $(document).on("keydown", "#product_code_textedit", function(e) {
      if (e.key == "Enter") {
        addProduct();
      }
    });

    function removeCartItem(self) {
      let $tr = $(self).parent().parent();
      let $tbody = $tr.parent();

      delete cart_item_by_ids[$tr.data('id')];
      $tr.remove();

      // tampilkan item kosong
      let children = $tbody.children();
      if (children.length == 1) {
        $('#empty-item-row').show();
      }

      // reset nomor urut dan field row 
      children.each(function(i, el) {
        $(el).find('.num').text(i);
        $(el).find('.product_id').attr('name', 'product_id[' + i + ']');
        $(el).find('.qty').attr('name', 'qty[' + i + ']');
        $(el).find('.price').attr('name', 'price[' + i + ']');
      });

      updateSubtotal();
    }

    $(document).ready(function() {
      $(function() {
        $('.select2').select2({});
      });

      details.forEach(detail => {
        const code = product_code_by_ids[detail.product_id];
        addToCart(products[code], detail.quantity);
        updateTotal();
      });

      Inputmask("decimal", Object.assign({
        allowMinus: false
      }, INPUTMASK_OPTIONS)).mask("#down_payment,#estimated_cost,#total_cost");
    });
  </script>
@endsection
