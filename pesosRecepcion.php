<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrarsesion.php");
    exit();
}
error_reporting(0);
$_SESSION['tipoR'] = 2;
date_default_timezone_set("America/bogota");
require_once('./modelo/funciones.php');
$listaResponsables = listaResponsables();
$listaConductores = listaConductores_recepcion();
$listaPlacas = listaPlacas_recepcion();
$listaOrigen = listaOrigen();
$listaDestinos = listaDestinos();

$fecha_actual = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Planta de Desposte | Pesos Recepción</title>
  <?php include_once('encabezado.php');?>
  <style>
        .custom-margin {
            display: flex;
            align-items: center;
        }
        .custom-margin .form-group {
            display: flex;
            align-items: center;
            margin-left: 10px;
        }
        .custom-margin .form-control {
            width: 100px; /* O el tamaño que prefieras */
        }
		.text-left-custom {
        	text-align: left !important;
    	}
		#jtableCriterio_paginate,  
		#jtableCriterio_filter,    
		#jtableCriterio_info,      
		#jtableCriterio_length {   
    		display: none;
		}
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
      <h1>Registro de pesos de canales bovinas / porcinas</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">
              <table id="jtable" class="table table-striped table-bordered table-hover datatable">
              <thead>
                    <tr>
                        <th>Consecutivo<br>Tipo</th>
                        <th>Fecha<br>Remisión</th>
                        <th>Planta Beneficio<br>Destino</th>
                        <th>Canales<br>Lote</th>
                    <?php //if($_SESSION['tipoR'] != 2){?>    
						<th>Acción<br>&nbsp;</th>
					<?php //}?>						
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

