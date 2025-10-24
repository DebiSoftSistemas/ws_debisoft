<?php
function listaNotasCreditoCompras($i_empresa,$i_anio,$i_mes){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Notas de Credito Compras ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Anio: '.$i_anio);
        $Log->EscribirLog(' Mes: '.$i_mes);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            nc_empresa,
                            nc_numero,
                            nc_tipo_comprobante,
                            nc_fecha,
                            nc_serie,
                            nc_secuencial,
                            nc_autorizacion,
                            nc_motivo,
                            pr_tipo_identificacion,
                            pr_nombre,
                            pr_identificacion,
                            pr_direccion,
                            pr_telefono,
                            pr_email,
                            nc_cod_docmod,
                            nc_secuencial_docmod,
                            fecha_docmod,
                            nc_subtotal,
                            nc_total_descuento,
                            nc_subtotal_cero,
                            nc_subtotal_iva,
                            nc_subtotal_no_objeto,
                            nc_subtotal_excento,
                            nc_valor_iva,
                            nc_valor_ice,
                            nc_valor_irbpnr,
                            nc_total,
                            nc_estado, 
                            nc_estado_sri	
                    FROM    v_del_datos_nota_credito_compra
                    where nc_empresa='".$i_empresa."'
                    AND cast(month(nc_fecha) as varchar)='".$i_mes."'
                    AND cast(year(nc_fecha) as varchar)='".$i_anio."'
                    order by nc_numero;";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['nc_empresa'],
                    'id_nota_credito'=>$row['nc_numero'],
                    'tipo_comprobante'=>$row['nc_tipo_comprobante'],
                    'fecha'=>$row['nc_fecha'],
                    'serie'=>$row['nc_serie'],
                    'secuencial'=>$row['nc_secuencial'],
                    'autorizacion'=>$row['nc_autorizacion'],
                    'motivo'=>$row['nc_motivo'],
                    'tipo_identificacion'=>$row['pr_tipo_identificacion'],
                    'nombre'=>$row['pr_nombre'],
                    'identificacion'=>$row['pr_identificacion'],
                    'direccion'=>$row['pr_direccion'],
                    'telefono'=>$row['pr_telefono'],
                    'email'=>$row['pr_email'],
                    'tipo_docmod'=>$row['nc_cod_docmod'],
                    'secuencial_docmod'=>$row['nc_secuencial_docmod'],
                    'fecha_docmod'=>$row['fecha_docmod'],
                    'subtotal'=>$row['nc_subtotal'],
                    'total_descuento'=>$row['nc_total_descuento'],
                    'subtotal_cero'=>$row['nc_subtotal_cero'],
                    'subtotal_iva'=>$row['nc_subtotal_iva'],
                    'subtotal_no_objeto'=>$row['nc_subtotal_no_objeto'],
                    'subtotal_excento'=>$row['nc_subtotal_excento'],
                    'valor_iva'=>$row['nc_valor_iva'],
                    'valor_ice'=>$row['nc_valor_ice'],
                    'valor_irbpnr'=>$row['nc_valor_irbpnr'],
                    'total'=>$row['nc_total'],
                    'estado'=>$row['nc_estado'],
                    'estado_sri'=>$row['nc_estado_sri'],
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

function listaDetalleNCCompras($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalles NC Compras ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
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
                        FROM v_del_detalle_nota_credito_compras_sri
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
                    'tarifa_ice'=>$row['ice_tarifa'],
                    'porcentaje_ice'=>$row['dnc_porcentaje_ice'],
                    'valor_ice'=>$row['dnc_valor_ice'],
                    'base_iva'=>$row['dnc_base_iva'],
                    'porcentaje_iva'=>$row['iva_porcentaje'],
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

function listaDetalleNCComprasDebi($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalles NC Compras DEBI ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Documento: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT
                            dnc_empresa,
                            dnc_nota_credito,
                            pro_codigo,
                            pro_codigo_aux,
                            pro_descripcion,
                            dnc_subtotal,
                            dnc_base_ice,
                            ice_codigo,
                            ice_tarifa_especifica,
                            dnc_valor_ice,
                            dnc_base_iva,
                            iva_porcentaje,
                            dnc_valor_iva,
                            irbpnr_codigo,
                            irbpnr_tarifa_especifica,
                            dnc_valor_irbpnr,
                            dnc_total 
                        FROM v_detalle_nc_compra_debi
                        WHERE dnc_nota_credito=".$i_documento."
                        AND dnc_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['dnc_empresa'],
                    'id_nota_credito'=>$row['dnc_nota_credito'],
                    'codigo'=>$row['pro_codigo'],
                    'codigo_aux'=>$row['pro_codigo_aux'],
                    'descripcion'=>$row['pro_descripcion'],
                    'subtotal'=>$row['dnc_subtotal'],
                    'base_ice'=>$row['dnc_base_ice'],
                    'codigo_ice'=>$row['ice_codigo'],
                    'tarifa_ice'=>$row['ice_tarifa_especifica'],
                    'valor_ice'=>$row['dnc_valor_ice'],
                    'base_iva'=>$row['dnc_base_iva'],
                    'porcentaje_iva'=>$row['iva_porcentaje'],
                    'valor_iva'=>$row['dnc_valor_iva'],
                    'irbpnr_codigo'=>$row['irbpnr_codigo'],
                    'tarifa_irbpnr'=>$row['irbpnr_tarifa_especifica'],
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
?>