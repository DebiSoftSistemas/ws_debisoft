<?php
function listaServicios(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Servicios ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT sd_codigo,
                            sd_nombre,
                            sd_descripcion,
                            sd_estado 
                     FROM del_servicios_delivery";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(	
                    'codigo'=>		$row['sd_codigo'],
                    'nombre'=>		$row['sd_nombre'],
                    'descripcion'=>	$row['sd_descripcion'],
                    'estado'=>		$row['sd_estado']
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

function seleccionarservicio($i_codigo){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Servicio ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Codigo: '.$i_codigo);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT sd_codigo,
                            sd_nombre,
                            sd_descripcion,
                            sd_estado 
                    FROM del_servicios_delivery
                    WHERE sd_codigo='".$i_codigo."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 );
                $w_respuesta = array(	
                    'codigo'=>		$row['sd_codigo'],
                    'nombre'=>		$row['sd_nombre'],
                    'descripcion'=>	$row['sd_descripcion'],
                    'estado'=>		$row['sd_estado']
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);    
            }
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

function registrarServicio($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Servicio ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_servicios_delivery(
                                sd_codigo,
                                sd_nombre,
                                sd_descripcion,
                                sd_estado) 
                            VALUES(
                                    '".$i_data['codigo']."',
                                    '".$i_data['nombre']."',
                                    '".$i_data['descripcion']."',
                                    '".$i_data['estado']."'
                                );";
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Servicio creado exitosamente','datos'=>$i_data);
        }
                
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarServicio($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Servicio ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE del_servicios_delivery 
                        SET	
                            sd_nombre       ='".$i_data['nombre']."',
                            sd_descripcion  ='".$i_data['descripcion']."',
                            sd_estado       ='".$i_data['estado']."' 
                        WHERE sd_codigo     ='".$i_data['codigo']."'";
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Servicio actualizado exitosamente','datos'=>$i_data);
        }
                
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function inserUpdateServicio($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Servicio ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosServicio($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                        FROM del_servicios_delivery 
                        where sd_codigo='".$i_data['codigo']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);                
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarServicio($i_data);    
                }else{
                    $o_respuesta=actualizarServicio($i_data);    
                }
            }    
        } 
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;  
}

function validarDatosServicio($i_data){
    if (!isset($i_data['codigo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre');
        return $o_respuesta;
    }
    if (!isset($i_data['descripcion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo descripcion');
        return $o_respuesta;
    }
    if (!isset($i_data['estado'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

?>