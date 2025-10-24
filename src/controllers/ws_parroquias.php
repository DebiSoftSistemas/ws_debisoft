<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_parroquias.php');

$app->get('/parroquias/{pais}/{provincia}/{canton}',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $w_pais =$request->getAttribute('pais');
    $w_provincia =$request->getAttribute('provincia');
    $w_canton =$request->getAttribute('canton');
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=listaParroquias($w_pais,$w_provincia,$w_canton);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->get('/parroquias/{pais}/{provincia}/{canton}/{id}',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $w_pais =$request->getAttribute('pais');
    $w_provincia =$request->getAttribute('provincia');
    $w_canton =$request->getAttribute('canton');
    $w_id =$request->getAttribute('id');
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=seleccionarParroquia($w_pais,$w_provincia,$w_canton,$w_id);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->post('/parroquias/nuevo',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_body = $request->getBody();
    $w_data = json_decode($i_body, true);
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
            //$o_respuesta=nuevaParroquia($w_data);
        }else{
            $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        }
    }catch (Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
	return $response;
});

$app->put('/parroquias/actualizar',function(Request $request,Response $response){
    $i_token=$request->getHeaderLine('Authorization');
    $i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $i_body = $request->getBody();
    $w_data = json_decode($i_body, true);
    try{
        //$w_datos_token=Auth::GetData($i_token);
        $w_autorizacion= seleccionarToken($i_token);
        if (/*$w_datos_token->respuesta == 0 and */ $w_autorizacion['datos']['estado']=='V'){
           // $o_respuesta=actualizarParroquia($w_data);
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
