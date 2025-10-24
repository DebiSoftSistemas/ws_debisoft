<?php
function listaProvincias(){
    $Log=new IgtechLog ();
    $Log->Abrir();
    $Log->EscribirLog(' Lista Provincias ');
    $Log->EscribirLog(' DATOS DE ENTRADA');
    $ws_conexion=ws_coneccion_bdd();
    $select_sql="SELECT pro_id,
                        pro_pais,
                        pro_nombre,
                        pro_codigo,
                        pro_estado
                 FROM v_sis_provincia
                 order by pro_id;";
    $Log->EscribirLog(' Consulta: '.$select_sql);
	if($result = pg_query($ws_conexion, $select_sql)){
        $w_respuesta = array(); //creamos un array
        while($row = pg_fetch_array($result)) { 
            $w_respuesta[] = array(	
                'id'            =>$row['pro_id'],
                'pais'          =>$row['pro_pais'],
                'descripcion'   =>$row['pro_nombre'],
                'codigo'        =>$row['pro_codigo'],
                'estado'        =>$row['pro_estado'], 
            );
        }
        $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);    
    }else{
        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
    }    
    //desconectamos la base de datos
    $close = pg_close($ws_conexion);
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function listaProvinciasxPais($i_pais){
    $Log=new IgtechLog ();
    $Log->Abrir();
    $Log->EscribirLog(' Lista Provincias ');
    $Log->EscribirLog(' DATOS DE ENTRADA');
    $Log->EscribirLog(' Pais: '.$i_pais);
    $ws_conexion=ws_coneccion_bdd();
    $select_sql="SELECT pro_id,
                        pro_pais,
                        pro_nombre,
                        pro_codigo,
                        pro_estado
                 FROM v_sis_provincia
                 WHERE pro_pais=".$i_pais."
                 ORDER BY pro_id";
    $Log->EscribirLog(' Consulta: '.$select_sql);
	if($result = pg_query($ws_conexion, $select_sql)){
        $w_respuesta = array(); //creamos un array
        while($row = pg_fetch_array($result)) { 
            $w_respuesta[] = array(	
                'id'            =>$row['pro_id'],
                'pais'          =>$row['pro_pais'],
                'descripcion'   =>$row['pro_nombre'],
                'codigo'        =>$row['pro_codigo'],
                'estado'        =>$row['pro_estado'], 
            );
        }
        $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);    
    }else{
        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
    }    
    //desconectamos la base de datos
    $close = pg_close($ws_conexion);
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function seleccionarProvincia($i_provincia){
    $Log=new IgtechLog ();
    $Log->Abrir();
    $Log->EscribirLog(' Seleccionar Provincia ');
    $Log->EscribirLog(' DATOS DE ENTRADA');
    $Log->EscribirLog(' Provincia: '.$i_provincia);
    $ws_conexion=ws_coneccion_bdd();
    $select_sql="SELECT pro_id,
                        pro_pais,
                        pai_nombre,
                        pro_nombre,
                        pro_codigo,
                        pro_estado 
                 FROM v_sis_provincia
                 WHERE pro_id=".$i_provincia;
    $Log->EscribirLog(' Consulta: '.$select_sql);
	if($result = pg_query($ws_conexion, $select_sql)){
        if( pg_num_rows($result) > 0 ){
            $row = pg_fetch_object( $result, 0 ); 
            $w_respuesta = array(	
                'id'            =>$row->pro_id,
                'pais'          =>$row->pro_pais,
                'descripcion'   =>$row->pro_nombre,
                'codigo'        =>$row->pro_codigo,
                'estado'        =>$row->pro_estado,
            );
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta); 
        }else{
            $o_respuesta=array('error'=>'9996','mensaje'=>'No hay datos del país');
        }  
    }else{
        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
    }    
    //desconectamos la base de datos
    $close = pg_close($ws_conexion);
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

?>