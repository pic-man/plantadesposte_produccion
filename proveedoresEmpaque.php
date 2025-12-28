<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
/* error_reporting(0);
date_default_timezone_set("America/bogota"); */
require_once('./modelo/funciones.php');
$listaMunicipios = listaMunicipios(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planta de Desposte | Proveedores</title>
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
            <h1>Administrar Proveedores de Empaque</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <center>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR PROVEEDOR</a>
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

    
    <div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
        <div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo">AGREGAR PROVEEDOR</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" id="id">

                        <div class="col-md-6 mb-2">
                            <label for="nit" class="form-label">Nit</label>
                            <input class="form-control" type="text" name="nit" autocomplete="off" id="nit" placeholder="Ingrese el Nit">
                            <div class="alert alert-danger" role="alert" id="nitE" style="display: none">
                                Debe ingresar el nit
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="razon" class="form-label">Razon Social</label>
                            <input class="form-control" type="text" name="razon" autocomplete="off" id="razon" placeholder="Ingrese la Razon Social" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            <div class="alert alert-danger" role="alert" id="razonE" style="display: none">
                                Debe ingresar la razon social
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="direccion" class="form-label">Direccion</label>
                            <input class="form-control" type="text" name="direccion" autocomplete="off" id="direccion" placeholder="Ingrese la Direccion" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            <div class="alert alert-danger" role="alert" id="direccionE" style="display: none">
                                Debe ingresar la direccion
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="municipio" class="form-label">Municipio</label>
                            <select class="form-control" id="municipio" name="municipio">
                                <option value="">Seleccione el Municipio</option>
                                <?php foreach ($listaMunicipios as $perm) : ?>
                                    <option value="<?php echo strtoupper($perm['descripcion']) ?>"><?php echo strtoupper($perm['descripcion']) ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="alert alert-danger" role="alert" id="municipioE" style="display: none">
                                Debe seleccinar el municipio
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Seleccione el Status del Item</option>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                            <div class="alert alert-danger" role="alert" id="statusE" style="display: none">
                                Debe seleccinar el status
                            </div>
                        </div>

                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 mb-1" style="text-align:center">
                            <button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
                            <button class="btn btn-primary" style="display:none" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor">Editar</button>
                            <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php require_once('scripts.php'); ?>
<script>
    $(document).ready(function() {
        $('#jtable').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "order": [
            [0, 'desc']
            ],
            "ajax": {
            url: "tablas/TablaProveedoresEmpaque.php",
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
        $('#jtable').css('width', '100%');
        $('#jtable_filter input, #jtable_length select').addClass('form-control');
        $('#jtable_filter').find('label').find('input').attr("name", "input")
    });

    $('#crearProveedor').click(function() {
        $('#titulo').text('AGREGAR PROVEEDOR');
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
        $('#btnNuevoProveedor').css('display', 'initial');
        $('#btnEditProveedor').css('display', 'none');
    });

    function validaciones() {
        $('#nitE').css('display', 'none');
        $('#razonE').css('display', 'none');
        $('#direccionE').css('display', 'none');
        $('#municipioE').css('display', 'none');
        $('#statusE').css('display', 'none');

        if ($("#nit").val() == null || $("#nit").val() == "" || $("#nitE").text() == "El Nit ya esta registrado en el sistema") {
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

    $('#btnNuevoProveedor').click(function() {
        if (validaciones() == "") {
            const datosProveedorP = {
                nit: $('#nit').val(),
                razon: $('#razon').val(),
                direccion: $('#direccion').val(),
                municipio: $('#municipio').val(),        
                status: $('#status').val()
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    datosProveedorE: datosProveedorP
                },
                success: function(response) {
                    if (response.status === "success") {
                        $('#jtable').DataTable().ajax.reload();
                        $("#modalNuevoProveedor").modal('hide');
                        Swal.fire({
                            title: 'Nuevo Proveedor registrado correctamente',
                            text: '',
                            icon: 'success',
                            timer: 1300,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
    });

    function buscarProveedor(id) {
        $('#id').val(id);
        $('#titulo').text('EDITAR PROVEEDOR');
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
        $('#btnNuevoProveedor').css('display', 'none');
        $('#btnEditProveedor').css('display', 'initial');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                idProveedorE: $("#id").val()
            },
            success: function(data) {
                $('#nit').val(data[0][0]);
                $('#razon').val(data[0][1]);
                $('#direccion').val(data[0][2]);
                $('#municipio').val(data[0][3]);
                $('#status').val(data[0][4]);
            }
        });
    }

    $('#btnEditProveedor').click(function() {
        if (validaciones() == "") {
            datos = {
                id: $('#id').val(),
                nit: $('#nit').val(),
                razon: $('#razon').val(),
                direccion: $('#direccion').val(),
                municipio: $('#municipio').val(),        
                status: $('#status').val()
            };
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    editProveedorE: datos
                },
                success: function(data) {
                    $("#modalNuevoProveedor").modal('hide');
                    $('#jtable').DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Proveedor modificado satisfactoriamente',
                        text: '',
                        icon: 'success',
                        timer: 1300,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    $("#nit").on('change', function() {
        var validarNitProveedorEmpaque = $("#nit").val();
        if (!$("#nit").val() == "" || !($("#nit").val() == null)) {
            $.ajax({
                type: 'POST',
                url: 'controlador/controlador.php',
                data: {
                    validarNitProveedorEmpaque
                },
                success: function(data) {
                if (data.length < 5) {
                    $('#nitE').css('display', 'block').text('El Nit ya esta registrado en el sistema');
                } else {
                    $('#nitE').css('display', 'none').text('Debe ingresar el nit');
                }
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
</html>