<div class="modal fade" id="modalCriterios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
		<div class="modal-dialog modal-dialog modal-dialog-centered modal-dialog modal-fullscreen modal-dialog-scrollable">

        <div class="modal-content">
            <div class="modal-header" style="display: block;text-align: center;">
                <h5 class="modal-title" id="titulo2" style="font-weight: bold; text-align: center;"></h5>
            </div>
            <div class="modal-body">
            
                <div class="text-center mt-3 mb-3">
                    <!-- botones res -->
			
                    <button class="btn btn-danger" data-placement="bottom" data-bs-dismiss="modal" id="btnCerrarCriterios">Cerrar</button>
                </div>
                <div class="table-responsive">
                    <table id="jtableCriterio" class="table table-striped table-bordered table-hover datatable">
                        <thead>
                        <tr>
							<th>#</th>
							<th>Turno</th>
							<th id="pesoHeader">Peso</th>
							<!-- <th>Estomago 2</th>
							<th>Piernas 1</th>
							<th>Piernas 2</th> -->
							<th>Recibido Planta</th>
							<th>Peso Frigorifico</th>
                            <th>Diferencia Peso</th>
							<th>Diferencia %</th>
                            <th>Acciones</th>
						</tr>
                        </thead>
                        <tbody id="tbodyCriterio"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('scripts.php');?>
  <script>
		$(document).ready(function() {
			var dataTable = $('#jtable').dataTable({
				"responsive": true,
                "processing": true,
                "serverSide": true,
                "bDestroy": true,
                "order": [[0, 'desc']],
                "ajax": {
					url: "tablas/tablaRecepciones.php",
					type: "post"
				},
				"language": {
					"searchPlaceholder": "Ingrese caracter",
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
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
					},
				},
			});
			$('#jtable td:nth-child(5)').addClass('text-left-custom');
			$('#jtable').css('width', '100%');
            $('#jtable_filter input, #jtable_length select').addClass('form-control');
		
		});

		function eliminarItem(id_recepcion_peso) {
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
            const datosEPesoRecepcion = {
                id_recepcion_peso: id_recepcion_peso
            };
			console.log('datos a eliminar: ',id_recepcion_peso);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controlador/controlador.php',
                data: {
                    datosEPesoRecepcion: datosEPesoRecepcion
                },

                success: function(data) {
					console.log(data);
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'El elemento ha sido eliminado con éxito.',
                        icon: 'success',
                        timer: 1000, 
    					showConfirmButton: false
                    });
                    $('#jtableCriterio').DataTable().ajax.reload();
                }
            });
        }
    });
}


		function abrirModal(consecutivoRecepcion, status, tipo, raza,canales) {
			$('#titulo2').text('Consecutivo Recepcion: ' + consecutivoRecepcion + ' Especie: ' + raza);
			console.log('raza: ',raza);
			
			$('#turnoE').css('display', 'none');
			$('#parteE').css('display', 'none');
			$('#pesoE').css('display', 'none');
			
			if(raza=='RES'){
				
				$('#btnNuevoCriterioCerdo').css('display', 'none');
			    $('#btnEditarCriterioCerdo').css('display', 'none');
				
				$('#parteDiv').css('display', 'initial');
				$("#turnoDiv").removeClass("col-md-4");
				$("#pesoDiv").removeClass("col-md-4");
				$("#temperaturaDiv").removeClass("col-md-4");
				$("#turnoDiv").addClass("col-md-3");
				$("#pesoDiv").addClass("col-md-3");
				$("#temperaturaDiv").addClass("col-md-3");

				if((status==1) || (tipo == 0)){
					$('#btnNuevoCriterio').css('display', 'initial');
					$('#btnEditarCriterio').css('display', 'none');
				}else{
					$('#btnNuevoCriterio').css('display', 'none');
					$('#btnEditarCriterio').css('display', 'none');
				}
			}else{
				$('#btnNuevoCriterio').css('display', 'none');
				$('#btnEditarCriterio').css('display', 'none');
				
				$('#parteDiv').css('display', 'none');
				$("#turnoDiv").removeClass("col-md-3");
				$("#pesoDiv").removeClass("col-md-3");
				$("#temperaturaDiv").removeClass("col-md-3");
				$("#turnoDiv").addClass("col-md-4");
				$("#pesoDiv").addClass("col-md-4");
				$("#temperaturaDiv").addClass("col-md-4");

				if((status==1) || (tipo == 0)){
					$('#btnNuevoCriterioCerdo').css('display', 'initial');
					$('#btnEditarCriterioCerdo').css('display', 'none');
				}else{
					$('#btnNuevoCriterioCerdo').css('display', 'none');
					$('#btnEditarCriterioCerdo').css('display', 'none');
				}
			}

 			$('#turno').val('');
			$('#parte').val('');
			$('#peso').val('');
 		}

        /* function calcularDiferencia(id_recepcion_peso,peso,total) {
            if (peso > 0){
            const datosPesoRecepcion = {
                id_recepcion_peso: id_recepcion_peso,
                peso: peso,
			    total: total
        };
        console.log('datos para calcular: ', datosPesoRecepcion);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                datosPesoRecepcion: datosPesoRecepcion
            },
            success: function(data) {
                console.log('respuesta:', data);
                $('#jtableCriterio').DataTable().ajax.reload();
                if (data.status === 'success') {	
                    $('#jtableCriterio').DataTable().ajax.reload();
                } 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', textStatus, errorThrown);

                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado: ' + textStatus,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
   } */

   function calcularDiferencia(id_recepcion_peso, peso, total) {
    if (peso > 0) {
        const datosPesoRecepcion = {
            id_recepcion_peso: id_recepcion_peso,
            peso: peso,
            total: total
        };
        console.log('datos para calcular: ', datosPesoRecepcion);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                datosPesoRecepcion: datosPesoRecepcion
            },
            success: function(data) {
                console.log('respuesta:', data);
                //if (data.status === 'success') {
                    $('#jtableCriterio').DataTable().ajax.reload();
                //}
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', textStatus, errorThrown);

                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado: ' + textStatus,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
}

function focusNextInput(currentIndex) {
    var nextInput = document.getElementById('pesoInv_' + (currentIndex + 1));
    if (nextInput) {
        nextInput.focus();
    }
}

		function buscarItems(id_recepcion,raza) {
			//$('#jtableCriterio thead tr').empty();
        	//$('#jtableCriterio tbody').empty();
			ruta = "tablas/tablaPesosRecepcion.php";
			if (raza=='CERDO'){
				ruta = "tablas/tablaPesosCerdoRecepcion.php";
				$('#pesoHeader').text('Peso');
				//$('#jtableCriterio').DataTable().destroy();
				//$('#jtableCriterio thead tr').empty();
				//var newHeaders = ['#', 'Turno', 'Peso', 'Temperatura', 'Hora', 'Acción'];
			}else{
				$('#pesoHeader').text('E1 - E2 - P1 - P2');
				ruta = "tablas/tablaPesosRecepcion.php";
				//$('#jtableCriterio').DataTable().destroy();
				//$('#jtableCriterio thead tr').empty();
				//var newHeaders = ['#', 'Turno', 'Estomago 1', 'Estomago 2', 'Pierna 1', 'Pierna 2', 'Total', 'Temperatura', 'Hora', 'Acción'];
			}
        
        	/* newHeaders.forEach(function(header) {
            	$('#jtableCriterio thead tr').append('<th>' + header + '</th>');
        	}); */
			
			$('#id').val(id_recepcion);
			$('#jtableCriterio').dataTable({
				"paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
        		"processing": true,
        		"serverSide": true,
        		"bDestroy": true,
        		"order": [
            		[0, 'desc']
        		],
        		"columnDefs": [{
            	"orderable": false,
            	"targets": [1]
        		}],
				"ajax": {
                    url: ruta,
					type: "post",
					data: {
						recepcion: id_recepcion
					}
				},
			"language": {
            "searchPlaceholder": "Ingrese caracter",
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
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
            },
        },
        "dom": '<"row"<"col-md-6"l><"col-md-6"f>><"row"<"col-md-12"tr>><"row"<"col-md-6"i><"col-md-6"p>>',
    });

    // Estilo personalizado para alinear y espaciar los botones de paginación
    $('#jtableCriterio_paginate').addClass('text-md-right mt-3').css('float', 'center');
    $('#jtableCriterio_paginate .paginate_button').addClass('ml-1');
}


$('#btnCancelarEdicion').click(function() {
    $('#btnNuevoCriterio').css('display', 'initial');
	$('#btnEditarCriterio').css('display', 'none');
	$('#btnCancelarEdicion').css('display', 'none');
	$('#registrosPesos').css('display', '');
	$('#edicionPesos').css('display', 'none');
});

$('#btnCancelarEdicionCerdo').click(function() {
	$('#turno').prop('disabled', false);
    $('#btnNuevoCriterioCerdo').css('display', 'initial');
	$('#btnEditarCriterioCerdo').css('display', 'none');
	$('#btnCancelarEdicionCerdo').css('display', 'none');
	$('#registrosPesosCerdo').css('display', '');
	$('#edicionPesosCerdo').css('display', 'none');

	$('#turno').val('');
	$('#peso').val('');
	$('#temperaturap').val('');
});

$('#btnCerrarCriterios').click(function() {
	$('#turno').prop('disabled', false);
    $('#btnNuevoCriterioCerdo').css('display', 'initial');
	$('#btnEditarCriterioCerdo').css('display', 'none');
	$('#btnCancelarEdicionCerdo').css('display', 'none');
	$('#registrosPesosCerdo').css('display', '');
	$('#edicionPesosCerdo').css('display', 'none');

	$('#turno').val('');
	$('#peso').val('');
	$('#temperaturap').val('');
});

