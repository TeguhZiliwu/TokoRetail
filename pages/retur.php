<?php
include "../components/topapp.php";
?>

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Retur</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-xl-6">
                            <label for="txtSearch" class="form-label">Cari Transaction ID</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="txtSearch" placeholder="Masukkan Transaction ID" autocomplete="off">
                                <button class="btn input-group-text btn-info waves-effect waves-light" type="button" id="btnSearch"><i class="fe-search"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="txtTransactionID" class="form-label">Transaction ID</label>
                                <input type="text" class="form-control" id="txtTransactionID" placeholder="Transaction ID" autocomplete="off" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="txtTransactionDate" class="form-label">Tanggal Transaksi</label>
                                <input type="text" class="form-control" id="txtTransactionDate" placeholder="Tanggal Transaksi" autocomplete="off" disabled>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="txtCashier" class="form-label">Kasir</label>
                                <input type="text" class="form-control" id="txtCashier" placeholder="Kasir" autocomplete="off" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="txtTotalPurchase" class="form-label">Total Pembelian</label>
                                <div class="input-group">
                                    <div class="input-group-text">Rp</div>
                                    <input type="text" class="form-control" id="txtTotalPurchase" placeholder="Total Pembelian" autocomplete="off" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-xl-12">
                            <button type="button" class="btn btn-danger waves-effect waves-light float-end" id="btnCancel"><i class="fe-x me-1"></i> Batalkan</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <p class="text-danger float-end" style="font-size: 16px;">Catatan : barang diskon tidak dapat di retur.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="mb-3">
                                <table id="tblItemList" class="table table-hover nowrap" style="width: 100%;">
                                    <thead class="table-header-dark">
                                        <tr>
                                            <th style="width: 10px;">
                                                <div class="form-check form-check-blue">
                                                    <input class="form-check-input" type="checkbox" id="cbAllForm" style="font-size: 20px;">
                                                </div>
                                            </th>
                                            <th>No.</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Satuan</th>
                                            <th>Jenis Barang</th>
                                            <th>Harga Beli</th>
                                            <th>Jumlah Beli</th>
                                            <th>Diskon</th>
                                            <th>Sub Total</th>
                                            <th style="min-width: 100px;">Jumlah Retur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="txtTotalChange" class="form-label">Total Uang Kembali</label>
                                <div class="input-group">
                                    <div class="input-group-text">Rp</div>
                                    <input type="text" class="form-control maintenance-form autochange" id="txtTotalChange" placeholder="0" autocomplete="off" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="txtRemark" class="form-label">Catatan <span class="text-danger">*</span></label>
                                <textarea class="form-control maintenance-form" id="txtRemark" rows="5" placeholder="Masukkan Catatan" autocomplete="off" maxlength="2000"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <button type="button" class="btn btn-success waves-effect waves-light" id="btnSave"><i class="fe-check-circle me-1"></i> Simpan</button>
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
<script text="text/javascript">
    const kasir = "<?php echo $_SESSION['fullname']; ?>";
</script>
<script src="../assets/script/retur.js"></script>