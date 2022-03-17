let edit = false;
const currentFormID = "retur";
let contact = "";

$(document).ready(async function () {
  if (await checkAuthorize(currentFormID)) {
    $(".dt-buttons").css("display", "none");

    formProp(true);
    $("#divFixedDiscount, #divPercentageDiscount").css("display", "none");
    contact = await getGlobalSetting("ContactAdmin");
  } else {
    window.location.replace("../errorpage/403");
  }
});

const formProp = (isDisabled) => {
  $("#btnSave, #btnCancel, #btnRetur, #cbAllForm, #txtRemark").prop("disabled", isDisabled);
  $("#txtRemark").val("");
};

const disabledForm = async () => {
  formProp(true);
};

const enabledForm = async () => {
  formProp(false);
};

const clearForm = () => {
  $(".form-control").val("").trigger("change");
  itemList.clear().draw();
};

const clearItemField = () => {
  $(".form-control:not(.autochange):not(#txtRemark)").val("").trigger("change");
};

const validateSubmit = () => {
  let valid = true;
  const validateTitle = "Data tidak valid!";
  let validateDesc = "Silahkan lengkapi data dengan benar!";
  const remark = $("#txtRemark");

  if (itemList.rows().count() > 0) {
    let invalidReturQty = 0;
    let hasSelectedData = 0;
    itemList
      .column(11)
      .nodes()
      .to$()
      .each(function (index) {
        const returQty = $(this).closest("td").find("input[name='returqty']").val();
        const maxQty = $(this).closest("tr").find("td:eq(8)").text();
        const returChecked = $(this).closest("tr").find("td:eq(0)").find("input[type='checkbox'][name='itemlist']").prop("checked");

        if (returChecked) {
          hasSelectedData++;
          if (returQty == "") {
            invalidReturQty++;
            $(this).closest("td").find("input[name='returqty']").addClass("is-invalid");
          } else {
            if (returQty > maxQty) {
              invalidReturQty++;
              $(this).closest("td").find("input[name='returqty']").addClass("is-invalid");
            }
          }
        }
      });

    if (hasSelectedData == 0) {
      valid = false;
      validateDesc = "Tidak ada barang yang akan di retur!";
    } else {
      if (invalidReturQty > 0) {
        valid = false;
        validateDesc = "Jumlah retur tidak bisa kosong atau melebihi dari jumlah beli barang!";
      }
    }
  }
  
  if (remark.val().replace(/\s/g, "") == "" || remark.val().length > 2000) {
    valid = false;
    remark.addClass("is-invalid");
  }

  if (!valid) {
    showNotif(validateDesc, 15000, "warning", "top-end");
  }

  return valid;
};

// Start initialize element

// End initialize element

// Start click function

