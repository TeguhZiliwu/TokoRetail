let edit = false;
const currentFormID = "item";
let pictureItemDeletion = [];

$(document).ready(async function () {
    if (await checkAuthorize(currentFormID)) {
        mainTable.buttons().container().appendTo("#tblMainData_wrapper .col-md-6:eq(0)");
        invalidImport.buttons().container().appendTo("#tblInvalidData_wrapper .col-md-6:eq(0)");
        $(".dt-buttons").css("display", "none");
        await loadData();
        await getFormName(currentFormID);
        await getCategory();
        await getUOM();
        formProp(true);
    } else {
        window.location.replace("../errorpage/403");
    }
});

const formProp = (isDisabled) => {
    $(".maintenance-form, #btnSave, #btnCancel, #btnAddNewPicture").prop("disabled", isDisabled);
    $("#btnAdd, #btnImport, #btnExport, #btnTemplate, button[name='editaction'], button[name='deleteaction']").prop("disabled", !isDisabled);
    $("textarea.maintenance-form").css("resize", isDisabled ? "none" : "vertical").css("height", "128px");
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
};

const disabledForm = async () => {
    formProp(true);
};

const enabledForm = async () => {
    formProp(false);
};

const clearForm = () => {
    $("#txtItemCode").val("[AUTO]");
    $(".maintenance-form").val("").trigger("change");
    sellingPriceAutoNum.set("");
    $("#txtItemName, #txtItemDesc, #txtSellingPrice").removeClass("is-invalid");
    $("body").find("[aria-labelledby='select2-ddCategory-container']").removeClass("select2-invalid");
    $("body").find("[aria-labelledby='select2-ddUOM-container']").removeClass("select2-invalid");
    $("body").find("[aria-labelledby='select2-ddItemType-container']").removeClass("select2-invalid");
    pictureTable.clear().draw();
    pictureItemDeletion = [];
};

const validateSubmit = () => {
    let valid = true;
    const validateTitle = "Data tidak valid!";
    const validateDesc = "Silahkan lengkapi data dengan benar!";
    const ItemName = $("#txtItemName");
    const ItemDesc = $("#txtItemDesc");
    const Category = $("#ddCategory");
    const UOM = $("#ddUOM");
    const ItemType = $("#ddItemType");
    const SellingPrice = $("#txtSellingPrice");
    const isItemTypeMandatory = $("label[for='ddItemType'] span").css("display") == "none" ? false : true;

    if (ItemName.val().replace(/\s/g, "") == "" || ItemName.val().length > 100) {
        valid = false;
        ItemName.addClass("is-invalid");
    }

    if (ItemDesc.val().replace(/\s/g, "") == "" || ItemDesc.val().length > 2000) {
        valid = false;
        ItemDesc.addClass("is-invalid");
    }

    if (Category.val() == null || Category.val() == "") {
        valid = false;
        $("body").find("[aria-labelledby='select2-ddCategory-container']").addClass("select2-invalid");
    }

    if (UOM.val() == null || UOM.val() == "") {
        valid = false;
        $("body").find("[aria-labelledby='select2-ddUOM-container']").addClass("select2-invalid");
    }

    if (isItemTypeMandatory) {
        if (ItemType.val() == null || ItemType.val() == "") {
            valid = false;
            $("body").find("[aria-labelledby='select2-ddItemType-container']").addClass("select2-invalid");
        }
    }

    if (SellingPrice.val().replace(/\s/g, "") == "" || SellingPrice.val() == 0) {
        valid = false;
        SellingPrice.addClass("is-invalid");
    }

    if (pictureTable.rows().count() > 0) {
        let hasNoPict = 0;

        pictureTable.column(1).nodes().to$().each(function (index) {
            const fileValue = $(this).find("input[type='file']").val();
            const itempictureid = pictureTable.row($(this).parents('tr')).data().ItemPictureID;
            if (fileValue == "" && itempictureid == "") {
                hasNoPict++;
                $(this).find("input[type='file']").siblings("input.custom-file-group-table").addClass("is-invalid");
            }
        });

        if (hasNoPict > 0) {
            valid = false;
        }
    }

    if (!valid) {
        showNotif(validateDesc, 15000, "warning", "top-end");
    }

    return valid;
};

// Start initialize element

$("#ddCategory").select2({
    placeholder: "Pilih Kategori",
    // allowClear: true,
});

$("#ddUOM").select2({
    placeholder: "Pilih Satuan",
    // allowClear: true,
});

$("#ddItemType").select2({
    placeholder: "Pilih Jenis Barang",
    // allowClear: true,
});

const sellingPriceAutoNum = new AutoNumeric('#txtSellingPrice', {
    decimalPlaces: 0,
    decimalCharacter: ",",
    digitGroupSeparator: ".",
    modifyValueOnWheel: false,
});
const tooltipTemplate = tippy("#btnTemplate", {
    arrow: true,
    hideOnClick: false,
});

