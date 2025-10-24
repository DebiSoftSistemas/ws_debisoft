<?php


function listaFormasPagoEmpresa($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Formas de Pago Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT 
                        fp_id,
                        fp_empresa,
                        fp_descripcion,
                        fp_sri
                    FROM del_forma_pago
                    where fp_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'id'            =>$row['fp_id'],
                    'empresa'       =>$row['fp_empresa'],
                    'descripcion'   =>$row['fp_descripcion'],
                    'codigo_sri'    =>$row['fp_sri']
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

function seleccionarFormasPagoEmpresa($i_empresa,$i_formaPago){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Forma de Pago Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Forma de Pago: '.$i_formaPago);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT 
                        fp_id,
                        fp_empresa,
                        fp_descripcion,
                        fp_sri
                    FROM del_forma_pago
                    where fp_empresa='".$i_empresa."'
                    and fp_descripcion='".$i_formaPago."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(
                    'id'            =>$row->fp_id,
                    'empresa'       =>$row->fp_empresa,
                    'descripcion'   =>$row->fp_descripcion,
                    'codigo_sri'    =>$row->fp_sri
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);    
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No se encuentra la forma de Pago');
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

function registrarFormaPagoEmpresa($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Forma de Pago Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_forma_pago(
                        fp_id,
                        fp_empresa,
                        fp_descripcion,
                        fp_sri,
                        fp_contable,
                        fp_estado) 
                    VALUES
                        (
                            (SELECT max(fp_id)+1 FROM del_forma_pago),
                            '".$i_data['empresa']."',
                            '".$i_data['descripcion']."',
                            '".$i_data['codigo_sri']."',
                            '',
                            'V'
                        );";    
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Forma de Pago creada exitosamente','datos'=>$i_data);    
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
       $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }   
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarFormaPagoEmpresa($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Forma de Pago Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE del_forma_pago 
                    SET	 
                        fp_descripcion  ='".$i_data['descripcion']."',
                        fp_sri          ='".$i_data['codigo_sri']."',
                        fp_contable     ='',
                        fp_estado       ='V' 
                    WHERE fp_empresa      ='".$i_data['empresa']."'
                    AND fp_descripcion='".$i_data['descripcion']."';";
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Forma de Pago actualizada exitosamente','datos' => $i_data);
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }   
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateFormaPagoEmpresa($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Forma de Pago Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosFomaPagoEmpresa($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                    FROM del_forma_pago
                    where fp_empresa  ='".$i_data['empresa']."'
                    and fp_descripcion='".$i_data['descripcion']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarFormaPagoEmpresa($i_data);    
                }else{
                    $o_respuesta=actualizarFormaPagoEmpresa($i_data);    
                }
            }    
        } 
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosFomaPagoEmpresa($i_data){
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['descripcion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo descripcion');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo_sri'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_sri');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function listaMetodosPago(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Metodos Pago ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        fp_id,
                        fp_codigo,
                        fp_nombre,
                        fp_descripcion,
                        fp_sri 
                    FROM del_forma_pago_runrumm";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'codigo'=>$row['fp_codigo'],
                    'nombre'=>$row['fp_nombre'],
                    'descripcion'=>$row['fp_descripcion'],
                    'codigo_sri'=>$row['fp_sri'],
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

function seleccionarMetodoPago($i_codigo){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Metodo de Pago ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Codigo: '.$i_codigo);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            fp_id,
                            fp_codigo,
                            fp_nombre,
                            fp_descripcion,
                            fp_sri 
                        FROM del_forma_pago_runrumm
                        WHERE fp_codigo='".$i_codigo."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'codigo'=>      $row->fp_codigo,
                    'nombre'=>      $row->fp_nombre,
                    'descripcion'=> $row->fp_descripcion,
                    'codigo_sri'=>  $row->fp_sri,
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el Metodo de Pago');
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

function registrarMetodoPago($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Metodo de Pago ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_forma_pago_runrumm(
                            fp_id,
                            fp_codigo, 
                            fp_nombre,
                            fp_descripcion,
                            fp_sri)
                    VALUES
                        (
                            (SELECT coalesce(max(fp_id),0)+1 FROM del_forma_pago_runrumm),
                            '".$i_data['codigo']."',
                            '".$i_data['nombre']."',
                            '".$i_data['descripcion']."',
                            '".$i_data['codigo_sri']."'
                        )";
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje Metodo de Pago creado exitosamente','datos'=>$i_data);
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarMetodoPago($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Metodo de Pago ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE del_forma_pago_runrumm 
                    SET	
                        fp_nombre       ='".$i_data['nombre']."',
                        fp_descripcion  ='".$i_data['descripcion']."',
                        fp_sri          ='".$i_data['codigo_sri']."'	
                    WHERE
                        fp_codigo       ='".$i_data['codigo']."'";
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Metodo de Pago actualizado exitosamente','datos'=>$i_data);
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateMetodoPago($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Metodo Pago ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosMetodoPago($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                    FROM del_forma_pago_runrumm
                    where fp_codigo='".$i_data['codigo']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);            
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarMetodoPago($i_data);    
                }else{
                    $o_respuesta=actualizarMetodoPago($i_data);    
                }
            }    
        } 
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosMetodoPago($i_data){
    if (!isset($i_data['codigo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre');
        return $o_respuesta;
    }
    if (!isset($i_data['descripcion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo descripcion');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo_sri'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_sri');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

?>