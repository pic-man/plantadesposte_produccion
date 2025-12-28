<?php
include("../modelo/funciones.php");

if(isset($_POST["proveedor"])){
	echo json_encode(listarItems($_POST["proveedor"]));
}

if(isset($_POST["proveedorP"])){
	echo json_encode(listarItemsP($_POST["proveedorP"]));
}

if(isset($_POST["compradorSede"])){
	echo json_encode(listarSedes($_POST["compradorSede"]));
}

if(isset($_POST["itemPorProveedor"])){
	echo json_encode(listaItemsPorProveedor($_POST["itemPorProveedor"]));
}

if(isset($_POST["proveedorPorItem"])){
	echo json_encode(listaProveedorPorItem($_POST["proveedorPorItem"]));
}

if(isset($_POST["idGuia"])){
	echo json_encode(cargarGuia($_POST["idGuia"]));
}

if(isset($_POST["idGuiaP"])){
	echo json_encode(cargarGuiaP($_POST["idGuiaP"]));
}

if(isset($_POST["idRecepcion"])){
	echo json_encode(cargarGuiaRecepcion($_POST["idRecepcion"]));
}
if(isset($_POST["idRecepcionPollo"])){
	echo json_encode(cargarGuiaRecepcionPollo($_POST["idRecepcionPollo"]));
}
if(isset($_GET["copiarDatos"])){
	echo json_encode(cargarDatos());
}
if(isset($_POST["idResp"])){
	echo json_encode(cargarResponsable($_POST["idResp"]));
}

if(isset($_POST["idCond"])){
	echo json_encode(cargarConductor($_POST["idCond"]));
}

if(isset($_POST["idItem"])){
	echo json_encode(cargarItem($_POST["idItem"]));
}

if(isset($_POST["idResponsable"])){
	echo json_encode(cargarResponsables($_POST["idResponsable"]));
}

if(isset($_POST["idPlanta"])){
	echo json_encode(cargarPlanta($_POST["idPlanta"]));
}

if(isset($_POST["idProveedor"])){
	echo json_encode(cargarProveedor($_POST["idProveedor"]));
}

if(isset($_POST["idPlacasD"])){
	echo json_encode(cargarPlacasD($_POST["idPlacasD"]));
}

if(isset($_POST["idConductor"])){
	echo json_encode(cargarConductores($_POST["idConductor"]));
}

if(isset($_POST["idcriterio"])){
	echo json_encode(cargarCriterio($_POST["idcriterio"]));
}

if(isset($_POST["idcriterioP"])){
	echo json_encode(cargarCriterioP($_POST["idcriterioP"]));
}

if(isset($_POST["idcriterioRecepcionPollo"])){
	echo json_encode(cargarCriterioRecepcionPollo($_POST["idcriterioRecepcionPollo"]));
}

if(isset($_POST["idPeso"])){
	echo json_encode(cargarCriterioPeso($_POST["idPeso"]));
}

if(isset($_POST["infoEdit"])){
	echo json_encode(editarGuia($_POST["infoEdit"]));
}

if(isset($_POST["infoEditP"])){
	echo json_encode(editarGuiaP($_POST["infoEditP"]));
}

if(isset($_POST["datosEdit"])){
	echo json_encode(editarRecepcion($_POST["datosEdit"]));
}

if(isset($_POST["datosEditPollo"])){
	echo json_encode(editarRecepcionPollo($_POST["datosEditPollo"]));
}

if(isset($_POST["infoEditResp"])){
	echo json_encode(editarResponsable($_POST["infoEditResp"]));
}

if(isset($_POST["infoEditPlacasD"])){
	echo json_encode(editarPlacasD($_POST["infoEditPlacasD"]));
}


/* if(isset($_POST["infoEditCond"])){
	echo json_encode(editarConductor($_POST["infoEditCond"]));
} */

if(isset($_POST["infoEditItem"])){
	echo json_encode(editarItem($_POST["infoEditItem"]));
}

if(isset($_POST["infoEditResponsable"])){
	echo json_encode(editarResponsables($_POST["infoEditResponsable"]));
}

if(isset($_POST["infoEditPlanta"])){
	echo json_encode(editarPlanta($_POST["infoEditPlanta"]));
}

if(isset($_POST["infoEditProveedorPollo"])){
	echo json_encode(editarProveedorPollo($_POST["infoEditProveedorPollo"]));
}

if(isset($_POST["infoEditConductor"])){
	echo json_encode(editarConductores($_POST["infoEditConductor"]));
}

if(isset($_POST["infoCriEdit"])){
	echo json_encode(editarCriterio($_POST["infoCriEdit"]));
}

if(isset($_POST["infoCriEditP"])){
	echo json_encode(editarCriterioP($_POST["infoCriEditP"]));
}

if(isset($_POST["infoCriEditPolloR"])){
	echo json_encode(infoCriEditPolloR($_POST["infoCriEditPolloR"]));
}

if(isset($_POST["infoPesoEdit"])){
	echo json_encode(editarCriterioPeso($_POST["infoPesoEdit"]));
}

if(isset($_POST["infoPesoEditCerdo"])){
	echo json_encode(editarCriterioPesoCerdo($_POST["infoPesoEditCerdo"]));
}

