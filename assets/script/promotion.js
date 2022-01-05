$(document).ready(async function () {
  await getPromotionSlide();
  await getCategory();
  await getItem();
  let getContactAdmin = await getGlobalSetting("ContactAdmin", true); 
  $("#contact").html('<i class="fab fa-whatsapp"></i> ' + getContactAdmin);
  $(".promo-text-contact").html('<i class="fab fa-whatsapp"></i> ' + getContactAdmin);
});

$("#btnPromoSearch").click(async function () {
  await getItem();
});

$("#txtSearch").keypress(function (e) {
  const key = e.which;
  if (key == 13) {
    $("#btnPromoSearch").click();
  }
});

$("#contact").click(async function () {
  let getContactAdmin = await getGlobalSetting("ContactAdmin", true);
  getContactAdmin = getContactAdmin.substring(1);
  window.open("https://wa.me/" + getContactAdmin + "?text=", "_blank");
});

$("body").on("click", ".promo-text-contact", async function () {
  let getContactAdmin = await getGlobalSetting("ContactAdmin", true);
  getContactAdmin = getContactAdmin.substring(1);
  console.log($(this).parents(".card-body").find(".promo-text-itemname"))
  const getItemName = $(this).parents(".card").find(".promo-text-itemname").text();
  let msg = "Nama Barang : " + getItemName;
  window.open("https://wa.me/" + getContactAdmin + "?text=" + encodeURIComponent(msg), "_blank");
});

$("body").on("click", ".promo-text-desc", function () {
  const text = $(this).parents(".row.align-items-center").find("p.sub-header").text();
  $("#textDesc").html(text);
  $("#modalDesc").modal("show");
});

