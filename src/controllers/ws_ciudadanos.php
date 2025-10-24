<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_ciudadano.php');

$app->get('/ciudadano/{ced_ruc}',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_accept=$request->getHeaderLine('Accept');
    $i_ciudadano =$request->getAttribute('ced_ruc');
    try{
        // $w_datos_token=Auth::GetData($i_token);
        // $w_autorizacion= seleccionarToken($i_token);
        // if($w_datos_token->respuesta ==0 and $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=buscar_ciudadano($i_ciudadano);
        // }else{
        //     $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        // }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
    return $response;
});

$app->post('/ciudadano/nuevo',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_body = $request->getBody();
    $w_data = json_decode($i_body, true);
    try{
        // $w_datos_token=Auth::GetData($i_token);
        // $w_autorizacion= seleccionarToken($i_token);
        // if($w_datos_token->respuesta ==0 and $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=insertUpdateCiudadano($w_data);
        // }else{
            // $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        // }
    }catch (Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
    return $response;
});

$app->put('/ciudadano/actualizar',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_body = $request->getBody();
    $w_data = json_decode($i_body, true);
    try{
        // $w_datos_token=Auth::GetData($i_token);
        // $w_autorizacion= seleccionarToken($i_token);
        // if($w_datos_token->respuesta ==0 and $w_autorizacion['datos']['estado']=='V'){
             $o_respuesta=insertUpdateCiudadano($w_data);
        // }else{
        //     $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        // }
    }catch (Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
    return $response;
});

?>