if(isset($_POST["idBloquear"])){
	echo json_encode(bloquearGuia($_POST["idBloquear"]));
}

if(isset($_POST["idBloquearP"])){
	echo json_encode(bloquearGuiaP($_POST["idBloquearP"]));
}

if(isset($_POST["idBloquearR"])){
	echo json_encode(bloquearGuiaR($_POST["idBloquearR"]));
}

if(isset($_POST["idBloquearRPollo"])){
	echo json_encode(bloquearGuiaRecepcionPollo($_POST["idBloquearRPollo"]));
}

if(isset($_POST["idDesbloquear"])){
	echo json_encode(desbloquearGuia($_POST["idDesbloquear"]));
}

if(isset($_POST["idDesbloquearP"])){
	echo json_encode(desbloquearGuiaP($_POST["idDesbloquearP"]));
}

if(isset($_POST["idDesbloquearR"])){
	echo json_encode(desbloquearGuiaR($_POST["idDesbloquearR"]));
}

if(isset($_POST["datos"])){
	echo json_encode(agregarItem($_POST["datos"]));
}

if(isset($_POST["datosP"])){
	echo json_encode(agregarItemP($_POST["datosP"]));
}

if(isset($_POST["datosItemRecepcionPollo"])){
	echo json_encode(agregarItemRecepcionPollo($_POST["datosItemRecepcionPollo"]));
}

if (isset($_POST["datosPeso"])) {
    echo agregarPeso($_POST["datosPeso"]);
} 

if (isset($_POST["datosPesoCerdo"])) {
    echo agregarPesoCerdo($_POST["datosPesoCerdo"]);
} 

if(isset($_POST["datosResp"])){
	echo json_encode(agregarResponsable($_POST["datosResp"]));
}

if(isset($_POST["datosCond"])){
	echo json_encode(agregarConductor($_POST["datosCond"]));
}

/* if(isset($_POST["datosItem"])){
	echo json_encode(agregarNuevoItem($_POST["datosItem"]));
} */
if(isset($_POST["datosPlaca"])){
	echo json_encode(agregarPlaca($_POST["datosPlaca"]));
}

if(isset($_POST["datosPlacasD"])){
	echo json_encode(agregarPlacaD($_POST["datosPlacasD"]));
}

if(isset($_POST["datosSede"])){
	echo json_encode(agregarSede($_POST["datosSede"]));
}

if(isset($_POST["datosE"])){
	echo json_encode(eliminarItem($_POST["datosE"]));
}

if(isset($_POST["datosEP"])){
	echo json_encode(eliminarItemP($_POST["datosEP"]));
}

if(isset($_POST["datosEPolloR"])){
	echo json_encode(eliminarItemPolloR($_POST["datosEPolloR"]));
}

if(isset($_POST["datosEPeso"])){
	echo json_encode(eliminarItemPeso($_POST["datosEPeso"]));
}

if(isset($_POST["datosEliminarSede"])){
	echo json_encode(eliminarSede($_POST["datosEliminarSede"]));
}

if(isset($_POST["id_item_proveedor"])){
	echo json_encode(eliminarItemProveedor($_POST["id_item_proveedor"]));
}

if (isset($_POST["datosGuia"])) {
    $resultado = agregarGuia($_POST["datosGuia"]);
    echo json_encode($resultado);
}

if (isset($_POST["datosGuiaP"])) {
    $resultado = agregarGuiaP($_POST["datosGuiaP"]);
    echo json_encode($resultado);
}

if(isset($_POST["datosRecepcion"])){
	echo json_encode(agregarRecepcion($_POST["datosRecepcion"]));
}

if(isset($_POST["datosRecepcionPollo"])){
	echo json_encode(agregarRecepcionPollo($_POST["datosRecepcionPollo"]));
}

if(isset($_POST["datosCompra"])){
	echo json_encode(agregarItemCompra($_POST["datosCompra"]));
}

if(isset($_POST["cedula"])){
	echo json_encode(buscarCedula($_POST["cedula"]));
}

if(isset($_POST["cedulaPollo"])){
	echo json_encode(buscarCedulaPollo($_POST["cedulaPollo"]));
}

if(isset($_POST["validarNitPlanta"])){
	echo json_encode(buscarNitPlanta($_POST["validarNitPlanta"]));
}

if(isset($_POST["validarNitProveedor"])){
	echo json_encode(buscarNitProveedorPollo($_POST["validarNitProveedor"]));
}

if(isset($_POST["validarItem"])){
	echo json_encode(buscarItem($_POST["validarItem"]));
}

if(isset($_POST["validarCodigo"])){
	echo json_encode(buscarCodigo($_POST["validarCodigo"]));
}

if(isset($_POST["validarCedulaResponsable"])){
	echo json_encode(buscarCedulaResponsable($_POST["validarCedulaResponsable"]));
}

if(isset($_POST["validarPlacaD"])){
	echo json_encode(buscarPlacaD($_POST["validarPlacaD"]));
}

if(isset($_POST["validarCedulaConductor"])){
	echo json_encode(buscarCedulaConductor($_POST["validarCedulaConductor"]));
}

