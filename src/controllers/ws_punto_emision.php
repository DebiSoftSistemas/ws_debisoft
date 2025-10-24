<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once('src/funciones/igtech.funciones_puntoemision.php');

$app->get('/puntos_emision/{empresa}',function(Request $request,Response $response){
	$i_token     =	$request->getHeaderLine('Authorization');	
    $i_ct        =	$request->getHeaderLine('Content-Type');
	$i_accept    =	$request->getHeaderLine('Accept');
	$i_empresa =	$request->getAttribute('empresa');
	
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaPuntoEmisionEmpresa($i_empresa);			
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/puntos_emision/{empresa}/{establecimiento}',function(Request $request,Response $response){
	$i_token				= 	$request->getHeaderLine('Authorization');	
    $i_ct					=	$request->getHeaderLine('Content-Type');
	$i_accept				=	$request->getHeaderLine('Accept');
    $i_empresa 			= 	$request->getAttribute('empresa');
    $i_establecimiento	=	$request->getAttribute('establecimiento');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaPuntoEmisionEmpresaEstablecimiento($i_empresa,$i_establecimiento);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/puntos_emision/{empresa}/{establecimiento}/{codigo}',function(Request $request,Response $response){
	$i_token				= 	$request->getHeaderLine('Authorization');	
    $i_ct					=	$request->getHeaderLine('Content-Type');
	$i_accept				=	$request->getHeaderLine('Accept');
    $i_empresa 			= 	$request->getAttribute('empresa');
	$i_establecimiento	=	$request->getAttribute('establecimiento');
	$i_punto_emision	=	$request->getAttribute('codigo');
	
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=seleccionarPuntoEmisionEmpresa($i_empresa,$i_establecimiento,$i_punto_emision);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->post('/puntos_emision/nuevo',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertUpdatePuntoEmision($w_data);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->put('/puntos_emision/actualizar',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertUpdatePuntoEmision($w_data);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});


?>
