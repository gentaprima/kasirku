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
                <button class="btn btn-outline-primary size-btn mb-3" onclick="addData()" data-toggle="modal" data-target="#modal-form">Tambah Data</button>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($dataProduct as $row) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row->product_name; ?></td>
                                <td><?= $row->price; ?></td>
                                <td><span class="badge badge-secondary"><?= $row->product_category; ?></span> </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php  if($row->remark == 1){  ?>
                                            Produk Kita
                                        <?php  }else if($row->remark == 2){ ?>
                                            Produk Luat
                                        <?php  }else if($row->remark == 3){ ?>
                                            Frozen Food
                                        <?php  } ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row->is_active == 1) { ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php } else { ?>
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button onclick="updateData('<?= $row->id ?>','<?= $row->product_name ?>','<?= $row->product_category ?>','<?= $row->price ?>','<?= $row->is_active ?>','<?= $row->group ?>','<?= $row->stock_reduction ?>','<?= $row->unit ?>','<?= $row->remark ?>')" type="button" data-target="#modal-form" data-toggle="modal" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i></button>
                                    <button type="button" onclick="deleteData('<?= $row->id ?>')" data-target="#modal-delete" data-toggle="modal" class="btn btn-secondary btn-sm"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
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
                            <input type="text" required class="form-control" id="productName" value="{{old('productName')}}" name="productName" placeholder="Nama Produk">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <input type="text" required class="form-control" id="price" value="{{old('price')}}" name="price" placeholder="Harga">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Kategori</label>
                        <div class="col-sm-10">
                            <select required class="form-control" id="productCategory" value="{{old('productCategory')}}" name="productCategory">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Status</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" required name="isActive" value="1" id="radioStatus1">
                                        <label for="radioStatus1">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" required name="isActive" value="0" id="radioStatus2">
                                        <label for="radioStatus2">
                                            Tidak Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Group</label>
                        <div class="col-sm-10">
                            <input type="text" name="group" id="group" class="form-control">
                            <span>Khusus indomie diisi dengan nama merk, yang lain boleh kosong, "Indomie Rendang"</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Pengurangan Stock</label>
                        <div class="col-sm-10">
                            <input type="text" name="stockReduction" id="stockReduction" class="form-control">
                            <span>Selain indomie double bisa dikosongkan</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Satuan</label>
                        <div class="col-sm-10">
                            <select name="unit" id="unit" class="form-control">
                                <option value="">Pilih Satuan</option>
                                <option value="Porsi">Porsi</option>
                                <option value="Pcs">Pcs</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2">Keterangan</label>
                        <div class="col-sm-10">
                            <select name="remark" id="remark" class="form-control">
                                <option value="">Pilih Keterangan</option>
                                <option value="1">Produk Kita</option>
                                <option value="2">Produk Luar</option>
                                <option value="3">Frozen Food</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
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

    function loadData(page, search = '') {
        $("#table tbody").empty();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/show-product?page=${page}&search=${search}`,
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
                    tr.append("<td>" + data.data.data[i].product_name + "</td>");
                    tr.append("<td>" + data.data.data[i].price + "</td>");
                    tr.append(`<td> <span class='badge badge-secondary'>${data.data.data[i].product_category}</span></td>`);
                    if (data.data.data[i].is_active == 1) {
                        tr.append("<td> <span class='badge badge-success'>Aktif</span></td>");
                    } else {
                        tr.append("<td> <span class='badge badge-danger'>Tidak Aktif</span></td>");

                    }

                    tr.append(` <td><span class='badge badge-secondary badge-photo' onclick="showImage('${data.data.data[i].photo}','${data.linkPhoto}')" data-target="#modal-image" data-toggle="modal">Lihat Foto</span></td>`);

                    tr.append(`
                    <td>
                    <button onclick="updateData('${data.data.data[i].id}','${data.data.data[i].product_name}','${data.data.data[i].product_category}','${data.data.data[i].price}','${data.data.data[i].is_active}','${data.data.data[i].photo}','${data.data.data[i].group}')" type="button" data-target="#modal-form" data-toggle="modal" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i></button>
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

    function updateData(id, productName, productCategory, price, isActive, group, stockReduction, unit, remark) {
        console.log(productCategory);
        document.getElementById("productName").value = productName;
        document.getElementById("productCategory").value = productCategory;
        document.getElementById("price").value = price;
        document.getElementById("group").value = group;
        document.getElementById("stockReduction").value = stockReduction;
        document.getElementById("unit").value = unit;
        document.getElementById("remark").value = remark;
        // document.getElementById("labelNamePhoto").innerHTML = photo;
        // document.getElementById("labelPhoto").hidden = false;
        document.getElementById("form").action = `/update-produk/${id}`;
        document.getElementById("titleModal").innerHTML = 'Perbarui Produk';
        if (isActive == 0) {
            document.getElementById('radioStatus1').checked = false;
            document.getElementById('radioStatus2').checked = true;
        } else {
            document.getElementById('radioStatus1').checked = true;
            document.getElementById('radioStatus2').checked = false;
        }

        // let requiredImage = document.getElementById("imagePick");
        // requiredImage.removeAttribute('required', '')

    }

    function addData() {
        document.getElementById("productName").value = "";
        document.getElementById("productCategory").value = "";
        document.getElementById("price").value = "";
        // document.getElementById("labelNamePhoto").innerHTML = '';
        document.getElementById("group").value = '';
        // document.getElementById("labelPhoto").hidden = true;
        document.getElementById("form").action = '/add-produk';
        document.getElementById("titleModal").innerHTML = 'Tambah Produk';
        document.getElementById('radioStatus1').checked = false;
        document.getElementById('radioStatus2').checked = false;
        let requiredImage = document.getElementById("imagePick");
        requiredImage.setAttribute('required', '')
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