if(isset($_POST["datosConductor"])){
	echo json_encode(agregarConductorRecepcion($_POST["datosConductor"]));
}

if(isset($_POST["datosConductorPollo"])){
	echo json_encode(agregarConductorRecepcionPollo($_POST["datosConductorPollo"]));
}

if(isset($_POST["datosPlacaR"])){
	echo json_encode(buscarPlaca($_POST["datosPlacaR"]));
}

if(isset($_POST["datosPlacaRPollo"])){
	echo json_encode(buscarPlacaPollo($_POST["datosPlacaRPollo"]));
}

if(isset($_POST["datosPesoRecepcion"])){
	echo json_encode(calcularDiferencia($_POST["datosPesoRecepcion"]));
}

if(isset($_POST["datosEPesoRecepcion"])){
	echo json_encode(eliminarCalculoDiferencia($_POST["datosEPesoRecepcion"]));
}

if (isset($_POST["datosItem"])) {
    $resultado = agregarNuevoItem($_POST["datosItem"]);
    echo json_encode($resultado);
}

if (isset($_POST["datosResponsable"])) {
    $resultado = agregarNuevoResponsable($_POST["datosResponsable"]);
    echo json_encode($resultado);
}

if (isset($_POST["datosPlanta"])) {
    $resultado = agregarNuevaPlanta($_POST["datosPlanta"]);
    echo json_encode($resultado);
}

if (isset($_POST["datosProveedorP"])) {
    $resultado = agregarProveedorPollo($_POST["datosProveedorP"]);
    echo json_encode($resultado);
}

if (isset($_POST["datosConductorD"])) {
    $resultado = agregarNuevoConductorD($_POST["datosConductorD"]);
    echo json_encode($resultado);
}

if (isset($_POST["guardarTiempoRecepcionRes"])) {
    $resultado = guardarTiempoRecepcionRes($_POST["guardarTiempoRecepcionRes"]);
    echo json_encode($resultado);
}

if (isset($_POST["guardarTiempoRecepcionPollo"])) {
    $resultado = guardarTiempoRecepcionPollo($_POST["guardarTiempoRecepcionPollo"]);
    echo json_encode($resultado);
}

if (isset($_POST["guardarTiempoDespachoRes"])) {
    $resultado = guardarTiempoDespachoRes($_POST["guardarTiempoDespachoRes"]);
    echo json_encode($resultado);
}

if (isset($_POST["guardarTiempoDespachoPollo"])) {
    $resultado = guardarTiempoDespachoPollo($_POST["guardarTiempoDespachoPollo"]);
    echo json_encode($resultado);
}

if (isset($_POST["Destino"])) {
    echo json_encode(guardar_destino($_POST["Destino"]));
}

if (isset($_POST["EditDestino"])) {
    echo json_encode(traer_destino($_POST["EditDestino"]));
}

if (isset($_POST["EditarDestino"])) {
    echo json_encode(editar_destino($_POST["EditarDestino"]));
}

if (isset($_POST["bloq_guia"])) {
    echo json_encode(bloquear_guia());
}

if (isset($_POST["borrarfoto"])) {
    echo json_encode(borrarfoto($_POST["borrarfoto"]));
}

if (isset($_POST["bloq_guiaC"])) {
    echo json_encode(bloquear_guiaCarne());
}

if (isset($_POST["bloq_guiaDC"])) {
    echo json_encode(bloquear_guia_despacho());
}

if (isset($_POST["bloq_guiaDP"])) {
    echo json_encode(bloquear_guia_despacho_pollo());
}

if (isset($_POST["datosConductorR"])) {
    echo json_encode(agregarNuevoConductorR($_POST["datosConductorR"]));
}

if (isset($_POST["idConductorR"])) {
    echo json_encode(cargarConductoresR($_POST["idConductorR"]));
}

if (isset($_POST["infoEditConductorR"])) {
    echo json_encode(editarConductoresR($_POST["infoEditConductorR"]));
}

if (isset($_POST["validarCedulaConductorR"])) {
    echo json_encode(buscarCedulaConductorR($_POST["validarCedulaConductorR"]));
}

if (isset($_POST["datosCR"])) {
    echo json_encode(eliminar_conductorR($_POST["datosCR"]));
}

if (isset($_POST["datosCP"])) {
    echo json_encode(eliminar_conductorD($_POST["datosCP"]));
}

if (isset($_POST["datosConductorP"])) {
    echo json_encode(agregarNuevoConductorP($_POST["datosConductorP"]));
}

if (isset($_POST["idConductorP"])) {
    echo json_encode(cargarConductoresP($_POST["idConductorP"]));
}

if (isset($_POST["infoEditConductorP"])) {
    echo json_encode(editarConductoresP($_POST["infoEditConductorP"]));
}

if (isset($_POST["validarCedulaConductorP"])) {
    echo json_encode(buscarCedulaConductorP($_POST["validarCedulaConductorP"]));
}

if (isset($_POST["guiaDesprese"])) {
    echo json_encode(agregar_guia_desprese($_POST["guiaDesprese"]));
}

