<?php
include('../config.php');

$datos = $_POST["datos"] ? $_POST["datos"] : "";

$cantidad = $datos["cantidad"];
$especie = $datos["especie"];

if ($especie == "RES") {
    $sql = "SELECT descripcion, KgxRes, PorcentajeParte, item FROM plantilla WHERE item = '073617' OR item = '073618' OR item = '073619' OR item = '073620' OR item = '073624' OR item = '073625' OR item = '073626' OR item = '073627' OR item = '073628' OR item = '073631' OR item = '006411' OR item = '037508' OR item = '073633' OR item = '073621' OR item = '073622' OR item = '073623' OR item = '073629' OR item = '073630' OR item = '073632' OR item = '007760' OR item = '005831' OR item = '009276' OR item = '033647'";
}else{
    $sql = "SELECT descripcion, KgxRes, PorcentajeParte, item FROM plantilla WHERE item = '073612' OR item = '073613' OR item = '073614' OR item = '073615' OR item = '073616' OR item = '009251' OR item = '073559' OR item = '005800' OR item = '009270' OR item = '005819' OR item = '007844' OR item = '040959' OR item = '033700'";
}


$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();
    
    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[1] * $cantidad . "</center>";
    if ($row[3] == "073632" || $row[3] == "007760" || $row[3] == "005831" || $row[3] == "009276" || $row[3] == "033647") {
        $subdata[] = "<center><input style='visibility: hidden;' type='text' name='".$row[0]."' disabled class='form-control'></center>";
        $subdata[] = "<center><input style='visibility: hidden;' type='text' name='".$row[0]."' disabled class='form-control'></center>";
    } else {
        $subdata[] = "
            <center>
                <select class='form-select' name=".$row[3].">
                    <option value='0'>Seleccione una opcion</option>
                    <option value='NECESIDAD'>NECESIDAD</option>
                    <option value='SOBRANTE'>SOBRANTE</option>
                </select>
            </center>";
        $subdata[] = "<center><input type='number' name=".$row[3]." class='form-control' placeholder='Ingrese el sobrante'></center>";
    }
    
    

    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>
