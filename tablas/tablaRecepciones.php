<?php
session_start();
error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaProveedores = listaRecepcionesCompleta();
$totalData = mysqli_num_rows($listaProveedores);

if (!empty($busqueda)) {
    $listaProveedores = listaRecepcionesPaginada($inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaProveedores);
} else {
    $totalFiltro = $totalData;
}

$listaProveedores = listaRecepcionesPaginada($inicio, $fin, $busqueda); 
$data = array();
$cont=0;

while ($row = mysqli_fetch_array($listaProveedores)) {
    include('../config.php');
    $sql = ("select count(turno)as canales from recepcion_pesos where id_recepcion =".$row[0]);
    $rs_operacion = mysqli_query($link, $sql);
    $c = mysqli_fetch_array($rs_operacion);
    
    $canales = $c['canales'];
    $slash = "/";

    $sql = "SELECT id_recepcion FROM recepcion ORDER BY id_recepcion DESC LIMIT 1";
    $id_guia = mysqli_fetch_row(mysqli_query($link, $sql));
    if ($row[0] != $id_guia[0]) {
        $sql5 = "SELECT id_recepcion FROM recepcion_pesos WHERE id_recepcion =".$row[0];
        if (mysqli_num_rows(mysqli_query($link, $sql5)) == 0) {
            $canales = "";
            $slash = "";
            $row[5] = "ANULADO";
            $row[6] = "";
        }
    }

    if ($_SESSION["tipo"] == 0) {
        $sqlCambios = "SELECT COUNT(idCambio) AS totalCambios FROM recepcion_pesos_cambios WHERE id_recepcion = '$row[0]'";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);
        $rowCambios = mysqli_fetch_assoc($rs_operacionCambios);
        $totalCambios = $rowCambios["totalCambios"];
    } else {
        $totalCambios = 0;
    }

    $cont++;
    $btns = '';
    $estadoBtn = '';
    $subdata = array();
    $subdata[] = '<center>'.$row[0].'<br>'.$row[8].'</center>';
