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
            </div>
        </div> <!-- end col-->
    </div>
    <!-- end row-->

</div> <!-- container -->

<?php
include "../components/bottomapp.php";
?>
<script src="../assets/script/gainloss.js"></script>