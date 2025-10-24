<?php
include_once ('src/funciones/igtech.funciones_categoria_productos.php');
/*Funciones Productos Ventas */
function listaProductosVenta($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Productos Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT pro_empresa,
                                pro_codigo,
                                pro_codigo_aux,
                                pro_descripcion,
                                pro_grupo_compras,
                                pro_grupo_ventas,
                                pro_categoria_producto,
                                pro_familia_producto,
                                ROUND(pro_precio *(1 + iva_porcentaje/100),2) AS pro_precio,
                                pro_descuento,
                                pro_base_ice,
                                pro_por_ice,
                                iva_porcentaje AS pro_por_iva,
                                pro_base_irbpnr, 
                                pro_por_irbpnr,
                                pro_estado,
                                pro_cantidad_inventario,
                                pro_costo_promedio,
                                pro_total_inventario,
                                pro_imagen,
                                pro_unidad,
                                pro_compra,
                                pro_venta,
                                pro_stock                            
                            FROM del_producto
                            inner join sri_tarifa_iva on pro_por_iva=iva_codigo
                            WHERE pro_empresa='".$i_empresa."'
                            AND pro_familia_producto in (1,3)
                            order by pro_codigo;";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['pro_empresa'],
                    'codigo'=>$row['pro_codigo'],
                    'codigo_aux'=>$row['pro_codigo_aux'],
                    'descripcion'=>$row['pro_descripcion'],
                    'grupo_compras'=>$row['pro_grupo_compras'],
                    'grupo_ventas'=>$row['pro_grupo_ventas'],
                    'categoria_producto'=>$row['pro_categoria_producto'],
                    'familia_producto'=>$row['pro_familia_producto'],
                    'precio'=>$row['pro_precio'],
                    'descuento'=>$row['pro_descuento'],
                    'base_ice'=>$row['pro_base_ice'],
                    'por_ice'=>$row['pro_por_ice'],
                    'por_iva'=>$row['pro_por_iva'],
                    'base_irbpnr'=>$row['pro_base_irbpnr'],
                    'por_irbpnr'=>$row['pro_por_irbpnr'],
                    'estado'=>$row['pro_estado'],
                    'cantidad_inventario'=>$row['pro_cantidad_inventario'],
                    'costo_promedio'=>$row['pro_costo_promedio'],
                    'total_inventario'=>$row['pro_total_inventario'],
                    'imagen'=>$row['pro_imagen'],
                    'unidad'=>$row['pro_unidad'],
                    'compra'=>$row['pro_compra'],
                    'venta'=>$row['pro_venta'],
                    'stock'=>$row['pro_stock'],
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

function stockProductosVenta($i_empresa,$i_establecimiento,$i_producto){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Productos Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT pro_empresa,
                                pro_codigo,
                                pro_codigo_aux,
                                pro_descripcion,
                                pro_cantidad_inventario                            
                            FROM del_producto
                            WHERE pro_empresa='".$i_empresa."'
                            AND pro_codigo='".$i_producto."'
                            order by pro_codigo;";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>$row['pro_empresa'],
                    'codigo'=>$row['pro_codigo'],
                    'codigo_aux'=>$row['pro_codigo_aux'],
                    'descripcion'=>$row['pro_descripcion'],
                    'cantidad_inventario'=>$row['pro_cantidad_inventario'],
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

function seleccionarProducto($i_empresa,$i_codigo){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Producto ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Codigo: '.$i_codigo);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT empresa,
                                codigo,
                                codigo_auxiliar,
                                nombre_producto,
                                categoria, 
                                precio_venta,
                                porcentaje_iva,
                                familia                            
                            FROM v_ws_productos
                            WHERE empresa='".$i_empresa."'
                            AND codigo='".$i_codigo."';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $w_respuesta = array(
                    'empresa'           =>	$row->empresa,
                    'codigo'            =>	$row->codigo,
                    'codigo_auxiliar'   =>	$row->codigo_auxiliar,
                    'nombre_producto'   =>	$row->nombre_producto,
                    'categoria'         =>  $row->categoria, 
                    'precio_venta'      =>	$row->precio_venta,
                    'porcentaje_iva'    =>	$row->porcentaje_iva,
                    'familia'           =>	$row->familia,
                );
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);   
            }else{
                $o_respuesta=array('error'=>'9997','mensaje'=>'No existe el producto '.$i_codigo);
            }
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }    
        $close = pg_close($ws_conexion) ;
    }catch(Throwable $e){
        $o_respuesta=array( 'error'=>'9998','mensaje'=>$e->getMessage());
    }    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarProductoVenta($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Producto Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_categoria=seleccionarCategoria($i_data['empresa'], $i_data['categoria']);
        if ($w_categoria['error']<>'0'){
            return $w_categoria;
        }
        $ws_conexion=ws_coneccion_bdd();
        
        $insert_sql="INSERT INTO del_producto(
                            pro_codigo,
                            pro_codigo_aux,
                            pro_empresa,
                            pro_descripcion,
                            pro_iva,
                            pro_por_iva,
                            pro_ice,
                            pro_por_ice,
                            pro_irbpnr,
                            pro_por_irbpnr,
                            pro_precio,
                            pro_descuento,
                            pro_estado,
                            pro_familia_producto,
                            pro_grupo_compras,
                            pro_grupo_ventas,
                            pro_categoria_producto,
                            pro_cantidad_inventario,
                            pro_costo_promedio,
                            pro_total_inventario) 
                        VALUES	(
                            '".$i_data['codigo']."',
                            '".$i_data['codigo_auxiliar']."',
                            '".$i_data['empresa']."',
                            '".$i_data['nombre_producto']."',
                            '2',
                            (SELECT min(iva_codigo) FROM sri_tarifa_iva WHERE iva_porcentaje=".$i_data['porcentaje_iva']."),
                            '3',
                            0,
                            '5',
                            0,
                            '".$i_data['precio_venta']."',
                            0,
                            '".$i_data['estado']."',
                            1,
                            17,
                            1,
                            '".$i_data['categoria']."',
                            0,
                            '".$i_data['costo_promedio']."',
                            0
                            );";
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = pg_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
                        //registrar movimiento en kardex
            $insert_sql = "INSERT INTO del_kardex(
                kar_empresa,
                kar_fecha,
                kar_producto,
                kar_tipo_movimiento,
                kar_cant_inicial,
                kar_costo_inicial,
                kar_saldo_inicial,
                kar_cantidad_ingreso,
                kar_costo_ingreso,
                kar_total_ingreso,
                kar_cantidad_salida,
                kar_costo_salida,
                kar_total_salida,
                kar_cantidad_existencia,
                kar_costo_existencia,
                kar_total_existencia,
                kar_pvp,
                kar_tipo_documento,
                kar_documento) 
            VALUES(
                    '".$i_data['empresa']."',
                    getdate(),
                    '".$i_data['codigo']."',
                    'KI',
                    0,
                    0,
                    0,
                    0,
                    '".$i_data['costo_promedio']."',
                    0,
                    0,
                    0,
                    0,
                    0,
                    '".$i_data['costo_promedio']."',
                    0,
                    ".$i_data['precio_venta'].",
                    '',
                    ''
                );";
            $Log->EscribirLog(' Consulta: '.$insert_sql);
            if (!$result = pg_query($ws_conexion, $insert_sql)){
                $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
            }else{
                $o_respuesta=array('error'=>'0','mensaje'=>'Producto creado exitosamente','datos' => $i_data);        
            }
        }    
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage(),'datos'=>$i_data);
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarProductoVenta($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Producto Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_categoria=seleccionarCategoria($i_data['empresa'], $i_data['categoria']);
        if ($w_categoria['error']<>'0'){
            return $w_categoria;
        }
        $ws_conexion=ws_coneccion_bdd();
        
        $update_sql="UPDATE del_producto 
                    SET	
                    pro_codigo_aux          ='".$i_data['codigo_auxiliar']."',
                    pro_descripcion         ='".$i_data['nombre_producto']."',
                    pro_iva                 ='2',
                    pro_por_iva             = (SELECT min(iva_codigo) FROM sri_tarifa_iva WHERE iva_porcentaje=".$i_data['porcentaje_iva']."),
                    pro_ice                 ='3',
                    pro_irbpnr              ='5',
                    pro_precio              = ".$i_data['precio_venta'].",
                    pro_categoria_producto  ='".$i_data['categoria']."',
                    pro_descuento           =0,
                    pro_estado              ='".$i_data['estado']."'
                WHERE
                    pro_codigo      ='".$i_data['codigo']."'
                    and pro_empresa ='".$i_data['empresa']."'";
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = pg_query($ws_conexion, $update_sql)){
            $o_respuesta=array( 'error'=>'9997','mensaje'=>pg_last_error($ws_conexion)); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Producto actualizado exitosamente','datos' => $i_data);        
        }
        $close = pg_close($ws_conexion);
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage(),'datos'=>$i_data);
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function insertUpdateProductoVenta($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Producto Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosProductoVenta($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*)
                    FROM del_producto
                    WHERE pro_empresa   ='".$i_data['empresa']."'
                    and pro_codigo      ='".$i_data['codigo']."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if ($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                if($row->count==0){
                    $o_respuesta=registrarProductoVenta($i_data);    
                }else{
                    $o_respuesta=actualizarProductoVenta($i_data);    
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

function validarDatosProductoVenta($i_data){
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo');
        return $o_respuesta;
    }
    if (!isset($i_data['codigo_auxiliar'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo codigo_auxiliar');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre_producto'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre_producto');
        return $o_respuesta;
    }
    if (!isset($i_data['categoria'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo categoria');
        return $o_respuesta;
    }
    if (!isset($i_data['precio_venta'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo precio_venta');
        return $o_respuesta;
    }
    if (!isset($i_data['porcentaje_iva'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo porcentaje_iva');
        return $o_respuesta;
    }
    if (!isset($i_data['costo_promedio'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo costo_promedio');
        return $o_respuesta;
    }
    if (!isset($i_data['estado'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

/* funciones productos compras */
function listaProductosCompra($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Productos Compras ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=	"SELECT empresa,
                                codigo,
                                codigo_auxiliar,
                                nombre_producto,
                                categoria, 
                                precio_venta,
                                porcentaje_iva,
                                familia                            
                            FROM v_ws_productos
                            WHERE empresa='".$i_empresa."'
                            AND codigo_familia in (2,3)
                            order by codigo;";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'empresa'=>					$row['empresa'],
                    'codigo'=>					$row['codigo'],
                    'codigo_auxiliar'=>			$row['codigo_auxiliar'],
                    'nombre_producto'=>			$row['nombre_producto'],
                    'categoria'=>				$row['categoria'], 
                    'precio_venta'=>			$row['precio_venta'],
                    'porcentaje_iva'=>			$row['porcentaje_iva'],
                    'familia'=>					$row['familia'],
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



?>