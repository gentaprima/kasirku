<?php

use Illuminate\Support\Facades\Session;
?>
@extends('master')

@section('title-link','Beranda')
@section('sub-title-link','Input Barang ')
@section('title','Input Barang')

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
                        <li class="breadcrumb-item active">Input Barang </li>
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
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="card p-4 rounded mb-3">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left text-primary font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Pilih Makanan
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <!-- <input type="text" onkeydown="searchMakanan(this)" class="form-control mb-3" placeholder="Cari Makanan"> -->
                                        <div class="search-container mb-3">
                                            <input type="text" id="searchMakananInput" oninput="toggleClearButtonMakanan()" onkeydown="searchMakanan(this)" class="form-control" placeholder="Cari Makanan">
                                            <span class="clear-btn" onclick="clearSearchMakanan()">✕</span>
                                        </div>
                                        <ul class="list-group makanan">
                                            <!-- <li class="list-group-item d-flex justify-content-between align-items-center">
                                                An item <span class="text-primary fw-bold badge btn-primary">+</span>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed text-primary font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Pilih Minuman
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <!-- <input type="text" onkeydown="searchMinuman(this)" class="form-control mb-3" placeholder="Cari Minuman"> -->
                                        <div class="search-container-minuman mb-3">
                                            <input type="text" id="searchMinumanInput" oninput="toggleClearButtonMinuman()" onkeydown="searchMinuman(this)" class="form-control" placeholder="Cari Minuman">
                                            <span class="clear-btn-minuman" onclick="clearSearchMinuman()">✕</span>
                                        </div>
                                        <ul class="list-group minuman">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed text-primary font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Pilih Topping
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <!-- <input type="text" onkeydown="searchMinuman(this)" class="form-control mb-3" placeholder="Cari Minuman"> -->
                                        <div class="search-container-topping mb-3">
                                            <input type="text" id="searchMinumanInput" oninput="toggleClearButtonMinuman()" onkeydown="searchTopping(this)" class="form-control" placeholder="Cari Topping">
                                            <span class="clear-btn-topping" onclick="clearSearchTopping()">✕</span>
                                        </div>
                                        <ul class="list-group topping">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="card p-4 rounded mb-3">
                        <div class="row d-flex justify-content-between align-items-center mb-4">
                            <div class="col">
                                <h4>Produk</h4>
                            </div>
                            <div class="col text-right">
                                <button type="button" onClick="edit()" class="btn btn-primary" data-toggle="modal" data-target="#modal-edit">Edit</button>
                            </div>
                        </div>

                        <table id="table-cart">
                            <thead>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>total</th>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th id="total" colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                        <hr>
                        <p style="display: none;" id="totalInput"></p>
                        <button class="btn btn-primary" onClick="addTranscation(this)">Process</button>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="modal-cart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <input type="hidden" value="" id="idMakanan">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Pilih Topping</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="" id="topping">
                                <option value="Tanpa Topping">Tanpa Topping</option>
                                <option value="Keju">Keju</option>
                                <option value="Telor">Telor</option>
                                <option value="Kornet">Kornet</option>
                            </select>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                <button type="button" onclick="addCart()" class="btn btn-primary">Simpan</button>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="color: red;height:15px;"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul id="cart-list" class="list-group">
                    <!-- Data Produk akan dimasukkan melalui AJAX -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                </form>
            </div>
            <div class="bg-chocolate rounded-modal" style="color: red;height:15px;"></div>
        </div>
    </div>
