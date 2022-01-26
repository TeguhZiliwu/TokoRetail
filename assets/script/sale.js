let edit = false;
const currentFormID = "sale";
let contact = "";

$(document).ready(async function () {
  if (await checkAuthorize(currentFormID)) {
    itemList.buttons().container().appendTo("#tblItemList_wrapper .col-md-6:eq(0)");
    invalidImport.buttons().container().appendTo("#tblInvalidData_wrapper .col-md-6:eq(0)");
    $(".dt-buttons").css("display", "none");
    await getPercentageDiscount();
    await getFormName(currentFormID);
    formProp(true);
    $("#divFixedDiscount, #divPercentageDiscount").css("display", "none");
    contact = await getGlobalSetting("ContactAdmin");
  } else {
    window.location.replace("../errorpage/403");
  }
});

const formProp = (isDisabled) => {
  $(".maintenance-form, #btnSave, #btnCancel").prop("disabled", isDisabled);
  $("#btnAdd, #btnImport").prop("disabled", !isDisabled);

  $("#txtItemDesc")
    .css("resize", isDisabled ? "none" : "vertical")
    .css("height", "128px");
  $("#txtRemark")
    .css("resize", isDisabled ? "none" : "vertical")
    .css("height", "127px");
};

const disabledForm = async () => {
  formProp(true);
};

const enabledForm = async () => {
  formProp(false);
};

const clearForm = () => {
  $(".form-control").val("").trigger("change");
  buyingPriceAutoNum.set("");
  qtyAutoNum.set("");
  totalPaymentAutoNum.set("");
  $("#txtQty").removeClass("is-invalid");
  $("body").find("[aria-labelledby='select2-ddItemCode-container']").removeClass("select2-invalid");
  itemList.clear().draw();
  $("#lblTotal").html(`Total : Rp <strong>0</strong>`);
  $("#ddItemCode").empty();
  $("input[type='radio'][name='discount'][value='No Discount']").prop("checked");
};

const clearItemField = () => {
  $(".form-control:not(.autochange):not(#txtRemark)").val("").trigger("change");
  buyingPriceAutoNum.set("");
  qtyAutoNum.set("");
  discountAutoNum.set("");
};

const validateSubmit = () => {
  let valid = true;
  const validateTitle = "Data tidak valid!";
  let validateDesc = "Silahkan lengkapi data dengan benar!";

  if (itemList.rows().count() == 0) {
    valid = false;
    validateDesc = "Tidak ada barang didalam list, silahkan masukkan data untuk melakukan stock in.";
  }

  if (!valid) {
    showNotif(validateDesc, 15000, "warning", "top-end");
  }

  return valid;
};

const validateAddToList = () => {
  let valid = true;
  const validateTitle = "Data tidak valid!";
  let validateDesc = "Silahkan lengkapi data dengan benar!";
  const ItemCode = $("#ddItemCode");
  const PurchasPrice = $("#txtPurchasePrice");
  const Qty = $("#txtQty");
  const CurrentStock = $("#txtCurrentStock");
  const discounttype = $("input[type='radio'][name='discount']:checked");
  const discountPercentage = $("#ddPercentageDiscount");
  const discountFixed = $("#txtDiscount");
  const purchasePrice = $("#txtPurchasePrice");

  if (ItemCode.val() == null || ItemCode.val() == "") {
    valid = false;
    $("body").find("[aria-labelledby='select2-ddItemCode-container']").addClass("select2-invalid");
    validateDesc = "Silahkan pilih barang.";
  } else {
    if (PurchasPrice.val().replace(/\s/g, "") == "" || PurchasPrice.val() == 0) {
      valid = false;
      PurchasPrice.addClass("is-invalid");
    }

    if (Qty.val().replace(/\s/g, "") == "" || Qty.val() == 0) {
      valid = false;
      Qty.addClass("is-invalid");
    }

    if (Qty.val() != "" && Qty.val() != 0) {
      if (parseInt(Qty.val()) > parseInt(CurrentStock.val())) {
        valid = false;
        validateDesc = "Jumlah barang melebihi stock yang tersedia!";
      }
    }

    if (discounttype.val() === "Percentage") {
      if (discountPercentage.val() == null || discountPercentage.val() == "") {
        valid = false;
        $("body").find("[aria-labelledby='select2-ddPercentageDiscount-container']").addClass("select2-invalid");
      }
    }

    if (discounttype.val() === "Fixed") {
      if (discountFixed.val().replace(/\s/g, "") == "" || discountFixed.val() == 0) {
        valid = false;
        discountFixed.addClass("is-invalid");
      }

      if (discountFixed.val() != "" && discountFixed.val() != 0) {
        if (parseInt(discountFixed.val()) > parseInt(Qty.val()) * parseInt(purchasePrice.val())) {
          valid = false;
          validateDesc = "Total diskon melebihi total penjualan!";
        }
      }
    }
  }

  if (itemList.rows().count() > 0) {
    let hasSameVal = false;
    itemList
      .column(1)
      .nodes()
      .to$()
      .each(function (index) {
        const thisItemCode = $(this).closest("td").text();
        if (thisItemCode == ItemCode.val()) {
          hasSameVal = true;
        }
      });

    if (hasSameVal) {
      valid = false;
      validateDesc = "Kode barang sudah tersedia didalam list.";
    }
  }

  if (!valid) {
    showNotif(validateDesc, 15000, "warning", "top-end");
  }

  return valid;
};

