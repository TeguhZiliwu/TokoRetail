let edit = false;
const currentFormID = "report";

$(document).ready(async function () {
    if (await checkAuthorize(currentFormID)) {
        const DateNow = new Date();
        const todayDate = DateNow.getFullYear() + '-' + ('0' + (DateNow.getMonth() + 1)).slice(-2) + '-' + ('0' + DateNow.getDate()).slice(-2);

        mainTable.buttons().container().appendTo("#tblMainData_wrapper .col-md-6:eq(0)");
        $(".dt-buttons").css("display", "none");
        setDatetimepicker($('#txtFromDate'), "DAILY", todayDate);
        setDatetimepicker($('#txtToDate'), "DAILY", todayDate, todayDate);

        $('#txtFromDate').on("dp.change", function () {
            const fromDate = $(this).val();
            const toDate = $("#txtToDate").val();
            const period = $("#ddPeriod").val();

            if (fromDate != "" && toDate != "") {
                let fixToDate = toDate.split("-");
                let fixFromDate = fromDate.split("-");

                if (period === "Daily") {
                    fixFromDate = fixFromDate[2] + "-" + fixFromDate[1] + "-" + fixFromDate[0] + " 24:00:00";
                    fixToDate = fixToDate[2] + "-" + fixToDate[1] + "-" + fixToDate[0];
                    const minDate = new Date(fixFromDate);
                    const defToDate = new Date(fixToDate);
                    $('#txtToDate').datetimepicker("destroy").val("");
                    if (stringToDate(fromDate, "dd-mm-yyyy", "-") > stringToDate(toDate, "dd-mm-yyyy", "-")) {
                        setDatetimepicker($('#txtToDate'), "Daily", minDate, minDate);
                    } else {
                        setDatetimepicker($('#txtToDate'), "Daily", defToDate, minDate);
                    }
                } else if (period === "Monthly") {
                    fixFromDate = fixFromDate[1] + "-" + fixFromDate[0] + "-01 24:00:00";
                    fixToDate = fixToDate[1] + "-" + fixToDate[0] + "-01 00:00:00";
                    const minDate = new Date(fixFromDate);
                    const defToDate = new Date(fixToDate);
                    $('#txtToDate').datetimepicker("destroy").val("");
                    if (stringToDate(fromDate, "mm-yyyy", "-", "Monthly") > stringToDate(toDate, "mm-yyyy", "-", "Monthly")) {
                        setDatetimepicker($('#txtToDate'), "Monthly", minDate, minDate);
                    } else {
                        setDatetimepicker($('#txtToDate'), "Monthly", defToDate, minDate);
                    }
                } else if (period === "Yearly") {
                    const minDate = new Date(fixFromDate);
                    const defToDate = new Date(fixToDate);
                    $('#txtToDate').datetimepicker("destroy").val("");
                    if (stringToDate(fromDate, "yyyy", "-", "Yearly") > stringToDate(toDate, "yyyy", "-", "Yearly")) {
                        setDatetimepicker($('#txtToDate'), "Yearly", minDate, minDate);
                    } else {
                        setDatetimepicker($('#txtToDate'), "Yearly", defToDate, minDate);
                    }
                }
            }
        });
        await loadData();
        await getFormName(currentFormID);
    } else {
        window.location.replace("../errorpage/403");
    }
});

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

    if (
        GroupDesc.val().replace(/\s/g, "") == "" ||
        GroupDesc.val().length > 100
    ) {
        valid = false;
        GroupDesc.addClass("is-invalid");
    }

    if (!valid) {
        showNotif(validateDesc, 15000, "warning", "top-end");
    }

    return valid;
};

// Start initialize element

$('#ddReportType').select2({
    placeholder: "Pilih Jenis Laporan",
    //   allowClear: true
});

$('#ddPeriod').select2({
    placeholder: "Pilih Periode",
    //   allowClear: true
});

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
                    itemdesc: item.ItemDesc,
                    sellingprice: item.SellingPrice,
                    qty: item.Qty
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
    allowClear: true,
}).on('select2:select', async function (evt) {
}).on('select2:clear', async function (evt) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    $("#ddItemCode").empty();
});

// End initialize element

$("#txtFromDate, #txtToDate").on("keydown", function (e) {
    preventInput(e);
});

// Start click function

