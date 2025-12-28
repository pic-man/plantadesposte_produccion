<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
error_reporting(0);
date_default_timezone_set("America/bogota");
require_once('./modelo/funciones.php');
$listaMunicipios = listaMunicipios(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planta de Desposte | Despacho</title>
    <?php include_once('encabezado.php'); ?>
    <style>
        #jtable th,
        #jtable td {
            width: 20%;
        }
    </style>
</head>

<body>
    <?php include_once('menu.php'); ?>
    <?php include_once('menuizquierda.php'); ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Administrar Plantas de Beneficio</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <center>
                                    <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
                                        title="Agregar Nueva Guia" id="crearProveedor">AGREGAR PLANTA DE BENEFICIO</a>
                                </center>
                                <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>Nit</th>
                                            <th>Razon Social</th>
                                            <th>Direccion</th>
                                            <th>Municipio</th>
                                            <th>Status</th>
                                            <th>Acción</th>
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
</body>

<div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
    <div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

        <div class="modal-content">
            <div class="modal-header" style="display: block;text-align: center;">
                <label style="text-align: center;font-weight: bold;" id="titulo">AGREGAR PLANTA DE BENEFICIO</label>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="criteriosForm">
                    <input type="hidden" id="id">
                    <input type="hidden" id="nitExiste">
                    <div class="row">
                        <div class="col-md-6" id="labelCanales">
                            Nit
                            <div class="form-group label-floating" id="itemDiv">
                                <input class="form-control" type="text" name="nit" autocomplete="off" id="nit" placeholder="Ingrese el Nit">
                            </div>
                            <div class="alert alert-danger" role="alert" id="nitE" style="display: none">
                                Debe ingresar el nit
                            </div>
                        </div>

                        <div class="col-md-6" id="labelCanales">
                            Razon Social
                            <div class="form-group label-floating" id="codigoDiv">
                                <input class="form-control" type="text" name="razon" autocomplete="off" id="razon" placeholder="Ingrese la Razon Social" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>
                            <div class="alert alert-danger" role="alert" id="razonE" style="display: none">
                                Debe ingresar la razon social
                            </div>
                        </div>

                        <div class="col-md-6" id="labelCanales">
                            Direccion
                            <div class="form-group label-floating" id="descripcionDiv">
                                <input class="form-control" type="text" name="direccion" autocomplete="off" id="direccion" placeholder="Ingrese la Direccion" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>
                            <div class="alert alert-danger" role="alert" id="direccionE" style="display: none">
                                Debe ingresar la direccion
                            </div>
                        </div>

                        <div class="col-md-6">
                            Municipio
                            <div class="form-group label-floating" id="statusDiv">
                                <select class="form-control" id="municipio" name="municipio">
                                    <option value="">Seleccione el Municipio</option>
                                    <?php foreach ($listaMunicipios as $perm) : ?>
                                        <option value="<?php echo strtoupper($perm['descripcion']) ?>">
                                            <?php echo strtoupper($perm['descripcion']) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="alert alert-danger" role="alert" id="statusE" style="display: none">
                                Debe seleccinar el municipio
                            </div>
                        </div>

                        <div class="col-md-6">
                            Status
                            <div class="form-group label-floating" id="statusDiv">
                                <select class="form-control" id="status" name="status">
                                    <option value="">Seleccione el Status del Item</option>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                </select>
                            </div>
                            <div class="alert alert-danger" role="alert" id="statusE" style="display: none">
                                Debe seleccinar el status del responsable
                            </div>
                        </div>

                    </div>
                </form>
                <div class="row mt-5">
                    <div class="col-md-12" style="text-align:center">
                        <button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
                        <button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;display:none" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor">Editar</button>
                        <button class="btn btn-danger" style="margin-bottom: 30px; margin-top: 10px;" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('scripts.php'); ?>
