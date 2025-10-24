<?php
function listaEstablecimientos($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Establecimientos ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT
                            est_id,
                            est_empresa,
                            est_logo,
                            est_nombre,
                            est_codigo,
                            can_nombre,
                            est_direccion,
                            est_telefono,
                            est_estado 
                        FROM	v_ws_establecimientos
                        WHERE 	est_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) {
                $w_respuesta[] = array(
                    'id'                    =>  $row['est_id'],	
                    'empresa'	    		=>	$row['est_empresa'],
                    'logo'                  =>  $row['est_logo'],
                    'nombre'                =>	$row['est_nombre'],
                    'codigo'			    =>	$row['est_codigo'],
                    'canton'				=>	$row['can_nombre'],
                    'direccion'				=>	$row['est_direccion'],
                    'telefono'  			=>	$row['est_telefono'],
                    'estado'				=>	$row['est_estado']
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);    
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }    
        //desconectamos la base de datos
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function seleccionarEstablecimiento($i_empresa,$i_codigo){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Establecimiento ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Codigo Establecimiento: '.$i_codigo);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT
                            est_id,
                            est_empresa,
                            est_logo,
                            est_nombre,
                            est_codigo,
                            can_nombre,
                            est_direccion,
                            est_telefono,
                            est_estado 
                        FROM	v_ws_establecimientos
                        WHERE 	est_empresa='".$i_empresa."'
                        AND		est_codigo='".$i_codigo."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(	
                    'id'                    =>  $row->est_id,
                    'empresa'			    =>	$row->est_empresa,
                    'logo'			        =>	$row->est_logo,
                    'nombre'                =>	$row->est_nombre,
                    'codigo '			    =>	$row->est_codigo,
                    'canton'				=>	$row->can_nombre,
                    'direccion'				=>	$row->est_direccion,
                    'telefono'				=>	$row->est_telefono,
                    'estado'				=>	$row->est_estado
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);    
            }else{
               $o_respuesta=array('error'=>'9996','mensaje'=>'No hay datos del establecimiento');
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

function registrarEstablecimiento($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Establecimiento ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosEstablecimiento($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion ;   
        }
        $w_crear_direc=crearDirectorios($i_data['empresa']);
        $w_ruta_logo=$w_crear_direc['datos']['ruta_logo'];
        $w_ruta_logo_interno=$w_crear_direc['datos']['ruta_logo_interno'];
        if ($i_data['ruta_logo']<>""){
            $w_nombre_logo=copiarArchivos($i_data['ruta_logo'],$w_ruta_logo,$w_ruta_logo_interno);    
        }else{
            $w_nombre_logo='';
        }
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_establecimiento(
                            est_id,
                            est_empresa,
                            est_nombre,
                            est_codigo,
                            est_pais,
                            est_provincia,
                            est_canton,
                            est_direccion,
                            est_logo,
                            est_telefono,
                            est_estado) 
                        VALUES
                            (
                                (select max(est_id)+1 from del_establecimiento),
                                '".$i_data['empresa']."',
                                '".$i_data['nombre']."',
                                '".$i_data['codigo']."',
                                    (select pai_id from sis_pais where pai_nombre='".strtoupper($i_data['pais'])."'),
                                    (select pro_id from sis_provincia where pro_nombre='".strtoupper($i_data['provincia'])."'),
                                    (select can_id from sis_canton where can_nombre='".strtoupper($i_data['canton'])."'),
                                '".$i_data['direccion']."',
                                '".$w_nombre_logo."',
                                '".$i_data['telefono']."',
                                '".$i_data['estado']."'
                            )";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Establecimiento creada exitosamente','datos'=>$i_data);                    
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarEstablecimiento($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Establecimiento ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosEstablecimiento($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion ;   
        }
        $w_crear_direc=crearDirectorios($i_data['empresa']);
        $w_ruta_logo=$w_crear_direc['datos']['ruta_logo'];
        $w_ruta_logo_interno=$w_crear_direc['datos']['ruta_logo_interno'];
        if ($i_data['ruta_logo']<>""){
            $w_nombre_logo=copiarArchivos($i_data['ruta_logo'],$w_ruta_logo,$w_ruta_logo_interno);    
        }else{
            $w_nombre_logo='';
        }
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE del_establecimiento 
                    SET	
                        est_nombre='".$i_data['nombre_establecimiento']."',
                        est_pais=(SELECT pai_id FROM sis_pais WHERE pai_nombre='".strtoupper($i_data['pais'])."'),
                        est_provincia=(SELECT pro_id FROM sis_provincia WHERE pro_nombre='".strtoupper($i_data['provincia'])."'),
                        est_canton=(SELECT can_id FROM sis_canton WHERE can_nombre='".strtoupper($i_data['canton'])."'),
                        est_direccion='".$i_data['direccion']."',
                        est_logo='".$w_nombre_logo."',
                        est_telefono='".$i_data['telefono']."',
                        est_estado='".$i_data['estado']."' 
                    WHERE est_empresa='".$i_data['empresa']."'
                    AND	est_codigo='".$i_data['codigo_sri']."'";
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Establecimiento actualizada exitosamente','datos'=>$i_data);      
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
       $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateEstablecimiento($i_data){
	try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Establecimiento ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        if (!isset($i_data['empresa'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
            return $o_respuesta;
        }
        if (!isset($i_data['codigo_sri'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_sri');
            return $o_respuesta;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=    "SELECT count(*) 
                        FROM del_establecimiento 
                        WHERE est_empresa   ='".$i_data['empresa']."'
                        and est_codigo      ='".$i_data['codigo_sri']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarEstablecimiento($i_data);
                }else{
                    $o_respuesta=actualizarEstablecimiento($i_data);
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

function validarDatosEstablecimiento($i_data){
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre_establecimiento'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre_establecimiento');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo_sri'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_sri');
        return $o_respuesta;
    }
    if (!isset($i_data['pais'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo pais');
        return $o_respuesta;
    }
    if (!isset($i_data['provincia'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo provincia');
        return $o_respuesta;
    }
    if (!isset($i_data['canton'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo canton');
        return $o_respuesta;
    }
    if (!isset($i_data['direccion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo direccion');
        return $o_respuesta;
    }
    if (!isset($i_data['estado'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado');
        return $o_respuesta;
    }
    if (!isset($i_data['ruta_logo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo ruta_logo');
        return $o_respuesta;
    }
    if (!isset($i_data['telefono'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo telefono');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}
?>