<?php
#print $_POST["operacion"];
include_once("../modelo/m_tfases.php");
switch($_POST["operacion"]){
    case 'actualizar':actualizar_fases();break;
    case 'consultar':consultar_fases();break;
}
function actualizar_fases(){
    $tipo="";
    $mensaje="";
    $respuesta="";
    $FASES=new tfases();
    $FASES->dato_de_busqueda_fase($_POST["id_fase"]);
    $fase=$FASES->consultar_fase();
    $tfase=$FASES->enviar_areglo($fase);
    if($_POST["id_fase"]==$tfase["id_fase"]){
        $FASES->set_datos($_POST["id_fase"],$_POST["fecha_fase_I"],$_POST["combo_estad_fase_I"],$_POST["fecha_fase_II"],$_POST["combo_estad_fase_II"],$_POST["fecha_fase_III"]);
        $FASES->actualizar_fase();
        $tipo="actualizar";
        $mensaje="Actualizacion Completada";
        $respuesta="si";
        header("location:../vista/v_fases.php?respuesta=".$respuesta."&tipo=".$tipo."&mensaje=".$mensaje);
    }
    else{
        $tipo="actualizar";
        $mensaje="Error en la Actualizacion (este Codigo de Fases no exite ne la base de datos)";
        $respuesta="no";
        header("location:../vista/v_fases.php?respuesta=".$respuesta."&tipo=".$tipo."&mensaje=".$mensaje);

    }
}

function consultar_fases(){
    $tipo="";
    $mensaje="";
    $respuesta="";
    $FASES=new tfases();
    $FASES->dato_de_busqueda_fase($_POST["id_fase"]);
    $fase=$FASES->consultar_fase();
    $tfase=$FASES->enviar_areglo($fase);
    if($_POST["id_fase"]==$tfase["id_fase"]){
        $tipo="consultar";
        $mensaje="Consulta Exitosa";
        $respuesta="si";
        $fecha_1_limite=explode("-",$tfase["fase_uno_minima"]);
        $fecha_2_limite=explode("-",$tfase["fase_dos_minima"]);
        $fecha_3_limite=explode("-",$tfase["fase_tres_minima"]);
        $fecha_1_limite_listo=$fecha_1_limite[2]."-".$fecha_1_limite[1]."-".$fecha_1_limite[0];
        $fecha_2_limite_listo=$fecha_2_limite[2]."-".$fecha_2_limite[1]."-".$fecha_2_limite[0];
        $fecha_3_limite_listo=$fecha_3_limite[2]."-".$fecha_3_limite[1]."-".$fecha_3_limite[0];
        header("location:../vista/v_fases.php?respuesta=".$respuesta."&tipo=".$tipo."&mensaje=".$mensaje."&id_fase=".$tfase["id_fase"]."&fase_1_inicio=".$tfase["fase_uno"]."&fase_1_fin=".$fecha_1_limite_listo."&fase_2_inicio=".$tfase["fase_dos"]."&fase_2_fin=".$fecha_2_limite_listo."&fase_3_inicio=".$tfase["fase_tres"]."&fase_3_fin=".$fecha_3_limite_listo."&estado_fase_1=".$tfase["entrega_fase_uno"]."&estado_fase_2=".$tfase["entrega_fase_dos"]."&estado_fase_3=".$tfase["estado_fase_tres"]);
    }
    else{
        $tipo="consultar";
        $mensaje="Error en la Consulta (este Codigo de Fases no exite ne la base de datos)";
        $respuesta="no";
        header("location:../vista/v_fases.php?respuesta=".$respuesta."&tipo=".$tipo."&mensaje=".$mensaje);

    }
}

if(isset($_GET["id_fase"])){
    $FASES=new tfases();
    $FASES->dato_de_busqueda_fase($_GET["id_fase"]);
    $fase=$FASES->consultar_fase();
    $tfase=$FASES->enviar_areglo($fase);
    $fecha_1_limite=explode("-",$tfase["fase_uno_minima"]);
    $fecha_2_limite=explode("-",$tfase["fase_dos_minima"]);
    $fecha_3_limite=explode("-",$tfase["fase_tres_minima"]);
    $fecha_1_limite_listo=$fecha_1_limite[2]."-".$fecha_1_limite[1]."-".$fecha_1_limite[0];
    $fecha_2_limite_listo=$fecha_2_limite[2]."-".$fecha_2_limite[1]."-".$fecha_2_limite[0];
    $fecha_3_limite_listo=$fecha_3_limite[2]."-".$fecha_3_limite[1]."-".$fecha_3_limite[0];
    header("location:../vista/v_fases.php?id_fase=".$tfase["id_fase"]."&fase_1_inicio=".$tfase["fase_uno"]."&fase_1_fin=".$fecha_1_limite_listo."&fase_2_inicio=".$tfase["fase_dos"]."&fase_2_fin=".$fecha_2_limite_listo."&fase_3_inicio=".$tfase["fase_tres"]."&fase_3_fin=".$fecha_3_limite_listo."&estado_fase_1=".$tfase["entrega_fase_uno"]."&estado_fase_2=".$tfase["entrega_fase_dos"]."&estado_fase_3=".$tfase["estado_fase_tres"]);
}
?>