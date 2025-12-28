function agregarItemsPorProveedor(){
    $proveedores = listaNitProveedores();
    
    foreach ($proveedores as $proveedor) {
        $itemsProveedor = listaItemsPorProveedorInicial($proveedor[0]);
         
        foreach ($itemsProveedor as $item) {
                 $nitSinDigito = explode('-', $proveedor[0]);
                 $insertQuery = "INSERT INTO cotizacion_proveedor
                            (nit_proveedor,
                            nit_proveedor_cod,
                            codigo_sucursal,
                            descripcion_sucursal,
                            codigo_barra,
                            id_item,
                            id_impuesto,
                            descripcion_item,
                            unidad_medida,
                            fecha_vigencia,
                            valor_sin_iva,
                            cantidad_descuento_1,
                            porcentaje_descuento_1,
                            cantidad_descuento_2,
                            porcentaje_descuento_2,
                            valor_descuento_1,
                            valor_descuento_2,
                            tasa_iva)
                            VALUES (
                                '" . $proveedor[0] . "',
                                '" . $nitSinDigito[0] . "',
                                '" . $item['codigo_sucursal'] . "',
                                '" . $item['descripcion_sucursal'] . "',
                                '" . $item['codigo_barra'] . "',
                                '" . $item['id_item'] . "',
                                '" . $item['id_impuesto'] . "',
                                '" . $item['descripcion_item'] . "',
                                '" . $item['unidad_medida'] . "',
                                '" . $item['fecha_vigencia'] . "',
                                " . $item['valor_sin_iva'] . ",
                                " . $item['cantidad_descuento_1'] . ",
                                " . $item['porcentaje_descuento_1'] . ",
                                " . $item['cantidad_descuento_2'] . ",
                                " . $item['porcentaje_descuento_2'] . ",
                                " . $item['valor_descuento_1'] . ",
                                " . $item['valor_descuento_2'] . ",
                                " . $item['tasa_iva'] . "
                            )";

            if (!@mysqli_query($con, $insertQuery)) {
                return "Error al insertar el nuevo registro: " . mysqli_error($con);
            }
            
        }     
    }
    return "Registros insertados correctamente";
} 