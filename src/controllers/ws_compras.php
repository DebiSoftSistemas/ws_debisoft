<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_compras.php');

$app->get('/compras/{empresa}/{anio}/{mes}',function(Request $request,Response $response){
	$i_token=$request->getHeaderLine('Authorization');
	$i_ct=$request->getHeaderLine('Content-Type');
	$i_accept=$request->getHeaderLine('Accept');
	$i_empresa =$request->getAttribute('empresa');
	$i_anio =$request->getAttribute('anio');
	$i_mes =$request->getAttribute('mes');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaCompras($i_empresa,$i_mes,$i_anio);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/detalleCompras/{empresa}/{id_factura}',function(Request $request,Response $response){
	$i_token=$request->getHeaderLine('Authorization');
	$i_ct=$request->getHeaderLine('Content-Type');
	$i_accept=$request->getHeaderLine('Accept');
	$i_empresa =$request->getAttribute('empresa');
	$i_factura=$request->getAttribute('id_factura');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaDetallesCompras($i_empresa,$i_factura);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/detalleComprasDebi/{empresa}/{id_factura}',function(Request $request,Response $response){
	$i_token=$request->getHeaderLine('Authorization');
	$i_ct=$request->getHeaderLine('Content-Type');
	$i_accept=$request->getHeaderLine('Accept');
	$i_empresa =$request->getAttribute('empresa');
	$i_factura=$request->getAttribute('id_factura');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaDetalleComprasDebi($i_empresa,$i_factura);
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