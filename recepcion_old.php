<?php
session_start();
error_reporting(0);
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
  <title>Planta de Desposte | Despacho</title>
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
            width: 100px; 
        }
		#photo-preview {
            display: flex;
            flex-wrap: wrap;
        }
        #photo-preview img {
            margin: 10px;
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
 <?php include_once('menu.php');?>
 <?php include_once('menuizquierda.php');?> 
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Recepción de canales bovinas / porcinas</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">
                <center>
                  <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"
                   title="Agregar Nueva Guia" id="crearProveedor">AGREGAR LOTE DE RECEPCIÓN</a>
                </center>
              <table id="jtable" class="table table-striped table-bordered table-hover datatable">
              <thead>
                    <tr>
                        <th>Consecutivo<br>Tipo</th>
                        <th>Fecha<br>Remisión</th>
                        <th>Planta Beneficio<br>Fecha Sacrificio</th>
                        <th>Canales<br>Lote</th>
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
</body>

<div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
    <div class="modal-dialog modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

			<div class="modal-content">
        <div class="modal-header" style="display: block;text-align: center;">
          <label style="text-align: center;font-weight: bold;" id="titulo">LOTE DE RECEPCIÓN</label>
          </div>
				<div class="modal-body">
                <form method="POST" id="criteriosForm">
						<div class="row">
							<div class="col-lg-3">
								Fecha de Recepción
								<div class="form-group label-floating" id="fechaExpDiv">
									<input type="date" class="form-control" autocomplete="off" id="fecharec" value="<?php echo $fecha_actual; ?>" name="fecharec" placeholder="Ingrese fecha Recepción" >
								</div>
								<div class="alert alert-danger" role="alert" id="fecharecE" style="display: none">
									Debes ingresar fecha de recepción
								</div>
							</div>
							<div class="col-lg-3">
								Fecha de Beneficio
								<div class="form-group label-floating" id="fechaExpDiv">
									<input type="date" class="form-control" autocomplete="off" id="fechasac" name="fechasac" placeholder="Ingrese fecha Sacrificio" >
								</div>
								<div class="alert alert-danger" role="alert" id="fechasacE" style="display: none">
									Debes ingresar fecha de sacrificio
								</div>
							</div>

							<div class="col-lg-3">
								Especie
								<div class="form-group label-floating" id="telefonoDiv">
									<select class="form-control" id="tipo" name="tipo">
										<option value="">Seleccione Especie</option>
										<option value="CERDO">CERDO</option>
										<option value="RES">RES</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="tipoE" style="display: none">
									Debes seleccionar la especie a despachar
								</div>
							</div>

							<div class="col-lg-3">
								Nro Remisión
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="remision" name="remision" placeholder="Ingrese Remisión">
									<input type="hidden" id="id_guia">
								</div>
								<div class="alert alert-danger" role="alert" id="remisionE" style="display: none">
									Debes ingresar el nro remisión
								</div>
							</div>

							<div class="col-lg-2">
								Canales
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" type="number" name="canales" autocomplete="off" id="canales" placeholder="Ingrese el Nro de Canales">
								</div>
								<div class="alert alert-danger" role="alert" id="canalesE" style="display: none">
									Debe ingresar la cantidad de Canales
								</div>
							</div>

							<div class="col-lg-2">
								Recibo
								<select class="form-control" id="despacho" name="despacho">
									<option value="">Seleccione Orden de Recibo</option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
									<option value="E">E</option>
									<option value="F">F</option>
								</select>
								<div class="alert alert-danger" role="alert" id="despachoE" style="display: none">
									Debe seleccionar el orden de recibo
								</div>
							</div>

							<div class="col-lg-2">
								<center>Lote</center>
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="consecutivog" name="consecutivog">
									<input type="hidden" id="id_guia">
								</div>
								<div class="alert alert-danger" role="alert" id="consecutivogE" style="display: none">
									Debes ingresar el lote a despachar
								</div>
							</div>

							<div class="col-lg-6">
								Responsable Recepción
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="responsable" name="responsable">
										<option value="">Seleccione Responsable de Despacho</option>
										<?php foreach ($listaResponsables as $perm) : ?>
											<option value="<?php echo $perm['cedula'] ?>">
												<?php echo $perm['cedula'] . " - " . $perm['nombres'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="responsableE" style="display: none">
									Debe seleccinar el eesponsable del despacho
								</div>
							</div>

							<div class="col-md-6">
								Origen
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="beneficio" name="beneficio">
										<option value="">Seleccione Planta de Beneficio</option>
										<?php foreach ($listaOrigen as $perm) : ?>
											<option value="<?php echo $perm['id'] ?>">
												<?php echo $perm['nit'] . " - " . $perm['sede'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="beneficioE" style="display: none">
									Debe seleccinar la planta de beneficio
								</div>
							</div>
							
							<div class="col-md-6">
								Destino
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="destino" name="destino">
										<option value="">Seleccione Destino de Despacho</option>
										<?php foreach ($listaDestinos as $perm) : ?>
											<option value="<?php echo $perm['id'] ?>">
												<?php echo $perm['empresa'] . " - " . $perm['sede'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="destinoE" style="display: none">
									Debe seleccinar el destino del despacho
								</div>
							</div>

							<div class="col-md-6">
								Nombre del Conductor
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="conductor" name="conductor">
										<option value="">Seleccione el Conductor de Despacho</option>
										<?php foreach ($listaConductores as $perm) : ?>
											<option value="<?php echo $perm['cedula'] ?>">
												<?php echo $perm['empresa'] . ":" .$perm['cedula'] . "-" . $perm['nombres'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="conductorE" style="display: none">
									Debe seleccinar el conductor
								</div>
							</div>

							<div class="col-md-6">
								Placa Vehiculo
								<div class="form-group label-floating" id="conseGuiaDiv">
									<select class="form-control" id="placa" name="placa">
										<option value="">Seleccione la Placa del Vehiculo</option>
										<?php foreach ($listaPlacas as $perm) : ?>
											<option value="<?php echo $perm['placa'] ?>">
												<?php echo $perm['empresa'].":".$perm['placa'] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="placaE" style="display: none">
									Debe seleccinar la placa del vehiculo
								</div>
							</div>
							<div class="col-md-12 mt-3">
								<center>
								  <label style="text-align: center;font-weight: bold;" id="titulo">INFORMACIÓN GUÍAS DE TRANSPORTE Y CERTIFCADO DE CALIDAD</label>
								</center>
							</div>
							<div class="col-md-3">
								Lote Beneficio
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="lotep" name="lotep" placeholder="Ingrese Lote Planta">
								</div>
							</div>
							<div class="col-md-3">
								N° Guía de ICA
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="ica" name="ica" placeholder="Ingrese Nro Guia de ICA">
								</div>
								<div class="alert alert-danger" role="alert" id="icaE" style="display: none">
									Debes ingresar el n° guía de ICA
								</div>
							</div>
							<div class="col-md-3" style="font-size: 13px">
							   Guía de Transp. Carne en C
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="guiat" name="guiat" placeholder="Ingrese Guia de Transporte">
								</div>
								<div class="alert alert-danger" role="alert" id="guiatE" style="display: none">
									Debes ingresar el n° Guía de transporte de carne en canal
								</div>
							</div>
							<div class="col-md-3">
							Certificado de Calidad					
								<div class="form-group label-floating" id="codigoAutoDiv">
									<input type="text" class="form-control" autocomplete="off" id="certificadoc" name="certificadoc" placeholder="Ingrese Certificado de Calidad">
								</div>
							</div>
							
							<div class="col-md-12 mt-3">
								<center>
								  <label style="text-align: center;font-weight: bold;" id="titulo">CUMPLIMIENTO PRÁCTICAS HIGIÉNICAS PERSONAL MANIPULADOR</label>
								</center>
							</div>
							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="cph1" name="cph1" value="1">&nbsp;
                                <label for="cph1">Usa cofia y tapabocas</label>
							</div>

							<div class="col-md-6 custom-margin">					
								<input type="checkbox" class="form-check-input" id="cph2" name="cph2" value="1">&nbsp;
                                <label for="cph2">Capa limpia y en buen estado</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="cph3" name="cph3" value="1">&nbsp;
                                <label for="cph3">Botas limpias y en buen estado</label>
							</div>

							<div class="col-md-6 custom-margin">
									<input type="checkbox" class="form-check-input" id="cph4" name="cph4" value="1">&nbsp;
                                    <label for="cph4">Uñas cortas limpias y sin esmalte</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="cph5" name="cph5" value="1">&nbsp;
                                <label for="cph5">No usa accesorios</label>
							</div>

							<div class="col-md-12 mt-3">
								<center>
								  <label style="text-align: center;font-weight: bold;" id="titulo">CUMPLIMIENTO CONDICIONES HIGIÉNICO SANITARIAS DEL VEHÍCULO DE TRANSPORTE</label>
								</center>
							</div>
							
							<div class="col-md-6 custom-margin">
								Temperatura del Vehiculo
								<div class="form-group label-floating" id="conseGuiaDiv">
                				<input class="form-control" type="number" name="chv1" autocomplete="off" id="chv1" placeholder="Ingrese Temperatura">
								</div>
								<div class="alert alert-danger" role="alert" id="chv1E" style="display:none">
									Debe ingresar la Temperatura del Vehiculo
								</div>
							</div>

							<div class="col-md-6 custom-margin">					
								<input type="checkbox" class="form-check-input" id="chv2" name="chv2" value="1">&nbsp;
                                <label for="chv2">¿Vehículo limpio y ausente de plagas?</label>
							</div>

							<div class="col-md-6 custom-margin">
								<input type="checkbox" class="form-check-input" id="chv3" name="chv3" value="1">&nbsp;
                                <label for="chv3" style="font-size: 14px">¿Transporta sustancias químicas con las canales?</label>
							</div>

							<div class="col-md-6 custom-margin">
									<input type="checkbox" class="form-check-input" id="chv4" name="chv4" value="1">&nbsp;
                                    <label for="chv4">¿Transporta canales de una sola especie?</label>
							</div>

							<div class="col-md-12 mt-3">
								<center>
								  <label style="text-align: center;font-weight: bold;" id="titulo">CUMPLIMIENTO CARACTERISTICAS ORGANOLÉPTICAS E HIGIÉNICO SANITARIAS DE LAS CANALES</label>
								</center>
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh1" name="ccoh1" value="1">&nbsp;
                                <label id="ccoh1t" for="ccoh1">Color</label>	
							</div>

							<div class="col-md-4 custom-margin">					
								<input type="checkbox" class="form-check-input" id="ccoh2" name="ccoh2" value="1">&nbsp;
                                <label id="ccoh2t" for="ccoh2">Sin Pelo</label>	
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh3" name="ccoh3" value="1">&nbsp;
                                <label id="ccoh3t" for="ccoh3">Sin Contenido Ruminal</label>
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh4" name="ccoh4" value="1">&nbsp;
                                <label id="ccoh4t" for="ccoh4">Sin Materia Fecal</label>
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh5" name="ccoh5" value="1">&nbsp;
                                <label id="ccoh5t" for="ccoh5">Sin Medula Espinal</label>	
							</div>

							<div class="col-md-4 custom-margin">					
								<input type="checkbox" class="form-check-input" id="ccoh6" name="ccoh6" value="1">&nbsp;
                                <label id="ccoh6t" for="ccoh6">Sin Nuches</label>	
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh7" name="ccoh7" value="1">&nbsp;
                                <label id="ccoh7t" for="ccoh7">Sin Garrapatas</label>
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh8" name="ccoh8" value="1">&nbsp;
                                <label id="ccoh8t" for="ccoh8">Sin coágulos ni hematomas</label>
							</div>

							<div class="col-md-4 custom-margin">
								<input type="checkbox" class="form-check-input" id="ccoh9" name="ccoh9" value="1">&nbsp;
                                <label id="ccoh9t" for="ccoh9">Sin leche</label>
							</div>

							<div class="col-md-4 custom-margin" id="dermatitis-div" style="display:none">
								<input type="checkbox" class="form-check-input" id="ccoh10" name="ccoh10" value="1">&nbsp;
                                <label id="ccoh10t" for="ccoh10">Sin dermatitis</label>
							</div>
							
						</div>		
							<div class="col-md-12">
								Observaciones
								<div class="form-group label-floating" id="observacionesDiv">
									<input type="text" class="form-control" autocomplete="off" id="observaciones" name="observaciones" placeholder="Ingrese observaciones">
								</div>
							</div>

						</div>
					</form>
					<div class="row">
						<div class="col-md-12" style="text-align:center; margin-bottom:10px">
							<button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;" rel="tooltip" data-placement="bottom" title="Guardar Guia" id="btnNuevoProveedor">Guardar</button>
							<button class="btn btn-primary" style="margin-bottom: 30px; margin-top: 10px;display:none" rel="tooltip" data-placement="bottom" title="Editar Guia" id="btnEditProveedor">Editar</button>
							<button class="btn btn-danger" style="margin-bottom: 30px; margin-top: 10px;" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- formulario res --> 
<div class="modal fade" id="modalCriterios" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #00000042 !important;">
	<div class="modal-dialog modal-dialog modal-dialog-centered modal-dialog modal-fullscreen modal-dialog-scrollable">
		<div class="modal-content">
        	<div class="modal-header" style="display: block;text-align: center;">
                <h5 class="modal-title" id="titulo2" style="font-weight: bold; text-align: center;"></h5>
            </div>
    		<div class="modal-body">
				<form method="POST" id="criteriosForm">
				<!-- inicia -->
				<input type="hidden" name="id" id="id">
					<div id="registrosPesos">
						 <div class="row">
							<div class="col-md-3">
								Turno
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" min="1" max="999" maxlength="3" type="number" name="turno" autocomplete="off" id="turno" placeholder="Ingrese el Turno">
								</div>
								<div class="alert alert-danger" role="alert" id="turnoE" style="display: none">
									Debe ingresar el turno
								</div>
							</div>
							<div class="col-md-3">
								Parte
								<div class="form-group label-floating" id="telefonoDiv">
									<select class="form-control" id="parte" name="parte">
										<option value="">Seleccione Parte</option>
										<option value="ESTOMAGO">ESTOMAGO</option>
										<option value="PIERNA">PIERNA</option>
									</select>
								</div>
								<div class="alert alert-danger" role="alert" id="parteE" style="display: none">
									Debes seleccionar la parte a registrar
								</div>
							</div>
							<div class="col-md-3">
								Peso
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="peso" autocomplete="off" id="peso" placeholder="Ingrese el Peso de la parte">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar el peso de la parte
								</div>
							</div>
							<div class="col-md-3">
								Temperatura
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="temperaturap" autocomplete="off" id="temperaturap" placeholder="Ingrese la temperatura de la parte">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar la temperatura de la parte
								</div>
							</div>
						
						</div>
						<div class="row">
							<div class="col-md-6">
								Observación
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" min="1" max="999" maxlength="3" type="number" name="observcionPeso" autocomplete="off" id="observacionPeso" placeholder="Ingrese Observación">
								</div>
							</div>
							<div class="col-md-3">
								Fotografía
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" min="1" max="999" maxlength="3" type="file" name="foto" autocomplete="off" id="foto" placeholder="Seleccione Archivo">
								</div>
							</div>
							<div class="col-md-3">
								<div id="photo-preview"></div>
							</div>	
					   </div>
						
					<div class="text-center mt-3 mb-3">
                    	<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Agregar Item" id="btnNuevoCriterio">Guardar</button>
                    	<button class="btn btn-danger" data-placement="bottom" id="btnCerrarCriterios">Cerrar</button>
             		</div>

					</div>
					<div id="edicionPesos" style="display: none;">
						 <div class="row">
							<div class="col-md-2">
								Turno
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input class="form-control" min="1" max="999" maxlength="3" type="number" name="turnoEdicion" autocomplete="off" id="turnoEdicion" placeholder="Ingrese el Turno" disabled>
									<input type="hidden" name="id_recepcion_pesos" autocomplete="off" id="id_recepcion_pesos">
								</div>
								<div class="alert alert-danger" role="alert" id="turnoE" style="display: none">
									Debe ingresar el turno
								</div>
							</div>
							
							<div class="col-md-2">
								Peso Estomago 1
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="estomago1Edicion" autocomplete="off" id="estomago1Edicion" placeholder="Ingrese el Peso de la parte">
								</div>
							</div>

							<div class="col-md-2">
								Peso Estomago 2
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="estomago2Edicion" autocomplete="off" id="estomago2Edicion" placeholder="Ingrese el Peso de la parte">
								</div>
							</div>

							<div class="col-md-2">
								Peso Pierna 1
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="pierna1Edicion" autocomplete="off" id="pierna1Edicion" placeholder="Ingrese el Peso de la parte">
								</div>
							</div>

							<div class="col-md-2">
								Peso Pierna 2
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="pierna2Edicion" autocomplete="off" id="pierna2Edicion" placeholder="Ingrese el Peso de la parte">
								</div>
							</div>

							<div class="col-md-2">
								Temperatura
								<div class="form-group label-floating" id="conseGuiaDiv">
									<input type="number" class="form-control" name="temperaturaEdicion" autocomplete="off" id="temperaturaEdicion" placeholder="Ingrese la temperatura de la parte">
								</div>
								<div class="alert alert-danger" role="alert" id="pesoE" style="display: none">
									Debe ingresar la temperatura de la parte
								</div>
							</div>
						</div>
						<div class="text-center mt-3 mb-3">
							<button class="btn btn-primary" rel="tooltip" data-placement="bottom" title="Editar Item" id="btnEditarCriterio" style="display: none;">Editar</button>
							<button class="btn btn-warning" title="Cancelar Edicion" id="btnCancelarEdicion" style="display: none;">Cancelar</button>
                    		<button class="btn btn-danger" data-placement="bottom" id="btnCerrarCriterios">Cerrar</button>
						</div>	
					</div>	
				<!-- termina -->
				</form>
				<div class="table-responsive">
                    <table id="jtableCriterio" class="table table-striped table-bordered table-hover datatable">
                        <thead>
                        <tr>
						    <th>#</th>
							<th>Turno</th>
							<th>Estomago 1</th>
							<th>Estomago 2</th>
							<th>Piernas 1</th>
							<th>Piernas 2</th>
							<th>Total</th>
							<th>Temperatura</th>
							<th>Hora</th>
							<th>Acción</th>
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
  <script src="assets/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/jquery.datatables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
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
			$('#jtable').css('width', '100%');
            $('#jtable_filter input, #jtable_length select').addClass('form-control');
			
			$('#foto').on('change', function(event) {
                const files = event.target.files;
                const preview = $('#photo-preview');
                const formData = new FormData();
                const userId = $('#user-id').val();

                preview.empty();

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const img = $('<img>').attr('src', URL.createObjectURL(file));
                    preview.append(img);
                    formData.append('photos[]', file);
                }

                // Añadir el campo numérico al FormData
                formData.append('user-id', userId);

                $.ajax({
                    url: 'upload.php',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.status === "success") {
                            alert('Fotos guardadas exitosamente');
                        } else {
                            alert('Error al guardar las fotos: ' + data.message);
                        }
                    },
                    error: function() {
                        alert('Error al guardar las fotos');
                    }
                });
            });

			$('#tipo').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'CERDO') {
					$('#ccoh1t').text('Olor');
					$('#ccoh2t').text('Color');
					$('#ccoh3t').text('Sin Pelo');
					$('#ccoh4t').text('Contenido estomacal');
					$('#ccoh5t').text('Sin Materia Fecal');
					$('#ccoh6t').text('Sin coágulos ni hematomas');
					$('#ccoh7t').text('Sin Riñones');
					$('#ccoh8t').text('Sin Leche');
					$('#ccoh9t').text('Sin Fracturas');
					$('#dermatitis-div').css('display', 'block');
					$('#ccoh1').prop('checked', false);
					$('#ccoh2').prop('checked', false);
					$('#ccoh3').prop('checked', false);
					$('#ccoh4').prop('checked', false);
					$('#ccoh5').prop('checked', false);
					$('#ccoh6').prop('checked', false);
					$('#ccoh7').prop('checked', false);
					$('#ccoh8').prop('checked', false);
					$('#ccoh9').prop('checked', false);
					$('#ccoh10').prop('checked', false); 
                } else {
					$('#ccoh1t').text('Color');
					$('#ccoh1').prop('checked', false);
					$('#ccoh2t').text('Sin Pelo');
					$('#ccoh3t').text('Sin Contenido Ruminal');
					$('#ccoh4t').text('Sin Materia Fecal');
					$('#ccoh5t').text('Sin Medula Espinal');
					$('#ccoh6t').text('Sin Nuches');
					$('#ccoh7t').text('Sin Garrapatas');
					$('#ccoh8t').text('Sin coágulos ni hematomas');
					$('#ccoh9t').text('Sin leche');
					$('#dermatitis-div').css('display', 'none');
					$('#ccoh1').prop('checked', false);
					$('#ccoh2').prop('checked', false);
					$('#ccoh3').prop('checked', false);
					$('#ccoh4').prop('checked', false);
					$('#ccoh5').prop('checked', false);
					$('#ccoh6').prop('checked', false);
					$('#ccoh7').prop('checked', false);
					$('#ccoh8').prop('checked', false);
					$('#ccoh9').prop('checked', false);
					$('#ccoh10').prop('checked', false); 
                }
            });
		
			function generarCodigo() {
                const tipo = $('#tipo').val();
                const despacho = $('#despacho').val();
                if (tipo && despacho) {
                    const tipoCodigo = tipo === 'CERDO' ? '2' : '1';
                    const fecha = new Date();
                    const year = fecha.getFullYear();
                    const ultimosDosDigitos = year.toString().slice(-2);
                    const semana = ("0" + fecha.getWeek()).slice(-2); // Función getWeek más abajo
                    const diaSemana = fecha.getDay();
                    const codigo = tipoCodigo + ultimosDosDigitos + semana + diaSemana + despacho;
                    $('#consecutivog').val(codigo);
                } else {
                    $('#consecutivog').val(''); // Clear the field if inputs are not selected
                }
            }

            // Función para obtener la semana del año
            Date.prototype.getWeek = function() {
                const onejan = new Date(this.getFullYear(), 0, 1);
                return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
            }

            $('#tipo, #despacho').change(generarCodigo);
		
		});

		function eliminarItem(id_item_proveedor) {
			const datosEPeso = {
				id_item_proveedor: id_item_proveedor
			};
			console.log('datos para eliminar: ',datosEPeso);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosEPeso: datosEPeso
				},
				success: function(data) {
					$('#jtableCriterio').DataTable().ajax.reload();
				}
			});
		}

		function abrirModal(consecutivoRecepcion, status, tipo) {
			$('#titulo2').text('Consecutivo Recepcion: ' + consecutivoRecepcion);
			if((status==1) || (tipo == 0)){
				$('#btnNuevoCriterio').css('display', 'initial');
				$('#btnEditarCriterio').css('display', 'none');
			}else{
				$('#btnNuevoCriterio').css('display', 'none');
				$('#btnEditarCriterio').css('display', 'none');
			}
 			$('#turno').val('');
			$('#parte').val('');
			$('#peso').val('');
 		}

		 function abrirModalC(consecutivoRecepcion, status, tipo) {
			$('#titulo2').text('Consecutivo Recepcion: ' + consecutivoRecepcion);
			if((status==1) || (tipo == 0)){
				$('#btnNuevoCriterioC').css('display', 'initial');
				$('#btnEditarCriterioC').css('display', 'none');
			}else{
				$('#btnNuevoCriterioC').css('display', 'none');
				$('#btnEditarCriterioC').css('display', 'none');
			}
 			$('#turnoC').val('');
			$('#parteC').val('');
			$('#pesoC').val('');
 		}

		function buscarItems(id_recepcion) {
			$('#id').val(id_recepcion);
			console.log('aqui: ',id_recepcion);
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
					url: "tablas/tablaPesos.php",
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
};

