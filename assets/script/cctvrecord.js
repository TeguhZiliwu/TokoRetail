let edit = false;
const currentFormID = "cctvrecord";

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
  $("textarea.maintenance-form")
    .css("resize", isDisabled ? "none" : "vertical")
    .css("height", "128px");
};

const disabledForm = async () => {
  formProp(true);
};

const enabledForm = async () => {
  formProp(false);
};

const clearForm = () => {
  $(".maintenance-form, #txtCCTVRecordID, #txtRemark").val("").trigger("change");
  $("#txtCCTVRecordID, #txtRemark").removeClass("is-invalid");
  $("#btnView").prop("disabled", true);
  $("#txtCCTVFileUpload").siblings(".custom-file-group").val("Pilih Rekaman CCTV");
  $("#txtCCTVFileUpload").val("");  
  $("#btnView").attr("src", "");
};

const validateSubmit = () => {
  let valid = true;
  const validateTitle = "Data tidak valid!";
  const validateDesc = "Silahkan lengkapi data dengan benar!";
  const FileUpload = $("#txtCCTVFileUpload");
  const Remark = $("#txtRemark");
  const btnView = $("#btnView");

  if (FileUpload.val().replace(/\s/g, "") == "") {
    if (!btnView.attr("src")) {
      valid = false;
      FileUpload.siblings(".custom-file-group").addClass("is-invalid");
    }
  }

  if (Remark.val().replace(/\s/g, "") == "" || Remark.val().length > 2000) {
    valid = false;
    Remark.addClass("is-invalid");
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

$("body").on("click", ".custom-file-group", function () {
  $(this).siblings("input[type='file']").click();
});

$("body").on("click", ".custom-span-group", function () {
  $(this).siblings(".custom-file-group").click();
});

$("#tblMainData").on("click", 'button[name="editaction"]', async function () {
  edit = true;
  const rowData = mainTable.row($(this).parents("tr")).data();
  const CCTVRecordID = rowData.CCTVRecordID;
  const fileName = rowData.RecordName;
  const pathName = await getGlobalSetting("PathCCTVRecord");
  const path = "../" + pathName + CCTVRecordID + "_" + fileName;

  $("#txtCCTVRecordID").val(CCTVRecordID);
  $("#txtCCTVFileUpload").siblings(".custom-file-group").val(fileName);
  $("#txtRemark").val(rowData.Remark);
  $("#btnView").attr("src", path);

  enabledForm();
  $('a[href="#tabs-detail"]').tab("show");
  $("#txtCCTVRecordID").prop("disabled", true);
  $(".custom-file-group, .custom-span-group").prop("disabled", true);
  
  $("#btnView").prop("disabled", false);
});

$("#tblMainData").on("click", 'button[name="deleteaction"]', function () {
  const rowData = mainTable.row($(this).parents("tr")).data();
  confirmDelete(rowData.CCTVRecordID);
});

$("#btnExport").click(function () {
  $("#mainTableExportExcel").click();
});

$("#btnView").click(function () {
  $("#modalViewVideo").modal("show");
  if ($(this).attr("src")) {
    $("#frameCCTVRecord").attr("src", $(this).attr("src"));
  } else {
    const fileVid = $("#txtCCTVFileUpload");
    let url = URL.createObjectURL(fileVid[0].files[0]);
    $("#frameCCTVRecord").attr("src", url);
  }
});

$("#tblMainData").on("click", ".view-record-table", async function () {
  const rowData = mainTable.row($(this).parents("tr")).data();
  const CCTVRecordID = rowData.CCTVRecordID;
  const fileName = rowData.RecordName;
  const pathName = await getGlobalSetting("PathCCTVRecord");
  const path = "../" + pathName + CCTVRecordID + "_" + fileName;
  $("#frameCCTVRecord").attr("src", path);
  $("#modalViewVideo").modal("show");
});

$("#modalViewVideo").on("hidden.bs.modal", function () {
  $("#frameCCTVRecord").attr("src", "");
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
$("#txtCCTVFileUpload").change(function () {
  validateVideo(this);
});

$("#txtRemark").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 2000) {
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
      data: "RecordName",
      className: "align-middle",
    },
    {
      data: "RecordName",
      render: function (value) {
        return '<button type="button" class="btn btn-blue waves-effect waves-light view-record-table">Lihat Rekaman <i class="fe-play"></i></button>';
      },
      className: "align-middle",
    },
    {
      data: "Remark",
      className: "align-middle",
    },
    {
      data: "CreatedBy",
      className: "align-middle",
    },
    {
      data: "CreatedDate",
      render: function (value) {
        if (value === null) {
          return "";
        } else {
          return moment(value, "YYYY-MM-DD HH:mm:ss").format("DD-MM-YYYY HH:mm:ss");
        }
      },
      className: "align-middle",
    },
    {
      data: "UpdatedBy",
      className: "align-middle",
    },
    {
      data: "UpdatedDate",
      render: function (value) {
        if (value === null) {
          return "";
        } else {
          return moment(value, "YYYY-MM-DD HH:mm:ss").format("DD-MM-YYYY HH:mm:ss");
        }
      },
      className: "align-middle",
    },
    {
      data: "",
      render: function (data, type, row, meta) {
        return '<button type="button" class="btn btn-outline-success waves-effect waves-light btn-sm me-1 action-button" name="editaction"><i class="fas fa-edit" style></i></button>' + '<button type="button" class="btn btn-outline-danger waves-effect waves-light btn-sm action-button" name="deleteaction"><i class="fas fas fa-trash-alt"></i></button>';
      },
      orderable: false,
      className: "text-center",
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
      filename: "Data Group",
      attr: {
        id: "mainTableExportExcel",
      },
      exportOptions: {
        columns: ":not(:last-child)",
        //orthogonal: 'export'
      },
    },
  ],
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

// Load data
const loadData = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/cctvrecord/loaddata.php";
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
    const url = "../controller/cctvrecord/create_update.php";
    const RecordID = $("#txtCCTVRecordID").val();
    const Remark = $("#txtRemark").val();
    const files = $("#txtCCTVFileUpload")[0].files;

    let fileData = new FormData();
    if (files.length > 0) {
      for (var i = 0; i < files.length; i++) {
        fileData.append("fileUpload", files[i]);
        fileData.append("hasFileToUpload", "yes");
      }
    } else {
      fileData.append("fileUpload", null);
      fileData.append("hasFileToUpload", "no");
    }
    fileData.append("RecordID", RecordID);
    fileData.append("Remark", Remark);
    fileData.append("edit", edit === true ? "true" : "false");

    showLoading();
    const response = await callAPI(url, "POST", fileData, true);

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

const confirmDelete = (cctvrecorid) => {
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
        deleteData(cctvrecorid);
      } else {
        setTimeout(function () {
          $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        }, 400);
      }
    });
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
};

