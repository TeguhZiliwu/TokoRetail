let edit = false;
const currentFormID = "gainloss";

$(document).ready(async function () {
  if (await checkAuthorize(currentFormID)) {
    mainTable.buttons().container().appendTo("#tblMainData_wrapper .col-md-6:eq(0)");
    $(".dt-buttons").css("display", "none");
    await loadData();
    await getFormName(currentFormID);

    const DateNow = new Date();
    const todayDate = DateNow.getFullYear() + "-" + ("0" + (DateNow.getMonth() + 1)).slice(-2) + "-" + ("0" + DateNow.getDate()).slice(-2);
    setDatetimepicker($("#txtFromDate"), "DAILY", todayDate);
    setDatetimepicker($("#txtToDate"), "DAILY", todayDate, todayDate);
    $("#txtFromDate").on("dp.change", function () {
      const fromDate = $(this).val();
      const toDate = $("#txtToDate").val();
      const period = $("#ddPeriod").val();

      if (fromDate != "" && toDate != "") {
        let fixToDate = toDate.split("-");
        let fixFromDate = fromDate.split("-");

        if (period === "Daily") {
          fixFromDate = fixFromDate[2] + "-" + fixFromDate[1] + "-" + fixFromDate[0] + " 24:00:00";
          fixToDate = fixToDate[2] + "-" + fixToDate[1] + "-" + fixToDate[0];
          const minDate = new Date(fixFromDate);
          const defToDate = new Date(fixToDate);
          $("#txtToDate").datetimepicker("destroy").val("");
          if (stringToDate(fromDate, "dd-mm-yyyy", "-") > stringToDate(toDate, "dd-mm-yyyy", "-")) {
            setDatetimepicker($("#txtToDate"), "Daily", minDate, minDate);
          } else {
            setDatetimepicker($("#txtToDate"), "Daily", defToDate, minDate);
          }
        } else if (period === "Monthly") {
          fixFromDate = fixFromDate[1] + "-" + fixFromDate[0] + "-01 24:00:00";
          fixToDate = fixToDate[1] + "-" + fixToDate[0] + "-01 00:00:00";
          const minDate = new Date(fixFromDate);
          const defToDate = new Date(fixToDate);
          $("#txtToDate").datetimepicker("destroy").val("");
          if (stringToDate(fromDate, "mm-yyyy", "-", "Monthly") > stringToDate(toDate, "mm-yyyy", "-", "Monthly")) {
            setDatetimepicker($("#txtToDate"), "Monthly", minDate, minDate);
          } else {
            setDatetimepicker($("#txtToDate"), "Monthly", defToDate, minDate);
          }
        } else if (period === "Yearly") {
          const minDate = new Date(fixFromDate);
          const defToDate = new Date(fixToDate);
          $("#txtToDate").datetimepicker("destroy").val("");
          if (stringToDate(fromDate, "yyyy", "-", "Yearly") > stringToDate(toDate, "yyyy", "-", "Yearly")) {
            setDatetimepicker($("#txtToDate"), "Yearly", minDate, minDate);
          } else {
            setDatetimepicker($("#txtToDate"), "Yearly", defToDate, minDate);
          }
        }
      }
    });
  } else {
    window.location.replace("../errorpage/403");
  }
});

// Start click function

$("#btnSearch").click(async function (e) {
  await loadData();
});

$("#btnShowProfitAndLoss").click(async function (e) {
  await loadDataProfitAndLoss();
});