// Start initialize element

$("#ddItemCode")
  .select2({
    placeholder: "Pilih Barang",
    minimumInputLength: 3,
    ajax: {
      url: "../controller/item/fetch_data.php",
      dataType: "json",
      delay: 800,
      data: function (params) {
        return {
          search: params.term == undefined ? "" : params.term,
          FetchData: "findItemCode",
        };
      },
      processResults: function (data, page) {
        var results = [];
        data = data.data;
        $.each(data, function (index, item) {
          let itemType = "";
          if (item.ItemType != "") {
            itemType = " [" + item.ItemType + "]";
          }
          results.push({
            id: item.ItemCode,
            text: item.ItemCode + " - " + item.ItemName + itemType,
            itemname: item.ItemName,
            category: item.Category,
            uom: item.UOM,
            itemtype: item.ItemType,
            itemdesc: item.ItemDesc,
            sellingprice: item.SellingPrice,
            qty: item.Qty,
          });
        });
        return {
          results: results,
        };
      },
    },
    language: {
      inputTooShort: function (e) {
        return "Silahkan masukkan minimal " + (e.minimum - e.input.length) + " atau lebih karakter";
      },
      searching: function () {
        return "Mencari…";
      },
      noResults: function () {
        return "Tidak ada data yang ditemukan";
      },
    },
    allowClear: true,
  })
  .on("select2:select", async function (evt) {
    const data = $(this).select2("data")[0];
    $("#txtItemName").val(data.itemname);
    $("#txtCategory").val(data.category);
    $("#txtUOM").val(data.uom);
    $("#txtItemType").val(data.itemtype);
    $("#txtItemDesc").val(data.itemdesc);
    $("#txtCurrentStock").val(data.qty);
    buyingPriceAutoNum.set(data.sellingprice);

    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  })
  .on("select2:clear", async function (evt) {
    $("#txtItemName").val("");
    $("#txtCategory").val("");
    $("#txtUOM").val("");
    $("#txtItemType").val("");
    $("#txtItemDesc").val("");
    $("#txtCurrentStock").val("");
    buyingPriceAutoNum.set("");

    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    $("#ddItemCode").empty();
  });

$("#ddPercentageDiscount").select2({
  placeholder: "Pilih Diskon",
  // allowClear: true,
});

const buyingPriceAutoNum = new AutoNumeric("#txtPurchasePrice", {
  decimalPlaces: 0,
  digitGroupSeparator: "",
  modifyValueOnWheel: false,
});

const totalPaymentAutoNum = new AutoNumeric("#txtTotalPayment", {
  decimalPlaces: 0,
  decimalCharacter: ",",
  digitGroupSeparator: ".",
  modifyValueOnWheel: false,
});

const qtyAutoNum = new AutoNumeric("#txtQty", {
  decimalPlaces: 0,
  digitGroupSeparator: "",
  modifyValueOnWheel: false,
});

const discountAutoNum = new AutoNumeric("#txtDiscount", {
  decimalPlaces: 0,
  digitGroupSeparator: "",
  modifyValueOnWheel: false,
});

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

$("#btnImport").click(function () {
  $("#fileUpload").click();
});

