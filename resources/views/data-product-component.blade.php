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
                <div class="row">
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
                </div>
                <!-- <button class="btn btn-outline-primary size-btn mb-3" onclick="addData()" data-toggle="modal" data-target="#modal-form">Tambah Data</button> -->
                <table id="table-component" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Bahan</th>
                            <th>Quantity</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                   <tbody></tbody>

                </table>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Tambah Produk komponen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" method="post" id="form" action="/add-product-component" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Produk</label>
                        <div class="col-sm-10">
                            <select id="product" class="form-control" style="width: 100%;">
                                <option value="">Select an option</option>
                            </select>


                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Bahan</label>
                        <div class="col-sm-10">
                            <select id="component" class="form-control" style="width: 100%;">
                                <option value="">Select an option</option>
                            </select>


                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Quantity</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="qty" id="qty">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                <button type="button" id="btnSubmit" onClick="addProductComponent()" class="btn btn-primary">Simpan</button>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="color: red;height:15px;"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Anda yakin ingin menghapus data tersebut?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                <a id="btnDelete" type="submit" class="btn btn-primary">Hapus</a>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="color: red;height:15px;"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        loadData(1)
    })

    $(document).ready(function() {
        var page = 1;
        var search = "";
        $(document).ready(function() {
            $("#product").select2({
                placeholder: "Search an option...",
                allowClear: true,
                ajax: {
                    url: "/show-component-product?", // Replace with your API
                    type: "GET",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        console.log()
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.product_name
                                };
                            }),
                        };
                    },
                    cache: true
                }
            });
        });
    });
    $(document).ready(function() {
        var page = 1;
        var search = "";
        $(document).ready(function() {
            $("#component").select2({
                placeholder: "Search an option...",
                allowClear: true,
                ajax: {
                    url: "/show-component?", // Replace with your API
                    type: "GET",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data.data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.product_name
                                };
                            }),
                        };
                    },
                    cache: true
                }
            });
        });
    });

    function addProductComponent() {
        $("#btnSubmit").prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        var product = $("#product").val();
        var component = $("#component").val();
        var qty = $("#qty").val();
        var token = $("input[name='_token']").val();
        console.log(token)
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/add-product-component',
            contentType: 'application/json',
            data: JSON.stringify({
                'product': product,
                'component': component,
                'qty': qty,
                '_token': token
            }),
            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    $("#btnSubmit").prop("disabled", false).html("Submit"); // Kembalikan tombol seperti semula
                    $("#closeModal").click();
                } else {
                    Toast.fire({
                        icon: "error",
                        title: response.message
                    });
                }
            },
            complete: function() {
                $("#btnSubmit").prop("disabled", false).html("Submit"); // Kembalikan tombol seperti semula

            }
        })
    }


    function loadData(page, search = '') {
        $("#table-component tbody").empty();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/get-product-component?page=${page}&search=${search}`,
            success: function(response) {
                let data = response;
                let k = 1;
                if (response.data.length > 0) {
                    $.each(response.data, function(index, item) {
                        $("#table-component tbody").append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.main_product_name}</td>
                                <td>${item.component_name}</td>
                                <td>${item.quantity}</td>
                                <td>
                                    <button class="btn btn-secondary btn-sm btn-sm" data-id="${item.id}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    $("#table-component tbody").append(`<tr><td colspan="5" class="text-center">No data available</td></tr>`);
                }
            }
        })
    }

    function checkAds(val) {
        console.log(val.value);
    }



    function updateData(id, productName, productCategory, price, isActive, group, stockReduction, unit, remark) {
        console.log(productCategory);
        document.getElementById("productName").value = productName;
        document.getElementById("productCategory").value = productCategory;
        document.getElementById("price").value = price;
        document.getElementById("group").value = group;
        document.getElementById("stockReduction").value = stockReduction;
        document.getElementById("unit").value = unit;
        document.getElementById("remark").value = remark;
        document.getElementById("form").action = `/update-produk/${id}`;
        document.getElementById("titleModal").innerHTML = 'Perbarui Produk';
        if (isActive == 0) {
            document.getElementById('radioStatus1').checked = false;
            document.getElementById('radioStatus2').checked = true;
        } else {
            document.getElementById('radioStatus1').checked = true;
            document.getElementById('radioStatus2').checked = false;
        }


    }

    function addData() {
        // document.getElementById("productName").value = "";
        // document.getElementById("productCategory").value = "";
        // document.getElementById("price").value = "";
        // document.getElementById("group").value = '';
        // document.getElementById("form").action = '/add-produk';
        // document.getElementById("titleModal").innerHTML = 'Tambah Produk';
        // document.getElementById('radioStatus1').checked = false;
        // document.getElementById('radioStatus2').checked = false;
        // let requiredImage = document.getElementById("imagePick");
        // requiredImage.setAttribute('required', '')
    }

    function deleteData(id) {
        document.getElementById("btnDelete").href = `/delete-produk/${id}`;
    }
</script>
@endsection