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
      <h1>Administrar Responsables</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">
                <center>
                  <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
                   title="Agregar Nueva Guia" id="crearProveedor">AGREGAR RESPONSABLE</a>
                </center>
              <table id="jtable" class="table table-striped table-bordered table-hover datatable">
              <thead>
                    <tr>
                        <th>Cedula</th>
                        <th>Nombres y Apellidos</th>
                        <th>Telefono</th>
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
          <label style="text-align: center;font-weight: bold;" id="titulo">AGREGAR RESPONSABLE</label>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
				<div class="modal-body">
					<form method="POST" id="criteriosForm">
					<input type="hidden" id="id">
                    <input type="hidden" id="cedulaExiste">
						<div class="row">
							<div class="col-md-6" id="labelCanales">
								Cedula
								<div class="form-group label-floating" id="itemDiv">
									<input class="form-control" type="text" name="cedula" autocomplete="off" id="cedula" placeholder="Ingrese la Cedula">
								</div>
								<div class="alert alert-danger" role="alert" id="cedulaE" style="display: none">
									Debe ingresar la cedula
								</div>
							</div>

                            <div class="col-md-6" id="labelCanales">
								Nombres y Apellidos 
								<div class="form-group label-floating" id="codigoDiv">
									<input class="form-control" type="text" name="nombres" autocomplete="off" id="nombres" placeholder="Ingrese los Nombres y Apellidos" onkeyup="javascript:this.value=this.value.toUpperCase();">
								</div>
								<div class="alert alert-danger" role="alert" id="nombresE" style="display: none">
									Debe ingresar los nombres y apellidos
								</div>
							</div>

                            <div class="col-md-6" id="labelCanales">
								Telefono
								<div class="form-group label-floating" id="descripcionDiv">
									<input class="form-control" type="text" name="telefono" autocomplete="off" id="telefono" placeholder="Ingrese el Telefono">
								</div>
								<div class="alert alert-danger" role="alert" id="telefonoE" style="display: none">
									Debe ingresar el telefono del responsable
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

                            <div class="col-md-6" id="labelCanales">
								Clave
								<div class="form-group label-floating" id="descripcionDiv">
									<input class="form-control" type="password" name="clave" autocomplete="off" id="clave" placeholder="Ingrese la Clave del Responsable">
								</div>
								<div class="alert alert-danger" role="alert" id="claveE" style="display: none">
									Debe ingresar la clave del responsable
								</div>
							</div>

                            <div class="col-md-6" id="labelCanales">
								Confirmar Clave
								<div class="form-group label-floating" id="descripcionDiv">
									<input class="form-control" type="password" name="cclave" autocomplete="off" id="cclave" placeholder="Confirme la clave del Responsable">
								</div>
								<div class="alert alert-danger" role="alert" id="cclaveE" style="display: none">
									Debe repetir la clave del responsable
								</div>
							</div>

                            <div class="col-md-6">
                                Firma
                                <input type="file" class="form-control" id="foto" name="foto">
                            </div>

                            <div class="mt-3 col-md-6 text-center">
						        <div id="photo-preview"></div>
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
          url: "tablas/tablaResponsables.php",
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
			$('#titulo').text('AGREGAR RESPONSABLE');
			$('#btnNuevoProveedor').css('display', 'initial');
			$('#btnEditProveedor').css('display', 'none');
            $('#cedula').val('');
			$('#nombres').val('');
			$('#telefono').val('');
			$('#status').val('');
            $('#clave').val('');
            $('#cclave').val('');
            $("#foto").val("");

			$('#cedulaE').css('display', 'none');
			$('#nombresE').css('display', 'none');
			$('#telefonoE').css('display', 'none');
			$('#statusE').css('display', 'none');
            $('#claveE').css('display', 'none');
            $('#cclaveE').css('display', 'none');
		});

 $('#btnNuevoProveedor').click(function() {
    let validacionesF = validaciones();

    if (validacionesF == "") {
        const datosResponsable = {
            cedula: $('#cedula').val(),
            nombres: $('#nombres').val(),
            telefono: $('#telefono').val(),
            status: $('#status').val(),
            clave: $('#clave').val(),
            cclave: $('#cclave').val()
        };

        console.log('datos a guardar:', datosResponsable);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: { datosResponsable: datosResponsable },
            success: function(response) {
                if (response.status === "success") {
                    $('#jtable').DataTable().ajax.reload();
                    $('#cedula').val('');
                    $('#nombres').val('');
                    $('#telefono').val('');
                    $('#status').val('');
                    $('#clave').val('');
                    $('#cclave').val('');
                    $("#modalNuevoProveedor").modal('hide');
                    Swal.fire({
                        title: 'Nuevo Responsable registrado satisfactoriamente',
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
			let idResponsable = $("#id").val();
            console.log('id: ',id);
            $("#foto").val("");
			$('#titulo').text('EDITAR RESPONSABLE');
			$('#btnNuevoProveedor').css('display', 'none');
			$('#btnEditProveedor').css('display', 'initial');
            $("#cedulaE").css("display", "none");
            $("#photo-preview").empty();
			console.log('consulta: ', idResponsable);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idResponsable: idResponsable
				},
				success: function(data) {
					console.log('data: ',data);
					$('#id').val(data[0].id);
                    $('#cedula').val(data[0].cedula);
					$('#nombres').val(data[0].nombres);
					$('#telefono').val(data[0].telefono);
					$('#status').val(data[0].status);
                    $("#photo-preview").empty();
                    $("#photo-preview").append("<img src='assets/img/firmas/"+data[0].cedula+".jpg?v=<?php echo time(); ?>' id='mifoto' alt='Foto' style='width: 250px; height: auto; border-radius: 8px;' />");
				}
			});
		}

		$('#btnEditProveedor').click(function() {
			validacionesF = validacionesE();
			if (validacionesF == "") {
				infoEditResponsable = {
                    "id": $('#id').val(),
                    "cedula": $('#cedula').val(),
					"nombres": $('#nombres').val(),
					"telefono": $('#telefono').val(),
					"status": $('#status').val(),
					"clave": $('#clave').val()
				};
				console.log('datos enviados: ',infoEditResponsable);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoEditResponsable
					},
					success: function(data) {
                        $("#photo-preview").empty();
						$("#modalNuevoProveedor").modal('hide');
						$('#jtable').DataTable().ajax.reload();
						Swal.fire({
        					title: 'Responsable modificado satisfactoriamente',
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
			console.log('validando Cedula');
        	var validarCedulaResponsable = $("#cedula").val();
        	if (!($("#cedula").val() == null || $("#cedula").val() == "")) {
            	console.log("Datos enviados a controlador:", validarCedulaResponsable);
            	$.ajax({
                	type: 'POST',
                	url: 'controlador/controlador.php',
                	data: {
                    	validarCedulaResponsable
                	},
                success: function(data) {
                    if (data.length < 5) {						
						$('#cedulaExiste').val(validarCedulaResponsable);
						$('#cedulaE').css('display', 'block').text('La Cedula ya esta registrada en el sistema');
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

            if ($("#clave").val() == null || $("#clave").val() == "") {
				$('#claveE').css('display', 'block');
                return 'R';
            }
			
            if ($("#cclave").val() == null || $("#cclave").val() == "") {
				$('#cclaveE').css('display', 'block');
                return 'R';
            }else{
                if ($("#clave").val() != $("#cclave").val()) {
				$('#cclaveE').css('display', 'block').text('La clave y la confirmacion no coinciden');
                return 'R';
                }
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
        
        $("#foto").on('change', function(event) {
            if ($("#cedula").val() == "" || $("#cedula").val() == null) {
                $("#cedulaE").css("display", "block");
            }else{
                const files = event.target.files;
                const formData = new FormData();
                const preview = $('#photo-preview');

                preview.empty();

                let fileNames = []; // Array para almacenar los nombres generados

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const uniqueId = $("#cedula").val();
                    const fileExtension = file.name.split('.').pop(); // Obtener la extensión del archivo
                    const newFileName = `${uniqueId}.${fileExtension}`; // Crear nuevo nombre

                    fileNames.push(newFileName); // Agregar nombre al array

                    const renamedFile = new File([file], newFileName, { type: file.type });

                    const img = $('<img>').attr('src', URL.createObjectURL(renamedFile)).css({ width: '250px', height: 'auto' }).attr("id", "mifoto");
                    preview.append(img);
                    formData.append('photos[]', renamedFile);
                }

                $.ajax({
                    url: 'controlador/uploadFirma.php',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                    },
                error: function() {
                    //alert('Error al guardar las fotos');
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