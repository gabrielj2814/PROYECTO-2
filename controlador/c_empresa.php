<?php
include_once("../modelo/m_tempresa.php");
//print $_POST["rif-empresa"];
$operacion=$_POST["operacion"];
switch($operacion){
    case "registrar":registrar_empresa();break;
    case "consultar":consultar_empresa();break;
    case "actualizar":actualizar_empresa();break;
}

function registrar_empresa(){
    $EMPRESA=new tempresa();
    $rif_empresa=$_POST["codigo_rif"]."-".$_POST["rif-empresa"];
    $EMPRESA->dato_de_busqueda_empresa($rif_empresa);
    $arreglo_empresa=$EMPRESA->buscar_empresa();
    $tempresa=$EMPRESA->modificar_arreglo($arreglo_empresa);
    if($rif_empresa!=$tempresa["rifempresa"]){
        $cordenadas=$_POST["codigo_estado"]."-".$_POST["codigo_municipio"]."-".$_POST["codigo_parroquia"];
        $EMPRESA->setdatos_empresa($rif_empresa,$_POST["nombre-empresa"],$_POST["estado"],$cordenadas,$_POST["direccion-empresa"],$_POST["numerotlf"]);
        $EMPRESA->registrar_empresa();
        $tipo="registrar";
        $respuesta="si";
        $mensaje="Registro Completado";
        header("location:../vista/v_empresa.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="registrar";
        $respuesta="no";
        $mensaje="Error al registrar(Este RIF ya exite)";
        header("location:../vista/v_empresa.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function actualizar_empresa(){
    $EMPRESA=new tempresa(); 
    $rif_empresa=$_POST["codigo_rif"]."-".$_POST["rif-empresa"];  
    $EMPRESA->dato_de_busqueda_empresa($rif_empresa);
    $arreglo_empresa=$EMPRESA->buscar_empresa();
    $tempresa=$EMPRESA->modificar_arreglo($arreglo_empresa);
    if($rif_empresa==$tempresa["rifempresa"]){
        $cordenadas=$_POST["codigo_estado"]."-".$_POST["codigo_municipio"]."-".$_POST["codigo_parroquia"];
        $EMPRESA->actualizar_datos_empresa($rif_empresa,$_POST["nombre-empresa"],$_POST["estado"],$cordenadas,$_POST["direccion-empresa"],$_POST["numerotlf"]);
        $EMPRESA->actualizar_empresa();
        $tipo="actualizar";
        $respuesta="si";
        $mensaje="Actualización Completada";
        header("location:../vista/v_empresa.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="actualizar";
        $respuesta="no";
        $mensaje="Error al Actualizar (este rif no esta registrado)";
        header("location:../vista/v_empresa.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function consultar_empresa(){
    $EMPRESA=new tempresa();
    $rif_empresa=$_POST["codigo_rif"]."-".$_POST["rif-empresa"];
    $EMPRESA->dato_de_busqueda_empresa($rif_empresa);
    $arreglo_empresa=$EMPRESA->buscar_empresa();
    $tempresa=$EMPRESA->modificar_arreglo($arreglo_empresa);
    if($rif_empresa==$tempresa["rifempresa"]){
        $codigo_ubicacion=explode("-",$tempresa["codigo_ubicacion_empresa"]);
        $rif_empresa=explode("-",$tempresa["rifempresa"]);
        $tipo="actualizar";
        $respuesta="si";
        $mensaje="Consulta Completada";
        header("location:../vista/v_empresa.php?rif=".$rif_empresa[1]."&letra_rif=".$rif_empresa[0]."&nombre=".$tempresa["nombreempresa"]."&estado=".$tempresa["estadoempresa"]."&idparroquia=".$codigo_ubicacion[2]."&direccion=".$tempresa["direccionempresa"]."&peticion="."SI"."&codigo_estado=".$codigo_ubicacion[0]."&codigo_municipio=".$codigo_ubicacion[1]."&ntlf_empresa=".$tempresa["numerotlf_empresa"]."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="consultar";
        $respuesta="no";
        $mensaje="Error al Consultar (este rif no esta registrado)";
        header("location:../vista/v_empresa.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}

?>