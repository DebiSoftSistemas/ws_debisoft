<?php
function listaClientes($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Clientes ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa:'.$i_empresa);
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
                            email 
                        FROM v_ws_clientes
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
                    'email'=>						$row['email']
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

function seleccionarClientes($i_empresa,$i_cliente){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Clientes');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Cliente: '.$i_cliente);

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
                            email 
                        FROM v_ws_clientes
                        WHERE empresa='".$i_empresa."'
                        AND   identificacion='".$i_cliente."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 );
                $w_respuesta = array(
                    'id'                        =>$row->id,
                    'empresa'                   =>$row->empresa,
                    'nombre'                    =>$row->nombre,
                    'tipo_identificacion'       =>$row->tipo_identificacion,
                    'identificacion'            =>$row->identificacion,
                    'pais'                      =>$row->pais,
                    'provincia'                 =>$row->provincia,
                    'ciudad'                    =>$row->ciudad,
                    'direccion'                 =>$row->direccion,
                    'telefono'                  =>$row->telefono,
                    'celular'                   =>$row->celular,
                    'email'                     =>$row->email
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

function registrarCliente($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Cliente ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        if(strlen($i_data['identificacion'])==10){
            $valida_cedruc=validar_CedulaRuc($i_data['identificacion'],'CEDULA');
            if($valida_cedruc==1){
                $tipo_identificacion='CEDULA';    
            }else{
                $valida_cedruc=1;
                $tipo_identificacion='PASAPORTE';
            }
        }elseif(strlen($i_data['identificacion'])==13){
            $valida_cedruc=validar_CedulaRuc($i_data['identificacion'],'RUC');
            if($valida_cedruc==1){
                $tipo_identificacion='RUC';    
            }else{
                $valida_cedruc=1;
                $tipo_identificacion='PASAPORTE';
            }
        }else{
            $valida_cedruc=1;
            $tipo_identificacion='PASAPORTE';
        }

        // if(strtoupper($i_data['tipo_identificacion'])=='RUC' or strtoupper($i_data['tipo_identificacion'])=='CEDULA'){
        //    $valida_cedruc=validar_CedulaRuc($i_data['identificacion'],strtoupper($i_data['tipo_identificacion']));     
        // }elseif(strtoupper($i_data['tipo_identificacion'])=='PASAPORTE'){
        //         $valida_cedruc=1;
        // }elseif(strtoupper($i_data['tipo_identificacion'])=='VENTA CONSUMIDOR FINAL' and $i_data['identificacion']=='9999999999999'){
        //     $valida_cedruc=1;
        // }else{
        //     $valida_cedruc=0;
        // }
        if($valida_cedruc==1){
            $ws_conexion=ws_coneccion_bdd();
            $insert_sql="INSERT INTO del_cliente(
                            cl_id,
                            cl_empresa,
                            cl_nombre,
                            cl_tipo_identificacion,
                            cl_identificacion,
                            cl_pais,
                            cl_provincia,
                            cl_ciudad,
                            cl_direccion,
                            cl_telefono,
                            cl_celular,
                            cl_email) 
                        VALUES(
                            (SELECT max(cl_id)+1 FROM del_cliente),
                            '".$i_data['empresa']."',
                            '".$i_data['nombre']."',
                            (SELECT ti_codigo FROM sri_tipo_identificacion WHERE ti_nombre='".strtoupper($tipo_identificacion)."'),
                            '".$i_data['identificacion']."',
                            (SELECT can_pais FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."' LIMIT 1),
                            (SELECT can_provincia FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."' LIMIT 1),
                            (SELECT can_id FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."'),
                            '".$i_data['direccion']."',
                            '".$i_data['telefono']."',
                            '".$i_data['celular']."',
                            '".$i_data['email']."');"; 
            $Log->EscribirLog(' Consulta: '.$insert_sql);
            if (!$result = pg_query($ws_conexion, $insert_sql)){
                $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
            }else{
                $o_respuesta=array('error'=>'0','mensaje'=>'Cliente creada exitosamente','datos' => $i_data);        
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

function actualizarCliente($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Cliente ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Dato: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();

        if(strlen($i_data['identificacion'])==10){
            $valida_cedruc=validar_CedulaRuc($i_data['identificacion'],'CEDULA');
            if($valida_cedruc==1){
                $tipo_identificacion='CEDULA';    
            }else{
                $valida_cedruc=1;
                $tipo_identificacion='PASAPORTE';
            }
        }elseif(strlen($i_data['identificacion'])==13){
            $valida_cedruc=validar_CedulaRuc($i_data['identificacion'],'RUC');
            if($valida_cedruc==1){
                $tipo_identificacion='RUC';    
            }else{
                $valida_cedruc=1;
                $tipo_identificacion='PASAPORTE';
            }
        }else{
            $valida_cedruc=1;
            $tipo_identificacion='PASAPORTE';
        }

        $update_sql="UPDATE del_cliente 
                    SET	cl_nombre='".$i_data['nombre']."',
                        cl_tipo_identificacion=(SELECT ti_codigo FROM sri_tipo_identificacion WHERE ti_nombre='".strtoupper($tipo_identificacion)."'),
                        cl_pais     =(SELECT can_pais FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."' LIMIT 1),
                        cl_provincia=(SELECT can_provincia FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."' LIMIT 1),
                        cl_ciudad=(SELECT can_id FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."'),
                        cl_direccion='".$i_data['direccion']."',
                        cl_telefono='".$i_data['telefono']."',
                        cl_celular='".$i_data['celular']."',
                        cl_email='".$i_data['email']."' 
                    WHERE cl_empresa='".$i_data['empresa']."'
                    and cl_identificacion='".$i_data['identificacion']."';"; 
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Cliente actualizado exitosamente','datos'=>$i_data);        
        }
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }        
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function inserUpdateCliente($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Cliente ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosCliente($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                    FROM del_cliente 
                    WHERE cl_empresa        ='".$i_data['empresa']."'
                        AND cl_identificacion   ='".$i_data['identificacion']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarCliente($i_data);    
                }else{
                    $o_respuesta=actualizarCliente($i_data);    
                }
            }    
        } 
        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;  
}

function validarDatosCliente($i_data){
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
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

?>
