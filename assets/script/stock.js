let edit = false;
const currentFormID = "stock";

$(document).ready(async function () {
  if (await checkAuthorize(currentFormID)) {
    mainTable.buttons().container().appendTo("#tblMainData_wrapper .col-md-6:eq(0)");
    itemList.buttons().container().appendTo("#tblItemList_wrapper .col-md-6:eq(0)");
    $(".dt-buttons").css("display", "none");
    await loadData();
    await getFormName(currentFormID);
    formProp(true);
  } else {
    window.location.replace("../errorpage/403");
  }
});

const formProp = (isDisabled) => {
  $(".maintenance-form, #btnSave, #btnCancel, #btnAddToList").prop("disabled", isDisabled);
  $("#btnAdd, button[name='editaction'], button[name='deleteaction']").prop(
    "disabled",
    !isDisabled
  );
  $("textarea.form-control").css("resize", isDisabled ? "none" : "vertical").css("height", "120px");
};

const disabledForm = async () => {
  formProp(true);
};

const enabledForm = async () => {
  formProp(false);
};

const clearForm = () => {
  $(".form-control").val("").trigger("change");
  qtyAutoNum.set("");
  purchasePriceAutoNum.set("");
  $("body").find("[aria-labelledby='select2-ddItemCode-container']").removeClass("select2-invalid");
  $("#txtPurchasePrice, #txtQty").removeClass("is-invalid");  
  itemList.clear().draw();
  $("#ddItemCode").empty();
};

