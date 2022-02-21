let edit = false;
const currentFormID = "globalsetting";
let pictureItemDeletion = [];

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
  $(".maintenance-form, #btnSave, #btnCancel, #btnAddNewPicture").prop("disabled", isDisabled);
  $("#btnAdd, button[name='editaction']").prop("disabled", !isDisabled);

  if (!isDisabled) {
    const settingValue = $("#txtSettingID").val();
    if (settingValue == "SlidePromotionPage") {
      $("#rowSettingValue").css("display", "block");
      $("#SettingValue").css("display", "none");
    } else if (settingValue == "isPrintActive") {
      $("#rowSettingValue").css("display", "none");
      $("#SettingValue").css("display", "block");
      const checkboxEl = '<div class="row checkbox-input-wrapper"><div class="col-12"><div class="form-check form-check-blue" id="divChangePassword">' + '<input class="form-check-input form-check-input-large" type="checkbox" value="" id="cbbSettingValue">' + "</div></div></div>";
      $("#txtSettingValue").remove();
      $("#SettingValue").append(checkboxEl);
    } else {
      $("#rowSettingValue").css("display", "none");
      $("#SettingValue").css("display", "block");
    }
  }
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
};

const disabledForm = async () => {
  formProp(true);
};

const enabledForm = async () => {
  formProp(false);
};

const clearForm = () => {
  $(".maintenance-form").val("").trigger("change");
  $("#txtSettingID, #txtRemark").removeClass("is-invalid");
  pictureTable.clear().draw();
  pictureItemDeletion = [];
  
  $(".checkbox-input-wrapper, #txtSettingValue").remove();
  const inputEl = '<input type="text" class="form-control maintenance-form" id="txtSettingValue" placeholder="Masukkan Setting Value" autocomplete="off" maxlength="2000">';
  $("#SettingValue").append(inputEl);
  $("#rowSettingValue").css("display", "none");
  $("#SettingValue").css("display", "block");
};

const validateSubmit = () => {
  let valid = true;
  const validateTitle = "Data tidak valid!";
  let validateDesc = "Silahkan lengkapi data dengan benar!";
  const SettingID = $("#txtSettingID");
  const SettingValue = $("#txtSettingValue");

  if (SettingID.val().replace(/\s/g, "") == "" || SettingID.val().length > 50) {
    valid = false;
    SettingID.addClass("is-invalid");
  }

  if (SettingID.val().replace(/\s/g, "") == "SlidePromotionPage") {
    if (pictureTable.rows().count() > 0) {
      let hasNoPict = 0;

      pictureTable
        .column(1)
        .nodes()
        .to$()
        .each(function (index) {
          const fileValue = $(this).find("input[type='file']").val();
          const itempictureid = pictureTable.row($(this).parents("tr")).data().PictureName;
          if (fileValue == "" && itempictureid == "") {
            hasNoPict++;
            $(this).find("input[type='file']").siblings("input.custom-file-group-table").addClass("is-invalid");
          }
        });

      if (hasNoPict > 0) {
        valid = false;
      }
    } else {
      valid = false;
      validateDesc = "Silahkan pilih minimal 1 foto.";
    }
  } else {
    if (SettingID.val().replace(/\s/g, "") != "isPrintActive") {
      if (SettingValue.val().replace(/\s/g, "") == "" || SettingValue.val().length > 2000) {
        valid = false;
        SettingValue.addClass("is-invalid");
      }
    }
  }

  if (!valid) {
    showNotif(validateDesc, 15000, "warning", "top-end");
  }

  return valid;
};

// Start click function

