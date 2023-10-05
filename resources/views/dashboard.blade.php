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
            <center> Status: <span data-widget="mcp-stream-status" data-name="tvupi" data-online-text="Online" data-offline-text="Offline"></span><br/></center>
          </div>
          <div class="card p-5">
            <div class="row">
              <div class="col-12">

                <!-- Connections: <span data-widget="mcp-custom-text" data-name="tvupi" data-format="%connections%"></span><br /> -->
              </div>
              <div class="col-12">
                <iframe src="https://wms.klikhost.com:2000/country-stats/tvupi/?header=true" width="100%" height="165px" style="border: none;" allowtransparency="true"></iframe>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card p-5" style="height: 403px;">
            <iframe src="https://wms.klikhost.com:2000/map/tvupi" width="100%" height="300px" border="0" style="border: none" allowtransparency="true"></iframe>
          </div>
        </div>
      </div>




    </div>
  </section>
  <!-- /.content -->
</div>
@endsection 