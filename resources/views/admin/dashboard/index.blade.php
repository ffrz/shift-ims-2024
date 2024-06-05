@extends('admin._layouts.default', [
    'title' => 'Dashboard',
    'nav_active' => 'dashboard',
])
@section('right-menu')
@endsection
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $data['active_service_order_count'] }}</h3>
              <p>Order Servis Aktif</p>
            </div>
            <div class="icon">
              <i class="fas fa-screwdriver-wrench"></i>
            </div>
            <a href="/admin/service-order?order_status=0&service_status=-1&payment_status=-1" class="small-box-footer"><i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $data['active_sales_count'] }}</h3>
              <p>Order Penjualan Aktif</p>
            </div>
            <div class="icon">
              <i class="fa fa-file-invoice"></i>
            </div>
            <a href="/admin/sales-order?status=0" class="small-box-footer"><i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><sup style="font-size: 20px">Rp. </sup>{{ format_number($data['total_sales_this_month']) }}</h3>
              <p>Omset Penjualan Bulan Ini</p>
            </div>
            <div class="icon">
              <i class="fa fa-money-bills"></i>
            </div>
            <a href="#" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $data['sales_count_this_month'] }}</h3>
              <p>Order Penjualan Bulan Ini</p>
            </div>
            <div class="icon">
              <i class="fa fa-receipt"></i>
            </div>
            <a href="#" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
