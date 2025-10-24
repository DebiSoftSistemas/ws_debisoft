<?php
function listaParroquias($i_pais,$i_provincia,$i_canton){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Parroquias ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Pais: '.$i_pais);
        $Log->EscribirLog(' Provincia: '.$i_provincia);
        $Log->EscribirLog(' Canton: '.$i_canton);

    $ws_conexion=ws_coneccion_bdd();
    $select_sql="SELECT 
                        pai_id,
                        pai_nombre,
                        pro_id,
                        pro_nombre,
                        can_id,
                        can_nombre,
                        parr_id,
                        parr_nombre,
                        parr_codigo
                    FROM v_sis_parroquias
                    WHERE pai_id=".$i_pais."
                    AND pro_id=".$i_provincia."
                    AND can_id=".$i_canton.";";
    $Log->EscribirLog(' Consulta: '.$select_sql);
    if($result = pg_query($ws_conexion, $select_sql)){
        $w_respuesta = array(); //creamos un array
        while($row = pg_fetch_array($result)) { 
            $w_respuesta[] = array(
                'id'=>$row['parr_id'],
                'id_pais'=>$row['pai_id'],
                'pais'=>$row['pai_nombre'],
                'id_provincia'=>$row['pro_id'],
                'provincia'=>$row['pro_nombre'],
                'id_canton'=>$row['can_id'],
                'conton'=>$row['can_nombre'],
                'parroquia'=>$row['parr_nombre'],
                'codigo_sri'=>$row['parr_codigo']
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

function seleccionarParroquia($i_pais,$i_provincia,$i_canton,$i_parroquia){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Parroquia ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Pais: '.$i_pais);
        $Log->EscribirLog(' Provincia: '.$i_provincia);
        $Log->EscribirLog(' Canton: '.$i_canton);
        $Log->EscribirLog(' Parroquia: '.$i_parroquia);

        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        pai_id,
                        pai_nombre,
                        pro_id,
                        pro_nombre,
                        can_id,
                        can_nombre,
                        parr_id,
                        parr_nombre,
                        parr_codigo
                    FROM v_sis_parroquias
                    WHERE pai_id=".$i_pais."
                    AND pro_id=".$i_provincia."
                    AND can_id=".$i_canton."
                    AND parr_id=".$i_parroquia.";";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'id'=>$row->parr_id,
                    'id_pais'=>$row->pai_id,
                    'pais'=>$row->pai_nombre,
                    'id_provincia'=>$row->pro_id,
                    'provincia'=>$row->pro_nombre,
                    'id_canton'=>$row->can_id,
                    'canton'=>$row->can_nombre,
                    'parroquia'=>$row->parr_nombre,
                    'codigo_sri'=>$row->parr_codigo 
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
