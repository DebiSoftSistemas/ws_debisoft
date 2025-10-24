<?php
function listaRetencionesCompras($i_empresa,$i_anio,$i_mes){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Retenciones Compras ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Anio: '.$i_anio);
        $Log->EscribirLog(' Mes: '.$i_mes);
        
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            ret_empresa,
                            ret_numero,
                            ret_ambiente,
                            ret_tipo_comprobante,
                            fecha,
                            ret_tipo_libretin,
                            est_codigo,
                            pen_serie,
                            ret_secuencial,
                            est_direccion,
                            pr_tipo_identificacion,
                            pr_nombre,
                            pr_identificacion,
                            pr_direccion,
                            pr_telefono,
                            pr_email,
                            ret_comentario,
                            ret_estado,
                            ret_estado_sri,
                            ret_periodo_fiscal 
                    FROM v_del_datos_retencion_sri
                    WHERE ret_empresa='".$i_empresa."'
                    AND cast(month(ret_fecha) as varchar)='".$i_mes."'
                    AND cast(year(ret_fecha) as varchar)='".$i_anio."'
                    order by ret_numero;";
        $Log->EscribirLog(' Consulta: '.$select_sql);            
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['ret_empresa'],
                    'id_retencion'=>$row['ret_numero'],
                    'ambiente'=>$row['ret_ambiente'],
                    'tipo_comprobante'=>$row['ret_tipo_comprobante'],
                    'fecha'=>$row['fecha'],
                    'tipo_libretin'=>$row['ret_tipo_libretin'],
                    'establecimiento'=>$row['est_codigo'],
                    'serie'=>$row['pen_serie'],
                    'secuencial'=>$row['ret_secuencial'],
                    'direccion'=>$row['est_direccion'],
                    'tipo_identificacion'=>$row['pr_tipo_identificacion'],
                    'nombre'=>$row['pr_nombre'],
                    'identificacion'=>$row['pr_identificacion'],
                    'direccion'=>$row['pr_direccion'],
                    'telefono'=>$row['pr_telefono'],
                    'email'=>$row['pr_email'],
                    'comentario'=>$row['ret_comentario'],
                    'estado'=>$row['ret_estado'],
                    'estado_sri'=>$row['ret_estado_sri'],
                    'periodo_fiscal'=>$row['ret_periodo_fiscal'],
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

function listaDetalleRetencionesCompras($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalle Retenciones Compras');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Documento: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT dr_empresa,
                            dr_retencion,
                            dr_impuesto,
                            imr_descripcion,
                            pri_codigo,
                            pri_descripcion,
                            dr_base_imponible,
                            dr_porcentaje_retencion,
                            dr_valor_retenido,
                            dr_cod_doc_sustento,
                            doc_sustento,
                            fecha        
                    FROM v_del_detalle_retencion_sri
                    WHERE dr_retencion=".$i_documento."
                    AND dr_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['dr_empresa'],
                    'id_retencion'=>$row['dr_retencion'],
                    'impuesto'=>$row['dr_impuesto'],
                    'descripcion'=>$row['imr_descripcion'],
                    'codigo'=>$row['pri_codigo'],
                    'descripcion'=>$row['pri_descripcion'],
                    'base_imponible'=>$row['dr_base_imponible'],
                    'porcentaje_retencion'=>$row['dr_porcentaje_retencion'],
                    'valor_retenido'=>$row['dr_valor_retenido'],
                    'cod_doc_sustento'=>$row['dr_cod_doc_sustento'],
                    'sustento'=>$row['doc_sustento'],
                    'fecha'=>$row['fecha'],
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