$('#btnNuevoCriterio').click(function() {
    validacionesCri = validacionesC('RES');
    if (validacionesCri == ""){
        const datosPeso = {
            turno: $('#turno').val(),
            parte: $('#parte').val(),
            peso: $('#peso').val(),
			temperaturap: $('#temperaturap').val(),
            recepcion: $('#id').val()
        };
        console.log('datos a registrar: ', datosPeso);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                datosPeso: datosPeso
            },
            success: function(data) {
                console.log('respuesta:', data);
                if (data.status === 'success') {
                    $('#valorCriterio').val('');
                    /* $('#turno').val('');
                    $('#parte').val(''); */
                    $('#peso').val('');
					$('#observacionPeso').val('');
					$('#foto').val('');
					$('#temperaturap').val('');
					$('#jtableCriterio').DataTable().ajax.reload();

                    Swal.fire({
                        title: 'Peso registrado',
                        text: data.message,
                        icon: 'success',
                        timer: 1000, 
    					showConfirmButton: false
                    });
                } else if (data.status === 'warning') {
                    Swal.fire({
                        title: 'Advertencia',
                        text: data.message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                } else if (data.status === 'error') {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Respuesta del servidor no válida',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', textStatus, errorThrown);

                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado: ' + textStatus,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
});

$('#btnNuevoCriterioCerdo').click(function() {
    validacionesCri = validacionesC('CERDO');
    if (validacionesCri == ""){
        const datosPesoCerdo = {
            turno: $('#turno').val(),
            parte: $('#parte').val(),
            peso: $('#peso').val(),
			observacionPeso: $('#observacionPeso').val(),
			foto: $('#foto').val(),
			temperaturap: $('#temperaturap').val(),
            recepcion: $('#id').val()
        };
        console.log('datos a registrar: ', datosPesoCerdo);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controlador/controlador.php',
            data: {
                datosPesoCerdo: datosPesoCerdo
            },
            success: function(data) {
                console.log('respuesta:', data);
                if (data.status === 'success') {
                    $('#valorCriterio').val('');
                    $('#turno').val('');
                    $('#parte').val('');
                    $('#peso').val('');
					$('#temperaturap').val('');
					$('#jtableCriterio').DataTable().ajax.reload();

                    Swal.fire({
                        title: 'Peso registrado',
                        text: data.message,
                        icon: 'success',
                        timer: 1000, 
    					showConfirmButton: false
                    });
                } else if (data.status === 'warning') {
                    Swal.fire({
                        title: 'Advertencia',
                        text: data.message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                } else if (data.status === 'error') {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Respuesta del servidor no válida',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', textStatus, errorThrown);

                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado: ' + textStatus,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
});

		$('#crearProveedor').click(function() {
			var today = new Date();
        	var day = String(today.getDate()).padStart(2, '0');
        	var month = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
        	var year = today.getFullYear();
	        var formattedDate = year + '-' + month + '-' + day;
        
			$('#fecharec').val(formattedDate);
			$('#fechasac').val('');
			$('#tipo').val('');
			$('#remision').val('');
			$('#canales').val('');
			$('#consecutivog').val('');
			$('#responsable').val(<?php echo $_SESSION['usuario'];?>);
			$('#beneficio').val('');
			$('#destino').val('');
			$('#conductor').val('');
			$('#placa').val('');
			$('#ica').val('');
			$('#guiat').val('');
			$('#certificadoc').val('');
			$('#cph1').val('');$('#cph1').prop('checked', false);
			$('#cph2').val('');$('#cph2').prop('checked', false);
			$('#cph3').val('');$('#cph3').prop('checked', false);
			$('#cph4').val('');$('#cph4').prop('checked', false);
			$('#cph5').val('');$('#cph5').prop('checked', false);
			$('#chv1').val('');$('#chv1').prop('checked', false);
			$('#chv2').val('');$('#chv2').prop('checked', false);
			$('#chv3').val('');$('#chv3').prop('checked', false);
			$('#chv4').val('');$('#chv4').prop('checked', false);
			$('#ccoh1').val('');$('#ccoh1').prop('checked', false);
			$('#ccoh2').val('');$('#ccoh2').prop('checked', false);
			$('#ccoh3').val('');$('#ccoh3').prop('checked', false);
			$('#ccoh4').val('');$('#ccoh4').prop('checked', false);
			$('#ccoh5').val('');$('#ccoh5').prop('checked', false);
			$('#ccoh6').val('');$('#ccoh6').prop('checked', false);
			$('#ccoh7').val('');$('#ccoh7').prop('checked', false);
			$('#ccoh8').val('');$('#ccoh8').prop('checked', false);
			$('#ccoh9').val('');$('#ccoh9').prop('checked', false);
			$('#ccoh10').val('');$('#ccoh10').prop('checked', false);
			$('#despacho').val('');
			$('#lotep').val('');
			$('#observaciones').val('');
			
			$('#remision').css('border', '');
			$('#destino').css('border', '');
			$('#canales').css('border', '');
			$('#ica').css('border', '');
			$('#certificadoc').css('border', '');

			$('#id_guia').val('');
			$('#titulo').text('Registro de Recepción');
			$('#btnNuevoProveedor').css('display', 'initial');
			$('#btnEditProveedor').css('display', 'none');
		});

		$('#abrirSegundaModal').click(function() {
			
			$('#cedula').val('');
			$('#nombresConductor').val('');
			
			$('#cedulaE').css('display', 'none');
			$('#nombresConductorE').css('display', 'none');
		});

		$('#abrirTerceraModal').click(function() {
			
			$('#placav').val('');
			
			$('#placavE').css('display', 'none');
		});

		$('#btnNuevoProveedor').click(function() {
			validacionesF = validaciones();
        	
			if (validacionesF == ""){	
			  console.log('validaciones: ',validacionesF);	
			  const datosRecepcion = {
				fecharec: $('#fecharec').val(),
				fechasac: $('#fechasac').val(),
				tipo: $('#tipo').val(),
				remision: $('#remision').val(),
				canales: $('#canales').val(),
				despacho: $('#despacho').val(),
				consecutivog: $('#consecutivog').val(),
				responsable: $('#responsable').val(),
				beneficio: $('#beneficio').val(),
				destino: $('#destino').val(),
				conductor: $('#conductor').val(),
				placa: $('#placa').val(),
				lotep: $('#lotep').val(),
				ica: $('#ica').val(),
				guiat: $('#guiat').val(),
				certificadoc: $('#certificadoc').val(),
				chv1: $('#chv1').val(),
				cph1: $('#cph1').is(':checked') ? 1 : 0,
				cph2: $('#cph2').is(':checked') ? 1 : 0,
				cph3: $('#cph3').is(':checked') ? 1 : 0,
				cph4: $('#cph4').is(':checked') ? 1 : 0,
				cph5: $('#cph5').is(':checked') ? 1 : 0,
				chv2: $('#chv2').is(':checked') ? 1 : 0,
				chv3: $('#chv3').is(':checked') ? 1 : 0,
				chv4: $('#chv4').is(':checked') ? 1 : 0,
				ccoh1: $('#ccoh1').is(':checked') ? 1 : 0,
				ccoh2: $('#ccoh2').is(':checked') ? 1 : 0,
				ccoh3: $('#ccoh3').is(':checked') ? 1 : 0,
				ccoh4: $('#ccoh4').is(':checked') ? 1 : 0,
				ccoh5: $('#ccoh5').is(':checked') ? 1 : 0,
				ccoh6: $('#ccoh6').is(':checked') ? 1 : 0,
				ccoh7: $('#ccoh7').is(':checked') ? 1 : 0,
				ccoh8: $('#ccoh8').is(':checked') ? 1 : 0,
				ccoh9: $('#ccoh9').is(':checked') ? 1 : 0,
				ccoh10: $('#ccoh10').is(':checked') ? 1 : 0,
				observaciones: $('#observaciones').val()
			};
			console.log('datos a guardar:', datosRecepcion);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosRecepcion: datosRecepcion
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
					$('#fecharec').val('');
					$('#fechasac').val('');
					$('#tipo').val('');
					$('#remision').val('');
					$('#canales').val('');
					$('#consecutivog').val('');
					$('#responsable').val('');
					$('#beneficio').val('');
					$('#destino').val('');
					$('#conductor').val('');
					$('#placa').val('');
					$('#ica').val('');
					$('#guiat').val('');
					$('#certificadoc').val('');
					$('#cph1').val('');
					$('#cph2').val('');
					$('#cph3').val('');
					$('#cph4').val('');
					$('#cph5').val('');
					$('#chv1').val('');
					$('#chv2').val('');
					$('#chv3').val('');
					$('#chv4').val('');
					$('#ccoh1').val('');
					$('#ccoh2').val('');
					$('#ccoh3').val('');
					$('#ccoh4').val('');
					$('#ccoh5').val('');
					$('#ccoh6').val('');
					$('#ccoh7').val('');
					$('#ccoh8').val('');
					$('#ccoh9').val('');
					$('#despacho').val('');
					$('#lotep').val('');
					$('#observaciones').val('');
					
					$('#remision').css('border', '');
					$('#destino').css('border', '');
					$('#canales').css('border', '');
					$('#ica').css('border', '');
					$('#certificadoc').css('border', '');

					$("#modalNuevoProveedor").modal('hide');
					Swal.fire({
        				title: 'Nueva recepción registrada satisfactoriamente',
        				text: '',
        				icon: 'success',
        				timer: 1000, 
    					showConfirmButton: false
      				});
				}
			});
		  }
		});

		$('#btnNuevoConductor').click(function() {
			validacionesCo = validacionesCon();
        					  
			if (validacionesCo == "" && $("#cedulaExiste").val() == ""){	
			  console.log('validaciones: ',validacionesCon);	
			  const datosConductor = {
				cedula: $('#cedula').val(),
				nombresConductor: $('#nombresConductor').val()
			};
			console.log('datos a guardar:', datosConductor);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosConductor: datosConductor
				},
				
				success: function(data) {
					console.log('data: ', data);
					$("#modalNuevoConductor").modal('hide');
					Swal.fire({
        				title: 'Nuevo conductor registrado satisfactoriamente',
        				text: '',
        				icon: 'success',
        				timer: 1000, 
    					showConfirmButton: false
      				});
					
					  let selectConductor = $('#conductor');
    				  selectConductor.empty();
    				  selectConductor.append('<option value="">Seleccione el Conductor de Recepcion</option>');

    				  let listaConductores = JSON.parse(data);

    				  listaConductores.forEach(function(perm) {
        			 	 selectConductor.append('<option value="' + perm.cedula + '">' + perm.cedula + " - " + perm.nombres + '</option>');
    				  });
					  $('#conductor').val($('#cedula').val());
				}
			});
		  }
		});

		$('#btnNuevaPlaca').click(function() {
			validacionesPl = validacionesPla();
        					  
			if (validacionesPl == ""){	
			  console.log('validaciones: ',validacionesPl);	
			  const datosPlacaR = {
				placa: $('#placav').val()
			};
			console.log('datos a guardar:', datosPlacaR);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosPlacaR: datosPlacaR
				},
				
				success: function(data) {
					console.log('data: ', data);
					
					if (data.length < 5) {						
						$('#placavE').css('display', 'block').text('La placa ya esta registrada en el sistema');
                    } else {
						$('#placavE').css('display', 'none');
						$("#modalNuevaPlaca").modal('hide');
						Swal.fire({
        					title: 'Nueva placa registrada satisfactoriamente',
        					text: '',
        					icon: 'success',
        					timer: 1000, 
    						showConfirmButton: false
      					});
					
					  	let selectPlaca = $('#placa');
    				  	selectPlaca.empty();
    				  	selectPlaca.append('<option value="">Seleccione la Placa del Vehiculo</option>');

    				  	let listaPlaca = JSON.parse(data);

    				  	listaPlaca.forEach(function(perm) {
        			 	selectPlaca.append('<option value="' + perm.placa + '">' + perm.placa +'</option>');
    				  });
					  $('#placa').val($('#placav').val());
                    }					
				}
			});
		  }
		});
		
		function buscarGuia(idRecepcion) {
			$('#id_guia').val(idRecepcion);
			$('#titulo').text('Editar Guia');
			$('#btnNuevoProveedor').css('display', 'none');
			$('#btnEditProveedor').css('display', 'initial');
			console.log('consulta: ', idRecepcion);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idRecepcion: idRecepcion
				},
				success: function(data) {
                    console.log('datos: ',data);
                    $('#fecharec').val(data[0].fecharec);
					$('#fechasac').val(data[0].fechasac);
					$('#tipo').val(data[0].tipo);
					$('#remision').val(data[0].remision);
					$('#canales').val(data[0].canales);
					$('#despacho').val(data[0].recibo);
					$('#consecutivog').val(data[0].consecutivog);
					$('#responsable').val(data[0].responsable);
					$('#beneficio').val(data[0].beneficio);
					$('#destino').val(data[0].destino);
					$('#conductor').val(data[0].conductor);
					$('#placa').val(data[0].placa);
					$('#lotep').val(data[0].lotep);
					$('#ica').val(data[0].ica);
					$('#guiat').val(data[0].guiat);
					$('#certificadoc').val(data[0].conductor);
					$('#remision').css('border', '');
					$('#destino').css('border', '');
					$('#canales').css('border', '');
					$('#ica').css('border', '');
					$('#certificadoc').css('border', '');

					if(data[0].cph1 == 1){$('#cph1').prop('checked', true);}
					else{$('#cph1').prop('checked', false);}

					if(data[0].cph2 == 1){$('#cph2').prop('checked', true);}
					else{$('#cph2').prop('checked', false);}

					if(data[0].cph3 == 1){$('#cph3').prop('checked', true);}
					else{$('#cph3').prop('checked', false);}

					if(data[0].cph4 == 1){$('#cph4').prop('checked', true);}
					else{$('#cph4').prop('checked', false);}

					if(data[0].cph5 == 1){$('#cph5').prop('checked', true);}
					else{$('#cph5').prop('checked', false);}

					$('#chv1').val(data[0].chv1);
                    
					if(data[0].chv2 == 1){$('#chv2').prop('checked', true);}
					else{$('#chv2').prop('checked', false);}

					if(data[0].chv3 == 1){$('#chv3').prop('checked', true);}
					else{$('#chv3').prop('checked', false);}

					if(data[0].chv4 == 1){$('#chv4').prop('checked', true);}
					else{$('#chv4').prop('checked', false);}

					if(data[0].ccoh1 == 1){$('#ccoh1').prop('checked', true);}
					else{$('#ccoh1').prop('checked', false);}

					if(data[0].ccoh2 == 1){$('#ccoh2').prop('checked', true);}
					else{$('#ccoh2').prop('checked', false);}

					if(data[0].ccoh3 == 1){$('#ccoh3').prop('checked', true);}
					else{$('#ccoh3').prop('checked', false);}

					if(data[0].ccoh4 == 1){$('#ccoh4').prop('checked', true);}
					else{$('#ccoh4').prop('checked', false);}

					if(data[0].ccoh5 == 1){$('#ccoh5').prop('checked', true);}
					else{$('#ccoh5').prop('checked', false);}

					if(data[0].ccoh6 == 1){$('#ccoh6').prop('checked', true);}
					else{$('#ccoh6').prop('checked', false);}

					if(data[0].ccoh7 == 1){$('#ccoh7').prop('checked', true);}
					else{$('#ccoh7').prop('checked', false);}

					if(data[0].ccoh8 == 1){$('#ccoh8').prop('checked', true);}
					else{$('#ccoh8').prop('checked', false);}

					if(data[0].ccoh9 == 1){$('#ccoh9').prop('checked', true);}
					else{$('#ccoh9').prop('checked', false);}

					if(data[0].ccoh10 == 1){$('#ccoh10').prop('checked', true);}
					else{$('#ccoh10').prop('checked', false);}

					$('#observaciones').val(data[0].observaciones);
				}
			});
		}

		function copiarDatos() {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					copiarDatos: 1
				},
				success: function(data) {
                    console.log('id_recepcion: ',data[0].id_recepcion);
					console.log('responsable: ',data[0].responsable,' - sesion:',<?php echo $usuarioresponsable;?>);
                 	$('#fechasac').val(data[0].fechasac);
					$('#tipo').val(data[0].tipo);
					
					$('#despacho').val(data[0].recibo);
					$('#consecutivog').val(data[0].consecutivog);
					$('#responsable').val(data[0].responsable);
					$('#beneficio').val(data[0].beneficio);
					$('#conductor').val(data[0].conductor);
					$('#placa').val(data[0].placa);
					$('#guiat').val(data[0].guiat);
					
					$('#remision').css('border', '2px solid red');
					$('#destino').css('border', '2px solid red');
					$('#canales').css('border', '2px solid red');
					$('#ica').css('border', '2px solid red');
					$('#certificadoc').css('border', '2px solid red');

					if(data[0].cph1 == 1){$('#cph1').prop('checked', true);}
					else{$('#cph1').prop('checked', false);}

					if(data[0].cph2 == 1){$('#cph2').prop('checked', true);}
					else{$('#cph2').prop('checked', false);}

					if(data[0].cph3 == 1){$('#cph3').prop('checked', true);}
					else{$('#cph3').prop('checked', false);}

					if(data[0].cph4 == 1){$('#cph4').prop('checked', true);}
					else{$('#cph4').prop('checked', false);}

					if(data[0].cph5 == 1){$('#cph5').prop('checked', true);}
					else{$('#cph5').prop('checked', false);}

					$('#chv1').val(data[0].chv1);
                    
					if(data[0].chv2 == 1){$('#chv2').prop('checked', true);}
					else{$('#chv2').prop('checked', false);}

					if(data[0].chv3 == 1){$('#chv3').prop('checked', true);}
					else{$('#chv3').prop('checked', false);}

					if(data[0].chv4 == 1){$('#chv4').prop('checked', true);}
					else{$('#chv4').prop('checked', false);}

					/* if(data[0].ccoh1 == 1){$('#ccoh1').prop('checked', true);}
					else{$('#ccoh1').prop('checked', false);}

					if(data[0].ccoh2 == 1){$('#ccoh2').prop('checked', true);}
					else{$('#ccoh2').prop('checked', false);}

					if(data[0].ccoh3 == 1){$('#ccoh3').prop('checked', true);}
					else{$('#ccoh3').prop('checked', false);}

					if(data[0].ccoh4 == 1){$('#ccoh4').prop('checked', true);}
					else{$('#ccoh4').prop('checked', false);}

					if(data[0].ccoh5 == 1){$('#ccoh5').prop('checked', true);}
					else{$('#ccoh5').prop('checked', false);}

					if(data[0].ccoh6 == 1){$('#ccoh6').prop('checked', true);}
					else{$('#ccoh6').prop('checked', false);}

					if(data[0].ccoh7 == 1){$('#ccoh7').prop('checked', true);}
					else{$('#ccoh7').prop('checked', false);}

					if(data[0].ccoh8 == 1){$('#ccoh8').prop('checked', true);}
					else{$('#ccoh8').prop('checked', false);}

					if(data[0].ccoh9 == 1){$('#ccoh9').prop('checked', true);}
					else{$('#ccoh9').prop('checked', false);}

					if(data[0].ccoh10 == 1){$('#ccoh10').prop('checked', true);}
					else{$('#ccoh10').prop('checked', false);} */
                    
					$('#observaciones').val(data[0].observaciones);
				}
			});
		}
		function buscarCriterio(idcriterio) {
			$('#id_criterio').val(idcriterio);
			$('#titulo').text('Editar Registro');
			$('#btnNuevoCriterio').css('display', 'none');
			$('#btnEditarCriterio').css('display', 'initial');
			$('#btnCancelarEdicion').css('display', 'initial');
			$('#registrosPesos').css('display', 'none');
			$('#edicionPesos').css('display', '');
			console.log('consulta: ', idcriterio);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idPeso: idcriterio
				},
				success: function(data) {
					console.log('datos recibidos: ',data);
					$('#id_recepcion_pesos').val(data[0].id_recepcion_pesos);
					$('#turnoEdicion').val(data[0].turno);
					$('#estomago1Edicion').val(data[0].estomago1);
					$('#estomago2Edicion').val(data[0].estomago2);
					$('#pierna1Edicion').val(data[0].piernas1);
					$('#pierna2Edicion').val(data[0].piernas2);
					$('#temperaturaEdicion').val(data[0].temperatura);
				}
			});
		}

		function buscarCriterioCerdo(idcriterio) {
			$('#id_criterio').val(idcriterio);
			$('#titulo').text('Editar Registro');
			$('#btnNuevoCriterioCerdo').css('display', 'none');
			$('#btnEditarCriterioCerdo').css('display', 'initial');
			$('#btnCancelarEdicionCerdo').css('display', 'initial');
			console.log('consulta: ', idcriterio);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idPeso: idcriterio
				},
				success: function(data) {
					console.log('datos recibidos: ',data);
					$('#id_recepcion_pesos').val(data[0].id_recepcion_pesos);
					$('#turno').val(data[0].turno);
					$('#turno').prop('disabled', true);
					$('#peso').val(data[0].estomago1);
					$('#temperaturap').val(data[0].temperatura);
				}
			});
		}

		function bloquearEdicion(idBloquear) {
			 $.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					idBloquear: idBloquear
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
				}
			});
		}
		$('#btnEditProveedor').click(function() {
			validacionesF = validaciones();
			if (validacionesF == ""){	
			    console.log('validaciones: ',validacionesF);	
			    datosEdit = {
					"id_guia": $('#id_guia').val(),
				    "fecharec": $('#fecharec').val(),
				    "fechasac": $('#fechasac').val(),
				    "tipo": $('#tipo').val(),
				    "remision": $('#remision').val(),
				    "canales": $('#canales').val(),
					"despacho": $('#despacho').val(),
				    "consecutivog": $('#consecutivog').val(),
				    "responsable": $('#responsable').val(),
				    "beneficio": $('#beneficio').val(),
				    "destino": $('#destino').val(),
				    "conductor": $('#conductor').val(),
				    "placa": $('#placa').val(),
				    "lotep": $('#lotep').val(),
					"ica": $('#ica').val(),
				    "guiat": $('#guiat').val(),
				    "certificadoc": $('#certificadoc').val(),
					"chv1": $('#chv1').val(),
				    "cph1": $('#cph1').is(':checked') ? 1 : 0,
				    "cph2": $('#cph2').is(':checked') ? 1 : 0,
				    "cph3": $('#cph3').is(':checked') ? 1 : 0,
				    "cph4": $('#cph4').is(':checked') ? 1 : 0,
				    "cph5": $('#cph5').is(':checked') ? 1 : 0,
				    "chv2": $('#chv2').is(':checked') ? 1 : 0,
				    "chv3": $('#chv3').is(':checked') ? 1 : 0,
				    "chv4": $('#chv4').is(':checked') ? 1 : 0,
				    "ccoh1": $('#ccoh1').is(':checked') ? 1 : 0,
				    "ccoh2": $('#ccoh2').is(':checked') ? 1 : 0,
				    "ccoh3": $('#ccoh3').is(':checked') ? 1 : 0,
				    "ccoh4": $('#ccoh4').is(':checked') ? 1 : 0,
				    "ccoh5": $('#ccoh5').is(':checked') ? 1 : 0,
				    "ccoh6": $('#ccoh6').is(':checked') ? 1 : 0,
				    "ccoh7": $('#ccoh7').is(':checked') ? 1 : 0,
				    "ccoh8": $('#ccoh8').is(':checked') ? 1 : 0,
				    "ccoh9": $('#ccoh9').is(':checked') ? 1 : 0,
				    "ccoh10": $('#ccoh10').is(':checked') ? 1 : 0,
				    "observaciones": $('#observaciones').val()
                };			
			console.log('datos a actualizar:', datosEdit);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosEdit: datosEdit
				},
				success: function(data) {
					$('#jtable').DataTable().ajax.reload();
					$('#fecharec').val('');
					$('#fechasac').val('');
					$('#tipo').val('');
					$('#remision').val('');
					$('#canales').val('');
					$('#consecutivog').val('');
					$('#responsable').val('');
					$('#beneficio').val('');
					$('#destino').val('');
					$('#conductor').val('');
					$('#placa').val('');
					$('#ica').val('');
					$('#guiat').val('');
					$('#certificadoc').val('');
					$('#cph1').val('');
					$('#cph2').val('');
					$('#cph3').val('');
					$('#cph4').val('');
					$('#cph5').val('');
					$('#chv1').val('');
					$('#chv2').val('');
					$('#chv3').val('');
					$('#chv4').val('');
					$('#ccoh1').val('');
					$('#ccoh2').val('');
					$('#ccoh3').val('');
					$('#ccoh4').val('');
					$('#ccoh5').val('');
					$('#ccoh6').val('');
					$('#ccoh7').val('');
					$('#ccoh8').val('');
					$('#ccoh9').val('');
					$('#despacho').val('');
					$('#lotep').val('');
					$('#observaciones').val('');
					$("#modalNuevoProveedor").modal('hide');
					Swal.fire({
        				title: 'Recepción editada satisfactoriamente',
        				text: '',
        				icon: 'success',
        				timer: 1000, 
    					showConfirmButton: false
      				});
				}
			});
		  }
		});
		 function validaciones() {
				$('#fecharecE').css('display', 'none');
				$('#fechasacE').css('display', 'none');
				$('#tipoE').css('display', 'none');
				$('#despachoE').css('display', 'none');
				$('#canalesE').css('display', 'none');
				$('#consecutivogE').css('display', 'none');
				$('#responsableE').css('display', 'none');
				$('#beneficioE').css('display', 'none');
				$('#destinoE').css('display', 'none');
				$('#conductorE').css('display', 'none');
				$('#placaE').css('display', 'none');
				$('#icaE').css('display', 'none');
				$('#guiatE').css('display', 'none');
				$('#certificadocE').css('display', 'none');
				$('#chv1E').css('display', 'none');

				if ($("#fecharec").val() == null || $("#fecharec").val() == "") {
                	$('#fecharecE').css('display', 'block');
					return 'R';
                }
				if ($("#fechasac").val() == null || $("#fechasac").val() == "") {
					$('#fechasacE').css('display', 'block');
                    return 'R';
                }

				let fecharec = new Date($("#fecharec").val());
				let fechasac = new Date($("#fechasac").val());

				if (fechasac >= fecharec) {
    				$('#fechasacE').css('display', 'block').text('La fecha de beneficio no puede ser mayor o igual que la fecha de recepción');
    				return 'R';
				}

				if ($("#tipo").val() == null || $("#tipo").val() == "") {
					$('#tipoE').css('display', 'block');
                    return 'R';
                }
				if ($("#canales").val() == null || $("#canales").val() == "") {
					$('#canalesE').css('display', 'block');
                    return 'R';
                }
				if ($("#despacho").val() == null || $("#despacho").val() == "") {
					$('#despachoE').css('display', 'block');
                    return 'R';
                }
				if ($("#consecutivog").val() == null || $("#consecutivog").val() == "") {
					$('#consecutivogE').css('display', 'block');
                    return 'R';
                }
				if ($("#responsable").val() == null || $("#responsable").val() == "") {
					$('#responsableE').css('display', 'block');
                    return 'R';
                }
				if ($("#beneficio").val() == null || $("#beneficio").val() == "") {
					$('#beneficioE').css('display', 'block');
                    return 'R';
                }
				if ($("#destino").val() == null || $("#destino").val() == "") {
					$('#destinoE').css('display', 'block');
                    return 'R';
                }
				if ($("#conductor").val() == null || $("#conductor").val() == "") {
					$('#conductorE').css('display', 'block');
                    return 'R';
                }
				if ($("#placa").val() == null || $("#placa").val() == "") {
					$('#placaE').css('display', 'block');
                    return 'R';
                }
				if ($("#ica").val() == null || $("#ica").val() == "") {
					$('#icaE').css('display', 'block');
                    return 'R';
                }
				if ($("#guiat").val() == null || $("#guiat").val() == "") {
					$('#guiatE').css('display', 'block');
                    return 'R';
                }
				if ($("#chv1").val() == null || $("#chv1").val() == "") {
					$('#chv1E').css('display', 'block');
                    return 'R';
                }
                return "";
            }

			$('#btnEditarCriterio').click(function() {
			validacionesCri = validacionesC();
			//if (validacionesCri == "") {
			if (1 == 1) {
				infoPesoEdit = {
					"id_recepcion_pesos": $('#id_recepcion_pesos').val(),
					"estomago1Edicion": $('#estomago1Edicion').val(),
					"estomago2Edicion": $('#estomago2Edicion').val(),
					"pierna1Edicion": $('#pierna1Edicion').val(),
					"pierna2Edicion": $('#pierna2Edicion').val(),
					"temperaturaEdicion": $('#temperaturaEdicion').val(),
				};
				console.log('datos enviados:',infoPesoEdit);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoPesoEdit
					},
					success: function(data) {
						/* $('#btnNuevoCriterio').css('display', 'initial');
						$('#btnEditarCriterio').css('display', 'none');*/
						$('#turnoE').css('display', 'none');
						$('#btnNuevoCriterio').css('display', 'initial');
						$('#btnEditarCriterio').css('display', 'none');
						$('#btnCancelarEdicion').css('display', 'none');
						$('#registrosPesos').css('display', '');
						$('#edicionPesos').css('display', 'none');
						$('#estomago1Edicion').val('');
						$('#estomago2Edicion').val('');
						$('#pierna1Edicion').val('');
						$('#pierna2Edicion').val('');
						$('#turnoE').val('');
						$('#temperaturaEdicion').val(''); 
						$('#jtableCriterio').DataTable().ajax.reload();
						Swal.fire({
        					title: 'Turno modificado satisfactoriamente',
        					text: '',
        					icon: 'success',
        					timer: 1000, 
    						showConfirmButton: false
      					});
					}
				});
			}
		});

		$('#btnEditarCriterioCerdo').click(function() {
			validacionesCri = validacionesC();
			//if (validacionesCri == "") {
			if (1 == 1) {
				infoPesoEditCerdo = {
					"id_recepcion_pesos": $('#id_recepcion_pesos').val(),
					"peso": $('#peso').val(),
					"temperaturap": $('#temperaturap').val(),
				};
				console.log('datos enviado cerdos:',infoPesoEditCerdo);
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: 'controlador/controlador.php',
					data: {
						infoPesoEditCerdo
					},
					success: function(data) {
						/* $('#btnNuevoCriterio').css('display', 'initial');
						$('#btnEditarCriterio').css('display', 'none');*/
						$('#turnoE').css('display', 'none');
						$('#btnNuevoCriterioCerdo').css('display', 'initial');
						$('#btnEditarCriterioCerdo').css('display', 'none');
						$('#btnCancelarEdicionCerdo').css('display', 'none');
						$('#registrosPesos').css('display', '');
						$('#turnoE').val('');
						$('#turno').prop('disabled', false);
						$('#btnNuevoCriterioCerdo').css('display', 'initial');
						$('#registrosPesosCerdo').css('display', '');
						$('#edicionPesosCerdo').css('display', 'none');

						$('#turno').val('');
						$('#peso').val('');
						$('#temperaturap').val('');
						$('#jtableCriterio').DataTable().ajax.reload();
						Swal.fire({
        					title: 'Turno modificado satisfactoriamente',
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
			console.log('validando cedula');
        	var cedula = $("#cedula").val();
        	if (!($("#cedula").val() == null || $("#cedula").val() == "")) {
            	console.log("Datos enviados a controlador:", cedula);
            	$.ajax({
                	type: 'POST',
                	url: 'controlador/controlador.php',
                	data: {
                    	cedula
                	},
                success: function(data) {
                    if (data.length < 5) {
                        /* Swal.fire({
                        	title: 'Advertencia',
                        	text: 'La cedula ingresada ya esta registrada en el sistema',
                        	icon: 'warning',
                        	confirmButtonText: 'OK'
                    	}); */
						
						$('#cedulaExiste').val(cedula);
						$('#cedulaE').css('display', 'block').text('La cedula ya esta registrada en el sistema');
                    } else {
						$('#cedulaExiste').val('');
						$('#cedulaE').css('display', 'none');
                    }
                }
            });
        }
    	});
	function validacionesC(raza) {
		$('#turnoE').css('display', 'none');
		$('#parteE').css('display', 'none');
		$('#pesoE').css('display', 'none');

		if ($("#turno").val() == null || $("#turno").val() == "") {
			$('#turnoE').css('display', 'block');
            return 'R';
        }
		if(raza!='CERDO'){
			if ($("#parte").val() == null || $("#parte").val() == "") {
				$('#parteE').css('display', 'block');
            	return 'R';
            }
		}	
		if ($("#peso").val() == null || $("#peso").val() == "") {
			$('#pesoE').css('display', 'block');
            return 'R';
        }
        return "";
    }
	function validacionesCon() {
		
		$('#cedulaE').css('display', 'none');
		$('#nombresConductorE').css('display', 'none');

		if ($("#cedula").val() == null || $("#cedula").val() == "" || $("#cedulaExiste").val() != "") {
			$('#cedulaE').css('display', 'block');
            return 'R';
        }	
		if ($("#nombresConductor").val() == null || $("#nombresConductor").val() == "") {
			$('#nombresConductorE').css('display', 'block');
            return 'R';
        }
        return "";
    }

	function validacionesPla() {
		
		$('#placavE').css('display', 'none');

		if ($("#placav").val() == null || $("#placav").val() == "") {
			$('#placavE').css('display', 'block');
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