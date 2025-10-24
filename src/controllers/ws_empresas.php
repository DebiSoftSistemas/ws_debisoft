<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_empresa.php');

$app->get('/empresas', function(Request $request, Response $response){
	$i_token= 	$request->getHeaderLine('Authorization');	
    $i_ct=		$request->getHeaderLine('Content-Type');
	$i_accept=	$request->getHeaderLine('Accept');
	
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaEmpresas();
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
}); 

$app->get('/empresas/{empresa}',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');	
    $i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	$i_empresa =	$request->getAttribute('empresa');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=seleccionarEmpresa($i_empresa);
		}else {
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->post('/empresas/nuevo',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertupdateEmpresa($w_data);
		}else {
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage(),'datos'=>$w_data);
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;	
});

$app->put('/empresas/actualizar',function (Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertupdateEmpresa($w_data);
		}else {
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

?>
