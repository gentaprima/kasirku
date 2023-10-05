<?php

use Illuminate\Support\Facades\Session;
?>
@extends('master')

@section('title-link','Beranda')
@section('sub-title-link','Beranda')
@section('active','beranda')
@section('title','Dashboard')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="padding: 10px 12px 0px 37px;">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active">Beranda</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card p-5">
            <h5>List Barang Keluar</h5>
            <hr>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card p-5">
            <h5>Pendapatan Hari ini </h5>
            <hr>
            <h5>Pendapatan Minggu ini </h5>
            <hr>
            <h5>Pendapatan Bulan ini </h5>
            <hr>
          </div>
        </div>
      </div>




    </div>
  </section>
  <!-- /.content -->
</div>
@endsection 