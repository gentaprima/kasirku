@extends('master')

@section('title-link','Beranda')
@section('sub-title-link','Data Produk ')
@section('title','Data Produk')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="padding: 10px 12px 0px 37px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                        <li class="breadcrumb-item active">Data Produk</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    @if(Session::has('message'))
    <p hidden="true" id="message">{{ Session::get('message') }}</p>
    <p hidden="true" id="icon">{{ Session::get('icon') }}</p>
    @endif
    <!-- Main content -->

    <section class="content">
        <div class="container-fluid">
            <div class="card p-4 rounded mb-3">
                <!-- <div class="row">
                    <div class="col-6">
                        <button class="btn btn-outline-primary size-btn" onclick="addData()" data-toggle="modal" data-target="#modal-form">Tambah Data</button>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3 search">
                            <input type="search" class="form-control border-search" placeholder="Telusuri ..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-6  mb-3">
                        <button data-target="#modal-show-date" data-toggle="modal" class="btn btn-primary"><i class="fa fa-filter"></i> Pilih Tanggal</button>
                        <?php if ($filter == true) { ?>
                            <a href="/transaction" class="btn btn-outline-primary">Reset Tanggal</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="example3" class="table table-bordered table-striped">
                        <thead class="">
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($transaction as $row) { ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->order_id; ?></td>
                                    <td><?= $row->product_name; ?></td>
                                    <td class="text-center"><?= $row->quantity; ?></td>
                                    <td class="text-center"><?= date('d M Y H:i', strtotime($row->created_at)); ?></td>
                                    <td class="text-center">Rp <?= number_format($row->total, 0, '.', '.'); ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="bg-light font-weight-bold">
                                <td colspan="5" class="text-right">Subtotal</td>
                                <td class="text-center">Rp <?= number_format($totalToday, 0, '.', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                    <ul class="pagination">
                        <li>Halaman</li>
                        <li class="paginate_button active mr-2"><a href="#" aria-controls="example1" id="current_page" data-dt-idx="1" tabindex="0">1</a></li>
                        <li>Dari</li>
                        <li class="ml-2" id="total_page"></li>
                        <li class="paginate_button next prev" id="example1_previous"><a href="#" aria-controls="example1" id="link_prev" data-dt-idx="0" tabindex="0"><i class="fa fa-chevron-left"></i></a></li>
                        <li class="paginate_button next" id="example1_next"><a id="link_next" href="" aria-controls="example1" data-dt-idx="2" tabindex="0"><i class="fa fa-chevron-right"></i></a></li>
                    </ul>
                </div> -->
            </div>
        </div><!-- /.container-fluid -->
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
                <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="height:15px;"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-show-topping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleTopping">Data Topping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped" id="tableTopping">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" onclick="addTopping()" class="btn btn-primary">Tambah</button>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="height:15px;"></div>
        </div>
    </div>
</div>
<script>


</script>
@endsection