if (isset($_POST["idDesprese"])) {
    echo json_encode(buscar_desprese($_POST["idDesprese"]));
}

if (isset($_POST["editGuia"])) {
    echo json_encode(editar_guia_desprese($_POST["editGuia"]));
}

if (isset($_POST["datosProveedorH"])) {
    echo json_encode(agregar_proveedor_hielo($_POST["datosProveedorH"]));
}

if (isset($_POST["idProveedorH"])) {
    echo json_encode(cargar_proveedor_hielo($_POST["idProveedorH"]));
}

if (isset($_POST["editProveedorH"])) {
    echo json_encode(editar_proveedor_hielo($_POST["editProveedorH"]));
}

if (isset($_POST["validarNitProveedorHielo"])) {
    echo json_encode(buscarNitProveedorHielo($_POST["validarNitProveedorHielo"]));
}

if (isset($_POST["datosProveedorS"])) {
    echo json_encode(agregar_proveedor_salmuera($_POST["datosProveedorS"]));
}

if (isset($_POST["idProveedorS"])) {
    echo json_encode(cargar_proveedor_salmuera($_POST["idProveedorS"]));
}

if (isset($_POST["editProveedorS"])) {
    echo json_encode(editar_proveedor_salmuera($_POST["editProveedorS"]));
}

if (isset($_POST["validarNitProveedorSalmuera"])) {
    echo json_encode(buscarNitProveedorSalmuera($_POST["validarNitProveedorSalmuera"]));
}

if (isset($_POST["datosProveedorE"])) {
    echo json_encode(agregar_proveedor_empaque($_POST["datosProveedorE"]));
}

if (isset($_POST["idProveedorE"])) {
    echo json_encode(cargar_proveedor_empaque($_POST["idProveedorE"]));
}

if (isset($_POST["editProveedorE"])) {
    echo json_encode(editar_proveedor_empaque($_POST["editProveedorE"]));
}

if (isset($_POST["validarNitProveedorEmpaque"])) {
    echo json_encode(buscarNitProveedorEmpaque($_POST["validarNitProveedorEmpaque"]));
}

if (isset($_POST["datosFafricanteE"])) {
    echo json_encode(agregar_fabricante_empaque($_POST["datosFafricanteE"]));
}

if (isset($_POST["idFabricanteE"])) {
    echo json_encode(cargar_fabricante_empaque($_POST["idFabricanteE"]));
}

if (isset($_POST["editFabricanteE"])) {
    echo json_encode(editar_fabricante_empaque($_POST["editFabricanteE"]));
}

if (isset($_POST["validarNitFabricanteEmpaque"])) {
    echo json_encode(buscarNitFabricanteEmpaque($_POST["validarNitFabricanteEmpaque"]));
}

if (isset($_POST["despreseItem"])) {
    echo json_encode(agregar_desprese_item($_POST["despreseItem"]));
}

if (isset($_POST["AggMejora"])) {
    echo json_encode(agregar_mejora($_POST["AggMejora"]));
}

if (isset($_POST["idItemD"])) {
    echo json_encode(traer_item($_POST["idItemD"]));
}

if (isset($_POST["editItem"])) {
    echo json_encode(editar_item($_POST["editItem"]));
}

if (isset($_POST["deleteItem"])) {
    echo json_encode(eliminar_Item($_POST["deleteItem"]));
}

if (isset($_POST["traerMejora"])) {
	echo json_encode(traer_mejora($_POST["traerMejora"]));
}

if (isset($_POST["editarMejora"])) {
	echo json_encode(editar_mejora($_POST["editarMejora"]));
}

if (isset($_POST["deleteMejora"])) {
	echo json_encode(eliminar_Mejora($_POST["deleteMejora"]));
}

if (isset($_POST["verifiSelect"])) {
	echo json_encode(verificar_item($_POST["verifiSelect"]));
}

if (isset($_POST["generarCodigo"])) {
	echo json_encode(traer_dias($_POST["generarCodigo"]));
}

if (isset($_POST["AggTemp"])) {
	echo json_encode(agregar_temps($_POST["AggTemp"]));
}

if (isset($_POST["envio"])) {
	echo json_encode(agregar_item_canal($_POST["envio"]));
}

if (isset($_POST["aggCanal"])) {
	echo json_encode(agregar_canal($_POST["aggCanal"]));
}

if (isset($_POST["idCanal"])) {
	echo json_encode(traer_canal($_POST["idCanal"]));
}

if (isset($_POST["editCanal"])) {
	echo json_encode(editar_canal($_POST["editCanal"]));
}

if (isset($_POST["bloquearCanal"])) {
	echo json_encode(bloquear_canal($_POST["bloquearCanal"]));
}

if (isset($_POST["desbloquearCanal"])) {
	echo json_encode(desbloquear_canal($_POST["desbloquearCanal"]));
}

if (isset($_POST["idTemp"])) {
	echo json_encode(traer_temps($_POST["idTemp"]));
}

if (isset($_POST["aggCiudad"])) {
	echo json_encode(agregar_ciudad($_POST["aggCiudad"]));
}

if (isset($_POST["idCiudad"])) {
	echo json_encode(traer_ciudad($_POST["idCiudad"]));
}