$("#btnAdd").click(async function () {
  edit = false;
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

$("#tblMainData").on("click", 'button[name="editaction"]', async function () {
  edit = true;
  const rowData = mainTable.row($(this).parents("tr")).data();
  $("#txtSettingID").val(rowData.SettingID);
  $("#txtRemark").val(rowData.Remark);

  enabledForm();
  $('a[href="#tabs-detail"]').tab("show");
  $("#txtSettingID").prop("disabled", true);

  const settingValue = $("#txtSettingID").val();
  if (settingValue == "SlidePromotionPage") {
    await getPicture();
  } else if (settingValue == "isPrintActive") {
    $("#cbbSettingValue").prop("checked", rowData.SettingValue.toUpperCase() == "TRUE" ? true : false);
  } else {
    $("#txtSettingValue").val(rowData.SettingValue);
  }

  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
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

$("#btnAddNewPicture").click(async function () {
  let newData = [];
  const obj = {
    OldPictureName: "",
    PictureName: "",
  };
  newData.push(obj);
  pictureTable.rows.add(newData).draw();
  if (pictureTable.rows().count() == 5) {
    $(this).prop("disabled", true);
  }
});

$("#tblItemPicture").on("click", ".custom-file-group-table, .custom-file-span-table", function () {
  $(this).siblings("input[type='file']").click();
});

$("#tblItemPicture").on("click", 'button[name="deletepictaction"]', function () {
  const rowData = pictureTable.row($(this).parents("tr")).data();

  if (rowData.ItemPictureID != "") {
    const obj = {
      OldPictureName: rowData.OldPictureName,
      PictureName: rowData.PictureName,
    };
    pictureItemDeletion.push(obj);
  }

  pictureTable.rows($(this).parents("tr")).remove().draw();
  if (pictureTable.rows().count() < 5) {
    $("#btnAddNewPicture").prop("disabled", false);
  }
});

$("#tblItemPicture").on("click", 'button[name="viewpictaction"]', async function () {
  const rowData = pictureTable.row($(this).parents("tr")).data();
  const attrFileLink = $(this).closest("td").parent().find('input[type="file"].custom-file-input-table').attr("filename");
  if (attrFileLink != "" && attrFileLink != undefined && $(this).closest("td").parent().find('input[type="file"]').val() == "") {
    let path = await getGlobalSetting("PathPromotionPicture");
    path = "../" + path;
    const filename = $(this).closest("td").parent().find('input[type="file"].custom-file-input-table').attr("filename");
    $("#modalViewPict").modal("show");
    $("#imgPreview").attr("src", path + filename);
  } else {
    if ($(this).closest("td").parent().find('input[type="file"]').val() != "") {
      const file = $(this).closest("td").parent().find('input[type="file"]').get(0).files[0];
      let url = URL.createObjectURL(file);
      url = url.replace("~", "");
      $("#modalViewPict").modal("show");
      $("#imgPreview").attr("src", url);
    }
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
$("#txtSettingID").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 50) {
    $(this).removeClass("is-invalid");
  }
});

$("#txtSettingValue").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 2000) {
    $(this).removeClass("is-invalid");
  }
});

$("#fileUpload").change(function () {
  uploadFile(this);
});

$("#tblItemPicture").on("change", ".custom-file-input-table", function () {
  validateImage(this);
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
      data: "SettingID",
      className: "align-middle",
    },
    {
      data: "SettingValue",
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
        return '<button type="button" class="btn btn-outline-success waves-effect waves-light btn-sm me-1 action-button" name="editaction"><i class="fas fa-edit" style></i></button>';
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

const pictureTable = $("#tblItemPicture").DataTable({
  columns: [
    {
      data: "",
      render: function (data, type, row, meta) {
        return meta.row + 1 + ".";
      },
      className: "align-middle",
    },
    {
      data: "PictureName",
      render: function (data, type, row, meta) {
        let rownumber = meta.row + 1;
        const itemcode = row["ItemCode"];
        let inputFile = "";
        inputFile = '<div class="input-group">';
        inputFile += '<input type="file" class="form-control custom-file-input-table" itemcode="' + itemcode + '" filename="' + data + '" id="inputFile_' + rownumber + '" hidden accept=".PNG, .JPG, .JPEG" >';
        inputFile += '<span class="input-group-text custom-file-span-table">Pilih foto</span>';
        inputFile += '<input type="text" class="form-control custom-file-group-table" placeholder="Tidak ada foto" readonly>';
        inputFile += "</div>";
        return inputFile;
      },
      className: "align-middle",
    },
    {
      data: "",
      render: function (data, type, row, meta) {
        return '<button type="button" class="btn btn-outline-success waves-effect waves-light btn-sm me-1 action-button" name="viewpictaction"><i class="fas fa-eye" style></i></button>' + '<button type="button" class="btn btn-outline-danger waves-effect waves-light btn-sm action-button" name="deletepictaction"><i class="fas fas fa-trash-alt"></i></button>';
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
    emptyTable: "Tidak ada data foto barang",
  },
  drawCallback: function () {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();

    $("input[type='file'].custom-file-input-table").each(function () {
      const ItemCode = $(this).attr("itemcode");
      let FileName = $(this).attr("filename");
      if (FileName != "") {
        FileName = FileName.replace(ItemCode + "_", "");
        $(this).siblings(".custom-file-group-table").val(FileName);
      }
    });
  },
  rowCallback: function (row, data, displayNum, displayIndex, dataIndex) {
    $("td:first", row).html(displayNum + 1 + ".");
    $("td:eq(1)", row)
      .find("input[type='file'].custom-file-input-table")
      .attr("id", "inputFile_" + (displayNum + 1) + "");
  },
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

// Load data
const loadData = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/globalsetting/loaddata.php";
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
    const url = "../controller/globalsetting/create_update.php";
    const SettingID = $("#txtSettingID").val();
    let SettingValue = "";
    const Remark = $("#txtRemark").val();

    if (SettingID == "SlidePromotionPage") {
      SettingValue = "";
      pictureTable
        .column(1)
        .nodes()
        .to$()
        .each(function (index) {
          const valFiles = $(this).find("input[type='file'].custom-file-input-table").val();
          const valName = $(this).find("input.custom-file-group-table").val();

          if (valFiles != "") {
            const files = $(this).find("input[type='file'].custom-file-input-table")[0].files;
            SettingValue += files[0].name + "|";
          }

          if (valName != "Tidak ada foto" && valFiles == "") {
            SettingValue += valName + "|";
          }
        });

      SettingValue = SettingValue.substring(0, SettingValue.length - 1);
    } else if (SettingID == "isPrintActive") {
      SettingValue = $("#cbbSettingValue").prop("checked") == true ? "true" : "false";
    } else {
      SettingValue = $("#txtSettingValue").val();
    }

    const param = {
      SettingID: SettingID,
      SettingValue: SettingValue,
      Remark: Remark,
      edit: edit === true ? "true" : "false",
    };
    showLoading();
    const response = await callAPI(url, "POST", param);

    if (response.success) {
      showNotif(response.msg, 15000, "success", "top-end");
      await uploadImage();
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

const validateImage = async (thisfile) => {
  try {
    if (fileSelected(thisfile)) {
      if (fileIsPicture(thisfile)) {
        const tr = $(thisfile).closest("tr");
        const rowData = pictureTable.row(tr).data();
        const listImage = [];
        const files = $(thisfile)[0].files;
        const fileName = files[0].name;
        const currentID = $(thisfile).attr("id");
        let hasSameVal = false;

        pictureTable
          .column(1)
          .nodes()
          .to$()
          .each(function (index) {
            const valFiles = $(this).find("input[type='file'].custom-file-input-table").val();
            if (valFiles != "") {
              const name = $(this).find("input[type='file'].custom-file-input-table")[0].files[0].name;
              const ID = $(this).find("input[type='file'].custom-file-input-table").attr("id");
              const obj = {
                ID: ID,
                Name: name,
              };
              listImage.push(obj);
            }
          });

        for (let i = 0; i < listImage.length; i++) {
          if (currentID != listImage[i].ID) {
            if (fileName == listImage[i].Name) {
              hasSameVal = true;
              break;
            }
          }
        }

        if (hasSameVal) {
          showNotif("Foto dengan nama yang sama sudah ada didalam list.", 15000, "warning", "top-end");
          $(thisfile).val("");
          $(thisfile).siblings("input.custom-file-group-table").val("Tidak ada foto");
        } else {
          $(thisfile).siblings("input.custom-file-group-table").val(fileName);
          $(thisfile).siblings("input.custom-file-group-table").removeClass("is-invalid");

          if (rowData.OldPictureName != "") {
            const obj = {
              OldPictureName: rowData.OldPictureName,
            };
            pictureItemDeletion.push(obj);
          }
        }
      } else {
        showNotif("Silakan pilih hanya file PNG, JPG dan JPEG.", 15000, "warning", "top-end");
        $(thisfile).val("");
        $(thisfile).siblings("input.custom-file-group-table").val("Tidak ada foto");
      }
    } else {
      showNotif("Tidak ada file yang dipilih.", 15000, "warning", "top-end");
      $(thisfile).val("");
      $(thisfile).siblings("input.custom-file-group-table").val("Tidak ada foto");
    }
  } catch (error) {
    console.log(error);
    showNotif(error, 15000, "error", "top-end");
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
const fileIsPicture = (thisfile) => {
  let file = $(thisfile);
  let ext = file.val().substring(file.val().lastIndexOf(".") + 1);

  if (ext.toUpperCase() == "PNG" || ext.toUpperCase() == "JPG" || ext.toUpperCase() == "JPEG") {
    return true;
  } else {
    return false;
  }
};

const uploadImage = async () => {
  try {
    const url = "../controller/globalsetting/uploadimage.php";
    let fileData = new FormData();
    fileData.append("pictureItemDeletion", JSON.stringify(pictureItemDeletion));
    let hasFileToUpload = "no";

    pictureTable
      .column(1)
      .nodes()
      .to$()
      .each(function (index) {
        const valFiles = $(this).find("input[type='file'].custom-file-input-table").val();

        if (valFiles != "") {
          const files = $(this).find("input[type='file'].custom-file-input-table")[0].files;
          fileData.append("fileImage[]", files[0]);
          hasFileToUpload = "yes";
        }
      });

    if (hasFileToUpload == "no") {
      fileData.append("fileImage[]", null);
    }
    fileData.append("hasFileToUpload", hasFileToUpload);

    showLoading();
    const response = await callAPI(url, "POST", fileData, true);

    if (response.success) {
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
    console.log(error);
    showNotif(error, 15000, "error", "top-end");
  }
};

const getPicture = async () => {
  try {
    const url = "../controller/globalsetting/fetch_data.php";
    const param = {
      FetchData: "getGlobalValue",
      SettingID: "SlidePromotionPage",
      isPromotionPage: "false",
    };

    showLoading();
    pictureTable.clear().draw();
    const response = await callAPI(url, "GET", param);
    console.log(response);

    if (response.success) {
      const data = response.data[0].SettingValue.split("|");
      const newData = [];
      for (let i = 0; i < data.length; i++) {
        const obj = {
          OldPictureName: data[i],
          PictureName: data[i],
        };
        newData.push(obj);
      }
      pictureTable.rows.add(newData).draw();
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
