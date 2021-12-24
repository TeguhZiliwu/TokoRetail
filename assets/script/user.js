let edit = false;
const currentFormID = "user";

$(document).ready(async function () {
    if (await checkAuthorize(currentFormID)) {
        mainTable.buttons().container().appendTo("#tblMainData_wrapper .col-md-6:eq(0)");
        $(".dt-buttons").css("display", "none");
        await loadData();
        await getGroupID();
        await getFormName(currentFormID);
        formProp(true);
    } else {
        window.location.replace("../errorpage/403");
    }
});

const formProp = (isDisabled) => {
    $(".maintenance-form, #btnSave, #btnCancel").prop("disabled", isDisabled);
    $("#btnAdd, button[name='editaction'], button[name='deleteaction']").prop(
        "disabled",
        !isDisabled
    );
};

const disabledForm = async () => {
    formProp(true);
};

const enabledForm = async () => {
    formProp(false);
};

const clearForm = () => {
    $(".maintenance-form").val("").trigger("change");
    $("#txtUserID, #txtFullName").removeClass("is-invalid");
    $("#cbbChangePassword").prop("checked", false);
    $("#divChangePassword").css("display", "none");
};

const validateSubmit = () => {
    let valid = true;
    const validateTitle = "Data tidak valid!";
    let validateDesc = "Silahkan lengkapi data dengan benar!";
    const UserID = $("#txtUserID");
    const FullName = $("#txtFullName");
    const Password = $("#txtPassword");
    const ConfirmPassword = $("#txtConfirmPassword");
    const TelpNo = $("#txtTelpNo");
    const GroupID = $("#ddGroupID");
    const isChangePassword = $("#cbbChangePassword").prop("checked");

    if (UserID.val().replace(/\s/g, "") == "" || UserID.val().length > 50) {
        valid = false;
        UserID.addClass("is-invalid");
    }

    if (FullName.val().replace(/\s/g, "") == "" || FullName.val().length > 100) {
        valid = false;
        FullName.addClass("is-invalid");
    }

    if ((isChangePassword && edit) || (!isChangePassword && !edit)) {

        if (Password.val().replace(/\s/g, "") == "" || Password.val().length > 200) {
            valid = false;
            Password.addClass("is-invalid");
        }

        if (ConfirmPassword.val().replace(/\s/g, "") == "" || ConfirmPassword.val().length > 200) {
            valid = false;
            ConfirmPassword.addClass("is-invalid");
        }
    }

    if (TelpNo.val().replace(/\s/g, "") == "" || TelpNo.val().length > 20) {
        valid = false;
        TelpNo.addClass("is-invalid");
    }

    if (GroupID.val() == null || GroupID.val() == "") {
        valid = false;
        $("body").find("[aria-labelledby='select2-ddGroupID-container']").addClass("select2-invalid");
    }

    if (Password.val() != "" && ConfirmPassword.val() != "" && valid) {
        if (Password.val() != ConfirmPassword.val()) {
            valid = false;
            Password.addClass("is-invalid");
            ConfirmPassword.addClass("is-invalid");
            validateDesc = "Password tidak sesuai."
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
    $("#txtUserID").val(rowData.UserID);
    $("#txtFullName").val(rowData.FullName);
    $("#txtEmail").val(rowData.Email);
    $("#txtTelpNo").val(rowData.TelpNo);
    $("#ddGroupID").val(rowData.GroupID).trigger("change");

    enabledForm();
    $('a[href="#tabs-detail"]').tab("show");
    $("#txtUserID").prop("disabled", true);
    $("#txtPassword").prop("disabled", true);
    $("#txtConfirmPassword").prop("disabled", true);
    $("#divChangePassword").css("display", "block");
});

$("#tblMainData").on("click", 'button[name="deleteaction"]', function () {
    const rowData = mainTable.row($(this).parents("tr")).data();
    confirmDelete(rowData.UserID, rowData.GroupID);
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
$("#txtUserID").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 50) {
        $(this).removeClass("is-invalid");
    }
});