</div>
<script>
    let debounceTimerMakanan;
    let debounceTimerMinuman;
    let debounceTimerTopping;
    loadDataMakanan("");
    loadDataMinuman("");
    loadDataTopping("");
    getCart();

    function toggleClearButtonMakanan() {
        let input = document.getElementById("searchMakananInput");
        let clearBtn = document.querySelector(".clear-btn");

        // Jika ada teks, tampilkan tombol "X", jika kosong sembunyikan
        if (input.value.length > 0) {
            clearBtn.style.display = "block";
        } else {
            clearBtn.style.display = "none";
        }
    }

    function toggleClearButtonMinuman() {
        let input = document.getElementById("searchMinumanInput");
        let clearBtn = document.querySelector(".clear-btn-minuman");

        // Jika ada teks, tampilkan tombol "X", jika kosong sembunyikan
        if (input.value.length > 0) {
            clearBtn.style.display = "block";
        } else {
            clearBtn.style.display = "none";
        }
    }

    function clearSearchMakanan() {
        let input = document.getElementById("searchMakananInput");
        let clearBtn = document.querySelector(".clear-btn");
        input.value = ""; // Kosongkan input
        input.focus(); // Fokus kembali ke input
        loadDataMakanan(""); // Panggil fungsi pencarian untuk update hasil
        clearBtn.style.display = "none";

    }
    function clearSearchMinuman() {
        let input = document.getElementById("searchMinumanInput");
        let clearBtn = document.querySelector(".clear-btn-minuman");
        input.value = ""; // Kosongkan input
        input.focus(); // Fokus kembali ke input
        loadDataMinuman(""); // Panggil fungsi pencarian untuk update hasil
        clearBtn.style.display = "none";

    }

    function edit() {
        $idUsers = $("#idUsers").html();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/api/get-cart',
            contentType: 'application/json',
            data: JSON.stringify({
                idUsers: $idUsers,
            }),
            success: function(response) {
                console.log(response);
                // Kosongkan List Group sebelum memasukkan data baru
                $("#cart-list").empty();

                response.data.forEach(function(item) {

                    let listItem = `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span>${item.product_name}</span>
                                    ${item.topping !== "Tanpa Topping" ? `<small class="text-muted ml-2">+ ${item.topping}</small>` : ""}
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-chocolate badge-pill mr-3">${item.quantity} pcs</span>
                                    <button class="btn btn-primary btn-sm mr-1" onClick="addCartMinuman(this,${item.id_product},'${item.topping}')">+</button>
                                    <button class="btn btn-default btn-sm" onClick="minCart(${item.id_product},'${item.topping}')">-</button>
                                </div>
                            </li>
                        `;
                    $("#cart-list").append(listItem);
                });
            }
        })
    }



    function loadDataMakanan(key) {
        clearTimeout(debounceTimerMakanan); // Hapus timer sebelumnya

        debounceTimerMakanan = setTimeout(() => {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/api/get-product',
                contentType: 'application/json',
                data: JSON.stringify({
                    category: "Makanan",
                    search: key
                }),
                beforeSend: function() {
                    $(".list-group.makanan").html('<li class="list-group-item text-center">Loading...</li>'); // Tampilkan indikator loading
                },
                success: function(response) {
                    $(".list-group.makanan").empty(); // Bersihkan hasil sebelumnya

                    response.data.data.forEach(function(item) {
                        $(".list-group.makanan").append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ${item.product_name}   
                        <button class="text-primary fw-bold badge btn-primary btn-addCart" 
                            onclick="addCartMinuman(this, ${item.id}, 'Tanpa Topping')">
                            +
                        </button>
                    </li>
                `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching products:", error);
                    $(".list-group.makanan").html('<li class="list-group-item text-danger text-center">Gagal mengambil data</li>'); // Tampilkan pesan error
                }
            });
        }, 500); // Delay 500ms sebelum request dikirim
    }
    function loadDataTopping(key) {
        clearTimeout(debounceTimerTopping); // Hapus timer sebelumnya

        debounceTimerTopping = setTimeout(() => {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/api/get-product',
                contentType: 'application/json',
                data: JSON.stringify({
                    category: "Bahan",
                    search: key
                }),
                beforeSend: function() {
                    $(".list-group.topping").html('<li class="list-group-item text-center">Loading...</li>'); // Tampilkan indikator loading
                },
                success: function(response) {
                    $(".list-group.topping").empty(); // Bersihkan hasil sebelumnya

                    response.data.data.forEach(function(item) {
                        $(".list-group.topping").append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ${item.product_name}   
                        <button class="text-primary fw-bold badge btn-primary btn-addCart" 
                            onclick="addCartMinuman(this, ${item.id}, 'Tanpa Topping')">
                            +
                        </button>
                    </li>
                `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching products:", error);
                    $(".list-group.topping").html('<li class="list-group-item text-danger text-center">Gagal mengambil data</li>'); // Tampilkan pesan error
                }
            });
        }, 500); // Delay 500ms sebelum request dikirim
    }

    function loadDataMinuman(key) {
        clearTimeout(debounceTimerMinuman); // Hapus timer sebelumnya

        debounceTimerMinuman = setTimeout(() => {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/api/get-product',
                contentType: 'application/json',
                data: JSON.stringify({
                    category: "Minuman",
                    search: key
                }),
                beforeSend: function() {
                    $(".list-group.minuman").html('<li class="list-group-item text-center">Loading...</li>'); // Indikator loading
                },
                success: function(response) {
                    $(".list-group.minuman").empty();
                    response.data.data.forEach(function(item) {
                        $(".list-group.minuman").append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${item.product_name}
                            <button class="text-primary fw-bold badge btn-primary btn-addCart" 
                                onclick="addCartMinuman(this, ${item.id}, 'Tanpa Topping')">
                                +
                            </button>
                        </li>
                    `);
                    });
                },
                error: function() {
                    $(".list-group.minuman").html('<li class="list-group-item text-danger text-center">Gagal mengambil data</li>');
                }
            });
        }, 500); // Delay 500ms sebelum request dikirim
    }

    function searchMakanan(val) {
        loadDataMakanan(val.value);
    }

    function searchMinuman(val) {
        loadDataMinuman(val.value);
    }
    function searchTopping(val) {
        loadDataTopping(val.value);
    }

    function addCart() {
        var idUsers = $("#idUsers").html();
        var idProduct = $("#idMakanan").val();
        var topping = $("#topping").val();

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/api/add-cart',
            contentType: 'application/json',
            data: JSON.stringify({
                idProduct: idProduct,
                idUsers: idUsers,
                topping: topping
            }),
            success: function(response) {
                console.log(response);
                if (response.success) {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    getCart();
                } else {
                    Toast.fire({
                        icon: "error",
                        title: response.message
                    });
                }


            }
        })
    }

    function addCartMinuman(button, id, topping) {
        var idUsers = $("#idUsers").html();
        var btn = $(button); // Ambil tombol yang diklik
        var originalText = btn.html(); // Simpan teks asli tombol

        // Ubah tombol jadi loading & disable
        btn.html('<span class="spinner-border spinner-border-sm"></span> Loading...')
            .prop("disabled", true);

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/api/add-cart',
            contentType: 'application/json',
            data: JSON.stringify({
                idProduct: id,
                idUsers: idUsers,
                topping: topping
            }),
            success: function(response) {

                if (response.success) {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    getCart();
                    edit();
                } else {
                    Toast.fire({
                        icon: "error",
                        title: response.message
                    });
                }
            },
            error: function() {
                Toast.fire({
                    icon: "error",
                    title: "Terjadi kesalahan, coba lagi!"
                });
            },
            complete: function() {
                // Kembalikan tombol ke kondisi semula setelah request selesai
                btn.html(originalText).prop("disabled", false);
            }
        });
    }

    function minCart(id, topping) {
        var idUsers = $("#idUsers").html();

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/api/min-cart',
            contentType: 'application/json',
            data: JSON.stringify({
                idProduct: id,
                idUsers: idUsers,
                topping: topping
            }),
            success: function(response) {
                console.log(response);
                if (response.success) {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    getCart();
                    edit();
                } else {
                    Toast.fire({
                        icon: "error",
                        title: response.message
                    });
                }
            },
            error: function() {
                Toast.fire({
                    icon: "error",
                    title: "Terjadi kesalahan, coba lagi!"
                });
            }
        })
    }

    function getCart() {
        $idUsers = $("#idUsers").html();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/api/get-cart',
            contentType: 'application/json',
            data: JSON.stringify({
                idUsers: $idUsers,
            }),
            success: function(response) {
                console.log(response);
                $("#table-cart tbody").empty();
                response.data.forEach(function(item) {
                    let row = `
                <tr>
                    <td>${item.product_name} ${item.topping != "Tanpa Topping" ? ` + ${item.topping}` : ""}</td>
                    <td>${item.quantity}</td>
                    <td>Rp ${item.total.toLocaleString("id-ID")}</td>
                </tr>
            `;
                    $("#table-cart tbody").append(row);
                });
                $("#total").html(`Rp ${response.total_cart.toLocaleString("id-ID")}`)
                $("#totalInput").html(response.total_cart);
            }
        })
    }

    function setMakanan(id) {
        $("#idMakanan").val(id)
    }

    function addTranscation(button) {
        var idUsers = $("#idUsers").html();
        var total = $("#totalInput").html();
        var btn = $(button);
        var originalText = btn.html(); // Simpan teks asli tombol
        btn.html('<span class="spinner-border spinner-border-sm"></span> Loading...')
            .prop("disabled", true);

        $.ajax({
            dataType: 'json',
            type: 'post',
            url: '/api/add-transaction',
            contentType: 'application/json',
            data: JSON.stringify({
                idUsers: idUsers,
                total: total
            }),
            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    getCart();
                }
            },
            error : function(){
                Toast.fire({
                    icon: "error",
                    title: "Terjadi kesalahan, coba lagi!"
                });
            },
            complete: function() {
                // Kembalikan tombol ke kondisi semula setelah request selesai
                btn.html(originalText).prop("disabled", false);
            }
        })
    }
</script>
@endsection