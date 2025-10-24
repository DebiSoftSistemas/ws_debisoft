<?php
include_once ('src/funciones/igtech.procesar_documentos.php');
include_once ('src/funciones/igtech.funciones_generales.php');

function seleccionarDatosFactura($fac_numero){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
	    $Log->EscribirLog(' SELECCIONAR DATOS FACTURA');
	    $Log->EscribirLog(' DATOS DE ENTRADA');
	    $Log->EscribirLog(' Factura: '.$fac_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT fac_empresa,
                            fac_establecimiento 
                     FROM del_factura 
                     WHERE fac_numero =".$fac_numero;
        $Log->EscribirLog(' Consulta: '.$select_sql);             
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'empresa'=>$row->fac_empresa,
                    'establecimiento'=>$row->fac_establecimiento
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

function seleccionarDatosEmpresaSRI($i_empresa,$i_establecimiento){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
	    $Log->EscribirLog(' SELECCIONAR DATOS EMPRESA SRI');
	    $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $Log->EscribirLog(' Establecimiento: '.$i_establecimiento);
        
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT
                            emp_ruc,
                            emp_logo,
                            emp_firma,
                            emp_clave_firma,
                            emp_razon_social,
                            emp_nombre_comercial,
                            emp_direccion_matriz,
                            emp_obligado_contabilidad,
                            emp_ambiente_sri,
                            emp_tipo_emision,
                            emp_contribuyente_especial,
                            fil_id,
                            csmtp_servidor,
                            csmtp_contrasenia,
                            csmtp_puerto_java,
                            csmtp_usuario,
                            emp_es_op_transporte,
                            emp_regimen_especial,
                            emp_agente_retencion,
                            emp_calificacion_artesanal,
                            sp_busca_parametro('DNSSERVER','https://igtech.dyndns.org')||csmtp_ruta_imagenes as ruta_imagenes
                        FROM
                            v_del_datos_empresa_sri
                        WHERE emp_ruc='".$i_empresa."'
                        AND est_id=".$i_establecimiento.";";
        $Log->EscribirLog(' Consulta: '.$select_sql);                
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'ruc'=>$row->emp_ruc,
                    'logo'=>$row->emp_logo,
                    'firma'=>$row->emp_firma,
                    'passFirma'=>$row->emp_clave_firma,
                    'razonSocial'=>$row->emp_razon_social,
                    'nombreComercial'=>$row->emp_nombre_comercial,
                    'dirMatriz'=>$row->emp_direccion_matriz,
                    'obligadoContabilidad'=>$row->emp_obligado_contabilidad,
                    'ambiente'=>$row->emp_ambiente_sri,
                    'tipoEmision'=>$row->emp_tipo_emision,
                    'contribuyenteEspecial'=>$row->emp_contribuyente_especial,
                    'filial'=>$row->fil_id,
                    'correoHost'=>$row->csmtp_servidor,
                    'correoPass'=>$row->csmtp_contrasenia,
                    'correoPort'=>$row->csmtp_puerto_java,
                    'correoRemitente'=>$row->csmtp_usuario,
                    'esOpTransporte'=>$row->emp_es_op_transporte,
                    'padronMicroempresa'=>$row->emp_regimen_especial,
                    'padronAgenteRetencion'=>$row->emp_agente_retencion,
                    'artesanoCalificado'=>$row->emp_calificacion_artesanal,
                    'rutaLogo'=>$row->ruta_imagenes,
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

function seleccionarDatosFacturaSRI($fac_numero){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
	    $Log->EscribirLog(' SELECCIONAR DATOS FACTURA');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Factura: '.$fac_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT
                            fac_numero,
                            fac_ambiente,
                            fac_tipo_comprobante,
                            fecha,
                            est_direccion,
                            est_codigo,
                            pen_serie,
                            fac_secuencial,
                            cl_tipo_identificacion,
                            cl_nombre,
                            cl_identificacion,
                            cl_direccion,
                            cl_telefono,
                            cl_email,
                            fac_subtotal,
                            fac_total_descuento,
                            fac_subtotal_iva,
                            fac_valor_iva,
                            fac_subtotal_cero,
                            fac_subtotal_no_objeto,
                            fac_subtotal_excento,
                            fac_valor_ice,
                            fac_valor_irbpnr,
                            fac_propina,
                            fac_total,
                            fac_guia_remision,
                            fac_comentario,
                            sp_busca_parametro('MONEDASRI'::character varying, 'DOLAR'::character varying) AS fac_moneda,
                            usuario,
                            usu_cedula,
                            usu_telefono,
                            usu_email,
                            usu_placa,
                            usu_tipo_documento
                        FROM
                            v_del_datos_factura_sri
                        WHERE fac_numero=".$fac_numero ;
        $Log->EscribirLog(' Consulta: '.$select_sql);                
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'numero'=>$row->fac_numero,    
                    'ambiente'=>$row->fac_ambiente,  
                    'codDoc'=>$row->fac_tipo_comprobante,  
                    'fechaEmision'=>$row->fecha, 
                    'dirEstablecimiento'=>$row->est_direccion, 
                    'establecimiento'=>$row->est_codigo,    
                    'ptoEmision'=>$row->pen_serie, 
                    'secuencial'=>$row->fac_secuencial,    
                    'tipoIdentificacionComprador'=>$row->cl_tipo_identificacion,    
                    'razonSocialComprador'=>$row->cl_nombre, 
                    'identificacionComprador'=>$row->cl_identificacion, 
                    'direccionComprador'=>$row->cl_direccion,  
                    'telefonoComprador'=>$row->cl_telefono,   
                    'email'=>$row->cl_email,  
                    'totalSinImpuestos'=>$row->fac_subtotal,  
                    'totalDescuento'=>$row->fac_total_descuento,   
                    'baseIva'=>$row->fac_subtotal_iva,  
                    'valorIva'=>$row->fac_valor_iva, 
                    'baseCero'=>$row->fac_subtotal_cero, 
                    'baseNoObjeto'=>$row->fac_subtotal_no_objeto,    
                    'baseExcento'=>$row->fac_subtotal_excento,  
                    'valorIce'=>$row->fac_valor_ice, 
                    'valorIrbpnr'=>$row->fac_valor_irbpnr,  
                    'propina'=>$row->fac_propina,   
                    'importeTotal'=>$row->fac_total, 
                    'guiaRemision'=>$row->fac_guia_remision, 
                    'comentario'=>$row->fac_comentario,    
                    'moneda'=>$row->fac_moneda,    
                    'usuario'=>$row->usuario,   
                    'cedulaUsuario'=>$row->usu_cedula,    
                    'telefonoUsuario'=>$row->usu_telefono,  
                    'emailUsuario'=>$row->usu_email, 
                    'placaUsuario'=>$row->usu_placa, 
                    'tipoDocUsuario'=>$row->usu_tipo_documento,
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

function listaDetallesFacturaSRi($fac_numero){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
	    $Log->EscribirLog(' LISTA DETALLES FACTURA SRI');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Factura: '.$fac_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT
                            df_factura,
                            df_producto,
                            pro_codigo_aux,
                            pro_descripcion,
                            df_cantidad,
                            df_precio_unitario,
                            df_descuento,
                            valor_sin_impuesto,
                            pro_iva,
                            df_porcentaje_iva,
                            iva_porcentaje,
                            df_base_iva,
                            df_valor_iva,
                            pro_ice,
                            df_porcentaje_ice,
                            df_base_ice,
                            ice_tarifa,
                            df_valor_ice,
                            pro_irbpnr,
                            df_porcentaje_irbpnr,
                            irbpnr_tarifa,
                            df_base_irbpnr,
                            df_valor_irbpnr,
                            df_descripcion 
                        FROM
                            v_del_detalle_factura_sri
                        where df_factura=".$fac_numero."
                        order by df_id";
        $Log->EscribirLog(' Consulta: '.$select_sql);                
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'factura'               =>$row['df_factura'],
                    'codigoPrincipal'       =>$row['df_producto'],
                    'codigoAuxiliar'        =>$row['pro_codigo_aux'],
                    'descripcion'           =>$row['pro_descripcion'],
                    'cantidad'              =>$row['df_cantidad'],
                    'precioUnitario'        =>$row['df_precio_unitario'],
                    'descuento'             =>$row['df_descuento'],
                    'precioTotalSinImpuesto'=>$row['valor_sin_impuesto'],
                    'codigoImpuestoIva'     =>$row['pro_iva'],
                    'codigoIva'             =>$row['df_porcentaje_iva'],
                    'porcentajeIva'         =>$row['iva_porcentaje'],
                    'baseIva'               =>$row['df_base_iva'],
                    'valoriva'              =>$row['df_valor_iva'],
                    'codigoImpuestoIce'     =>$row['pro_ice'],
                    'codigoIce'             =>$row['df_porcentaje_ice'],
                    'baseIce'               =>$row['df_base_ice'],
                    'porcentajeIce'         =>$row['ice_tarifa'],
                    'valorIce'              =>$row['df_valor_ice'],
                    'codigoImpuestoIrbpnr'  =>$row['pro_irbpnr'],
                    'codigoIrbpnr'          =>$row['df_porcentaje_irbpnr'],
                    'porcentajeIrbpnr'      =>$row['irbpnr_tarifa'],
                    'baseIrbpnr'            =>$row['df_base_irbpnr'],
                    'valorIrbpnr'           =>$row['df_valor_irbpnr'],
                    'adicional'             =>$row['df_descripcion'],    
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

function listaIceFactura($fac_numero){
    try{
        $Log=new IgtechLog ();        
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ICE FACTURA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Factura :'.$fac_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT  '3' as codigo,
                            df_porcentaje_ice,
                        sum(df_base_ice) as baseImponible,
                        sum(df_valor_ice) as valor
                    FROM del_detalle_factura 
                    WHERE df_porcentaje_ice<>'0' 
                    and df_factura=".$fac_numero."
                    group by df_porcentaje_ice";
        $Log->EscribirLog(' Consulta:'.$select_sql);            
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'codigo'=>$row['codigo'],
                    'codigoPorcentaje'=>$row['df_porcentaje_ice'],
                    'baseImponible'=>$row['baseImponible'],
                    'valor'=>$row['valor'],
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

function listaIrbpnrFactura($fac_numero){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA IRBPNR FACTURA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Factura: '.$fac_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT  '5' as codigo,
                            df_porcentaje_irbpnr,
                            sum(df_base_irbpnr) as baseImponible,
                            sum(df_valor_irbpnr) as valor 
                    FROM del_detalle_factura 
                    WHERE df_porcentaje_irbpnr<>'0' 
                    and df_factura=".$fac_numero ."
                    group by df_porcentaje_irbpnr";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'codigo'=>$row['codigo'],
                    'codigoPorcentaje'=>$row['df_porcentaje_irbpnr'],
                    'baseImponible'=>$row['baseImponible'],
                    'valor'=>$row['valor'],
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

function listaPagosFactura($fac_numero){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Pagos Factura ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Factura:'.$fac_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=    "SELECT  a.fp_id,
                                sri_forma_pago.fp_codigo as formapago,
                                a.fp_valor as valor,
                                coalesce(a.fp_plazo,0) as plazo,
                                coalesce(a.fp_unidad_tiempo,'DIAS') as unidadtiempo
                        FROM del_forma_pago_factura a 
                        inner join del_forma_pago b on a.fp_forma_pago=b.fp_id	
                        inner join sri_forma_pago on b.fp_sri=sri_forma_pago.fp_codigo
                        where a.fp_factura=".$fac_numero;
        $Log->EscribirLog(' Consulta:'.$select_sql);                
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'formaPago'=>$row['formapago'],
                    'total'=>$row['valor'],
                    'plazo'=>$row['plazo'],
                    'unidadTiempo'=>$row['unidadtiempo'],
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

function autorizar_factura($fac_numero,$i_autorizar){	
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Autorizar Factura ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Factura :'.$fac_numero);
        $w_parametros = buscarParametro ('RUTA_FIRMADOR','http://localhost:8085/MasterOffline/ProcesarComprobanteElectronico?wsdl');
        if ($w_parametros['error']=='0'){
        $ruta_firmador=$w_parametros['datos'];
        }
        $procesarComprobanteElectronico = new ProcesarComprobanteElectronico($wsdl = $ruta_firmador);
        $configApp = new \configAplicacion();
        $configCorreo = new \configCorreo();
        $factura = new factura();
        
        $w_parametros = buscarParametro ('RUTA_EMPRESA','D:/Desarrollos/');
        if ($w_parametros['error']=='0'){
        $dir=$w_parametros['datos'];
        }
        
        $w_parametros = buscarParametro ('RUTA_IREPORT','D:/Desarrollo/IReport');
        if ($w_parametros['error']=='0'){
        $dir_ireport=$w_parametros['datos'];
        }
        
        $w_datos_factura=seleccionarDatosFactura($fac_numero);
        
        if ($w_datos_factura['error']=='0'){
            $var_empresa=$w_datos_factura['datos']['empresa'];
            $var_establecimiento=$w_datos_factura['datos']['establecimiento'];
        
        }
        
        $w_datos_empresa=seleccionarDatosEmpresaSRI($var_empresa,$var_establecimiento);
        if ($w_datos_empresa['error']=='0'){
            $configApp->dirAutorizados = $dir.$w_datos_empresa['datos']['ruc']."/documentos/";
            $configApp->dirLogo =        $dir.$w_datos_empresa['datos']['ruc']."/logo/".$w_datos_empresa['datos']['logo'];
            $configApp->dirFirma =       $dir.$w_datos_empresa['datos']['ruc']."/firma/".$w_datos_empresa['datos']['firma'];
            $configApp->passFirma =      $w_datos_empresa['datos']['passFirma'];
            $configApp->dirIreport=      $dir_ireport;
            $factura->configAplicacion = $configApp;

            $configCorreo->correoAsunto = "Nueva Factura";
            $configCorreo->correoHost =      $w_datos_empresa['datos']['correoHost'];
            $configCorreo->correoPass =      $w_datos_empresa['datos']['correoPass'];
            $configCorreo->correoPort =      $w_datos_empresa['datos']['correoPort'];
            $configCorreo->correoRemitente = $w_datos_empresa['datos']['correoRemitente'];
            $configCorreo->sslHabilitado =   false;
            $configCorreo->rutaLogo=         $w_datos_empresa['datos']['rutaLogo'].'logo.png';
            $factura->configCorreo =         $configCorreo;

            $factura->ruc                       = $w_datos_empresa['datos']['ruc'];
            $factura->razonSocial               = $w_datos_empresa['datos']['razonSocial'];
            $factura->nombreComercial           = $w_datos_empresa['datos']['nombreComercial']; 
            $factura->dirMatriz                 = $w_datos_empresa['datos']['dirMatriz']; 
            $factura->obligadoContabilidad      = $w_datos_empresa['datos']['obligadoContabilidad']; 
            $factura->tipoEmision               = $w_datos_empresa['datos']['tipoEmision'];
            if ( $w_datos_empresa['datos']['contribuyenteEspecial']!=''){
                $factura->contribuyenteEspecial = $w_datos_empresa['datos']['contribuyenteEspecial'];
            }	
            $factura->padronMicroempresa    = $w_datos_empresa['datos']['padronMicroempresa'];
            $factura->padronAgenteRetencion = $w_datos_empresa['datos']['padronAgenteRetencion'];
            if ($w_datos_empresa['datos']['padronAgenteRetencion']=='S'){
                $w_parametros = buscarParametro ('NUMERORESOAR','1');
                if ($w_parametros['error']=='0'){
                    $factura->numeroResolucion=$w_parametros['datos'];
                }
            }
            $factura->artesanoCalificado    = $w_datos_empresa['datos']['artesanoCalificado'];
        }

        $w_datosFacturaSRI=seleccionarDatosFacturaSRI($fac_numero);
        if ($w_datosFacturaSRI['error']=='0'){
            $factura->ambiente =                    $w_datosFacturaSRI['datos']['ambiente'];
            $factura->codDoc =                      $w_datosFacturaSRI['datos']['codDoc'];
            $factura->fechaEmision =                $w_datosFacturaSRI['datos']['fechaEmision'];
            $factura->dirEstablecimiento =          $w_datosFacturaSRI['datos']['dirEstablecimiento'];
            $factura->establecimiento =             $w_datosFacturaSRI['datos']['establecimiento']; 
            $factura->ptoEmision =                  $w_datosFacturaSRI['datos']['ptoEmision']; 
            $factura->secuencial =                  $w_datosFacturaSRI['datos']['secuencial'];
            $factura->tipoIdentificacionComprador = $w_datosFacturaSRI['datos']['tipoIdentificacionComprador'];
            if($w_datosFacturaSRI['datos']['guiaRemision']<>''){
                $factura->guiaRemision= $w_datosFacturaSRI['datos']['guiaRemision'];
            }
            $factura->razonSocialComprador =    $w_datosFacturaSRI['datos']['razonSocialComprador']; 
            $factura->identificacionComprador = $w_datosFacturaSRI['datos']['identificacionComprador'];
            $factura->direccionComprador=       $w_datosFacturaSRI['datos']['direccionComprador'];
            $factura->totalSinImpuestos =       $w_datosFacturaSRI['datos']['totalSinImpuestos']; 
            $factura->totalDescuento =          $w_datosFacturaSRI['datos']['totalDescuento']; 
            $total_Impuestos=array();
            $i=0;
                if($w_datosFacturaSRI['datos']['baseIva']>0){
                    $totalImpuesto = new totalImpuesto();
                    $totalImpuesto->codigo =            '2'; 
                    $totalImpuesto->codigoPorcentaje =  '2'; 
                    $totalImpuesto->baseImponible =     $w_datosFacturaSRI['datos']['baseIva']; 
                    $totalImpuesto->valor =             $w_datosFacturaSRI['datos']['valorIva'];
                    $total_Impuestos[$i]=$totalImpuesto;
                    $i+=1;
                }	
                if($w_datosFacturaSRI['datos']['baseCero']>0){
                    $totalImpuesto = new totalImpuesto();
                    $totalImpuesto->codigo =            '2'; 
                    $totalImpuesto->codigoPorcentaje =  '0'; 
                    $totalImpuesto->baseImponible =     $w_datosFacturaSRI['datos']['baseCero']; 
                    $totalImpuesto->valor =             '0.00';
                    $total_Impuestos[$i]=$totalImpuesto;
                    $i+=1;
                }	
                if($w_datosFacturaSRI['datos']['baseNoObjeto']>0){
                    $totalImpuesto = new totalImpuesto();
                    $totalImpuesto->codigo =            '2'; 
                    $totalImpuesto->codigoPorcentaje =  '6'; 
                    $totalImpuesto->baseImponible =     $w_datosFacturaSRI['datos']['baseNoObjeto']; 
                    $totalImpuesto->valor = '0.00';
                    $total_Impuestos[$i]=$totalImpuesto;
                    $i+=1;
                }	
                if($w_datosFacturaSRI['datos']['baseExcento']>0){
                    $totalImpuesto = new totalImpuesto();
                    $totalImpuesto->codigo =            '2'; 
                    $totalImpuesto->codigoPorcentaje =  '7'; 
                    $totalImpuesto->baseImponible =     $w_datosFacturaSRI['datos']['baseExcento']; 
                    $totalImpuesto->valor =             '0.00';
                    $total_Impuestos[$i]=$totalImpuesto;
                    $i+=1;
                }	
                
                if($w_datosFacturaSRI['datos']['valorIce']>0){	
                    $w_datos_ice=listaIceFactura($fac_numero);
                    if($w_datos_ice['error']=='0'){
                        $w_item_ice=$w_datos_ice['datos'];
                        for ($j=0;$j<count($w_item_ice);$j++){
                            $item_ice=$w_item_ice[$j];
                            $totalImpuesto = new totalImpuesto();
                            $totalImpuesto->codigo =            $item_ice['codigo']; 
                            $totalImpuesto->codigoPorcentaje =  $item_ice['codigoPorcentaje']; 
                            $totalImpuesto->baseImponible =     $item_ice['baseImponible'];
                            $totalImpuesto->valor =             $item_ice['valor'];
                            $total_Impuestos[$i]=$totalImpuesto;
                            $i+=1; 
                        }
                    }	
                }
                if($w_datosFacturaSRI['datos']['valorIrbpnr']>0){	
                    $w_datos_irbpnr=listaIrbpnrFactura($fac_numero);
                    if($w_datos_irbpnr['error']=='0'){
                        $w_item_irbpnr=$w_datos_irbpnr['datos'];
                        for ($j=0;$j<count($w_item_irbpnr);$j++){
                            $item_irbpnr=$w_item_irbpnr[$j]; 
                            $totalImpuesto = new totalImpuesto();
                            $totalImpuesto->codigo =            $item_irbpnr['codigo']; 
                            $totalImpuesto->codigoPorcentaje =  $item_irbpnr['codigo`Porcentaje'];
                            $totalImpuesto->baseImponible =     $item_irbpnr['baseImponible']; 
                            $totalImpuesto->valor =             $item_irbpnr['valor'];
                            $total_Impuestos[$i]=$totalImpuesto;
                            $i+=1; 
                        }
                    }	
                }
            $factura->totalConImpuesto = $total_Impuestos;
            $factura->propina =         $w_datosFacturaSRI['datos']['propina']; 
            $factura->importeTotal =    $w_datosFacturaSRI['datos']['importeTotal']; 
            $factura->moneda =          $w_datosFacturaSRI['datos']['moneda'];
            //aqui van los detalles
            $w_detalleFacturaSRI=listaDetallesFacturaSRi($fac_numero) ;
            if($w_detalleFacturaSRI['error']=='0'){
                $detalles_factura = array();
                $w_productos=$w_detalleFacturaSRI['datos'];
                $w_cantidad_items=count($w_productos);
                for ($i=0;$i<$w_cantidad_items;$i++){
                    $item=$w_productos[$i];
                    $detalleFactura = new detalleFactura();
                    $detalleFactura->codigoPrincipal =          $item['codigoPrincipal'];
                    $detalleFactura->codigoAuxiliar =           $item['codigoAuxiliar']; 
                    $detalleFactura->descripcion =              $item['descripcion']; 
                    $detalleFactura->cantidad =                 $item['cantidad']; 
                    $detalleFactura->precioUnitario =           $item['precioUnitario']; 
                    $detalleFactura->descuento =                $item['descuento']; 
                    $detalleFactura->precioTotalSinImpuesto =   $item['precioTotalSinImpuesto']; 
                    if ($item['adicional'] <>""){
                        $informacion_adicional=array();
                        $detalle_adicional= new detalleAdicional();
                        $detalle_adicional->nombre =        'Adicional';
                        $detalle_adicional->valor =         $item['adicional'] ;
                        $informacion_adicional[0]=          $detalle_adicional;							 
                        $detalleFactura->detalleAdicional = $informacion_adicional;	
                    }
                    $impuestos_det=array();
                    $j=0;
                    $impuesto = new impuesto();
                    $impuesto->codigo =             $item['codigoImpuestoIva'];
                    $impuesto->codigoPorcentaje =   $item['codigoIva']; 
                    $impuesto->tarifa =             $item['porcentajeIva']; 
                    $impuesto->baseImponible =      $item['baseIva']; 
                    $impuesto->valor =              $item['valoriva'];
                    $impuestos_det[$j]=$impuesto;
                    $j+=1;
                    if($item['codigoIce']<>'0'){
                        $impuesto = new impuesto();
                        $impuesto->codigo =             $item['codigoImpuestoIce'];
                        $impuesto->codigoPorcentaje =   $item['codigoIce']; 
                        $impuesto->baseImponible =      $item['baseIce']; 
                        $impuesto->tarifa =             $item['porcentajeIce'];  
                        $impuesto->valor =              $item['valorIce'];
                        $impuestos_det[$j]=$impuesto;
                        $j+=1;
                    }
                    if($item['codigoIrbpnr']<>'0'){
                        $impuesto = new impuesto();
                        $impuesto->codigo =             $item['codigoImpuestoIrbpnr'];
                        $impuesto->codigoPorcentaje =   $item['codigoIrbpnr']; 
                        $impuesto->tarifa =             $item['porcentajeIrbpnr'];  
                        $impuesto->baseImponible =      $item['baseIrbpnr']; 
                        $impuesto->valor =          $item['valorIrbpnr'];
                        $impuestos_det[$j]=$impuesto;
                        $j+=1;
                    }
                    $detalleFactura->impuestos = $impuestos_det;
                    $detalles_factura[$i]=$detalleFactura;
                    
                }
                $factura->detalles = $detalles_factura;
            }
            $pagos = array();
            $w_pagosFactura=listaPagosFactura($fac_numero) ;
            if($w_pagosFactura['error']=='0'){
                $w_pagos=$w_pagosFactura['datos'];
                for ($i=0;$i<count($w_pagos);$i++){
                    $itemPago=$w_pagos[$i];
                    $pago = new pagos();
                    $pago->formaPago =  $itemPago['formaPago'];
                    $pago->total =      $itemPago['total'];
                    $pago->plazo =      $itemPago['plazo'];
                    $pago->unidadTiempo=$itemPago['unidadTiempo'];
                    $pagos[$i]=$pago;
                }
            }	
            $factura->pagos = $pagos;
            $camposAdicionales = array();
            $i=0;
            
            if($w_datos_empresa['datos']['esOpTransporte']=='S'){
                if( $w_datosFacturaSRI['datos']['ptoEmision']<>''){
                    $campoAdicional = new campoAdicional();
                    $campoAdicional->nombre = "Punto Emision";
                    $campoAdicional->valor = $w_datosFacturaSRI['datos']['ptoEmision'];
                    $camposAdicionales[$i] = $campoAdicional;
                    $i+=1;
                }
                if($w_datosFacturaSRI['datos']['usuario']<>''){
                    $campoAdicional = new campoAdicional();
                    $campoAdicional->nombre = "Socio";
                    $campoAdicional->valor = $w_datosFacturaSRI['datos']['usuario'];
                    $camposAdicionales[$i] = $campoAdicional;
                    $i+=1;
                }
                if($w_datosFacturaSRI['datos']['cedulaUsuario']<>''){
                    $campoAdicional = new campoAdicional();
                    $campoAdicional->nombre = "Ruc";
                    $campoAdicional->valor = $w_datosFacturaSRI['datos']['cedulaUsuario'];
                    $camposAdicionales[$i] = $campoAdicional;
                    $i+=1;
                }
                if($w_datosFacturaSRI['datos']['telefonoUsuario']<>''){
                    $campoAdicional = new campoAdicional();
                    $campoAdicional->nombre = "Telefono Socio";
                    $campoAdicional->valor = $w_datosFacturaSRI['datos']['telefonoUsuario'];
                    $camposAdicionales[$i] = $campoAdicional;
                    $i+=1;
                }
                if($w_datosFacturaSRI['datos']['placaUsuario']<>''){
                    $campoAdicional = new campoAdicional();
                    $campoAdicional->nombre = "Placa";
                    $campoAdicional->valor = $w_datosFacturaSRI['datos']['placaUsuario'];
                    $camposAdicionales[$i] = $campoAdicional;
                    $i+=1;
                }
                if($w_datosFacturaSRI['datos']['tipoDocUsuario']<>''){
                    $campoAdicional = new campoAdicional();
                    $campoAdicional->nombre = "Contribuyente";
                    $campoAdicional->valor = $w_datosFacturaSRI['datos']['tipoDocUsuario'];
                    $camposAdicionales[$i] = $campoAdicional;
                    $i+=1;
                }
            }
            /*
            if($w_datos_empresa['datos']['padronMicroempresa']=='S'){
                $campoAdicional = new campoAdicional();
                $campoAdicional->nombre = "regimenMicroempresa";
                $campoAdicional->valor = "Reg. Nº111 del 31/12/2019";
                $camposAdicionales[$i] = $campoAdicional;
                $i+=1;
            }
            if($w_datos_empresa['datos']['padronAgenteRetencion']=='S'){
                $campoAdicional = new campoAdicional();
                $campoAdicional->nombre = "agenteRetencion";
                $campoAdicional->valor = "Res. Nro. NAC-DNCRASC20-00000001";
                $camposAdicionales[$i] = $campoAdicional;
                $i+=1;
            }
            */
            if($w_datos_empresa['datos']['artesanoCalificado']<>''){
                $campoAdicional = new campoAdicional();
                $campoAdicional->nombre = "artesanoCalificado";
                $campoAdicional->valor = 'Nro. '.$w_datos_empresa['datos']['artesanoCalificado'];
                $camposAdicionales[$i] = $campoAdicional;
                $i+=1;
            }
            if($w_datosFacturaSRI['datos']['telefonoComprador']<>''){
                $campoAdicional = new campoAdicional();
                $campoAdicional->nombre = "Telefono";
                $campoAdicional->valor = $w_datosFacturaSRI['datos']['telefonoComprador'];
                $camposAdicionales[$i] = $campoAdicional;
                $i+=1;
            }
            if($w_datosFacturaSRI['datos']['email']<>''){
                    $campoAdicional = new campoAdicional();
                    $campoAdicional->nombre = "Email";
                    $campoAdicional->valor = $w_datosFacturaSRI['datos']['email'];
                    $camposAdicionales[$i] = $campoAdicional;
                    $i+=1;
                }
            if($w_datosFacturaSRI['datos']['comentario']<>''){
                $campoAdicional = new campoAdicional();
                $campoAdicional->nombre = "Comentario";
                $campoAdicional->valor = $w_datosFacturaSRI['datos']['comentario'];
                $camposAdicionales[$i] = $campoAdicional;
                $i+=1;
            }
            $factura->infoAdicional = $camposAdicionales;
            $Log->EscribirLog(' Factura: '.var_export($factura,true));
            $procesarComprobante = new procesarComprobante();
            $procesarComprobante->comprobante = $factura;
            $procesarComprobante->envioSRI = false; //El sistema si es true //1-Crea XML en el directorio de autorizado 
            $res = $procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
            $Log->EscribirLog(' Respuesta: '.var_export($res,true));
            //var_dump($factura);
            //var_dump($res);
            //echo '<br>'.$i_autorizar.'<br>';
            if($i_autorizar=='S'){
                if ($res->return->estadoComprobante == "FIRMADO") {
                    $procesarComprobante = new procesarComprobante();
                    $procesarComprobante->comprobante = $factura;
                    $procesarComprobante->envioSRI = true; //El sistema si es false 
                    $res=$procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
                    $Log->EscribirLog(' Respuesta: '.var_export($res,true));
                    //var_dump($res);
                }else{
                    if($res->return->estadoComprobante == "PROCESANDOSE"){
                        $comprobantePendiente = new \comprobantePendiente();
                        $comprobantePendiente->configAplicacion = $configApp;
                        $comprobantePendiente->configCorreo = $configCorreo;
                        $comprobantePendiente->ambiente =               $w_datosFacturaSRI['datos']['ambiente'];
                        $comprobantePendiente->codDoc =                 $w_datosFacturaSRI['datos']['codDoc'];
                        $comprobantePendiente->establecimiento =        $w_datosFacturaSRI['datos']['establecimiento'];
                        $comprobantePendiente->fechaEmision =           $w_datosFacturaSRI['datos']['fechaEmision'];
                        $comprobantePendiente->ptoEmision =             $w_datosFacturaSRI['datos']['ptoEmision'];
                        $comprobantePendiente->ruc =                    $w_datos_empresa['datos']['ruc'];
                        $comprobantePendiente->secuencial =             $w_datosFacturaSRI['datos']['secuencial'];
                        $comprobantePendiente->tipoEmision =            $w_datos_empresa['datos']['tipoEmision'];
                        $comprobantePendiente->padronMicroempresa =     $w_datos_empresa['datos']['padronMicroempresa'];
                        $comprobantePendiente->padronAgenteRetencion =  $w_datos_empresa['datos']['padronAgenteRetencion'];
                        $procesarComprobantePendiente = new \procesarComprobantePendiente();
                        $procesarComprobantePendiente->comprobantePendiente = $comprobantePendiente;
                        $res = $procesarComprobanteElectronico->procesarComprobantePendiente($procesarComprobantePendiente);
                        if ($res->return->estadoComprobante == "PROCESANDOSE") {
                            $res->return->estadoComprobante = "ERROR";
                        }
                    }	
                }
            }
            $mensaje_final=	$res->return->estadoComprobante;
            //echo $res->return->estadoComprobante;
            if ($res->return->estadoComprobante == 'ERROR'){
                $mensaje_final.="-".$res->return->mensajes->mensaje;
                $update_sql =  "UPDATE del_factura 
                                SET  fac_error_sri='".$mensaje_final."'
                                WHERE fac_numero=".$fac_numero ;
            }
            if ($res->return->estadoComprobante == "FIRMADO") {
                $update_sql =  "UPDATE del_factura 
                                SET  fac_estado_sri='".$res->return->estadoComprobante."'
                                WHERE fac_numero=".$fac_numero ;

            }
            if($res->return->estadoComprobante=='AUTORIZADO'){
                $update_sql = "UPDATE del_factura 
                                SET  fac_estado_sri='".$res->return->estadoComprobante."',
                                    fac_clave='".$res->return->claveAcceso."',   
                                    fac_autorizacion='".$res->return->numeroAutorizacion."',
                                    fac_fecha_autorizacion='".$res->return->fechaAutorizacion."',
                                    fac_error_sri=''
                                WHERE fac_numero=".$fac_numero ;

            }
            if($res->return->estadoComprobante=='DEVUELTA'){
                if($res->return->mensajes->mensaje=='CLAVE ACCESO REGISTRADA'){
                    $update_sql = "UPDATE del_factura 
                                SET  fac_estado_sri='AUTORIZADO',
                                    fac_clave='".$res->return->claveAcceso."',   
                                    fac_autorizacion='".$res->return->claveAcceso."'
                                WHERE fac_numero=".$fac_numero ;
                }else{
                    $update_sql = "UPDATE del_factura 
                                SET  fac_estado_sri='".$res->return->estadoComprobante."',
                                    fac_clave='".$res->return->claveAcceso."',   
                                    fac_error_sri='".$res->return->mensajes->mensaje."'
                                WHERE fac_numero=".$fac_numero ;
                }

                $mensaje_final.="-".$res->return->mensajes->mensaje;
            }
            if($res->return->estadoComprobante=='NO AUTORIZADO'){
                $update_sql = "UPDATE del_factura 
                                SET  fac_estado_sri='".$res->return->estadoComprobante."',
                                    fac_clave='".$res->return->claveAcceso."',   
                                    fac_error_sri='".$res->return->mensajes->mensaje."',
                                    fac_fecha_autorizacion='".$res->return->fechaAutorizacion."'
                                WHERE fac_numero=".$fac_numero ;

                $mensaje_final.="-".$res->return->mensajes->mensaje;
            }
            //var_dump($update_sql);
            $ws_conexion=ws_coneccion_bdd();
            if (!$result = pg_query($ws_conexion, $update_sql)){
                $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
            }else{
                $o_respuesta=array('error'=>'0','mensaje'=>'Ejecución exitosa','datos'=>$mensaje_final);     
            }
            $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
            return $o_respuesta;
        }
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
        return $o_respuesta;
    }       
}	

function autorizar_guia($gr_numero,$i_autorizar){	
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Autorizar Guia ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Guia:'.$gr_numero);

        $w_parametros = buscarParametro ('RUTA_FIRMADOR','http://localhost:8085/MasterOffline/ProcesarComprobanteElectronico?wsdl');
        if ($w_parametros['error']=='0'){
        $ruta_firmador=$w_parametros['datos'];
        }
        $procesarComprobanteElectronico = new ProcesarComprobanteElectronico($wsdl = $ruta_firmador);
        $configApp = new \configAplicacion();
        $configCorreo = new \configCorreo();
        $guiaRemision = new  guiaRemision();
        
        $w_parametros = buscarParametro ('RUTA_EMPRESA','D:/Desarrollos/');
        if ($w_parametros['error']=='0'){
        $dir=$w_parametros['datos'];
        }
        
        $w_parametros = buscarParametro ('RUTA_IREPORT','D:/Desarrollo/IReport');
        if ($w_parametros['error']=='0'){
        $dir_ireport=$w_parametros['datos'];
        }
        
        $w_datos_guia=seleccionarDatosGuia($gr_numero);
        
        if ($w_datos_guia['error']=='0'){
            $var_empresa=$w_datos_guia['datos']['empresa'];
            $var_establecimiento=$w_datos_guia['datos']['establecimiento'];
            $var_tipo_emision=$w_datos_guia['datos']['tipo_emision'];
        }
        if($var_tipo_emision=='E'){

            $w_datos_empresa=seleccionarDatosEmpresaSRI($var_empresa,$var_establecimiento);
            if ($w_datos_empresa['error']=='0'){
                $configApp->dirAutorizados = $dir.$w_datos_empresa['datos']['ruc']."/documentos/";
                $configApp->dirLogo =        $dir.$w_datos_empresa['datos']['ruc']."/logo/".$w_datos_empresa['datos']['logo'];
                $configApp->dirFirma =       $dir.$w_datos_empresa['datos']['ruc']."/firma/".$w_datos_empresa['datos']['firma'];
                $configApp->passFirma =      $w_datos_empresa['datos']['passFirma'];
                $configApp->dirIreport=      $dir_ireport;
                $guiaRemision->configAplicacion = $configApp;
    
                $configCorreo->correoAsunto = "Nueva Guia de Remision";
                $configCorreo->correoHost =      $w_datos_empresa['datos']['correoHost'];
                $configCorreo->correoPass =      $w_datos_empresa['datos']['correoPass'];
                $configCorreo->correoPort =      $w_datos_empresa['datos']['correoPort'];
                $configCorreo->correoRemitente = $w_datos_empresa['datos']['correoRemitente'];
                $configCorreo->sslHabilitado =   false;
                $configCorreo->rutaLogo=         $w_datos_empresa['datos']['rutaLogo'].'logo.png';
                $guiaRemision->configCorreo =         $configCorreo;
    
                $guiaRemision->ruc                       = $w_datos_empresa['datos']['ruc'];
                $guiaRemision->razonSocial               = $w_datos_empresa['datos']['razonSocial'];
                $guiaRemision->nombreComercial           = $w_datos_empresa['datos']['nombreComercial']; 
                $guiaRemision->dirMatriz                 = $w_datos_empresa['datos']['dirMatriz']; 
                $guiaRemision->obligadoContabilidad      = $w_datos_empresa['datos']['obligadoContabilidad']; 
                $guiaRemision->tipoEmision               = $w_datos_empresa['datos']['tipoEmision'];
                if ( $w_datos_empresa['datos']['contribuyenteEspecial']!=''){
                    $guiaRemision->contribuyenteEspecial = $w_datos_empresa['datos']['contribuyenteEspecial'];
                }	
                $guiaRemision->padronMicroempresa    = $w_datos_empresa['datos']['padronMicroempresa'];
                $guiaRemision->padronAgenteRetencion = $w_datos_empresa['datos']['padronAgenteRetencion'];
                if ($w_datos_empresa['datos']['padronAgenteRetencion']=='S'){
                    $w_parametros = buscarParametro ('NUMERORESOAR','1');
                    if ($w_parametros['error']=='0'){
                        $guiaRemision->numeroResolucion=$w_parametros['datos'];
                    }
                }
                $guiaRemision->artesanoCalificado    = $w_datos_empresa['datos']['artesanoCalificado'];
            }
    
            $w_datosGuiaSRI=seleccionarDatosGuiaSRI($gr_numero);
            if ($w_datosGuiaSRI['error']=='0'){
                $guiaRemision->ambiente =                           $w_datosGuiaSRI['datos']['ambiente'];
                $guiaRemision->codDoc =                             $w_datosGuiaSRI['datos']['codDoc'];
                $guiaRemision->establecimiento =                    $w_datosGuiaSRI['datos']['establecimiento']; 
                $guiaRemision->ptoEmision =                         $w_datosGuiaSRI['datos']['ptoEmision']; 
                $guiaRemision->secuencial =                         $w_datosGuiaSRI['datos']['secuencial'];
                $guiaRemision->dirEstablecimiento =                 $w_datosGuiaSRI['datos']['dirEstablecimiento'];
                $guiaRemision->dirPartida = 						$w_datosGuiaSRI['datos']['dirPartida'];
                $guiaRemision->razonSocialTransportista = 			$w_datosGuiaSRI['datos']['razonSocialTransportista'];
                $guiaRemision->tipoIdentificacionTransportista = 	$w_datosGuiaSRI['datos']['tipoIdentificacionTransportista'];
                $guiaRemision->rucTransportista =					$w_datosGuiaSRI['datos']['rucTransportista'];
                $guiaRemision->fechaIniTransporte = 				$w_datosGuiaSRI['datos']['fechaIniTransporte'];
                $guiaRemision->fechaFinTransporte = 				$w_datosGuiaSRI['datos']['fechaFinTransporte'];
                $guiaRemision->placa = 								$w_datosGuiaSRI['datos']['placa'];
                $guiaRemision->rise = "RISE";
                //aqui van los DESTINATARIOS
                $w_destinatariosGuiaSRI=listaDestinatariosGuiSRI($gr_numero);
                if($w_destinatariosGuiaSRI['error']=='0'){
                    $destinatarios_guiaRemision = array();
                    $w_destinatarios=$w_destinatariosGuiaSRI['datos'];
                    $w_cantidad_items=count($w_destinatarios);
                    for ($i=0;$i<$w_cantidad_items;$i++){
                        $item=$w_destinatarios[$i];
                        $destinatario = new Destinatario();
                        $destinatario->identificacionDestinatario = 	$item['identificacionDestinatario'];
                        $destinatario->razonSocialDestinatario = 		$item['razonSocialDestinatario'];
                        $destinatario->dirDestinatario =				$item['dirDestinatario'];
                        $destinatario->motivoTraslado = 				$item['motivoTraslado'];
                        $destinatario->docAduaneroUnico = 				$item['docAduaneroUnico'];
                        $destinatario->codEstabDestino = 				$item['codEstabDestino'];
                        $destinatario->ruta =							$item['ruta'];
                        $destinatario->codDocSustento = 				$item['codDocSustento'];
                        $destinatario->numDocSustento = 				$item['numDocSustento'];
                        $destinatario->numAutDocSustento = 				$item['numAutDocSustento'];
                        $destinatario->fechaEmisionDocSustento = 		$item['fechaEmisionDocSustento'];
                        $detalles = array();
                        $w_detallesDestinatario=listaDetallesDestinatario($item['idDestinatario']) ;
                        if($w_detallesDestinatario['error']=='0'){
                            $w_detalles=$w_detallesDestinatario['datos'];
                            for ($j=0;$j<count($w_detalles);$j++){
                                $itemDetalle=$w_detalles[$j];
                                $detalle = new DetalleGuiaRemision();
                                $detalle->codigoInterno =   $itemDetalle['codigoInterno'];
                                $detalle->codigoAdicional = $itemDetalle['codigoAdicional'];
                                $detalle->descripcion =     $itemDetalle['descripcion'];
                                $detalle->cantidad =        $itemDetalle['cantidad'];
                                $detalles[$j] = $detalle;
                            }
                            $destinatario->detalles = $detalles;	
                        }
                        $destinatarios_guiaRemision[$i]=$destinatario;
                    }
                    $guiaRemision->destinatarios = $destinatarios_guiaRemision;
                }
                $i=0;
                $camposAdicionales = array();
                if($w_datosGuiaSRI['datos']['email']<>''){
                        $campoAdicional = new campoAdicional();
                        $campoAdicional->nombre = "Email";
                        $campoAdicional->valor = $w_datosGuiaSRI['datos']['email'];
                        $camposAdicionales[$i] = $campoAdicional;
                        $i+=1;
                }
                $guiaRemision->infoAdicional = $camposAdicionales;
                $procesarComprobante = new procesarComprobante();
                $procesarComprobante->comprobante = $guiaRemision;
                $procesarComprobante->envioSRI = false; //El sistema si es true //1-Crea XML en el directorio de autorizado 
                $res = $procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
                //var_dump($factura);
                //var_dump($res);
                //echo '<br>'.$i_autorizar.'<br>';
                if($i_autorizar=='S'){
                    if ($res->return->estadoComprobante == "FIRMADO") {
                        $procesarComprobante = new procesarComprobante();
                        $procesarComprobante->comprobante = $guiaRemision;
                        $procesarComprobante->envioSRI = true; //El sistema si es false 
                        $res=$procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
                        //var_dump($res);
                    }else{
                        if($res->return->estadoComprobante == "PROCESANDOSE"){
                            $comprobantePendiente = new \comprobantePendiente();
                            $comprobantePendiente->configAplicacion = $configApp;
                            $comprobantePendiente->configCorreo = $configCorreo;
                            $comprobantePendiente->ambiente =               $w_datosGuiaSRI['datos']['ambiente'];
                            $comprobantePendiente->codDoc =                 $w_datosGuiaSRI['datos']['codDoc'];
                            $comprobantePendiente->establecimiento =        $w_datosGuiaSRI['datos']['establecimiento'];
                            $comprobantePendiente->fechaEmision =           $w_datosGuiaSRI['datos']['fechaEmision'];
                            $comprobantePendiente->ptoEmision =             $w_datosGuiaSRI['datos']['ptoEmision'];
                            $comprobantePendiente->ruc =                    $w_datos_empresa['datos']['ruc'];
                            $comprobantePendiente->secuencial =             $w_datosGuiaSRI['datos']['secuencial'];
                            $comprobantePendiente->tipoEmision =            $w_datos_empresa['datos']['tipoEmision'];
                            $comprobantePendiente->padronMicroempresa =     $w_datos_empresa['datos']['padronMicroempresa'];
                            $comprobantePendiente->padronAgenteRetencion =  $w_datos_empresa['datos']['padronAgenteRetencion'];
                            $procesarComprobantePendiente = new \procesarComprobantePendiente();
                            $procesarComprobantePendiente->comprobantePendiente = $comprobantePendiente;
                            $res = $procesarComprobanteElectronico->procesarComprobantePendiente($procesarComprobantePendiente);
                            if ($res->return->estadoComprobante == "PROCESANDOSE") {
                                $res->return->estadoComprobante = "ERROR";
                            }
                        }	
                    }
                }
                $mensaje_final=	$res->return->estadoComprobante."\n";
                //echo $res->return->estadoComprobante;
                if ($res->return->estadoComprobante == 'ERROR'){
                    $mensaje_final.=$res->return->mensajes->mensaje."\n";
                }
                if ($res->return->estadoComprobante == "FIRMADO") {
                    $update_sql =  "UPDATE del_guia_remision 
                                    SET  gr_estado_sri='".$res->return->estadoComprobante."'
                                    WHERE gr_numero=".$gr_numero ;
    
                }
                if($res->return->estadoComprobante=='AUTORIZADO'){
                    $update_sql = "UPDATE del_guia_remision 
                                    SET  gr_estado_sri='".$res->return->estadoComprobante."',
                                        gr_clave='".$res->return->claveAcceso."',   
                                        gr_autorizacion='".$res->return->numeroAutorizacion."',
                                        gr_fecha_autorizacion='".$res->return->fechaAutorizacion."',
                                        gr_error_sri=''
                                    WHERE gr_numero=".$gr_numero ;
    
                }
                if($res->return->estadoComprobante=='DEVUELTA'){
                    if($res->return->mensajes->mensaje=='CLAVE ACCESO REGISTRADA'){
                        $update_sql = "UPDATE del_guia_remision 
                                    SET  gr_estado_sri='AUTORIZADO',
                                        gr_clave='".$res->return->claveAcceso."',   
                                        gr_autorizacion='".$res->return->claveAcceso."'
                                    WHERE gr_numero=".$gr_numero ;
                    }else{
                        $update_sql = "UPDATE del_guia_remision 
                                    SET  gr_estado_sri='".$res->return->estadoComprobante."',
                                        gr_clave='".$res->return->claveAcceso."',   
                                        gr_error_sri='".$res->return->mensajes->mensaje."'
                                    WHERE gr_numero=".$gr_numero ;
                    }
    
                    $mensaje_final.=$res->return->mensajes->mensaje."\n";
                }
                if($res->return->estadoComprobante=='NO AUTORIZADO'){
                    $update_sql = "UPDATE del_guia_remision 
                                    SET  gr_estado_sri='".$res->return->estadoComprobante."',
                                        gr_clave='".$res->return->claveAcceso."',   
                                        gr_error_sri='".$res->return->mensajes->mensaje."',
                                        gr_fecha_autorizacion='".$res->return->fechaAutorizacion."'
                                    WHERE gr_numero=".$gr_numero ;
    
                    $mensaje_final.=$res->return->mensajes->mensaje."\n";
                }
                $ws_conexion=ws_coneccion_bdd();
                if (!$result = pg_query($ws_conexion, $update_sql)){
                    $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
                }else{
                    $o_respuesta=array('error'=>'0','mensaje'=>'Ejecución exitosa','datos'=>$mensaje_final);     
                }
                $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
                return $o_respuesta;
            }
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Guia Fisica');
        return $o_respuesta;
        }
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
        return $o_respuesta;
    }    
}

function seleccionarDatosGuia($gr_numero){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Datos Guia ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Guia :'.$gr_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT gr_empresa,
                            gr_establecimiento,
                            gr_tipo_libretin
                      FROM del_guia_remision 
                      WHERE gr_numero=".$gr_numero;
        $Log->EscribirLog(' Consulta:'.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'empresa'=>$row->gr_empresa,
                    'establecimiento'=>$row->gr_establecimiento,
                    'tipo_emision'=>$row->gr_tipo_libretin    
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

function seleccionarDatosGuiaSRI($gr_numero){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Datos Guia SRI ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Guia: '.$gr_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT gr_numero,
                            gr_ambiente,
                            gr_tipo_comprobante,
                            est_codigo,
                            pen_serie,
                            gr_secuencial,
                            est_direccion,
                            gr_direccion_partida,
                            tr_nombre,
                            tr_tipo_identificacion,
                            tr_identificacion,
                            fecha_inicio,
                            fecha_fin,
                            tr_placa,
                            tr_email
                    FROM v_del_datos_guia_remision
                    WHERE gr_numero=".$gr_numero;
        $Log->EscribirLog(' Consulta:'.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'numero'=>$row->gr_numero,
                    'ambiente'=>$row->gr_ambiente,
                    'codDoc'=>$row->gr_tipo_comprobante,
                    'establecimiento'=>$row->est_codigo,
                    'ptoEmision'=>$row->pen_serie,
                    'secuencial'=>$row->gr_secuencial,
                    'dirEstablecimiento'=>$row->est_direccion,
                    'dirPartida'=>$row->gr_direccion_partida,
                    'razonSocialTransportista'=>$row->tr_nombre,
                    'tipoIdentificacionTransportista'=>$row->tr_tipo_identificacion,
                    'rucTransportista'=>$row->tr_identificacion,
                    'fechaIniTransporte'=>$row->fecha_inicio,
                    'fechaFinTransporte'=>$row->fecha_fin,
                    'placa'=>$row->tr_placa,
                    'email'=>$row->tr_email,
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

function listaDestinatariosGuiSRI($gr_numero){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Destinatarios Guia SRI ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Guia :'.$gr_numero);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT dg_guia,
                            dg_identificacion_destinatario,
                            dg_nombre_destinatario,
                            dg_direccion_destino,
                            dg_motivo_traslado,
                            dg_documento_aduanero,
                            coalesce(est_codigo,'') as est_codigo,
                            dg_ruta,
                            dg_coddoc_sustento,
                            dg_documento_sustento,
                            dg_autorizacion_sustento,
                            coalesce(dg_fecha_sustento,'') as fecha_sustento,
                            dg_id
                        FROM v_del_destinatario_guia_sri
                        where dg_guia=".$gr_numero."
                        order by dg_id" ;
        $Log->EscribirLog(' Consulta:'.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'idGuia'=>                      $row['dg_guia'],
                    'identificacionDestinatario'=>  $row['dg_identificacion_destinatario'],
                    'razonSocialDestinatario'=>     $row['dg_nombre_destinatario'],
                    'dirDestinatario'=>             $row['dg_direccion_destino'],
                    'motivoTraslado'=>              $row['dg_motivo_traslado'],
                    'docAduaneroUnico'=>            $row['dg_documento_aduanero'],
                    'codEstabDestino'=>             $row['est_codigo'],
                    'ruta'=>                        $row['dg_ruta'],
                    'codDocSustento'=>              $row['dg_coddoc_sustento'],
                    'numDocSustento'=>              $row['dg_documento_sustento'],
                    'numAutDocSustento'=>           $row['dg_autorizacion_sustento'],
                    'fechaEmisionDocSustento'=>     $row['fecha_sustento'],
                    'idDestinatario'=>              $row['dg_id'],
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

function listaDetallesDestinatario($id_destinatario){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Detalles Destinatario ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Destinatario:'.$id_destinatario);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                                dgd_destinatario,
                                pro_codigo,
                                pro_codigo_aux,
                                pro_descripcion,
                                dgd_cantidad 
                     FROM v_del_productos_destinatario_guia_sri
                     WHERE dgd_destinatario=".$id_destinatario."
                        order by dgd_id";
        $Log->EscribirLog(' Consulta:'.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'destinatario'=>$row['dgd_destinatario'],
                    'codigoInterno'=>$row['pro_codigo'],
                    'codigoAdicional'=>$row['pro_codigo_aux'],
                    'descripcion'=>$row['pro_descripcion'],
                    'cantidad'=>$row['dgd_cantidad'],
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
