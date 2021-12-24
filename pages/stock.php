<?php
include "../components/topapp.php";
?>

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Stock</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-bordered">
                        <li class="nav-item">
                            <a href="#tabs-data" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                List Data
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-detail" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                Stock In
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="tabs-data">
                            <div class="row">
                                <div class="col-xl-4">
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" placeholder="Cari Data" aria-label="Cari Data" id="txtSearch" autocomplete="off">
                                        <button class="btn input-group-text btn-info waves-effect waves-light" type="button" id="btnSearch"><i class="fe-search"></i> Cari</button>
                                    </div>
                                </div>
                                <div class="col-xl-8">
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end mb-2" id="btnExport">
                                        <i class="fe-download me-1"></i> Export
                                    </button>
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end me-1 mb-2" id="btnAdd">
                                        <i class="fe-plus me-1"></i> Tambah Stock
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <table id="tblMainData" class="table w-100 nowrap">
                                        <thead class="table-header-dark">
                                            <tr>
                                                <th>No.</th>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Kategori</th>
                                                <th>Satuan</th>
                                                <th>Jenis Barang</th>
                                                <th>Harga Jual</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-detail">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="ddItemCode" class="form-label">Barang <span class="text-danger">*</span></label>
                                        <select class="form-control maintenance-form" data-toggle="select2" data-width="100%" id="ddItemCode">
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="txtItemName" class="form-label">Nama Barang</label>
                                        <input type="text" class="form-control" id="txtItemName" placeholder="Nama Barang" autocomplete="off" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="txtCategory" class="form-label">Kategori</label>
                                        <input type="text" class="form-control" id="txtCategory" placeholder="Kategori" autocomplete="off" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="txtUOM" class="form-label">Satuan</label>
                                        <input type="text" class="form-control" id="txtUOM" placeholder="Satuan" autocomplete="off" disabled>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="txtItemType" class="form-label">Jenis Barang</label>
                                        <input type="text" class="form-control" id="txtItemType" placeholder="Jenis Barang" autocomplete="off" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txtPurchasePrice" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-text">Rp</div>
                                            <input type="text" class="form-control maintenance-form autonumber" id="txtPurchasePrice" placeholder="Masukkan Harga Beli" autocomplete="off" data-maximum-value="2147483647" data-minimum-value="0">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txtQty" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control maintenance-form autonumber" id="txtQty" placeholder="Masukkan Jumlah" autocomplete="off" data-maximum-value="2147483647" data-minimum-value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end" id="btnAddToList">
                                        <i class="fe-plus me-1"></i> Masukkan Kedalam List
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">List Barang</label>
                                        <table id="tblItemList" class="table table-hover nowrap" style="width: 100%;">
                                            <thead class="table-header-dark">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Kategori</th>
                                                    <th>Satuan</th>
                                                    <th>Jenis Barang</th>
                                                    <th>Harga Beli</th>
                                                    <th>Jumlah</th>
                                                    <th class="text-center" style="min-width: 50px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <label for="txtRemark" class="form-label">Catatan</label>
                                        <textarea class="form-control maintenance-form" id="txtRemark" rows="5" placeholder="Masukkan Catatan" autocomplete="off" maxlength="2000"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12">
                                    <button type="button" class="btn btn-success waves-effect waves-light" id="btnSave"><i class="fe-check-circle me-1"></i> Simpan</button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light" id="btnCancel"><i class="fe-x me-1"></i> Batalkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col-->
    </div>
    <!-- end row-->

</div> <!-- container -->

<?php
include "../components/bottomapp.php";
?>
<script src="../assets/script/stock.js"></script>