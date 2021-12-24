let edit = false;
const currentFormID = "groupaccess";
let detailData = [],
  existingData = [];

$(document).ready(async function () {
  if (await checkAuthorize(currentFormID)) {
    mainTable.buttons().container().appendTo("#tblMainData_wrapper .col-md-6:eq(0)");
    $(".dt-buttons").css("display", "none");
    await loadData();
    await getGroupID();
    await getFormList();
    await getFormName(currentFormID);
    formProp(true);
  } else {
    window.location.replace("../errorpage/403");
  }
});

const formProp = (isDisabled) => {
  $(".maintenance-form, #btnSave, #btnCancel, #cbAllForm, input[type='checkbox'][name='formlist']").prop("disabled", isDisabled);
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
  $("input[type='checkbox'][name='formlist']").prop("checked", false);
  $("body").find("[aria-labelledby='select2-ddGroupID-container']").removeClass("select2-invalid");
  existingData = [];
};

const validateSubmit = () => {
  let valid = true;
  const validateTitle = "Data tidak valid!";
  let validateDesc = "Silahkan lengkapi data dengan benar!";
  const GroupID = $("#ddGroupID");

  if (GroupID.val() == null || GroupID.val() == "") {
    valid = false;
    $("body").find("[aria-labelledby='select2-ddGroupID-container']").addClass("select2-invalid");
  }

  if (GroupID.val() != null && GroupID.val() != "") {
    const totalSelectedForm = $("input[type='checkbox'][name='formlist']:checked").length;
    if (totalSelectedForm == 0) {
      valid = false;
      validateDesc = "Silahkan pilih minimal salah satu form!";
    }
  }

  if (!valid) {
    showNotif(validateDesc, 15000, "warning", "top-end");
  }

  return valid;
};

// Start initialize element

$("#ddGroupID").select2({
  placeholder: "Pilih Group ID",
  // allowClear: true,
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
});

$("#btnSave").click(async function () {
  if (validateSubmit()) {
    await saveData();
    await getMenu();  
  }
});

$("#btnSearch").click(async function (e) {
  await loadData();
});

