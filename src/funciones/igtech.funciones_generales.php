<?php
function generaSecuencial($i_secuencial){
    try{
        $ws_conexion = ws_coneccion_bdd();
        $select_sql="SELECT sp_secuencial('".$i_secuencial."')";
        if($rs_secuencial=pg_query($ws_conexion, $select_sql)){
            while($row = pg_fetch_array($rs_secuencial)) {
                $w_respuesta=$row[0];
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

function verificaConvenio($i_convenio){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Verificar Convenio ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_convenio,true));
        $ws_conexion = ws_coneccion_bdd();
        $select_sql="SELECT dec_id FROM v_sis_convenios WHERE dec_nombre='".$i_convenio."'";
        $Log->EscribirLog(' Consulta: '.var_export($select_sql,true));
        if($rs_convenio=pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($rs_convenio) > 0 ){
                $row = pg_fetch_object( $rs_convenio, 0 );
                $w_respuesta=$row->dec_id;
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }else{
                $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el convenio');
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

function buscarParametro($i_parametro,$i_valor_defecto){
    try{
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT sp_busca_parametro ('".$i_parametro."','".$i_valor_defecto."')";
        
        if($result = pg_query($ws_conexion, $select_sql)){
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object( $result, 0 ); 
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$row->sp_busca_parametro);
            }
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$i_valor_defecto);
        }
        $close = pg_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    return $o_respuesta;
}

function crearDirectorios($i_ruc){
    
    try{    
        $w_resp_parametro=buscarParametro('RUTA_DOCUMENTOS','E:/Desarrollos/');
        
        if($w_resp_parametro['error']=='0'){
            $dir=$w_resp_parametro['datos'].$i_ruc;
        }
        $rights=0777;
        $dir_logo=$dir.'/logo';
        $dir_firma=$dir.'/firma';
        $dir_documentos=$dir.'/documentos';
        //var_dump($dir);
        if (!file_exists($dir)){
            mkdir($dir, $rights);
            chmod($dir, $rights);
        }	
        if (!file_exists($dir_logo)){
            mkdir($dir_logo, $rights);
            chmod($dir_logo, $rights);
        }	
        if (!file_exists($dir_firma)){
            mkdir($dir_firma, $rights);
            chmod($dir_firma, $rights);
        }	
        if (!file_exists($dir_documentos)){
            mkdir($dir_documentos, $rights);
            chmod($dir_documentos, $rights);
        }
        $w_parametro_ruta_logo=buscarParametro('RUTA_IMAGENES','D:/Desarrollo/httdocs/runrumm/_lib/file/img');
        if($w_parametro_ruta_logo['error']=='0')$dir_logo_interno=$w_parametro_ruta_logo['datos'].$i_ruc;
        $w_parametro_ruta_firma=buscarParametro('RUTA_FIRMAS','D:/Desarrollo/httdocs/runrumm/_lib/file/doc');
        if($w_parametro_ruta_firma['error']=='0')$dir_firma_interno=$w_parametro_ruta_firma['datos'].$i_ruc;
        if (!file_exists($dir_logo_interno)){
            mkdir($dir_logo_interno, $rights);
            chmod($dir_logo_interno, $rights);
        }else{
            chmod($dir_firma_interno, $rights);
        }	
        if (!file_exists($dir_firma_interno)){
            mkdir($dir_firma_interno, $rights);
            chmod($dir_firma_interno, $rights);
        }else{
            chmod($dir_firma_interno, $rights);
        }
        $o_respuesta= array(
            'error'=>'0',
            'mesaje'=>'ok',
            'datos' => array(
                'ruta_logo'=>$dir_logo,
                'ruta_firma'=>$dir_firma,
                'ruta_logo_interno'=>$dir_logo_interno,
                'ruta_firma_interno'=>$dir_firma_interno                
            )
        );
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    return $o_respuesta;  
}

function copiarArchivos($ruta_origen,$ruta_destino,$ruta_destino_interno){
    try{
        $archivo_actual=$ruta_origen;
        $pos = strrpos($ruta_origen, "%");
        $posf=strrpos($ruta_origen, "?");
        if ($pos === false or $posf===false) { 
            return ''; 
        }else{
            $pos+=1;
            $longitud=$posf-$pos;    
            $nombre_archivo=substr($ruta_origen,$pos,$longitud);
            $archivo_nuevo=$ruta_destino.'/'.$nombre_archivo;
            $archivo_nuevo_interno=$ruta_destino_interno.'/'.$nombre_archivo;
            $archivo_memoria = file_get_contents($archivo_actual);
            $save = file_put_contents($archivo_nuevo,$archivo_memoria);
            $save1 = file_put_contents($archivo_nuevo_interno,$archivo_memoria);
            if(file_exists($archivo_nuevo)){
                return $nombre_archivo;
            }
        }
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
        return '';
    }
}
?>