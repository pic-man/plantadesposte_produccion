<?php
//session_start();
//error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$fecha = $_SESSION['fecha'];
$listaSedes = listaSedeComprador();
//$listaPrecios = listaSugeridosCompleta($fecha);
//$totalData = mysqli_num_rows($listaPrecios);

//if (!empty($busqueda)) {
    //$listaPrecios = listaSugeridosConFiltro($busqueda, $fecha);
  //  $totalFiltro = mysqli_num_rows($listaPrecios);
//} else {
//    $totalFiltro = $totalData;
//}

$listaPrecios = listaSugeridosPaginada($inicio, $fin, $busqueda, $fecha, $listaSedes); 

$data = array();
$cont = 1;

include ("../config.php");

if($listaPrecios!=0){
    
  while ($row = mysqli_fetch_array($listaPrecios)) {

    $total = 0;
    $btns = '';
    $subdata = array();
    $subdata[] = $cont;
    $subdata[] = $row[0];
    $subdata[] = $row[1];
    $subdata[] = $row[2];
    $num_elementos = count($listaSedes);

    $itemSugerido = array();
    $imprimir = 1;
    for($i=3;$i<=($num_elementos+3);$i++){
        if($i<($num_elementos+3)){ 
          $sql11="SELECT empresa,cod,nombre FROM sede WHERE campo='".$listaSedes[$i-3]."'";
          $result11=mysqli_query($link,$sql11);
          $row11=mysqli_fetch_array($result11);
      
          $empresa = $row11['empresa'];
          $cod = $row11['cod'];
          $nombre = $row11['nombre'];
          
          $sql1="SELECT sum(cantidad) as total FROM item_proveedor_compra 
          WHERE item='".$row[0]."' and sede='".$cod."' and empresa='".$empresa."' and fecha='".$fecha."'";
          $result1=mysqli_query($link,$sql1);
          if($row1=mysqli_fetch_array($result1)){
            $total = $total + $row1['total'];
            $sede = $row1['total'];
          }   
            $subdata[] = $sede." / ".$row[$i];
            $cuenta = $row[$i] - $sede;
            $itemSugerido[] = array('sede' => $nombre, 'sugerido' => $cuenta, 'faltante' => $cuenta);
       
        }else{
            $subdata[] = $total." / ".$row[$i];
             if($total >= $row[$i]){
                $imprimir=0;
            }else{
                if($total>0){
                    $imprimir=2;
                    $cont++;     
                }else{
                    $imprimir=1;
                    $cont++;  
                }
            } 
         }
        }

            $itemSugeridoData = json_encode($itemSugerido);
            $jsonCadena = htmlspecialchars(json_encode($itemSugeridoData), ENT_QUOTES, 'UTF-8');

            if($imprimir==1){
                $subdata[] ="<font color='000000'>PENDIENTE</font>";
                $estadoBtn = "<br><center><a class='btn btn-primary' style='z-index: 0;color:#000' class='btn btn-info' data-target='#modalCriterios' onclick=\"abrirModal('$row[1]');buscarProveedorPorItem('$row[0]', $jsonCadena)\" data-toggle='modal'>Comprar</a></center><br>";
            }elseif($imprimir==2){
                $subdata[] ="<font color='FF0000'>INCOMPLETO</font>";
                $estadoBtn = "<br><center><a class='btn btn-primary' style='z-index: 0;color:#000' class='btn btn-info' data-target='#modalCriterios' onclick=\"abrirModal('$row[1]');buscarProveedorPorItem('$row[0]', $jsonCadena)\" data-toggle='modal'>Comprar</a></center><br>";
            }
            else{
                $subdata[] ="<font color='00FF00'>COMPLETO</font>";
                $estadoBtn = "<br><center><a data-target='#modalCriterios' onclick=\"abrirModal('$row[1]');buscarProveedorPorItem('$row[0]', $jsonCadena)\" data-toggle='modal'><img src='images/lapiz.png' width='20' heigth='30'></a></center><br>";
            }
            $subdata[] = $estadoBtn;
            $data[] = $subdata;
       
    }
}else{
    $listaItem = listaItemsCompleta($inicio, $fin, $busqueda);
    while ($row = mysqli_fetch_array($listaItem)) {
        
        $total = 0;
        $btns = '';
        $subdata = array();
        $subdata[] = $cont;
        $subdata[] = $row[0];
        $subdata[] = $row[1];
        $subdata[] = $row[2];

        //$subdata[] = '0';
        //$subdata[] = '0';
        //$subdata[] = '0';

        //$subdata[] = '0';
        //$subdata[] = '0';
        //$subdata[] = '0';
        $num_elementos = count($listaSedes);
        $itemSugerido = array();
        $imprimir = 1;
        for($i=3;$i<=($num_elementos+3);$i++){
            if($i<($num_elementos+3)){ 
              $sql11="SELECT empresa,cod,nombre FROM sede WHERE campo='".$listaSedes[$i-3]."'";
              $result11=mysqli_query($link,$sql11);
              $row11=mysqli_fetch_array($result11);
          
              $empresa = $row11['empresa'];
              $cod = $row11['cod'];
              $nombre = $row11['nombre'];
              
              $sql1="SELECT sum(cantidad) as total FROM item_proveedor_compra 
              WHERE item='".$row[0]."' and sede='".$cod."' and empresa='".$empresa."' and fecha='".$fecha."'";
              $result1=mysqli_query($link,$sql1);
              if($row1=mysqli_fetch_array($result1)){
                $total = $total + $row1['total'];
                $sede = $row1['total'];
              }   
                $subdata[] = $sede;
                $itemSugerido[] = array('sede' => $nombre, 'sugerido' => $row[$i]);
           
            }else{
                $subdata[] = $total;
                 if($total >= $row[$i]){
                    $imprimir=0;
                }else{
                    $imprimir=1;
                    $cont++;
                } 
             }
            }
    
                $itemSugeridoData = json_encode($itemSugerido);
                $jsonCadena = htmlspecialchars(json_encode($itemSugeridoData), ENT_QUOTES, 'UTF-8');
    
                $subdata[] ="<font color='000000'>C. PROG</font>";
                $estadoBtn = "<br><center><a class='btn btn-primary' style='z-index: 0;color:#000' class='btn btn-info' data-target='#modalCriterios' onclick=\"abrirModal('$row[1]');buscarProveedorPorItem('$row[0]', $jsonCadena)\" data-toggle='modal'>Programar</a></center><br>";
                
                $subdata[] = $estadoBtn;
                $data[] = $subdata;
        
        }

}

$json_data = array(
    "draw"            => intval($request['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltro),
    "data"            => $data
);
echo json_encode($json_data);
?>
