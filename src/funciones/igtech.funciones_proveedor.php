<?php
function listaProveedores($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Proveedores ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT id,
                            empresa,
                            nombre,
                            codigo_tipo_identificacion,
                            tipo_identificacion,
                            identificacion,
                            pais,
                            provincia,
                            ciudad,
                            direccion,
                            telefono,
                            celular,
                            email,
                            codigo_ret_iva,
                            descripcion_ret_iva,
                            porcentaje_ret_iva 
                        FROM v_ws_proveedores
                        WHERE empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(	
                    'id'=>							$row['id'],
                    'empresa'=>						$row['empresa'],
                    'nombre'=>						$row['nombre'],
                    'tipo_identificacion'=>			$row['tipo_identificacion'],
                    'identificacion'=>				$row['identificacion'],
                    'pais'=>						$row['pais'],
                    'provincia'=>					$row['provincia'],
                    'ciudad'=>						$row['ciudad'],
                    'direccion'=>					$row['direccion'],
                    'telefono'=>					$row['telefono'],
                    'celular'=>						$row['celular'],
                    'email'=>						$row['email'],
                    'codigo_ret_iva'=>              $row['codigo_ret_iva'],
                    'descripcion_ret_iva'=>         $row['descripcion_ret_iva'],
                    'porcentaje_ret_iva'=>          $row['porcentaje_ret_iva']
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'','datos'=>$w_respuesta);  
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

function seleccionarProveedor($i_empresa,$i_proveedor){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Proveedor ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Proveedor: '.$i_proveedor);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT id,
                            empresa,
                            nombre,
                            codigo_tipo_identificacion,
                            tipo_identificacion,
                            identificacion,
                            pais,
                            provincia,
                            ciudad,
                            direccion,
                            telefono,
                            celular,
                            email,
                            codigo_ret_iva,
                            descripcion_ret_iva,
                            porcentaje_ret_iva 
                        FROM v_ws_proveedores
                        WHERE empresa='".$i_empresa."'
                        AND   identificacion='".$i_proveedor."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 );
                $w_respuesta = array(
                    'id'=>$row->id,
                    'empresa'=>$row->empresa,
                    'nombre'=>$row->nombre,
                    'tipo_identificacion'=>$row->tipo_identificacion,
                    'identificacion'=>$row->identificacion,
                    'pais'=>$row->pais,
                    'provincia'=>$row->provincia,
                    'ciudad'=>$row->ciudad,
                    'direccion'=>$row->direccion,
                    'telefono'=>$row->telefono,
                    'celular'=>$row->celular,
                    'email'=>$row->email,
                    'codigo_ret_iva'=>$row->codigo_ret_iva,
                    'descripcion_ret_iva'=>$row->descripcion_ret_iva,
                    'porcentaje_ret_iva'=>$row->porcentaje_ret_iva
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'Ok','datos'=>$w_respuesta);  
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el proveedor');    
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

function registrarProveedor($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Proveedor ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        if(strtoupper($i_data['tipo_identificacion'])=='RUC' or strtoupper($i_data['tipo_identificacion'])=='CEDULA'){
           $valida_cedruc=validar_CedulaRuc($i_data['identificacion'],strtoupper($i_data['tipo_identificacion']));     
        }else{
            $valida_cedruc=1; 
        }
        if($valida_cedruc==1){
            $ws_conexion=ws_coneccion_bdd();
            $insert_sql="INSERT INTO del_proveedor(
                            pr_id,
                            pr_empresa,
                            pr_nombre,
                            pr_tipo_identificacion,
                            pr_identificacion,
                            pr_pais,
                            pr_provincia,
                            pr_ciudad,
                            pr_direccion,
                            pr_telefono,
                            pr_celular,
                            pr_email,
                            pr_retencion_iva
                            ) 
                        VALUES(
                            (SELECT coalesce(max(pr_id),0)+1 FROM del_proveedor),
                            '".$i_data['empresa']."',
                            '".$i_data['nombre']."',
                            (SELECT ti_codigo FROM sri_tipo_identificacion WHERE ti_nombre='".strtoupper($i_data['tipo_identificacion'])."'),
                            '".$i_data['identificacion']."',
                            (SELECT pai_id FROM sis_pais WHERE pai_nombre IN ('".strtoupper($i_data['pais'])."','ECUADOR') ),
                            (SELECT pro_id FROM sis_provincia WHERE pro_nombre='".strtoupper($i_data['provincia'])."'),
                            (SELECT can_id FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."'),
                            '".$i_data['direccion']."',
                            '".$i_data['telefono']."',
                            '".$i_data['celular']."',
                            '".$i_data['email']."',
                            '".$i_data['codigo_ret_iva']."');"; 
            $Log->EscribirLog(' Consulta: '.$insert_sql);    
            if (!$result = pg_query($ws_conexion, $insert_sql)){
                $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
            }else{
                $o_respuesta=array('error'=>'0','mensaje'=>'','datos'=>$i_data);     
            }
        }else{
            $o_respuesta=array('error'=>'9996','mensaje'=>'Cedula o Ruc incorrecto');           
        }        
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
            $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }        
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarProveedor($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Proveedor ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        
        $update_sql="UPDATE del_proveedor 
                    SET	pr_nombre='".$i_data['nombre']."',
                        pr_pais=(SELECT pai_id FROM sis_pais WHERE pai_nombre IN ('".strtoupper($i_data['pais'])."','ECUADOR') ),
                        pr_provincia=(SELECT pro_id FROM sis_provincia WHERE pro_nombre='".strtoupper($i_data['provincia'])."'),
                        pr_ciudad=(SELECT can_id FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."'),
                        pr_direccion='".$i_data['direccion']."',
                        pr_telefono='".$i_data['telefono']."',
                        pr_celular='".$i_data['celular']."',
                        pr_email='".$i_data['email']."',
                        pr_retencion_iva='".$i_data['codigo_ret_iva']."' 
                    WHERE pr_empresa='".$i_data['empresa']."'
                    and pr_identificacion='".$i_data['identificacion']."';"; 
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Proveedor actualizado exitosamente','datos'=>$i_data);      
        }
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());    
    }        
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateProveedor($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Proveedor ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosProveedor($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                        FROM del_proveedor 
                        WHERE pr_empresa        ='".$i_data['empresa']."'
                        AND pr_identificacion   ='".$i_data['identificacion']."'";
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarProveedor($i_data);    
                }else{
                    $o_respuesta=actualizarProveedor($i_data);    
                }
            }    
        } 
    }catch (Throwable $e) {
       $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }  
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosProveedor($i_data){
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre');
        return $o_respuesta;
    }
    if (!isset($i_data['tipo_identificacion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo_identificacion');
        return $o_respuesta;
    }
    if (!isset($i_data['identificacion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo identificacion');
        return $o_respuesta;
    }
    if (!isset($i_data['pais'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo pais');
        return $o_respuesta;
    }
    if (!isset($i_data['provincia'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo provincial');
        return $o_respuesta;
    }
    if (!isset($i_data['ciudad'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo ciudad');
        return $o_respuesta;
    }
    if (!isset($i_data['direccion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo direccion');
        return $o_respuesta;
    }
    if (!isset($i_data['telefono'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo telefono');
        return $o_respuesta;
    }
    if (!isset($i_data['celular'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo celular');
        return $o_respuesta;
    }
    if (!isset($i_data['email'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo email');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo_ret_iva'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_ret_iva');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

?>