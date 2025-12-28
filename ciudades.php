<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
//error_reporting(0);
date_default_timezone_set("America/bogota");
//require_once('./modelo/funciones.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planta de Desposte | Ciudades</title>
    <?php include_once('encabezado.php'); ?>
    <style>
        #jtable th:nth-child(1),#jtable td:nth-child(1) {
            width: 6%;
            center{
                margin-top: 11px;
            }
        }
        #jtable th:nth-child(4),#jtable td:nth-child(4) {
            width: 14%;
        }
    </style>
</head>

<body>
    <?php include_once('menu.php'); ?>
    <?php include_once('menuizquierda.php'); ?>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Administrar Ciudades</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                            <center>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" title="Agregar Nueva Guia" id="crearProveedor">AGREGAR CIUDAD</a>
                            </center>
                            <table id="jtable" class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Ciudad/Municipio</th>
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
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 style="margin-bottom: 0px;font-weight: bold;" class="w-100" id="titulo">AGREGAR CIUDAD/MUNICIPIO</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <input type="hidden" id="id">

                        <div class="col-md-12 mb-2">
                            <label for="ciudadMunicipio" class="form-label">Ciudad/Municipio</label>
                            <input class="form-control" type="text" name="ciudadMunicipio" autocomplete="off" id="ciudadMunicipio" placeholder="Ingrese la Ciudad o Municipio" value="<?= $_SESSION['usuario'] ?>">
                            <div class="alert alert-danger" role="alert" id="ciudadMunicipioError" style="display: none">
                                Debe ingresar la Ciudad o el Municipio
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="0">Seleccione el status</option>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                            <div class="alert alert-danger" role="alert" id="statusError" style="display: none">
                                Debe ingresar el status
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

<?php require_once('scripts.php'); ?>
<script>
    $(document).ready(function() {
        $('#jtable').DataTable({
            "responsive": true,
            "processing": true,
            "bDestroy": true,
            "order": [
                [1, 'asc']
            ],
            "ajax": {
            url: "tablas/TablaCiudades.php",
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
        $('#titulo').text('AGREGAR CIUDAD/MUNICIPIO');
        $('#ciudadMunicipio').val("");
        $('#status').prop("selectedIndex", 0);
        $('#ciudadMunicipioError').css('display', 'none');
        $('#statusError').css('display', 'none');
        $('#btnNuevoProveedor').css('display', 'initial');
        $('#btnEditProveedor').css('display', 'none');
    });

    function validaciones() {
        $('#ciudadMunicipioError').css('display', 'none');
        $('#statusError').css('display', 'none');

        if ($("#ciudadMunicipio").val() == null || $("#ciudadMunicipio").val() == "") {
            $('#ciudadMunicipioError').css('display', 'block');
            return 'R1';
        }

        if ($("#status").val() == "0" || $("#status").val() == "") {
            $('#statusError').css('display', 'block');
            return 'R2';
        }

        return "";
    }

    $('#btnNuevoProveedor').click(function() {
        if (validaciones() == "") {
            
            datos = {
                ciudadMunicipio: $('#ciudadMunicipio').val(),
                status: $('#status').val()
            };

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    aggCiudad: datos
                },
                success: function(data) {
                    $("#modalNuevoProveedor").modal("hide");
                    $('#jtable').DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Registro registrado satisfactoriamente',
                        text: '',
                        icon: 'success',
                        timer: 1300,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    function edit_ciudad(id) {
        $('#id').val(id);
        $('#titulo').text('EDITAR CIUDAD/MUNICIPIO');
        $('#ciudadMunicipio').val("");
        $('#status').prop("selectedIndex", 0);
        $('#ciudadMunicipioError').css('display', 'none');
        $('#statusError').css('display', 'none');
        $('#btnNuevoProveedor').css('display', 'none');
        $('#btnEditProveedor').css('display', 'initial');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                idCiudad: $("#id").val()
            },
            success: function(data) {
                $('#ciudadMunicipio').val(data[0][0]);
                $('#status').val(data[0][1]);
            }
        });
    }

    $('#btnEditProveedor').click(function() {
        if (validaciones() == "") {
            datos = {
                id: $('#id').val(),
                ciudadMunicipio: $('#ciudadMunicipio').val(),
                status: $('#status').val(),
            };
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    editCiudad: datos
                },
                success: function(data) {
                    $("#modalNuevoProveedor").modal('hide');
                    $('#jtable').DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Registro modificado satisfactoriamente',
                        text: '',
                        icon: 'success',
                        timer: 1300,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    function borrar_ciudad(id) {
        Swal.fire({
            title: '¿Esta seguro que desea eliminar este Registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "controlador/controlador.php",
                data: {
                    EliminarCuidad: id
                },
                success: function(data) {
                    $('#jtable').DataTable().ajax.reload();
                    Swal.fire({
                        title: 'Registro eliminado satisfactoriamente',
                        text: '',
                        icon: 'success',
                        timer: 1300,
                        showConfirmButton: false
                    });
                }
            });
        }
        });
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