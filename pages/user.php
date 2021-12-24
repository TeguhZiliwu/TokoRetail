<?php
include "../components/topapp.php";
?>

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">User</h4>
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
                                                <th>User ID</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th>No. Telp</th>
                                                <th>Group ID</th>
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
                                        <label for="txtUserID" class="form-label">User ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control maintenance-form" id="txtUserID" placeholder="Masukkan User ID" autocomplete="off" maxlength="50">
                                    </div>

                                    <div class="mb-3">
                                        <label for="txtFullName" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control maintenance-form" id="txtFullName" placeholder="Masukkan Nama Lengkap" autocomplete="off" maxlength="100">
                                    </div>

                                    <div class="mb-3">
                                        <label for="txtPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="form-check form-check-blue float-end" id="divChangePassword" style="display: none;">
                                            <input class="form-check-input" type="checkbox" value="" id="cbbChangePassword">
                                            <label class="form-check-label" for="cbbChangePassword">Ubah Password</label>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <input type="password" class="form-control maintenance-form" id="txtPassword" placeholder="Masukkan Password" autocomplete="off" maxlength="200">
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="txtConfirmPassword" class="form-label">Ulangi Password <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" class="form-control maintenance-form" id="txtConfirmPassword" placeholder="Masukkan Password" autocomplete="off" maxlength="200">
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end col-->

                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="txtEmail" class="form-label">Email</label>
                                        <input type="text" class="form-control maintenance-form" id="txtEmail" placeholder="Masukkan Email" autocomplete="off" maxlength="100">
                                    </div>

                                    <div class="mb-3">
                                        <label for="txtTelpNo" class="form-label">No. Telpon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control maintenance-form" id="txtTelpNo" placeholder="Masukkan No Telpon" autocomplete="off" maxlength="20" data-toggle="input-mask" data-mask-format="+62 000-0000-00000">
                                    </div>

                                    <div class="mb-3">
                                        <label for="ddGroupID" class="form-label">Group ID <span class="text-danger">*</span></label>
                                        <select class="form-control maintenance-form" data-toggle="select2" data-width="100%" id="ddGroupID">
                                        </select>
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
<script src="../assets/script/user.js"></script>