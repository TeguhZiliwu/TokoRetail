<?php
include "../components/topapp.php";
?>

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Group</h4>
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
                                Detail
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
                                        <i class="fe-plus me-1"></i> Tambah Data
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <table id="tblMainData" class="table w-100 nowrap">
                                        <thead class="table-header-dark">
                                            <tr>
                                                <th>No.</th>
                                                <th>Kode Kategori</th>
                                                <th>Kategori</th>
                                                <th>Deskripsi Kategori</th>
                                                <th>Dibuat Oleh</th>
                                                <th>Tanggal Dibuat</th>
                                                <th>Diubah Oleh</th>
                                                <th>Tanggal Diubah</th>
                                                <th class="text-center">Aksi</th>
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
                                        <label for="txtCategoryCode" class="form-label">Kode Kategori</label>
                                        <input type="text" class="form-control" id="txtCategoryCode" placeholder="[AUTO]" autocomplete="off" maxlength="20" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txtCategory" class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control maintenance-form" id="txtCategory" placeholder="Masukkan Kategori" autocomplete="off" maxlength="20">
                                    </div>

                                    <div class="mb-3">
                                        <label for="txtCategoryDesc" class="form-label">Deskripsi Kategori</label>
                                        <textarea class="form-control maintenance-form" id="txtCategoryDesc" rows="5" placeholder="Masukkan Deskripsi Kategori" autocomplete="off" maxlength="100"></textarea>
                                    </div>
                                </div> <!-- end col-->
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
<script src="../assets/script/category.js"></script>