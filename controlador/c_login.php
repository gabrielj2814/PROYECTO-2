<?php
session_start();
include_once("../modelo/m_testudiante.php");
include_once("../modelo/m_tpersonal.php");
include_once("../modelo/m_ttutoracademico.php");
if(isset($_POST["cuenta"])){
    $cuenta=$_POST["cuenta"];
    $clave=$_POST["clave"];
    //START TUTOR ACADEMICO
    $TUTOR_CADEMICO=new ttutoracademico();
    $TUTOR_CADEMICO->dato_de_busqueda_tutor_academico($_POST["cuenta"]);
    $tutor_academico=$TUTOR_CADEMICO->consultar_tutor();
    $ttutoracademico=$TUTOR_CADEMICO->modificar_consulata_tutor_academico($tutor_academico);
    $cedula_tutor_academico=explode("-",$ttutoracademico["cedulatutoraca"]);
    //END TUTOR ACADEMICO
    // inicio consulata estudiante
    $EST=new testudiante();
    $EST->dato_de_busqueda_estudiante($_POST["cuenta"]);
    $consulta_est=$EST->consultar_estudiante();
    $consulta_est_modificada=$EST->modificar_consulta($consulta_est);
    $testudiante=explode("-",$consulta_est_modificada["cedulaest"]);
    // fin consulata estudiante
    // inicio consulata personal
    $PERSONAL=new tpersonal();
    $PERSONAL->dato_de_busqueda_personal($_POST["cuenta"]);
    $consulta_personal=$PERSONAL->consultar_personal();
    $consulta_personal_modificada=$PERSONAL->modificar_consulata_personal($consulta_personal);
    $tpersonal=explode("-",$consulta_personal_modificada["cedulaper"]);
    // fin consulata personal
    $contador=0;
    //-------------------------------------------------------------------------
    if ($cuenta==$testudiante[1]){
        estudiante($clave,$consulta_est_modificada);
    }
    else{
        $contador=$contador+1;
    }
    if ($cuenta==$tpersonal[1]){
        personal($clave,$consulta_personal_modificada);
    }
    else{
        $contador=$contador+1;
    }
    if ($cuenta==$cedula_tutor_academico[1]){
        tutor_academico($clave,$ttutoracademico);
    }
    else{
        $contador=$contador+1;
    }
    if ($contador==3){
        header("location:../vista/v_login.php?error_cuenta="."Error esta cuenta no exite o fue mal escrita verifique");
    }
    //-------------------------------------------------------------------------
    
}
//FUNCIONES
function estudiante($clave,$consulta_est_modificada){
    if(password_verify($clave,$consulta_est_modificada["claveest"])){
        $_SESSION["estado_sesion"]=$consulta_est_modificada["estado_sesion_est"];
        if ($consulta_est_modificada["estadoest"]=="1"){
            $_SESSION["usuario"]=$consulta_est_modificada["pnombreest"]." ".$consulta_est_modificada["snombreest"];
            $_SESSION["id-usuario"]=$consulta_est_modificada["cedulaest"];
            $_SESSION["tipo_de_usuario"]=$consulta_est_modificada["tipo_usuario_est"];
            
            #print "hola";
            header("location:../vista/v_estudiante.php");
        }
        else{
            header("location:../vista/v_login.php?error_clave="."este usuario esta inactivo"."&cuenta=".$consulta_est_modificada["cedulaest"]);
        }
    }
    else{
        header("location:../vista/v_login.php?error_clave="."Error al introducir la Contraseña"."&cuenta=".$consulta_est_modificada["cedulaest"]);
    }
}
function personal($clave,$consulta_personal_modificada){
    if(password_verify($clave,$consulta_personal_modificada["claveper"])){
        if ($consulta_personal_modificada["estadoper"]=="1"){
            $_SESSION["estado_sesion"]=$consulta_personal_modificada["estado_sesion_per"];
            if($consulta_personal_modificada["idestatusper"]=="0444"){
                
                $_SESSION["usuario"]=$consulta_personal_modificada["pnombreper"]." ".$consulta_personal_modificada["snombreper"]." ".$consulta_personal_modificada["papellidoper"]." ".$consulta_personal_modificada["sapellidoper"];
                $_SESSION["id-usuario"]=$consulta_personal_modificada["cedulaper"];
                $_SESSION["tipo_de_usuario"]=$consulta_personal_modificada["idestatusper"];
                header("location:../vista/v_perfil_personal_pasantia.php");
            }
            if($consulta_personal_modificada["idestatusper"]=="0777"){
                
                $_SESSION["usuario"]=$consulta_personal_modificada["pnombreper"]." ".$consulta_personal_modificada["snombreper"]." ".$consulta_personal_modificada["papellidoper"]." ".$consulta_personal_modificada["sapellidoper"];
                $_SESSION["id-usuario"]=$consulta_personal_modificada["cedulaper"];
                $_SESSION["tipo_de_usuario"]=$consulta_personal_modificada["idestatusper"];
                header("location:../vista/v_coordinador.php");
            }
            if($consulta_personal_modificada["idestatusper"]=="1111"){
                $_SESSION["usuario"]=$consulta_personal_modificada["pnombreper"]." ".$consulta_personal_modificada["snombreper"]." ".$consulta_personal_modificada["papellidoper"]." ".$consulta_personal_modificada["sapellidoper"];
                $_SESSION["id-usuario"]=$consulta_personal_modificada["cedulaper"];
                $_SESSION["tipo_de_usuario"]=$consulta_personal_modificada["idestatusper"];
                header("location:../vista/v_coordinador.php");
            }
        }
        else{
            header("location:../vista/v_login.php?error_clave="."este usuario esta inactivo"."&cuenta=".$consulta_personal_modificada["cedulaper"]);
        }
    }
    else{
        header("location:../vista/v_login.php?error_clave="."Error al introducir la Contraseña"."&cuenta=".$consulta_personal_modificada["cedulaper"]);
    }
}
function tutor_academico($clave,$ttutoracademico){
    if(password_verify($clave,$ttutoracademico["clavetutoraca"])){
        $_SESSION["estado_sesion"]=$ttutoracademico["estado_sesion_tutor"];
        if($ttutoracademico["estadotutoraca"]=="1"){
            $_SESSION["usuario"]=$ttutoracademico["pnombretutoraca"]." ".$ttutoracademico["snombretutoraca"]." ".$ttutoracademico["papellidotutoraca"]." ".$ttutoracademico["sapellidotutoraca"];
            $_SESSION["id-usuario"]=$ttutoracademico["cedulatutoraca"];
            $_SESSION["tipo_de_usuario"]=$ttutoracademico["tipo_usuario_tutor"];
            header("location:../vista/v_cerrar_pasantia.php");
        }
        else{
            header("location:../vista/v_login.php?error_clave="."este usuario esta inactivo"."&cuenta=".$consulta_personal_modificada["cedulaper"]);
        }
    }
    else{
        header("location:../vista/v_login.php?error_clave="."Error al introducir la Contraseña"."&cuenta=".$consulta_personal_modificada["cedulaper"]);
    }

}
#funcion AJAX
if(isset($_GET["cedula_usuario"])){
    #print "cedula usuario->".$_GET["cedula_usuario"]." tipo de usuario->".$_GET["tipo"]." clave nueva->".$_GET["nueva_clave"];
    switch($_GET["tipo"]){
        case'estudiante':cambiar_clave_estudiante($_GET["cedula_usuario"],$_GET["nueva_clave"]);break;
        case'personal':cambiar_clave_personal($_GET["cedula_usuario"],$_GET["nueva_clave"]);break;
        case'tutor':cambiar_clave_tutor($_GET["cedula_usuario"],$_GET["nueva_clave"]);break;
    }
}
function cambiar_clave_estudiante($cedula,$nueva_clave){
    #print "estudiante";
    $ESTUDIANTE=new testudiante();
    $ESTUDIANTE->dato_de_busqueda_estudiante($cedula);
    $clave_nueva_encriptada=password_hash($nueva_clave,PASSWORD_DEFAULT,array("coste"=>12));
    #print $clave_nueva_encriptada;
    $ESTUDIANTE->restaurar_clave($clave_nueva_encriptada);
    $mensaje=["mensaje"=>"la clave a sido actualizada exitosamente"];
    print json_encode($mensaje);
}
function cambiar_clave_tutor($cedula,$nueva_clave){
    #print "tutor";
    $TUTOR=new ttutoracademico();
    $TUTOR->dato_de_busqueda_tutor_academico($cedula);
    $clave_nueva_encriptada=password_hash($nueva_clave,PASSWORD_DEFAULT,array("coste"=>12));
    $TUTOR->reiniciar_clave($clave_nueva_encriptada);
    $mensaje=["mensaje"=>"la clave a sido actualizada exitosamente"];
    print json_encode($mensaje);

}
function cambiar_clave_personal($cedula,$nueva_clave){
    #print "personal";
    $PERSONAL=new tpersonal();
    $PERSONAL->dato_de_busqueda_personal($cedula);
    $clave_nueva_encriptada=password_hash($nueva_clave,PASSWORD_DEFAULT,array("coste"=>12));
    $PERSONAL->restaurar_clave($clave_nueva_encriptada);
    $mensaje=["mensaje"=>"la clave a sido actualizada exitosamente"];
    print json_encode($mensaje);
}