if (isset($_POST["editCiudad"])) {
	echo json_encode(editar_ciudad($_POST["editCiudad"]));
}

if (isset($_POST["EliminarCuidad"])) {
	echo json_encode(elminar_cuidad($_POST["EliminarCuidad"]));
}

if (isset($_POST["idItemsD"])) {
	echo json_encode(items_desprese($_POST["idItemsD"]));
}

if (isset($_POST["aggYear"])) {
	echo json_encode(agregar_year($_POST["aggYear"]));
}

if (isset($_POST["idYear"])) {
	echo json_encode(traer_year($_POST["idYear"]));
}

if (isset($_POST["editYear"])) {
	echo json_encode(editar_year($_POST["editYear"]));
}

if (isset($_POST["aggSemana"])) {
	echo json_encode(agregar_semana($_POST["aggSemana"]));
}

if (isset($_POST["idSemana"])) {
	echo json_encode(traer_semana($_POST["idSemana"]));
}

if (isset($_POST["editSemana"])) {
	echo json_encode(editar_Semana($_POST["editSemana"]));
}

if (isset($_POST["botonP"])) {
	echo json_encode(semana_activa());
}

if (isset($_POST["traerGuia"])) {
	echo json_encode(semana_activa());
}

if (isset($_POST["agregarCanal"])) {
	echo json_encode(agregar_canal($_POST["agregarCanal"]));
}

if (isset($_POST["bloquear_desprese"])) {
	echo json_encode(bloquear_desprese($_POST["bloquear_desprese"]));
}

if (isset($_POST["desbloquear_desprese"])) {
	echo json_encode(desbloquear_desprese($_POST["desbloquear_desprese"]));
}

if (isset($_POST["traerItems"])) {
	echo json_encode(items_canal($_POST["traerItems"]));
}

if (isset($_POST["enviarPro"])) {
	echo json_encode(agregar_programacion_final($_POST["enviarPro"]));
}

if (isset($_POST["traerItemsEs"])) {
	echo json_encode(traer_especies($_POST["traerItemsEs"]));
}

if (isset($_POST["GuardarPollo"])) {
	echo json_encode(guardar_pesos_pollo($_POST["GuardarPollo"]));
}

if (isset($_POST["traerPesos"])) {
	echo json_encode(traer_pesos($_POST["traerPesos"]));
}

if (isset($_POST["EditPesosDespresado"])) {
	echo json_encode(editar_pesos_despresado($_POST["EditPesosDespresado"]));
}

if (isset($_POST["deletePeso"])) {
	echo json_encode(eliminarPeso($_POST["deletePeso"]));
}

if (isset($_POST["verifiPollo"])) {
	echo json_encode(verificar_pollo($_POST["verifiPollo"]));
}

if (isset($_POST["enviarCambio"])) {
	echo json_encode(guardar_cambio($_POST["enviarCambio"]));
}

if (isset($_POST["BorrarCambio"])) {
	echo json_encode(Borrar_Cambio($_POST["BorrarCambio"]));
}

if (isset($_POST["UltimoCambio"])) {
	echo json_encode(ultimo_cambio($_POST["UltimoCambio"]));
}

if (isset($_POST["verificarCampos"])) {
	echo json_encode(verificar_campos($_POST["verificarCampos"]));
}

if (isset($_POST["CamposGuia"])) {
	echo json_encode(agg_campos_guia($_POST["CamposGuia"]));
}

if (isset($_POST["verificarItems"])) {
	echo json_encode(verificar_items($_POST["verificarItems"]));
}

if (isset($_POST["mostrarAlerta"])) {
	echo json_encode(mostrar_alerta($_POST["mostrarAlerta"]));
}

if (isset($_POST["verificarPesosDesprese"])) {
	echo json_encode(verificar_Pesos_Desprese($_POST["verificarPesosDesprese"]));
}

if (isset($_POST["generarCodigoLote"])) {
	echo json_encode(verificar_proveedor_orden($_POST["generarCodigoLote"]));
}

if (isset($_POST["deleteLote"])) {
	echo json_encode(borrar_lote($_POST["deleteLote"]));
}

if (isset($_POST["guardarLote"])) {
	echo json_encode(guardar_lote($_POST["guardarLote"]));
}

if (isset($_POST["intercambioRES"])) {
	set_time_limit(0);
	echo json_encode(intercambio_res($_POST["intercambioRES"]));
}

if (isset($_POST["intercambioCERDO"])) {
	echo json_encode(intercambio_cerdo($_POST["intercambioCERDO"]));
}

if (isset($_POST["RevertirRES"])) {
	echo json_encode(revertir_res($_POST["RevertirRES"]));
}

if (isset($_POST["RevertirCERDO"])) {
	echo json_encode(revertir_cerdo($_POST["RevertirCERDO"]));
}

if (isset($_POST["AggProveedorCerdo"])) {
	echo json_encode(agregar_proveedor_cerdo($_POST["AggProveedorCerdo"]));
}

if (isset($_POST["idProveedorCerdo"])) {
	echo json_encode(cargar_proveedor_cerdo($_POST["idProveedorCerdo"]));
}

