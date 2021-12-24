<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include '../config/appname.php';
    session_name($encryptedAppName);
    session_start();

    if (!empty($_SESSION['userid'])) {
        header("location:../pages/dashboard");
    }
    ?>
    <meta charset="utf-8" />
    <title>Log In | Toko Berkat Tani & Ternak</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Usaha toko retail pertanian dan peternakan berbasis web" name="description" />
    <meta content="TeguhZiliwu" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- App css -->
    <link href="../assets/css/config/default/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="../assets/css/config/default/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    <link href="../assets/css/config/default/bootstrap-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
    <link href="../assets/css/config/default/app-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />

    <!-- icons -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert-->
    <link href="../assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

</head>

<body class="loading authentication-bg authentication-bg-pattern">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto mb-4">
                                <div class="auth-logo">
                                    <a href="../login" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="../assets/images/logo-sm.png" alt="" height="22">
                                        </span>
                                    </a>

                                    <a href="../login" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="../assets/images/logo-sm.png" alt="" height="22">
                                        </span>
                                    </a>
                                    <h4>Toko Berkat Tani & Ternak</h4>
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="txtUserID" class="form-label">User ID</label>
                                <input class="form-control" type="text" id="txtUserID" placeholder="Masukkan User ID">
                            </div>

                            <div class="mb-3">
                                <label for="txtPassword" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="txtPassword" class="form-control" placeholder="Masukkan Password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center d-grid">
                                <button class="btn btn-primary" id="btnLogin"> Log In </button>
                            </div>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Vendor js -->
    <script src="../assets/js/vendor.min.js"></script>

    <!-- Sweet Alerts js -->
    <script src="../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- App js -->
    <script src="../assets/js/app.js"></script>

    <script>
        $("#btnLogin").click(async function() {
            if (validateLogin()) {
                const loadingbutton = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="spanLoading"></span> Loading...';
                $(this).text("").append(loadingbutton).prop("disabled", true);
                await Login();
            }
        });

        $("#txtUserID, #txtPassword").change(function() {
            if ($(this).val().replace(/\s/g, "") != "") {
                $(this).removeClass("is-invalid");
            }
        });

        $("#txtPassword").keypress(function(e) {
            const key = e.which;
            if (key == 13) {
                $("#btnLogin").click();
            }
        });

        const validateLogin = () => {
            let valid = true;
            const validateDesc = "Silahkan isi User ID dan Password!";
            const UserID = $("#txtUserID");
            const Password = $("#txtPassword");

            if (UserID.val().replace(/\s/g, "") == "") {
                valid = false;
                UserID.addClass("is-invalid");
            }

            if (Password.val().replace(/\s/g, "") == "") {
                valid = false;
                Password.addClass("is-invalid");
            }

            if (!valid) {
                showNotif(validateDesc, 15000, "warning", "top");
            }

            return valid;
        }

        const Login = async () => {
            try {
                const url = "../controller/user/login.php";
                const UserID = $("#txtUserID").val();
                const Password = $("#txtPassword").val();
                const param = {
                    UserID: UserID,
                    Password: Password
                };

                const response = await callAPI(url, "POST", param);
                
                if (response.success) {
                    showNotif(response.msg, 5000, "success", "top");
                    window.setTimeout(function() {
                        window.location.replace("../pages/dashboard");
                    }, 5000);
                } else {
                    if (response.msg.includes("[ERROR]")) {
                        response.msg = response.msg.replace("[ERROR] ", "");
                        showNotif(response.msg, 15000, "error", "top");
                    } else {
                        showNotif(response.msg, 15000, "warning", "top");
                    }
                    $("#txtPassword").val("");
                    $("#btnLogin").text("Login").prop("disabled", false);
                }
            } catch (error) {
                showNotif(error, 15000, "error", "top");
            }

        }

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
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: notiftype,
                title: message
            });
        };

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

                if (result.ok) {
                    return result.json();
                }

                throw await response.text();
            } catch (error) {
                throw error;
            }
        };
    </script>

</body>

</html>