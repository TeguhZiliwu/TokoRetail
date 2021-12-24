let edit = false;
const currentFormID = "gainloss";

$(document).ready(async function () {
  if (await checkAuthorize(currentFormID)) {
    mainTable.buttons().container().appendTo("#tblMainData_wrapper .col-md-6:eq(0)");
    $(".dt-buttons").css("display", "none");
    await loadData();
    await getFormName(currentFormID);
    formProp(true);
  } else {
    window.location.replace("../errorpage/403");
  }
});

const formProp = (isDisabled) => {
  $(".maintenance-form, #btnSave, #btnCancel").prop("disabled", isDisabled);
  $("#btnAdd, button[name='editaction'], button[name='deleteaction']").prop("disabled", !isDisabled);
};

const disabledForm = async () => {
  formProp(true);
};

const enabledForm = async () => {
  formProp(false);
};

const clearForm = () => {
  $(".maintenance-form").val("").trigger("change");
  $("#txtGroupID, #txtGroupDescription").removeClass("is-invalid");
};

const validateSubmit = () => {
  let valid = true;
  const validateTitle = "Data tidak valid!";
  const validateDesc = "Silahkan lengkapi data dengan benar!";
  const GroupID = $("#txtGroupID");
  const GroupDesc = $("#txtGroupDescription");

  if (GroupID.val().replace(/\s/g, "") == "" || GroupID.val().length > 20) {
    valid = false;
    GroupID.addClass("is-invalid");
  }

  if (GroupDesc.val().replace(/\s/g, "") == "" || GroupDesc.val().length > 100) {
    valid = false;
    GroupDesc.addClass("is-invalid");
  }

  if (!valid) {
    showNotif(validateDesc, 15000, "warning", "top-end");
  }

  return valid;
};

// Start click function

$("#btnAdd").click(async function () {
  $('a[href="#tabs-detail"]').tab("show");
  formProp(false);
});

$("#btnCancel").click(async function () {
  $('a[href="#tabs-data"]').tab("show");
  formProp(true);
  clearForm();
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
$("#txtGroupID").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 20) {
    $(this).removeClass("is-invalid");
  }
});

$("#txtGroupDescription").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 100) {
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
          return data;
        } else {
          return '<p class="' + profit + ' m-0">' + minus + "Rp " + fixSubTotal + "</p>";
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
    const url = "../controller/group/create_update.php";
    const GroupID = $("#txtGroupID").val();
    const GroupDesc = $("#txtGroupDescription").val();
    const param = {
      GroupID: GroupID,
      GroupDesc: GroupDesc,
      edit: edit === true ? "true" : "false",
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