$("#btnInvalidExport").click(function () {
  $("#invalidTableExportExcel").click();
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

$("#btnAddToList").click(async function () {
  if (validateAddToList()) {
    const discounttype = $("input[type='radio'][name='discount']:checked");
    let newData = [];
    let Discount = 0;

    switch (discounttype.val()) {
      case "Percentage":
        Discount = $("#ddPercentageDiscount").val();
        break;

      case "Fixed":
        Discount = $("#txtDiscount").val();
        break;

      default:
        Discount = 0;
        break;
    }

    let SubTotal = parseInt($("#txtQty").val()) * parseInt($("#txtPurchasePrice").val());

    if (Discount != 0) {
      if (discounttype.val() == "Percentage") {
        const disc = Discount.replace(/\D/g, "");
        const totaldisc = (parseInt(disc) * parseInt(SubTotal)) / 100;
        Discount = totaldisc;
        SubTotal = parseInt(SubTotal) - Discount;
      } else if (discounttype.val() == "Fixed") {
        SubTotal = parseInt(SubTotal) - parseInt(Discount);
      }
    }

    const obj = {
      ItemCode: $("#ddItemCode").val(),
      ItemName: $("#txtItemName").val(),
      Category: $("#txtCategory").val(),
      UOM: $("#txtUOM").val(),
      ItemType: $("#txtItemType").val(),
      PurchasePrice: $("#txtPurchasePrice").val(),
      Qty: $("#txtQty").val(),
      Discount: Discount,
      SubTotal: SubTotal,
    };

    if (itemList.rows().count() == 0) {
      $("#txtTotalPayment, #txtRemark").prop("disabled", false);
    }

    newData.push(obj);
    itemList.rows.add(newData).draw();
    clearItemField();
    $("input[type='radio'][name='discount'][value='No Discount']").prop("checked", true).trigger("change");
    // $("#btnSave, #btnCancel").prop("disabled", false);

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

      const format = currentSubTotal.toString().split("").reverse().join("");
      const convert = format.match(/\d{1,3}/g);
      const fixSubTotal = convert.join(".").split("").reverse().join("");

      $("#lblTotal").html(`Total : Rp <strong>${fixSubTotal}</strong>`);

      if ($("#txtTotalChange").val() != "") {
        calculateTotalChanges();
      }
    }
  }
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
$("#ddItemCode").change(function () {
  if ($(this).val() != null && $(this).val() != "") {
    $("body").find("[aria-labelledby='select2-ddItemCode-container']").removeClass("select2-invalid");
  }
});

$("#txtPurchasePrice").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val() != 0) {
    $(this).removeClass("is-invalid");
  }
});

$("#txtQty").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val() != 0) {
    $(this).removeClass("is-invalid");
  }
});

$("#txtDiscount").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val() != 0) {
    $(this).removeClass("is-invalid");
  }
});

$("#ddPercentageDiscount").change(function () {
  if ($(this).val() != null && $(this).val() != "") {
    $("body").find("[aria-labelledby='select2-ddPercentageDiscount-container']").removeClass("select2-invalid");
  }
});

$("#fileUpload").change(function () {
  uploadFile(this);
});

$("input[type='radio'][name='discount']").change(function () {
  const thischecked = $(this).is(":checked");
  $("#txtDiscount, #ddPercentageDiscount").val("").trigger("change");
  discountAutoNum.set("");
  if (thischecked) {
    const thischeckedVal = $(this).val();
    if (thischeckedVal === "No Discount") {
      $("#divFixedDiscount, #divPercentageDiscount").css("display", "none");
    } else if (thischeckedVal === "Percentage") {
      $("#divPercentageDiscount").css("display", "block");
      $("#divFixedDiscount").css("display", "none");
    } else if (thischeckedVal === "Fixed") {
      $("#divPercentageDiscount").css("display", "none");
      $("#divFixedDiscount").css("display", "block");
    }
  }
});

$("#txtTotalPayment").on("input", function () {
  calculateTotalChanges();
});
// End change function

