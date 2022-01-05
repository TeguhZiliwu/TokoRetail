$(document).ready(async function () {
  runningOutTable.buttons().container().appendTo("#tbltopRunningOutItem_wrapper .col-md-6:eq(0)");
  $(".dt-buttons").css("display", "none");
  await getStockRunningOut();

  if (GroupID != "Pemilik Usaha") {
    $("#rowOwner").remove();
  } else {
    await getIncomeInfo();
    await getTotalTransaction();
    await getTotalStockIn();
  }

  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

const runningOutTable = $("#tbltopRunningOutItem").DataTable({
  columns: [
    {
      data: "",
      render: function (data, type, row, meta) {
        return meta.row + 1 + ".";
      },
      className: "align-middle",
    },
    {
      data: "ItemCode",
      className: "align-middle",
    },
    {
      data: "ItemName",
      className: "align-middle",
    },
    {
      data: "Qty",
      className: "align-middle",
    },
  ],
  scrollX: !0,
  scrollY: "350px",
  scrollCollapse: !0,
  searching: !1,
  sort: !1,
  paging: !1,
  info: !1,
  language: {
    paginate: {
      previous: "<i class='mdi mdi-chevron-left'>",
      next: "<i class='mdi mdi-chevron-right'>",
    },
    emptyTable: "Tidak ada data yang tersedia",
  },
  buttons: [
      {
          extend: "excelHtml5",
          title: "",
          filename: "Data Stok",
          attr: {
              id: "tableRunningStock",
          },
      },
  ],
  drawCallback: function () {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  },
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
  rowCallback: function (row, data, index) {
    if (data.Qty <= 5) {
      $(row).addClass("bg-danger text-white");
    }
  },
});

const getStockRunningOut = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/dashboard/fetch_data.php";
    const param = {
      search: Search,
      FetchData: "getStockRunningOut",
    };

    showLoading();
    runningOutTable.clear().draw();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      const data = response.data;
      runningOutTable.rows.add(data).draw();
      hideLoading();
    } else {
      if (response.msg.includes("[ERROR]")) {
        response.msg = response.msg.replace("[ERROR] ", "");
        showNotif(response.msg, 15000, "error", "top-end");
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");
      }
    }

    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const getIncomeInfo = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/dashboard/fetch_data.php";
    const param = {
      search: Search,
      FetchData: "getIncomeDashboard",
    };
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      const data = response.data;
      let Total = data[0].Total;

      if (Total != null) {
        const format = Total.toString().split("").reverse().join("");
        const convert = format.match(/\d{1,3}/g);
        const fixTotal = convert.join(",").split("").reverse().join("");
        $("#lblIncome").html(fixTotal);
        var delay = $(this).attr("data-delay") ? $(this).attr("data-delay") : 100; //default is 100
        var time = $(this).attr("data-time") ? $(this).attr("data-time") : 1200; //default is 1200
        $("#lblIncome").counterUpCustom({
          delay: delay,
          time: time,
        });
      }
    } else {
      if (response.msg.includes("[ERROR]")) {
        response.msg = response.msg.replace("[ERROR] ", "");
        showNotif(response.msg, 15000, "error", "top-end");
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");
      }
    }
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const getTotalTransaction = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/dashboard/fetch_data.php";
    const param = {
      search: Search,
      FetchData: "getTotalSale",
    };
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      const data = response.data;
      let Total = data[0].TotalTransaction;
      $("#lblTotalSale").html(Total);
      var delay = $(this).attr("data-delay") ? $(this).attr("data-delay") : 100; //default is 100
      var time = $(this).attr("data-time") ? $(this).attr("data-time") : 1200; //default is 1200
      $("#lblTotalSale").counterUpCustom({
        delay: delay,
        time: time,
      });
    } else {
      if (response.msg.includes("[ERROR]")) {
        response.msg = response.msg.replace("[ERROR] ", "");
        showNotif(response.msg, 15000, "error", "top-end");
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");
      }
    }
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const getTotalStockIn = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/dashboard/fetch_data.php";
    const param = {
      search: Search,
      FetchData: "getTotalStockIn",
    };
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      const data = response.data;
      let Total = data[0].TotalTransaction;
      $("#lblTotalStockIn").html(Total);
      var delay = $(this).attr("data-delay") ? $(this).attr("data-delay") : 100; //default is 100
      var time = $(this).attr("data-time") ? $(this).attr("data-time") : 1200; //default is 1200
      $("#lblTotalStockIn").counterUpCustom({
        delay: delay,
        time: time,
      });
    } else {
      if (response.msg.includes("[ERROR]")) {
        response.msg = response.msg.replace("[ERROR] ", "");
        showNotif(response.msg, 15000, "error", "top-end");
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");
      }
    }
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const tooltip = tippy("#btnDownload, #btnRefreshlistofOutItem, #btnCollapseRunningStock", {
  arrow: true,
  hideOnClick: false,
  dynamicTitle: true
});

$("#btnRefreshlistofOutItem").click(async function (ev) {
  await getStockRunningOut();
});

$("#btnDownload").click(function () {
  $("#tableRunningStock").click();
});

$("#btnCollapseRunningStock").click(function () {
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  const collapseMode = $(this).attr('aria-expanded');
  if (collapseMode === "true") {
    $(this).attr('title', 'Tutup');
  } else {
    $(this).attr('title', 'Buka');
  }
  tooltip;
});
