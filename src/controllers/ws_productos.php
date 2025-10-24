<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_productos.php');

$app->get('/productos_venta/{empresa}/{establecimiento}',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');	
    $i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	$i_empresa = 	$request->getAttribute('empresa');
	$i_establecimiento=$request->getAttribute('establecimiento');
	//agregar los demas parametros que se necesitan
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaProductosVenta($i_empresa);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/productos_venta/{empresa}/{establecimiento}/{producto}',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');	
    $i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	$i_empresa = 	$request->getAttribute('empresa');
	$i_establecimiento = 	$request->getAttribute('establecimiento');
	$i_producto	=	$request->getAttribute('producto');
	
	//agregar los demas parametros que se necesitan
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=stockProductosVenta($i_empresa,$i_establecimiento,$i_producto);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->post('/productos_venta/nuevo',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertUpdateProductoVenta($w_data);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;	
});

$app->put('/productos_venta/actualizar',function (Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertUpdateProductoVenta($w_data);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/productos_compra/{empresa}',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');	
    $i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	$i_empresa = 	$request->getAttribute('empresa');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listaProductosCompra($i_empresa);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/productos_compra/{empresa}/{codigo}',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');	
    $i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	$i_empresa = 	$request->getAttribute('empresa');
	$i_codigo = 	$request->getAttribute('codigo');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=seleccionarProducto($i_empresa,$i_codigo);
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