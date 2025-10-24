<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_usuarios.php');

$app->get('/usuarios', function(Request $request, Response $response){
	$i_token= 	$request->getHeaderLine('Authorization');	
    $i_ct=		$request->getHeaderLine('Content-Type');
	$i_accept=	$request->getHeaderLine('Accept');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listarUsuarios();
			
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
}); 

$app->get('/usuarios/{empresa}', function(Request $request, Response $response){
	$i_token= 	$request->getHeaderLine('Authorization');	
    $i_ct=		$request->getHeaderLine('Content-Type');
	$i_accept=	$request->getHeaderLine('Accept');
	$i_empresa =$request->getAttribute('empresa');
	
	//agregar los demas parametros que se necesitan
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=listarusuariosEmpresa($i_empresa);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
}); 

$app->get('/usuarios/{empresa}/{usuario}', function(Request $request, Response $response){
	$i_token= 	$request->getHeaderLine('Authorization');	
    $i_ct=		$request->getHeaderLine('Content-Type');
	$i_accept=	$request->getHeaderLine('Accept');
    $i_empresa =$request->getAttribute('empresa');
    $i_usuario =$request->getAttribute('usuario');
	
	//agregar los demas parametros que se necesitan
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=seleccionarUsuario($i_empresa,$i_usuario);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;	
}); 

$app->post('/usuarios/nuevo',function(Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertUpdateUsuario($w_data);
		}else{
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e){
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;	
});

$app->put('/usuarios/actualizar',function (Request $request,Response $response){
	$i_token= 		$request->getHeaderLine('Authorization');
	$i_ct=			$request->getHeaderLine('Content-Type');
	$i_accept=		$request->getHeaderLine('Accept');
	
	$i_body = $request->getBody();
	$w_data = json_decode($i_body, true);
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if(/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			$o_respuesta=insertUpdateUsuario($w_data);
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
