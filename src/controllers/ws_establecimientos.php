<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_establecimiento.php');

$app->get('/establecimientos/{empresa}',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');	
    $i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	$i_empresa = 	$request->getAttribute('empresa');
	
	//agregar los demas parametros que se necesitan
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaEstablecimientos($i_empresa);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/establecimientos/{empresa}/{id}',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');	
    $i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	$i_empresa = 	$request->getAttribute('empresa');
	$i_codigo	=	$request->getAttribute('id');
	//agregar los demas parametros que se necesitan
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=seleccionarEstablecimiento($i_empresa,$i_codigo);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->post('/establecimientos/nuevo',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertUpdateEstablecimiento($w_data);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->put('/establecimientos/actualizar',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertUpdateEstablecimiento($w_data);
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
