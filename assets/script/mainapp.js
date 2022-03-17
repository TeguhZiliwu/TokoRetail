$(document).ready(async function () {
  await getMenu();
});

$(document).on("select2:open", () => {
  document.querySelector(".select2-search__field").focus();
});

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

$("#btnLogout").click(function () {
  window.location.href = "../controller/user/logout";
});

$("#btnChangePassword").click(function () {
  $("#modalChangePassword").modal("show");
});

$("#modalChangePassword").on("hidden.bs.modal", function () {
  $("#txtOldPassword, #txtNewPassword, #txtConfirmNewPassword").val("");
  $("#txtOldPassword, #txtNewPassword, #txtConfirmNewPassword").removeClass("is-invalid");
});

$("#btnSaveNewPassword").click(async function () {
  if (validateSavePassword()) {
    await changeNewPassword();
  }
});

$("#txtOldPassword").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 200) {
    $(this).removeClass("is-invalid");
  }
});

$("#txtNewPassword").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 200) {
    $(this).removeClass("is-invalid");

    if ($("#txtConfirmNewPassword").val().replace(/\s/g, "") != "") {
      $("#txtConfirmNewPassword").removeClass("is-invalid");
    }
  }
});

$("#txtConfirmNewPassword").change(function () {
  if ($(this).val().replace(/\s/g, "") != "" && $(this).val().length != 0 && $(this).val().length <= 200) {
    $(this).removeClass("is-invalid");

    if ($("#txtNewPassword").val().replace(/\s/g, "") != "") {
      $("#txtNewPassword").removeClass("is-invalid");
    }
  }
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

    throw await result.text();
  } catch (error) {
    throw error;
  }
};

