<?php
include_once("../modelo/m_trepresentante.php");
//print $_POST["cedula-representante"];
$operacion=$_POST["operacion"];
switch($operacion){
    case "registrar":registrar_repre();break;
    case "actualizar":actualizar_repre();break;
    case "consultar":consultar_repre();break;
}
function registrar_repre(){
    $REPRESENTANTE=new trepresentante();
    $tipo="";
    $respuesta="";
    $mensaje="";
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-representante"];
    $REPRESENTANTE->dato_de_busqueda_representante($cedula);
    $areglo_representante=$REPRESENTANTE->consultar_representate();
    $trepresentante=$REPRESENTANTE->enviar_areglo($areglo_representante);
    if($cedula!=$trepresentante["cedularepre"]){
        $codigo_ubicacion=$_POST["codigo_estado"]."-".$_POST["codigo_municipio"]."-".$_POST["codigo_parroquia"];
        $REPRESENTANTE->setdatos_representantes($cedula,$_POST["p-nombre-representante"],$_POST["s-nombre-representante"],$_POST["p-apellido-representante"],$_POST["s-apellido-representante"],$_POST["correo-representante"],$_POST["ntlf-representante"],$_POST["estado"],$codigo_ubicacion,$_POST["direccion-representante"],$_POST["parentesco"]);
        $REPRESENTANTE->registrar_representante();
        $tipo="registrar";
        $respuesta="si";
        $mensaje="Registro Completado";
        header("location:../vista/v_representante.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="registrar";
        $respuesta="no";
        $mensaje="Error al Registrar (este Cedula ya Exite)";
        header("location:../vista/v_representante.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function actualizar_repre(){
    $REPRESENTANTE=new trepresentante();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-representante"];
    $REPRESENTANTE->dato_de_busqueda_representante($cedula);
    $areglo_representante=$REPRESENTANTE->consultar_representate();
    $trepresentante=$REPRESENTANTE->enviar_areglo($areglo_representante);
    if($cedula==$trepresentante["cedularepre"]){
        $codigo_ubicacion=$_POST["codigo_estado"]."-".$_POST["codigo_municipio"]."-".$_POST["codigo_parroquia"];
        $REPRESENTANTE->setdatos_representantes($cedula,$_POST["p-nombre-representante"],$_POST["s-nombre-representante"],$_POST["p-apellido-representante"],$_POST["s-apellido-representante"],$_POST["correo-representante"],$_POST["ntlf-representante"],$_POST["estado"],$codigo_ubicacion,$_POST["direccion-representante"],$_POST["parentesco"]);
        $REPRESENTANTE->actualizar_representante();
        $tipo="actualizar";
        $respuesta="si";
        $mensaje="Actualización Completada";
        header("location:../vista/v_representante.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="actualizar";
        $respuesta="no";
        $mensaje="Error al Actualizar (Esta cedula no esta registrada)";
        header("location:../vista/v_representante.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function consultar_repre(){
    $REPRESENTANTE=new trepresentante();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-representante"];
    $REPRESENTANTE->dato_de_busqueda_representante($cedula);
    $areglo_representante=$REPRESENTANTE->consultar_representate();
    $trepresentante=$REPRESENTANTE->enviar_areglo($areglo_representante);
    if($cedula==$trepresentante["cedularepre"]){
        $codigo=explode("-",$trepresentante["codigo_ubicacion_representante"]);
        $identidad=explode("-",$trepresentante["cedularepre"]);
        $tipo="consultar";
        $respuesta="si";
        $mensaje="Consulta Exitosa";
        header("location:../vista/v_representante.php?cedula=".$identidad[1]."&letra_cedula=".$identidad[0]."&p_nombre=".$trepresentante["pnombrerepre"]."&s_nombre=".$trepresentante["snombrerepre"]."&p_apellido=".$trepresentante["papellidorepre"]."&s_apellido=".$trepresentante["sapellidorepre"]."&correo=".$trepresentante["correorepre"]."&numerotlf=".$trepresentante["numerotlfrepre"]."&id_estado=".$codigo[0]."&id_municipio=".$codigo[1]."&idparroquia=".$codigo[2]."&direccion=".$trepresentante["direccionrepresentante"]."&estado=".$trepresentante["estadorepresentante"]."&parentesco=".$trepresentante["parentesco"]."&peticion="."SI"."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="consultar";
        $respuesta="no";
        $mensaje="Error al Consultar (esta cedula no esta registrada)";
        header("location:../vista/v_representante.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
?>