<?php
//print $_POST["cedula-personal"];
include_once("../modelo/m_tpersonal.php");
if(isset($_POST["operacion"])){
    $operacion=$_POST["operacion"];
    switch($operacion){
        case "registrar":registrar_per();break;
        case "actualizar":actualizar_per();break;
        case "consultar":consultar_per();break;
}
}
function registrar_per(){
    $PERSONAL=new tpersonal();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-personal"];
    $PERSONAL->dato_de_busqueda_personal($cedula);
    $areglo_personal=$PERSONAL->buscar_personal();
    $tpersonal=$PERSONAL->modificar_consulata_personal($areglo_personal);
    $clave_encriptada=password_hash($_POST["cedula-personal"],PASSWORD_DEFAULT,array("coste"=>12));
    if($cedula!=$tpersonal["cedulaper"]){
        $PERSONAL->setdatos_personal($cedula,$_POST["p-nombre-personal-pasantia"],$_POST["s-nombre-personal-pasantia"],$_POST["p-apellido-personal-pasantia"],$_POST["s-apellido-personal-pasantia"],$_POST["estado"],$_POST["estatus-personal"],$clave_encriptada,$_POST["pregunta_1"],$_POST["pregunta_2"],$_POST["respuesta_1"],$_POST["respuesta_2"]);
        $PERSONAL->registrar_personal();
        $tipo="registrar";
        $respuesta="si";
        $mensaje="Registro Completado la contraseña del usuario que acaba de registrar es su cedula";
        header("location:../vista/v_personal.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="registrar";
        $respuesta="no";
        $mensaje="Error al Registrar (Esta cedula ya fue registrada->$cedula)";
        header("location:../vista/v_personal.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
function actualizar_per(){
    $PERSONAL=new tpersonal();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-personal"];
    $PERSONAL->dato_de_busqueda_personal($cedula);
    $areglo_personal=$PERSONAL->buscar_personal();
    $tpersonal=$PERSONAL->modificar_consulata_personal($areglo_personal);
    if($cedula==$tpersonal["cedulaper"]){
        $PERSONAL->actualizar_datos_personal($cedula,$_POST["p-nombre-personal-pasantia"],$_POST["s-nombre-personal-pasantia"],$_POST["p-apellido-personal-pasantia"],$_POST["s-apellido-personal-pasantia"],$_POST["estado"],$_POST["estatus-personal"],$_POST["pregunta_1"],$_POST["pregunta_2"],$_POST["respuesta_1"],$_POST["respuesta_2"]);
        $PERSONAL->actualizar_personal();
        $tipo="actualizar";
        $respuesta="si";
        $mensaje="Actualización Completada";
        header("location:../vista/v_personal.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="actualizar";
        $respuesta="no";
        $mensaje="Error al Actualizar (esta cedula -> $cedula no esta registrada)";
        header("location:../vista/v_personal.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
   
}
function consultar_per(){
    $PERSONAL=new tpersonal();
    $cedula=$_POST["nacionalidad"]."-".$_POST["cedula-personal"];
    $PERSONAL->dato_de_busqueda_personal($cedula);
    $areglo_personal=$PERSONAL->buscar_personal();
    $tpersonal=$PERSONAL->modificar_consulata_personal($areglo_personal);
    $identidad=explode("-",$tpersonal["cedulaper"]);
    if($cedula==$tpersonal["cedulaper"]){
        $tipo="consultar";
        $respuesta="si";
        $mensaje="Consulta Exitosa";
        header("location:../vista/v_personal.php?cedula=".$identidad[1]."&letra_cedula=".$identidad[0]."&p_nombre=".$tpersonal["pnombreper"]."&s_nombre=".$tpersonal["snombreper"]."&p_apellido=".$tpersonal["papellidoper"]."&s_apellido=".$tpersonal["sapellidoper"]."&cargo=".$tpersonal["idestatusper"]."&estado=".$tpersonal["estadoper"]."&P_1=".$tpersonal["pregunta_1_per"]."&P_2=".$tpersonal["pregunta_2_per"]."&R_1=".$tpersonal["respuesta_1_per"]."&R_2=".$tpersonal["respuesta_2_per"]."&tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
    else{
        $tipo="actualizar";
        $respuesta="no";
        $mensaje="Error en la Consulta (esta cedula -> $cedula no esta registrada)";
        header("location:../vista/v_personal.php?tipo=".$tipo."&respuesta=".$respuesta."&mensaje=".$mensaje);
    }
}
if(isset($_GET["cedula"])){
    #print "Peticion completada ".$_GET["cedula"];
    $PERSONAL=new tpersonal();
    $PERSONAL->dato_de_busqueda_personal($_GET["cedula"]);
    $clave_encriptada=password_hash($_GET["cedula"],PASSWORD_DEFAULT,array("coste"=>12));
    $PERSONAL->restaurar_clave($clave_encriptada);
    print "Restauración Completada"; 
}

if(isset($_GET["personal"])){
    #print "hola mundo";
    $PERSONAL=new tpersonal();
    $PERSONAL->dato_de_busqueda_personal($_GET["personal"]);
    $personal=$PERSONAL->consultar_personal();
    $tpersonal=$PERSONAL->modificar_consulata_personal($personal);
    $cedula=explode("-",$tpersonal["cedulaper"]);
    #print $cedula[1];
    if($_GET["personal"]==$cedula[1]){
        $JSON=["mensaje"=>"Personal de Pasantia Encontrado","estado"=>"true"];
        print json_encode($JSON);
    }
    else{
        $JSON=["mensaje"=>"Personal de Pasantia no Encontrado","estado"=>"false"];
        print json_encode($JSON);
    }
}
?>