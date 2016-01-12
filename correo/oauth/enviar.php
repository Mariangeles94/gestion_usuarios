<?php
session_start();
require '../../clases/Constant.php';
$semilla = Constant::SEMILLA;
$id = uniqid();
$origen = "alexita870@gmail.com";
$alias = "Mariangeles";
$destino = "mariangeles11_1994@hotmail.com";
//$destino = Request::get("usuario");
$asunto = "Prueba de correo";
$mensaje = "http://localhost:8008/Gestion/correo/oauth/phpactivar.php?activo=$semilla";

require_once '../../clases/Google/autoload.php';
require_once '../class.phpmailer.php';  //las últimas versiones también vienen con autoload

$cliente = new Google_Client();
$cliente->setApplicationName('ProyectoEnviarCorreoGmail');
$cliente->setClientId('628244456811-ttpsl5j7hqocrubipifrb38stvurfuk5.apps.googleusercontent.com');
$cliente->setClientSecret('Bfy_BJa0ctfm7kv2WwZOJp8l');
$cliente->setRedirectUri('https://podcast-mariangeles94.c9users.io/correo/oauth/guardar.php');
$cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
$cliente->setAccessToken(file_get_contents('token.conf'));
if ($cliente->getAccessToken()) {
    $service = new Google_Service_Gmail($cliente);
    try {
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->From = $origen;
        $mail->FromName = $alias;
        $mail->AddAddress($destino);
        $mail->AddReplyTo($origen, $alias);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->preSend();
        $mime = $mail->getSentMIMEMessage();
        $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
        $mensaje = new Google_Service_Gmail_Message();
        $mensaje->setRaw($mime);
        $service->users_messages->send('me', $mensaje);
        echo "Correo enviado correctamente";
    } catch (Exception $e) {
        print("Error en el envio del correo".$e->getMessage());
    }
}else{
    echo 'no conectado con gmail';
}