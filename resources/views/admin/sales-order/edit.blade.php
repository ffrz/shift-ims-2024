<?php
use App\Models\StockUpdate;
?>
@extends('admin._layouts.default', [
    'title' => 'Order Penjualan',
    'menu_active' => 'sales',
    'nav_active' => 'sales_order',
])
@section('content')
  <form method="POST" id="editor" class="pos-editor" action="{{ url('admin/sales-order/edit/' . (int) $item->id) }}">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group row">
              <label for="party_id" class="col-sm-2 col-form-label">Pelanggan</label>
              <div class="col-sm-10">
                <select class="custom-select select2" id="party_id" name="party_id">
                  <option value="" {{ !$item->party_id ? 'selected' : '' }}>Pelanggan Baru</option>
                  @foreach ($parties as $party)
                    <option value="{{ $party->id }}"
                      {{ old('party_id', $item->party_id) == $party->id ? 'selected' : '' }}>
                      {{ $party->idFormatted() }} - {{ $party->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="party_name" class="col-sm-2 col-form-label">Nama</label>
              <div class="col-sm-10">
                <input type="text" id="party_name" class="form-control" name="party_name"
                  value="{{ old('party_name', $item->party_name) }}">
                @error('party_name')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="party_phone" class="col-sm-2 col-form-label">Kontak</label>
              <div class="col-sm-10">
                <input type="text" id="party_phone" class="form-control" name="party_phone"
                  value="{{ old('party_phone', $item->party_phone) }}">
                @error('party_phone')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="party_address" class="col-sm-2 col-form-label">Alamat</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="party_address"
                  value="{{ old('party_address', $item->party_address) }}">
                @error('party_address')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group row">
              <label for="datetime" class="col-sm-4 col-form-label">Tanggal:</label>
              <div class="col">
                <input type="datetime-local" class="form-control @error('datetime') is-invalid @enderror" id="datetime"
                  name="datetime" value="{{ old('datetime', $item->datetime) }}">
                @error('datetime')
                  <span class="text-danger">
                    {{ $message }}
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="id" class="col-sm-4 col-form-label">#No Invoice:</label>
              <div class="col">
                <input type="text" class="form-control" id="id" name=""
                  value="{{ $item->id2Formatted() }}" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="ml-5" style="font-weight:bold;font-size:70px;"><span>Rp.
              </span><span id="total">0</span>,-</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="row mt">
          <div class="col col-md-12">
            <div class="input-group mb-2">
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
            <table id="product-list" class="table table-sm table-bordered table-hover">
              <thead>
                <th style="width:3%">No</th>
                <th>Produk</th>
                <th style="width:10%">Qty</th>
                <th style="width:10%">Satuan</th>
                <th style="width:10%">Harga</th>
                <th style="width:10%">Jml Harga</th>
                <th style="width:3%"></th>
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
            <b>Info Tambahan</b>
          </button>
        </div>
        <div id="filterCollapse" class="collapse" aria-labelledby="filterHeading" data-parent="#filterBox">
          <div class="card-body">
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
          <button type="submit" id="complete" onclick="return confirm('Selesaikan Transaksi?')" name="action"
            value="complete" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i> Selesai</button>
          <button type="submit" id="save" name="action" value="save" class="btn btn-default mr-1"><i
              class="fa fa-save mr-1"></i> Simpan</button>
          <button type="submit" id="cancel" onclick="return confirm('Batalkan Transaksi?')" name="action"
            value="cancel" class="btn btn-default"><i class="fas fa-cancel mr-1"></i> Batalkan</button>
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
    let products = {!! json_encode($products) !!};
    let barcodes = {!! json_encode($barcodes) !!};
    let details = {!! json_encode($details) !!};
    let parties = {!! json_encode($parties) !!}
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

    function addItem() {
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

      setItemData(product, qty, product.price);
      code_text_edit.val('');
      code_text_edit.focus();
    }

    function setItemData(product, qty, price) {
      qty = Math.abs(qty);

      let $item = $('#item-' + product.id);
      if ($item.length != 0) {
        // add to existing item in table
        let $qty = $item.find('.qty');
        let $price = $item.find('.price');
        let newQty = localeNumberToNumber($qty.val()) + qty;
        $qty.val(toLocaleNumber(newQty));
        updateSubtotal();
      } else {
        $('#empty-item-row').hide();
        let $tbody = $('#product-list tbody');
        let row = $tbody.children().length;
        $tbody.append(
          '<tr id="item-' + product.id + '">' +
          '<td class="text-right num">' + row + '</td>' +
          '<td>' + product.code + '<input type="hidden" class="product_id" name="product_id[' + row + ']" value="' +
          product.id + '"></td>' +
          '<td class="text-right"><input class="text-right qty" onchange="updateSubtotal()" name="qty[' + row +
          ']" value="' + toLocaleNumber(qty) + '"></td> ' +
          '<td>' + product.uom + '</td>' +
          '<td class="text-right"><input class="text-right price" onchange="updateSubtotal()" name="price[' + row +
          ']" value="' + toLocaleNumber(price) + '"></td>' +
          '<td id="subtotal-' + product.id + '" class="subtotal text-right">' + toLocaleNumber(price * qty) +
          '</td>' +
          '<td><button onclick="removeItem(this)" type="button" class="btn btn-sm btn-danger"><i class="fa fa-cancel"></i></button></td>' +
          '</tr>'
        );
        Inputmask("decimal", Object.assign({
          allowMinus: false
        }, INPUTMASK_OPTIONS)).mask("#item-" + product.id + " .qty, #item-" + product.id + " .price");
        total += price * qty;
        updateTotal();
      }
    }

    $('#add-item').click(function(e) {
      e.preventDefault();
      addItem();
    });

    $(document).on("keydown", "#product_code_textedit", function(e) {
      if (e.key == "Enter") {
        addItem();
      }
    });

    function removeItem(self) {
      let $tr = $(self).parent().parent();
      let $tbody = $tr.parent();

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

    $('#party_id').change(function() {
      let id = $(this).val();
      var party = parties.filter(obj => {
        return obj.id == id;
      });
      if (party.length > 0) {
        $('#party_name').val(party[0].name);
        $('#party_phone').val(party[0].phone);
        $('#party_address').val(party[0].address);
      }
    });

    $(document).ready(function() {
      $(function() {
        $('.select2').select2({});
      });

      details.forEach(detail => {
        const code = product_code_by_ids[detail.product_id];
        setItemData(products[code], detail.quantity, detail.price);
        updateTotal();
      });

      Inputmask("decimal", Object.assign({
        allowMinus: false
      }, INPUTMASK_OPTIONS)).mask(".qty,.price");
    });
  </script>
@endsection
