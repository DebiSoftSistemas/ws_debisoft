<?php
include_once ('src/funciones/igtech.funciones_pedidos.php');
function ejecutarFacturaDelivery($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Ejecutar Factura Delivery ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT * from  sp_factura_delivery('".$i_data['empresa']."', 
                                                        '".$i_data['usuario']."', 
                                                         ".$i_data['cliente'].", 
                                                        '".$i_data['servicio']."', 
                                                         ".$i_data['valor'].", 
                                                        '".$i_data['forma_pago']."',
                                                        '".$i_data['lote']."',
                                                        '".$i_data['transaccion']."') 
                    as (a integer, b varchar, c integer)";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'error'=>$row->a,
                    'mensaje'=>$row->b,
                    'datos'=>$row->c
                );
            }
            $o_respuesta=$w_respuesta;
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

function registrarFacturaDelivery($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Factura Delivery ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validarDatos=validarDatosFacturaDelivery($i_data);
        if($w_validarDatos['error']<>'0'){
            return $w_validarDatos;
        }
        $w_datos_convenio=verificaConvenio($i_data['convenio']);
        if ($w_datos_convenio['error']<>0){
            return $w_datos_convenio;
        }
        $w_datos_cliente=inserUpdateCliente($i_data['cliente']);
        if ($w_datos_cliente['error']<>0){
            return $w_datos_cliente;
        }
        $w_datos_cliente=seleccionarClientes($i_data['empresa'],$i_data['cliente']['identificacion']);
        if ($w_datos_cliente['error']<>0){
            return $w_datos_cliente;
        }
        $datos_forma_pago=array(
            'empresa'    =>$i_data['empresa'], 
            'descripcion'=>$i_data['forma_pago'], 
            'codigo_sri' =>$i_data['codigo_sri']
        );
      
        $w_datos_forma_pago=insertUpdateFormaPagoEmpresa($datos_forma_pago);
        
        if($w_datos_forma_pago['error']<>'0'){
            return $w_datos_forma_pago;
        }
        $w_datos_factura=array(
            'empresa'=>$i_data['empresa'],
            'usuario'=>$i_data['usuario'],
            'cliente'=>$w_datos_cliente['datos']['id'],
            'servicio'=>$i_data['servicio'],
            'valor'=>$i_data['valor'],
            'forma_pago'=>$i_data['forma_pago']
        );
        
        $w_ejecutarFactura=ejecutarFacturaDelivery($w_datos_factura);
        if($w_ejecutarFactura['error']<>'0'){
            return $w_ejecutarFactura;
        }
        $w_autorizarFactura=autorizar_factura($w_ejecutarFactura['datos'],'S');
        $o_respuesta=$w_autorizarFactura;
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosFacturaDelivery($i_data){
    if (!isset($i_data['convenio'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo convenio');
        return $o_respuesta;
    }
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['usuario'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo usuario');
        return $o_respuesta;
    }
    if (!isset($i_data['servicio'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo servicio');
        return $o_respuesta;
    }
    if (!isset($i_data['valor'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo valor');
        return $o_respuesta;
    }
    if (!isset($i_data['cliente'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo cliente');
        return $o_respuesta;
    }
    if (!isset($i_data['forma_pago'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo pagos');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo_sri'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_sri');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function validarDatosDeliveryPedido($i_data){
    if (!isset($i_data['convenio'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo convenio');
        return $o_respuesta;
    }
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['usuario'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo usuario');
        return $o_respuesta;
    }
    if (!isset($i_data['servicio'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo servicio');
        return $o_respuesta;
    }
    if (!isset($i_data['code_order'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo code_order');
        return $o_respuesta;
    }
    if (!isset($i_data['code_sale_invoice'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo code_sale_invoice');
        return $o_respuesta;
    }

    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function registrarFacturaDeliveryRunRumm($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Factura Delivery RunRumm ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validarDatos=validarDatosDeliveryPedido($i_data);
        if($w_validarDatos['error']<>'0'){
            return $w_validarDatos;
        }

        $w_datos_convenio=verificaConvenio($i_data['convenio']);
        if ($w_datos_convenio['error']<>0){
            return $w_datos_convenio;
        }
        $w_datos_pedido=seleccionarDatosPedido($i_data);
        if($w_datos_pedido['error']<>'0'){
            return $w_datos_pedido;
        }
        $datos_forma_pago=array(
            'empresa'    =>$i_data['empresa'], 
            'descripcion'=>$w_datos_pedido['datos']['forma_pago'], 
            'codigo_sri' =>$w_datos_pedido['datos']['codigo_sri']
        );
        $w_datos_forma_pago=insertUpdateFormaPagoEmpresa($datos_forma_pago);
        if($w_datos_forma_pago['error']<>'0'){
            return $w_datos_forma_pago;
        }
        
        $w_datos_factura=array(
            'empresa'   =>$i_data['empresa'],
            'usuario'   =>$i_data['usuario'],
            'servicio'  =>$i_data['servicio'],
            'valor'     =>$i_data['valor'],
            'cliente'   =>$w_datos_pedido['datos']['cliente'],
            'forma_pago'=>$w_datos_pedido['datos']['forma_pago'],
            'lote'      =>$w_datos_pedido['datos']['lote'],
            'transaccion'=>$w_datos_pedido['datos']['transaccion']

        );
        
        $w_ejecutarFactura=ejecutarFacturaDelivery($w_datos_factura);
        if($w_ejecutarFactura['error']<>'0'){
            return $w_ejecutarFactura;
        }
        $w_autorizarFactura=autorizar_factura($w_ejecutarFactura['datos'],'S');
        $o_respuesta=$w_ejecutarFactura;
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

?>