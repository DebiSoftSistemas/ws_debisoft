<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_tipo_empresa.php');
$app->get('/tarifas_iva',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=listaTarifasIVA();
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
}); 

$app->get('/tipo_empresa',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_empresa =$request->getAttribute('empresa');
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=listaTipoEmpresa();
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/tipo_empresa/{codigo}',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_codigo =$request->getAttribute('codigo');
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=seleccionarTipoEmpresa($i_codigo);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->post('/tipo_empresa/nuevo',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_body = $request->getBody();
    $w_data = json_decode($i_body, true);
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=registrarTipoEmpresa($w_data);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->put('/tipo_empresa/actualizar',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_body = $request->getBody();
    $w_data = json_decode($i_body, true);
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=actualizarTipoEmpresa($w_data);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});
/*
$app->get('/tipo_empresa/productos/{tipo_empresa}',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_tipo_empresa =$request->getAttribute('tipo_empresa');
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if($w_datos_token->respuesta == 0 and  $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=listaProductosTipoEmpresa($i_tipo_empresa);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/tipo_empresa/productos/{tipo_empresa}/{codigo}',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_tipo_empresa =$request->getAttribute('tipo_empresa');
    $i_codigo =$request->getAttribute('codigo');

    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if($w_datos_token->respuesta == 0 and  $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=seleccionarProductosTipoEmpresa($i_tipo_empresa,$i_codigo);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->post('/tipo_empresa/productos/nuevo',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_body = $request->getBody();
    $w_data = json_decode($i_body, true);
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if($w_datos_token->respuesta == 0 and  $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=registrarProductoTipoEmpresa($w_data);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->put('/tipo_empresa/productos/actualizar',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_body = $request->getBody();
    $w_data = json_decode($i_body, true);
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if($w_datos_token->respuesta == 0 and  $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=actualizarProductosTipoEmpresa($w_data);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});
*/

?>