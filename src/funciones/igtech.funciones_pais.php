<?php
function listaPais(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Pais ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT pai_id,
                            pai_nombre,
                            pai_codigo_sri,
                            pai_estado 
                    FROM sis_pais
                    order by pai_id";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(	
                    'id'=>          $row['pai_id'],
                    'descripcion'=> $row['pai_nombre'],
                    'codigo'=>      $row['pai_codigo_sri'],
                    'estado'=>      $row['pai_estado'],
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

function seleccionarPais($i_pais){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' seleccionar Pais ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Pais: '.$i_pais);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT pai_id,
                            pai_nombre,
                            pai_codigo_sri,
                            pai_estado  
                     FROM sis_pais
                     where pai_id=".$i_pais."
                     order by pai_id";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(	
                    'id'        =>$row->pai_id,
                    'descripcion'    =>$row->pai_nombre,
                    'codigo'=>$row->pai_codigo_sri,
                    'estado'=>$row->pai_estado,
                );
               $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }else{
               $o_respuesta=array('error'=>'9996','mensaje'=>'No hay datos del país');
            }
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

function seleccionarPaisxNombre($i_pais){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Pais x Nombre ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Pais: '.$i_pais);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT pai_id,
                            pai_nombre,
                            pai_codigo_sri,
                            pai_estado 
                     FROM sis_pais
                     where pai_nombre='".$i_pais."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(	
                    'id'            =>$row->pai_id,
                    'descripcion'   =>$row->pai_nombre,
                    'codigo'        =>$row->pai_codigo_sri,
                    'estado'        =>$row->pai_estado
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta); 
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No hay datos del país');
            }
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

function registrarPais($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Pais ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                        FROM sis_pais
                        where pai_nombre    ='".$i_data['nombre']."'
                        or pai_codigo_sri   ='".$i_data['codigo_sri']."'";
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $insert_sql="INSERT INTO sis_pais(
                                    pai_id,
                                    pai_nombre,
                                    pai_codigo_sri,
                                    pai_estado) 
                                VALUES
                                    (
                                        (select max(pai_id)+1 from sis_pais),
                                        '".$i_data['descripcion']."',
                                        '".$i_data['codigo']."',
                                        '".$i_data['estado']."'
                                    )";    
                    
                    $Log->EscribirLog(' Consulta: '.$insert_sql);
                    if (!$result = pg_query($ws_conexion, $insert_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                        $close = pg_close($ws_conexion);
                    }else{
                        $close = pg_close($ws_conexion);	    
                        $w_respuesta=seleccionarPaisxNombre($i_data['nombre']);
                        $o_respuesta=array('error'=>'0','mensaje'=>'Pais creado exitosamente','datos' => $w_respuesta['datos']);        
                    }
                }else{
                    $o_respuesta=array('error'=>'9996','mensaje'=>'Ya existe el pais');
                }
            }
        }    
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }   
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarPais($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Pais ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=    "SELECT count(*) 
                        FROM sis_pais
                        WHERE pai_id=".$i_data['id'];
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==1){
                    $update_sql="UPDATE sis_pais 
                                SET	
                                    pai_nombre      ='".$i_data['descripcion']."',
                                    pai_codigo_sri  ='".$i_data['codigo']."'
                                    pai_estado      ='".$i_data['estado']."' 
                                WHERE   pai_id=".$i_data['id'];    
                    $Log->EscribirLog(' Consulta: '.$update_sql);
                    if (!$result = pg_query($ws_conexion, $update_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
                    }else{
                        $o_respuesta=array('error'=>'0','mensaje'=>'Pais actualizado exitosamente','datos'=>$i_data);        
                    }
                }else{
                    $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el pais');
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