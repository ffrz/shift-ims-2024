<?php $title = ($item->id ? 'Edit' : 'Tambah') . ' Pengeluaran'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'expense',
    'nav_active' => 'expense',
    'form_action' => url('admin/expense/edit/' . (int) $item->id),
])

@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a onclick="return confirm('Batalkan perubahan?')" class="btn btn-default" href="{{ url('/admin/expense/') }}"><i
        class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection

@section('content')
  <div class="row">
    <div class="col-lg-4">
      <div class="card card-primary">
        <div class="card-body">
          <div class="form-group">
            <label for="date" class="col-form-label">Tanggal:</label>
            <input autofocus type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date"
              value="{{ old('date', $item->date) }}">
            @error('date')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="category_id">Kategori</label>
            <select class="custom-select select2 @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
              <option value="" {{ !$item->category_id ? 'selected' : '' }}>-- Pilih Kategori --</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                  {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" autofocus
              id="description" placeholder="Contoh: Listrik Januari" name="description"
              value="{{ old('description', $item->description) }}">
            @error('description')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="amount">Jumlah</label>
            <input type="text" class="form-control col-md-5 text-right @error('amount') is-invalid @enderror"
              id="amount" placeholder="Jumlah pengeluaran" name="amount"
              value="{{ format_number(old('amount', $item->amount)) }}">
            @error('amount')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="notes">Catatan</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" cols="30"
              rows="4">{{ old('notes', $item->notes) }}</textarea>
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
@endSection
@section('footscript')
  <script>
    Inputmask("decimal", Object.assign({
      allowMinus: false
    }, INPUTMASK_OPTIONS)).mask("#amount");
    $('.select2').select2();
  </script>
@endsection
