<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
error_reporting(0);
date_default_timezone_set("America/bogota");
require_once('./modelo/funciones.php');
$listaCategorias = listaCategorias();
$listaSubCategorias = listaSubCategorias();
$listaTipos = listaTipos();?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Planta de Desposte | Despacho</title>
  <?php include_once('encabezado.php');?>
<style>
	#jtable th, #jtable td {
    width: 20%; 
}
</style>
</head>
<body>
 <?php include_once('menu.php');?>
 <?php include_once('menuizquierda.php');?> 
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Administrar Placas de Vehiculos de Despacho</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">
                <center>
                  <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
                   title="Agregar Nueva Guia" id="crearProveedor">AGREGAR PLACA</a>
                </center>
              <table id="jtable" class="table table-striped table-bordered table-hover datatable">
              <thead>
                    <tr>
                        <th>Placa Vehiculo</th>
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
          <label style="text-align: center;font-weight: bold;" id="titulo">AGREGAR PLACAS</label>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
					<input type="hidden" id="id">
                    <input type="hidden" id="cedulaExiste">
						<div class="row">
							<div class="col-md-6" id="labelCanales">
								Placa
								<div class="form-group label-floating" id="itemDiv">
									<input class="form-control" type="text" name="cedula" autocomplete="off" id="cedula" placeholder="Ingrese la Placa del Vehiculo de Despacho" onkeyup="javascript:this.value=this.value.toUpperCase();">
								</div>
								<div class="alert alert-danger" role="alert" id="cedulaE" style="display: none">
									Debe ingresar la placa
								</div>
							</div>

                            <div class="col-md-6">
								Status
								<div class="form-group label-floating" id="statusDiv">
									<select class="form-control" id="status" name="status">
										<option value="">Seleccione el Status de la Placa</option>
                                        <option value="ACTIVO">ACTIVO</option>
                                        <option value="INACTIVO">INACTIVO</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="statusE" style="display: none">
									Debe seleccinar el status
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

<?php require_once('scripts.php');?>
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
          url: "tablas/tablaPlacas.php",
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

            console.log('datos para eliminar: ',datosEP);
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
			$('#titulo').text('AGREGAR PLACA');
			$('#btnNuevoProveedor').css('display', 'initial');
			$('#btnEditProveedor').css('display', 'none');
            $('#cedula').val('');
			$('#status').val('');

			$('#cedulaE').css('display', 'none');
			$('#statusE').css('display', 'none');
		});

 $('#btnNuevoProveedor').click(function() {
    let validacionesF = validaciones();

    if (validacionesF == "") {
        const datosPlacasD = {
            placa: $('#cedula').val(),
            status: $('#status').val()
        };

        console.log('datos a guardar:', datosPlacasD);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: { datosPlacasD: datosPlacasD },
            success: function(response) {
                if (response.status === "success") {
                    $('#jtable').DataTable().ajax.reload();
                    $('#cedula').val('');
                    $('#status').val('');
                    $("#modalNuevoProveedor").modal('hide');
                    Swal.fire({
                        title: 'Nueva Placa registrado satisfactoriamente',
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

		function buscarItems(id){
			$('#id').val(id);
			let idPlacasD = $("#id").val();
            console.log('id: ',id);
			$('#titulo').text('EDITAR PLACA');
			$('#btnNuevoProveedor').css('display', 'none');
			$('#btnEditProveedor').css('display', 'initial');
			console.log('consulta: ', idPlacasD);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idPlacasD: idPlacasD
				},
				success: function(data) {
					console.log('data: ',data);
					$('#id').val(data[0].id);
                    $('#cedula').val(data[0].placa);
					$('#status').val(data[0].status);
				}
			});
		}

		$('#btnEditProveedor').click(function() {
			validacionesF = validaciones();
			if (validacionesF == "") {
				infoEditPlacasD = {
                    "id": $('#id').val(),
                    "placa": $('#cedula').val(),
					"status": $('#status').val()
				};
				console.log('datos enviados: ',infoEditPlacasD);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoEditPlacasD
					},
					success: function(data) {
						$("#modalNuevoProveedor").modal('hide');
						$('#jtable').DataTable().ajax.reload();
						Swal.fire({
        					title: 'Placa modificada satisfactoriamente',
        					text: '',
        					icon: 'success',
        					timer: 1000, 
    						showConfirmButton: false
      					});
					}
				});
			}
		});

        $("#cedula").on('change', function() {
			console.log('validando Placa');
        	var validarPlacaD = $("#cedula").val();
        	if (!($("#cedula").val() == null || $("#cedula").val() == "")) {
            	console.log("Datos enviados a controlador:", validarPlacaD);
            	$.ajax({
                	type: 'POST',
                	url: 'controlador/controlador.php',
                	data: {
                    	validarPlacaD
                	},
                success: function(data) {
                    console.log(data);
                    if (data.length < 5) {						
						$('#cedulaExiste').val(validarPlacaD);
						$('#cedulaE').css('display', 'block').text('La Placa ya esta registrada en el sistema');
                    } else {
						$('#cedulaExiste').val('');
						$('#cedulaE').css('display', 'none');
                    }
                }
            });
        }
    	});

		function validaciones() {
			$('#cedulaE').css('display', 'none');
			$('#statusE').css('display', 'none');

            if ($("#cedula").val() == null || $("#cedula").val() == "" || $("#cedulaExiste").val() != "") {
			    $('#cedulaE').css('display', 'block');
                return 'R';
            }           
            			
            if ($("#status").val() == null || $("#status").val() == "") {
				$('#statusE').css('display', 'block');
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