if(isset($_GET["cedula"])){
    #print "hola ->".$_GET["cedula"]." ".$_GET["tipo"];
    switch($_GET["tipo"]){
        case'estudiante':recuperar_estudiante($_GET["cedula"]);break;
        case'personal':recuperar_personal($_GET["cedula"]);break;
        case'tutor':recuperar_tutor($_GET["cedula"]);break;
    }
}
function recuperar_estudiante($cedula){
    #print "estudiante";
    $ESTUDIANTE=new testudiante();
    $ESTUDIANTE->dato_de_busqueda_estudiante($cedula);
    $estudiante=$ESTUDIANTE->consultar_estudiante();
    $testudiante=$ESTUDIANTE->modificar_consulta($estudiante);
    $cedual_explotada=explode("-",$testudiante["cedulaest"]);
    if($cedula==$cedual_explotada[1]){
        $mensaje=["mensaje"=>"el estudiante a sido encontrado","pregunta_1"=>$testudiante["pregunta_1_est"],"pregunta_2"=>$testudiante["pregunta_2_est"],"respuesta_1"=>$testudiante["respuesta_1_est"],"respuesta_2"=>$testudiante["respuesta_2_est"],"estado"=>"true"];
        #print_r($mensaje);
        print json_encode($mensaje);
        #print $testudiante["respuesta_1_est"]." ".$testudiante["respuesta_2_est"];
    }
    else{
        #print "el estudiante no a sido encontrado";
        $mensaje=["mensaje"=>"el estudiante no a sido encontrado","estado"=>"false"];
        print json_encode($mensaje);
    }

}
function recuperar_tutor($cedula){
    #print "tuor";
    $TUTOR=new ttutoracademico();
    $TUTOR->dato_de_busqueda_tutor_academico($cedula);
    $tutor=$TUTOR->consultar_tutor();
    $ttutoracademico=$TUTOR->modificar_consulata_tutor_academico($tutor);
    $cedula_explotada_tutor=explode("-",$ttutoracademico["cedulatutoraca"]);
    if($cedula==$cedula_explotada_tutor[1]){
        $mensaje=["mensaje"=>"el tutor a sido encontrado","pregunta_1"=>$ttutoracademico["pregunta_1_tutor"],"pregunta_2"=>$ttutoracademico["pregunta_2_tutor"],"respuesta_1"=>$ttutoracademico["respuesta_1_tutor"],"respuesta_2"=>$ttutoracademico["respuesta_2_tutor"],"estado"=>"true"];
        print json_encode($mensaje);
    }
    else{
        $mensaje=["mensaje"=>"el tutor no a sido encontrado","estado"=>"false"];
        print json_encode($mensaje);
    }
}
function recuperar_personal($cedula){
    #print "personal";
    $PERSONAL=new tpersonal();
    $PERSONAL->dato_de_busqueda_personal($cedula);
    $personal=$PERSONAL->consultar_personal();
    $tpersonal=$PERSONAL->modificar_consulata_personal($personal);
    $cedula_explotada_personal=explode("-",$tpersonal["cedulaper"]);
    if($cedula==$cedula_explotada_personal[1]){
        $mensaje=["mensaje"=>"el usuario a sido encontrado","pregunta_1"=>$tpersonal["pregunta_1_per"],"pregunta_2"=>$tpersonal["pregunta_2_per"],"respuesta_1"=>$tpersonal["respuesta_1_per"],"respuesta_2"=>$tpersonal["respuesta_2_per"],"estado"=>"true"];
        print json_encode($mensaje);
    }
    else{
        $mensaje=["mensaje"=>"el usuario no a sido encontrado","estado"=>"false"];
        print json_encode($mensaje);
    }
}
?>