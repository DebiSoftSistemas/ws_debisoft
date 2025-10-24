<?php
function listaNotaCreditoVentas($i_empresa,$i_anio,$i_mes){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Nota Credito Ventas ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Anio: '.$i_anio);
        $Log->EscribirLog(' Mes: '.$i_mes);

        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        nc_empresa,
                        nc_numero,
                        nc_ambiente,
                        nc_tipo_comprobante,
                        fecha,
                        est_codigo,
                        pen_serie,
                        nc_secuencial,
                        nc_tipo_libretin,
                        est_direccion,
                        cl_tipo_identificacion,
                        cl_nombre,
                        cl_identificacion,
                        cl_direccion,
                        cl_telefono,
                        cl_email,
                        nc_cod_docmod,
                        nc_secuencial_docmod,
                        fecha_docmod,
                        nc_subtotal,
                        nc_total_descuento,
                        nc_subtotal_iva,
                        nc_valor_iva,
                        nc_subtotal_cero,
                        nc_subtotal_no_objeto,
                        nc_subtotal_excento,
                        nc_valor_ice,
                        nc_valor_irbpnr,
                        nc_total,
                        nc_motivo,
                        nc_estado,
                        nc_estado_sri,
                        nc_autorizacion 
                    FROM v_del_datos_nota_credito_sri
                    WHERE nc_empresa='".$i_empresa."'
                    AND year(nc_fecha)=".$i_anio."
                    AND month(nc_fecha)=".$i_mes.";";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['nc_empresa'],
                    'id_notacredito'=>$row['nc_numero'],
                    'ambiente'=>$row['nc_ambiente'],
                    'tipo_comprobante'=>$row['nc_tipo_comprobante'],
                    'fecha'=>$row['fecha'],
                    'establecimiento'=>$row['est_codigo'],
                    'serie'=>$row['pen_serie'],
                    'secuencial'=>$row['nc_secuencial'],
                    'tipo_libretin'=>$row['nc_tipo_libretin'],
                    'direccion'=>$row['est_direccion'],
                    'tipo_identificacion'=>$row['cl_tipo_identificacion'],
                    'nombre'=>$row['cl_nombre'],
                    'identificacion'=>$row['cl_identificacion'],
                    'direccion'=>$row['cl_direccion'],
                    'telefono'=>$row['cl_telefono'],
                    'email'=>$row['cl_email'],
                    'serie_docmod'=>$row['nc_cod_docmod'],
                    'secuencial_docmod'=>$row['nc_secuencial_docmod'],
                    'fecha_docmod'=>$row['fecha_docmod'],
                    'subtotal'=>$row['nc_subtotal'],
                    'total_descuento'=>$row['nc_total_descuento'],
                    'subtotal_iva'=>$row['nc_subtotal_iva'],
                    'valor_iva'=>$row['nc_valor_iva'],
                    'subtotal_cero'=>$row['nc_subtotal_cero'],
                    'subtotal_no_objeto'=>$row['nc_subtotal_no_objeto'],
                    'subtotal_excento'=>$row['nc_subtotal_excento'],
                    'valor_ice'=>$row['nc_valor_ice'],
                    'valor_irbpnr'=>$row['nc_valor_irbpnr'],
                    'total'=>$row['nc_total'],
                    'motivo'=>$row['nc_motivo'],
                    'estado'=>$row['nc_estado'],
                    'estado_sri'=>$row['nc_estado_sri'],
                    'autorizacion'=>$row['nc_autorizacion']
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

function listaDetalleNcVentas($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalles NC Ventas ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '. $i_empresa);
        $Log->EscribirLog(' Documento: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            dnc_empresa,
                            dnc_nota_credito,
                            dnc_producto,
                            pro_codigo_aux,
                            pro_descripcion,
                            dnc_descripcion,
                            dnc_cantidad,
                            dnc_precio_unitario,
                            dnc_descuento,
                            valor_sin_impuesto,
                            dnc_base_ice,
                            ice_tarifa,
                            dnc_porcentaje_ice,
                            dnc_valor_ice,
                            dnc_base_iva,
                            iva_porcentaje,
                            dnc_valor_iva,
                            dnc_base_irbpnr,
                            irbpnr_tarifa,
                            dnc_porcentaje_irbpnr,
                            dnc_valor_irbpnr,
                            dnc_total
                        FROM v_del_detalle_nota_credito_sri
                        WHERE dnc_nota_credito=".$i_documento."
                        AND dnc_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['dnc_empresa'],
                    'id_nota_credito'=>$row['dnc_nota_credito'],
                    'codigo'=>$row['dnc_producto'],
                    'codigo_aux'=>$row['pro_codigo_aux'],
                    'descripcion'=>$row['pro_descripcion'],
                    'info_adicional'=>$row['dnc_descripcion'],
                    'cantidad'=>$row['dnc_cantidad'],
                    'precio_unitario'=>$row['dnc_precio_unitario'],
                    'descuento'=>$row['dnc_descuento'],
                    'valor_sin_impuesto'=>$row['valor_sin_impuesto'],
                    'base_ice'=>$row['dnc_base_ice'],
                    'ice_tarifa'=>$row['ice_tarifa'],
                    'porcentaje_ice'=>$row['dnc_porcentaje_ice'],
                    'valor_ice'=>$row['dnc_valor_ice'],
                    'base_iva'=>$row['dnc_base_iva'],
                    'iva_porcentaje'=>$row['iva_porcentaje'],
                    'valor_iva'=>$row['dnc_valor_iva'],
                    'base_irbpnr'=>$row['dnc_base_irbpnr'],
                    'irbpnr_tarifa'=>$row['irbpnr_tarifa'],
                    'porcentaje_irbpnr'=>$row['dnc_porcentaje_irbpnr'],
                    'valor_irbpnr'=>$row['dnc_valor_irbpnr'],
                    'total'=>$row['dnc_total'],
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

function listaDetalleNcVentasDebi($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalles NC Ventas Debi ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Documento: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            dnc_empresa,
                            dnc_nota_credito,
                            gpv_id,
                            cgv_descripcion,
                            subtotal,
                            codigo_ice,
                            tarifa_ice,
                            ice,
                            base_iva,
                            porcentaje_iva,
                            iva,
                            irbpnr_codigo,
                            irbpnr,
                            total 
                    FROM v_detalle_nc_ventas_debi
                    WHERE dnc_nota_credito=".$i_documento."
                    AND dnc_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['dnc_empresa'],
                    'id_nota_credito'=>$row['dnc_nota_credito'],
                    'codigo'=>$row['gpv_id'],
                    'codigo_aux'=>$row['gpv_id'],
                    'descripcion'=>$row['cgv_descripcion'],
                    'subtotal'=>$row['subtotal'],
                    'codigo_ice'=>$row['codigo_ice'],
                    'tarifa_ice'=>$row['tarifa_ice'],
                    'ice'=>$row['ice'],
                    'base_iva'=>$row['base_iva'],
                    'porcentaje_iva'=>$row['porcentaje_iva'],
                    'iva'=>$row['iva'],
                    'irbpnr_codigo'=>$row['irbpnr_codigo'],
                    'irbpnr'=>$row['irbpnr'],
                    'total'=>$row['total'],
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