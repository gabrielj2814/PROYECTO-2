<?php
session_start();
include_once("../modelo/m_tfases.php");
include_once("../modelo/m_testudiante.php");
include_once("../modelo/m_trepresentante.php");
if(isset($_POST["operacion"])){
    $operacion=$_POST["operacion"];
    switch($operacion){
        case 'registrar':registrar_pasante();break;
        case 'actualizar':modificar_pasante();break;
        case 'consultar':consultar_estudiante();break;
        case 'reporte':generar_reporte();break;
}
}
function generar_reporte(){
    include_once("../clases/clss_estudiante.php");
    $CLASS_ESTUDIANTE=new estudiante();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-pasante"];
    if($_POST["cedula-pasante"]!=""){
        $CLASS_ESTUDIANTE->reporte_estudiante($cedula);
    }
    else{
        $CLASS_ESTUDIANTE->listar_todos();
    }

}

function registrar_pasante(){
    $ESTUDIANTE=new testudiante();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-pasante"];
    $ESTUDIANTE->dato_de_busqueda_estudiante($cedula);
    $consulta_estudiante=$ESTUDIANTE->buscar_estudiante();
    $consulta_testudiante=$ESTUDIANTE->modificar_consulta($consulta_estudiante);
    if($cedula!=$consulta_testudiante["cedulaest"]){
        $FASES=new tfases();
        $id_fases=$FASES->registrar_fases($_POST["year"]);
        $encriptar_clave=password_hash($_POST["cedula-pasante"], PASSWORD_DEFAULT, array("coste"=>12));
        $codigo_ubicacion=$_POST["codigo_estado"]."-".$_POST["codigo_municipio"]."-".$_POST["codigo_parroquia"];
        #print $_POST["respuesta_1"]." ".$_POST["respuesta_2"];
        $ESTUDIANTE->setdatos($cedula,$_POST["p-nombre-pasante"],$_POST["s-nombre-pasante"],$_POST["p-apellido-pasante"],$_POST["s-apellido-pasante"],$_POST["fecha-pasante"],$_POST["ntlf-pasante"],$_POST["correo-pasante"],$codigo_ubicacion,$_POST["direccion-pasante"],$_POST["sexo"],$encriptar_clave,$_POST["estado"],$_POST["id-personal"],$id_fases,$_POST["pregunta_1"],$_POST["pregunta_2"],$_POST["respuesta_1"],$_POST["respuesta_2"]);
        $ESTUDIANTE->registrar_estudiante();
        $tipo="registrar";
        $respuesta="si";
        $msj="Registro Completado la contraseña del estudiante que acaba de registrar es su cedula (haga clik en <a href='v_representante.php' class='alert-link'>Registrar Pepresentante</a> para poder terminar el registro)";
        header("location:../vista/v_coordinador.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$msj);
        
    }
    else{
        $tipo="registrar";
        $respuesta="no";
        $msj="Error al registrar ('estudiante ya exitente')";
        header("location:../vista/v_coordinador.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$msj);
    }
}
function modificar_pasante(){
    $ESTUDIANTE=new testudiante();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-pasante"];
    $ESTUDIANTE->dato_de_busqueda_estudiante($cedula);
    $consulta_estudiante=$ESTUDIANTE->buscar_estudiante();
    $consulta_testudiante=$ESTUDIANTE->modificar_consulta($consulta_estudiante);
    if($cedula==$consulta_testudiante["cedulaest"]){
        $codigo_ubicacion=$_POST["codigo_estado"]."-".$_POST["codigo_municipio"]."-".$_POST["codigo_parroquia"];
        $ESTUDIANTE->actualizar_datos($cedula,$_POST["p-nombre-pasante"],$_POST["s-nombre-pasante"],$_POST["p-apellido-pasante"],$_POST["s-apellido-pasante"],$_POST["fecha-pasante"],$_POST["ntlf-pasante"],$_POST["correo-pasante"],$codigo_ubicacion,$_POST["direccion-pasante"],$_POST["sexo"],$_POST["estado"],$_POST["pregunta_1"],$_POST["pregunta_2"],$_POST["respuesta_1"],$_POST["respuesta_2"]);
        $ESTUDIANTE->actualizar_pasante();
        $tipo="actualizar";
        $respuesta="si";
        $msj="Actualizacion Completa";
        header("location:../vista/v_coordinador.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$msj);
    }
    else{
        $tipo="actualizar";
        $respuesta="no";
        $msj="Error al registrar ('Estudiante no encontrado')";
        header("location:../vista/v_coordinador.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$msj);
    }
}
function consultar_estudiante(){
    $ESTUDIANTE=new testudiante();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-pasante"];
    $ESTUDIANTE->dato_de_busqueda_estudiante($cedula);
    $consulta_estudiante=$ESTUDIANTE->buscar_estudiante();
    $consulta_testudiante=$ESTUDIANTE->modificar_consulta($consulta_estudiante);
    if($cedula==$consulta_testudiante["cedulaest"]){
        $cordenadas=explode("-",$consulta_testudiante["codigo_ubicacion"]);
        $identidad=explode("-",$consulta_testudiante["cedulaest"]);
        $identidad_representante=explode("-",$consulta_testudiante["cedularepre"]);
        $tipo="consulta";
        $respuesta="si";
        $msj="Consulta Exitosa";
        header("location:../vista/v_coordinador.php?cedula=".$identidad[1]."&letra_cedula=".$identidad[0]."&p_nombre=".$consulta_testudiante["pnombreest"]."&s_nombre=".$consulta_testudiante["snombreest"]."&p_apellido=".$consulta_testudiante["papellidoest"]."&s_apellido=".$consulta_testudiante["sapellidoest"]."&fecha=".$consulta_testudiante["fechanacimientos"]."&sexo=".$consulta_testudiante["sexoest"]."&estado=".$consulta_testudiante["estadoest"]."&correo=".$consulta_testudiante["correoestudiante"]."&ntlf=".$consulta_testudiante["ntlfestudiante"]."&id_estado=". $cordenadas[0]."&id_municipio=". $cordenadas[1]."&idparroquia=".$cordenadas[2]."&direccion=".$consulta_testudiante["direccionpasante"]."&P_1=".$consulta_testudiante["pregunta_1_est"]."&P_2=".$consulta_testudiante["pregunta_2_est"]."&R_1=".$consulta_testudiante["respuesta_1_est"]."&R_2=".$consulta_testudiante["respuesta_2_est"]."&peticion="."SI"."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$msj);    
    }
    else{
        $tipo="consulta";
        $respuesta="no";
        $msj="Error en la busqueda cedula no encontrada";
        header("location:../vista/v_coordinador.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$msj);
    }
}
if(isset($_GET["cedula"])){
    print "Consulta lograda".$_GET["cedula"];
    $ESTUDIANTE=new testudiante();
    $ESTUDIANTE->dato_de_busqueda_estudiante($_GET["cedula"]);
    $clave_encriptada=password_hash($_GET["cedula"],PASSWORD_DEFAULT,array("coste"=>12));
    $ESTUDIANTE->restaurar_clave($clave_encriptada);
    print "Restauracion Completada";
}

if(isset($_GET["estudiante"])){
    #print "hola mundo ajax";
    $ESTUDIANTE=new testudiante();
    $ESTUDIANTE->dato_de_busqueda_estudiante($_GET["estudiante"]);
    $estudiante=$ESTUDIANTE->consultar_estudiante();
    $testudiante=$ESTUDIANTE->modificar_consulta($estudiante);
    $cedula=explode("-",$testudiante["cedulaest"]);
    #print $testudiante["cedulaest"];
    if($_GET["estudiante"]==$cedula[1]){
        $JSON=["mensaje"=>"El estudiante fue Encontrado","estado"=>"true"];
        print json_encode($JSON);
    }
    else{
        $JSON=["mensaje"=>"El estudiante no fue Encontrado","estado"=>"false"];
        print json_encode($JSON);
    }
}
?>