// End initialize element

// Start click function

$("#btnAdd").click(async function () {
    $('a[href="#tabs-detail"]').tab("show");
    formProp(false);
    $("#ddItemType").prop("disabled", true);
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
    $("#txtItemCode").val(rowData.ItemCode);
    $("#txtItemName").val(rowData.ItemName);
    $("#txtItemDesc").val(rowData.ItemDesc);
    $("#ddCategory").val(rowData.CategoryCode).trigger("change");
    $("#ddUOM").val(rowData.UOMCode).trigger("change");
    const uom = $("#ddUOM").find("option:selected").text();
    await switchItemType(uom);
    $("#ddItemType").val(rowData.ItemType).trigger("change");
    sellingPriceAutoNum.set(rowData.SellingPrice);
    await getPicture(rowData.ItemCode);

    enabledForm();
    $('a[href="#tabs-detail"]').tab("show");
    // $("#txtItemName, #ddCategory, #ddUOM, #ddItemType").prop("disabled", true);
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

$("#tblMainData").on("click", 'button[name="deleteaction"]', function () {
    const rowData = mainTable.row($(this).parents("tr")).data();
    confirmDelete(rowData.ItemCode);
});

$("#btnExport").click(function () {
    $("#mainTableExportExcel").click();
});

$("#btnTemplate").click(function () {
    const url = "../controller/downloadfile/downloadfile.php?filename=Template Barang.xlsx";
    window.location = url;
});

$("#btnImport").click(function () {
    $("#fileUpload").click();
});

$("#btnInvalidExport").click(function () {
    $("#invalidTableExportExcel").click();
});

$('a[href="#tabs-data"], a[href="#tabs-detail"]').click(function () {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

$("#btnAddNewPicture").click(async function () {
    let newData = [];
    const obj = {
        ItemPictureID: "",
        ItemCode: "",
        PictureName: "",
    }
    newData.push(obj);
    pictureTable.rows.add(newData).draw();
    if (pictureTable.rows().count() == 5) {
        $(this).prop("disabled", true);
    }
});

$("#tblItemPicture").on("click", '.custom-file-group-table, .custom-file-span-table', function () {
    $(this).siblings("input[type='file']").click();
});

$("#tblItemPicture").on("click", 'button[name="deletepictaction"]', function () {
    const rowData = pictureTable.row($(this).parents("tr")).data();

    if (rowData.ItemPictureID != "") {
        const obj = {
            ItemPictureID: rowData.ItemPictureID,
            ItemCode: rowData.ItemCode,
        };
        pictureItemDeletion.push(obj);
    }

    pictureTable.rows($(this).parents('tr')).remove().draw();
    if (pictureTable.rows().count() < 5) {
        $("#btnAddNewPicture").prop("disabled", false);
    }
});

$("#tblItemPicture").on("click", 'button[name="viewpictaction"]', async function () {
    const rowData = pictureTable.row($(this).parents('tr')).data();
    const attrFileLink = $(this).closest('td').parent().find('input[type="file"].custom-file-input-table').attr("filename");
    if (attrFileLink != "" && attrFileLink != undefined && $(this).closest('td').parent().find('input[type="file"]').val() == "") {
        let path = await getGlobalSetting("PathItemPicture");
        path = "../" + path;
        const filename = $(this).closest('td').parent().find('input[type="file"].custom-file-input-table').attr("filename");
        $("#modalViewPict").modal("show");
        $("#imgPreview").attr("src", path + filename);
    } else {
        if ($(this).closest('td').parent().find('input[type="file"]').val() != "") {
            const file = $(this).closest('td').parent().find('input[type="file"]').get(0).files[0];
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
$("#txtItemName").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 100) {
        $(this).removeClass("is-invalid");
    }
});

$("#txtItemDesc").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 2000) {
        $(this).removeClass("is-invalid");
    }
});

$("#ddCategory").change(function () {
    if ($(this).val() != null && $(this).val() != "") {
        $("body").find("[aria-labelledby='select2-ddCategory-container']").removeClass("select2-invalid");
    }
});

$("#ddUOM").change(async function () {
    if ($(this).val() != null && $(this).val() != "") {
        $("body").find("[aria-labelledby='select2-ddUOM-container']").removeClass("select2-invalid");
        const uom = $(this).find("option:selected").text();
        $("#ddItemType").empty();
        switchItemType(uom);
    }
});

$("#ddItemType").change(function () {
    if ($(this).val() != null && $(this).val() != "") {
        $("body").find("[aria-labelledby='select2-ddItemType-container']").removeClass("select2-invalid");
    }
});

$("#txtSellingPrice").change(function () {
    if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val() != 0) {
        $(this).removeClass("is-invalid");
    }
});

$("#fileUpload").change(function () {
    uploadFile(this);
});

$("#tblItemPicture").on("change", '.custom-file-input-table', function () {
    validateImage(this);
});
// End change function

$('#modalViewPict').on('hidden.bs.modal', function () {
    $("#imgPreview").attr("src", "");
});

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
            data: "ItemDesc",
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
            render: function (value) {
                const format = value.toString().split('').reverse().join('');
                const convert = format.match(/\d{1,3}/g);
                const fixSubTotal = convert.join('.').split('').reverse().join('');

                return "Rp " + fixSubTotal;
            },
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
            filename: "Data Barang",
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

const invalidImport = $("#tblInvalidData").DataTable({
    columns: [
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
            data: "ItemDesc",
            className: "align-middle",
        },
        {
            data: "SellingPrice",
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
                let sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('c[r=A1] t', sheet).text('NamaBarang');
                $('c[r=B1] t', sheet).text('Kategori');
                $('c[r=C1] t', sheet).text('Satuan');
                $('c[r=D1] t', sheet).text('JenisBarang');
                $('c[r=E1] t', sheet).text('DeskripsiBarang');
                $('c[r=F1] t', sheet).text('HargaJual');
            }
        }
    ],
    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
});