<script>
    $(document).ready(function() {
        $(document).ready(function() {
            var dataTable = $('#jtable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "bDestroy": true,
                "order": [
                    [0, 'desc']
                ],
                "ajax": {
                    url: "tablas/tablaBeneficio.php",
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
            });
        });
        $('#jtable').css('width', '100%');
        $('#jtable_filter input, #jtable_length select').addClass('form-control');
    });

    function eliminarItem(id_item_proveedor) {
        Swal.fire({
            title: '¿Esta seguro que desea eliminar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const datosEP = {
                    id_item_proveedor: id_item_proveedor,
                    proveedor: $('#id').val()
                };

                console.log('datos para eliminar: ', datosEP);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'controlador/controlador.php',
                    data: {
                        datosEP: datosEP
                    },
                    success: function(data) {
                        $('#jtableCriterio').DataTable().ajax.reload();
                        $('#jtable').DataTable().ajax.reload();
                        buscarItems($('#id').val());
                    }
                });
            }
        });
    }
    $('#crearProveedor').click(function() {
        $('#titulo').text('AGREGAR PLANTA DE BENEFICIO');
        $('#btnNuevoProveedor').css('display', 'initial');
        $('#btnEditProveedor').css('display', 'none');
        $('#nit').val('');
        $('#razon').val('');
        $('#direccion').val('');
        $('#municipio').val('');
        $('#status').val('');

        $('#nitE').css('display', 'none');
        $('#razonE').css('display', 'none');
        $('#direccionE').css('display', 'none');
        $('#municipioE').css('display', 'none');
        $('#statusE').css('display', 'none');
    });

    $('#btnNuevoProveedor').click(function() {
        let validacionesF = validaciones();
        console.log(validacionesF);
        if (validacionesF == "") {
            const datosPlanta = {
                nit: $('#nit').val(),
                razon: $('#razon').val(),
                direccion: $('#direccion').val(),
                municipio: $('#municipio').val(),
                status: $('#status').val()
            };

            console.log('datos a guardar:', datosPlanta);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    datosPlanta: datosPlanta
                },
                success: function(response) {
                    if (response.status === "success") {
                        $('#jtable').DataTable().ajax.reload();
                        $('#nit').val('');
                        $('#razon').val('');
                        $('#direccion').val('');
                        $('#municipio').val('');
                        $("#modalNuevoProveedor").modal('hide');
                        Swal.fire({
                            title: 'Nueva Planta registrada satisfactoriamente',
                            text: '',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema con la solicitud. Inténtelo de nuevo más tarde.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    console.error('Error:', error);
                }
            });
        }
    });

    function buscarItems(id) {
        $('#id').val(id);
        let idPlanta = $("#id").val();
        console.log('id: ', id);
        $('#titulo').text('EDITAR PLANTA');
        $('#btnNuevoProveedor').css('display', 'none');
        $('#btnEditProveedor').css('display', 'initial');
        console.log('consulta: ', idPlanta);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                idPlanta: idPlanta
            },
            success: function(data) {
                console.log('data: ', data);
                $('#id').val(data[0].id);
                $('#nit').val(data[0].nit);
                $('#razon').val(data[0].sede);
                $('#direccion').val(data[0].direccion);
                $('#municipio').val(data[0].municipio);
                $('#status').val(data[0].status);
            }
        });
    }

    $('#btnEditProveedor').click(function() {
        validacionesF = validaciones();
        if (validacionesF == "") {
            infoEditPlanta = {
                "id": $('#id').val(),
                "nit": $('#nit').val(),
                "razon": $('#razon').val(),
                "direccion": $('#direccion').val(),
                "municipio": $('#municipio').val(),
                "status": $('#status').val()
            };
            console.log('datos enviados: ', infoEditPlanta);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    infoEditPlanta
                },
                success: function(data) {
                    $("#modalNuevoProveedor").modal('hide');
                    $('#jtable').DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Planta modificada satisfactoriamente',
                        text: '',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    $("#nit").on('change', function() {
        console.log('validando Nit');
        var validarNitPlanta = $("#nit").val();
        if (!($("#nit").val() == null || $("#nit").val() == "")) {
            console.log("Datos enviados a controlador:", validarNitPlanta);
            $.ajax({
                type: 'POST',
                url: 'controlador/controlador.php',
                data: {
                    validarNitPlanta
                },
                success: function(data) {
                    if (data.length < 5) {
                        $('#nitExiste').val(validarNitPlanta);
                        $('#nitE').css('display', 'block').text('El Nit ya esta registrado en el sistema');
                    } else {
                        $('#nitExiste').val('');
                        $('#nitE').css('display', 'none');
                    }
                }
            });
        }
    });

    function validaciones() {
        $('#nitE').css('display', 'none');
        $('#razonE').css('display', 'none');
        $('#direccionE').css('display', 'none');
        $('#municipioE').css('display', 'none');
        $('#statusE').css('display', 'none');

        if ($("#nit").val() == null || $("#nit").val() == "" || $("#nitExiste").val() != "") {
            $('#nitE').css('display', 'block');
            return 'R1';
        }

        if ($("#razon").val() == null || $("#razon").val() == "") {
            $('#razonE').css('display', 'block');
            return 'R2';
        }

        if ($("#direccion").val() == null || $("#direccion").val() == "") {
            $('#direccionE').css('display', 'block');
            return 'R3';
        }

        if ($("#municipio").val() == null || $("#municipio").val() == "") {
            $('#municipioE').css('display', 'block');
            return 'R4';
        }

        if ($("#status").val() == null || $("#status").val() == "") {
            $('#statusE').css('display', 'block');
            return 'R';
        }

        return "";
    }

    function validacionesE() {
        $('#cedulaE').css('display', 'none');
        $('#nombresE').css('display', 'none');
        $('#telefonoE').css('display', 'none');
        $('#statusE').css('display', 'none');
        $('#claveE').css('display', 'none');
        $('#cclaveE').css('display', 'none');

        if ($("#cedula").val() == null || $("#cedula").val() == "" || $("#cedulaExiste").val() != "") {
            $('#cedulaE').css('display', 'block');
            return 'R';
        }

        if ($("#nombres").val() == null || $("#nombres").val() == "") {
            $('#nombresE').css('display', 'block');
            return 'R';
        }

        if ($("#telefono").val() == null || $("#telefono").val() == "") {
            $('#telefonoE').css('display', 'block');
            return 'R';
        }

        if ($("#status").val() == null || $("#status").val() == "") {
            $('#statusE').css('display', 'block');
            return 'R';
        }

        if ($("#clave").val() != $("#cclave").val()) {
            $('#cclaveE').css('display', 'block').text('La clave y la confirmacion no coinciden');
            return 'R';
        }

        return "";
    }
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
</html>