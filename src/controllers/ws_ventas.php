<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_ventas.php');

$app->get('/ventas/{empresa}/{anio}/{mes}',function(Request $request,Response $response){
	$i_token=$request->getHeaderLine('Authorization');
	$i_ct=$request->getHeaderLine('Content-Type');
	$i_accept=$request->getHeaderLine('Accept');
	$i_empresa =$request->getAttribute('empresa');
	$i_anio=$request->getAttribute('anio');
	$i_mes=$request->getAttribute('mes');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaVentas($i_empresa,$i_anio,$i_mes);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/detalleVentas/{empresa}/{id_factura}',function(Request $request,Response $response){
	$i_token=$request->getHeaderLine('Authorization');
	$i_ct=$request->getHeaderLine('Content-Type');
	$i_accept=$request->getHeaderLine('Accept');
	$i_empresa =$request->getAttribute('empresa');
	$i_factura =$request->getAttribute('id_factura');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaDetalleVentas($i_empresa,$i_factura);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/detalleVentasDebi/{empresa}/{id_factura}',function(Request $request,Response $response){
	$i_token=$request->getHeaderLine('Authorization');
	$i_ct=$request->getHeaderLine('Content-Type');
	$i_accept=$request->getHeaderLine('Accept');
	$i_empresa =$request->getAttribute('empresa');
	$i_factura =$request->getAttribute('id_factura');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaDetalleVentasDebi($i_empresa,$i_factura);
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