<?php
function seleccionarDatosSMTP(){
    try{
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT 
                        csmtp_servidor,
                        csmtp_usuario,
                        csmtp_contrasenia,
                        csmtp_email_seguridad,
                        csmtp_puerto_php,
                        'S' as  csmtp_seguridad 
                        FROM sis_config_smtp 
                        WHERE csmtp_filial=1";
        if($result = pg_query($ws_conexion, $select_sql)){
            $w_respuesta = array(); //creamos un array
            if( pg_num_rows($result) > 0 ){
                $row = pg_fetch_object($result, 0 );
                $w_respuesta = array(
                    'servidor'=>        $row->csmtp_servidor,
                    'usuario'=>         $row->csmtp_usuario,
                    'contrasenia'=>     $row->csmtp_contrasenia,
                    'from'=>            $row->csmtp_email_seguridad,
                    'puerto'=>          $row->csmtp_puerto_php,
                    'seguridad'=>       $row->csmtp_seguridad
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
        }
        $close = pg_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    return $o_respuesta;
}

function notificaPedido($i_pedido){
    $w_datos_smtp=seleccionarDatosSMTP();
    if ($w_datos_smtp['error']<>'0'){
        return $w_datos_smtp;
    }
    $ws_conexion=ws_coneccion_bdd();
    $select_sql="SELECT prof_fecha,
                        cl_nombre,
                        est_nombre,
                        prof_total,
                        RIGHT('000000000'||prof_secuencial,9) AS prof_secuencial,
                        emp_email 
                    FROM del_pedido_convenio 
                    INNER JOIN del_cliente ON prof_cliente=cl_id 
                    INNER JOIN del_establecimiento	ON prof_establecimiento=est_id
                    inner join del_empresa on prof_empresa=emp_ruc
                    WHERE prof_numero=".$i_pedido;
    if($result = pg_query($ws_conexion, $select_sql)){
        if( pg_num_rows($result) > 0 ){
            $row = pg_fetch_object( $result, 0 );
            $mensaje=formulario_nueva_compra($row->prof_fecha,$row->cl_nombre,$row->est_nombre,$row->prof_total,$row->prof_secuencial);
            $w_envio_correo=enviarCorreo(   $w_datos_smtp['datos']['servidor'],
                                            $w_datos_smtp['datos']['usuario'],
                                            $w_datos_smtp['datos']['contrasenia'],
                                            $w_datos_smtp['datos']['from'],
                                            $row->emp_email,
                                            'Nuevo Pedido',
                                            $mensaje,
                                            'H',
                                            '',
                                            '',
                                            $w_datos_smtp['datos']['puerto'],
                                            $w_datos_smtp['datos']['seguridad']
                                        );

        }
        $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_envio_correo);
    }else{
        $o_respuesta=array('error'=>'9997','mensaje'=>pg_last_error($ws_conexion));
    }                       
    return $o_respuesta;
}

function formulario_nueva_compra($fecha,$cliente,$establecimiento,$valor,$numero_pedido){
    $datos="";
    $ws_conexion=ws_coneccion_bdd();
    $select_sql="SELECT  
                    csmtp_nombre_empresa, 
                    sp_busca_parametro('DNSSERVER','https://igtech.dyndns.org')||csmtp_ruta_imagenes as ruta_imagenes, 
                    sp_busca_parametro('DNSSERVER','https://igtech.dyndns.org')||csmtp_ruta_plantillas as ruta_plantillas,
                    csmtp_dominio
                FROM sis_config_smtp
                WHERE csmtp_filial=1";
    if($result = pg_query($ws_conexion, $select_sql)){
        if( pg_num_rows($result) > 0 ){
            $row = pg_fetch_object( $result, 0 ); 
            $ruta_skin=$row->ruta_plantillas;
            $ruta_imagenes=$row->ruta_imagenes;
            $dominio=$row->csmtp_dominio;
            $empresa_cliente=$row->csmtp_nombre_empresa;
        }
    }   

    $nombre=$ruta_skin."skin_mail_nuevo_pedido.html";
   
    if (file_exists($nombre)){ 
          $fp = fopen ($nombre,"r"); 
          $datos = fread($fp, filesize($nombre));
          fclose($fp);
          $datos=str_replace('$fecha',$fecha,$datos);
          $datos=str_replace('$cliente',$cliente,$datos);
          $datos=str_replace('$establecimiento',$establecimiento,$datos);	
          $datos=str_replace('$valor',$valor,$datos);
          $datos=str_replace('$numero_pedido',$numero_pedido,$datos);	
          $datos=str_replace('imagenes/',$ruta_imagenes,$datos);
          $datos=str_replace('$DOMINIO',$dominio,$datos);
          $datos=str_replace('$EMPRESA_CLIENTE',$empresa_cliente,$datos);
          return $datos;
    }else
        return 'No se pudo cargar skin';
}

function enviarCorreo(  $mail_smtp_server,
                        $mail_smtp_user,
                        $mail_smtp_pass,
                        $mail_from,
                        $mail_to,
                        $mail_subject,
                        $mail_message,
                        $mail_format,
                        $mail_copies,
                        $mail_tp_copies,
                        $mail_port,
                        $mail_tp_connection){
    try {
        // Create the SMTP Transport
        $sc_mail_port     = "$mail_port";
        $sc_mail_tp_port  = "$mail_tp_connection";
        if ($sc_mail_tp_port == "S" || $sc_mail_tp_port == "Y"){
            $sc_mail_port = (!empty($sc_mail_port)) ? $sc_mail_port : 465;
            $transport = (new Swift_SmtpTransport($mail_smtp_server, $sc_mail_port,'ssl'))
                ->setUsername( $mail_smtp_user)
                ->setPassword($mail_smtp_pass);
        }else if($sc_mail_tp_port == "T"){
            $sc_mail_port = !empty($sc_mail_port) ? $sc_mail_port : 587;    
            $transport = (new Swift_SmtpTransport($mail_smtp_server, $sc_mail_port,'tls'))
                ->setUsername( $mail_smtp_user)
                ->setPassword($mail_smtp_pass);
         
        }else{
            $sc_mail_port = (!empty($sc_mail_port)) ? $sc_mail_port : 25;
            $transport = (new Swift_SmtpTransport($mail_smtp_server, $sc_mail_port))
                ->setUsername( $mail_smtp_user)
                ->setPassword($mail_smtp_pass);
        }
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();
        $message->setSubject($mail_subject);
        $message->setFrom([$mail_from => 'Debisoft']);
        $message->addTo($mail_to,'');
     
        // Add "CC" address [Use setCc method for multiple recipients, argument should be array]
        //$message->addCc($mail_to, '');
     
        // Add "BCC" address [Use setBcc method for multiple recipients, argument should be array]
        //$message->addBcc('recipient@gmail.com', 'recipient name');
     
        // Add an "Attachment" (Also, the dynamic data can be attached)
        //$attachment = Swift_Attachment::fromPath('example.xls');
        //$attachment->setFilename('report.xls');
        //$message->attach($attachment);
     
        // Add inline "Image"
        //$inline_attachment = Swift_Image::fromPath('nature.jpg');
        //$cid = $message->embed($inline_attachment);
     
        // Set the plain-text "Body"
        if($mail_format=='T'){
            $message->setBody( $mail_message);
        }else{    
        // Set a "Body"
            $message->addPart( $mail_message,'text/html');
        }    
        // Send the message
        return $mailer->send($message);
    } catch (Exception $e) {
      return $e->getMessage();
    }

}

?>