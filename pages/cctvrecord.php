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
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end mb-2" id="btnAdd">
                                        <i class="fe-plus me-1"></i> Tambah Record
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <table id="tblMainData" class="table w-100 nowrap">
                                        <thead class="table-header-dark">
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama File</th>
                                                <th>Rekaman CCTV</th>
                                                <th>Catatan</th>
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
                                        <label for="txtCCTVRecordID" class="form-label">CCTV Record ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="txtCCTVRecordID" placeholder="[AUTO]" autocomplete="off" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Rekaman CCTV</label>
                                        <div class="input-group input-group-file">
                                            <input type="file" class="form-control custom-file-input" id="txtCCTVFileUpload" hidden accept=".WMV, .M4V, .AVI, .MPG, .MPEG, .MP4, .WEBM">
                                            <input type="text" class="form-control maintenance-form custom-file-group" value="Pilih Rekaman CCTV" readonly>
                                            <span class="input-group-text custom-span-group">Pilih Rekaman</span>
                                        </div>
                                        <button type="button" class="btn btn-blue waves-effect waves-light mt-1" id="btnView" disabled>Lihat Rekaman <i class="fe-play"></i></button>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txtRemark" class="form-label">Catatan <span class="text-danger">*</span></label>
                                        <textarea class="form-control maintenance-form" id="txtRemark" rows="5" placeholder="Masukkan Catatan" autocomplete="off" maxlength="2000"></textarea>
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

<div class="modal fade" id="modalViewVideo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Rekaman CCTV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <video id="frameCCTVRecord" src="" controls></video>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
include "../components/bottomapp.php";
?>
<script src="../assets/script/cctvrecord.js"></script>