$("#tblMainData").on("click", 'button[name="editaction"]', function () {
  edit = true;
  const rowData = mainTable.row($(this).parents("tr")).data();
  $("#ddGroupID").val(rowData.GroupID).trigger("change");

  enabledForm();
  $('a[href="#tabs-detail"]').tab("show");
  $("#ddGroupID").prop("disabled", true);
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

$("#tblMainData").on("click", "tbody tr td.dt-control", function () {
  let tr = $(this).closest("tr");
  let row = mainTable.row(tr);

  if (row.child.isShown()) {
    // This row is already open - close it
    row.child.hide();
    tr.removeClass("dt-hasChild");
  } else {
    // Open this row
    let groupid = row.data().GroupID;
    row.child(showDetail(groupid)).show();
    tr.addClass("dt-hasChild");
  }
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
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
$("#ddGroupID").change(async function () {
  if ($(this).val() != null && $(this).val() != "") {
    const GroupID = $(this).val();
    $("body").find("[aria-labelledby='select2-ddGroupID-container']").removeClass("select2-invalid");

    if (GroupID == "Pemilik Usaha") {
      formListTable.column(0).nodes().to$().each(function (index) {
        const mandatoryForm = ["user", "group", "form", "groupaccess"];
        const formName = $(this).find("input[type='checkbox'][name='formlist']").attr("formid");
        if (mandatoryForm.includes(formName)) {
          $(this).find("input[type='checkbox'][name='formlist']").prop("checked", true).prop("disabled", true);
          $(this).find("input[type='checkbox'][name='formlist']").attr("mandatoryform", "true");
        }
      });
    } else {
      formListTable.column(0).nodes().to$().find("input[type='checkbox'][name='formlist']").prop("checked", false).prop("disabled", false);
      formListTable.column(0).nodes().to$().find("input[type='checkbox'][name='formlist']").removeAttr("mandatoryform");
    }

    await getExistingListForm(GroupID);
  }
});

$("#cbAllForm").on("change", function (e) {
  const allForm = $(this).prop("checked");
  if (allForm) {
    formListTable.column(0).nodes().to$().find("input[type='checkbox'][name='formlist'][mandatoryform!='true']").prop("checked", true).trigger("change");
  } else {
    formListTable.column(0).nodes().to$().find("input[type='checkbox'][name='formlist'][mandatoryform!='true']").prop("checked", false).trigger("change");
  }
});

$("#tblFormList").on("change", "input[type='checkbox'][name='formlist']", function () {
  const totalForm = $("input[type='checkbox'][name='formlist']").length;
  const totalSelectedForm = $("input[type='checkbox'][name='formlist']:checked").length;
  const isChecked = $(this).prop("checked");
  const FormID = $(this).attr("formid");

  $("#cbAllForm").prop("checked", totalForm === totalSelectedForm ? true : false);

  if (existingData.some((data) => data.FormID === FormID)) {
    const objIndex = existingData.findIndex((data) => data.FormID === FormID);

    if (isChecked) {
      existingData[objIndex].status = "updated";
    } else {
      if (existingData[objIndex].status === "new") {
        existingData.splice(objIndex, 1);
      } else {
        existingData[objIndex].status = "deleted";
      }
    }
  } else {
    const formList = {
      FormID: FormID,
      status: "new",
    };
    existingData.push(formList);
  }
});

// End change function

const mainTable = $("#tblMainData").DataTable({
  columns: [
    {
      className: "dt-control",
      orderable: false,
      data: null,
      defaultContent: "",
    },
    {
      data: "",
      render: function (data, type, row, meta) {
        return meta.row + 1 + ".";
      },
      className: "align-middle",
    },
    {
      data: "GroupID",
      className: "align-middle",
    },
    {
      data: "GroupDesc",
      className: "align-middle",
    },
    {
      data: "TotalForm",
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
    setTimeout(function () {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    }, 10);
  },
  buttons: [
    {
      extend: "excelHtml5",
      title: "",
      filename: "Data Group Akses",
      attr: {
        id: "mainTableExportExcel",
      },
      exportOptions: {
        columns: ":not(:last-child):not(:first-child)",
        //orthogonal: 'export'
      },
    },
  ],
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
  order: [[1, "asc"]],
});

const formListTable = $("#tblFormList").DataTable({
  columns: [
    {
      data: "",
      render: function (data, type, row, meta) {
        const FormID = row["FormID"].replace(/\s/g, "");
        let rowcheckbox = "";
        rowcheckbox += '<div class="form-check form-check-blue">';
        rowcheckbox += '<input class="form-check-input" type="checkbox" name="formlist" formid="' + FormID + '" id="cb' + FormID.replaceAll(/\s/g, "") + '">';
        rowcheckbox += "</div>";
        return rowcheckbox;
      },
      className: "align-middle",
    },
    {
      data: "FormID",
      className: "align-middle",
    },
    {
      data: "FormName",
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
  dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
  order: [[1, "asc"]],
});

// Load data
const loadData = async () => {
  try {
    const Search = $("#txtSearch").val();
    const url = "../controller/groupaccess/loaddata.php";
    const param = {
      search: Search,
    };

    showLoading();
    mainTable.clear().draw();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      detailData = response.data;
      const groupDistinct = [...new Map(detailData.map((item) => [item["GroupID"], item])).values()];

      mainTable.rows.add(groupDistinct).draw();
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
    const url = "../controller/groupaccess/create_update.php";
    const GroupID = $("#ddGroupID").val();
    const param = {
      GroupID: GroupID,
      FormList: existingData
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
    const url = "../controller/groupaccess/delete.php";
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

const getGroupID = async () => {
  try {
    const url = "../controller/group/fetch_data.php";

    const response = await callAPI(url, "GET");

    if (response.success) {
      let option = "";

      for (let i = 0; i < response.data.length; i++) {
        option += '<option value="' + response.data[i].GroupID + '">' + response.data[i].GroupID + "</option>";
      }
      $("#ddGroupID").html(option).val("").trigger("change");
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

const getFormList = async () => {
  try {
    const url = "../controller/form/fetch_data.php";
    const param = {
      FetchData: "getFormList"
    };
    
    showLoading();
    formListTable.clear().draw();

    const response = await callAPI(url, "GET", param);

    if (response.success) {
      formListTable.rows.add(response.data).draw();
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

const getExistingListForm = async (groupid) => {
  try {
    const url = "../controller/groupaccess/fetch_data.php";
    const param = {
      FetchData: "ExistingFormList",
      GroupID: groupid,
    };

    showLoading();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      const dataFormList = response.data;
      existingData = [];
      $("input[type='checkbox'][name='formlist']").prop("checked", false);
      if (dataFormList.length > 0) {
        for (let i = 0; i < dataFormList.length; i++) {
          const objForm = {
            FormID: dataFormList[i].FormID,
            status: "updated",
          };
          existingData.push(objForm);
          $("input[type='checkbox'][name='formlist'][id='cb" + dataFormList[i].FormID.replaceAll(/\s/g, "") + "']").prop("checked", true).trigger("change");
        }
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
  } catch (error) {
    console.log(error);
    showNotif(error, 15000, "error", "top-end");
  }
};

const showDetail = (groupID) => {
  let generatedTable = "";
  let No = 1;

  generatedTable = '<table class="table nowrap child-table-no-hover" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
  generatedTable += '<thead class="table-secondary">';
  generatedTable += "<tr>";
  generatedTable += "<th>No.</th>";
  generatedTable += "<th>Form ID</th>";
  generatedTable += "<th>Nama Form</th>";
  generatedTable += "<th>Dibuat Oleh</th>";
  generatedTable += "<th>Tanggal Dibuat</th>";
  generatedTable += "<th>Diubah Oleh</th>";
  generatedTable += "<th>Tanggal Diubah</th>";
  generatedTable += "</tr>";
  generatedTable += "</thead>";
  generatedTable += "</tbody>";

  for (let i = 0; i < detailData.length; i++) {
    if (detailData[i].GroupID === groupID) {
      const formID = detailData[i].FormID;
      const formName = detailData[i].FormName;
      const createdBy = detailData[i].CreatedBy;
      const createdDate = detailData[i].CreatedDate === null ? "" : moment(detailData[i].CreatedDate, "YYYY-MM-DD HH:mm:ss").format("DD-MM-YYYY HH:mm:ss");
      const updatedBy = detailData[i].UpdatedBy === null ? "" : detailData[i].UpdatedBy;
      const updatedDate = detailData[i].UpdatedDate === null ? "" : moment(detailData[i].UpdatedDate, "YYYY-MM-DD HH:mm:ss").format("DD-MM-YYYY HH:mm:ss");

      generatedTable += "<tr>";
      generatedTable += "<td>" + No++ + ".</td>";
      generatedTable += "<td>" + formID + "</td>";
      generatedTable += "<td>" + formName + "</td>";
      generatedTable += "<td>" + createdBy + "</td>";
      generatedTable += "<td>" + createdDate + "</td>";
      generatedTable += "<td>" + updatedBy + "</td>";
      generatedTable += "<td>" + updatedDate + "</td>";
      generatedTable += "</tr>";
    }
  }

  generatedTable += "</tbody>" + "</table>";

  return generatedTable;
};
