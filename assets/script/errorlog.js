let edit = false;
const currentFormID = "errorlog";

$(document).ready(async function () {
    if (await checkAuthorize(currentFormID)) {
        mainTable.buttons().container().appendTo("#tblMainData_wrapper .col-md-6:eq(0)");
        $(".dt-buttons").css("display", "none");
        await loadData();
        await getFormName(currentFormID);
    } else {
        window.location.replace("../errorpage/403");
    }
});

$("#btnSearch").click(async function (e) {
    await loadData();
});

$("#btnExport").click(function () {
    $("#mainTableExportExcel").click();
});

$("#tblMainData").on("dblclick", "tbody tr", function () {
    const rowData = mainTable.row(this).data();
    $("#modalBodyErrorLog").html(rowData.ErrorDesc);
    $("#modalErrorLog").modal("show");
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
            data: "ErrorID",
            className: "align-middle",
        },
        {
            data: "Form",
            className: "align-middle",
        },
        {
            data: "Module",
            className: "align-middle",
        },
        {
            data: "ErrorDesc",
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
        }
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
            filename: "Data Error Log",
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
        const url = "../controller/errorlog/loaddata.php";
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