const checkAuthorize = async (formid) => {
  try {
    const url = "../controller/groupaccess/authorize.php";
    let haveauthorize = false;
    const param = {
      FormID: formid,
    };

    const response = await callAPI(url, "GET", param);

    if (response.success) {
      haveauthorize = response.data === "allowed" ? true : false;
    }

    return haveauthorize;
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};

const getMenu = async () => {
  try {
    const url = "../controller/groupaccess/fetch_data.php";
    const param = {
      FetchData: "getMenuList",
    };

    showLoading();
    const response = await callAPI(url, "GET", param);

    if (response.success) {
      $("#side-menu").empty();
      const data = response.data;
      let menuMaster = [...new Set(data.map((item) => item.FormType))];
      menuMaster = menuMaster.filter((e) => e);
      menuMaster.sort();

      const homeDashboard = '<li><a href="dashboard"><i data-feather="airplay"></i><span> Dashboard </span></a></li>';
      $("#side-menu").append('<li class="menu-title">Home</li>');
      $("#side-menu").append(homeDashboard);
      $("#side-menu").append('<li class="menu-title mt-2">Menu</li>');

      for (let i = 0; i < menuMaster.length; i++) {
        let menuMasterDisplay = "";
        let iconMasterDisplay = "";

        switch (menuMaster[i]) {
          case "Master Data":
            iconMasterDisplay = '<i data-feather="database"></i>';
            break;
          case "Pengaturan":
            iconMasterDisplay = '<i data-feather="settings"></i>';
            break;

          default:
            break;
        }

        menuMasterDisplay += "<li>";
        menuMasterDisplay += '<a href="#sidebar' + menuMaster[i].replaceAll(/\s/g, "") + '" data-bs-toggle="collapse">';
        menuMasterDisplay += iconMasterDisplay;
        menuMasterDisplay += "<span>" + menuMaster[i] + "</span>";
        menuMasterDisplay += '<span class="menu-arrow"></span>';
        menuMasterDisplay += "</a>";
        menuMasterDisplay += '<div class="collapse" id="sidebar' + menuMaster[i].replaceAll(/\s/g, "") + '"><ul class="nav-second-level"></ul</div>';
        menuMasterDisplay += "</li>";
        $("#side-menu").append(menuMasterDisplay);
      }

      for (let i = 0; i < data.length; i++) {
        let menuList = "";
        if (data[i].FormType != "") {
          menuList = '<li><a href="' + data[i].FormID + '">' + data[i].FormName + "</a></li>";
          $("#sidebar" + data[i].FormType.replaceAll(/\s/g, "") + " ul").append(menuList);
        } else {
          let iconMenuList = '<i data-feather="bar-chart-2"></i>';

          if (data[i].FormID != "errorlog" && data[i].FormID != "report" && data[i].FormID != "gainloss") {
            menuList = '<li><a href="' + data[i].FormID + '">' + iconMenuList + "<span>" + data[i].FormName + "</span></a></li>";
            $("#side-menu").append(menuList);
          }
        }
      }

      if (data.some((data) => data.FormID === "report")) {
        const objIndex = data.findIndex((data) => data.FormID === "report");
        let iconMenuList = '<i data-feather="file-text"></i>';
        menuList = '<li><a href="' + data[objIndex].FormID + '">' + iconMenuList + "<span>" + data[objIndex].FormName + "</span></a></li>";
        $("#side-menu").append(menuList);
      }

      if (data.some((data) => data.FormID === "gainloss")) {
        const objIndex = data.findIndex((data) => data.FormID === "gainloss");
        let iconMenuList = '<i data-feather="dollar-sign"></i>';
        menuList = '<li><a href="' + data[objIndex].FormID + '">' + iconMenuList + "<span>" + data[objIndex].FormName + "</span></a></li>";
        $("#side-menu").append(menuList);
      }

      if (data.some((data) => data.FormID === "errorlog")) {
        const objIndex = data.findIndex((data) => data.FormID === "errorlog");
        let iconMenuList = '<i data-feather="alert-octagon"></i>';
        menuList = '<li><a href="' + data[objIndex].FormID + '">' + iconMenuList + "<span>" + data[objIndex].FormName + "</span></a></li>";
        $("#side-menu").append(menuList);
      }

      feather.replace();
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
    menuHighlight();
  } catch (error) {
    console.log(error);
    showNotif(error, 15000, "error", "top-end");
  }
};

const menuHighlight = () => {
  $("#side-menu a").each(function () {
    let pageUrl = window.location.href.split(/[?#]/)[0];
    pageUrl = pageUrl.replace(/\.[^/.]+$/, "");

    if (this.href == pageUrl) {
      $(this).addClass("active");
      $(this).parent().addClass("menuitem-active");
      $(this).parent().parent().parent().addClass("show");
      $(this).parent().parent().parent().parent().addClass("menuitem-active"); // add active to li of the current link

      var firstLevelParent = $(this).parent().parent().parent().parent().parent().parent();
      if (firstLevelParent.attr("id") !== "sidebar-menu") firstLevelParent.addClass("show");

      $(this).parent().parent().parent().parent().parent().parent().parent().addClass("menuitem-active");

      var secondLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
      if (secondLevelParent.attr("id") !== "wrapper") secondLevelParent.addClass("show");

      var upperLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
      if (!upperLevelParent.is("body")) upperLevelParent.addClass("menuitem-active");
    }
  });
};

const getFormName = async (currentFormID) => {
  try {
    const url = "../controller/form/fetch_data.php";
    const param = {
      FetchData: "getFormName",
      FormID: currentFormID,
    };

    showLoading();

    const response = await callAPI(url, "GET", param);

    if (response.success) {
      $("h4.page-title").html(response.data[0].FormName);
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

const getGlobalSetting = async (settingid, ispromotionpage = false) => {
  let globalValue = "";
  try {
    const url = "../controller/globalsetting/fetch_data.php";
    const param = {
      FetchData: "getGlobalValue",
      SettingID: settingid,
      isPromotionPage: ispromotionpage ? "true" : "false"
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

const validateSavePassword = () => {
  let valid = true;
  const validateTitle = "Data tidak valid!";
  let validateDesc = "Silahkan lengkapi data dengan benar!";
  const OldPassword = $("#txtOldPassword");
  const NewPassword = $("#txtNewPassword");
  const ConfirmPassword = $("#txtConfirmNewPassword");

  if (OldPassword.val().replace(/\s/g, "") == "" || OldPassword.val().length > 200) {
    valid = false;
    OldPassword.addClass("is-invalid");
  }

  if (NewPassword.val().replace(/\s/g, "") == "" || NewPassword.val().length > 200) {
    valid = false;
    NewPassword.addClass("is-invalid");
  }

  if (ConfirmPassword.val().replace(/\s/g, "") == "" || ConfirmPassword.val().length > 200) {
    valid = false;
    ConfirmPassword.addClass("is-invalid");
  }

  if (NewPassword.val() != "" && ConfirmPassword.val() != "" && valid) {
    if (NewPassword.val() != ConfirmPassword.val()) {
      valid = false;
      NewPassword.addClass("is-invalid");
      ConfirmPassword.addClass("is-invalid");
      validateDesc = "Password tidak sesuai.";
    }
  }

  if (!valid) {
    showNotif(validateDesc, 15000, "warning", "top-end");
  }

  return valid;
};

const changeNewPassword = async () => {
  try {
    const url = "../controller/user/change_password.php";
    const OldPassword = $("#txtOldPassword").val();
    const NewPassword = $("#txtNewPassword").val();
    const ConfirmPassword = $("#txtConfirmNewPassword").val();
    const param = {
      OldPassword: OldPassword,
      NewPassword: NewPassword,
      ConfirmPassword: ConfirmPassword,
    };

    showLoading();
    const response = await callAPI(url, "POST", param);

    if (response.success) {
      showNotif(response.msg, 15000, "success", "top-end");
      $("#modalChangePassword").modal("hide");
    } else {
      if (response.msg.includes("[ERROR]")) {
        response.msg = response.msg.replace("[ERROR] ", "");
        showNotif(response.msg, 15000, "error", "top-end");
      } else {
        showNotif(response.msg, 15000, "warning", "top-end");

        if (response.msg == "Password lama tidak sesuai!") {
            $("#txtOldPassword").addClass("is-invalid");
        } else if (response.msg == "Password baru tidak sesuai!") {
            $("#txtNewPassword, #txtConfirmNewPassword").addClass("is-invalid");
        }
      }
    }
    hideLoading();
  } catch (error) {
    showNotif(error, 15000, "error", "top-end");
  }
};
