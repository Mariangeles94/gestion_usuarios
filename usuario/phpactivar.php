<?php
require '../clases/AutoCarga.php';
$bd = new DataBase();
$gestor = new ManageUser($bd);
$mensaje = "";
$activacion = Request::get("activo");

$email = Request::post("email");
$clave = Request::post("clave1");
$clave2 = Request::post("clave2");
$alias = $email;
$fechaAlta = date("Y-m-d");
$activo = 0;
if($clave == $clave2){
    $mensaje = "Usuario registrado con éxito";
    $usuario = new User($email, $clave, $alias, $fechaAlta, $activo);
    $gestor->insert($usuario);
    //header("Location:../correo/oauth/enviar.php?usuario=$email");
    header("Location:../correo/oauth/enviar.php");
}else{
    $mensaje = "Las claves no coinciden";
}
if($activacion == Constant::SEMILLA){
    $activo = 1;
    $gestor->set2($usuario, $activo);
}