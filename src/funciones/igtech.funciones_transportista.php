<?php
function listaTransportistas($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Transportistas ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            id,
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
                            placa 
                        FROM v_ws_transportistas
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
                    'placa'=>						$row['placa'],
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

function seleccionarTransportistas($i_empresa,$i_transportista){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' seleccionar Transportista ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Transportista: '.$i_transportista);
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
                            placa 
                        FROM v_ws_transportistas
                        WHERE empresa='".$i_empresa."'
                        AND   identificacion='".$i_transportista."';";
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
                    'email'                     =>$row->email,
                    'placa'                     =>$row->placa
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

function registrarTransportista($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Transportista ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        if(strtoupper($i_data['tipo_identificacion'])=='RUC' or strtoupper($i_data['tipo_identificacion'])=='CEDULA'){
           $valida_cedruc=validar_CedulaRuc($i_data['identificacion'],strtoupper($i_data['tipo_identificacion']));     
        }else{
            $valida_cedruc=1; 
        }
        if($valida_cedruc==1){
            $ws_conexion=ws_coneccion_bdd();
            $insert_sql="INSERT INTO del_transportista(
                            tr_id,
                            tr_empresa,
                            tr_nombre,
                            tr_tipo_identificacion,
                            tr_identificacion,
                            tr_pais,
                            tr_provincia,
                            tr_ciudad,
                            tr_direccion,
                            tr_telefono,
                            tr_celular,
                            tr_email,
                            tr_placa) 
                        VALUES(
                            (SELECT coalesce(max(tr_id),0)+1 FROM del_transportista),
                            '".$i_data['empresa']."',
                            '".$i_data['nombre']."',
                            (SELECT ti_codigo FROM sri_tipo_identificacion WHERE ti_nombre='".strtoupper($i_data['tipo_identificacion'])."'),
                            '".$i_data['identificacion']."',
                            (SELECT pai_id FROM sis_pais WHERE pai_nombre IN ('".strtoupper($i_data['pais'])."','ECUADOR')),
                            (SELECT pro_id FROM sis_provincia WHERE pro_nombre='".strtoupper($i_data['provincia'])."'),
                            (SELECT can_id FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."'),
                            '".$i_data['direccion']."',
                            '".$i_data['telefono']."',
                            '".$i_data['celular']."',
                            '".$i_data['email']."',
                            '".$i_data['placa']."');";    
            $Log->EscribirLog(' Consulta: '.$insert_sql);
            if (!$result = pg_query($ws_conexion, $insert_sql)){
                $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
            }else{
                $o_respuesta=array('error'=>'0','mensaje'=>'Transportista creada exitosamente','datos'=>$i_data);     
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

function actualizarTransportista($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Transportista ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true ));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE del_transportista 
                    SET	tr_nombre='".$i_data['nombre']."',
                        tr_pais=(SELECT pai_id FROM sis_pais WHERE pai_nombre IN('".strtoupper($i_data['pais'])."','ECUADOR') ),
                        tr_provincia=(SELECT pro_id FROM sis_provincia WHERE pro_nombre='".strtoupper($i_data['provincia'])."'),
                        tr_ciudad=(SELECT can_id FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."'),
                        tr_direccion='".$i_data['direccion']."',
                        tr_telefono='".$i_data['telefono']."',
                        tr_celular='".$i_data['celular']."',
                        tr_email='".$i_data['email']."',
                        tr_placa='".$i_data['placa']."' 
                    WHERE tr_empresa='".$i_data['empresa']."'
                    and tr_identificacion='".$i_data['identificacion']."';";    
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Transportista actualizado exitosamente','datos'=>$i_data);       
        }
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
            $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }        
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateTransportista($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Transportista ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosTransportista($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                        FROM del_Transportista 
                        WHERE tr_empresa        ='".$i_data['empresa']."'
                        AND tr_identificacion   ='".$i_data['identificacion']."'";
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarTransportista($i_data);    
                }else{
                    $o_respuesta=actualizarTransportista($i_data);    
                }
            }    
        }else{
           $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
            $close = pg_close($ws_conexion); 
        }                 
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));  
    return $o_respuesta;
}

function validarDatosTransportista($i_data){
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
    if (!isset($i_data['placa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo placa');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}
?>