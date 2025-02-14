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
            <div class="card rounded mb-3">
                <div class="card-body">
                    <h6 class="btn btn-outline-primary"><span class="font-weight-bold">Bulan</span> : <?= $bulan  ?></h6>
                    <button class="btn btn-outline-primary size-btn mb-2" onclick="addData()" data-toggle="modal" data-target="#modal-form">Pilih Bulan</button>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th>Barang Masuk</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($history as $row) { ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->product_name ?></td>
                                    <td class="font-weight-bold"><?= $row->incoming_stock ?></td>
                                    <td><?= $row->date ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
                <h5 class="modal-title" id="titleModal">Filter Bulan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" method="get" id="form" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Bulan</label>
                        <div class="col-sm-10">
                            <input type="month" required class="form-control" id="bulan" value="{{old('bulan')}}" name="bulan">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="height:15px;"></div>
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
            <div class="bg-chocolate rounded-modal" style="color: #967E76;height:15px;"></div>
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
</script>
@endsection