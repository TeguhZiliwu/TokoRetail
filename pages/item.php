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
                                    <button type="button" class="btn btn-info waves-effect waves-light float-end mb-2" id="btnTemplate" title="Download Template" tabindex="0">
                                        <i class="fe-info"></i>
                                    </button>
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end me-1 mb-2" id="btnImport">
                                        <i class="fe-upload me-1"></i> Import
                                    </button>
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end me-1 mb-2" id="btnExport">
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
                                                <th>Kode Barang</th>
                                                <th>Barcode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Deskripsi Barang</th>
                                                <th>Kategori Barang</th>
                                                <th>Satuan</th>
                                                <th>Jenis Barang</th>
                                                <th>Harga Jual</th>
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
                                        <label for="txtItemCode" class="form-label">Kode Barang</label>
                                        <input type="text" class="form-control" id="txtItemCode" placeholder="[AUTO]" autocomplete="off" maxlength="20" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txtItemBarcode" class="form-label">Barcode Barang</label>
                                        <input type="text" class="form-control maintenance-form" id="txtItemBarcode" placeholder="Masukkan Barcode Barang" autocomplete="off" maxlength="200">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txtItemName" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control maintenance-form" id="txtItemName" placeholder="Masukkan Kategori" autocomplete="off" maxlength="100">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ddCategory" class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-control maintenance-form" data-toggle="select2" data-width="100%" id="ddCategory">
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ddUOM" class="form-label">Satuan <span class="text-danger">*</span></label>
                                        <select class="form-control maintenance-form" data-toggle="select2" data-width="100%" id="ddUOM">
                                        </select>
                                    </div>
                                </div> <!-- end col-->

                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="ddItemType" class="form-label">Jenis Barang <span class="text-danger" style="display: none;">*</span></label>
                                        <select class="form-control maintenance-form" data-toggle="select2" data-width="100%" id="ddItemType">
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txtItemDesc" class="form-label">Deskripsi Barang <span class="text-danger">*</span></label>
                                        <textarea class="form-control maintenance-form" id="txtItemDesc" rows="5" placeholder="Masukkan Deskripsi Barang" autocomplete="off" maxlength="2000"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txtSellingPrice" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-text">Rp</div>
                                            <input type="text" class="form-control maintenance-form autonumber" id="txtSellingPrice" placeholder="Masukkan Harga Jual" autocomplete="off" data-maximum-value="2147483647" data-minimum-value="0">
                                        </div>
                                    </div>
                                </div> <!-- end col-->
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <button type="button" class="btn btn-success waves-effect waves-light float-end" id="btnAddNewPicture">
                                        <i class="fe-plus me-1"></i> Tambah Foto
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Foto Barang <span class="sub-header">(max 5 foto)</span></label>
                                        <table id="tblItemPicture" class="table table-hover nowrap" style="width: 100%;">
                                            <thead class="table-header-dark">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Foto</th>
                                                    <th class="text-center" style="min-width: 50px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
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

<!-- Standard modal content -->
<div id="modalInvalidUpload" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ringkasan Import</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-6 col-6">
                        <table>
                            <tr>
                                <td>Total Import Berhasil</td>
                                <td>:</td>
                                <td><strong id="totalSuccessImport">0</strong></td>
                            </tr>
                            <tr>
                                <td>Total Import Gagal</td>
                                <td>:</td>
                                <td><strong id="totalInvalidImport">0</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-6">
                        <button type="button" class="btn btn-success waves-effect waves-light float-end mb-2" id="btnInvalidExport">
                            <i class="fe-download me-1"></i> Export
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tblInvalidData" class="table w-100 nowrap">
                            <thead class="table-header-dark">
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Jenis Barang</th>
                                    <th>Deskripsi Barang</th>
                                    <th>Harga Jual</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalViewPict" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Pratinjau</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="imgPreview" width="100%" height="100%" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<form method="post" enctype="multipart/form-data" id="fileUploadForm" onsubmit="return dataUpload();">
    <input type="file" id="fileUpload" name="fileUpload" style="display:none;" name="dir" accept=".XLS, .XLSX" />
</form>

<?php
include "../components/bottomapp.php";
?>
<script src="../assets/script/item.js"></script>