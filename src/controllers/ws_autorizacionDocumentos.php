<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.autorizar_documentos.php');

$app->get('/autorizarfactura/{id}',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_factura =$request->getAttribute('id');
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if(/*$w_datos_token->respuesta ==0 and */$w_autorizacion['datos']['estado']=='V'){
            $o_respuesta= autorizar_factura($i_factura,'S');
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
    return $response;
});
?>