const getPromotionSlide = async () => {
  try {
    let fileList = await getGlobalSetting("SlidePromotionPage", true);
    let getPath = await getGlobalSetting("PathPromotionPicture", true);
    console.log(getPath)
    fileList = fileList.split("|");
    for (let i = 0; i < fileList.length; i++) {
      const path = "../" + getPath + fileList[i];
      const active = i === 0 ? "active" : "";
      let item = '<div class="carousel-item promotion-page ' + active + '">';
      item += '<img class="d-block img-fluid" src="' + path + '">';
      item += "</div>";
      const indicator = '<li data-bs-target="#promoSlide" data-bs-slide-to="' + i + '" class="' + active + '"></li>';
      $("#listBoxPromotion").append(item);
      $("#listIndicator").append(indicator);
    }
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const getItem = async () => {
  try {
    const Search = $("#txtSearch").val();
    const Category = $("#ddCategory").val();
    const url = "../controller/promotion/fetch_data.php";
    const param = {
      FetchData: "getItem",
      Search: Search,
      Category: Category == null ? "" : Category,
    };

    showLoading();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      $("#rowItem").empty();
      const data = response.data;
      if (data.length > 0) {
        for (let i = 0; i < data.length; i++) {
          const price = data[i].SellingPrice;
          const format = price.toString().split("").reverse().join("");
          const convert = format.match(/\d{1,3}/g);
          const fixPrice = convert.join(".").split("").reverse().join("");
          const itemName = data[i].ItemName;
          const itemDesc = data[i].ItemDesc;
          const itemCode = data[i].ItemCode;
          const itemPicList = await getImagePic(data[i].ItemCode);
          const active = i === 0 ? "active" : "";
          let listIndicator = "";
          let item = "";

          if (itemPicList.length > 0) {
            for (let i = 0; i < itemPicList.length; i++) {
              let getPath = await getGlobalSetting("PathItemPicture", true);
              const fileName = itemPicList[i].PictureName;
              const path = "../" + getPath + fileName;
              const active = i === 0 ? "active" : "";

              item += '<div class="carousel-item promotion-page ' + active + '">';
              item += '<img class="d-block img-fluid" src="' + path + '">';
              item += "</div>";

              listIndicator += '<li data-bs-target="#slide_' + itemCode + '" data-bs-slide-to="' + i + '" class="' + active + '"></li>';
            }
          } else {
            item = '<div class="carousel-item promotion-page active">';
            item += '<img class="d-block img-fluid" src="../file/no_image.png">';
            item += "</div>";
          }

          let obj = "";
          obj += '<div class="col-md-6 col-xl-3">';
          obj += '<div class="card product-box">';
          obj += '<div class="card-body">';
          obj += '<div class="bg-light">';
          obj += '<div class="row">';
          obj += '<div class="col-12">';
          obj += '<div id="slide_' + itemCode + '" class="carousel slide" data-bs-ride="carousel">';
          obj += '<ol class="carousel-indicators" id="listIndicator' + itemCode + '">';
          obj += listIndicator;
          obj += "</ol>";
          obj += '<div class="carousel-inner" role="listbox" id="listBox' + itemCode + '">';
          obj += item;
          obj += "</div>";
          obj += '<a class="carousel-control-prev" href="#slide_' + itemCode + '" role="button" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></a>';
          obj += '<a class="carousel-control-next" href="#slide_' + itemCode + '" role="button" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></a>';
          obj += "</div>";
          obj += "</div>";
          obj += "</div>";
          obj += "</div>";
          obj += '<div class="product-info">';
          obj += '<div class="row align-items-center">';
          obj += '<div class="col">';
          obj += '<h5 class="font-16 mt-0 sp-line-1"><a class="text-dark promo-text-itemname">' + itemName + "</a> </h5>";
          obj += '<p class="font-16 mt-0 sp-line-1 sub-header"> ' + itemDesc + " </p>";
          obj += "<div class='row'>";
          obj += "<div class='col-6'>";
          obj += '<h5 class="m-0"> <span class="text-muted">Rp ' + fixPrice + "</span></h5>";
          obj += "</div>";
          obj += "<div class='col-6'>";
          obj += '<a class="m-0 float-end promo-text-desc" href="javascript:void(0);" style="color: blue; text-decoration: underline;"> Lihat Deskripsi</a>';
          obj += "</div>";
          obj += "</div>";
          obj += "<div class='row mt-3'>";
          obj += "<div class='col-12'>";
          obj += '<a class="m-0 float-start promo-text-contact text-success" href="javascript:void(0);"></a>';
          obj += "</div>";
          obj += "</div>";
          obj += "</div>";
          obj += "</div>";
          obj += "</div>";
          obj += "</div>";
          obj += "</div>";
          obj += "</div>";
          $("#rowItem").append(obj);
        }
      } else {
        let obj = "";
        obj += '<div class="col-12">';
        obj += '<div class="card">';
        obj += '<div class="card-body text-center">';
        obj += "<label>Data yang dicari tidak ditemukan</label>";
        obj += "</div>";
        obj += "</div>";
        obj += "</div>";
        $("#rowItem").append(obj);
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
    showNotif(error, 15000, "error", "top-end");
  }
};

const getCategory = async () => {
  try {
    const url = "../controller/promotion/fetch_data.php";
    const param = {
      FetchData: "getCategory",
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

const getImagePic = async (itemcode) => {
  try {
    const url = "../controller/promotion/fetch_data.php";
    const param = {
      FetchData: "getImageItem",
      ItemCode: itemcode,
    };

    showLoading();
    const response = await callAPI(url, "GET", param);
    let data = [];

    if (response.success) {
      data = response.data;
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

    return data;
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

$("#ddCategory").select2({
  placeholder: "Pilih Kategori",
  allowClear: true,
});

const callAPI = async (url, method, params = {}, uploadfile = false) => {
  try {
    let options = {
      method,
    };

    if (method === "GET") {
      url += "?" + new URLSearchParams(params).toString();
    } else {
      options.body = uploadfile === false ? JSON.stringify(params) : params;
      if (uploadfile == false) {
        options.headers = {
          Accept: "application/json",
          "Content-Type": "application/json",
        };
      }
    }

    const result = await fetch(url, options);
    //             const restult2 = await fetch(url, options).then((response) => {
    //     return response.text();
    //   })
    //   .then((data) => {
    //       console.log(data)
    //   });

    if (result.ok) {
      return result.json();
    }

    throw await response.text();
  } catch (error) {
    throw error;
  }
};

const showNotif = (message, timer, notiftype, position) => {
  const Toast = Swal.mixin({
    toast: true,
    position: position,
    showConfirmButton: false,
    timer: timer,
    backdrop: false,
    timerProgressBar: true,
    showCloseButton: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: notiftype,
    title: message,
  });
};

const showLoading = async () => {
  $(".backdrop-loading").css("display", "block");
};

const hideLoading = async () => {
  $(".backdrop-loading").css("display", "none");
};

const getGlobalSetting = async (settingid, ispromotionpage = false) => {
  let globalValue = "";
  try {
    const url = "../controller/globalsetting/fetch_data.php";
    const param = {
      FetchData: "getGlobalValue",
      SettingID: settingid,
      isPromotionPage: "true"
    };

    showLoading();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      globalValue = response.data[0].SettingValue;
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
    return globalValue;
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
    return globalValue;
  }
};
