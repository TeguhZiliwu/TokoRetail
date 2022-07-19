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
                    <ul class="nav nav-tabs nav-bordered">
                        <li class="nav-item">
                            <a href="#tabs-data" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                Laba Rugi Per Item
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-detail" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                Laba Rugi Per Periode
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="tabs-data">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" placeholder="Cari Data" aria-label="Cari Data" id="txtSearch" autocomplete="off">
                                        <button class="btn input-group-text btn-info waves-effect waves-light" type="button" id="btnSearch"><i class="fe-search"></i> Cari</button>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end" id="btnExport">
                                        <i class="fe-download me-1"></i> Export
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
                                                <th>Harga Jual</th>
                                                <th>Harga Beli</th>
                                                <th>Untung / Rugi (Rp)</th>
                                                <th>Info</th>
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
                        <div class="tab-pane" id="tabs-detail">

                            <div class="row">
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
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <button type="button" class="btn btn-info waves-effect waves-light float-end " id="btnShowProfitAndLoss">
                                        <i class="fe-search me-1"></i> Tampilkan Data
                                    </button>
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end me-2" id="btnExportProfitLoss">
                                        <i class="fe-download me-1"></i> Export
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <table id="tblProfitAndLoss" class="table table-bordered w-100 nowrap">
                                        <thead>
                                            <tr>
                                                <td colspan="6" id="lblPeriode">Periode : - s/d -</td>
                                            </tr>
                                            <tr>
                                                <td>Kode Barang</td>
                                                <td>Nama Barang</td>
                                                <td>Total Jumlah Terjual</td>
                                                <td>Total Harga Terjual</td>
                                                <td>Total Modal Awal</td>
                                                <td>Laba</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                                <td>Penjualan Barang</td>
                                                <td class="table-profit"><span id="lblTotalOut">Rp 0</span></td>
                                            </tr>
                                            <tr>
                                                <td>Pembelian Barang</td>
                                                <td class="table-profit"><span id="lblTotalIn">Rp 0</span></td>
                                            </tr>
                                            <tr class="table-active">
                                                <td data-fill-color="ECECEC">Laba Bersih Usaha</td>
                                                <td class="table-profit" data-fill-color="ECECEC"><span id="lblTotalProfit">Rp 0</span></td>
                                            </tr> -->
                                        </tbody>
                                    </table>
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
<script src="../assets/script/gainloss.js"></script>