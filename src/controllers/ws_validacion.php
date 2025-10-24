<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.validar_cedula.php');

$app->get('/validar_cedula/{cedula}', function(Request $request, Response $response){
	$i_token= 	$request->getHeaderLine('Authorization');	
    $i_ct=		$request->getHeaderLine('Content-Type');
	$i_accept=	$request->getHeaderLine('Accept');
	$i_cedula = $request->getAttribute('cedula');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			if(esCedula($i_cedula)==1){
                $o_respuesta=array('error'=>'0','mensaje'=>'Cedula Valida','datos' =>$i_cedula);        
            }else{
                $o_respuesta=array('error'=>'9995','mensaje'=>'Cedula Incorrecta','datos' =>$i_cedula);    
            }
		}else {
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array( 'error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
}); 

$app->get('/validar_ruc/{ruc}', function(Request $request, Response $response){
	$i_token= 	$request->getHeaderLine('Authorization');	
    $i_ct=		$request->getHeaderLine('Content-Type');
	$i_accept=	$request->getHeaderLine('Accept');
	$i_ruc =    $request->getAttribute('ruc');
	try{
		//$w_datos_token=Auth::GetData($i_token);
		$w_autorizacion= seleccionarToken($i_token);
		if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
			if(validar_CedulaRuc($i_ruc,'RUC')){
                $o_respuesta=array('error'=>'0','mensaje'=>'RUC Valido','datos'=>$i_ruc);        
            }else{
                $o_respuesta=array('error'=>'9995','mensaje'=>'RUC Incorrecto','datos' =>$i_ruc);    
            }
		}else {
			$o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
}); 

$app->get('/validar_identificacion/{ruc}', function(Request $request, Response $response){
	$i_ct=		$request->getHeaderLine('Content-Type');
	$i_accept=	$request->getHeaderLine('Accept');
	$i_ruc =    $request->getAttribute('ruc');
	try{
		if(strlen($i_ruc)==10){
			if(validar_CedulaRuc($i_ruc,'CEDULA')){
                $o_respuesta=array( 'error'=>'0','mensaje'=>'Cedula Valida','datos' =>$i_ruc);        
            }else{
				$o_respuesta=array( 'error'=>'9995','mensaje'=>'Cedula Incorrecta','datos' =>$i_ruc); 
			}
		}elseif(strlen($i_ruc)==13){
			if(validar_CedulaRuc($i_ruc,'RUC')){
                $o_respuesta=array('error'=>'0','mensaje'=>'RUC Valido','datos' =>$i_ruc);        
            }else{
                $o_respuesta=array('error'=>'9995','mensaje'=>'RUC Incorrecto','datos' =>$i_ruc);    
            }
		}else {
			$o_respuesta=array('error'=>'9998','mensaje'=>'La longitud de la identificacion debe ser 10 0 13 digitos');
		}
	}catch (Throwable $e) {
		$o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
	}	
	$response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
}); 

?>