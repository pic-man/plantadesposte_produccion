<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
error_reporting(0);
date_default_timezone_set("America/bogota");

include("modelo/funciones.php");
$municipios = listaMunicipios();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planta de Desposte | Destinos</title>
    <?php include_once('encabezado.php');?>
    <style>
        #TDestinos th:nth-child(1), #TDestinos td:nth-child(1) {
            width: 18%; /* Ancho de la quinta columna */
        }
        #TDestinos th:nth-child(2), #TDestinos td:nth-child(2) {
            width: 18%; /* Ancho de la quinta columna */
        }
        #TDestinos th:nth-child(3), #TDestinos td:nth-child(3) {
            width: 18%; /* Ancho de la quinta columna */
        }
        #TDestinos th:nth-child(4), #TDestinos td:nth-child(4) {
            width: 19%; /* Ancho de la quinta columna */
        }
        #TDestinos th:nth-child(5), #TDestinos td:nth-child(5) {
            width: 19%; /* Ancho de la quinta columna */
        }
        #TDestinos th:nth-child(6), #TDestinos td:nth-child(6) {
            width: 8%; /* Ancho de la quinta columna */
        }
    </style>
</head>
<body>
    <?php include_once('menu.php');?>
    <?php include_once('menuizquierda.php');?>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Administrar Destinos</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <center>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalDestinos" title="Agregar Nuevo Destino" id="AddDestino">Agregar Destino</a>
                            </center><br>
                            <table id="TDestinos" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Sede</th>
                                        <th>Direccion</th>
                                        <th>Municipio</th>
                                        <th>status</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="modal fade" id="modalDestinos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header text-center">
                    <label for="CerrarModal" class="w-100" style="font-weight: bold;" id="titulo">Agregar Destino</label>
                    <button type="button" class="btn-close" id="CerrarModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
				<div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="empresa" class="form-label">Empresa</label>
                            <input type="text" class="form-control" id="empresa" name="empresa" placeholder="ingrese una empresa">
                            <div class="alert alert-danger" id="errorEmpresa" style="display: none;">
                                <strong>Error:</strong> El campo Empresa es obligatorio.
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="sede" class="form-label">Sede</label>
                            <input type="text" class="form-control" id="sede" name="sede" placeholder="ingrese una sede">
                            <div class="alert alert-danger" id="errorSede" style="display: none;">
                                <strong>Error:</strong> El campo Sede es obligatorio.
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="direccion" class="form-label">Direccion</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="ingrese una direccion">
                            <div class="alert alert-danger" id="errorDireccion" style="display: none;">
                                <strong>Error:</strong> El campo Direccion es obligatorio.
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="municipio" class="form-label">Municipio</label>
                            <select class="form-select" name="municipio" id="municipio">
                                <option value="0">Seleccione un municipio</option>
                                <?php foreach ($municipios as $municipio): ?>
                                    <option value="<?= $municipio["descripcion"] ?>"><?= $municipio["descripcion"] ?></option>
                                <?php  endforeach; ?>
                            </select>
                            <div class="alert alert-danger" id="errorMunicipio" style="display: none;">
                                <strong>Error:</strong> El campo Municipio es obligatorio.
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>

                        <div class="row mt-3 mb-2">
                            <div class="col-md-12" style="text-align:center">
                                <button class="btn btn-primary mb-1" rel="tooltip" data-placement="bottom" id="GuardarDestino">Guardar</button>
                                <button class="btn btn-primary mb-1" rel="tooltip" style="display: none;" data-placement="bottom" id="EditarDestino">Editar</button>
                                <button class="btn btn-danger mb-1" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>

    <?php require_once('scripts.php');?>
    <script>
        $(document).ready(function () {
            $("#TDestinos").DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "bDestroy": true,
                "order": [
                [0, 'desc']
                ],
                "ajax": {
                url: "tablas/TablaDestinos.php",
                type: "post"
                },
                "language": {
                "searchPlaceholder": "Ingrese caracter",
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": " _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Consultar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "<<",
                    "sLast": ">>",
                    "sNext": ">",
                    "sPrevious": "<"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
                }
            })
            $('#TDestinos').css('width', '100%');
            $('#TDestinos_filter input, #TDestinos_length select').addClass('form-control');
        });

        $("#AddDestino").click(function (e) { 
            e.preventDefault();
            $("#titulo").text("Agregar Destino");
            $("#empresa").val("");
            $("#sede").val("");
            $("#direccion").val("");
            $("#status").prop("selectedIndex", 0);
            $("#municipio").prop("selectedIndex", 0);
            $("#GuardarDestino").css("display", "initial");
            $("#EditarDestino").css("display", "none");
        });

        function validaciones() {
            $("#errorEmpresa").css("display", "none");
            $("#errorSede").css("display", "none");
            $("#errorDireccion").css("display", "none");
            $("#errorMunicipio").css("display", "none");

            let cont = 0

            if ($("#empresa").val() == "" || $("#empresa").val() == null) {
                $("#errorEmpresa").css("display", "block");
                cont++
            }

            if ($("#sede").val() == "" || $("#sede").val() == null) {
                $("#errorSede").css("display", "block");
                cont++
            }

            if ($("#direccion").val() == "" || $("#direccion").val() == null) {
                $("#errorDireccion").css("display", "block");
                cont++
            }

            if ($("#municipio").val() == "" || $("#municipio").val() == null || $("#municipio").val() == "0") {
                $("#errorMunicipio").css("display", "block");
                cont++
            }

            if (cont == 0) {
                return ""
            }
        }

        $("#GuardarDestino").click(function (e) { 
            e.preventDefault();
            if (validaciones() == "") {
                datos = {
                    empresa: $("#empresa").val(),
                    sede: $("#sede").val(),
                    direccion: $("#direccion").val(),
                    municipio: $("#municipio").val(),
                    status: $("#status").val()
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        Destino: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#TDestinos").DataTable().ajax.reload();
                        $("#modalDestinos").modal("hide")
                        Swal.fire({
                            title: 'Destino Registrado correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                    }
                });
            }
        });


        function editar_destino(id) {
            $("#titulo").text("Editar Destino");
            $("#id").val(id);
            $("#empresa").val("");
            $("#sede").val("");
            $("#direccion").val("");
            $("#municipio").prop("selectedIndex", 0);
            $("#status").prop("selectedIndex", 0);
            $("#GuardarDestino").css("display", "none");
            $("#EditarDestino").css("display", "initial");
            $.ajax({
                type: "POST",
                url: "controlador/controlador.php",
                data: {
                    EditDestino: id
                },
                dataType: "json",
                success: function (data) {
                    $("#empresa").val(data[0].empresa);
                    $("#sede").val(data[0].sede);
                    $("#direccion").val(data[0].direccion);
                    $("#municipio").val(data[0].municipio);
                    $("#status").val(data[0].status);
                }
            });
        }

        $("#EditarDestino").click(function (e) { 
            e.preventDefault();
            if (validaciones() == "") {
                datos = {
                    id: $("#id").val(),
                    empresa: $("#empresa").val(),
                    sede: $("#sede").val(),
                    direccion: $("#direccion").val(),
                    municipio: $("#municipio").val(),
                    status: $("#status").val()
                }
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php",
                    data: {
                        EditarDestino: datos
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#id").val("");
                        $("#empresa").val("");
                        $("#sede").val("");
                        $("#direccion").val("");
                        $("#municipio").prop("selectedIndex", 0);
                        $("#status").prop("selectedIndex", 0);
                        $("#modalDestinos").modal("hide");
                        $("#TDestinos").DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Destino actualizado correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300, 
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    </script>
    <script>
        let timeout;

        function resetTimer() {
            clearTimeout(timeout);
            timeout = setTimeout(logout, 1200000);
        }

        function logout() {
            
            window.location.href = 'cerrarsesion.php';
        }
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onscroll = resetTimer;
    </script>
</body>
</html>