function buscarItemsC(id_recepcion) {
			$('#id').val(id_recepcion);
			console.log('aqui: ',id_recepcion);
			$('#jtableCriterioC').dataTable({
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
					url: "tablas/tablaPesosC.php",
					type: "post",
					data: {
						recepcionC: id_recepcion
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
};


/* $('#btnCancelarEdicion').click(function() {
    $('#btnNuevoCriterio').css('display', 'initial');
	$('#btnEditarCriterio').css('display', 'none');
	$('#btnCancelarEdicion').css('display', 'none');
	$('#registrosPesos').css('display', '');
	$('#edicionPesos').css('display', 'none');
}); */


$('#btnNuevoCriterio').click(function() {
    validacionesCri = validacionesC();
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
                    $('#turno').val('');
                    $('#parte').val('');
                    $('#peso').val('');
					$('#temperaturap').val('');
					$('#jtableCriterio').DataTable().ajax.reload();

                    Swal.fire({
                        title: 'Peso registrado',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
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
			$('#responsable').val('');
			$('#beneficio').val('');
			$('#destino').val('');
			$('#conductor').val('');
			$('#placa').val('');
			$('#ica').val('');
			$('#guiat').val('');
			$('#certificadoc').val('');
			$('#cph1').prop('checked', false);
			$('#cph2').prop('checked', false);
			$('#cph3').prop('checked', false);
			$('#cph4').prop('checked', false);
			$('#cph5').prop('checked', false);
			$('#chv1').val('');
			$('#chv2').prop('checked', false);
			$('#chv3').prop('checked', false);
			$('#chv4').prop('checked', false);

			$('#ccoh1').prop('checked', false);
			$('#ccoh2').prop('checked', false);
			$('#ccoh3').prop('checked', false);
			$('#ccoh4').prop('checked', false);
			$('#ccoh5').prop('checked', false);
			$('#ccoh6').prop('checked', false);
			$('#ccoh7').prop('checked', false);
			$('#ccoh8').prop('checked', false);
			$('#ccoh9').prop('checked', false);
			$('#ccoh10').prop('checked', false); 



			$('#despacho').val('');
			$('#lotep').val('');
			$('#observaciones').val('');

			$('#id_guia').val('');
			$('#titulo').text('Lote de Recepción');
			$('#btnNuevoProveedor').css('display', 'initial');
			$('#btnEditProveedor').css('display', 'none');
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
				cph1: $('#cph1').is(':checked') ? $('#cph1').val() : null,
				cph2: $('#cph2').is(':checked') ? $('#cph2').val() : null,
				cph3: $('#cph3').is(':checked') ? $('#cph3').val() : null,
				cph4: $('#cph4').is(':checked') ? $('#cph4').val() : null,
				cph5: $('#cph5').is(':checked') ? $('#cph5').val() : null,
				chv2: $('#chv2').is(':checked') ? $('#chv2').val() : null,
				chv3: $('#chv3').is(':checked') ? $('#chv3').val() : null,
				chv4: $('#chv4').is(':checked') ? $('#chv4').val() : null,
				ccoh1: $('#ccoh1').is(':checked') ? $('#ccoh1').val() : null,
				ccoh2: $('#ccoh2').is(':checked') ? $('#ccoh2').val() : null,
				ccoh3: $('#ccoh3').is(':checked') ? $('#ccoh3').val() : null,
				ccoh4: $('#ccoh4').is(':checked') ? $('#ccoh4').val() : null,
				ccoh5: $('#ccoh5').is(':checked') ? $('#ccoh5').val() : null,
				ccoh6: $('#ccoh6').is(':checked') ? $('#ccoh6').val() : null,
				ccoh7: $('#ccoh7').is(':checked') ? $('#ccoh7').val() : null,
				ccoh8: $('#ccoh8').is(':checked') ? $('#ccoh8').val() : null,
				ccoh9: $('#ccoh9').is(':checked') ? $('#ccoh9').val() : null,
				ccoh10: $('#ccoh10').is(':checked') ? $('#ccoh10').val() : null,
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
					$("#modalNuevoProveedor").modal('hide');
					Swal.fire({
        				title: 'Nueva recepción registrada satisfactoriamente',
        				text: '',
        				icon: 'success',
        				confirmButtonText: 'OK'
      				});
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
					
					data[0].cph1 == 1?$('#cph1').prop('checked', true):null;
                    data[0].cph2 == 1?$('#cph2').prop('checked', true):null;
                    data[0].cph3 == 1?$('#cph3').prop('checked', true):null;
                    data[0].cph4 == 1?$('#cph4').prop('checked', true):null;
                    data[0].cph5 == 1?$('#cph5').prop('checked', true):null;

					$('#chv1').val(data[0].chv1);
                    data[0].chv2 == 1?$('#chv2').prop('checked', true):null;
                    data[0].chv3 == 1?$('#chv3').prop('checked', true):null;
                    data[0].chv4 == 1?$('#chv4').prop('checked', true):null;

                    data[0].ccoh1 == 1?$('#ccoh1').prop('checked', true):null;
                    data[0].ccoh2 == 1?$('#ccoh2').prop('checked', true):null;
                    data[0].ccoh3 == 1?$('#ccoh3').prop('checked', true):null;
                    data[0].ccoh4 == 1?$('#ccoh4').prop('checked', true):null;
                    data[0].ccoh5 == 1?$('#ccoh5').prop('checked', true):null;
                    data[0].ccoh6 == 1?$('#ccoh6').prop('checked', true):null;
                    data[0].ccoh7 == 1?$('#ccoh7').prop('checked', true):null;
                    data[0].ccoh8 == 1?$('#ccoh8').prop('checked', true):null;
                    data[0].ccoh9 == 1?$('#ccoh9').prop('checked', true):null;
                    data[0].ccoh10 == 1?$('#ccoh10').prop('checked', true):null;
                    
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
			    datosRecepcion = {
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
				    "cph1": $('#cph1').is(':checked') ? $('#cph1').val() : null,
				    "cph2": $('#cph2').is(':checked') ? $('#cph2').val() : null,
				    "cph3": $('#cph3').is(':checked') ? $('#cph3').val() : null,
				    "cph4": $('#cph4').is(':checked') ? $('#cph4').val() : null,
				    "cph5": $('#cph5').is(':checked') ? $('#cph5').val() : null,
				    
					"chv1": $('#chv1').val(),
				    
					"chv2": $('#chv2').is(':checked') ? $('#chv2').val() : null,
				    "chv3": $('#chv3').is(':checked') ? $('#chv3').val() : null,
				    "chv4": $('#chv4').is(':checked') ? $('#chv4').val() : null,
				    "ccoh1": $('#ccoh1').is(':checked') ? $('#ccoh1').val() : null,
				    "ccoh2": $('#ccoh2').is(':checked') ? $('#ccoh2').val() : null,
				    "ccoh3": $('#ccoh3').is(':checked') ? $('#ccoh3').val() : null,
				    "ccoh4": $('#ccoh4').is(':checked') ? $('#ccoh4').val() : null,
				    "ccoh5": $('#ccoh5').is(':checked') ? $('#ccoh5').val() : null,
				    "ccoh6": $('#ccoh6').is(':checked') ? $('#ccoh6').val() : null,
				    "ccoh7": $('#ccoh7').is(':checked') ? $('#ccoh7').val() : null,
				    "ccoh8": $('#ccoh8').is(':checked') ? $('#ccoh8').val() : null,
				    "ccoh9": $('#ccoh9').is(':checked') ? $('#ccoh9').val() : null,
				    "ccoh10": $('#ccoh10').is(':checked') ? $('#ccoh10').val() : null,
				    "observaciones": $('#observaciones').val()
                };			
			console.log('datos a guardar:', datosRecepcion);
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'controlador/controlador.php',
				data: {
					datosEdit: datosRecepcion
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
        				confirmButtonText: 'OK'
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
        					confirmButtonText: 'OK'
      					});
					}
				});
			}
		});
			function validacionesC() {
				$('#turnoE').css('display', 'none');
				$('#parteE').css('display', 'none');
				$('#pesoE').css('display', 'none');

				if ($("#turno").val() == null || $("#turno").val() == "") {
					$('#turnoE').css('display', 'block');
                    return 'R';
                }

				if ($("#parte").val() == null || $("#parte").val() == "") {
					$('#parteE').css('display', 'block');
                    return 'R';
                }
				if ($("#peso").val() == null || $("#peso").val() == "") {
					$('#pesoE').css('display', 'block');
                    return 'R';
                }
                return "";
            }
	</script>
  	
</html>