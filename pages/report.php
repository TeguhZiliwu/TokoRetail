<?php
include "../components/topapp.php";
?>

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Report</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="mb-3">
                                <label for="ddReportType" class="form-label">Jenis Laporan</label>
                                <select class="form-control maintenance-form" data-toggle="select2" data-width="100%" id="ddReportType">
                                    <option value="OUT" selected>Penjualan</option>
                                    <option value="IN">Stok Masuk</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="mb-3">
                                <label for="ddItemCode" class="form-label">Barang</label>
                                <select class="form-control maintenance-form" data-toggle="select2" data-width="100%" id="ddItemCode">
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="mb-3">
                                <label for="ddPeriod" class="form-label">Periode</label>
                                <select class="form-control maintenance-form" data-toggle="select2" data-width="100%" id="ddPeriod">
                                    <option value="Daily">Harian</option>
                                    <option value="Monthly">Bulanan</option>
                                    <option value="Yearly">Tahunan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="mb-3">
                                <label for="txtFromDate" class="form-label">Dari Tanggal</label>
                                <input type="text" class="form-control maintenance-form" id="txtFromDate" placeholder="Masukkan Tanggal" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="mb-3">
                                <label for="txtToDate" class="form-label">Ke Tanggal</label>
                                <input type="text" class="form-control maintenance-form" id="txtToDate" placeholder="Masukkan Tanggal" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xl-4">
                                <label for="txtToDate" class="form-label">Cari Data</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" placeholder="Cari Data" aria-label="Cari Data" id="txtSearch" autocomplete="off">
                                <button class="btn input-group-text btn-info waves-effect waves-light" type="button" id="btnSearch"><i class="fe-search"></i> Cari</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <button type="button" class="btn btn-success waves-effect waves-light float-end" id="btnExport">
                                <i class="fe-download me-1"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="tblMainData" class="table w-100 nowrap">
                                <thead class="table-header-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Transaksi ID</th>
                                        <th>Jenis Transaksi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Jenis Barang</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Diskon</th>
                                        <th>Sub Total</th>
                                        <th>Catatan</th>
                                        <th>Transaksi Oleh</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="float-end" id="lblTotal">Total : Rp <strong>0</strong></label>
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
<script src="../assets/script/report.js"></script>