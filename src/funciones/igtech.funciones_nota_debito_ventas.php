<?php
function listaNotaDebitoVentas($i_empresa,$i_anio,$i_mes){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Notas Debito Ventas ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Anio: '.$i_anio);
        $Log->EscribirLog(' Mes: '.$i_mes);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        nd_empresa,
                        nd_numero,
                        nd_ambiente,
                        nd_tipo_comprobante,
                        nd_tipo_libretin,
                        nd_fecha,
                        est_codigo,
                        pen_serie,
                        nd_secuencial,
                        est_direccion,
                        cl_identificacion,
                        cl_tipo_identificacion,
                        cl_nombre,
                        cl_direccion,
                        cl_telefono,
                        cl_email,
                        nd_cod_docmod,
                        nd_serie_docmod,
                        nd_secuencial_docmod,
                        nd_fecha_docmod,
                        nd_subtotal,
                        nd_subtotal_iva,
                        nd_subtotal_cero,
                        nd_subtotal_no_objeto,
                        nd_subtotal_excento,
                        nd_valor_ice,
                        nd_valor_iva,
                        nd_total,
                        nd_estado,
                        nd_estado_sri,
                        nd_autorizacion	 
                    FROM v_del_datos_nota_debito_sri
                    where nd_empresa='".$i_empresa."'
                    AND cast(month(nd_fecha) as varchar)='".$i_mes."'
                    AND cast(year(nd_fecha) as varchar)='".$i_anio."'
                    order by nd_numero;";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['nd_empresa'],
                    'id_nota_debito'=>$row['nd_numero'],
                    'ambiente'=>$row['nd_ambiente'],
                    'tipo_comprobante'=>$row['nd_tipo_comprobante'],
                    'tipo_libretin'=>$row['nd_tipo_libretin'],
                    'fecha'=>$row['nd_fecha'],
                    'establecimiento'=>$row['est_codigo'],
                    'serie'=>$row['pen_serie'],
                    'secuencial'=>$row['nd_secuencial'],
                    'direccion'=>$row['est_direccion'],
                    'identificacion'=>$row['cl_identificacion'],
                    'tipo_identificacion'=>$row['cl_tipo_identificacion'],
                    'nombre'=>$row['cl_nombre'],
                    'direccion'=>$row['cl_direccion'],
                    'telefono'=>$row['cl_telefono'],
                    'email'=>$row['cl_email'],
                    'tipo_docmod'=>$row['nd_cod_docmod'],
                    'serie_docmod'=>$row['nd_serie_docmod'],
                    'secuencial_docmod'=>$row['nd_secuencial_docmod'],
                    'fecha_docmod'=>$row['nd_fecha_docmod'],
                    'subtotal'=>$row['nd_subtotal'],
                    'subtotal_iva'=>$row['nd_subtotal_iva'],
                    'subtotal_cero'=>$row['nd_subtotal_cero'],
                    'subtotal_no_objeto'=>$row['nd_subtotal_no_objeto'],
                    'subtotal_excento'=>$row['nd_subtotal_excento'],
                    'valor_ice'=>$row['nd_valor_ice'],
                    'valor_iva'=>$row['nd_valor_iva'],
                    'total'=>$row['nd_total'],
                    'estado'=>$row['nd_estado'],
                    'estado_sri'=>$row['nd_estado_sri'],
                    'autorizacion'=>$row['nd_autorizacion'],
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

function listaDetallesNdVentas($i_empresa,$i_documento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalles ND Ventas ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Documento: '.$i_documento);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            dnd_empresa,
                            dnd_nota_debito,
                            dnd_producto,
                            pro_descripcion,
                            dnd_precio_unitario,
                            dnd_subtotal,
                            dnd_base_ice,
                            dnd_porcentaje_ice,
                            ice_codigo,
                            ice_tarifa_especifica,
                            dnd_valor_ice,
                            dnd_base_iva,
                            iva_porcentaje,
                            dnd_valor_iva,
                            dnd_total
                        FROM v_del_detalle_nota_debito_sri
                        WHERE dnd_nota_debito=".$i_documento."
                        AND dnd_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);            
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>                 $row['dnd_empresa'],
                    'id_nota_debito'=>          $row['dnd_nota_debito'],
                    'codigo'=>                  $row['dnd_producto'],
                    'descripcion'=>             $row['pro_descripcion'],
                    'precio_unitario'=>         $row['dnd_precio_unitario'],
                    'subtotal'=>                $row['dnd_subtotal'],
                    'base_ice'=>                $row['dnd_base_ice'],
                    'porcentaje_ice'=>          $row['dnd_porcentaje_ice'],
                    'ice_codigo'=>              $row['ice_codigo'],
                    'ice_tarifa_especifica'=>   $row['ice_tarifa_especifica'],
                    'valor_ice'=>               $row['dnd_valor_ice'],
                    'base_iva'=>                $row['dnd_base_iva'],
                    'iva_porcentaje'=>          $row['iva_porcentaje'],
                    'valor_iva'=>               $row['dnd_valor_iva'],
                    'total'=>                   $row['dnd_total'],
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