$("#txtFullName").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 100) {
        $(this).removeClass("is-invalid");
    }
});

$("#txtPassword").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 200) {
        $(this).removeClass("is-invalid");

        if ($("#txtConfirmPassword").val().replace(/\s/g, "") != "") {
            $("#txtConfirmPassword").removeClass("is-invalid");
        }
    }
});

$("#txtConfirmPassword").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 200) {
        $(this).removeClass("is-invalid");

        if ($("#txtPassword").val().replace(/\s/g, "") != "") {
            $("#txtPassword").removeClass("is-invalid");
        }
    }
});

$("#txtEmail").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 100) {
        $(this).removeClass("is-invalid");
    }
});

$("#txtTelpNo").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 20) {
        $(this).removeClass("is-invalid");
    }
});

$("#ddGroupID").change(function () {
    if ($(this).val() != null && $(this).val() != "") {
        $("body").find("[aria-labelledby='select2-ddGroupID-container']").removeClass("select2-invalid");
    }
});

$("#cbbChangePassword").change(function () {
    const thisVal = $(this).prop("checked");
    if (thisVal) {
        $("#txtPassword, #txtConfirmPassword").prop("disabled", false);
    } else {
        $("#txtPassword, #txtConfirmPassword").prop("disabled", true).val("");
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
            data: "UserID",
            className: "align-middle",
        },
        {
            data: "FullName",
            className: "align-middle",
        },
        {
            data: "Email",
            className: "align-middle",
        },
        {
            data: "TelpNo",
            className: "align-middle",
        },
        {
            data: "GroupID",
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
                    return moment(value, "YYYY-MM-DD HH:mm:ss").format(
                        "DD-MM-YYYY HH:mm:ss"
                    );
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
                    return moment(value, "YYYY-MM-DD HH:mm:ss").format(
                        "DD-MM-YYYY HH:mm:ss"
                    );
                }
            },
            className: "align-middle",
        },
        {
            data: "",
            render: function (data, type, row, meta) {
                return (
                    '<button type="button" class="btn btn-outline-success waves-effect waves-light btn-sm me-1 action-button" name="editaction"><i class="fas fa-edit" style></i></button>' +
                    '<button type="button" class="btn btn-outline-danger waves-effect waves-light btn-sm action-button" name="deleteaction"><i class="fas fas fa-trash-alt"></i></button>'
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
            filename: "Data User",
            attr: {
                id: "mainTableExportExcel",
            },
            exportOptions: {
                columns: ":not(:last-child)",
                //orthogonal: 'export'
            },
        },
    ],
    dom:
        "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});

// Load data
const loadData = async () => {
    try {
        const Search = $("#txtSearch").val();
        const url = "../controller/user/loaddata.php";
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
        const url = "../controller/user/create_update.php";
        const UserID = $("#txtUserID").val();
        const FullName = $("#txtFullName").val();
        const Password = $("#txtPassword").val();
        const Email = $("#txtEmail").val();
        const TelpNo = $("#txtTelpNo").val();
        const GroupID = $("#ddGroupID").val();
        const editPassword = $("#cbbChangePassword").prop("checked");
        const param = {
            UserID: UserID,
            FullName: FullName,
            Password: Password,
            Email: Email,
            TelpNo: TelpNo,
            GroupID: GroupID,
            editPassword: editPassword === true ? "true" : "false",
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

const confirmDelete = (userid, groupid) => {
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
                deleteData(userid, groupid);
            } else {
                setTimeout(function () {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                }, 400);
            }
        });
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
};

const deleteData = async (userid, groupid) => {
    try {
        const url = "../controller/user/delete.php";
        const param = {
            UserID: userid,
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

$('[data-toggle="input-mask"]').each(function (a, e) {
    const t = $(e).data("maskFormat"),
        n = $(e).data("reverse");
    null != n ? $(e).mask(t, { reverse: n }) : $(e).mask(t);
});
