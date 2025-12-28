<?php
    session_start();
    include("config.php");
    require_once('seg.php');
    date_default_timezone_set('America/Bogota');

    // Inicialización de variables y control de errores
    $errores = [
        "login" => ""
    ];

    $Ingresar = isset($_POST['Ingresar']) ? $_POST['Ingresar'] : null;
    $cedula   = isset($_POST['cedula'])   ? $_POST['cedula']   : "";
    $klave    = isset($_POST['klave'])    ? $_POST['klave']    : "";
    $ingresoSemana = "";

    // Formatear cédula
    $cedula = formatear($cedula);

    // Proceso de autenticación
    if (isset($Ingresar)) {
        if (!empty($cedula) && !empty($klave)) {
            // Incluimos de nuevo el config solo si realmente lo necesitamos (antipatrón si es doble!)
            include("config.php");

            $stmt = mysqli_prepare($link, "SELECT * FROM responsables WHERE cedula = ?");
            mysqli_stmt_bind_param($stmt, "s", $cedula);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_array($result)) {
                if ($row["clave"] === md5($klave)) {
                    $_SESSION['usuario']  = $cedula;
                    $_SESSION['tipo']     = $row["tipo"];
                    $_SESSION['nombres']  = $row["nombres"];

                    /* if ($row["tipo"] == "3") {
                        $semana = date('W');
                        $horaActual = date('H:i');

                        if ($row["ingresoSemana"] == $semana) {
                            $ingresoSemana = "Bloqueado";
                        } elseif ($horaActual > '12:00') {
                            $sqlIngreso = "UPDATE responsables SET ingresoSemana = '$semana' WHERE cedula = '$cedula'";
                            mysqli_query($link, $sqlIngreso);
                            $ingresoSemana = "RecienBloqueado";
                        }
                    } */

                    $sqlParametros = "SELECT * FROM parametros";
                    $rs_operacionParametros = mysqli_query($link, $sqlParametros);

                    $parametros = [];

                    while ($rowParametros = mysqli_fetch_assoc($rs_operacionParametros)) {
                        $parametros[$rowParametros["descripcion"]] = $rowParametros["status"];
                    }

                    $_SESSION["registrosCambios"] = $parametros["registrosCambios"] ?? 0;

                    if ($ingresoSemana == "") {
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $sql = "INSERT INTO log_usuarios (usuario, accion, fecha, ip) VALUES (?, 'Login', NOW(), ?)";
                        $stmtLog = mysqli_prepare($link, $sql);
                        mysqli_stmt_bind_param($stmtLog, "ss", $cedula, $ip);
                        mysqli_stmt_execute($stmtLog);
                        mysqli_stmt_close($stmtLog);

                        header('Location: inicio.php');
                        exit();
                    }
                } else {
                    $errores["login"] = "<span style='color: #ff0000;'>Clave incorrecta</span>";
                }
            } else {
                $errores["login"] = "<span style='color: #ff0000;'>Usuario no registrado</span>";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errores["login"] = "<span style='color: #ff0000;'>Debe introducir el usuario y la clave</span>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Planta de Desposte | Login</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/logo.png" rel="apple-touch-icon">
    <link href="assets/img/logo.png" rel="icon">

    <!-- Google Fonts & Vendor CSS Files-->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="assets/img/logo-mercamio.png" alt="">
                                    <span class="d-none d-lg-block">Planta de Desposte</span>
                                </a>
                            </div> <!-- End Logo -->

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                                        <p class="text-center small">Ingrese Usuario y Clave</p>
                                    </div>

                                    <form class="row g-3 needs-validation" novalidate method="post" autocomplete="off" action="index.php">

                                        <div class="col-12">
                                            <label for="cedula" class="form-label">Cédula</label>
                                            <div class="input-group has-validation">
                                                <input 
                                                    type="text"
                                                    id="cedula"
                                                    name="cedula"
                                                    class="form-control"
                                                    placeholder="Ingresar Cédula"
                                                    value="<?php echo htmlspecialchars($cedula); ?>"
                                                    required
                                                >
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="klave" class="form-label">Clave</label>
                                            <input 
                                                type="password"
                                                class="form-control"
                                                id="klave"
                                                name="klave"
                                                placeholder="Ingresar Clave"
                                                required
                                            >
                                        </div>

                                        <div class="col-12">
                                            <?php if (!empty($errores["login"])): ?>
                                                <div class="alert alert-danger" role="alert">
                                                    <?php echo $errores["login"]; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit" name="Ingresar" value="Ingresar">
                                                Ingresar
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div> <!-- End Card -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/sweetalert2@11.js"></script>
    <script>
        $(document).ready(function() {
            if ("<?= $ingresoSemana ?>" == "RecienBloqueado") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Acceso bloqueado temporalmente',
                    text: 'Has ingresado esta semana después de la 1:00 PM. Sera bloqueado hasta la próxima semana. El acceso estará disponible nuevamente la próxima semana.',
                    confirmButtonColor: '#3085d6',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    allowClosePropagation: false
                }).then(function() {
                    window.location.href = window.location.pathname;
                });
            } else if ("<?= $ingresoSemana ?>" == "Bloqueado") {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso bloqueado',
                    text: 'Tu acceso semanal ha sido bloqueado, vuelve la próxima semana para ingresar nuevamente.',
                    confirmButtonColor: '#d33',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    allowClosePropagation: false
                }).then(function() {
                    window.location.href = window.location.pathname;
                });
            }
        });
    </script>
</body>
</html>