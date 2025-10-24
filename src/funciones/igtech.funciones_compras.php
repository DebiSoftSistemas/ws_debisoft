<?php
function listaCompras($i_empresa,$i_mes,$i_anio){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Compras ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Mes: '.$i_mes);
        $Log->EscribirLog(' Anio: '.$i_anio);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT fc_id,
                            fc_empresa,
                            fc_tipo_comprobante,
                            tc_nombre,
                            fc_proveedor,
                            pr_nombre,
                            pr_identificacion,
                            pr_direccion,
                            pr_email,
                            fc_fecha,
                            fc_serie,
                            fac_secuencial,
                            fc_autorizacion,
                            fc_comentario,
                            fc_subtotal_iva,
                            fc_subtotal_cero,
                            fc_subtotal_no_objeto,
                            fc_subtotal_excento,
                            fc_total_descuento,
                            fc_subtotal,
                            fc_valor_ice,
                            fc_valor_iva,
                            fc_valor_irbpnr,
                            fc_total,	
                            lib_tipo_libretin,
                            fc_usuario,
                            fc_estado,
                            liq_estado_sri	 
                        FROM v_del_datos_factura_compra
                        WHERE fc_empresa='".$i_empresa."'
                        AND cast(month(fc_fecha) as varchar)='".$i_mes."'
                        AND cast(year(fc_fecha) as varchar)='".$i_anio."'
                        order by fc_id;";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'id'=>                  $row['fc_id'],
                    'empresa'=>             $row['fc_empresa'],
                    'tipo_comprobante'=>    $row['fc_tipo_comprobante'],
                    'comprobante'=>         $row['tc_nombre'],
                    'id_proveedor'=>        $row['fc_proveedor'],
                    'proveedor'=>           $row['pr_nombre'],
                    'identificacion'=>      $row['pr_identificacion'],
                    'direccion'=>           $row['pr_direccion'],
                    'email'=>               $row['pr_email'],
                    'fecha'=>               $row['fc_fecha'],
                    'serie'=>               $row['fc_serie'],
                    'secuencial'=>          $row['fac_secuencial'],
                    'autorizacion'=>        $row['fc_autorizacion'],
                    'comentario'=>          $row['fc_comentario'],
                    'subtotal_iva'=>        $row['fc_subtotal_iva'],
                    'subtotal_cero'=>       $row['fc_subtotal_cero'],
                    'subtotal_no_objeto'=>  $row['fc_subtotal_no_objeto'],
                    'subtotal_excento'=>    $row['fc_subtotal_excento'],
                    'total_descuento'=>     $row['fc_total_descuento'],
                    'subtotal'=>            $row['fc_subtotal'],
                    'valor_ice'=>           $row['fc_valor_ice'],
                    'valor_iva'=>           $row['fc_valor_iva'],
                    'valor_irbpnr'=>        $row['fc_valor_irbpnr'],
                    'total'=>               $row['fc_total'],
                    'tipo_libretin'=>       $row['lib_tipo_libretin'],
                    'usuario'=>             $row['fc_usuario'],
                    'estado'=>              $row['fc_estado'],
                    'estado_sri'=>          $row['liq_estado_sri']
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }
        $close = pg_close($ws_conexion);
        }catch(Throwable $e){
            $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
        }
        $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
        return $o_respuesta;
}

function listaDetallesCompras($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalles Compras ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Factura: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT  
                            dfc_empresa,
                            dfc_factura,
                            codigo,
                            codigo_aux,
                            fp_descripcion,
                            dfc_cantidad,
                            dfc_precio_unitario,
                            dfc_descuento,
                            valor_sin_impuesto,
                            dfc_base_ice,
                            ice_tarifa,
                            dfc_porcentaje_ice,
                            dfc_valor_ice,
                            dfc_base_iva,
                            iva_porcentaje,
                            dfc_valor_iva,
                            dfc_base_irbpnr,
                            dfc_porcentaje_irbpnr,
                            irbpnr_tarifa,
                            dfc_valor_irbpnr,
                            dfc_total,
                            dfc_descripcion
                    FROM v_del_detalle_liquidacion_sri
                    WHERE dfc_factura=".$i_documento."
                    AND dfc_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['dfc_empresa'],
                    'factura'=>$row['dfc_factura'],
                    'codigo'=>$row['codigo'],
                    'codigo_aux'=>$row['codigo_aux'],
                    'descripcion'=>$row['fp_descripcion'],
                    'info_adicional'=>$row['dfc_descripcion'],
                    'cantidad'=>$row['dfc_cantidad'],
                    'precio_unitario'=>$row['dfc_precio_unitario'],
                    'descuento'=>$row['dfc_descuento'],
                    'valor_sin_impuesto'=>$row['valor_sin_impuesto'],
                    'base_ice'=>$row['dfc_base_ice'],
                    'tarifa'=>$row['ice_tarifa'],
                    'porcentaje_ice'=>$row['dfc_porcentaje_ice'],
                    'valor_ice'=>$row['dfc_valor_ice'],
                    'base_iva'=>$row['dfc_base_iva'],
                    'porcentaje'=>$row['iva_porcentaje'],
                    'valor_iva'=>$row['dfc_valor_iva'],
                    'base_irbpnr'=>$row['dfc_base_irbpnr'],
                    'porcentaje_irbpnr'=>$row['dfc_porcentaje_irbpnr'],
                    'irbpnr_tarifa'=>$row['irbpnr_tarifa'],
                    'valor_irbpnr'=>$row['dfc_valor_irbpnr'],
                    'total'=>$row['dfc_total'],
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }
        $close = pg_close($ws_conexion);
        }catch(Throwable $e){
            $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
        }
        $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
        return $o_respuesta;
}

function listaDetalleComprasDebi($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalles Compras DEBI ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Factura: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT dfc_empresa,
                            dfc_factura,
                            pro_codigo,
                            pro_codigo_aux,
                            pro_descripcion,
                            dfc_subtotal,
                            dfc_base_ice,
                            ice_codigo,
                            ice_tarifa_especifica,
                            dfc_valor_ice,
                            dfc_base_iva,
                            iva_porcentaje,
                            dfc_valor_iva,
                            irbpnr_codigo,
                            irbpnr_tarifa_especifica,
                            dfc_valor_irbpnr,
                            dfc_total 
                    FROM v_detalle_factura_compra_debi
                    WHERE dfc_factura=".$i_documento."
                    AND dfc_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['dfc_empresa'],
                    'factura'=>$row['dfc_factura'],
                    'codigo'=>$row['pro_codigo'],
                    'codigo_aux'=>$row['pro_codigo_aux'],
                    'descripcion'=>$row['pro_descripcion'],
                    'subtotal'=>$row['dfc_subtotal'],
                    'base_ice'=>$row['dfc_base_ice'],
                    'codigo'=>$row['ice_codigo'],
                    'tarifa_especifica'=>$row['ice_tarifa_especifica'],
                    'valor_ice'=>$row['dfc_valor_ice'],
                    'base_iva'=>$row['dfc_base_iva'],
                    'iva_porcentaje'=>$row['iva_porcentaje'],
                    'valor_iva'=>$row['dfc_valor_iva'],
                    'irbpnr_codigo'=>$row['irbpnr_codigo'],
                    'irbpnr_tarifa_especifica'=>$row['irbpnr_tarifa_especifica'],
                    'valor_irbpnr'=>$row['dfc_valor_irbpnr'],
                    'total'=>$row['dfc_total']
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }
        $close = pg_close($ws_conexion);
        }catch(Throwable $e){
            $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
        }
        $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
        return $o_respuesta;
}

?>