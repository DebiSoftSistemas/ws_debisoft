<?php
function listaGrupoProductosCompras($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Grupos Productos Compras ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT  
                            gpc_id,
                            gpc_empresa,
                            gpc_impuesto,
                            gpc_contable,
                            gpc_tipo
                     FROM del_grupo_productos_compras 
                     WHERE gpc_empresa='".$i_empresa."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'id'=>$row['gpc_id'],
                    'empresa'=>$row['gpc_empresa'],
                    'impuesto'=>$row['gpc_impuesto'],
                    'contable'=>$row['gpc_contable'],
                    'tipo'=>$row['gpc_tipo'],
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

function listaGrupoProductosVentas($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Lista Grupo Productos Ventas ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Empresa: '.$i_empresa);
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                            gpv_id,
                            gpv_empresa,
                            gpv_impuesto,
                            gpv_contable,
                            gpv_tipo
                    FROM del_grupo_productos_ventas
                    WHERE gpv_empresa='".$i_empresa."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'id'=>$row['gpv_id'],
                    'empresa'=>$row['gpv_empresa'],
                    'impuesto'=>$row['gpv_impuesto'],
                    'contable'=>$row['gpv_contable'],
                    'tipo'=>$row['gpv_tipo']
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