if (isset($_POST["editProveedorC"])) {
	echo json_encode(editar_proveedor_cerdo($_POST["editProveedorC"]));
}

if (isset($_POST["validarNitProveedorCerdo"])) {
	echo json_encode(buscarNitProveedorCerdo($_POST["validarNitProveedorCerdo"]));
}

if (isset($_POST["guiaDespresado"])) {
    echo json_encode(agregar_guia_despresado($_POST["guiaDespresado"]));
}

if (isset($_POST["GuardarPolloDespresado"])) {
	echo json_encode(guardar_pesos_pollo_despresado($_POST["GuardarPolloDespresado"]));
}

if (isset($_POST["traerPesosDespresado"])) {
	echo json_encode(traer_pesos_despresado($_POST["traerPesosDespresado"]));
}

if (isset($_POST["deletePesoDespresado"])) {
	echo json_encode(eliminarPesoDespresado($_POST["deletePesoDespresado"]));
}

if (isset($_POST["idDespreseDespresado"])) {
    echo json_encode(buscar_desprese_despresado($_POST["idDespreseDespresado"]));
}

if (isset($_POST["editGuiaDespresado"])) {
    echo json_encode(editar_guia_despresado($_POST["editGuiaDespresado"]));
}

if (isset($_POST["idItemsDespresado"])) {
	echo json_encode(items_despresado($_POST["idItemsDespresado"]));
}

if (isset($_POST["despresadoItem"])) {
    echo json_encode(agregar_despresado_item($_POST["despresadoItem"]));
}

if (isset($_POST["verificarPesosDespresado"])) {
	echo json_encode(verificar_Pesos_Despresado($_POST["verificarPesosDespresado"]));
}

if (isset($_POST["idItemDespresado"])) {
    echo json_encode(traer_item_despresado($_POST["idItemDespresado"]));
}

if (isset($_POST["editItemDespresado"])) {
    echo json_encode(editar_item_despresado($_POST["editItemDespresado"]));
}

if (isset($_POST["deleteItemDespresado"])) {
    echo json_encode(eliminar_Item_despresado($_POST["deleteItemDespresado"]));
}

if (isset($_POST["verificarCamposDespresado"])) {
	echo json_encode(verificar_campos_despresado($_POST["verificarCamposDespresado"]));
}

if (isset($_POST["verifiSelectDespresado"])) {
	echo json_encode(verificar_item_despresado($_POST["verifiSelectDespresado"]));
}

if (isset($_POST["idTempDespresado"])) {
	echo json_encode(traer_temps_despresado($_POST["idTempDespresado"]));
}

if (isset($_POST["CamposGuiaDespresado"])) {
	echo json_encode(agg_campos_guia_despresado($_POST["CamposGuiaDespresado"]));
}

if (isset($_POST["AggMejoraDespresado"])) {
    echo json_encode(agregar_mejora_despresado($_POST["AggMejoraDespresado"]));
}

if (isset($_POST["verificarItemsDespresado"])) {
	echo json_encode(verificar_items_despresado($_POST["verificarItemsDespresado"]));
}

if (isset($_POST["generarCodigoDespresado"])) {
	echo json_encode(traer_dias_despresado($_POST["generarCodigoDespresado"]));
}

if (isset($_POST["traerMejoraDespresado"])) {
	echo json_encode(traer_mejora_despresado($_POST["traerMejoraDespresado"]));
}

if (isset($_POST["editarMejoraDespresado"])) {
	echo json_encode(editar_mejora_despresado($_POST["editarMejoraDespresado"]));
}

if (isset($_POST["deleteMejoraDespresado"])) {
	echo json_encode(eliminar_Mejora_despresado($_POST["deleteMejoraDespresado"]));
}

if (isset($_POST["AggTempDespresado"])) {
	echo json_encode(agregar_temps_despresado($_POST["AggTempDespresado"]));
}

if (isset($_POST["bloquear_despresado"])) {
	echo json_encode(bloquear_despresado($_POST["bloquear_despresado"]));
}

if (isset($_POST["desbloquear_despresado"])) {
	echo json_encode(desbloquear_despresado($_POST["desbloquear_despresado"]));
}

if (isset($_POST["validarLotePlanta"])) {
	echo json_encode(ValidarLotePlanta($_POST["validarLotePlanta"]));
}

if (isset($_POST["EditPesos"])) {
	echo json_encode(editar_pesos($_POST["EditPesos"]));
}

if (isset($_POST["validarLotePlantaDatos"])) {
	echo json_encode(ValidarLotePlantaDatos($_POST["validarLotePlantaDatos"]));
}

if (isset($_POST["PesosEstandar"])) {
	echo json_encode(EjecutarPesosEstandar($_POST["PesosEstandar"]));
}

if (isset($_POST["fechaInicio"])) {
	echo json_encode(actualizarFechaInicio());
}

if (isset($_POST["verificarPesosEstandar"])) {
	echo json_encode(verificarPesosEstandar($_POST["verificarPesosEstandar"]));
}

if (isset($_POST["RevertPesosEstandar"])) {
	echo json_encode(RevertirPesosEstandar($_POST["RevertPesosEstandar"]));
}

