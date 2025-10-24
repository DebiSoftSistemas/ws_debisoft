<?php
include_once('src/funciones/igtech.validar_cedula.php');

function listaEmpresas(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Empresa ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        emp_ruc,
                        emp_logo,
                        emp_razon_social,
                        emp_nombre_comercial,
                        emp_direccion_matriz,
                        emp_email,
                        emp_telefono,
                        emp_obligado,
                        escontribuyenteespecial,
                        emp_contribuyente_especial,
                        emp_regimen_especial,
                        emp_agente_retencion,
                        emp_es_op_transporte,
                        esartesanocalificado,
                        emp_calificacion_artesanal,
                        emp_estado
                FROM del_empresa;";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(	
                    'emp_ruc'                   =>$row['emp_ruc'],
                    'emp_logo'                  =>$row['emp_logo'],
                    'emp_razon_social'          =>$row['emp_razon_social'],
                    'emp_nombre_comercial'      =>$row['emp_nombre_comercial'],
                    'emp_direccion_matriz'      =>$row['emp_direccion_matriz'],
                    'emp_email'                 =>$row['emp_email'],
                    'emp_telefono'              =>$row['emp_telefono'],
                    'emp_obligado'              =>$row['emp_obligado'],
                    'escontribuyenteespecial'   =>$row['escontribuyenteespecial'],
                    'emp_contribuyente_especial'=>$row['emp_contribuyente_especial'],
                    'emp_regimen_especial'      =>$row['emp_regimen_especial'],
                    'emp_agente_retencion'      =>$row['emp_agente_retencion'],
                    'operadora_transporte'      =>$row['emp_es_op_transporte'],
                    'esartesanocalificado'      =>$row['esartesanocalificado'],
                    'emp_calificacion_artesanal'=>$row['emp_calificacion_artesanal'],
                    'emp_estado'                =>$row['emp_estado'],
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

function seleccionarEmpresa($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        if ($i_empresa=='1091786547001'){
            $select_sql="SELECT 
                            emp_ruc,
                            '' as emp_logo,
                            emp_razon_social,
                            emp_nombre_comercial,
                            emp_direccion as emp_direccion_matriz,
                            fil_email as emp_email,
                            fil_telefono as  emp_telefono,
                            fil_obligado_contabilidad as emp_obligado,
                            'N' as escontribuyenteespecial,
                            fil_contribuyente_especial as emp_contribuyente_especial,
                            'N' as emp_regimen_especial,
                            'N' as emp_agente_retencion,
                            'N' as emp_es_op_transporte,
                            'N' as esartesanocalificado,
                            '' as emp_calificacion_artesanal,
                            fil_estado as emp_estado
                        from v_sis_datos_empresa_sri
                        WHERE emp_ruc='".$i_empresa."';";
        }else{
            $select_sql="SELECT 
                            emp_ruc,
                            emp_logo,
                            emp_razon_social,
                            emp_nombre_comercial,
                            emp_direccion_matriz,
                            emp_email,
                            emp_telefono,
                            emp_obligado,
                            escontribuyenteespecial,
                            emp_contribuyente_especial,
                            emp_regimen_especial,
                            emp_agente_retencion,
                            emp_es_op_transporte,
                            esartesanocalificado,
                            emp_calificacion_artesanal,
                            emp_estado
                        FROM del_empresa
                        WHERE emp_ruc='".$i_empresa."';";
        }            
        $Log->EscribirLog(' Consulta: '.$select_sql);             
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta=array();
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(	
                    'emp_ruc'                   =>$row->emp_ruc,
                    'emp_logo'                  =>$row->emp_logo,
                    'emp_razon_social'          =>$row->emp_razon_social,
                    'emp_nombre_comercial'      =>$row->emp_nombre_comercial,
                    'emp_direccion_matriz'      =>$row->emp_direccion_matriz,
                    'emp_email'                 =>$row->emp_email,
                    'emp_telefono'              =>$row->emp_telefono,
                    'emp_obligado'              =>$row->emp_obligado,
                    'escontribuyenteespecial'   =>$row->escontribuyenteespecial,
                    'emp_contribuyente_especial'=>$row->emp_contribuyente_especial,
                    'emp_regimen_especial'      =>$row->emp_regimen_especial,
                    'emp_agente_retencion'      =>$row->emp_agente_retencion,
                    'operadora_transporte'      =>$row->emp_es_op_transporte,
                    'esartesanocalificado'      =>$row->esartesanocalificado,
                    'emp_calificacion_artesanal'=>$row->emp_calificacion_artesanal,
                    'emp_estado'                =>$row->emp_estado,

                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);   
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No hay datos de la empresa');
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

function registrarEmpresa($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosRegistro($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        if(isset($i_data['operadora_transporte'])){
            if($i_data['operadora_transporte']=='S' or $i_data['operadora_transporte']=='N'){
                $w_operadora_tranporte=$i_data['operadora_transporte'];
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'campo operadora_transporte es diferente de S o N'); 
                return $o_respuesta;
            }    
        }else{
            $w_operadora_tranporte='N';
        }
        if(isset($i_data['tipo_empresa'])){
            $w_tipo_empresa=$i_data['tipo_empresa'];
        }else{
            $w_tipo_empresa='';
        }
        if (validar_CedulaRuc($i_data['ruc'],'RUC')==0){
            $o_respuesta=array('error'=>'9996','mensaje'=>'RUC Incorrecto'); 
            return $o_respuesta;  
        }
        $w_crear_direc=crearDirectorios($i_data['ruc']);
        $w_ruta_logo=$w_crear_direc['datos']['ruta_logo'];
        $w_ruta_firma=$w_crear_direc['datos']['ruta_firma'];
        $w_ruta_logo_interno=$w_crear_direc['datos']['ruta_logo_interno'];
        $w_ruta_firma_interno=$w_crear_direc['datos']['ruta_firma_interno'];
        if ($i_data['ruta_logo']<>""){
            $w_nombre_logo=copiarArchivos($i_data['ruta_logo'],$w_ruta_logo,$w_ruta_logo_interno);    
        }else{
            $w_nombre_logo='';
        }
        if ($i_data['ruta_firma']<>"" ){
            $w_nombre_firma=copiarArchivos($i_data['ruta_firma'],$w_ruta_firma,$w_ruta_firma_interno);    
        }else{
            $w_nombre_firma='';
        }   
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_empresa(
                    emp_ruc,
                    emp_razon_social,
                    emp_nombre_comercial,
                    emp_representante_legal,
                    emp_direccion_matriz,
                    emp_logo,
                    emp_firma,
                    emp_clave_firma,
                    emp_telefono,
                    emp_email,
                    emp_ambiente_sri,
                    emp_obligado,
                    emp_contribuyente_especial,
                    emp_regimen_especial, 
                    emp_agente_retencion,
                    emp_autorizacion_inmediata,
                    emp_es_op_transporte,
                    emp_estado,
                    emp_tipo_empresa)
        VALUES(
                '".$i_data['ruc']."',
                '".$i_data['razon_social']."',
                '".$i_data['nombre_comercial']."',
                '".$i_data['representante_legal']."',
                '".$i_data['direccion_matriz']."',
                '".$w_nombre_logo."',
                '".$w_nombre_firma."',
                '".$i_data['clave_firma']."',
                '".$i_data['telefono']."',
                '".$i_data['email']."',
                '".$i_data['ambiente_sri']."',
                '".substr($i_data['obligado_contabilidad'],0,1)."',
                '".$i_data['contribuyente_especial']."',
                '".$i_data['regimen_especial']."',
                '".$i_data['agente_retencion']."',
                'N',
                '".$w_operadora_tranporte."',
                'V',
                '".$w_tipo_empresa."'
            )";    
        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        if(!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
            $close=pg_close($ws_conexion);    
            return $o_respuesta;
        }
        $exec_sql= "SELECT  * 
                    from sp_inicializa_empresa ('".$i_data['ruc']."')
                    as (o_num_error int, o_Mensaje_Error varchar);";
        if(!$rs_exec=pg_query($ws_conexion, $exec_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Empresa creada exitosamente','datos'=>$i_data); 
        }
        $close=pg_close($ws_conexion);
    }catch(Throwable $e){
       $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarEmpresa($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosRegistro($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $w_datos_empresa_anterior=seleccionarEmpresa($i_data['ruc']);
        if (validar_CedulaRuc($i_data['ruc'],'RUC')==0){
            $o_respuesta=array('error'=>'9996','mensaje'=>'RUC Incorrecto'); 
            return $o_respuesta;  
        }
        if(isset($i_data['operadora_transporte'])){
            if($i_data['operadora_transporte']=='S' or $i_data['operadora_transporte']=='N'){
                $w_operadora_tranporte=$i_data['operadora_transporte'];
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'campo operadora_transporte es diferente de S o N'); 
                return $o_respuesta;
            }    
        }else{
            $w_operadora_tranporte='N';
        }
        /*
        if(isset($i_data['tipo_empresa'])){
            $w_tipo_empresa=$i_data['tipo_empresa'];
        }else{
            $w_tipo_empresa='';
        }*/
        
        /*
        $w_crear_direc=crearDirectorios($i_data['ruc']);
        $w_ruta_logo=$w_crear_direc['datos']['ruta_logo'];
        $w_ruta_firma=$w_crear_direc['datos']['ruta_firma'];
        $w_ruta_logo_interno=$w_crear_direc['datos']['ruta_logo_interno'];
        $w_ruta_firma_interno=$w_crear_direc['datos']['ruta_firma_interno'];
        if ($i_data['ruta_logo']<>""){
            $w_nombre_logo=copiarArchivos($i_data['ruta_logo'],$w_ruta_logo,$w_ruta_logo_interno);    
        }else{
            $w_nombre_logo=$w_datos_empresa_anterior['datos']['nombre_logo'];
        }
        if ($i_data['ruta_firma']<>""){
            $w_nombre_firma=copiarArchivos($i_data['ruta_firma'],$w_ruta_firma,$w_ruta_firma_interno);    
        }else{
            $w_nombre_firma=$w_datos_empresa_anterior['datos']['nombre_firma'];
        }
        if($i_data['clave_firma']<>''){
            $w_clave_firma=$i_data['clave_firma'];
        }else{
            $w_clave_firma=$w_datos_empresa_anterior['datos']['clave_firma'];
        }  */ 
        $ws_conexion=ws_coneccion_bdd();        
        $update_sql="UPDATE del_empresa 
                     SET	 
                        emp_razon_social            ='".$i_data['razon_social']."',
                        emp_nombre_comercial        ='".$i_data['nombre_comercial']."',
                        emp_representante_legal     ='".$i_data['representante_legal']."',
                        emp_direccion_matriz        ='".$i_data['direccion_matriz']."',
                        emp_telefono                ='".$i_data['telefono']."',
                        emp_email                   ='".$i_data['email']."',
                        emp_obligado                ='".substr($i_data['obligado_contabilidad'],0,1)."',
                        emp_contribuyente_especial  ='".$i_data['contribuyente_especial']."',
                        emp_regimen_especial        ='".$i_data['regimen_especial']."',
                        emp_agente_retencion        ='".$i_data['agente_retencion']."',
                        emp_ambiente_sri            ='".$i_data['ambiente_sri']."',
                        emp_es_op_transporte        ='".$w_operadora_tranporte."'
                     WHERE
                        emp_ruc='".$i_data['ruc']."';";    
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Empresa actualizada exitosamente','datos' => $i_data);        
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
       $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertupdateEmpresa($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        if (!isset($i_data['ruc'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo ruc');
            return $o_respuesta;
        }
        
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT COUNT(*) 
                        FROM del_empresa 
                        WHERE emp_ruc='".$i_data['ruc']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                //$close=pg_close($ws_conexion);
                if($row->count==0){
                    $o_respuesta=registrarEmpresa($i_data);    
                }else{
                    $o_respuesta=actualizarEmpresa($i_data);    
                }
            }    
        } 
        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;   
}

function validarDatosRegistro($i_data){
    if (!isset($i_data['ruc'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo ruc');
        return $o_respuesta;
    }
    if (!isset($i_data['razon_social'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo razon_social');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre_comercial'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre_comercial');
        return $o_respuesta;
    }
    if (!isset($i_data['representante_legal'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo representante_legal');
        return $o_respuesta;
    }
    if (!isset($i_data['direccion_matriz'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo direccion_matriz');
        return $o_respuesta;
    }
    if (!isset($i_data['telefono'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo telefono');
        return $o_respuesta;
    }
    if (!isset($i_data['email'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo email');
        return $o_respuesta;
    }
    
    if (!isset($i_data['obligado_contabilidad'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo obligado_contabilidad');
        return $o_respuesta;
    }
    if (!isset($i_data['contribuyente_especial'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo contribuyente_especial');
        return $o_respuesta;
    }
    if (!isset($i_data['regimen_especial'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo regimen_especial');
        return $o_respuesta;
    }
    if (!isset($i_data['agente_retencion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo agente_retencion');
        return $o_respuesta;
    }
    if (!isset($i_data['operadora_transporte'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo operadora_transporte');
        return $o_respuesta;
    }
    if (!isset($i_data['ambiente_sri'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo ambiente_sri');
        return $o_respuesta;
    }else{
        if($i_data['ambiente_sri']<>'1' and $i_data['ambiente_sri']<>'2'){
            $o_respuesta=array('error'=>'9999','mensaje'=>'Valor incorrecto para ambiente_sri');
            return $o_respuesta;
        }
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
        return $o_respuesta;
} 

?>
