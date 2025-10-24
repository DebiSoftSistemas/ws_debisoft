<?php
function listaCategorias($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Categorias ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        cat_id,
                        cat_descripcion 
                    FROM del_categoria_producto
                    WHERE cat_empresa='".$i_empresa."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[]=array(
                    'id'        =>  $row['cat_id'],
                    'categoria' =>  $row['cat_descripcion'],
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

function seleccionarCategoria($i_empresa,$i_categoria){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Categoria ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Categoria: '.$i_categoria);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        cat_id,
                        cat_descripcion 
                    FROM del_categoria_producto 
                    WHERE cat_id='".$i_categoria."'
                    AND cat_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'id'=>$row->cat_id,
                    'categoria'=>$row->cat_descripcion
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No existe esta categoria');
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

function registrarCategoria($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Categoria ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_categoria_producto(
                                    cat_id,
                                    cat_descripcion) 
                                VALUES
                                    (
                                        '".$i_data['id']."',
                                        '".$i_data['categoria']."'
                                    );";    
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Categoria creada exitosamente','datos' => $i_data);        
        }
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarCategoria($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Categoria ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
    $ws_conexion=ws_coneccion_bdd();
    $update_sql="UPDATE del_categoria_producto 
                    SET	 
                        cat_descripcion='".$i_data['categoria']."' 
                    WHERE  cat_id='".$i_data['id']."';";
    $Log->EscribirLog(' Consulta: '.$update_sql);
    if (!$result = pg_query($ws_conexion, $update_sql)){
        $o_respuesta=array('error'=>'9997', 'mensaje'=>pg_last_error($ws_conexion)); 
    }else{
        $o_respuesta=array('error'=>'0','mensaje'=>'Categoria actualizada exitosamente','datos' => $i_data);        
    }
           
    $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateCategoria($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Inser Update Categoria ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosCategoria($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                    FROM del_categoria_producto 
                    WHERE cat_id='".$i_data['id']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarCategoria($i_data);    
                }else{
                    $o_respuesta=actualizarCategoria($i_data);    
                }
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

function validarDatosCategoria($i_data){
    if (!isset($i_data['id'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo id');
        return $o_respuesta;
    }
    if (!isset($i_data['categoria'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo categoria');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}
?>