if (isset($_POST["verificarDesprese"])) {
	echo json_encode(verificar_desprese($_POST["verificarDesprese"]));
}

if (isset($_POST["itemStatus"])) {
	echo json_encode(actualizar_item_status($_POST["itemStatus"]));
}

if (isset($_POST["AggPanificacion"])) {
	echo json_encode(agregar_despacho_panificacion($_POST["AggPanificacion"]));
}

if (isset($_POST["TraerDespachoP"])) {
	echo json_encode(traer_despacho_panificacion($_POST["TraerDespachoP"]));
}

if (isset($_POST["EditarDespachoP"])) {
	echo json_encode(editar_despacho_panificacion($_POST["EditarDespachoP"]));
}

if (isset($_POST["AggItemDespachoP"])) {
	echo json_encode(agregar_item_despacho_panificadora($_POST["AggItemDespachoP"]));
}

if (isset($_POST["TraerItemDespachoP"])) {
	echo json_encode(traer_item_despacho_panificacion($_POST["TraerItemDespachoP"]));
}

if (isset($_POST["EditarItemDP"])) {
	echo json_encode(editar_item_despacho_panificacion($_POST["EditarItemDP"]));
}

if (isset($_POST["EliminarItemDP"])) {
	echo json_encode(eliminar_item_despacho_panificacion($_POST["EliminarItemDP"]));
}

if (isset($_POST["agregarDataDesprese"])) {
	echo json_encode(agregar_data_desprese($_POST["agregarDataDesprese"]));
}

if (isset($_POST["eliminarRegistro"])) {
	echo json_encode(eliminar_registro($_POST["eliminarRegistro"]));
}

if (isset($_POST["verificarTipoPollo"])) {
	echo json_encode(verificar_tipo_pollo($_POST["verificarTipoPollo"]));
}

if (isset($_POST["trerLotes"])) {
	echo json_encode(traer_lotes($_POST["trerLotes"]));
}

if (isset($_POST["validarTipoPollo"])) {
	echo json_encode(validar_tipo_pollo($_POST["validarTipoPollo"]));
}

if (isset($_POST["TraerItemsDP"])) {
	echo json_encode(traer_items_despacho_panificacion($_POST["TraerItemsDP"]));
}

if (isset($_POST["validarLotePlantaDatosDespresado"])) {
	echo json_encode(validar_lote_planta_datos_despresado($_POST["validarLotePlantaDatosDespresado"]));
}

if (isset($_POST["validarTipoPolloDespresado"])) {
	echo json_encode(validar_tipo_pollo_despresado($_POST["validarTipoPolloDespresado"]));
}

if (isset($_POST["agregarDataDespresado"])) {
	echo json_encode(agregar_data_despresado($_POST["agregarDataDespresado"]));
}

if (isset($_POST["eliminarRegistroDespresado"])) {
	echo json_encode(eliminar_registro_despresado($_POST["eliminarRegistroDespresado"]));
}

if (isset($_POST["trerLotesDespresado"])) {
	echo json_encode(traer_lotes_despresado($_POST["trerLotesDespresado"]));
}

if (isset($_POST["agregarBolsa"])) {
	$items = [];
	foreach ($_POST["agregarBolsa"] as $item) {
		$items[$item["name"]] = $item["value"];
	}
	echo json_encode(agregar_bolsa($items));
}

if (isset($_POST["traerBolsa"])) {
	echo json_encode(traer_bolsa($_POST["traerBolsa"]));
}

if (isset($_POST["editBolsa"])) {
	$items = [];
	foreach ($_POST["editBolsa"] as $item) {
		$items[$item["name"]] = $item["value"];
	}
	$items["id"] = $_POST["id"];
	echo json_encode(editar_bolsa($items));
}

if (isset($_POST["eliminarBolsa"])) {
	echo json_encode(eliminar_bolsa($_POST["eliminarBolsa"]));
}

if (isset($_POST["TraerCapacidad"])) {
	echo json_encode(traer_capacidad($_POST["TraerCapacidad"]));
}

if (isset($_POST["TraerItemsDespresado"])) {
	echo json_encode(traer_items_despresado($_POST["TraerItemsDespresado"]));
}

if (isset($_POST["validarLoteDespresado"])) {
	echo json_encode(validar_lote_despresado($_POST["validarLoteDespresado"]));
}

if (isset($_POST["TraerDestinos"])) {
	echo json_encode(TraerDestinos());
}

if (isset($_POST["TraerItemsSelect"])) {
	echo json_encode(TraerItems());
}

if (isset($_POST["GuardarExcepcion"])) {
	$items = [];

	foreach ($_POST["GuardarExcepcion"] as $item) {
		$items[$item["name"]] = $item["value"];
	}

	echo json_encode(guardar_excepcion($items));
}

if (isset($_POST["TraerExcepcion"])) {
	echo json_encode(traer_excepcion($_POST["TraerExcepcion"]));
}

if (isset($_POST["EditarExcepcion"])) {
	$items = [];

	foreach ($_POST["EditarExcepcion"] as $item) {
		$items[$item["name"]] = $item["value"];
	}

	echo json_encode(editar_excepcion($items));
}

