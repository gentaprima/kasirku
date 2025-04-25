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
                        <!-- <button class="btn btn-outline-primary size-btn" onclick="addData()" data-toggle="modal" data-target="#modal-form">Tambah Data</button> -->
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
                <table id="table-stock" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Sisa Stock</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
                <div id="loading" style="display: none; text-align: center; padding: 1rem;">
                    <span class="spinner-border text-danger" role="status"></span>
                    <span style="margin-left: 0.5rem;">Memuat data...</span>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
                            Showing 1 to 10 of 0 entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers d-flex justify-content-end" id="example1_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous disabled" id="example1_previous">
                                    <a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                </li>
                                <li class="paginate_button page-item active">
                                    <a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                                </li>
                                <li class="paginate_button page-item next" id="example1_next">
                                    <a href="#" aria-controls="example1" data-dt-idx="8" tabindex="0" class="page-link">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Tambah Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" method="post" id="form" action="/add-rekening" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Nama Produk</label>
                        <div class="col-sm-10">
                            <input type="text" readonly required class="form-control" id="group" value="{{old('productName')}}" name="group" placeholder="Nama Produk">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Satuan</label>
                        <div class="col-sm-10">
                            <select name="satuan" id="satuan" class="form-control">
                                <option value="Pcs">Pcs</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Stock</label>
                        <div class="col-sm-10">
                            <input type="text" name="stock" id="stock" class="form-control">
                        </div>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                <button type="button" id="btnStock" onClick="addStock()" class="btn btn-primary">Simpan</button>
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
<div class="modal fade" id="modal-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" style="width: 100%;height:300px;border-radius:8px;" id="imageBanner" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="background-color: #967E76 !important;height:15px;"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-add-topping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleTopping">Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <input type="hidden" id="idProductTopping">
                    <label class="col-sm-2">Nama Topping</label>
                    <div class="col-sm-10">
                        <input type="text" name="toppingName" id="toppingName" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2">Harga</label>
                    <div class="col-sm-10">
                        <input type="text" name="toppingPrice" id="toppingPrice" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" onclick="addTopping()" class="btn btn-primary">Tambah</button>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="background-color: #967E76 !important;height:15px;"></div>
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
            <div class="bg-chocolate rounded-modal" style="background-color: #967E76 !important;height:15px;"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        loadData(1)
    })

    $('input[type=search]').on('input', function() {
        clearTimeout(this.delay);
        this.delay = setTimeout(function() {
            console.log(this.value);
            /* call ajax request here */
            loadData(1, this.value)
        }.bind(this), 800);
    });

    // Pagination click handler
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (!$(this).parent().hasClass('disabled') && page) {
            loadData(page);
        }
    });

    function loadData(page = 1, search = '') {
        $("#table-stock tbody").empty();
        $("#loading").show();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/api/get-stock?page=${page}&search=${search}`,
            success: function(response) {
                let data = response.data.data; // asumsi: data berisi array stok
                let pagination = response.data; // untuk current_page, last_page, total, per_page

                // Render rows
                if (data.length > 0) {
                    data.forEach((item, index) => {
                        $("#table-stock tbody").append(`
                        <tr>
                            <td>${(pagination.current_page - 1) * pagination.per_page + index + 1}</td>
                            <td>${item.group}</td>
                            <td>${item.stock}</td>
                            <td><button onclick="updateData('${item.id}','${item.product_name}','${item.stock}','${item.remaining_stock}','${item.group}')" type="button" data-target="#modal-form" data-toggle="modal" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i></button></td>
                        </tr>
                    `);
                    });
                } else {
                    $("#table-stock tbody").append(`
                    <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                `);
                }

                // Update info
                const start = (pagination.current_page - 1) * pagination.per_page + 1;
                const end = start + data.length - 1;
                $('#example1_info').text(`Showing ${start} to ${end} of ${pagination.total} entries`);

                // Render pagination
                let paginationHTML = '';

                // Previous button
                paginationHTML += `
                <li class="paginate_button page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                    <a href="#" class="page-link" data-page="${pagination.current_page - 1}">Previous</a>
                </li>
            `;

                // Numbered pages
                for (let i = 1; i <= pagination.last_page; i++) {
                    paginationHTML += `
                    <li class="paginate_button page-item ${i === pagination.current_page ? 'active' : ''}">
                        <a href="#" class="page-link" data-page="${i}">${i}</a>
                    </li>
                `;
                }

                // Next button
                paginationHTML += `
                <li class="paginate_button page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                    <a href="#" class="page-link" data-page="${pagination.current_page + 1}">Next</a>
                </li>
            `;

                $('#example1_paginate ul.pagination').html(paginationHTML);

            },
            complete: function() {
                $("#loading").hide(); // Sembunyikan loading setelah selesai
            }
        })
    }

    function addStock() {
        $("#btnStock").prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        var product = $("#group").val();
        var satuan = $("#satuan").val();
        var stock = $("#stock").val();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/api/update-stock',
            contentType: 'application/json',
            data: JSON.stringify({ // Data yang dikirim ke API
                satuan: satuan,
                stock: stock,
                group: product
            }),
            success: function(response) {

                if (response.success) {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    loadData(1);
                } else {
                    Toast.fire({
                        icon: "error",
                        title: response.message
                    });
                }
            },
            complete: function() {
                $("#btnStock").prop("disabled", false).html("Submit"); // Kembalikan tombol seperti semula
                $("#closeModal").click();

            }
        })
    }

    function checkAds(val) {
        console.log(val.value);
    }

    function showImage(image, path) {
        document.getElementById("imageBanner").src = path + '/' + image;
    }

    function updateData(id, productName, stock, remainingStock, group) {
        document.getElementById("group").value = group;
        document.getElementById("stock").value = "";
        document.getElementById("form").action = `/update-stock`;
        document.getElementById("titleModal").innerHTML = 'Tambah Stock';


        // let requiredImage = document.getElementById("imagePick");
        // requiredImage.removeAttribute('required', '')

    }

    function addData() {
        // document.getElementById("productName").value = "";
        // document.getElementById("productCategory").value = "";
        // document.getElementById("price").value = "";
        // // document.getElementById("labelNamePhoto").innerHTML = '';
        // document.getElementById("group").value = '';
        // // document.getElementById("labelPhoto").hidden = true;
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

    // function showAddTopping(id) {
    //     document.getElementById("modalTitleTopping").innerHTML = "Add Topping";
    //     document.getElementById("idProductTopping").value = id;

    // }

    // function addTopping(id) {
    //     let idProduct = document.getElementById("idProductTopping").value;
    //     let price = document.getElementById("toppingPrice").value;
    //     let topping = document.getElementById("toppingName").value;
    //     console.log(topping);
    //     $.ajax({
    //         type: "post",
    //         url: '/add-topping',
    //         data: {
    //             toppingName: topping,
    //             price: price,
    //             idProduct: idProduct,
    //             _token: $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             if (response.status == true) {
    //                 Toast.fire({
    //                     icon: "success",
    //                     title: response.message
    //                 });

    //                 document.getElementById("toppingPrice").value = "",
    //                     document.getElementById("toppingName").value = ""
    //             }
    //         }

    //     })
    // }

    // function showDataTopping(id) {
    //     $("#tableTopping tbody").empty();
    //     $.ajax({
    //         type: "get",
    //         url: `/get-data-topping/${id}`,
    //         dataType: 'json',
    //         success: function(response) {
    //             let data = response.data;
    //             let k = 1;

    //             for (let i = 0; i < data.length; i++) {
    //                 var tr = $("<tr>");
    //                 tr.append("<td>" + k++ + "</td>");
    //                 tr.append("<td>" + data[i].topping_name + "</td>");
    //                 tr.append("<td>" + data[i].price + "</td>");
    //                 tr.append(`
    //                 <td>
    //                 <button onclick="updateData('${data[i].id}','${data[i].product_name}','${data[i].product_category}','${data[i].price}','${data[i].is_active}','${data[i].photo}'.'${data[i].group}')" type="button" data-target="#modal-form" data-toggle="modal" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i></button>
    //                 <button type="button" onclick="deleteData('${data[i].id}')" data-target="#modal-delete" data-toggle="modal" class="btn btn-secondary btn-sm"><i class="fa fa-trash"></i></button>
    //                 </td>`)
    //                 $("#tableTopping tbody").append(tr);
    //             }
    //         }
    //     })
    // }
</script>
@endsection