$("li.nav-item").click(function () {
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

$("#btnExportProfitLoss").click(function (e) {
  exportProfitLoss();
});

// End click function

// Start keypress function
$("#txtSearch").keypress(function (e) {
  const key = e.which;
  if (key == 13) {
    // the enter key code
    $("#btnSearch").click();
  }
});
// End keypress function

// Start change function

$("#ddPeriod").change(function () {
  const period = $(this).val();
  const DateNow = new Date();
  let todayDate = DateNow.getFullYear() + "-" + ("0" + (DateNow.getMonth() + 1)).slice(-2) + "-" + ("0" + DateNow.getDate()).slice(-2);

  $("#txtFromDate, #txtToDate").datetimepicker("destroy").val("");
  if (period === "Daily") {
    setDatetimepicker($("#txtFromDate"), "Daily", todayDate);
    setDatetimepicker($("#txtToDate"), "Daily", todayDate, todayDate);
  } else if (period === "Monthly") {
    todayDate = todayDate.split("-");
    const minDate = todayDate[0] + "-" + todayDate[1] + "-01 24:00:00";
    const defDate = todayDate[0] + "-" + todayDate[1] + "-01 00:00:00";

    setDatetimepicker($("#txtFromDate"), "Monthly", defDate);
    setDatetimepicker($("#txtToDate"), "Monthly", defDate, minDate);
  } else if (period === "Yearly") {
    todayDate = todayDate.split("-");
    const minDate = todayDate[0] + "-" + "01" + "-01 24:00:00";
    const defDate = todayDate[0] + "-" + "01" + "-01 00:00:00";

    setDatetimepicker($("#txtFromDate"), "Yearly", defDate);
    setDatetimepicker($("#txtToDate"), "Yearly", defDate, minDate);
  }
});

// End change function

// Start initialize element

$("#ddPeriod").select2({
  placeholder: "Pilih Periode",
  //   allowClear: true
});

$("#txtFromDate, #txtToDate").on("keydown", function (e) {
  preventInput(e);
});

// End initialize element

const mainTable = $("#tblMainData").DataTable({
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
      data: "SellingPrice",
      render: function (data, type, row, meta) {
        const format = data.toString().split("").reverse().join("");
        const convert = format.match(/\d{1,3}/g);
        const fixSubTotal = convert.join(".").split("").reverse().join("");

        if (type === "export") {
          return data;
        } else {
          return "Rp " + fixSubTotal;
        }
      },
      className: "align-middle",
    },
    {
      data: "BuyingPrice",
      render: function (data, type, row, meta) {
        const format = data.toString().split("").reverse().join("");
        const convert = format.match(/\d{1,3}/g);
        const fixSubTotal = convert.join(".").split("").reverse().join("");

        if (type === "export") {
          return data;
        } else {
          return "Rp " + fixSubTotal;
        }
      },
      className: "align-middle",
    },
    {
      data: "GainLoss",
      render: function (data, type, row, meta) {
        let profit = "text-success";
        let minus = "";
        const format = data.toString().split("").reverse().join("");
        const convert = format.match(/\d{1,3}/g);
        const fixSubTotal = convert.join(".").split("").reverse().join("");
        if (parseInt(data) <= 0) {
          profit = "text-danger";
          minus = "- ";
        }
        if (type === "export") {
          return minus + "Rp " + fixSubTotal;
        } else {
          return '<p class="' + profit + ' m-0">' + minus + "Rp " + fixSubTotal + "</p>";
        }
      },
      className: "align-middle",
    },
    {
      data: "GainLoss",
      render: function (data, type, row, meta) {
        let gainorloss = "Untung";
        let classprofit = "bg-soft-success text-success";
        if (parseInt(data) <= 0) {
          gainorloss = "Rugi";
          classprofit = "bg-soft-danger text-danger";
        }
        if (type === "export") {
          return gainorloss;
        } else {
          return '<span class="badge ' + classprofit + '" style="font-size: 13px;">' + gainorloss + "</span>";
        }
      },
      className: "align-middle",
    },
  ],
  scrollX: !0,
  scrollY: "350px",
  scrollCollapse: !0,
  searching: !1,
  language: {
    paginate: {
      previous: "<i class='mdi mdi-chevron-left'>",
      next: "<i class='mdi mdi-chevron-right'>",
    },
    emptyTable: "Tidak ada data yang tersedia",
  },
  drawCallback: function () {
    const isEdit = $("#btnAdd").prop("disabled");
    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");

    if (isEdit) {
      $("button[name='editaction'], button[name='deleteaction']").prop("disabled", isEdit);
    }
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  },
  buttons: [
    {
      extend: "excelHtml5",
      title: "",
      filename: "Data Laba & Rugi",
      attr: {
        id: "mainTableExportExcel",
      },
      exportOptions: {
        orthogonal: "export",
      },
    },
  ],
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

// Load data
const loadData = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/gainloss/loaddata.php";
    const param = {
      search: Search,
    };

    showLoading();
    mainTable.clear().draw();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      mainTable.rows.add(response.data).draw();

      let currentSubTotal = 0;
      mainTable
        .column(5)
        .nodes()
        .to$()
        .each(function (index) {
          const thisSubTotal = $(this).closest("td").text();
          const hasNegative = thisSubTotal.charAt(0);
          let fixThisSubTotal = 0;
          fixThisSubTotal = parseInt(thisSubTotal.replace(/\D/g, ""));
          if (hasNegative == "-") {
            fixThisSubTotal = -fixThisSubTotal;
          }
          currentSubTotal = currentSubTotal + fixThisSubTotal;
        });

      let minus = "";
      let classGain = "text-success";
      if (currentSubTotal < 0) {
        minus = "- ";
        classGain = "text-danger";
      }
      const format = currentSubTotal.toString().split("").reverse().join("");
      const convert = format.match(/\d{1,3}/g);
      const fixSubTotal = convert.join(".").split("").reverse().join("");

      $("#lblTotal").html(`Total : <span class="${classGain}">${minus} Rp <strong>${fixSubTotal}</strong></span>`);

      hideLoading();
    } else {
      if (response.msg.includes("[ERROR]")) {
        response.msg = response.msg.replace("[ERROR] ", "");
        showNotif(response.msg, 15000, "error", "top-end");
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");
      }
      hideLoading();
    }
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const loadDataProfitAndLoss = async () => {
  try {
    let FromDate = $("#txtFromDate").val();
    let ToDate = $("#txtToDate").val();
    const Period = $("#ddPeriod").val();

    if (Period === "Daily") {
      FromDate = FromDate.split("-");
      FromDate = FromDate[2] + "-" + FromDate[1] + "-" + FromDate[0] + " 00:00:00";

      ToDate = ToDate.split("-");
      ToDate = ToDate[2] + "-" + ToDate[1] + "-" + ToDate[0] + " 23:59:59";
    } else if (Period === "Monthly") {
      FromDate = FromDate.split("-");
      FromDate = FromDate[1] + "-" + FromDate[0] + "-01" + " 00:00:00";

      ToDate = ToDate.split("-");
      ToDate = ToDate[1] + "-" + ToDate[0] + "-31" + " 23:59:59";
    } else if (Period === "Yearly") {
      FromDate = FromDate + "-01" + "-01" + " 00:00:00";
      ToDate = ToDate + "-12" + "-31" + " 23:59:59";
    }

    const url = "../controller/gainloss/fetch_profit_loss.php";
    const param = {
      FromDate: FromDate,
      ToDate: ToDate,
    };

    showLoading();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      const data = await response.data;
      $("#lblPeriode").text(`Periode : ${$("#txtFromDate").val()} s/d ${$("#txtToDate").val()}`);
      $("#tblProfitAndLoss tbody").empty();
      if (data.length > 0) {
        let rowData = "";
        let totalGain = 0;
        for (let i = 0; i < data.length; i++) {
          rowData += "<tr>" + "<td>" + data[i].ItemCode + "</td>" + "<td>" + data[i].ItemName + "</td>" + "<td>" + data[i].Qty + "</td>" + "<td>" + convertIntToRp(data[i].TotalPurchase) + "</td>" + "<td>" + convertIntToRp(data[i].TotalInitialCapital) + "</td>" + "<td>" + convertIntToRp(data[i].TotalGain) + "</td>" + "</tr>";
          totalGain += parseInt(data[i].TotalGain);
        }
        rowData += '<tr class="table-active">' +
        '<td data-fill-color="ECECEC" colspan="5">Laba Bersih Usaha</td>' +
        '<td class="table-profit" data-fill-color="ECECEC">'+convertIntToRp(totalGain)+'</td>' +
        "</tr>";
        $("#tblProfitAndLoss tbody").append(rowData);
      }
      // $("#lblTotalIn").text(convertIntToRp(data[0].TotalIn));
      // $("#lblTotalOut").text(convertIntToRp(data[0].TotalOut));
      // $("#lblTotalProfit").text(data[0].TotalProfit < 0 ? "-" + convertIntToRp(data[0].TotalProfit) : convertIntToRp(data[0].TotalProfit));
      hideLoading();
    } else {
      if (response.msg.includes("[ERROR]")) {
        response.msg = response.msg.replace("[ERROR] ", "");
        showNotif(response.msg, 15000, "error", "top-end");
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");
      }
      hideLoading();
    }
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const preventInput = (evnt) => {
  if (evnt.which != 9) evnt.preventDefault();
};

const setDatetimepicker = (el, period, defaultdate, mindate = false) => {
  const defDate = new Date(defaultdate);
  let format = "DD-MM-YYYY";
  let viewMode = "days";

  if (period == "Monthly") {
    format = "MM-YYYY";
    viewMode = "months";
  } else if (period == "Yearly") {
    format = "YYYY";
    viewMode = "years";
  }

  if (mindate != false) {
    mindate = new Date(mindate);

    if (period == "Daily") {
      mindate.setDate(mindate.getDate() - 1);
    } else if (period == "Monthly") {
      mindate.setDate(mindate.getDate() - 1);
    } else if (period == "Yearly") {
      mindate.setDate(mindate.getDate() - 1);
    }
  }

  $(el).datetimepicker({
    format: format,
    viewMode: viewMode,
    showTodayButton: true,
    defaultDate: defDate,
    minDate: mindate,
    widgetPositioning: {
      horizontal: "left",
      vertical: "bottom",
    },
    icons: {
      time: "fas fa-clock-o",
      date: "fas fa-calendar",
      up: "fas fa-arrow-up",
      down: "fas fa-arrow-down",
      today: "fas fa-map-marker",
      previous: "fas fa-angle-left",
      next: "fas fa-angle-right",
    },
  });
};

const stringToDate = (_date, _format, _delimiter, _period = "Daily") => {
  const formatLowerCase = _format.toLowerCase();
  const formatItems = formatLowerCase.split(_delimiter);
  const dateItems = _date.split(_delimiter);
  const monthIndex = formatItems.indexOf("mm");
  const dayIndex = formatItems.indexOf("dd");
  const yearIndex = formatItems.indexOf("yyyy");
  let month = parseInt(dateItems[monthIndex]);
  month -= 1;

  if (_period === "Monthly") {
    const formatedDate = new Date(dateItems[yearIndex], month);
    return formatedDate;
  } else if (_period === "Yearly") {
    const formatedDate = new Date(dateItems[yearIndex]);
    return formatedDate;
  } else {
    const formatedDate = new Date(dateItems[yearIndex], month, dateItems[dayIndex]);
    return formatedDate;
  }
};

const convertIntToRp = (data) => {
  const format = data.toString().split("").reverse().join("");
  const convert = format.match(/\d{1,3}/g);
  const fixAmount = convert.join(".").split("").reverse().join("");

  return "Rp " + fixAmount;
};

const exportProfitLoss = () => {
  TableToExcel.convert(document.getElementById("tblProfitAndLoss"), {
    name: `ProfitLoss.xlsx`,
    sheet: {
      name: "Sheet 1",
    },
  });
};