const pictureTable = $("#tblItemPicture").DataTable({
    columns: [
        {
            data: "ItemPictureID",
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
                inputFile += '</div>';
                return inputFile;
            },
            className: "align-middle",
        },
        {
            data: "",
            render: function (data, type, row, meta) {
                return (
                    '<button type="button" class="btn btn-outline-success waves-effect waves-light btn-sm me-1 action-button" name="viewpictaction"><i class="fas fa-eye" style></i></button>' +
                    '<button type="button" class="btn btn-outline-danger waves-effect waves-light btn-sm action-button" name="deletepictaction"><i class="fas fas fa-trash-alt"></i></button>'
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
        $("td:eq(1)", row).find("input[type='file'].custom-file-input-table").attr("id", "inputFile_" + (displayNum + 1) + "");
    },
    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
});

// Load data
const loadData = async () => {
    try {
        const Search = $("#txtSearch").val();
        const url = "../controller/item/loaddata.php";
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
        const url = "../controller/item/create_update.php";
        const ItemCode = $("#txtItemCode").val();
        const ItemName = $("#txtItemName").val();
        const ItemDesc = $("#txtItemDesc").val();
        const Category = $("#ddCategory").val();
        const UOM = $("#ddUOM").val();
        const ItemType = $("#ddItemType").val();
        const SellingPrice = $("#txtSellingPrice").val();
        const param = {
            ItemCode: ItemCode,
            ItemName: ItemName,
            ItemDesc: ItemDesc,
            Category: Category,
            UOM: UOM,
            ItemType: ItemType,
            SellingPrice: SellingPrice.replace(/\D/g, ""),
            edit: edit === true ? "true" : "false",
        };

        showLoading();
        const response = await callAPI(url, "POST", param);

        if (response.success) {
            showNotif(response.msg, 15000, "success", "top-end");
            await uploadImage(response.itemcode);
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

const confirmDelete = (ItemCode) => {
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
                deleteData(ItemCode);
            } else {
                setTimeout(function () {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                }, 400);
            }
        });
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
};

const deleteData = async (ItemCode) => {
    try {
        const url = "../controller/item/delete.php";
        const param = {
            ItemCode: ItemCode,
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

const getCategory = async () => {
    try {
        const url = "../controller/category/fetch_data.php";
        const param = {
            FetchData: "getCategory"
        };

        const response = await callAPI(url, "GET", param);

        if (response.success) {
            let option = "";

            for (let i = 0; i < response.data.length; i++) {
                option += '<option value="' + response.data[i].CategoryCode + '">' + response.data[i].Category + "</option>";
            }
            $("#ddCategory").html(option).val("").trigger("change");
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

const getUOM = async () => {
    try {
        const url = "../controller/uom/fetch_data.php";
        const param = {
            FetchData: "getUOM"
        };

        const response = await callAPI(url, "GET", param);

        if (response.success) {
            let option = "";

            for (let i = 0; i < response.data.length; i++) {
                option += '<option value="' + response.data[i].UOMCode + '">' + response.data[i].UOM + "</option>";
            }
            $("#ddUOM").html(option).val("").trigger("change");
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

const getItemType = (data = "") => {
    try {
        if (data != "") {
            const globalValue = data.split(",");
            let option = "";

            for (let i = 0; i < globalValue.length; i++) {
                option += '<option value="' + globalValue[i] + '">' + globalValue[i] + "</option>";
            }
            $("#ddItemType").html(option).val("").trigger("change");
        }
    } catch (error) {
        showNotif(error, 15000, "error", "top-end");
    }
};

const switchItemType = async (uom) => {

    switch (uom) {
        case "KG":
            globalValue = await getGlobalSetting("ItemTypeUOMKG");
            getItemType(globalValue);
            $("label[for='ddItemType'] span").css("display", "");
            $("#ddItemType").prop("disabled", false);
            break;

        case "SAK":
            globalValue = await getGlobalSetting("ItemTypeUOMSAK");
            getItemType(globalValue);
            $("label[for='ddItemType'] span").css("display", "");
            $("#ddItemType").prop("disabled", false);
            break;

        default:
            $("body").find("[aria-labelledby='select2-ddItemType-container']").removeClass("select2-invalid");
            $("label[for='ddItemType'] span").css("display", "none");
            $("#ddItemType").prop("disabled", true);
            break;
    }
};

const uploadFile = async (thisfile) => {
    try {
        if (fileSelected(thisfile)) {
            if (fileIsExcel(thisfile)) {
                const files = $(thisfile)[0].files;
                const url = "../controller/item/import.php";

                let fileData = new FormData();
                for (var i = 0; i < files.length; i++) {
                    fileData.append("fileUpload", files[i]);
                }

                showLoading();
                const response = await callAPI(url, "POST", fileData, true);

                if (response.success && response.datainvalid.length == 0) {
                    showNotif(response.msg, 15000, "success", "top-end");
                    await loadData();
                } else {
                    if (response.datainvalid.length > 0) {
                        $('#modalInvalidUpload').on('shown.bs.modal', function (e) {
                            $.fn.dataTable.tables({
                                visible: true,
                                api: true
                            }).columns.adjust();
                        });
                        $("#totalInvalidImport").text(response.totalinvalid);
                        $("#totalSuccessImport").text(response.totalsuccess);
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

                    if (response.datainvalid.length > 0) {
                        await loadData();
                    }
                    hideLoading();
                }
            } else {
                showNotif("Silakan pilih hanya file Excel.", 15000, "warning", "top-end");
            }
        } else {
            showNotif("Tidak ada file yang dipilih.", 15000, "warning", "top-end");
        }
        $("#fileUpload").val("");
    } catch (error) {
        console.log(error)
        showNotif(error, 15000, "error", "top-end");
    }
};

const validateImage = async (thisfile) => {
    try {
        if (fileSelected(thisfile)) {
            if (fileIsPicture(thisfile)) {
                const tr = $(thisfile).closest('tr');
                const rowData = pictureTable.row(tr).data();
                const listImage = [];
                const files = $(thisfile)[0].files;
                const fileName = files[0].name;
                const currentID = $(thisfile).attr("id");
                let hasSameVal = false;

                pictureTable.column(1).nodes().to$().each(function (index) {
                    const valFiles = $(this).find("input[type='file'].custom-file-input-table").val();
                    if (valFiles != "") {
                        const name = $(this).find("input[type='file'].custom-file-input-table")[0].files[0].name;
                        const ID = $(this).find("input[type='file'].custom-file-input-table").attr("id");
                        const obj = {
                            ID: ID,
                            Name: name
                        }
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

                    if (rowData.ItemPictureID != "") {
                        const obj = {
                            ItemPictureID: rowData.ItemPictureID,
                            ItemCode: rowData.ItemCode,
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
        console.log(error)
        showNotif(error, 15000, "error", "top-end");
    }
};

const uploadImage = async (itemcode) => {
    try {
        const url = "../controller/item/uploadimage.php";
        let fileData = new FormData();
        fileData.append("ItemCode", itemcode);
        fileData.append("pictureItemDeletion", JSON.stringify(pictureItemDeletion));
        let hasFileToUpload = "no";

        pictureTable.column(1).nodes().to$().each(function (index) {
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
        console.log(error)
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
}

// Validate file extension
const fileIsExcel = (thisfile) => {
    let file = $(thisfile);
    let ext = file.val().substring(file.val().lastIndexOf('.') + 1);

    if (ext.toUpperCase() == "XLS" || ext.toUpperCase() == "XLSX") {
        return true;
    } else {
        return false;
    }
}

const fileIsPicture = (thisfile) => {
    let file = $(thisfile);
    let ext = file.val().substring(file.val().lastIndexOf('.') + 1);

    if (ext.toUpperCase() == "PNG" || ext.toUpperCase() == "JPG" || ext.toUpperCase() == "JPEG") {
        return true;
    } else {
        return false;
    }
}

const getPicture = async (itemcode) => {
    try {
        const url = "../controller/item/fetch_data.php";
        const param = {
            FetchData: "getPictureData",
            ItemCode: itemcode,
        };

        showLoading();
        pictureTable.clear().draw();
        const response = await callAPI(url, "GET", param);

        if (response.success) {
            pictureTable.rows.add(response.data).draw();
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