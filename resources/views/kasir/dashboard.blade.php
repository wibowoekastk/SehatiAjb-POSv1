@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-12">
        <div class="box" style="
            border-radius: 16px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 50px 30px;
            margin-top: 30px;
        ">
            <div class="box-body text-center">
                <h1 style="font-weight: 600; margin-bottom: 10px;">Selamat Datang,</h1>
                <h2 style="color: #555;">Kamu Login Di Sehati Jewelry Karyawan</h2>
                <br><br>
                <a href="{{ route('transaksi.baru') }}" class="btn btn-warning btn-lg" style="border-radius: 8px;">
                    <i class="fa fa-cart-plus"></i> New Transaction
                </a>
                <br><br><br>
            </div>
        </div>
    </div>
</div>
<!-- visit "codeastro" for more projects! -->
<!-- /.row (main row) -->
@endsection