<?php

function listaPuntoEmisionEmpresa($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Punto de Emision Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT 
                                pem_empresa,
                                est_codigo,
                                pen_serie,
                                coalesce(pem_nombre,'') as pem_nombre,
                                pem_tipo
                        FROM del_punto_emision
                        inner join del_establecimiento on pem_establecimiento=est_id
                        where pem_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $pem_empresa	=	$row['pem_empresa'];
                $est_codigo		=	$row['est_codigo'];
                $pen_serie		=	$row['pen_serie'];
                $pem_nombre		=	$row['pem_nombre'];
                $pem_tipo       =   $row['pem_tipo'];
                $respuesta[] = array(	
                    'empresa'		=>	$pem_empresa,
                    'establecimiento'	=>	$est_codigo,
                    'punto_emision'		=>	$pen_serie,
                    'nombre'			=>	$pem_nombre,
                    'tipo'              =>  $pem_tipo
                );
            }
            $o_respuesta=array(	'error'=>'0','mensaje'=>'ok','datos'=>$respuesta);   
        }else{
            $o_respuesta=array( 'error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }    
        //desconectamos la base de datos
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array( 'error'=>'9999','mensaje'=>	$e->getMessage());
    }   
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
			            
}

function listaPuntoEmisionEmpresaEstablecimiento($i_empresa,$i_establecimiento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Puntos de Emision por Establecimiento ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Establecimiento: '.$i_establecimiento);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT 
                                pem_empresa,
                                est_codigo,
                                pen_serie,
                                coalesce(pem_nombre,'') as pem_nombre,
                                pem_tipo
                        FROM del_punto_emision
                        inner join del_establecimiento on pem_establecimiento=est_id
                        where pem_empresa='".$i_empresa."'
                        and est_codigo='".$i_establecimiento."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $pem_empresa	=	$row['pem_empresa'];
                $est_codigo		=	$row['est_codigo'];
                $pen_serie		=	$row['pen_serie'];
                $pem_nombre		=	$row['pem_nombre'];
                $pem_tipo       =   $row['pem_tipo'];
                $respuesta[] = array(	
                    'empresa'		=>	$pem_empresa,
                    'establecimiento'	=>	$est_codigo,
                    'punto_emision'		=>	$pen_serie,
                    'nombre'			=>	$pem_nombre,
                    'tipo'              =>  $pem_tipo
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$respuesta);   
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

function seleccionarPuntoEmisionEmpresa($i_empresa,$i_establecimiento,$i_punto_emision){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Selecionar Punto Emision ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Establecimiento: '.$i_establecimiento);
        $Log->EscribirLog(' Punto de Emision: '.$i_punto_emision);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT 
                                pem_empresa,
                                est_codigo,
                                pen_serie,
                                coalesce(pem_nombre,'') as pem_nombre
                        FROM del_punto_emision
                        inner join del_establecimiento on pem_establecimiento=est_id
                        where pem_empresa='".$i_empresa."'
                        and est_codigo='".$i_establecimiento."'
                        and pen_serie='".$i_punto_emision."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $pem_empresa	=	$row['pem_empresa'];
                $est_codigo		=	$row['est_codigo'];
                $pen_serie		=	$row['pen_serie'];
                $pem_nombre		=	$row['pem_nombre'];
                $pem_tipo       =   $row['pem_tipo'];
                $respuesta = array(	
                    'empresa'		=>	$pem_empresa,
                    'establecimiento'	=>	$est_codigo,
                    'punto_emision'		=>	$pen_serie,
                    'nombre'			=>	$pem_nombre,
                    'tipo'              =>  $pem_tipo
                );
            }
            $o_respuesta=array('error'	=>'0','mensaje'=>'ok','datos'=>$respuesta);   
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

function registrarPuntoEmision($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Punto Emision ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_punto_emision(
                            pem_id,
                            pem_empresa,
                            pem_establecimiento,
                            pen_serie,
                            pem_nombre,
                            pem_tipo,
                            pem_usuario,
                            pem_estado
                            ) 
        VALUES(
                (SELECT max(pem_id)+1 FROM del_punto_emision ),
                '".$i_data['empresa']."',
                (SELECT est_id FROM del_establecimiento WHERE est_empresa='".$i_data['empresa']."' and est_codigo='".$i_data['establecimiento']."'),
                '".$i_data['punto_emision']."',
                '".$i_data['nombre']."',
                '".$i_data['tipo']."',
                '".$i_data['usuario']."',
                '".$i_data['estado']."'
        )";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);          
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta= array( 'error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Punto de Emision Registrado Exitosamente','datos'=>$i_data);
        }
        $close = pg_close($ws_conexion);    
    }catch (Throwable $e) {
        $o_respuesta=  array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarPuntoEmision($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Punto Emision ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE del_punto_emision 
                        SET	
                            pem_nombre='".$i_data['nombre']."',
                            pem_tipo='".$i_data['tipo']."',
                            pem_usuario='".$i_data['usuario']."',
                            pem_estado='".$i_data['estado']."'
                        WHERE
                            pem_empresa='".$i_data['empresa']."'
                        AND pen_serie='".$i_data['punto_emision']."'
                        AND pem_establecimiento=(SELECT est_id FROM del_establecimiento 
                                                    WHERE est_codigo='".$i_data['establecimiento']."' 
                                                    and est_empresa='".$i_data['empresa']."')";
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Punto Emision actualizado exitosamente','datos'=>$i_data);
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdatePuntoEmision($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Punto de Emision ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data, true));
        $w_validacion=validarDatosPuntoEmision($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*)
                    FROM del_punto_emision
                    INNER JOIN del_establecimiento ON pem_establecimiento=est_id
                    WHERE  pem_empresa  ='".$i_data['empresa']."'
                    AND est_codigo      ='".$i_data['establecimiento']."'
                    AND pen_serie       ='".$i_data['punto_emision']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarPuntoEmision($i_data);
                }else{
                    $o_respuesta=actualizarPuntoEmision($i_data);
                }
            }
            if($o_respuesta['error']=='0' and $i_data['tipo']=='EW001' and ($i_data['tipo_documento']=='01' or $i_data['tipo_documento']=='99' or $i_data['tipo_documento']=='06' ) ){
                $t_respuesta=insertUpdateLibretin($i_data);
                $o_respuesta['error']=$t_respuesta['error'];
                $o_respuesta['mensaje'].=" - ".$t_respuesta['mensaje'];
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

function validarDatosPuntoEmision($i_data){
    //datos del punto de emision
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['establecimiento'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo establecimiento');
        return $o_respuesta;
    }
    if (!isset($i_data['punto_emision'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo punto_emision');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre');
        return $o_respuesta;
    }
    if (!isset($i_data['tipo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo');
        return $o_respuesta;
    }
    if (!isset($i_data['estado'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado');
        return $o_respuesta;
    }
    //datos del libretin
    if (!isset($i_data['tipo_documento'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo_documento');
        return $o_respuesta;
    }
    if (!isset($i_data['tipo_emision'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo_emision');
        return $o_respuesta;
    }
    if($i_data['tipo_emision']=='F'){
        if (!isset($i_data['secuencial_desde'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo secuencial_desde');
            return $o_respuesta;
        }
        if (!isset($i_data['secuencial_hasta'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo secuencial_hasta');
            return $o_respuesta;
        }
        if (!isset($i_data['secuencial_inicial'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo secuencial_inicial');
            return $o_respuesta;
        }
        if (!isset($i_data['autorizacion'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo autorizacion');
            return $o_respuesta;
        }
        if (!isset($i_data['fecha_caducidad'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo fecha_caducidad');
            return $o_respuesta;
        }
    }else{
        if (!isset($i_data['secuencial_inicial'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo secuencial_inicial');
            return $o_respuesta;
        }
    }
    if (!isset($i_data['usuario'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo usuario');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function insertUpdateLibretin($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Inser Update Libretin ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) FROM del_libretin
                    join del_punto_emision on lib_punto_emision=pem_id
                    join del_establecimiento on pem_establecimiento=est_id
                    Where pem_empresa           ='".$i_data['empresa']."'
                    and est_codigo              ='".$i_data['establecimiento']."'
                    and pen_serie              ='".$i_data['punto_emision']."'
                    and pem_tipo                ='".$i_data['tipo']."'
                    and lib_tipo_comprobante    ='".$i_data['tipo_documento']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarLibretin($i_data);
                }else{
                    //$o_respuesta=actualizarLibretin($i_data);
                    $o_respuesta=array('error'=>'0','mensaje'=>'Ya existe el libretin','datos'=>$i_data);
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

function registrarLibretin_old($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Libretin ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        /*
        $select_sql="SELECT mn_documentos_gratis 
                    FROM del_empresa
                    inner join del_modelo_negocio ON emp_tipo_negocio=mn_id
                    where emp_ruc='".$i_data['empresa']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){             
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 );  
                    $w_doc_gratis=$row->mn_documentos_gratis;
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No existe la empresa o no tiene asignado un modelo de negocio');
            }
        }else{
            return array ('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }
        */
        $w_doc_gratis=999999999;
        $select_sql="SELECT pem_id 
                     FROM del_punto_emision
                     join del_establecimiento on pem_establecimiento=est_id 
                     Where pem_empresa           ='".$i_data['empresa']."'
                     and est_codigo              ='".$i_data['establecimiento']."'
                     and pen_serie              ='".$i_data['punto_emision']."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 );
                $w_id_punto_emision=$row->pem_id;
            }
        }else{
            return array( 'error'=>'9997','mensaje'=>'No existe el punto de emision'); 
        }
        if($i_data['tipo_documento']=='99'){
            $w_tipo_libretin='F';
        }else{
            $w_tipo_libretin=$i_data['tipo_emision'];
        } 
        if($w_tipo_libretin=='E'){
            if($w_doc_gratis<>0){
                if($w_doc_gratis==999999999){
                    $w_hasta=$w_doc_gratis;
                    $w_doc_gratis=999999999-$i_data['secuencial_inicial']+1;
                }else{
                    $w_hasta=$i_data['secuencial_inicial']+$w_doc_gratis-1;
                }
            }  
        }else{
            $w_hasta=$i_data['secuencial_hasta'];
        }   
        $insert_sql="INSERT INTO del_libretin(
            lib_punto_emision,
            lib_tipo_comprobante,
            lib_secuencial,
            lib_disponibles,
            lib_estado,
            lib_tipo_libretin,
            lib_utilizados,
            lib_desde,
            lib_hasta,
            lib_autorizacion,
            lib_fecha_caducidad) 
        VALUES(
                ".$w_id_punto_emision.",
                '".$i_data['tipo_documento']."',
                ". $i_data['secuencial_inicial'].",
                ".$w_doc_gratis.",
                'A',
                '".$w_tipo_libretin."',
                0,
                ".$i_data['secuencial_desde'].",
                ".$w_hasta.",
                '".$i_data['autorizacion']."',
                getdate()+'1 year' :: INTERVAL);";
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Libretin creado exitosamente','datos'=>$i_data);
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarLibretin($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' REGISTRAR Libretin2 ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' DATOS :'.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        
        $select_sql="SELECT pem_id 
                     FROM del_punto_emision
                     join del_establecimiento on pem_establecimiento=est_id 
                     Where pem_empresa           ='".$i_data['empresa']."'
                     and est_codigo              ='".$i_data['establecimiento']."'
                     and pen_serie              ='".$i_data['punto_emision']."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 );
                $w_id_punto_emision=$row->pem_id;
            }
        }else{
            return array( 'error'=>'9997','mensaje'=>'No existe el punto de emision'); 
        }
        if($i_data['tipo_emision']=='F'){
            $w_doc_disponibles=$i_data['secuencial_hasta']-$i_data['secuencial_inicial']+1;
            $w_tipo_libretin='F';
            $insert_sql="INSERT INTO del_libretin(
                lib_punto_emision,
                lib_tipo_comprobante,
                lib_secuencial,
                lib_disponibles,
                lib_estado,
                lib_tipo_libretin,
                lib_utilizados,
                lib_desde,
                lib_hasta,
                lib_autorizacion,
                lib_fecha_caducidad) 
            VALUES(
                    ".$w_id_punto_emision.",
                    '".$i_data['tipo_documento']."',
                    ". $i_data['secuencial_inicial'].",
                    ".$w_doc_disponibles.",
                    'A',
                    '".$w_tipo_libretin."',
                    0,
                    ".$i_data['secuencial_desde'].",
                    ".$i_data['secuencial_hasta'].",
                    '".$i_data['autorizacion']."',
                    '".$i_data['fecha_caducidad']."');";
        }else{
            $w_doc_disponibles=999999999-$i_data['secuencial_inicial']+1;
            $w_tipo_libretin='E';
            $insert_sql="INSERT INTO del_libretin(
                lib_punto_emision,
                lib_tipo_comprobante,
                lib_secuencial,
                lib_disponibles,
                lib_estado,
                lib_tipo_libretin,
                lib_utilizados,
                lib_desde,
                lib_hasta,
                lib_autorizacion,
                lib_fecha_caducidad) 
            VALUES(
                    ".$w_id_punto_emision.",
                    '".$i_data['tipo_documento']."',
                    ". $i_data['secuencial_inicial'].",
                    ".$w_doc_disponibles.",
                    'A',
                    '".$w_tipo_libretin."',
                    0,
                    1,
                    999999999,
                    '',
                    getdate()+'1 year' :: INTERVAL);";   
        }
        
        
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Libretin2 creado exitosamente','datos'=>$i_data);
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}


?>