$("#btnSearch").click(async function (e) {
    await loadData();
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

$("#ddPeriod").change(function () {
    const period = $(this).val();
    const DateNow = new Date();
    let todayDate = DateNow.getFullYear() + '-' + ('0' + (DateNow.getMonth() + 1)).slice(-2) + '-' + ('0' + DateNow.getDate()).slice(-2);

    $('#txtFromDate, #txtToDate').datetimepicker("destroy").val("");
    if (period === "Daily") {
        setDatetimepicker($('#txtFromDate'), "Daily", todayDate);
        setDatetimepicker($('#txtToDate'), "Daily", todayDate, todayDate);
    } else if (period === "Monthly") {        
        todayDate = todayDate.split("-");        
        const minDate = todayDate[0] + "-" + todayDate[1] + "-01 24:00:00";
        const defDate = todayDate[0] + "-" + todayDate[1] + "-01 00:00:00";

        setDatetimepicker($('#txtFromDate'), "Monthly", defDate);
        setDatetimepicker($('#txtToDate'), "Monthly", defDate, minDate);
    } else if (period === "Yearly") {
        todayDate = todayDate.split("-");        
        const minDate = todayDate[0] + "-" + "01" + "-01 24:00:00";
        const defDate = todayDate[0] + "-" + "01" + "-01 00:00:00";

        setDatetimepicker($('#txtFromDate'), "Yearly", defDate);
        setDatetimepicker($('#txtToDate'), "Yearly", defDate, minDate);
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
            data: "TransactionID",
            className: "align-middle",
        },
        {
            data: "TransactionType",
            render: function (value) {
                if (value === "IN") {
                    return "Stok Masuk";
                } else if (value === "OUT") {
                    return "Penjualan";
                } else if (value === "RETUR"){
                    return "Barang Retur";
                } else {
                    return "";
                }
            },
            className: "align-middle",
        },
        {
            data: "TransactionDate",
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
                const format = value.toString().split('').reverse().join('');
                const convert = format.match(/\d{1,3}/g);
                const fixPurchase = convert.join('.').split('').reverse().join('');

                return "Rp " + fixPurchase;
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
                const format = value.toString().split('').reverse().join('');
                const convert = format.match(/\d{1,3}/g);
                const fixSubTotal = convert.join('.').split('').reverse().join('');

                return "Rp " + fixSubTotal;
            },
            className: "align-middle",
        },
        {
            data: "SubTotal",
            render: function (value, type, row) {
                const discount = row["Discount"];
                const total = parseInt(value) - parseInt(discount);
                const format = total.toString().split('').reverse().join('');
                const convert = format.match(/\d{1,3}/g);
                const fixSubTotal = convert.join('.').split('').reverse().join('');

                return "Rp " + fixSubTotal;
            },
            className: "align-middle",
        },
        {
            data: "Remark",
            className: "align-middle",
        },
        {
            data: "CashierName",
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
            filename: "Data Transaksi",
            attr: {
                id: "mainTableExportExcel",
            },
            exportOptions: {
                // columns: ":not(:last-child)",
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
        const ItemCode = $("#ddItemCode").val();
        let FromDate = $("#txtFromDate").val();
        let ToDate = $("#txtToDate").val();
        const Period = $("#ddPeriod").val();
        const TransType = $("#ddReportType").val();

        if (Period === "Daily") {
            FromDate = FromDate.split("-");
            FromDate = FromDate[2] + "-" + FromDate[1] + "-" + FromDate[0] + " 00:00:00";

            ToDate = ToDate.split("-");
            ToDate = ToDate[2] + "-" + ToDate[1] + "-" + ToDate[0] + " 23:59:59";
        } else if (Period === "Monthly") {
            FromDate = FromDate.split("-");
            FromDate = FromDate[1] + "-" + FromDate[0] + "-01" + " 00:00:00";

            ToDate = ToDate.split("-");
            ToDate = ToDate[1] + "-" + ToDate[0] + "-31" + " 23:59:59";
        } else if (Period === "Yearly") {
            FromDate = FromDate + "-01" + "-01" + " 00:00:00";

            ToDate = ToDate + "-12" + "-31" + " 23:59:59";
        }

        const url = "../controller/report/loaddata.php";
        const param = {
            search: Search,
            ItemCode: ItemCode == null ? "" : ItemCode,
            FromDate: FromDate,
            ToDate: ToDate,
            TransType: TransType,
        };

        showLoading();
        mainTable.clear().draw();
        const response = await callAPI(url, "GET", param);

        if (response.success) {
            let currentSubTotal = 0;
            mainTable.rows.add(response.data).draw();
            mainTable
              .column(12)
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

const preventInput = (evnt) => {
    if (evnt.which != 9) evnt.preventDefault();
}

const setDatetimepicker = (el, period, defaultdate, mindate = false) => {

    const defDate = new Date(defaultdate);
    let format = "DD-MM-YYYY";
    let viewMode = "days";

    if (period == "Monthly") {
        format = "MM-YYYY";
        viewMode = "months";
    } else if (period == "Yearly") {
        format = "YYYY";
        viewMode = "years";
    }

    if (mindate != false) {
        mindate = new Date(mindate);
        
        if (period == "Daily") {
            mindate.setDate(mindate.getDate() - 1);
        } else if (period == "Monthly") {
            mindate.setDate(mindate.getDate() - 1);
        } else if (period == "Yearly") {
            mindate.setDate(mindate.getDate() - 1);
        }
    }

    $(el).datetimepicker({
        format: format,
        viewMode: viewMode,
        showTodayButton: true,
        defaultDate: defDate,
        minDate: mindate,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: "fas fa-clock-o",
            date: "fas fa-calendar",
            up: "fas fa-arrow-up",
            down: "fas fa-arrow-down",
            today: "fas fa-map-marker",
            previous: 'fas fa-angle-left',
            next: 'fas fa-angle-right',

        },
    });
};

const stringToDate = (_date, _format, _delimiter, _period = "Daily") => {
    const formatLowerCase = _format.toLowerCase();
    const formatItems = formatLowerCase.split(_delimiter);
    const dateItems = _date.split(_delimiter);
    const monthIndex = formatItems.indexOf("mm");
    const dayIndex = formatItems.indexOf("dd");
    const yearIndex = formatItems.indexOf("yyyy");
    let month = parseInt(dateItems[monthIndex]);
    month -= 1;

    if (_period === "Monthly") {
        const formatedDate = new Date(dateItems[yearIndex], month);
        return formatedDate;
    } else if (_period === "Yearly") {
        const formatedDate = new Date(dateItems[yearIndex]);
        return formatedDate;
    } else {
        const formatedDate = new Date(dateItems[yearIndex], month, dateItems[dayIndex]);
        return formatedDate;
    }
}