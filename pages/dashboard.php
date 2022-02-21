<?php
include "../components/topapp.php";
?>

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row" id="rowOwner">
        <div class="col-md-4 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <i class="fe-dollar-sign text-muted float-end"></i>
                    <h4 class="mt-0 font-16">Pendapatan Perbulan</h4>
                    <h3 class="text-success my-3 text-center">Rp <span id="lblIncome">0</span></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <i class="fe-arrow-up text-muted float-end"></i>
                    <h4 class="mt-0 font-16">Transaksi Penjualan Perbulan</h4>
                    <h3 class="text-success my-3 text-center" id="lblTotalSale"><span>0</span></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <i class="fe-arrow-down text-muted float-end"></i>
                    <h4 class="mt-0 font-16">Transaksi Stok Masuk Perbulan</h4>
                    <h3 class="text-success my-3 text-center"><span id="lblTotalStockIn">0</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript:void(0);" id="btnDownload" class="me-2" title="Download Data" tabindex="0"><i class="mdi mdi-download"></i></a>
                        <a href="javascript:void(0);" id="btnRefreshlistofOutItem" class="me-2" title="Refresh" tabindex="0"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#listofOutItem" role="button" aria-expanded="false" id="btnCollapseRunningStock" title="Tutup" tabindex="0"><i class="mdi mdi-minus"></i></a>
                    </div>
                    <h4 class="header-title mb-0">Stok Yang Hampir Habis</h4>
                    <div id="listofOutItem" class="collapse pt-2 show">
                        <div class="table-responsive">
                            <table class="table nowrap" id="tbltopRunningOutItem" style="width: 100%;">
                                <thead class="table-header-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                            </table>
                        </div> <!-- end table responsive-->
                    </div> <!-- collapsed end -->
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div>
    </div>
    <!-- end row -->

</div> <!-- container -->

<?php
include "../components/bottomapp.php";
?>
<script>    
  const GroupID = "<?php echo $_SESSION['groupid']; ?>";
</script>
<script src="../assets/script/dashboard.js"></script>