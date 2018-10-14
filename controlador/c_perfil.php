<?php
$vista=$_POST["vista"];
$clave=$_POST["clave"];
$ID=$_POST["ID"];
include_once("../modelo/m_testudiante.php");
$ESTUDIANTE=new testudiante();
include_once("../modelo/m_ttutoracademico.php");
$TUTOR=new ttutoracademico();
include_once("../modelo/m_tpersonal.php");
$PERSONAL=new tpersonal();
switch($vista){
    case 'estudiante':
    $clave_lista=encriptar_clave($clave);
    $ESTUDIANTE->dato_de_busqueda_estudiante($ID);
    $ESTUDIANTE->actualizar_clave($clave_lista,"1");
    $respuesta="si";
    $tipo="actualizar";
    $mensaje="Clave Actualizada Exitosamente";
    header("location:../vista/v_perfil.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);break;
    ###################
    case 'tutor':
    $clave_lista=encriptar_clave($clave);
    $TUTOR->dato_de_busqueda_tutor_academico($ID);
    $TUTOR->actualizar_clave($clave_lista,"1");
    $respuesta="si";
    $tipo="actualizar";
    $mensaje="Clave Actualizada Exitosamente";
    header("location:../vista/v_perfil_tutor_academico.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);break;
    ##################
    case 'personal':
    $clave_lista=encriptar_clave($clave);
    $PERSONAL->dato_de_busqueda_personal($ID);
    $PERSONAL->actualizar_clave($clave_lista,"1");
    $respuesta="si";
    $tipo="actualizar";
    $mensaje="Clave Actualizada Exitosamente";
    header("location:../vista/v_perfil_personal_pasantia.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);break;
}

function encriptar_clave($clave){
    $clave_encriptada=password_hash($clave,PASSWORD_DEFAULT,array("coste"=>12));
    return $clave_encriptada;
}
?>