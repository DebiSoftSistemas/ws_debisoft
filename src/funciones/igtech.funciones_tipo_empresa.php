<?php

function listaTipoEmpresa(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Tipo Empresa ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT te_codigo,
                            te_descripcion,
                            te_tipo_impuesto 
                     FROM del_tipo_empresa";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'codigo'=>$row['te_codigo'],
                    'descripcion'=>$row['te_descripcion'],
                    'tarifa_iva'=>$row['te_tipo_impuesto'],
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

function seleccionarTipoEmpresa($i_codigo){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Tipo Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Codigo: '.$i_codigo);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT te_codigo,
                            te_descripcion,
                            te_tipo_impuesto 
                        FROM del_tipo_empresa
                        WHERE te_codigo='".$i_codigo."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'codigo'        =>$row->te_codigo,
                    'descripcion'   =>$row->te_descripcion,
                    'tarifa_iva'    =>$row->te_tipo_impuesto,
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

function registrarTipoEmpresa($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Tipo Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosTipoEmpresa($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*)
                    FROM del_tipo_empresa
                    WHERE te_codigo='".$i_data['codigo']."'";
        if ($result = pg_query($ws_conexion, $select_sql)){
            while($row = pg_fetch_array($result)) {
                if($row[0]==0){
                    $insert_sql="INSERT INTO del_tipo_empresa(
                        te_codigo,
                        te_descripcion,
                        te_tipo_impuesto) 
                    VALUES(
                        '".$i_data['codigo']."',
                        '".$i_data['descripcion']."',
                        '".$i_data['tarifa_iva']."'
                        )";
                    $Log->EscribirLog(' Consulta: '.$insert_sql);    
                    if (!$result = pg_query($ws_conexion, $insert_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                    }else{
                        $o_respuesta=array('error'=>'0','mensaje'=>'Tipo de Empresa creado exitosamente','datos'=>$i_data);
                    }
                }else{
                    $o_respuesta=array('error'=>'9996','mensaje'=>'Ya existe el Tipo de Empresa');
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

function actualizarTipoEmpresa($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Tipo Empresa ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosTipoEmpresa($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*)
                    FROM del_tipo_empresa
                    WHERE te_codigo='".$i_data['codigo']."'";
        if ($result = pg_query($ws_conexion, $select_sql)){
            while($row = pg_fetch_array($result)) { 
                if($row[0]==1){
                    $update_sql="UPDATE del_tipo_empresa 
                                SET	 
                                    te_descripcion  ='".$i_data['descripcion']."',
                                    te_tipo_impuesto='".$i_data['tarifa_iva']."' 
                                WHERE te_codigo     ='".$i_data['codigo']."';";    
                    $Log->EscribirLog(' Consulta: '.$update_sql);
                    if (!$result = pg_query($ws_conexion, $update_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                    }else{
                        $o_respuesta=array('error'=>'0','mensaje'=>'Tipo de Empresa actualizado exitosamente','datos'=>$i_data);     
                    }
                }else{
                    $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el Tipo de Empresa');
                }
            }
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }  
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }        
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosTipoEmpresa($i_data){
    
    if (!isset($i_data['codigo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo');
        return $o_respuesta;
    }
    if (!isset($i_data['descripcion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo descripcion');
        return $o_respuesta;
    }
    if (!isset($i_data['tarifa_iva'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tarifa_iva');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}
/*
function listaProductosTipoEmpresa($i_tipo_empresa){
    try{
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        pte_tipo_empresa,
                        pte_codigo,
                        pte_codigo_aux,
                        pte_nombre,
                        pte_descripcion,
                        pte_por_iva,
                        pte_precio 
                    FROM del_producto_tipo_empresa
                    WHERE pte_tipo_empresa='".$i_tipo_empresa."'";
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'tipo_empresa'=>$row['pte_tipo_empresa'],
                    'codigo'=>$row['pte_codigo'],
                    'codigo_aux'=>$row['pte_codigo_aux'],
                    'nombre'=>$row['pte_nombre'],
                    'descripcion'=>$row['pte_descripcion'],
                    'tarifa_iva'=>$row['pte_por_iva'],
                    'precio'=>$row['pte_precio'],
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
    return $o_respuesta;
}

function seleccionarProductosTipoEmpresa($i_tipo_empresa,$i_codigo){
    try{
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        pte_tipo_empresa,
                        pte_codigo,
                        pte_codigo_aux,
                        pte_nombre,
                        pte_descripcion,
                        pte_por_iva,
                        pte_precio 
                    FROM del_producto_tipo_empresa
                    WHERE pte_tipo_empresa='".$i_tipo_empresa."'
                    and pte_codigo='".$i_codigo."'";
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'tipo_empresa'=>$row->pte_tipo_empresa,
                    'codigo'=>$row->pte_codigo,
                    'codigo_aux'=>$row->pte_codigo_aux,
                    'nombre'=>$row->pte_nombre,
                    'descripcion'=>$row->pte_descripcion,
                    'tarifa_iva'=>$row->pte_por_iva,
                    'precio'=>$row->pte_precio,
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
    return $o_respuesta;
}

function registrarProductoTipoEmpresa($i_data){
    try{
        $w_validacion=validarDatosProductoTipoEmpresa($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*)
                    FROM del_producto_tipo_empresa
                    WHERE pte_tipo_empresa  ='".$i_data['tipo_empresa']."'
                    and pte_codigo          ='".$i_data['codigo']."';";
        if ($result = pg_query($ws_conexion, $select_sql)){
            while($row = pg_fetch_array($result)) {
                if($row[0]==0){
                    $insert_sql="INSERT INTO del_producto_tipo_empresa(
                        pte_tipo_empresa,
                        pte_codigo,
                        pte_codigo_aux,
                        pte_nombre,
                        pte_descripcion,
                        pte_por_iva,
                        pte_precio) 
                    VALUES
                        (
                            '".$i_data['tipo_empresa']."',
                            '".$i_data['codigo']."',
                            '".$i_data['codigo_aux']."',
                            '".$i_data['nombre']."',
                            '".$i_data['descripcion']."',
                            '".$i_data['tarifa_iva']."',
                            ".$i_data['precio']."
                        )";
                    if (!$result = pg_query($ws_conexion, $insert_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                    }else{
                        $o_respuesta=array('error'=>'0','mensaje'=>'Producto creado exitosamente','datos'=>$i_data);
                    }
                }else{
                    $o_respuesta=array('error'=>'9996','mensaje'=>'Ya existe el Producto');
                }
            }
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    return $o_respuesta;
}

function actualizarProductosTipoEmpresa($i_data){
    try{
        $w_validacion=validarDatosProductoTipoEmpresa($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*)
                    FROM del_producto_tipo_empresa
                    WHERE pte_tipo_empresa  ='".$i_data['tipo_empresa']."'
                    and pte_codigo          ='".$i_data['codigo']."';";
        if ($result = pg_query($ws_conexion, $select_sql)){
            while($row = pg_fetch_array($result)) {
                if($row[0]==1){
                    $update_sql="UPDATE del_producto_tipo_empresa 
                                SET	 
                                        pte_codigo_aux  ='".$i_data['codigo_aux']."',
                                        pte_nombre      ='".$i_data['nombre']."',
                                        pte_descripcion ='".$i_data['descripcion']."',
                                        pte_por_iva     ='".$i_data['tarifa_iva']."',
                                        pte_precio      = ".$i_data['precio']."
                                WHERE   pte_codigo      ='".$i_data['codigo']."'
                                AND     pte_tipo_empresa='".$i_data['tipo_empresa']."'";
                    if (!$result = pg_query($ws_conexion, $update_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                    }else{
                        $o_respuesta=array('error'=>'0','mensaje'=>'Producto actualizado exitosamente','datos'=>$i_data);
                    }
                }else{
                    $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el Producto');
                }
            }
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    return $o_respuesta;
}

function validarDatosProductoTipoEmpresa($i_data){
    if (!isset($i_data['tipo_empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo_empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo_aux'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_aux');
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
    if (!isset($i_data['tarifa_iva'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tarifa_iva');
        return $o_respuesta;
    }
    if (!isset($i_data['precio'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo precio');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}
*/
?>