if ($row[5] != "ANULADO") {    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\',\''.$row[8].'\',\''.$row[5].'\'); buscarItems(\''.$row[0].'\',\''.$row[8].'\', \''. $totalCambios .'\');" data-bs-toggle="modal">'.$row[1].'<br>'.$row[2].'</a></center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\',\''.$row[8].'\',\''.$row[5].'\'); buscarItems(\''.$row[0].'\',\''.$row[8].'\', \''. $totalCambios .'\');" data-bs-toggle="modal">'.$row[3].'<br>'.$row[4].'</a></center>';

    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\',\''.$row[8].'\',\''.$row[5].'\'); buscarItems(\''.$row[0].'\',\''.$row[8].'\', \''. $totalCambios .'\');" data-bs-toggle="modal">'.$canales.$slash.$row[5].'<br>'.$row[6].'</a></center>';
}else{
    $subdata[] = '<center><a style="z-index: 0;color:#000">'.$row[1].'<br>'.$row[2].'</a></center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000">'.$row[3].'<br>'.$row[4].'</a></center>';

    $subdata[] = '<center><a style="z-index: 0;color:#000">'.$canales.$slash.$row[5].'<br>'.$row[6].'</a></center>';
}
    if ($row[5] != "ANULADO") {
        if($_SESSION['tipoR'] != 2){    
            if(($_SESSION['tipo'] == 0)) {
                $estadoBtn = '
                <a style="z-index: 0;color:#000;" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[0].'\')" title="Editar Guia"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';
        
                if($canales == $row[5]){ 
                    if($row[8] == 'RES'){
                        $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresopdf.php?id='.$row[0].'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2  me-2 text-primary"></i></a>';
                    }else{
                        $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresoCerdopdf.php?id='.$row[0].'" target="_blank" title="Editar Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
                    }  
                }else{
                    $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;""></i>';
                } 
        
                if($row[9] == '1'){
                    $estadoBtn .='<a style="z-index: 0;color: red" onclick="bloquearEdicion(\''.$row[0].'\')" title="Bloquear Guia"><i class="bi bi-unlock fs-2 me-2 style="color:#ff0000;"></i></a></center>';
                }else{
                    $estadoBtn .='<a style="z-index: 0;color:#000" onclick="desbloquearEdicion(\''.$row[0].'\')" title="Desbloquear Guia"><i class="bi-lock fs-2 me-2 style="color:#00a45f;"></i></a></center>';
                }
                $imagen = "SELECT foto FROM recepcion_pesos WHERE id_recepcion = ".$row[0];
                $rs_imagen = mysqli_query($link, $imagen);
                $foto = 0;
                while ($fotoN = mysqli_fetch_row($rs_imagen)) {
                    if ($fotoN[0] != '') {
                        $estadoBtn .= '<a style="z-index: 0;color: rgb(0, 35, 188); "><i class="bi bi-image fs-3 me-2"></i></a>';
                        $foto = 1;
                        break;
                    }
                }
                if ($foto == 0) {
                    $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;""></i>';
                }
                $imagen = "SELECT observacion FROM recepcion_pesos WHERE id_recepcion = ".$row[0];
                $rs_imagen = mysqli_query($link, $imagen);
                $observacion = 0;
                while ($fotoN = mysqli_fetch_row($rs_imagen)) {
                    if ($fotoN[0] != '') {
                        $estadoBtn .= '<a style="z-index: 0;color:#ff8a00;margin: 5px;" ><i class="bi bi-exclamation-diamond fs-2"></i></a>';
                        $observacion = 1;
                        break;
                    }
                }
                if ($observacion == 0) {
                    $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;""></i>';
                }
                
            }else{
                if($row[8] == 'RES') {
                    if($row[9] == '1'){
                        $estadoBtn .= '
                        <a style="z-index: 0;color:#000;" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[0].'\')" title="Editar Guia"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';

                        if($canales == $row[5]){
                            $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresopdf.php?id='.$row[0].'" target="_blank" name="status1" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
                        }else{
                            $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;""></i>';
                        }

                        $imagen = "SELECT foto FROM recepcion_pesos WHERE id_recepcion = ".$row[0];
                        $rs_imagen = mysqli_query($link, $imagen);
                        $foto = 0;
                        while ($fotoN = mysqli_fetch_row($rs_imagen)) {
                            if ($fotoN[0] != '') {
                                $estadoBtn .= '<a style="z-index: 0;color: rgb(0, 35, 188); "><i class="bi bi-image fs-3 me-2"></i></a>';
                                $foto = 1;
                                break;
                            }
                        }
                        if ($foto == 0) {
                            $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;""></i>';
                        }

                    }else{
                        $estadoBtn = '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;" ></i>';

                        if($canales == $row[5]){
                            $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresopdf.php?id='.$row[0].'" target="_blank" name="status0" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
                        }else{
                            $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;""></i>';
                        }

                        $imagen = "SELECT foto FROM recepcion_pesos WHERE id_recepcion = ".$row[0];
                        $rs_imagen = mysqli_query($link, $imagen);
                        $foto = 0;
                        while ($fotoN = mysqli_fetch_row($rs_imagen)) {
                            if ($fotoN[0] != '') {
                                $estadoBtn .= '<a style="z-index: 0;color: rgb(0, 35, 188); "><i class="bi bi-image fs-3 me-2"></i></a>';
                                $foto = 1;
                                break;
                            }
                        }
                        if ($foto == 0) {
                            $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;" name="foto1"></i>';
                        }
                    }    
                }else {
                     if($row[9] == '1') {
                        $estadoBtn = '
                        <a style="z-index: 0;color:#000;" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[0].'\')" title="Editar Guia"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';
        
                        if($canales == $row[5]){
                            $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresoCerdopdf.php?id='.$row[0].'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
                        }else{
                            $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;""></i>';
                        }

                        $imagen = "SELECT foto FROM recepcion_pesos WHERE id_recepcion = ".$row[0];
                        $rs_imagen = mysqli_query($link, $imagen);
                        $foto = 0;
                        while ($fotoN = mysqli_fetch_row($rs_imagen)) {
                            if ($fotoN[0] != '') {
                                $estadoBtn .= '<a style="z-index: 0;color: rgb(0, 35, 188); "><i class="bi bi-image fs-3 me-2"></i></a>';
                                $foto = 1;
                                break;
                            }
                        }
                        if ($foto == 0) {
                            $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning name="foto1"" style="visibility: hidden;""></i>';
                        }
                     }else{
                        $estadoBtn = '
                        <i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;"" name="status0"></i>';

                        if($canales == $row[5]){
                            $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresoCerdopdf.php?id='.$row[0].'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
                        }else{
                            $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;"" name="canal"></i>';
                        }
                        $imagen = "SELECT foto FROM recepcion_pesos WHERE id_recepcion = ".$row[0];
                        $rs_imagen = mysqli_query($link, $imagen);
                        $foto = 0;
                        while ($fotoN = mysqli_fetch_row($rs_imagen)) {
                            if ($fotoN[0] != '') {
                                $estadoBtn .= '<a style="z-index: 0;color: rgb(0, 35, 188); "><i class="bi bi-image fs-3 me-2"></i></a>';
                                $foto = 1;
                                break;
                            }
                        }
                        if ($foto == 0) {
                            $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-2 text-warning" style="visibility: hidden;"" name="foto1"></i>';
                        }
                     }   
                  }
                    
                  $sql_ob = ("select * from recepcion_pesos where id_recepcion ='".$row[0]."' and (observacion <>'' or foto <>'')");
                  $c_ob = mysqli_query($link, $sql_ob);
                  if($rs_ob = mysqli_fetch_array($c_ob)){
                     if($rs_ob['observacion']!=''){
                        $estadoBtn .= '<a style="z-index: 0;color:#ff8a00;margin: 5px;" ><i class="bi bi-exclamation-diamond fs-2"></i></a>';
                     }else {
                        $estadoBtn .= '<i class="bi bi-pencil-square fs-2 text-warning" style="visibility: hidden;"" name="observacion"></i>';
                     }
                  } else {
                    $estadoBtn .= '<i class="bi bi-pencil-square fs-2 text-warning" style="visibility: hidden;"" name="observacion"></i>';
                }
            }
        } else {
            $sql = ("select count(turno) as listos from recepcion_pesos where id_recepcion =".$row[0]." and inventario>0");
            $rs_operacion = mysqli_query($link, $sql);
            $c_registros = mysqli_fetch_array($rs_operacion);
            if($row[5]==$c_registros['listos']){
                $estadoBtn = '<a style="z-index: 0;color:#000" href="controlador/imprimirdiferenciaspdf.php?id='.$row[0].'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 text-primary"></i></a>';
            }
        }

        if ($totalCambios > 0 && $_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
            $estadoBtn .= '<i class="bi bi-exclamation-diamond fs-2 text-success"></i>';
        } else {
            $estadoBtn .= '<i class="bi bi-exclamation-diamond fs-2 text-success" style="visibility: hidden;"></i>';
        }
    }

    $subdata[] = $estadoBtn;
    $data[] = $subdata;
}
$json_data = array(
    "draw"            => intval($request['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltro),
    "data"            => $data
);
echo json_encode($json_data);
?>
