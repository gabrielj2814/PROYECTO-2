<?php
//print $_POST["nombre-mencion"];
include_once("../modelo/m_tmencion.php");
$operacion=$_POST["operacion"];
switch($operacion){
    case "registrar":registrar_men();break;
    case "actualizar":actualizar_men();break;
    case "consultar":consultar_men();break;
}

if(isset($_GET["generar"])){
    $codigo=generar();
    header("location:../vista/v_mencion.php?codigo_mencion=".$codigo);
}
function generar(){
    $codigo_mencion="M-";
    $MENCION=new tmencion();
    $MENCION->dato_de_busqueda_mencion($codigo_mencion);
    $CODIGO=$MENCION->consultar_codigo_mencion();
    $contador=0;
    while($codigo_m=mysqli_fetch_array($CODIGO)){
        $contador+=1;
    }
    if($contador==0){
        return "M"."-"."1";
    }
    else{
        return "M"."-".($contador+1);
    }
}
function registrar_men(){
    $MENCION=new tmencion();
    $tipo="";
    $respuesta="";
    $mensaje="";
    $MENCION->dato_de_busqueda_mencion($_POST["id-mencion"]);
    $areglo_mencion=$MENCION->consultar_mencion();
    $tmencion=$MENCION->enviar_areglo($areglo_mencion);
    if($_POST["id-mencion"]!=$tmencion["idmencion"]){
        $MENCION->setdatos_mencion($_POST["id-mencion"],$_POST["nombre-mencion"],$_POST["descripcion-mencion"],$_POST["estado"]);
        $MENCION->registrar_mencion();
        $tipo="registrar";
        $respuesta="si";
        $mensaje="Registro Completado";
        $codigo=generar();
        header("location:../vista/v_mencion.php?codigo_mencion=".$codigo."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="registrar";
        $respuesta="no";
        $mensaje="error al registrar (Este codigo ya exite)";
        $codigo=generar();
        header("location:../vista/v_mencion.php?codigo_mencion=".$codigo."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function actualizar_men(){
    $MENCION=new tmencion();
    $tipo="";
    $respuesta="";
    $mensaje="";
    $MENCION->dato_de_busqueda_mencion($_POST["id-mencion"]);
    $areglo_mencion=$MENCION->consultar_mencion();
    $tmencion=$MENCION->enviar_areglo($areglo_mencion);
    if($_POST["id-mencion"]==$tmencion["idmencion"]){
        $MENCION->actualizar_datos_mencion($_POST["id-mencion"],$_POST["nombre-mencion"],$_POST["descripcion-mencion"],$_POST["estado"]);
        $MENCION->actualizar_mencion();
        $tipo="actualizar";
        $respuesta="si";
        $mensaje="Actualización Completada";
        $codigo=generar();
        header("location:../vista/v_mencion.php?codigo_mencion=".$codigo."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="actualizar";
        $respuesta="no";
        $mensaje="error al actualizar (Este codigo no exite)";
        $codigo=generar();
        header("location:../vista/v_mencion.php?codigo_mencion=".$codigo."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function consultar_men(){
    $MENCION=new tmencion();
    $MENCION->dato_de_busqueda_mencion($_POST["cod_mencion"]);
    $areglo_mencion=$MENCION->consultar_mencion();
    $tmencion=$MENCION->enviar_areglo($areglo_mencion);
   if($_POST["cod_mencion"]==$tmencion["idmencion"]){
    $tipo="consultar";
    $respuesta="si";
    $mensaje="Consulta Exitosa";
    header("location:../vista/v_mencion.php?id=".$tmencion["idmencion"]."&nombre=".$tmencion["nombremencion"]."&descripcion=".$tmencion["descripcionmencion"]."&estado=".$tmencion["estadomencion"]."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
   }
   else{
    $tipo="consultar";
    $respuesta="no";
    $mensaje="Error en la consultae (este codigo no esta registrado)";
    $codigo=generar();
    header("location:../vista/v_mencion.php?codigo_mencion=".$codigo."&nombre="."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
   }
}
?>