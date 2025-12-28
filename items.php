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
		<h1>Administrar Items</h1>
		</div>

		<section class="section">
		<div class="row">
			<div class="col-lg-12">

			<div class="card">
				<div class="card-body">
				<h5 class="card-title">
					<center>
					<a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
					title="Agregar Nueva Guia" id="crearProveedor">AGREGAR ITEM</a>
					</center>
				<table id="jtable" class="table table-striped table-bordered table-hover datatable">
				<thead>
						<tr>
							<th>Item<br>Codigo</th>
							<th>Descripcion</th>
							<th>Tipo<br>Categoria</th>
							<th>Sub-Categoria<br>Status</th>
							<th>Acción<br>&nbsp;</th>
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
					<button type="button" class="btn-close" style="visibility: hidden;"></button>
					<label class="fw-bold w-100" id="titulo">AGREGAR ITEM</label>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
						<div class="row">

							<input type="hidden" id="id">
							<input type="hidden" id="itemExiste">
							<input type="hidden" id="codigoExiste">

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="item">Item</label>
								<div class="form-group label-floating" id="itemDiv">
									<input class="form-control" type="text" name="item" autocomplete="off" id="item" placeholder="Ingrese el Item">
								</div>
								<div class="alert alert-danger" role="alert" id="itemE" style="display: none">
									Debe ingresar el item
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="codigo">Codigo</label>
								<div class="form-group label-floating" id="codigoDiv">
									<input class="form-control" type="text" name="codigo" autocomplete="off" id="codigo" placeholder="Ingrese el Codigo">
								</div>
								<div class="alert alert-danger" role="alert" id="codigoE" style="display: none">
									Debe ingresar el codigo
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="descripcion">Descripcion</label>
								<div class="form-group label-floating" id="descripcionDiv">
									<input class="form-control" type="text" name="descripcion" autocomplete="off" id="descripcion" placeholder="Ingrese la Descripcion del Item" onkeyup="javascript:this.value=this.value.toUpperCase();">
								</div>
								<div class="alert alert-danger" role="alert" id="descripcionE" style="display: none">
									Debe ingresar la descripcion del item
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="tipo">Tipo</label>
								<div class="form-group label-floating" id="tipoDiv">
									<select class="form-control" id="tipo" name="tipo">
										<option value="">Seleccione el Tipo de Item</option>
										<?php foreach ($listaTipos as $perm) : ?>
											<option value="<?= $perm['descripcion'] ?>">
												<?= $perm['descripcion'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="tipoE" style="display: none">
									Debe seleccinar el tipo del item
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="categoria">Categoria</label>
								<div class="form-group label-floating" id="categoriaDiv">
									<select class="form-control" id="categoria" name="categoria">
										<option value="">Seleccione la Categoria del Item</option>
										<?php foreach ($listaCategorias as $perm) : ?>
											<option value="<?php echo $perm['descripcion'] ?>">
												<?php echo $perm['descripcion'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="categoriaE" style="display: none">
									Debe seleccinar la categoria del item
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="subcategoria">Sub - Categoria</label>
								<div class="form-group label-floating" id="subCategoriaDiv">
									<select class="form-control" id="subcategoria" name="subcategoria">
										<option value="">Seleccione la Sub-Categoria del Item</option>
										<?php foreach ($listaSubCategorias as $perm) : ?>
											<option value="<?php echo $perm['descripcion'] ?>">
												<?php echo $perm['descripcion'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="subcategoriaE" style="display: none">
									Debe seleccinar la sub-categoria del item
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="status">Status</label>
								<div class="form-group label-floating" id="statusDiv">
									<select class="form-control" id="status" name="status">
										<option value="">Seleccione el Status del Item</option>
										<option value="ACTIVO">ACTIVO</option>
										<option value="INACTIVO">INACTIVO</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="statusE" style="display: none">
									Debe seleccinar el status del item
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="tipoProceso">Tipo de Proceso</label>
								<div class="form-group label-floating" id="tipoProcesoDiv">
									<select class="form-control" id="tipoProceso" name="tipoProceso">
										<option value="">Seleccione el Tipo de Proceso</option>
										<option value="Marinado">Marinado</option>
										<option value="Sin Marinar">Sin Marinar</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="tipoProcesoE" style="display: none">
									Debe seleccinar el tipo de proceso del item
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="porcentajeParte">Porcentaje Parte (%)</label>
								<input type="number" class="form-control" id="porcentajeParte" name="porcentajeParte" placeholder="Ingrese el Porcentaje Parte" min="0" max="100">
								<div class="alert alert-danger" role="alert" id="porcentajeParteE" style="display: none">
									Debe ingresar el porcentaje de parte del item
								</div>
							</div>

							<div class="col-md-6 mb-2">
								<label class="form-label fw-bold" for="valorUnitario">Precio de venta unitario</label>
								<input type="number" class="form-control" id="valorUnitario" name="valorUnitario" placeholder="Ingrese el Valor Unitario" min="0">
								<div class="alert alert-danger" role="alert" id="valorUnitarioE" style="display: none">
									Debe ingresar el valor unitario del item
								</div>
							</div>
						</div>
					</form>
					<div class="row mt-2">
						<div class="col-md-12 d-flex justify-content-center gap-2">
							<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
							<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor" style="display:none">Editar</button>
							<button class="btn btn-danger" data-placement="bottom" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php require_once('scripts.php');?> 
	
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
				url: "tablas/tablaItems.php",
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
			$('#titulo').text('AGREGAR ITEM');
			$('#btnNuevoProveedor').css('display', 'initial');
			$('#btnEditProveedor').css('display', 'none');
            $('#item').val('');
			$('#codigo').val('');
			$('#descripcion').val('');
			$('#tipo').val('');
            $('#categoria').val('');
            $('#subcategoria').val('');
            $('#status').val('');
			$("#tipoProceso").val("");
			$("#porcentajeParte").val("");
			$("#valorUnitario").val("");

			$('.alert').hide();
			$('#itemE').css('display', 'none');
			$('#codigoE').css('display', 'none');
			$('#descripcionE').css('display', 'none');
			$('#tipoE').css('display', 'none');
            $('#categoriaE').css('display', 'none');
            $('#subcategoriaE').css('display', 'none');
            $('#statusE').css('display', 'none');
		});

		$('#btnNuevoProveedor').click(function() {
			let validacionesF = validaciones();

			if (validacionesF == "") {
				const datosItem = {
					item: $('#item').val(),
					codigo: $('#codigo').val(),
					descripcion: $('#descripcion').val(),
					tipo: $('#tipo').val(),
					categoria: $('#categoria').val(),
					subcategoria: $('#subcategoria').val(),
					status: $('#status').val(),
					tipoProceso: $('#tipoProceso').val(),
					porcentajeParte: $('#porcentajeParte').val(),
					valorUnitario: $('#valorUnitario').val()
				};

				console.log('datos a guardar:', datosItem);

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: { datosItem: datosItem },
					success: function(response) {
						if (response.status === "success") {
							$('#jtable').DataTable().ajax.reload();
							$('#item').val('');
							$('#codigo').val('');
							$('#descripcion').val('');
							$('#tipo').val('');
							$('#categoria').val('');
							$('#subcategoria').val('');
							$('#status').val('');
							$("#modalNuevoProveedor").modal('hide');
							Swal.fire({
								title: 'Nuevo Item registrado satisfactoriamente',
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
			$(".alert").hide();
			let idItem = $("#id").val();
            console.log('id: ',id);
			$('#titulo').text('EDITAR ITEM');
			$('#btnNuevoProveedor').css('display', 'none');
			$('#btnEditProveedor').css('display', 'initial');
			console.log('consulta: ', idItem);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idItem: idItem
				},
				success: function(data) {
					console.log('data: ',data);
					$('#id').val(data[0].id);
                    $('#item').val(data[0].item);
					$('#codigo').val(data[0].codigo);
					$('#descripcion').val(data[0].descripcion);
					$('#tipo').val(data[0].tipo);
					$('#categoria').val(data[0].categoria);
					$('#subcategoria').val(data[0].categoriadestino);
					$('#status').val(data[0].status);
					$('#tipoProceso').val(data[0].proceso);
					$('#porcentajeParte').val(data[0].PorcentajeParte);
					$('#valorUnitario').val(data[0].ValorUnitario);
				}
			});
		}

		$('#btnEditProveedor').click(function() {
			validacionesF = validaciones();
			if (validacionesF == "") {
				infoEditItem = {
                    "id": $('#id').val(),
                    "item": $('#item').val(),
					"codigo": $('#codigo').val(),
					"descripcion": $('#descripcion').val(),
					"tipo": $('#tipo').val(),
					"categoria": $('#categoria').val(),
					"subcategoria": $('#subcategoria').val(),
					"status": $('#status').val(),
					"tipoProceso": $('#tipoProceso').val(),
					"porcentajeParte": $('#porcentajeParte').val(),
					"valorUnitario": $('#valorUnitario').val()
				};
				console.log('datos enviados: ',infoEditItem);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoEditItem
					},
					success: function(data) {
						$("#modalNuevoProveedor").modal('hide');
						$('#jtable').DataTable().ajax.reload();
						Swal.fire({
        					title: 'Item modificado satisfactoriamente',
        					text: '',
        					icon: 'success',
        					timer: 1000, 
    						showConfirmButton: false
      					});
					}
				});
			}
		});

        $("#item").on('change', function() {
			console.log('validando Item');
        	var validarItem = $("#item").val();
        	if (!($("#item").val() == null || $("#item").val() == "")) {
            	console.log("Datos enviados a controlador:", validarItem);
            	$.ajax({
                	type: 'POST',
                	url: 'controlador/controlador.php',
                	data: {
                    	validarItem
                	},
					success: function(data) {
						if (data.length < 5) {						
							$('#itemExiste').val(validarItem);
							$('#itemE').css('display', 'block').text('El Item ya esta registrado en el sistema');
						} else {
							$('#itemExiste').val('');
							$('#itemE').css('display', 'none');
						}
					}
            	});
        	}
    	});

        $("#codigo").on('change', function() {
			console.log('validando Codigo');
        	var validarCodigo = $("#codigo").val();
        	if (!($("#codigo").val() == null || $("#codigo").val() == "")) {
            	console.log("Datos enviados a controlador:", validarCodigo);
            	$.ajax({
                	type: 'POST',
                	url: 'controlador/controlador.php',
                	data: {
                    	validarCodigo
                	},
					success: function(data) {
						if (data.length < 5) {						
							$('#codigoExiste').val(validarCodigo);
							$('#codigoE').css('display', 'block').text('El Codigo ya esta registrado en el sistema');
						} else {
							$('#codigoExiste').val('');
							$('#codigoE').css('display', 'none');
						}
					}
				});
			}
    	});

		function validaciones() {
			$('#itemE').css('display', 'none');
			$('#codigoE').css('display', 'none');
			$('#descripcionE').css('display', 'none');
			$('#tipoE').css('display', 'none');
			$('#categoriaE').css('display', 'none');
			$('#subcategoriaE').css('display', 'none');
			$('#statusE').css('display', 'none');

            if ($("#item").val() == null || $("#item").val() == "" || $("#itemExiste").val() != "") {
			    $('#itemE').css('display', 'block');
                return 'R';
            }           
            
            if ($("#codigo").val() == null || $("#codigo").val() == "" || $("#codigoExiste").val() != "") {
			    $('#codigoE').css('display', 'block');
                return 'R';
            }           

			if ($("#descripcion").val() == null || $("#descripcion").val() == "") {
             	$('#descripcionE').css('display', 'block');
				return 'R';
            }

			if ($("#tipo").val() == null || $("#tipo").val() == "") {
				$('#tipoE').css('display', 'block');
                return 'R';
            }
			
           /*  if ($("#categoria").val() == null || $("#categoria").val() == "") {
				$('#categoriaE').css('display', 'block');
                return 'R';
            }
			
            if ($("#subcategoria").val() == null || $("#subcategoria").val() == "") {
				$('#subcategoriaE').css('display', 'block');
                return 'R';
            } */
			
            if ($("#status").val() == null || $("#status").val() == "") {
				$('#statusE').css('display', 'block');
                return 'R';
            }

			if ($("#porcentajeParte").val() != "" && $("#porcentajeParte").val() != 0) {
				if ($("#porcentajeParte").val() < 0 || $("#porcentajeParte").val() > 100) {
					$('#porcentajeParteE').css('display', 'block');
					return 'R';
				}
			}

			if ($("#valorUnitario").val() != "" && $("#valorUnitario").val() != 0) {
				if ($("#valorUnitario").val() < 0) {
					$('#valorUnitarioE').css('display', 'block');
					return 'R';
				}
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
</body>
</html>