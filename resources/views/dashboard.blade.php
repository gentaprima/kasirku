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
      <div class="row">
        <div class="col-sm-6">
          <h6 class="btn btn-outline-primary"><span class="font-weight-bold">Tanggal</span> : <?= $date  ?></h6>
        </div>
        <div class="col-sm-6">
          <button data-target="#modal-show-date" data-toggle="modal" class="btn btn-primary " style="float:right;"><i class="fa fa-filter"></i> Pilih Tanggal</button>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card p-5">
            <div class="row">
              <div class="col-6">
                <h5>List Barang Keluar</h5>

              </div>
              <div class="col-6">

              </div>
            </div>
            <hr>
            <ul class="list-group">
              <?php foreach ($dataExitItem as $row) { ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <?= $row->group ?>
                  <span class="badge badge-primary badge-pill"><?= $row->barang_keluar ?></span>
                </li>
              <?php } ?>

            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card p-5">
            <h5>Pendapatan Hari ini</h5>
            <h5 class="font-weight-bold">Rp <?= number_format($dataTransactionToday, 0, ".", '.') ?></h5>
            <hr>
            <h5>Pendapatan Minggu ini </h5>
            <h5 class="font-weight-bold">Rp <?= number_format($dataTransactionWeek, 0, ".", '.') ?></h5>
            <hr>
            <h5>Pendapatan Bulan ini </h5>
            <h5 class="font-weight-bold">Rp <?= number_format($dataTransactionMonth, 0, ".", '.') ?></h5>
            <hr>
          </div>
        </div>
      </div>




    </div>
  </section>
  <!-- /.content -->
</div>
<div class="modal fade" id="modal-show-date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content rounded">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleTopping">Filter Tanggal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="get">
          <div class="form-group row">
            <label for="" class="col-sm-2">Pilih Tanggal</label>
            <div class="col-sm-10">
              <input type="date" name="date" class="form-control">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
      </div>
      <div class="bg-chocolate rounded-modal" style="background-color: #967E76 !important;height:15px;"></div>
    </div>
  </div>
</div>
@endsection