const clearItemField = () => {
  $(".form-control").val("").trigger("change");
  qtyAutoNum.set("");
  purchasePriceAutoNum.set("");
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
  }

  if (itemList.rows().count() > 0) {
    let hasSameVal = false;
    itemList.column(1).nodes().to$().each(function (index) {
      const thisItemCode = $(this).closest('td').text();
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

$("#ddItemCode").select2({
  placeholder: "Pilih Barang",
  minimumInputLength: 3,
  ajax: {
    url: "../controller/item/fetch_data.php",
    dataType: 'json',
    delay: 800,
    data: function (params) {
      return {
        search: params.term == undefined ? "" : params.term,
        FetchData: "findItemCode"
      }
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
          sellingprice: item.SellingPrice, 
        });
      });
      return {
        results: results
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
  // allowClear: true,
}).on('select2:select', async function (evt) {
  const data = $(this).select2('data')[0];
  $("#txtItemName").val(data.itemname);
  $("#txtCategory").val(data.category);
  $("#txtUOM").val(data.uom);
  $("#txtItemType").val(data.itemtype);
  sellingPriceAutoNum.set(data.sellingprice);

  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

const sellingPriceAutoNum = new AutoNumeric('#txtSellingPrice', {
  decimalPlaces: 0,
  decimalCharacter: ",",
  digitGroupSeparator: ".",
  modifyValueOnWheel: false,
});

const qtyAutoNum = new AutoNumeric('#txtQty', {
  decimalPlaces: 0,
  digitGroupSeparator: '',
  modifyValueOnWheel: false
});

const purchasePriceAutoNum = new AutoNumeric("#txtPurchasePrice", {
  decimalPlaces: 0,
  decimalCharacter: ",",
  digitGroupSeparator: ".",
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

$("#btnSearch").click(async function (e) {
  await loadData();
});

$("#tblMainData").on("click", 'button[name="editaction"]', function () {
  edit = true;
  const rowData = mainTable.row($(this).parents("tr")).data();
  $("#txtGroupID").val(rowData.GroupID);
  $("#txtGroupDescription").val(rowData.GroupDesc);

  enabledForm();
  $('a[href="#tabs-detail"]').tab("show");
  $("#txtGroupID").prop("disabled", true);
});

$("#tblMainData").on("click", 'button[name="deleteaction"]', function () {
  const rowData = mainTable.row($(this).parents("tr")).data();
  confirmDelete(rowData.GroupID);
});

$("#btnExport").click(function () {
  $("#mainTableExportExcel").click();
});

$('a[href="#tabs-data"], a[href="#tabs-detail"]').click(function () {
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

$("#btnAddToList").click(async function () {
  if (validateAddToList()) {
    let newData = [];
    const obj = {
      ItemCode: $("#ddItemCode").val(),
      ItemName: $("#txtItemName").val(),
      Category: $("#txtCategory").val(),
      UOM: $("#txtUOM").val(),
      ItemType: $("#txtItemType").val(),
      PurchasePrice: $("#txtPurchasePrice").val(),
      Qty: $("#txtQty").val(),
    }
    newData.push(obj);
    itemList.rows.add(newData).draw();
    clearItemField();
  }
});

$("#tblItemList").on("click", 'button[name="deleteitemtaction"]', function () {
  const rowData = mainTable.row($(this).parents("tr")).data();
  itemList.rows($(this).parents('tr')).remove().draw();
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
// End change function

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
      data: "SellingPrice",
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
      $("button[name='editaction'], button[name='deleteaction']").prop(
        "disabled",
        isEdit
      );
    }
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  },
  buttons: [
    {
      extend: "excelHtml5",
      title: "",
      filename: "Data Stock",
      attr: {
        id: "mainTableExportExcel",
      },
      exportOptions: {
        //orthogonal: 'export'
      },
    },
  ],
  dom:
    "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
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
      data: "",
      render: function (data, type, row, meta) {
        return (
          '<button type="button" class="btn btn-outline-danger waves-effect waves-light btn-sm action-button" name="deleteitemtaction"><i class="fas fas fa-trash-alt"></i></button>'
        );
      },
      orderable: false,
      className: "text-center"
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
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
});

// Load data
const loadData = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/stock/loaddata.php";
    const param = {
      search: Search,
    };

    showLoading();
    mainTable.clear().draw();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      mainTable.rows.add(response.data).draw();
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

const saveData = async () => {
  try {
    const url = "../controller/stock/stockin.php";
    const Remark = $("#txtRemark").val();
    const arrItemList = [];

    itemList.column(1).nodes().to$().each(function (index) {
      const thisItemCode = $(this).closest('td').text();
      const thisPurchasePrice = $(this).closest('tr').find("td:eq(6)").text();
      const thisQty = $(this).closest('tr').find("td:eq(7)").text();
      const obj = {
        ItemCode: thisItemCode,
        Qty: thisQty,
        PurchasePrice: thisPurchasePrice.replace(/\D/g, "")
      };
      arrItemList.push(obj);
    });

    const param = {
      Remark: Remark,
      ItemList: arrItemList
    };

    showLoading();
    const response = await callAPI(url, "POST", param);

    if (response.success) {
      showNotif(response.msg, 15000, "success", "top-end");
      await loadData();
      disabledForm();
      clearForm();
      $('a[href="#tabs-data"]').tab("show");
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
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

const confirmDelete = (groupid) => {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success me-2",
      cancelButton: "btn btn-danger",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons
    .fire({
      title: "Hapus Data",
      text: "Apakah anda yakin ingin menghapus data ini?",
      icon: "question",
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonText: "Ya",
      cancelButtonText: "Tidak",
      allowOutsideClick: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        deleteData(groupid);
      } else {
        setTimeout(function () {
          $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        }, 400);
      }
    });
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
};

const deleteData = async (groupid) => {
  try {
    const url = "../controller/group/delete.php";
    const param = {
      GroupID: groupid,
    };

    showLoading();
    const response = await callAPI(url, "POST", param);

    if (response.success) {
      showNotif(response.msg, 15000, "success", "top-end");
      await loadData();
    } else {
      if (response.msg.includes("[ERROR]")) {
        response.msg = response.msg.replace("[ERROR] ", "");
        showNotif(response.msg, 15000, "error", "top-end");
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");
      }
      hideLoading();
    }
    clearForm();
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};