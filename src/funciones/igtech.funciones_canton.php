<?php
function listaCantones(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
	    $Log->EscribirLog(' LISTA DE CANTONES');
	    $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            can_id,
                            can_provincia,
                            can_nombre,
                            can_codigo,
                            can_estado 
                    FROM v_sis_canton";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(	
                    'id'=>              $row['can_id'],
                    'id_provincia'=>    $row['can_provincia'],
                    'descripcion'=>     $row['can_nombre'],
                    'codigo'=>          $row['can_codigo'],
                    'estado'=>          $row['can_estado'],
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
    $Log->EscribirLog(' RESPUESTA: '.var_export($o_respuesta,true));   
    return $o_respuesta;
    
}

function listaCanton($i_provincia){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
	    $Log->EscribirLog(' LISTA DE CANTONES');
	    $Log->EscribirLog(' DATOS DE ENTRADA');
	    $Log->EscribirLog(' Provincia: '.$i_provincia);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            can_id,
                            can_provincia,
                            can_nombre,
                            can_codigo,
                            can_estado
                    FROM v_sis_canton
                    WHERE can_provincia=".$i_provincia.";";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(	
                    'id'=>              $row['can_id'],
                    'id_provincia'=>    $row['can_provincia'],
                    'descripcion'=>          $row['can_nombre'],
                    'codigo'=>          $row['can_codigo'],
                    'estado'=>          $row['can_estado'],
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
    $Log->EscribirLog(' RESPUESTA: '.var_export($o_respuesta,true));   
    return $o_respuesta;
    
}

function seleccionarCanton($i_canton){
    try{
        $ws_conexion=ws_coneccion_bdd();
        $Log=new IgtechLog ();
        $Log->Abrir();
	    $Log->EscribirLog(' SELECCIONAR CANTONES');
	    $Log->EscribirLog(' DATOS DE ENTRADA');
	    $Log->EscribirLog(' Id: '.$i_canton);
        $select_sql="SELECT 
                            can_id,
                            can_provincia,
                            can_nombre,
                            can_codigo,
                            can_estado 
                    FROM v_sis_canton
                    WHERE can_id=".$i_canton;
        $Log->EscribirLog(' Consulta: '.$select_sql);            
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(	
                    'id'=>              $row->can_id,
                    'id_provincia'=>    $row->can_provincia,
                    'descripcion'=>     $row->can_nombre,
                    'codigo'=>          $row->can_codigo,
                    'estado'=>          $row->can_estado,
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);   
            }else{
                $o_respuesta=array('error'=>'9997','mensaje'=>'No hay datos del cantón');
            }
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }    
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    } 
    $Log->EscribirLog(' RESPUESTA: '.var_export($o_respuesta,true));   
    return $o_respuesta;
}

function seleccionarCantonXNombre($i_canton){
    try{
        $ws_conexion=ws_coneccion_bdd();
        $Log=new IgtechLog ();
        $Log->Abrir();
	    $Log->EscribirLog(' SELECCIONAR CANTONES');
	    $Log->EscribirLog(' DATOS DE ENTRADA');
	    $Log->EscribirLog(' Canton: '.$i_canton);
	   
        $select_sql="SELECT 
                            can_id,
                            can_pais,
                            pai_nombre,
                            can_provincia,
                            pro_nombre,
                            can_nombre,
                            can_codigo,
                            can_estado 
                    FROM v_sis_canton
                    WHERE can_nombre='".strtoupper($i_canton)."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);            
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(	
                    'id'=>              $row->can_id,
                    'id_pais'=>         $row->can_pais,
                    'pais'=>            $row->pai_nombre,
                    'id_provincia'=>    $row->can_provincia,
                    'provincia'=>       $row->pro_nombre,
                    'descripcion'=>     $row->can_nombre,
                    'codigo'=>          $row->can_codigo,
                    'estado'=>          $row->can_estado,
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);   
            }else{
                $o_respuesta=array('error'=>'9997','mensaje'=>'No hay datos del cantón');
            }
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }    
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    } 
    $Log->EscribirLog(' RESPUESTA: '.var_export($o_respuesta,true));   
    return $o_respuesta;
}


?>