$("#btnAdd").click(async function () {
  $('a[href="#tabs-detail"]').tab("show");
  formProp(false);
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

$("#btnCancel").click(async function () {
  $('a[href="#tabs-data"]').tab("show");
  formProp(true);
  clearForm();
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

$("#btnSave").click(async function () {
  if (validateSubmit()) {
    await saveData();
  }
});

$("#btnExport").click(function () {
  $("#mainTableExportExcel").click();
});

$("#btnTemplate").click(function () {
  const url = "../controller/downloadfile/downloadfile.php?filename=Template Penjualan.xlsx";
  window.location = url;
});

$('a[href="#tabs-data"], a[href="#tabs-detail"]').click(function () {
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

$("#tblItemList").on("click", 'button[name="deleteitemtaction"]', function () {
  itemList.rows($(this).parents("tr")).remove().draw();

  if (itemList.rows().count() == 0) {
    $("#lblTotal").html(`Total : Rp <strong>0</strong>`);
    $("#btnSave, #btnCancel, #txtTotalPayment").prop("disabled", true);
    $("#txtTotalChange").val("");
    totalPaymentAutoNum.set("");
  } else {
    let currentSubTotal = 0;
    itemList
      .column(9)
      .nodes()
      .to$()
      .each(function (index) {
        const thisSubTotal = $(this).closest("td").text();
        currentSubTotal = currentSubTotal + parseInt(thisSubTotal.replace(/\D/g, ""));
      });

    const format = currentSubTotal.toString().split("").reverse().join("");
    const convert = format.match(/\d{1,3}/g);
    const fixSubTotal = convert.join(".").split("").reverse().join("");

    $("#lblTotal").html(`Total : Rp <strong>${fixSubTotal}</strong>`);
    calculateTotalChanges();
  }
});

$("#btnSearch").click(async function () {
  await getTransactionDet();
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

$("#tblItemList").on("keypress", "input[name='returqty']", function (e) {
  if ((event.which != 46 || (event.which == 46 && $(this).val() == "")) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
  if (event.which == 46) {
    e.preventDefault();
  }
});

$("#tblItemList").on("keypress", "input[name='returqty']", function (e) {
  var value = $(this).val();
  var numbers = value.replace(/[^0-9]/g, "");
  $(this).val(numbers);
});
// End keypress function

// Start change function

$("#cbAllForm").on("change", function (e) {
  const allItem = $(this).prop("checked");
  if (allItem) {
    itemList.column(0).nodes().to$().find("input[type='checkbox'][name='itemlist']:not(:disabled)").prop("checked", true).trigger("change");
  } else {
    itemList.column(0).nodes().to$().find("input[type='checkbox'][name='itemlist']:not(:disabled)").prop("checked", false).trigger("change");
  }
});

$("#tblItemList").on("change", "input[type='checkbox'][name='itemlist']", function () {
  const thick = $(this).prop("checked");
  const row = $(this).parents("tr");
  const rowData = itemList.row(row).data();
  const totalList = $("input[type='checkbox'][name='itemlist']:not(:disabled)").length;
  const totalChecked = $("input[type='checkbox'][name='itemlist']:not(:disabled):checked").length;

  if (thick) {
    const qty = rowData.Qty;
    itemList.cell(row, 11).nodes().to$().find("input[name='returqty']").prop("disabled", false).val("").attr("placeholder", qty).trigger("change");
  } else {
    itemList.cell(row, 11).nodes().to$().find("input[name='returqty']").prop("disabled", true).val("").attr("placeholder", "").trigger("change");
  }

  if (totalList == totalChecked) {
    $("#cbAllForm").prop("checked", true);
  } else {
    $("#cbAllForm").prop("checked", false);
  }
});

$("#tblItemList").on("change", "input[name='returqty']", function () {
  if ($(this).val().replace(/\s/g, "") != "") {
    $(this).removeClass("is-invalid");
  }
  let total = 0;
  const row = $(this).parents("tr");
  const selectedItem = itemList.cell(row, 0).nodes().to$().find("input[type='checkbox'][name='itemlist']").prop("checked");

  if (!selectedItem) {
    $(this).removeClass("is-invalid");
  }

  $("input[name='returqty']").each(function () {
    const val = $(this).val();
    const row = $(this).parents("tr");
    const qtyRetur = parseInt(val == "" ? 0 : val);
    const price = itemList.cell(row, 7).nodes().to$().text().replace(/\D/g, "");
    const subTotal = qtyRetur * parseInt(price);
    total += subTotal;
  });

  const format = total.toString().split("").reverse().join("");
  const convert = format.match(/\d{1,3}/g);
  const fixTotal = convert.join(".").split("").reverse().join("");
  $("#txtTotalChange").val(fixTotal);
});

$("#txtRemark").change(function() {
  if (
    $(this).val().replace(/\s/g, "") != "" &&
    $(this).val().length != 0 &&
    $(this).val().length <= 2000
  ) {
    $(this).removeClass("is-invalid");
  }
});

// End change function

const itemList = $("#tblItemList").DataTable({
  columns: [
    {
      data: "",
      render: function (data, type, row, meta) {
        const itemCode = row["ItemCode"];
        const discount = row["Discount"];
        let rowcheckbox = "";
        let disabled = "";

        if (discount != "0") {
          disabled = "disabled";
        }

        rowcheckbox += '<div class="form-check form-check-blue">';
        rowcheckbox += '<input class="form-check-input" type="checkbox" name="itemlist" itemcode="' + itemCode + '" id="cb' + itemCode + '" ' + disabled + ">";
        rowcheckbox += "</div>";
        return rowcheckbox;
      },
      className: "align-middle",
    },
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
      data: "Category",
      className: "align-middle",
    },
    {
      data: "UOM",
      className: "align-middle",
    },
    {
      data: "ItemType",
      className: "align-middle",
    },
    {
      data: "PurchasePrice",
      render: function (value) {
        const format = value.toString().split("").reverse().join("");
        const convert = format.match(/\d{1,3}/g);
        const fixSubTotal = convert.join(".").split("").reverse().join("");

        return "Rp " + fixSubTotal;
      },
      className: "align-middle",
    },
    {
      data: "Qty",
      className: "align-middle",
    },
    {
      data: "Discount",
      render: function (value) {
        const format = value.toString().split("").reverse().join("");
        const convert = format.match(/\d{1,3}/g);
        const fixSubTotal = convert.join(".").split("").reverse().join("");

        return "Rp " + fixSubTotal;
      },
      className: "align-middle",
    },
    {
      data: "SubTotal",
      render: function (value) {
        const format = value.toString().split("").reverse().join("");
        const convert = format.match(/\d{1,3}/g);
        const fixSubTotal = convert.join(".").split("").reverse().join("");

        return "Rp " + fixSubTotal;
      },
      className: "align-middle",
    },
    {
      data: "",
      render: function (data, type, row, meta) {
        return '<input class="form-control" name="returqty" type="text" disabled>';
      },
      orderable: false,
      className: "text-center",
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
    emptyTable: "Tidak ada data barang",
  },
  drawCallback: function () {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  },
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

const saveData = async () => {
  try {
    const url = "../controller/retur/retur.php";
    const transactionID = $("#txtTransactionID").val();
    const remark = $("#txtRemark").val();
    const arrItemList = [];

    itemList
      .column(2)
      .nodes()
      .to$()
      .each(function (index) {
        const isSelectedItem = $(this).closest("tr").find("td:eq(0)").find("input[type='checkbox'][name='itemlist']").prop("checked");
        const itemCode = $(this).closest("td").text();
        const itemName = $(this).closest("tr").find("td:eq(2)").text();
        const purchasePrice = $(this).closest("tr").find("td:eq(7)").text();
        const returQty = $(this).closest("tr").find("td:eq(11)").find("input[name='returqty']").val();
        const discount = "0";
        if (isSelectedItem) {
          const obj = {
            ItemCode: itemCode,
            ItemName: itemName,
            Qty: returQty.replace(/\D/g, ""),
            PurchasePrice: purchasePrice.replace(/\D/g, ""),
            Discount: discount.replace(/\D/g, ""),
          };
          arrItemList.push(obj);
        }
      });

    const param = {
      TransactionID: transactionID,
      Remark: remark,
      ItemList: arrItemList,
    };

    showLoading();
    const response = await callAPI(url, "POST", param);

    if (response.success) {
      showNotif(response.msg, 15000, "success", "top-end");
      disabledForm();
      clearForm();
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
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

// Validate has file
const fileSelected = (thisfile) => {
  var file = $(thisfile)[0];
  if (file.files.length > 0) {
    return true;
  } else {
    return false;
  }
};

// Validate file extension
const fileIsExcel = (thisfile) => {
  let file = $(thisfile);
  let ext = file.val().substring(file.val().lastIndexOf(".") + 1);

  if (ext.toUpperCase() == "XLS" || ext.toUpperCase() == "XLSX") {
    return true;
  } else {
    return false;
  }
};

const getPercentageDiscount = async () => {
  try {
    const data = await getGlobalSetting("DiscountPercentage");

    if (data != "") {
      const globalValue = data.split(",");
      let option = "";

      for (let i = 0; i < globalValue.length; i++) {
        option += '<option value="' + globalValue[i].replace(/\s+/g, " ").trim() + '">' + globalValue[i].replace(/\s+/g, " ").trim() + "</option>";
      }
      $("#ddPercentageDiscount").html(option).val("").trigger("change");
    }
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const calculateTotalChanges = () => {
  let total = 0;
  const totalPayment = parseInt($("#txtTotalPayment").val().replace(/\D/g, ""));

  if (itemList.rows().count() > 0) {
    let currentSubTotal = 0;
    itemList
      .column(9)
      .nodes()
      .to$()
      .each(function (index) {
        const thisSubTotal = $(this).closest("td").text();
        currentSubTotal = currentSubTotal + parseInt(thisSubTotal.replace(/\D/g, ""));
      });

    total = currentSubTotal;

    // $("#lblTotal").html(`Total : Rp <strong>${fixSubTotal}</strong>`);
    const totalChanges = totalPayment - total;
    const format = totalChanges.toString().split("").reverse().join("");
    const convert = format.match(/\d{1,3}/g);
    const fixTotalChange = convert.join(".").split("").reverse().join("");
    $("#txtTotalChange").val((totalChanges < 0 ? "- " : "") + fixTotalChange);

    if ($("#txtTotalPayment").val() != "") {
      if (totalChanges < 0) {
        $("#btnSave, #btnCancel").prop("disabled", true);
      } else {
        $("#btnSave, #btnCancel").prop("disabled", false);
      }
    } else {
      $("#btnSave, #btnCancel").prop("disabled", true);
    }
  } else {
    $("#btnSave, #btnCancel").prop("disabled", true);
  }
};

const getTransactionDet = async () => {
  try {
    const TransactionID = $("#txtSearch").val();
    const url = "../controller/retur/fetch_data.php";
    const FetchData = "getTransactionDet";
    const param = {
      TransactionID: TransactionID,
      FetchData: FetchData,
    };

    showLoading();
    itemList.clear().draw();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      formProp(false);
      itemList.rows.add(response.data).draw();
      if (response.data.length > 0) {
        const data = response.data;
        let totalPurchase = 0;
        for (let i = 0; i < data.length; i++) {
          const subTotal = parseInt(data[0].SubTotal);
          totalPurchase += subTotal;
        }

        const format = totalPurchase.toString().split("").reverse().join("");
        const convert = format.match(/\d{1,3}/g);
        const fixTotalPurchase = convert.join(".").split("").reverse().join("");
        const transactionDate = moment(response.data[0].TransactionDate, "YYYY-MM-DD HH:mm:ss").format(
          "DD-MM-YYYY HH:mm:ss");

        $("#txtTransactionID").val(response.data[0].TransactionID);
        $("#txtTransactionDate").val(transactionDate);
        $("#txtCashier").val(response.data[0].Cashier);
        $("#txtTotalPurchase").val(fixTotalPurchase);
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");
        formProp(true);
        $("#txtTransactionID, #txtTransactionDate, #txtCashier, #txtTotalPurchase").val("");
      }
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
    $("#txtSearch").val("");
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};
