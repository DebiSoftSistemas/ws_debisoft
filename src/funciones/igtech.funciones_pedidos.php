<?php
include_once ('src/funciones/igtech.funciones_generales.php');
include_once ('src/funciones/igtech.funciones_cliente.php');
include_once ('src/funciones/igtech.funciones_forma_pago.php');
include_once ('src/funciones/igtech.funciones_transportista.php');
include_once ('src/funciones/igtech.funciones_correo.php');
include_once ('src/funciones/igtech.autorizar_documentos.php');
include_once ('src/funciones/igtech.funciones_canton.php');

function validarDatosNuevoPedido($i_data){
    if (!isset($i_data['convenio'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo convenio');
        return $o_respuesta;
    }
    

    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    
    if (!isset($i_data['establecimiento'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo establecimiento');
        return $o_respuesta;
    }
    if (!isset($i_data['estado'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado');
        return $o_respuesta;
    }
    
    if (!isset($i_data['tipo_entrega'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo_entrega');
        return $o_respuesta;
    }

    if (!isset($i_data['code_order'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo code_order');
        return $o_respuesta;
    }

    if($i_data['tipo_entrega']=='EDM001'){
        if (!isset($i_data['persona_recibe']) or $i_data['persona_recibe']==''){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo persona_recibe');
            return $o_respuesta;
        }
        if (!isset($i_data['identificacion']) or $i_data['identificacion']==''){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo identificacion');
            return $o_respuesta;
        }
        if (!isset($i_data['ciudad']) or $i_data['ciudad']==''){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo ciudad');
            return $o_respuesta;
        }
        if (!isset($i_data['direccion']) or $i_data['direccion']==''){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo direccion');
            return $o_respuesta;
        }
        if (!isset($i_data['telefono'])){
            $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo telefono');
            return $o_respuesta;
        }
    }    
       
    if (!isset($i_data['cliente'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo cliente');
        return $o_respuesta;
    }
    
    if (!isset($i_data['productos'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo productos');
        return $o_respuesta;
    }
    if (!isset($i_data['pagos'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo productos');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;

}

function registrarNuevoPedido($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Nuevo Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        //vslidamos que esten todos los datos
        $w_validar=validarDatosNuevoPedido($i_data);
        if($w_validar['error']<>'0'){
            return $w_validar;
        }

        //verificamos el convenio
        $w_datos_convenio=verificaConvenio($i_data['convenio']);
        if ($w_datos_convenio['error']<>0){
            return $w_datos_convenio;
        }    
        //verificar empresa
        $w_datos_empresa=seleccionarEmpresa($i_data['empresa']);
        if ($w_datos_empresa['error']<>0){
            return $w_datos_empresa;
        }
        //verificar establecimiento
        
        $w_datos_establecimiento=seleccionarEstablecimiento($i_data['empresa'],$i_data['establecimiento']);
        if($w_datos_establecimiento['error']<>0){
            return $w_datos_establecimiento;
        }
        $w_data_cliente=array(
            'empresa'=>$i_data['empresa'],
            'nombre'=>$i_data['cliente']['nombre'],
            'tipo_identificacion'=>$i_data['cliente']['tipo_identificacion'],
            'identificacion'=>$i_data['cliente']['identificacion'],
            'ciudad'=>$i_data['cliente']['ciudad'],
            'direccion'=>$i_data['cliente']['direccion'],
            'telefono'=>$i_data['cliente']['telefono'],
            'celular'=>$i_data['cliente']['celular'],
            'email'=>$i_data['cliente']['email'],
        );
        //verificamos el cliente
        $w_datos_cliente=inserUpdateCliente($w_data_cliente);
        if ($w_datos_cliente['error']<>0){
            return $w_datos_cliente;
        }
        $w_datos_cliente=seleccionarClientes($i_data['empresa'],$i_data['cliente']['identificacion']);
        if ($w_datos_cliente['error']<>0){
            return $w_datos_cliente;
        }
        //verificamos los productos
        $productos=$i_data['productos'];
        foreach ($productos as $producto) {
            $w_datos_productos=seleccionarProducto($i_data['empresa'],$producto['codigo']);
            if($w_datos_productos['error']<>'0'){
                return $w_datos_productos;
            }
        }

        $w_datos_ciudad=seleccionarCantonXNombre($i_data['ciudad']);
        if($w_datos_ciudad['error']<>'0'){
            return $w_datos_ciudad;
        }
        //creamos el pedido
        //obtenemos el secuencial del pedido
        $w_datos_secuencial=generaSecuencial('SECPEDCON');
        if($w_datos_secuencial['error']<>'0'){
            return $w_datos_secuencial;
        }
        if(!isset($i_data['telefono'])){
            $telefono='';
        }else{
            $telefono=$i_data['telefono'];
        }
        $w_pedido=array(
            'pedido'=>$w_datos_secuencial['datos'],
            'convenio'=>$w_datos_convenio['datos'],
            'empresa'=>$i_data['empresa'],
            'establecimiento'=>$w_datos_establecimiento['datos']['id'],
            'estado'=>$i_data['estado'],
            'tipo_entrega'=>$i_data['tipo_entrega'],
            'code_order'=>$i_data['code_order'],
            'cliente'=>$w_datos_cliente['datos']['id'],
            
        );
        
        $w_respuesta=registrarPedido($w_pedido);
        if($w_respuesta['error']<>'0'){
            return $w_respuesta;
        }

        $w_envio=array(
            'persona_recibe'=>$i_data['persona_recibe'],
            'identificacion'=>$i_data['identificacion'],
            'pais'=>$w_datos_ciudad['datos']['pais'],
            'provincia'=>$w_datos_ciudad['datos']['provincia'],
            'ciudad'=>$i_data['ciudad'],
            'direccion'=>$i_data['direccion'],
            'telefono'=>$telefono,
        );
        $w_respuesta=registrarLugarEnvio($w_datos_secuencial['datos'],$w_envio);

        if($w_respuesta['error']<>'0'){
            return $w_respuesta;
        }
        
        foreach ($productos as $producto) {
            
            $detalle=registrarDetallePedido($w_datos_secuencial['datos'],$i_data['empresa'],$producto);
            
            if($detalle['error']<>'0'){
                return $detalle;
            }
        }
        $o_respuesta=registrarPagoPedido($i_data['pagos'],$w_datos_secuencial['datos'],$i_data['empresa']);
        if($o_respuesta['error']<>'0'){
            return $o_respuesta;
        }
        
        $w_cambio_estado= array(
            'pedido'=>$w_datos_secuencial['datos'],
            'code_order'=>$i_data['code_order'],
            'estado'=>'PR001',
        );
        $o_respuesta=cambiarEstadoPedido($w_cambio_estado);
        if($o_respuesta['error']<>'0'){
            return $o_respuesta;
        }

        $w_respuesta=array(
            'id_pedido'=>$w_datos_secuencial['datos'],
            'pedido'=>$i_data,
        );
        $o_respuesta=array('error'=>'0','mensaje'=>'Pedido registrado exitosamente','datos'=>$w_respuesta);    
        $w_envio_correo=notificaPedido($w_datos_secuencial['datos']);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarPedido($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_pedido_convenio(
            prof_numero,
            prof_convenio,
            prof_empresa,
            prof_establecimiento,
            prof_secuencial,
            prof_fecha,
            prof_estado,
            prof_tipo_entrega,
            prof_code_order,
            prof_cliente,
            prof_descripcion,
            prof_subtotal_cero,
            prof_subtotal_iva,
            prof_subtotal_excento,
            prof_subtotal_no_objeto,
            prof_subtotal,
            prof_total_descuento,
            prof_valor_ice,
            prof_valor_irbpnr,
            prof_valor_iva,
            prof_propina,
            prof_total,
            prof_aplica_propina
            ) 
        VALUES(
                ".$i_data['pedido'].",
                ".$i_data['convenio'].",
               '".$i_data['empresa']."',
               '".$i_data['establecimiento']."',
                (SELECT  coalesce(max(prof_secuencial),0)+1 
                    FROM del_pedido_convenio 
                    WHERE prof_empresa='".$i_data['empresa']."' 
                    and prof_establecimiento=".$i_data['establecimiento']."),
                getdate(),
                '".$i_data['estado']."',
                '".$i_data['tipo_entrega']."',
                ".$i_data['code_order'].",
                ".$i_data['cliente'].",
                '',
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                'N'
            );";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Pedido creada exitosamente','datos'=>$i_data);   
        }
        $close = pg_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarLugarEnvio($i_pedido,$i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Lugar Envio ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Pedido: '.$i_pedido);
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));

        $ws_conexion=ws_coneccion_bdd();
        
        $insert_sql="INSERT INTO del_direccion_entrega_pedido(
                                dep_id_pedido,
                                dep_identificacion_quien_recibe,
                                dep_nombre_quien_recibie,
                                dep_pais,
                                dep_provincial,
                                dep_ciudad,
                                dep_direccion,
                                dep_telefono) 
                            VALUES(
                                    $i_pedido,
                                    '".$i_data['identificacion']."',
                                    '".$i_data['persona_recibe']."',
                                    (SELECT pai_id FROM sis_pais WHERE pai_nombre IN ('".strtoupper($i_data['pais'])."','ECUADOR')),
                                    (SELECT pro_id FROM sis_provincia WHERE pro_nombre='".strtoupper($i_data['provincia'])."'),
                                    (SELECT can_id FROM sis_canton WHERE can_nombre='".strtoupper($i_data['ciudad'])."'),
                                    '".$i_data['direccion']."',
                                    '".$i_data['telefono']."'
            )";
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Lugar Envio creado exitosamente','datos'=>$i_data);
        }
                
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarDetallePedido($i_pedido,$i_empresa,$i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Detalle Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Pedido: '.$i_pedido);
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));

        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_detalle_pedido_convenio(
                                    dp_pedido_convenio,
                                    dp_empresa,
                                    dp_producto,
                                    dp_descripcion,
                                    dp_cantidad,
                                    dp_total,
                                    dp_descuento,
                                    dp_estado) 
                                VALUES(
                                        ".$i_pedido.",
                                        '".$i_empresa."',
                                        '".$i_data['codigo']."',
                                        '".$i_data['descripcion_adicional']."',
                                        ".$i_data['cantidad'].",
                                        ".$i_data['precio_unitario']*$i_data['cantidad'].",
                                        0,
                                        'R'
                                    );";    
        $Log->EscribirLog(' Consulta: '.$insert_sql);                            
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            $exec_sql="SELECT sp_del_actualiza_pedido(".$i_pedido.");";
            $result = pg_query($ws_conexion, $exec_sql);
            $o_respuesta=array('error'=>'0','mensaje'=>'Detalle creada exitosamente','datos'=>$i_data);        
        }
        $close = pg_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;

}

function registrarPagoPedido($i_data,$i_pedido,$i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Pago Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $Log->EscribirLog(' Pedido: '.var_export($i_pedido,true));
        $Log->EscribirLog(' Empresa: '.var_export($i_empresa,true));

        $w_validacion=validarDatosPago($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }       
        
        $w_secuencial=$i_pedido;
        $w_ruc_empresa=$i_empresa;
        $datos_forma_pago=array(
            'empresa'    =>$w_ruc_empresa, 
            'descripcion'=>$i_data['forma_pago'], 
            'codigo_sri' =>$i_data['codigo_sri']
        );

        $w_datos_forma_pago=insertUpdateFormaPagoEmpresa($datos_forma_pago);
        if($w_datos_forma_pago['error']<>'0'){
            return $w_datos_forma_pago;
        }

        $w_datos_valor_pago=seleccionarValorPagoPedido($w_secuencial);
        if($w_datos_valor_pago['error']<>'0'){
            return $w_datos_valor_pago;
        }
        if(round($i_data['valor'],2)<>round($w_datos_valor_pago['datos']['valor'],2)){
           
            return array('error'=>'9997','mensaje'=>"Valor Pago(".$i_data['valor'].")  diferente al valor del pedido(".$w_datos_valor_pago['datos']['valor'].")");
        }
        $w_datos_forma_pago=seleccionarFormasPagoEmpresa($w_ruc_empresa,$i_data['forma_pago']);
        $w_pago=array(
            'pedido'        =>$w_secuencial,
            'forma_pago'    =>$w_datos_forma_pago['datos']['id'],
            'valor'         =>$i_data['valor'],
            'plazo'         =>'1',
            'unidad_tiempo' =>'DIAS',
            'lote'          =>$i_data['lote'],
            'transaccion'   =>$i_data['transaccion'],
        );

        $w_reg_pago=registrarFormaPagoPedido($w_pago);
            return $w_reg_pago;
        

    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
        return $o_respuesta;
    }
    
}

function seleccionarIdPedido($code_order,$cod_sale_invoice){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar IDPedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' CodeOrder: '.$code_order);
        $Log->EscribirLog(' CodeSaleInvoice: '.$cod_sale_invoice);

        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT prof_numero,
                                prof_empresa 
                         FROM del_pedido_convenio
                         where prof_code_order='".$code_order."'
                         and prof_code_sale_invoice=".$cod_sale_invoice.";";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(
                    'id_pedido'=>$row->prof_numero,
                    'empresa'=>$row->prof_empresa
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No se encuentra el pedido');
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

function seleccionarDatosPedido($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Datos Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true ));
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT prof_cliente as cliente,
                        b.fp_descripcion as forma_pago,
                        b.fp_sri as codigo_sri,
                        a.fp_lote as lote,
                        a.fp_transaccion as transaccion
                        FROM del_pedido_convenio 
                        inner join del_forma_pago_pedido_convenio a on prof_numero=a.fp_pedido_convenio
                        inner join del_forma_pago b on a.fp_forma_pago=b.fp_id
                        WHERE
                        prof_code_order=".$i_data['code_order']."
                        and prof_code_sale_invoice=".$i_data['code_sale_invoice'];
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'cliente'=>     $row->cliente,
                    'forma_pago'=>  $row->forma_pago,
                    'codigo_sri'=>  $row->codigo_sri,
                    'lote'=>        $row->lote,
                    'transaccion'=> $row->transaccion
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }else{
                $o_respuesta=array('error'=>'9995','mensaje'=>'No existe el pedido');
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

function registrarFormaPagoPedido($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Forma Pago Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO del_forma_pago_pedido_convenio(
            fp_pedido_convenio,
            fp_forma_pago,
            fp_valor,
            fp_plazo,
            fp_unidad_tiempo,
            fp_lote,
            fp_transaccion) 
        VALUES(
             ".$i_data['pedido'].",
             ".$i_data['forma_pago'].",
             ".$i_data['valor'].",
             ".$i_data['plazo'].",
            '".$i_data['unidad_tiempo']."',
            '".$i_data['lote']."',
            '".$i_data['transaccion']."'
        );";    
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }else{
            
            $o_respuesta=array('error'=>'0','mensaje'=>'Registro de Forma de pago Exitoso','datos'=>$i_data);      
        }
        $close = pg_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function cambiarEstadoPedido($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Estado Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosEstadoPedido($i_data);
        if ($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) 
                    FROM del_pedido_convenio 
                    WHERE prof_code_order       = ".$i_data['code_order']."
                    AND prof_numero  = ".$i_data['pedido'].";"; 
        $Log->EscribirLog(' Consulta: '.$select_sql);            
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 );
                if($row->count>0){ 
                    $update_sql="UPDATE del_pedido_convenio 
                                SET	 
                                    prof_estado='".$i_data['estado']."' 
                                WHERE prof_code_order       = ".$i_data['code_order']."
                                AND prof_numero  = ".$i_data['pedido'].";"; 
                    $Log->EscribirLog(' Consulta: '.$update_sql);
                    if (!$result = pg_query($ws_conexion, $update_sql)){
                        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                    }else{
                        $o_respuesta=array('error'=>'0','mensaje'=>'Estado del pedido actualizada exitosamente','datos'=>$i_data);       
                    }
                }else{
                    $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el pedido');
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

function validarDatosEstadoPedido($i_data){
    if (!isset($i_data['code_order'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo code_order');
        return $o_respuesta;
    }
    if (!isset($i_data['pedido'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo pedido');
        return $o_respuesta;
    }
    
    if (!isset($i_data['estado'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function validarDatosPago($i_data){
    if (!isset($i_data['forma_pago'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo forma_pago');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo_sri'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_sri');
        return $o_respuesta;
    }
    if (!isset($i_data['valor'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo valor');
        return $o_respuesta;
    }
    if (!isset($i_data['lote'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo lote');
        return $o_respuesta;
    }
    if (!isset($i_data['transaccion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo transaccion');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function registrarTransportistaPedido($i_data){
    try{$Log=new IgtechLog ();
    $Log->Abrir();
    $Log->EscribirLog(' Registrar Transportista Pedido ');
    $Log->EscribirLog(' DATOS DE ENTRADA');
    $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosTransportistaPedido($i_data);
        
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }  
        
        $w_datos_pedido= seleccionarIdPedido($i_data['code_order'],$i_data['code_sale_invoice']);
        if ($w_datos_pedido['error']<>'0'){
            return $w_datos_pedido;
        } 
       
        $w_datos_transportista=array(
            'empresa'               =>$w_datos_pedido['datos']['empresa'],
            'nombre'                =>$i_data['transportista']['nombre'],
            'tipo_identificacion'   =>$i_data['transportista']['tipo_identificacion'],
            'identificacion'        =>$i_data['transportista']['identificacion'],
            'pais'                  =>$i_data['transportista']['pais'],
            'provincia'             =>$i_data['transportista']['provincia'],
            'ciudad'                =>$i_data['transportista']['ciudad'],
            'direccion'             =>$i_data['transportista']['direccion'],
            'telefono'              =>$i_data['transportista']['telefono'],
            'celular'               =>$i_data['transportista']['celular'],
            'email'                 =>$i_data['transportista']['email'],
            'placa'                 =>$i_data['transportista']['placa']
        );
        $w_valida_transportista=insertUpdateTransportista($w_datos_transportista);
       
        if($w_valida_transportista['error']<>'0'){
            return $w_valida_transportista;
        }
        $w_transportista=seleccionarTransportistas($w_datos_transportista['empresa'],$i_data['transportista']['identificacion']);
        if($w_transportista['error']<>'0'){
            return $w_transportista;
        }
        
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE del_pedido_convenio
                        SET
                        prof_transportista=".$w_transportista['datos']['id'].",
                        prof_estado='".$i_data['estado']."'
                        WHERE prof_code_order = ".$i_data['code_order']."
                        AND prof_code_sale_invoice = ".$i_data['code_sale_invoice'];
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
            $close = pg_close($ws_conexion);
        }else{
            $close = pg_close($ws_conexion);
            $o_respuesta=array('error'=>'0','mensaje'=>'Pedido Actualizado exitosamente','datos'=>$i_data);
            $w_guia=generarGuia($w_datos_pedido['datos']['id_pedido']);
            if($w_guia['error']=='0'){
                $autorizar=autorizar_guia($w_guia['datos']['guia'],'S');
            }
        }
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosTransportistaPedido($i_data){
    if (!isset($i_data['code_order'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo code_order');
        return $o_respuesta;
    }
    if (!isset($i_data['code_sale_invoice'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo code_sale_invoice');
        return $o_respuesta;
    }
    
    if (!isset($i_data['estado'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado');
        return $o_respuesta;
    }
    if (!isset($i_data['transportista'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo transportista');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function generarGuia($i_pedido){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Generar Guia Remision ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Pedido: '.$i_pedido);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT * from sp_guia_pedido(".$i_pedido.") 
                            as (respuesta int, 
                                mensaje varchar,
                                guia int);";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'pedido'=>$i_pedido,
                    'respuesta'=>$row->respuesta,
                    'mensaje'=>$row->mensaje,
                    'guia'=>$row->guia,
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

function seleccionarValorPagoPedido($i_num_pedido){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' SELECCIONAR ValorPagoPedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog('IdPedido: ' . $i_num_pedido );
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        prof_numero,
                        prof_total 
                    FROM del_pedido_convenio
                    WHERE prof_numero=".$i_num_pedido;
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'pedido'=>$row->prof_numero,
                    'valor'=>$row->prof_total
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

function listaPedidos($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        prof_numero,
                        prof_empresa,
                        prof_establecimiento,
                        prof_convenio,
                        prof_code_order,
                        prof_cliente,
                        prof_identificacion_recibe,
                        prof_nombre_recibe,
                        prof_telefono_recibe,
                        prof_canton_recibe,
                        prof_direccion_recibe, 
                        prof_descripcion,
                        prof_delivery_propio,
                        prof_fecha,
                        prof_secuencial,
                        prof_subtotal,
                        prof_total_descuento,
                        prof_valor_ice,
                        prof_subtotal_cero,
                        prof_subtotal_excento,
                        prof_subtotal_iva,
                        prof_subtotal_no_objeto,
                        prof_valor_iva,
                        prof_valor_irbpnr,
                        prof_total,
                        prof_estado,
                        prof_tipo_entrega,
                        prof_transportista,
                        prof_usuario,
                        prof_factura,
                        prof_guia_remision
                    FROM del_pedido_convenio
                    WHERE prof_empresa='".$i_empresa."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'id'=>$row['prof_numero'],
                    'empresa'=>$row['prof_empresa'],
                    'establecimiento'=>$row['prof_establecimiento'],
                    'convenio'=>$row['prof_convenio'],
                    'code_order'=>$row['prof_code_order'],
                    'cliente'=>$row['prof_cliente'],
                    'identificacion_recibe'=>$row['prof_identificacion_recibe'],
                    'nombre_recibe'=>$row['prof_nombre_recibe'],
                    'telefono_recibe'=>$row['prof_telefono_recibe'],
                    'canton_recibe'=>$row['prof_canton_recibe'],
                    'direccion_recibe'=>$row['prof_direccion_recibe'],
                    'descripcion'=>$row['prof_descripcion'],
                    'delivery_propio'=>$row['prof_delivery_propio'],
                    'fecha'=>$row['prof_fecha'],
                    'secuencial'=>$row['prof_secuencial'],
                    'subtotal'=>$row['prof_subtotal'],
                    'total_descuento'=>$row['prof_total_descuento'],
                    'valor_ice'=>$row['prof_valor_ice'],
                    'subtotal_cero'=>$row['prof_subtotal_cero'],
                    'subtotal_excento'=>$row['prof_subtotal_excento'],
                    'subtotal_iva'=>$row['prof_subtotal_iva'],
                    'subtotal_no_objeto'=>$row['prof_subtotal_no_objeto'],
                    'valor_iva'=>$row['prof_valor_iva'],
                    'valor_irbpnr'=>$row['prof_valor_irbpnr'],
                    'total'=>$row['prof_total'],
                    'estado'=>$row['prof_estado'],
                    'tipo_entrega'=>$row['prof_tipo_entrega'],
                    'transportista'=>$row['prof_transportista'],
                    'usuario'=>$row['prof_usuario'],
                    'factura'=>$row['prof_factura'],
                    'guia_remision'=>$row['prof_guia_remision'],
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

function seleccionarPedido($i_pedido){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' SELECCIONAR Pedido ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            prof_numero,
                            prof_empresa,
                            prof_establecimiento,
                            prof_convenio,
                            prof_code_order,
                            prof_cliente,
                            prof_identificacion_recibe,
                            prof_nombre_recibe,
                            prof_telefono_recibe,
                            prof_canton_recibe,
                            prof_direccion_recibe, 
                            prof_descripcion,
                            prof_delivery_propio,
                            prof_fecha,
                            prof_secuencial,
                            prof_subtotal,
                            prof_total_descuento,
                            prof_valor_ice,
                            prof_subtotal_cero,
                            prof_subtotal_excento,
                            prof_subtotal_iva,
                            prof_subtotal_no_objeto,
                            prof_valor_iva,
                            prof_valor_irbpnr,
                            prof_total,
                            prof_estado,
                            prof_tipo_entrega,
                            prof_transportista,
                            prof_usuario,
                            prof_factura,
                            prof_guia_remision
                        FROM del_pedido_convenio
                        WHERE prof_numero=".$i_pedido;
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'id'=>$row->prof_numero,
                    'empresa'=>$row->prof_empresa,
                    'establecimiento'=>$row->prof_establecimiento,
                    'convenio'=>$row->prof_convenio,
                    'code_order'=>$row->prof_code_order,
                    'cliente'=>$row->prof_cliente,
                    'identificacion_recibe'=>$row->prof_identificacion_recibe,
                    'nombre_recibe'=>$row->prof_nombre_recibe,
                    'telefono_recibe'=>$row->prof_telefono_recibe,
                    'canton_recibe'=>$row->prof_canton_recibe,
                    'direccion_recibe'=>$row->prof_direccion_recibe,
                    'descripcion'=>$row->prof_descripcion,
                    'delivery_propio'=>$row->prof_delivery_propio,
                    'fecha'=>$row->prof_fecha,
                    'secuencial'=>$row->prof_secuencial,
                    'subtotal'=>$row->prof_subtotal,
                    'total_descuento'=>$row->prof_total_descuento,
                    'valor_ice'=>$row->prof_valor_ice,
                    'subtotal_cero'=>$row->prof_subtotal_cero,
                    'subtotal_excento'=>$row->prof_subtotal_excento,
                    'subtotal_iva'=>$row->prof_subtotal_iva,
                    'subtotal_no_objeto'=>$row->prof_subtotal_no_objeto,
                    'valor_iva'=>$row->prof_valor_iva,
                    'valor_irbpnr'=>$row->prof_valor_irbpnr,
                    'total'=>$row->prof_total,
                    'estado'=>$row->prof_estado,
                    'tipo_entrega'=>$row->prof_tipo_entrega,
                    'transportista'=>$row->prof_transportista,
                    'usuario'=>$row->prof_usuario,
                    'factura'=>$row->prof_factura,
                    'guia_remision'=>$row->prof_guia_remision,
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

function listaDetallePedido($i_pedido){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        dp_id,
                        dp_pedido_convenio,
                        dp_empresa,
                        dp_producto,
                        dp_descripcion,
                        dp_cantidad,
                        dp_precio_unitario,
                        dp_descuento,
                        dp_subtotal,
                        dp_base_ice,
                        dp_porcentaje_ice,
                        dp_valor_ice,
                        dp_base_iva,
                        dp_porcentaje_iva,
                        dp_valor_iva, 
                        dp_base_irbpnr,
                        dp_porcentaje_irbpnr,
                        dp_valor_irbpnr,
                        dp_total,
                        dp_estado
                    FROM del_detalle_pedido_convenio
                    where dp_pedido_convenio=".$i_pedido;
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'id'=>$row['dp_id'],
                    'pedido_convenio'=>$row['dp_pedido_convenio'],
                    'empresa'=>$row['dp_empresa'],
                    'producto'=>$row['dp_producto'],
                    'descripcion'=>$row['dp_descripcion'],
                    'cantidad'=>$row['dp_cantidad'],
                    'precio_unitario'=>$row['dp_precio_unitario'],
                    'descuento'=>$row['dp_descuento'],
                    'subtotal'=>$row['dp_subtotal'],
                    'base_ice'=>$row['dp_base_ice'],
                    'porcentaje_ice'=>$row['dp_porcentaje_ice'],
                    'valor_ice'=>$row['dp_valor_ice'],
                    'base_iva'=>$row['dp_base_iva'],
                    'porcentaje_iva'=>$row['dp_porcentaje_iva'],
                    'valor_iva'=>$row['dp_valor_iva'],
                    'base_irbpnr'=>$row['dp_base_irbpnr'],
                    'porcentaje_irbpnr'=>$row['dp_porcentaje_irbpnr'],
                    'valor_irbpnr'=>$row['dp_valor_irbpnr'],
                    'total'=>$row['dp_total'],
                    'estado'=>$row['dp_estado'],
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

?>