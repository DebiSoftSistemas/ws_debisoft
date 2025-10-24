<?php

function listaFormasPagoSRI(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Formas de Pago SRI ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT 
                        fp_codigo,
                        fp_descripcion 
                        FROM sri_forma_pago
                        WHERE fp_estado='V'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $fp_codigo=$row['fp_codigo'];        
                $fp_descripcion=$row['fp_descripcion'];
                $w_respuesta[] = array(
                    'codigo' =>$fp_codigo,
                    'descripcion'=>$fp_descripcion
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);    
        }else{
           $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }    
        //desconectamos la base de datos
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }   
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function listaTipoIdentificacionSRI(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA TIPO DE IDNTIFICACION ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        ti_codigo,
                        ti_nombre 
        FROM sri_tipo_identificacion
        where ti_cliente='S'
        and ti_codigo not in ('08','09')";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'codigo'=>$row['ti_codigo'],
                    'nombre'=>$row['ti_nombre'],
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

function listaTarifasIVA(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT iva_codigo,
                            iva_descripcion,
                            iva_porcentaje,
                            iva_estado 
                        FROM sri_tarifa_iva";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'codigo'=>$row['iva_codigo'],
                    'descripcion'=>$row['iva_descripcion'],
                    'porcentaje'=>$row['iva_porcentaje'],
                    'estado'=>$row['iva_estado'],
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

function listaTarifasICE(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        ice_id, 
                        ice_codigo,
                        ice_descripcion,
                        ice_tarifa_especifica,
                        ice_tarifa_advaloren,
                        ice_estado
                    FROM sri_tarifa_ice
                    order by ice_id";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'id'=>$row['ice_id'],
                    'codigo'=>$row['ice_codigo'],
                    'descripcion'=>$row['ice_descripcion'],
                    'tarifa_especifica'=>$row['ice_tarifa_especifica'],
                    'tarifa_advaloren'=>$row['ice_tarifa_advaloren'],
                    'estado'=>$row['ice_estado'],
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

function listaTarifasIRBPNR(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        irbpnr_codigo,
                        irbpnr_descripcion,
                        irbpnr_tarifa_especifica,
                        irbpnr_tarifa_advaloren,
                        irbpnr_estado 
                    FROM sri_tarifa_irbpnr";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'codigo'=>$row['irbpnr_codigo'],
                    'descripcion'=>$row['irbpnr_descripcion'],
                    'tarifa_especifica'=>$row['irbpnr_tarifa_especifica'],
                    'tarifa_advaloren'=>$row['irbpnr_tarifa_advaloren'],
                    'estado'=>$row['irbpnr_estado'],
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