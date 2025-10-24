<?php

function listaEstadosPedidos(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Estados Pedidos ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT esp_id,
                            esp_nombre,
                            esp_descripcion
                     FROM del_estado_pedido";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(	
                    'codigo'=>      $row['esp_id'],
                    'nombre'=>      $row['esp_nombre'],
                    'descripcion'=> $row['esp_descripcion'],
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

function seleccionarEstadoPedido($i_idEstado){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Estado Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' IdEstado: '.$i_idEstado);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT esp_id,
                            esp_nombre,
                            esp_descripcion
                    FROM del_estado_pedido
                    WHERE esp_id='".$i_idEstado."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);             
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta=array();
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(	
                    'codigo'=>      $row->esp_id,
                    'nombre'=>      $row->esp_nombre,
                    'descripcion'=> $row->esp_descripcion,
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No hay datos del estado');
            }    
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }    
        $close = pg_close($ws_conexion) ;
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarEstadoPedido($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Estado Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_codigo=$i_data['codigo'];
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                    FROM del_estado_pedido
                    where esp_id='".$w_codigo."'";
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count>0){
                    $w_validar=false;
                    $o_respuesta=array('error'=>'9996','mensaje'=>'Ya existe el Estado');
                } else{
                    $insert_sql="INSERT INTO del_estado_pedido(
                        esp_id,
                        esp_descripcion,
                        esp_nombre) 
                    VALUES
                        (
                            '".$i_data['codigo']."',
                            '".$i_data['descripcion']."',
                            '".$i_data['nombre']."'
                        );";
                    $Log->EscribirLog(' Consulta: '.$insert_sql);
                    if(!$result = pg_query($ws_conexion, $insert_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                    }else{
                        $o_respuesta=array('error'=>'0','mensaje'=>'Estado creada exitosamente','datos'=>$i_data); 
                    }
                }   
            }
        } else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }   
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    } 
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));   
    return $o_respuesta;
}

function actualizarEstadoPedido($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Estado Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                    FROM del_estado_pedido
                    where esp_id='".$i_data['codigo']."';";
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==1){
                    $update_sql="UPDATE del_estado_pedido 
                                SET	 
                                    esp_descripcion ='".$i_data['descripcion']."',
                                    esp_nombre      ='".$i_data['nombre']."' 
                                WHERE esp_id='".$i_data['codigo']."';";
                    $Log->EscribirLog(' Consulta: '.$update_sql);
                    if (!$result = pg_query($ws_conexion, $update_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                    }else{
                        $o_respuesta=array('error'=>'0','mensaje'=>'Estado actualizada exitosamente','datos'=>$i_data);     
                    }
                }else{
                    $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el estado');
                }
            }
        }    
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));    
    return $o_respuesta;
}

?>
