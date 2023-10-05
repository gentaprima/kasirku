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
            <div class="card p-5 rounded mb-3">
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
                <table id="table" class="table table-striped mt-2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>No Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                    <ul class="pagination">
                        <li>Halaman</li>
                        <li class="paginate_button active mr-2"><a href="#" aria-controls="example1" id="current_page" data-dt-idx="1" tabindex="0">1</a></li>
                        <li>Dari</li>
                        <li class="ml-2" id="total_page"></li>
                        <li class="paginate_button next prev" id="example1_previous"><a href="#" aria-controls="example1" id="link_prev" data-dt-idx="0" tabindex="0"><i class="fa fa-chevron-left"></i></a></li>
                        <li class="paginate_button next" id="example1_next"><a id="link_next" href="" aria-controls="example1" data-dt-idx="2" tabindex="0"><i class="fa fa-chevron-right"></i></a></li>
                    </ul>
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
                        <label for="inputPassword" class="col-sm-2 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-10">
                            <input type="text" required class="form-control" id="fullName" value="{{old('fullName')}}" name="fullName" placeholder="Nama Lengkap">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" required class="form-control" id="email" value="{{old('email')}}" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">No Telepon</label>
                        <div class="col-sm-10">
                            <input type="text" required class="form-control" id="phoneNumber" value="{{old('phoneNumber')}}" name="phoneNumber" placeholder="No Telepon">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Status</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" required name="gender" value="Laki-laki" id="genderMan">
                                        <label for="genderMan">
                                            Laki-laki
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" required name="gender" value="Perempuan" id="genderWoman">
                                        <label for="genderWoman">
                                            Perempuan
                                        </label>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
            <div class="bg-red rounded-modal" style="color: red;height:15px;"></div>
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
            <div class="bg-red rounded-modal" style="color: red;height:15px;"></div>
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

    function loadData(page, search = '') {
        $("#table tbody").empty();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/show-karyawan?page=${page}&search=${search}`,
            success: function(response) {
                let data = response;
                let k = 1;
                if (data.data.current_page > 1) {
                    k = ((data.data.current_page * 10) - 10) + 1
                }
                let linkBanner = data.data.linkBanner

                // set pagination
                let buttonPrev = document.getElementById("link_prev")
                buttonPrev.href = "#"
                if (data.data.current_page == 1) {
                    $("#example1_previous").addClass("paginate_button next prev disabledd")
                    buttonPrev.removeAttribute("onclick")
                } else {
                    $("#example1_previous").removeClass("disabledd")
                    buttonPrev.setAttribute("onclick", `loadData(${data.data.current_page - 1})`)
                }

                let buttonNext = document.getElementById("link_next")
                buttonNext.href = "#"
                if (data.data.current_page == data.data.last_page) {
                    $("#example1_next").addClass("paginate_button next prev disabledd")
                    buttonNext.removeAttribute("onclick")
                } else {
                    buttonNext.setAttribute("onclick", ``)
                    $("#example1_next").removeClass("disabledd")
                    buttonNext.setAttribute("onclick", `loadData(${data.data.current_page + 1})`)
                }

                document.getElementById("current_page").innerHTML = data.data.current_page
                document.getElementById("total_page").innerHTML = data.data.last_page

                // set pagination


                for (let i = 0; i < data.data.data.length; i++) {
                    var tr = $("<tr>");
                    tr.append("<td>" + k++ + "</td>");
                    tr.append("<td>" + data.data.data[i].nama_lengkap + "</td>");
                    tr.append("<td>" + data.data.data[i].email + "</td>");
                    tr.append("<td>" + data.data.data[i].jenis_kelamin + "</td>");
                    tr.append("<td>" + data.data.data[i].nomor_telepon + "</td>");

                    tr.append(`
                    <td>
                    <button onclick="updateData('${data.data.data[i].id}','${data.data.data[i].nama_lengkap}','${data.data.data[i].email}','${data.data.data[i].tgl_lahir}','${data.data.data[i].jenis_kelamin}','${data.data.data[i].nomor_telepon}')" type="button" data-target="#modal-form" data-toggle="modal" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i></button>
                    <button type="button" onclick="deleteData('${data.data.data[i].id}')" data-target="#modal-delete" data-toggle="modal" class="btn btn-secondary btn-sm"><i class="fa fa-trash"></i></button>
                    </td>`)
                    $("#table tbody").append(tr);
                }
            }
        })
    }

    function checkAds(val) {
        console.log(val.value);
    }

    function showImage(image, path) {
        document.getElementById("imageBanner").src = path + '/' + image;
    }

    function updateData(id, fullName, email, date, gender, phoneNumber) {
        document.getElementById("fullName").value = fullName;
        document.getElementById("email").value = email;
        document.getElementById("phoneNumber").value = phoneNumber;
        document.getElementById("form").action = `/update-karyawan/${id}`;
        document.getElementById("titleModal").innerHTML = 'Perbarui Karyawan';
        if (gender == 'Laki-laki') {
            document.getElementById('genderMan').checked = true;
            document.getElementById('genderWoman').checked = false;
        } else {
            document.getElementById('genderMan').checked = false;
            document.getElementById('genderWoman').checked = true;
        }

    }

    function addData() {
        document.getElementById("fullName").value = "";
        document.getElementById("email").value = "";
        document.getElementById("phoneNumber").value = "";
        document.getElementById("form").action = '/add-karyawan';
        document.getElementById("titleModal").innerHTML = 'Tambah Karyawan';
    }

    function deleteData(id) {
        document.getElementById("btnDelete").href = `/delete-karyawan/${id}`;
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