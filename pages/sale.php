<?php
include "../components/topapp.php";
?>

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Sale</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->


    <div class="row">
        <div class="col-xl-12">
            <button type="button" class="btn btn-info waves-effect waves-light float-end mb-2" id="btnTemplate" title="Download Template" tabindex="0">
                <i class="fe-info"></i>
            </button>
            <button type="button" class="btn btn-success waves-effect waves-light float-end me-1 mb-2" id="btnImport">
                <i class="fe-upload me-1"></i> Import
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="ddItemCode" class="form-label">Barang <span class="text-danger">*</span></label>
                                <select class="form-control" data-toggle="select2" data-width="100%" id="ddItemCode">
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
                            <div class="mb-3">
                                <label for="txtItemType" class="form-label">Jenis Barang</label>
                                <input type="text" class="form-control" id="txtItemType" placeholder="Jenis Barang" autocomplete="off" disabled>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="txtItemDesc" class="form-label">Deskripsi Barang</label>
                                <textarea class="form-control" id="txtItemDesc" rows="5" placeholder="Deskripsi Barang" autocomplete="off" disabled></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="txtCurrentStock" class="form-label">Stock Tersedia</label>
                                <input type="text" class="form-control" id="txtCurrentStock" placeholder="Stock Tersedia" autocomplete="off" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="txtPurchasePrice" class="form-label">Harga Jual</label>
                                <div class="input-group">
                                    <div class="input-group-text">Rp</div>
                                    <input type="text" class="form-control maintenance-form autonumber" id="txtPurchasePrice" placeholder="Harga Jual" autocomplete="off" data-maximum-value="2147483647" data-minimum-value="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="txtQty" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control autonumber" id="txtQty" placeholder="Masukkan Jumlah" autocomplete="off" data-maximum-value="2147483647" data-minimum-value="0">
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-check form-check-blue">
                                            <input class="form-check-input rounded-circle" type="radio" id="rbNoDiscount" name="discount" checked="" value="No Discount">
                                            <label class="form-check-label" for="rbNoDiscount">Tidak diskon</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-check form-check-blue">
                                            <input class="form-check-input rounded-circle" type="radio" id="rbDiscountPercentage" name="discount" value="Percentage">
                                            <label class="form-check-label" for="rbDiscountPercentage">Diskon (%)</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-check form-check-blue">
                                            <input class="form-check-input rounded-circle" type="radio" id="rbDiscountFixed" name="discount" value="Fixed">
                                            <label class="form-check-label" for="rbDiscountFixed">Diskon (Rp)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3" id="divFixedDiscount">
                                <label for="txtDiscount" class="form-label">Total Diskon <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-text">Rp</div>
                                    <input type="text" class="form-control autonumber" id="txtDiscount" placeholder="Masukkan Total Diskon" autocomplete="off" data-maximum-value="2147483647" data-minimum-value="0">
                                </div>
                            </div>
                            <div class="mb-3" id="divPercentageDiscount">
                                <label for="ddPercentageDiscount" class="form-label">Total Diskon <span class="text-danger">*</span></label>
                                <select class="form-control" data-toggle="select2" data-width="100%" id="ddPercentageDiscount">
                                </select>
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
                                            <th>Diskon</th>
                                            <th>Sub Total</th>
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
                        <div class="col-md-12">
                            <label class="float-end" id="lblTotal">Total : Rp <strong>0</strong></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="txtRemark" class="form-label">Catatan</label>
                                <textarea class="form-control" id="txtRemark" rows="5" placeholder="Masukkan Catatan" autocomplete="off" maxlength="2000"></textarea>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="txtTotalPayment" class="form-label">Total Bayar <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-text">Rp</div>
                                    <input type="text" class="form-control maintenance-form autonumber" id="txtTotalPayment" placeholder="Masukkan Total Bayar" autocomplete="off" data-maximum-value="2147483647" data-minimum-value="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="txtTotalChange" class="form-label">Kembalian</label>
                                <div class="input-group">
                                    <div class="input-group-text">Rp</div>
                                    <input type="text" class="form-control maintenance-form" id="txtTotalChange" placeholder="0" autocomplete="off">
                                </div>
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
                    <!-- <div class="col-md-6 col-6">
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
                    </div> -->
                    <div class="col-md-12">
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
                                    <th>Urutan</th>
                                    <th>Kode Barang</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
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

<form method="post" enctype="multipart/form-data" id="fileUploadForm" onsubmit="return dataUpload();">
    <input type="file" id="fileUpload" name="fileUpload" style="display:none;" name="dir" accept=".XLS, .XLSX" />
</form>

<?php
include "../components/bottomapp.php";
?>
<script src="../assets/script/sale.js"></script>