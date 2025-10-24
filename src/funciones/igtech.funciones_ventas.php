<?php
function listaVentas($i_empresa,$i_anio,$i_mes){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Ventas ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Anio: '.$i_anio);
        $Log->EscribirLog(' Mes: '.$i_mes);
        $ws_conexion=ws_coneccion_bdd();
        if ($i_empresa=='1091786547001'){
            $select_sql="SELECT 
                        fac_empresa,
                        fac_numero,
                        fac_ambiente,
                        fac_tipo_comprobante,
                        fac_tipo_libretin,
                        fecha,
                        est_codigo,
                        pen_serie,
                        fac_secuencial,
                        est_direccion,
                        cl_tipo_identificacion,
                        cl_nombre,
                        cl_identificacion,
                        cl_direccion,
                        cl_telefono,
                        cl_email,
                        fac_subtotal,
                        fac_total_descuento,
                        fac_subtotal_iva,
                        fac_valor_iva,
                        fac_subtotal_cero,
                        fac_subtotal_no_objeto,
                        fac_subtotal_excento,
                        fac_valor_ice,
                        fac_valor_irbpnr,
                        fac_propina,
                        fac_total,
                        fac_guia_remision,
                        fac_comentario,
                        fac_estado,
                        fac_estado_sri,
                        fac_autorizacion,
                        usuario,
                        usu_email,
                        usu_cedula,
                        usu_telefono,
                        usu_placa,
                        usu_tipo_documento   
                    FROM v_sis_datos_facturas_sri
                    where fac_empresa='".$i_empresa."'
                    AND cast(month(fac_fecha) as varchar)='".$i_mes."'
                    AND cast(year(fac_fecha) as varchar)='".$i_anio."'
                    UNION ALL
                    SELECT 
                        fac_empresa,
                        fac_numero,
                        fac_ambiente,
                        fac_tipo_comprobante,
                        fac_tipo_libretin,
                        fecha,
                        est_codigo,
                        pen_serie,
                        fac_secuencial,
                        est_direccion,
                        cl_tipo_identificacion,
                        cl_nombre,
                        cl_identificacion,
                        cl_direccion,
                        cl_telefono,
                        cl_email,
                        fac_subtotal,
                        fac_total_descuento,
                        fac_subtotal_iva,
                        fac_valor_iva,
                        fac_subtotal_cero,
                        fac_subtotal_no_objeto,
                        fac_subtotal_excento,
                        fac_valor_ice,
                        fac_valor_irbpnr,
                        fac_propina,
                        fac_total,
                        fac_guia_remision,
                        fac_comentario,
                        fac_estado,
                        fac_estado_sri,
                        fac_autorizacion,
                        usuario,
                        usu_email,
                        usu_cedula,
                        usu_telefono,
                        usu_placa,
                        usu_tipo_documento   
                    FROM v_del_datos_factura_sri
                    where fac_empresa='".$i_empresa."'
                    AND cast(month(fac_fecha) as varchar)='".$i_mes."'
                    AND cast(year(fac_fecha) as varchar)='".$i_anio."'
                    order by fac_numero;";
            $Log->EscribirLog(' Consulta: '.$select_sql);
        }else{
            $select_sql="SELECT 
                        fac_empresa,
                        fac_numero,
                        fac_ambiente,
                        fac_tipo_comprobante,
                        fac_tipo_libretin,
                        fecha,
                        est_codigo,
                        pen_serie,
                        fac_secuencial,
                        est_direccion,
                        cl_tipo_identificacion,
                        cl_nombre,
                        cl_identificacion,
                        cl_direccion,
                        cl_telefono,
                        cl_email,
                        fac_subtotal,
                        fac_total_descuento,
                        fac_subtotal_iva,
                        fac_valor_iva,
                        fac_subtotal_cero,
                        fac_subtotal_no_objeto,
                        fac_subtotal_excento,
                        fac_valor_ice,
                        fac_valor_irbpnr,
                        fac_propina,
                        fac_total,
                        fac_guia_remision,
                        fac_comentario,
                        fac_estado,
                        fac_estado_sri,
                        fac_autorizacion,
                        usuario,
                        usu_email,
                        usu_cedula,
                        usu_telefono,
                        usu_placa,
                        usu_tipo_documento   
                    FROM v_del_datos_factura_sri
                    where fac_empresa='".$i_empresa."'
                    AND cast(month(fac_fecha) as varchar)='".$i_mes."'
                    AND cast(year(fac_fecha) as varchar)='".$i_anio."'
                    order by fac_numero;";
            $Log->EscribirLog(' Consulta: '.$select_sql);
        }
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'			    =>$row['fac_empresa'],
                    'id_factura'		    =>$row['fac_numero'],
                    'ambiente_sri'		    =>$row['fac_ambiente'],
                    'tipo_comprobante'	    =>$row['fac_tipo_comprobante'],
                    'tipo_libretin'		    =>$row['fac_tipo_libretin'],
                    'fecha'			        =>$row['fecha'],
                    'establecimiento'	    =>$row['est_codigo'],
                    'serie'				    =>$row['pen_serie'],
                    'secuencial'		    =>$row['fac_secuencial'],
                    'direccion'			    =>$row['est_direccion'],
                    'tipo_identificacion'   =>$row['cl_tipo_identificacion'],
                    'identificacion'		=>$row['cl_identificacion'],
                    'nombre'				=>$row['cl_nombre'],
                    'direccion'			    =>$row['cl_direccion'],
                    'telefono'			    =>$row['cl_telefono'],
                    'email'				    =>$row['cl_email'],
                    'subtotal'			    =>$row['fac_subtotal'],
                    'total_descuento'	    =>$row['fac_total_descuento'],
                    'subtotal_iva'		    =>$row['fac_subtotal_iva'],
                    'valor_iva'			    =>$row['fac_valor_iva'],
                    'subtotal_cero'		    =>$row['fac_subtotal_cero'],
                    'subtotal_no_objeto'    =>$row['fac_subtotal_no_objeto'],
                    'subtotal_excento'	    =>$row['fac_subtotal_excento'],
                    'valor_ice'			    =>$row['fac_valor_ice'],
                    'valor_irbpnr'		    =>$row['fac_valor_irbpnr'],
                    'propina'			    =>$row['fac_propina'],
                    'total'				    =>$row['fac_total'],
                    'guia_remision'		    =>$row['fac_guia_remision'],
                    'comentario'		    =>$row['fac_comentario'],
                    'estado'			    =>$row['fac_estado'],
                    'estado_sri'		    =>$row['fac_estado_sri'],
                    'autorizacion'		    =>$row['fac_autorizacion'],
                    'usuario'               =>$row['usuario'],
                    'usu_email'             =>$row['usu_email'],
                    'usu_cedula'            =>$row['usu_cedula'],
                    'usu_placa'             =>$row['usu_placa'],
                    'usu_tipo_documento'    =>$row['usu_tipo_documento'],
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

function listaDetalleVentas($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalle ventas ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Documento: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        if($i_empresa=='1091786547001'){
            $select_sql="SELECT 
                            df_empresa,
                            df_factura,
                            df_producto,
                            pro_codigo_aux,
                            pro_descripcion,
                            df_descripcion,
                            df_cantidad,
                            df_precio_unitario,
                            df_descuento,
                            valor_sin_impuesto,
                            df_base_ice,
                            ice_tarifa,
                            df_porcentaje_ice,
                            df_valor_ice,
                            df_base_iva,
                            iva_porcentaje,
                            df_valor_iva,
                            df_base_irbpnr,
                            df_porcentaje_irbpnr,
                            irbpnr_tarifa,
                            df_valor_irbpnr,
                            df_total
                    FROM 	v_sis_detalle_factura_sri
                    WHERE 	df_factura=".$i_documento."
                    AND df_empresa::text='".$i_empresa."'
					UNION ALL 
					SELECT 
                            df_empresa,
                            df_factura,
                            df_producto,
                            pro_codigo_aux,
                            pro_descripcion,
                            df_descripcion,
                            df_cantidad,
                            df_precio_unitario,
                            df_descuento,
                            valor_sin_impuesto,
                            df_base_ice,
                            ice_tarifa,
                            df_porcentaje_ice::text,
                            df_valor_ice,
                            df_base_iva,
                            iva_porcentaje,
                            df_valor_iva,
                            df_base_irbpnr,
                            df_porcentaje_irbpnr,
                            irbpnr_tarifa,
                            df_valor_irbpnr,
                            df_total
                    FROM 	v_del_detalle_factura_sri
                    WHERE 	df_factura=".$i_documento."
                    AND df_empresa='".$i_empresa."';";
        
        }else{
            $select_sql="SELECT 
                            df_empresa,
                            df_factura,
                            df_producto,
                            pro_codigo_aux,
                            pro_descripcion,
                            df_descripcion,
                            df_cantidad,
                            df_precio_unitario,
                            df_descuento,
                            valor_sin_impuesto,
                            df_base_ice,
                            ice_tarifa,
                            df_porcentaje_ice,
                            df_valor_ice,
                            df_base_iva,
                            iva_porcentaje,
                            df_valor_iva,
                            df_base_irbpnr,
                            df_porcentaje_irbpnr,
                            irbpnr_tarifa,
                            df_valor_irbpnr,
                            df_total
                    FROM 	v_del_detalle_factura_sri
                    WHERE 	df_factura=".$i_documento."
                    AND df_empresa='".$i_empresa."';";
        }
                    $Log->EscribirLog(' Consulta: '.$select_sql);

        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['df_empresa'],
                    'id_factura'=>$row['df_factura'],
                    'codigo'=>$row['df_producto'],
                    'codigo_aux'=>$row['pro_codigo_aux'],
                    'descripcion'=>$row['pro_descripcion'],
                    'info_adicional'=>$row['df_descripcion'],
                    'cantidad'=>$row['df_cantidad'],
                    'precio_unitario'=>$row['df_precio_unitario'],
                    'descuento'=>$row['df_descuento'],
                    'valor_sin_impuesto'=>$row['valor_sin_impuesto'],
                    'base_ice'=>$row['df_base_ice'],
                    'tarifa'=>$row['ice_tarifa'],
                    'porcentaje_ice'=>$row['df_porcentaje_ice'],
                    'valor_ice'=>$row['df_valor_ice'],
                    'base_iva'=>$row['df_base_iva'],
                    'iva_porcentaje'=>$row['iva_porcentaje'],
                    'valor_iva'=>$row['df_valor_iva'],
                    'base_irbpnr'=>$row['df_base_irbpnr'],
                    'porcentaje_irbpnr'=>$row['df_porcentaje_irbpnr'],
                    'irbpnr_tarifa'=>$row['irbpnr_tarifa'],
                    'valor_irbpnr'=>$row['df_valor_irbpnr'],
                    'df_total'=>$row['df_total'],
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

function listaDetalleVentasDebi($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalle Ventas DEBI ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Documento: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        if($i_empresa=='1091786547001'){
            $select_sql="SELECT 
                            df_empresa,
                            df_factura,
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
                    FROM v_detalle_ventas_debifact
                    WHERE 	df_factura=".$i_documento."
                    AND     df_empresa::text='".$i_empresa."'
					UNION ALL 
					SELECT 
                            df_empresa,
                            df_factura,
                            gpv_id::text,
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
                    FROM v_detalle_ventas_debi
                    WHERE 	df_factura=".$i_documento."
                    AND     df_empresa='".$i_empresa."';";
        }else{  

            $select_sql="SELECT 
                            df_empresa,
                            df_factura,
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
                    FROM v_detalle_ventas_debi
                    WHERE 	df_factura=".$i_documento."
                    AND     df_empresa='".$i_empresa."';";
        }
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['df_empresa'],
                    'id_factura'=>$row['df_factura'],
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
                    'total'=>$row['total']
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