if (isset($_POST["EliminarExcepcion"])) {
	echo json_encode(eliminar_excepcion($_POST["EliminarExcepcion"]));
}

if (isset($_POST["VerificarItem"])) {
	echo json_encode(verificar_item_excepcion($_POST["VerificarItem"]));
}

if (isset($_POST["agregarSalmuera"])) {
	$items = [];

	foreach ($_POST["agregarSalmuera"] as $item) {
		$items[$item["name"]] = $item["value"];
	}

	echo json_encode(agregar_salmuera($items));
}

if (isset($_POST["traerSalmuera"])) {
	echo json_encode(traer_salmuera($_POST["traerSalmuera"]));
}

if (isset($_POST["editSalmuera"])) {
	$items = [];

	foreach ($_POST["editSalmuera"] as $item) {
		$items[$item["name"]] = $item["value"];
	}
	$items["id"] = $_POST["id"];

	echo json_encode(editar_salmuera($items));
}

if (isset($_POST["eliminarSalmuera"])) {
	echo json_encode(eliminar_salmuera($_POST["eliminarSalmuera"]));
}

if (isset($_POST["TraerConductores"])) {
	echo json_encode(traer_conductores($_POST["TraerConductores"]));
}

if (isset($_POST["guardarEtiquetas"])) {
	echo json_encode(guardar_etiquetas($_POST["guardarEtiquetas"]));
}

if (isset($_POST["eliminarEtiqueta"])) {
	echo json_encode(eliminar_etiqueta($_POST["eliminarEtiqueta"]));
}

if (isset($_POST["guardarEtiquetasDespresado"])) {
	echo json_encode(guardar_etiquetas_despresado($_POST["guardarEtiquetasDespresado"]));
}

if (isset($_POST["eliminarEtiquetaDespresado"])) {
	echo json_encode(eliminar_etiqueta_despresado($_POST["eliminarEtiquetaDespresado"]));
}

if (isset($_POST["InsertarDatosCF"])) {
	echo json_encode(InsertarDatosCF($_POST["InsertarDatosCF"]));
}

if (isset($_POST["TraerGuias"])) {
	echo json_encode(TraerGuias($_POST["TraerGuias"], $_POST["tipoGuia"]));
}

if (isset($_POST["guardarDatosLiquidacion"])) {
	$items = [];
	foreach ($_POST["guardarDatosLiquidacion"] as $item) {
		$items[$item["name"]] = $item["value"];
	}
	echo json_encode(guardar_datos_liquidacion($items));
}

if (isset($_POST["resetDatosLiquidacion"])) {
	echo json_encode(reset_datos_liquidacion());
}

if (isset($_POST["traerDatosLiquidacion"])) {
	echo json_encode(traer_datos_liquidacion());
}

if (isset($_POST["ValidarGuiaPlano"])) {
	echo json_encode(validar_guia_plano($_POST["ValidarGuiaPlano"]));
}

if (isset($_POST["ValidarGuiaPlanoDestino"])) {
	echo json_encode(validar_guia_plano_destino($_POST["ValidarGuiaPlanoDestino"], $_POST["destino"], $_POST["tipoPollo"]));
}

if (isset($_POST["crearPlanos"])) {
	echo json_encode(crear_planos($_POST["crearPlanos"]));
}

if (isset($_POST["crearPlanosMejoramiento"])) {
	echo json_encode(crear_planos_mejoramiento($_POST["crearPlanosMejoramiento"]));
}

if (isset($_POST["InsertarDatosCFDespresado"])) {
	echo json_encode(InsertarDatosCFDespresado($_POST["InsertarDatosCFDespresado"]));
}

if (isset($_POST["ValidarGuiaPlanoDespresado"])) {
	echo json_encode(validar_guia_plano_despresado($_POST["ValidarGuiaPlanoDespresado"]));
}

if (isset($_POST["ValidarGuiaPlanoDestinoDespresado"])) {
	echo json_encode(validar_guia_plano_destino_despresado($_POST["ValidarGuiaPlanoDestinoDespresado"], $_POST["destino"], $_POST["tipoPollo"]));
}

if (isset($_POST["crearPlanosDespresado"])) {
	echo json_encode(crear_planos_despresado($_POST["crearPlanosDespresado"]));
}

if (isset($_POST["crearPlanosMejoramientoDespresado"])) {
	echo json_encode(crear_planos_mejoramiento_despresado($_POST["crearPlanosMejoramientoDespresado"]));
}

if (isset($_POST["crearPlanoRSV"])) {
	echo json_encode(crear_plano_rsv());
}

if (isset($_POST["enviarProduccionDia"])) {
	echo json_encode(enviar_produccion_dia($_POST["enviarProduccionDia"], $_POST["accion"]));
}

if (isset($_POST["ValidarDatosSemana"])) {
	echo json_encode(ValidarDatosSemana());
}

if (isset($_POST["EliminarProduccionDia"])) {
	echo json_encode(eliminar_produccion_dia($_POST["EliminarProduccionDia"]));
}

if (isset($_POST["cambiarRegistrosCambios"])) {
	echo json_encode(cambiarRegistrosCambios($_POST["cambiarRegistrosCambios"]));
}