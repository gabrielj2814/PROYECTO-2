<?php
#SQL->select cedulatutoraca,pnombretutoraca,snombretutoraca,papellidotutoraca,sapellidotutoraca from ttutoracademico
//print $_POST["operacion"];
include_once("../modelo/m_ttutoracademico.php");
if(isset($_POST["operacion"])){
    $oparacion=$_POST["operacion"];
    switch($oparacion){
        case "registrar":registrar_tutor_aca();break;
        case "actualizar":actualizar_tutor_aca();break;
        case "consultar":consultar_tutor_aca();break;
    }
}
function registrar_tutor_aca(){
    $TUTOR_A=new ttutoracademico();
    $encriptar_clave=password_hash($_POST["cedula-tutor-academico"],PASSWORD_DEFAULT,array("coste"=>12));
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-tutor-academico"];
    $TUTOR_A->dato_de_busqueda_tutor_academico($cedula);
    $areglo_tutor_a=$TUTOR_A->buscar_tutor_academico();
    $ttutoracademico=$TUTOR_A->modificar_consulata_tutor_academico($areglo_tutor_a);
    if($cedula!=$ttutoracademico["cedulatutoraca"]){
        $TUTOR_A->setdatos_tutor_academico($cedula,$_POST["p-nombre-tutor-academico"],$_POST["s-nombre-tutor-academico"],$_POST["p-apellido-tutor-academico"],$_POST["s-apellido-tutor-academico"],$encriptar_clave,$_POST["estado"],$_POST["profecion-tutor-academico"],$_POST["ntlf-tutor-academico"],$_POST["correo-tutor-academico"],$_POST["pregunta_1"],$_POST["pregunta_2"],$_POST["respuesta_1"],$_POST["respuesta_2"]);
        $TUTOR_A->registrar_tutor_academico();
        $tipo="registrar";
        $respuesta="si";
        $mensaje="Registro Completado la contraseña del tutor que acaba de registrar es su cedula";
        header("location:../vista/v_tutor_academico.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="registrar";
        $respuesta="no";
        $mensaje="Error al registrar (esta cedula ya a sido registrada)";
        header("location:../vista/v_tutor_academico.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function actualizar_tutor_aca(){
    $TUTOR_A=new ttutoracademico();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-tutor-academico"];
    $TUTOR_A->dato_de_busqueda_tutor_academico($cedula);
    $areglo_tutor_a=$TUTOR_A->buscar_tutor_academico();
    $ttutoracademico=$TUTOR_A->modificar_consulata_tutor_academico($areglo_tutor_a);
    if($cedula==$ttutoracademico["cedulatutoraca"]){
        $TUTOR_A->actualizar_datos_tutor_academico($cedula,$_POST["p-nombre-tutor-academico"],$_POST["s-nombre-tutor-academico"],$_POST["p-apellido-tutor-academico"],$_POST["s-apellido-tutor-academico"],$_POST["estado"],$_POST["profecion-tutor-academico"],$_POST["ntlf-tutor-academico"],$_POST["correo-tutor-academico"],$_POST["pregunta_1"],$_POST["pregunta_2"],$_POST["respuesta_1"],$_POST["respuesta_2"]);
        $TUTOR_A->actualizar_tutor_academico();
        $tipo="actualizar";
        $respuesta="si";
        $mensaje="Actualización Completada";
        header("location:../vista/v_tutor_academico.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="actualizar";
        $respuesta="no";
        $mensaje="Error al Actualizar (Error esta cedula no esta registrada)";
        header("location:../vista/v_tutor_academico.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function consultar_tutor_aca(){
    $TUTOR_A=new ttutoracademico();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-tutor-academico"];
    $TUTOR_A->dato_de_busqueda_tutor_academico($cedula);
    $areglo_tutor_a=$TUTOR_A->buscar_tutor_academico();
    $ttutoracademico=$TUTOR_A->modificar_consulata_tutor_academico($areglo_tutor_a);
   if($cedula==$ttutoracademico["cedulatutoraca"]){
    $identidad=explode("-",$ttutoracademico["cedulatutoraca"]);
    $tipo="consultar";
    $respuesta="si";
    $mensaje="Consulta Exitosa";
    header("location:../vista/v_tutor_academico.php?cedula=".$identidad[1]."&letra_cedula=".$identidad[0]."&p_nombre=".$ttutoracademico["pnombretutoraca"]."&s_nombre=".$ttutoracademico["snombretutoraca"]."&p_apellido=".$ttutoracademico["papellidotutoraca"]."&s_apellido=".$ttutoracademico["sapellidotutoraca"]."&profecion=".$ttutoracademico["nombre_profecion"]."&estado=".$ttutoracademico["estadotutoraca"]."&ntlf=".$ttutoracademico["numerotlftutoraca"]."&correo=".$ttutoracademico["correotutoraca"]."&P_1=".$ttutoracademico["pregunta_1_tutor"]."&P_2=".$ttutoracademico["pregunta_2_tutor"]."&R_1=".$ttutoracademico["respuesta_1_tutor"]."&R_2=".$ttutoracademico["respuesta_2_tutor"]."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
   }
   else{
    $tipo="consultar";
    $respuesta="no";
    $mensaje="Error al Consultar (Error esta cedula no esta registrada)";
    header("location:../vista/v_tutor_academico.php?tipo=". $tipo."&respuesta=". $respuesta."&mensaje=".$mensaje);
   }
}
if(isset($_GET["tutor"])){
    #print "SI";
    $TUTOR=new ttutoracademico();
    $TUTOR->dato_de_busqueda_tutor_academico($_GET["tutor"]);
    $tutor=$TUTOR->consultar_tutor();
    $ttutoracademico=$TUTOR->modificar_consulata_tutor_academico($tutor);
    $cedula=explode("-",$ttutoracademico["cedulatutoraca"]);
    if($_GET["tutor"]==$cedula[1]){
        $busqueda=["mensaje"=>"El Tutor Hacido Encontrado","estado"=>"true"];
        print json_encode($busqueda);
    }
    else{
        $busqueda=["mensaje"=>"El Tutor no Hacido Encontrado","estado"=>"false"];
        print json_encode($busqueda);
    }
}
if(isset($_GET["cedula"])){
    #print "lo he logrado";
    $TUTOR=new ttutoracademico();
    $clave_encriptada=password_hash($_GET["cedula"],PASSWORD_DEFAULT,array("coste"=>12));
    $TUTOR->dato_de_busqueda_tutor_academico($_GET["cedula"]);
    #print $clave_encriptada;
    $TUTOR->reiniciar_clave($clave_encriptada);
    print "Restauración Completada";
}
?>