<?php
include_once('src/funciones/igtech.validar_cedula.php');

function buscar_ciudadano($i_ciudadano){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' SELECCIONAR Ciudadano ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        ciu_identificacion,
                        ciu_nombre,
                        ciu_nombre_comercial,
                        ciu_obligado_contabilidad,
                        ciu_tipo_contribuyente,
                        ciu_provincia,
                        ciu_canton,
                        ciu_direccion,
                        ciu_correo,
                        ciu_telefono
                    FROM ciu_ciudadano
                    WHERE ciu_identificacion='".$i_ciudadano."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'identificacion'=>          $row->ciu_identificacion,
                    'nombre'=>                  $row->ciu_nombre,
                    'nombre_comercial'=>        $row->ciu_nombre_comercial,
                    'obligado_contabilidad'=>   $row->ciu_obligado_contabilidad,
                    'tipo_contribuyente'=>      $row->ciu_tipo_contribuyente,
                    'provincia'=>               $row->ciu_provincia,
                    'canton'=>                  $row->ciu_canton,
                    'direccion'=>               $row->ciu_direccion,
                    'correo'=>                  $row->ciu_correo,
                    'telefono'=>                $row->ciu_telefono
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

function registrarCiudadano($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' REGISTRAR Ciudadano ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' DATOS :'.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO ciu_ciudadano(
            ciu_identificacion,
            ciu_nombre,
            ciu_nombre_comercial,
            ciu_obligado_contabilidad,
            ciu_tipo_contribuyente,
            ciu_provincia,
            ciu_canton,
            ciu_direccion,
            ciu_correo,
            ciu_telefono) 
        VALUES
            (
                '".$i_data['identificacion']."',
                '".$i_data['nombre']."',
                '".$i_data['nombre_comercial']."',
                '".$i_data['obligado_contabilidad']."',
                '".$i_data['tipo_contribuyente']."',
                '".$i_data['provincia']."',
                '".$i_data['canton']."',
                '".$i_data['direccion']."',
                '".$i_data['correo']."',
                '".$i_data['telefono']."'
            );
        ";
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Ciudadano creado exitosamente','datos'=>$i_data);
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarCiudadano($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' ACTUALIZAR Ciudadano ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' DATOS :'.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE ciu_ciudadano 
        SET	 
            ciu_nombre                  ='".$i_data['nombre']."',
            ciu_nombre_comercial        ='".$i_data['nombre_comercial']."',
            ciu_obligado_contabilidad   ='".$i_data['obligado_contabilidad']."',
            ciu_tipo_contribuyente      ='".$i_data['tipo_contribuyente']."',
            ciu_provincia               ='".$i_data['provincia']."',
            ciu_canton                  ='".$i_data['canton']."',
            ciu_direccion               ='".$i_data['direccion']."',
            ciu_correo                  ='".$i_data['correo']."',
            ciu_telefono                ='".$i_data['telefono']."'
        WHERE ciu_identificacion        ='".$i_data['identificacion']."';
        ";
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Ciudadano actualizado exitosamente','datos'=>$i_data);
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateCiudadano($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));

        $w_validacion=validarDatosCiudadano($i_data);

        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        if (strlen($i_data['identificacion'])==13){
            $w_validacion_cedula=validar_CedulaRuc($i_data['identificacion'],'RUC');

        }elseif(strlen($i_data['identificacion'])==10){
            $w_validacion_cedula=validar_CedulaRuc($i_data['identificacion'],'CEDULA');
        }
        if($w_validacion_cedula==0){
            $o_respuesta=array('error'=>'9996','mensaje'=>'Cedula o Ruc incorrecto');
        }else{
            $ws_conexion=ws_coneccion_bdd();
            $select_sql="SELECT COUNT(*) 
                            FROM ciu_ciudadano 
                            WHERE ciu_identificacion='".$i_data['identificacion']."'";
            $Log->EscribirLog(' Consulta: '.$select_sql);
            if ($result = pg_query($ws_conexion, $select_sql)){
                if( pg_num_rows($result) > 0 ){
                    $row = pg_fetch_object( $result, 0 ); 
                    //$close=pg_close($ws_conexion);
                    if($row->count==0){
                        $o_respuesta= registrarCiudadano($i_data);
                    }else{
                        $o_respuesta=actualizarCiudadano($i_data);  
                        //$o_respuesta=array('error'=>'9999','mensaje'=>'Ya existe'); 
                    }
                }    
            } 
        }
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;   
}

function validarDatosCiudadano($i_data){
    if (!isset($i_data['identificacion']) or $i_data['identificacion']=='' ){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo identificacion');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre']) or $i_data['nombre']==''){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre');
        return $o_respuesta;
    }
    
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
} 


?>