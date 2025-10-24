<?php

function listarUsuarios(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Usuarios ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT usu_empresa,
                            usu_usuario,
                            usu_nombre,
                            usu_email,
                            usu_cedula,
                            usu_telefono,
                            usu_estado
                    FROM del_usuario";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'   =>$row['usu_empresa'],
                    'usuario'   =>$row['usu_usuario'],
                    'nombre'    =>$row['usu_nombre'],
                    'email'     =>$row['usu_email'],
                    'cedula'    =>$row['usu_cedula'],
                    'telefono'  =>$row['usu_telefono'],
                    'estado'    =>$row['usu_estado'],
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

function listarusuariosEmpresa($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Usuarios Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT usu_empresa,
                            usu_usuario,
                            usu_nombre,
                            usu_email,
                            usu_cambio_contrasenia,
                            usu_fecha_crea,
                            usu_fecha_estado,
                            usu_estado,
                            usu_cedula,
                            usu_telefono,
                            usu_placa,
                            usu_tipo_documento 
                    FROM del_usuario
                    WHERE usu_empresa='".$i_empresa."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
               $w_respuesta[] = array(
                    'empresa'   =>$row['usu_empresa'],
                    'usuario'   =>$row['usu_usuario'],
                    'nombre'    =>$row['usu_nombre'],
                    'email'     =>$row['usu_email'],
                    'cedula'    =>$row['usu_cedula'],
                    'telefono'  =>$row['usu_telefono'],
                    'estado'    =>$row['usu_estado'],
                );	
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);    
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)
            );
        }    
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    } 
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function seleccionarUsuario($i_empresa,$i_usuario){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Usuario ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Usuario: '.$i_usuario);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT usu_empresa,
                            usu_usuario,
                            usu_nombre,
                            usu_email,
                            usu_cambio_contrasenia,
                            usu_fecha_crea,
                            usu_fecha_estado,
                            usu_estado,
                            usu_rol,
                            usu_cedula,
                            usu_telefono,
                            usu_placa,
                            usu_tipo_documento 
                    FROM del_usuario
                    WHERE usu_empresa='".$i_empresa."'
                    AND usu_usuario='".$i_usuario."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta= array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(
                    'empresa'   =>$row->usu_empresa,
                    'usuario'   =>$row->usu_usuario,
                    'nombre'    =>$row->usu_nombre,
                    'email'     =>$row->usu_email,
                    'cedula'    =>$row->usu_cedula,
                    'telefono'  =>$row->usu_telefono,
                    'estado'    =>$row->usu_estado,
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

function registrarUsuario($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Usuario ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $w_validacion=validarDatosUsuario($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        if (isset($i_data['placa'])){
            $w_placa=$i_data['placa'];
            $w_tipo_doc_emite='FACTURA';    
        }else{
            $w_placa='';
            $w_tipo_doc_emite='';
        }

        $insert_sql="INSERT INTO del_usuario(
                        usu_empresa,
                        usu_usuario,
                        usu_contrasenia,
                        usu_nombre,
                        usu_email,
                        usu_cambio_contrasenia,
                        usu_fecha_crea,
                        usu_fecha_estado,
                        usu_estado,
                        usu_cedula,
                        usu_telefono,
                        usu_placa,
                        usu_tipo_documento,
                        usu_super_administrador) 
                    VALUES(
                        '".$i_data['empresa']."',
                        '".$i_data['usuario']."',
                        md5('".$i_data['contrasenia']."'),
                        '".$i_data['nombre']."',
                        '".$i_data['email']."',
                        'S',
                        getdate(),
                        getdate(),
                        'V',
                        '".$i_data['cedula']."',
                        '".$i_data['telefono']."',
                        '".$w_placa."',
                        '".$w_tipo_doc_emite."',
                        '".$i_data['superadministrador']."'
                    )";    
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array( 'error'=>'0','mensaje'=>'Usuario creada exitosamente','datos' => $i_data);        
        }
        $close = pg_close($ws_conexion); 
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarUsuario($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Usuario ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $w_validacion=validarDatosUsuario($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        if (isset($i_data['placa'])){
            $w_placa=$i_data['placa'];
            $w_tipo_doc_emite='FACTURA';    
        }else{
            $w_placa='';
            $w_tipo_doc_emite='';
        }
        $update_sql="UPDATE del_usuario 
                     SET	usu_contrasenia     =md5('".$i_data['contrasenia']."'),
                            usu_nombre          ='".$i_data['nombre']."',
                            usu_email           ='".$i_data['email']."',
                            usu_fecha_estado    =getdate(),
                            usu_estado          ='".$i_data['estado']."',
                            usu_cedula          ='".$i_data['cedula']."',
                            usu_telefono        ='".$i_data['telefono']."',
                            usu_placa           ='".$w_placa."',
                            usu_tipo_documento  ='".$w_tipo_doc_emite."'
                     WHERE usu_empresa='".$i_data['empresa']."'
                     AND   usu_usuario='".$i_data['usuario']."';"; 
        //var_dump($update_sql);                
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Usuario actualizada exitosamente','datos' => $i_data);        
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }  
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateUsuario($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Usuario ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        if (!isset($i_data['usuario'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo usuario');
            return $o_respuesta;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT COUNT(*) 
                        FROM del_usuario 
                        WHERE usu_usuario='".$i_data['usuario']."'";
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                //$close=pg_close($ws_conexion);
                if($row->count==0){
                    $o_respuesta=registrarUsuario($i_data);
                }else{
                    $o_respuesta=actualizarUsuario($i_data);
                }
            }
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));    
    return $o_respuesta;   
}

function validarDatosUsuario($i_data){
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['usuario'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo usuario');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre');
        return $o_respuesta;
    }
    if (!isset($i_data['contrasenia'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo contrasenia');
        return $o_respuesta;
    }
    if (!isset($i_data['email'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo email');
        return $o_respuesta;
    }
    if (!isset($i_data['cedula'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo cedula');
        return $o_respuesta;
    }
    if (!isset($i_data['telefono'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo telefono');
        return $o_respuesta;
    }
    if (!isset($i_data['estado'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado');
        return $o_respuesta;
    }
    if (!isset($i_data['superadministrador'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo superadministrador');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

?>