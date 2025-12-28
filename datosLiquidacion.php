<?php
    session_start();
    if (!isset($_SESSION["usuario"])) {
        header("Location: cerrarsesion.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Liquidación</title>
    <?php include "encabezado.php"; ?> 
    <style>
       /*  #jtable td {
            padding: 0rem 0.5rem 0rem 0.5rem;
        }

        #jtable td:nth-child(3) {
            padding: 0.3rem 0.5rem 0rem 0.5rem;
        }

        #TablaRecepcion_wrapper .row:nth-child(1) .col-sm-6:nth-child(2) {
            display: none !important;
        }

        #TablaDesprese_wrapper  .row:nth-child(1) .col-sm-6:nth-child(2) {
            display: none !important;
        } */
    </style>
</head>
<body>
    <?php include "menu.php"; ?> 
    <?php include "menuizquierda.php"; ?> 

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Datos de Liquidación</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Configuración de Liquidación</h5>
                            
                            <form id="formLiquidacion">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="costoFacturaPollo" class="form-label">Costo Factura Pollo</label>
                                        <input type="text" class="form-control" id="costoFacturaPollo" name="costoFacturaPollo" pattern="[0-9]+(\.[0-9]+)?" placeholder="0.00" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="costoPromedioSalmuera" class="form-label">Costo Promedio Salmuera</label>
                                        <input type="text" class="form-control" id="costoPromedioSalmuera" name="costoPromedioSalmuera" pattern="[0-9]+(\.[0-9]+)?" placeholder="0.00" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="porcentajeSalmuera" class="form-label">% Salmuera</label>
                                        <input type="text" class="form-control" id="porcentajeSalmuera" name="porcentajeSalmuera" pattern="[0-9]+(\.[0-9]+)?" placeholder="0.00" required>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    <button type="button" id="reset" class="btn btn-secondary ms-2">Restablecer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include "scripts.php"; ?> 
    
    <script>
        $(document).ready(function () {
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    traerDatosLiquidacion: true,
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        var datos = response.datos;
                        $("#costoFacturaPollo").val(datos.costoFactura);
                        $("#costoPromedioSalmuera").val(datos.costoPromedioSalmuera);
                        $("#porcentajeSalmuera").val(datos.porcentajeSalmuera);
                    } else if (response.status == "warning") {
                        console.log(response.message);
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "error al traer los datos de liquidación",
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: true
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "Error",
                        text: "error al traer los datos de liquidación",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: true
                    });
                }
            });
        });

        $("#formLiquidacion").submit(function (e) { 
            e.preventDefault();
            if ($("#costoFacturaPollo").val() == "" || $("#costoFacturaPollo").val() <= 0) {
                $("#costoFacturaPollo").addClass("is-invalid");
                $("#costoFacturaPollo").focus();
                return;
            }

            if ($("#costoPromedioSalmuera").val() == "" || $("#costoPromedioSalmuera").val() <= 0) {
                $("#costoPromedioSalmuera").addClass("is-invalid");
                $("#costoPromedioSalmuera").focus();
                return;
            }

            if ($("#porcentajeSalmuera").val() == "" || $("#porcentajeSalmuera").val() <= 0 || $("#porcentajeSalmuera").val() > 100) {
                $("#porcentajeSalmuera").addClass("is-invalid");
                $("#porcentajeSalmuera").focus();
                return;
            }

            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    guardarDatosLiquidacion: $("#formLiquidacion").serializeArray(),
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        Swal.fire({
                            title: "Datos guardados correctamente",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $("#costoFacturaPollo").removeClass("is-invalid is-valid");
                        $("#costoPromedioSalmuera").removeClass("is-invalid is-valid");
                        $("#porcentajeSalmuera").removeClass("is-invalid is-valid");
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "error al guardar los datos",
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: true
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "Error",
                        text: "error al guardar los datos",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: true
                    });
                }
            });
        });

        $("#reset").click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    resetDatosLiquidacion: true,
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        $("#costoFacturaPollo").val("0");
                        $("#costoPromedioSalmuera").val("0");
                        $("#porcentajeSalmuera").val("0");
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "error al restablecer los datos",
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: true
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "Error",
                        text: "error al restablecer los datos",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: true
                    });
                }
            });
        });
    </script>
</body>
</html>