const deleteData = async (cctvrecorid) => {
  try {
    const url = "../controller/cctvrecord/delete.php";
    const param = {
      RecordID: cctvrecorid,
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

const validateVideo = async (thisfile) => {
  try {
    const extVid = ["WMV", "M4V", "AVI", "MPG", "MPEG", "MP4", "WEBM"];
    if (fileSelected(thisfile)) {
      if (fileIsVideo(thisfile, extVid)) {
        const tr = $(thisfile).closest("tr");
        const files = $(thisfile)[0].files;
        const fileName = files[0].name;

        $(thisfile).siblings("input.custom-file-group").val(fileName);
        $(thisfile).siblings("input.custom-file-group").removeClass("is-invalid");
        $("#btnView").prop("disabled", false);
      } else {
        let extName = "";
        for (let i = 0; i < extVid.length; i++) {
          extName += extVid[i] + ", ";
        }
        extName = extName.substring(0, extName.length - 3);
        showNotif("Silakan pilih hanya file " + extName + ".", 15000, "warning", "top-end");
        $(thisfile).val("");
        $(thisfile).siblings("input.custom-file-group").val("Pilih Rekaman CCTV");
        $("#btnView").prop("disabled", true);
      }
    } else {
      showNotif("Tidak ada file yang dipilih.", 15000, "warning", "top-end");
      $(thisfile).val("");
      $(thisfile).siblings("input.custom-file-group").val("Pilih Rekaman CCTV");
      $("#btnView").prop("disabled", true);
    }
  } catch (error) {
    console.log(error);
    showNotif(error, 15000, "error", "top-end");
    $("#btnView").prop("disabled", true);
  }
};

const fileSelected = (thisfile) => {
  var file = $(thisfile)[0];
  if (file.files.length > 0) {
    return true;
  } else {
    return false;
  }
};

// Validate file extension
const fileIsVideo = (thisfile, extVid) => {
  let file = $(thisfile);
  let ext = file.val().substring(file.val().lastIndexOf(".") + 1);

  if (extVid.includes(ext.toUpperCase())) {
    return true;
  } else {
    return false;
  }
};
