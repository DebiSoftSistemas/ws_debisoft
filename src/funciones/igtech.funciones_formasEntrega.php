<?php
function listaFormasEntrega(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA FORMAS DE ENTREGA');
        $Log->EscribirLog(' DATOS DE ENTRADA ');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        dec_id,
                        dec_nombre 
                    FROM v_del_tipo_emtrega;";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            while($row = pg_fetch_array($result)) { 
                $w_respuesta[] = array(
                    'id'=>$row['dec_id'],
                    'tipo_entrega'=>$row['dec_nombre'],
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