let edit = false;
const currentFormID = "uom";

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
    $("#txtUOMCode").val("[AUTO]");
    $(".maintenance-form").val("").trigger("change");
    $("#txtUOM, #txtUOMDesc").removeClass("is-invalid");
};

const validateSubmit = () => {
    let valid = true;
    const validateTitle = "Data tidak valid!";
    const validateDesc = "Silahkan lengkapi data dengan benar!";
    const Category = $("#txtUOM");

    if (Category.val().replace(/\s/g, "") == "" || Category.val().length > 6) {
        valid = false;
        Category.addClass("is-invalid");
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
    $("#txtUOMCode").val(rowData.UOMCode);
    $("#txtUOM").val(rowData.UOM);
    $("#txtUOMDesc").val(rowData.UOMDesc);

    enabledForm();
    $('a[href="#tabs-detail"]').tab("show");
    $("#txtUOM").prop("disabled", true);
});

$("#tblMainData").on("click", 'button[name="deleteaction"]', function () {
    const rowData = mainTable.row($(this).parents("tr")).data();
    confirmDelete(rowData.UOMCode);
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
$("#txtUOM").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 6) {
        $(this).removeClass("is-invalid");
    }
});

$("#txtUOMDesc").change(function () {
    if (
        $(this).val().replace(/\s/g, "") != "" &&
        $(this).val().length != 0 &&
        $(this).val().length <= 100
    ) {
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
            data: "UOMCode",
            className: "align-middle",
        },
        {
            data: "UOM",
            className: "align-middle",
        },
        {
            data: "UOMDesc",
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
            filename: "Data Satuan",
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
        const url = "../controller/uom/loaddata.php";
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
        const url = "../controller/uom/create_update.php";
        const UOMCode = $("#txtUOMCode").val();
        const UOM = $("#txtUOM").val();
        const UOMDesc = $("#txtUOMDesc").val();
        const param = {
            UOMCode: UOMCode,
            UOM: UOM,
            UOMDesc: UOMDesc,
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

const confirmDelete = (uomcode) => {
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
                deleteData(uomcode);
            } else {
                setTimeout(function () {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                }, 400);
            }
        });
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
};

const deleteData = async (uomcode) => {
    try {
        const url = "../controller/uom/delete.php";
        const param = {
            UOMCode: uomcode,
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