const invalidImport = $("#tblInvalidData").DataTable({
  columns: [
    {
      data: "Seq",
      className: "align-middle",
    },
    {
      data: "ItemCode",
      className: "align-middle",
    },
    {
      data: "Qty",
      className: "align-middle",
    },
    {
      data: "Diskon",
      className: "align-middle",
    },
    {
      data: "Remark",
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
  drawCallback: function () {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  },
  buttons: [
    {
      extend: "excelHtml5",
      title: "",
      filename: "Data Invalid",
      attr: {
        id: "invalidTableExportExcel",
      },
      customize: function (xlsx) {
        let sheet = xlsx.xl.worksheets["sheet1.xml"];
        $("c[r=A1] t", sheet).text("Urutan");
        $("c[r=B1] t", sheet).text("KodeBarang");
        $("c[r=C1] t", sheet).text("Jumlah");
        $("c[r=D1] t", sheet).text("Diskon");
      },
    },
  ],
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

const itemList = $("#tblItemList").DataTable({
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
        return '<button type="button" class="btn btn-outline-danger waves-effect waves-light btn-sm action-button" name="deleteitemtaction"><i class="fas fas fa-trash-alt"></i></button>';
      },
      orderable: false,
      className: "text-center",
    },
  ],
  buttons: [
    {
      extend: "pdfHtml5",
      title: "",
      filename: "Struk",
      attr: {
        id: "pdfStruck",
      },
      exportOptions: {
        columns: [0, 2, 6, 7, 8, 9],
        //orthogonal: 'export'
      },
      messageTop: function () {
        const dt = new Date();
        const fixCurrentDate = dt.getDate().toString().padStart(2, '0') + "-" + 
        (dt.getMonth()+1).toString().padStart(2, '0') + "-" + 
        dt.getFullYear().toString().padStart(4, '0') + " " + 
        dt.getHours().toString().padStart(2, '0') + ":" + 
        dt.getMinutes().toString().padStart(2, '0') + ":" + 
        dt.getSeconds().toString().padStart(2, '0');
        return "Toko Berkat Tani dan Ternak \nContact : " + contact + " \nKasir : " + kasir + " \nTanggal : " + fixCurrentDate;
      },
      messageBottom: function () {
        const cash = $("#txtTotalPayment").val();
        const change = $("#txtTotalChange").val();
        return "\n " + $("#lblTotal").text() + "\nTunai : Rp " + cash + "\n------------------------------------------------------------\nKembalian : Rp " + change;
      },
      footer: true,
      customize: function (doc) {
        // doc.styles.message.alignment = "center";
      },
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
  rowCallback: function (row, data, displayNum, displayIndex, dataIndex) {
    $("td:first", row).html(displayNum + 1 + ".");
  },
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

const saveData = async () => {
  try {
    const url = "../controller/sale/sale.php";
    const Remark = $("#txtRemark").val();
    const arrItemList = [];

    itemList
      .column(1)
      .nodes()
      .to$()
      .each(function (index) {
        const thisItemCode = $(this).closest("td").text();
        const thisItemName = $(this).closest("tr").find("td:eq(2)").text();
        const thisPurchasePrice = $(this).closest("tr").find("td:eq(6)").text();
        const thisQty = $(this).closest("tr").find("td:eq(7)").text();
        const thisDiscount = $(this).closest("tr").find("td:eq(8)").text();
        const obj = {
          ItemCode: thisItemCode,
          ItemName: thisItemName,
          Qty: thisQty.replace(/\D/g, ""),
          PurchasePrice: thisPurchasePrice.replace(/\D/g, ""),
          Discount: thisDiscount.replace(/\D/g, ""),
        };
        arrItemList.push(obj);
      });

    const param = {
      Remark: Remark,
      ItemList: arrItemList,
    };

    showLoading();
    const response = await callAPI(url, "POST", param);

    if (response.success) {
      await printReciept();
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

const printReciept = async () => {
  try {
    const url = "../controller/printreciept/print.php";
    const arrItemList = [];

    itemList
      .column(1)
      .nodes()
      .to$()
      .each(function (index) {
        const thisItemCode = $(this).closest("td").text();
        const thisItemName = $(this).closest("tr").find("td:eq(2)").text();
        const thisPurchasePrice = $(this).closest("tr").find("td:eq(6)").text();
        const thisQty = $(this).closest("tr").find("td:eq(7)").text();
        const thisDiscount = $(this).closest("tr").find("td:eq(8)").text();
        const obj = {
          ItemCode: thisItemCode,
          ItemName: thisItemName,
          Qty: thisQty.replace(/\D/g, ""),
          PurchasePrice: thisPurchasePrice.replace(/\D/g, ""),
          Discount: thisDiscount.replace(/\D/g, ""),
        };
        arrItemList.push(obj);
      });

    const param = {
      ItemList: arrItemList,
    };

    showLoading();
    const response = await callAPI(url, "POST", param);

    if (response.success) {
      showNotif(response.msg, 15000, "success", "top-end");
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
}

const uploadFile = async (thisfile) => {
  try {
    if (fileSelected(thisfile)) {
      if (fileIsExcel(thisfile)) {
        const files = $(thisfile)[0].files;
        const url = "../controller/sale/import.php";

        let fileData = new FormData();
        for (var i = 0; i < files.length; i++) {
          fileData.append("fileUpload", files[i]);
        }

        showLoading();
        const response = await callAPI(url, "POST", fileData, true);

        if (response.success && response.successInsert) {
          showNotif(response.msg, 15000, "success", "top-end");
        } else {
          if (response.datainvalid.length > 0) {
            $("#modalInvalidUpload").on("shown.bs.modal", function (e) {
              $.fn.dataTable
                .tables({
                  visible: true,
                  api: true,
                })
                .columns.adjust();
            });
            $("#modalInvalidUpload").modal("show");
            invalidImport.clear().draw();
            invalidImport.rows.add(response.datainvalid).draw();
          }

          if (response.msg.includes("[ERROR]")) {
            response.msg = response.msg.replace("[ERROR] ", "");
            showNotif(response.msg, 15000, "error", "top-end");
          } else {
            showNotif(response.msg, 15000, "warning", "top-end");
          }
        }
        hideLoading();
      } else {
        showNotif("Silakan pilih hanya file Excel.", 15000, "warning", "top-end");
      }
    } else {
      showNotif("Tidak ada file yang dipilih.", 15000, "warning", "top-end");
    }
    $("#fileUpload").val("");
  } catch (error) {
    console.log(error);
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
