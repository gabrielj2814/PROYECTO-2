<?php
//print $_POST["operacion"];
include_once("../modelo/m_testudiante.php");
include_once("../modelo/m_tmencion.php");
include_once("../modelo/m_ttutoracademico.php");
include_once("../modelo/m_tlistapasantes.php");
include_once("../modelo/m_tempresa.php");
include_once("../modelo/m_tfases.php");
include_once("../modelo/m_trepresentante.php");
#-------Generar Codigo de la Pasantia------------
if(isset($_GET["generar"])){
    $codigo=generar();
    header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo);
}
function generar(){
    $hoy = getdate();
    $fecha=$hoy["year"]."-".$hoy["mon"];
    $PASANTE=new tlistapasantes();
    $PASANTE->dato_de_busque_pasante($fecha);
    $codigo=$PASANTE->consultar_codigos();
    $contador=0;
    while($codigo_pasante=mysqli_fetch_array($codigo)){
        $contador+=1;
    }
    if($contador==0){
        return $fecha."-"."1";
    }
    else{
        return $fecha."-".($contador+1);
    }
}
#------peticiones por el usuario------------
if(isset($_POST["operacion"])){
    switch($_POST["operacion"]){
        case 'registrar':registrar_transaccion();break;
        case 'actualizar':actualizar_transaccion();break;
        case 'consultar':consultar_transaccion();break;
        case 'guardar':guardar();break;
    }
}

function registrar_transaccion(){
    $PASANTE=new tlistapasantes();
    $ESTUDIANTE=new testudiante();
    $FASE=new tfases();
    $respuesta_pasante=false;
    $cedula_estudiante=$_POST["nacionalidad_pasante"]."-".$_POST["cedula_pasante"];
    $PASANTE->dato_de_busque_pasante_x_cedula($cedula_estudiante);
    $pasante=$PASANTE->consultar_pasante_cedula(); 
    while($tpasente=mysqli_fetch_array($pasante)){
        if($tpasente["estado_pasante"]=="culmino"){
            $respuesta_pasante=true;
        }
    }
    if($respuesta_pasante){
        $codigo=generar();
        $respuesta_peticion="no";
        $mensaje="Error al Registrar (Por que ya el estudiante culimino su pasantia con exito)";
        $peticion="registrar";
        header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);
    }
    else{
        if($respuesta_pasante==false){
            $PASANTE->dato_de_busque_pasante($_POST["id_pasante"]);
            $pasante=$PASANTE->consulta_pasante();
            $respuesta="";
            $mensaje="";
            $peticion="";
            $tlistapasantes=$PASANTE->enviar_arreglo($pasante);
            if($tlistapasantes["idpasante"]!=$_POST["id_pasante"]){
                $cedula_pasante=$_POST["nacionalidad_pasante"]."-".$_POST["cedula_pasante"];
                $ESTUDIANTE->dato_de_busqueda_estudiante($cedula_pasante);
                $estudiantes=$ESTUDIANTE->buscar_estudiante();
                $testudiante=$ESTUDIANTE->modificar_consulta($estudiantes);
                if($cedula_pasante==$testudiante["cedulaest"]){# CEDUAL DEL PASANTE 
                    $REPRESENTANTE=new trepresentante();
                    $cedula_representante=$_POST["nacionalidad_representante"]."-".$_POST["cedula_representante"];
                    $REPRESENTANTE->dato_de_busqueda_representante($cedula_representante);
                    $representante=$REPRESENTANTE->consultar_representate();
                    $trepresentante=mysqli_fetch_array($representante);

                    if($cedula_representante==$trepresentante["cedularepre"]){
                        $FASE->dato_de_busqueda_fase($testudiante["id_fase"]);
                        $fase=$FASE->consultar_fase();
                        $tfase=$FASE->enviar_areglo($fase);
                        if($tfase["entrega_fase_uno"]=="si" && $tfase["entrega_fase_dos"]=="si"){
                            $cedula_tutor_e=$_POST["nacionalidad_tutor_e"]."-".$_POST["cedula_tutor_empresarilal"];
                            #print "hola pariente".$cedula_representante;
                            $PASANTE->setdatos_pasante($_POST["id_pasante"],$cedula_pasante,$_POST["combo_mencion"],$_POST["combo_cedula_tutor_academico"],$_POST["combo_rif_empresa"],$cedula_tutor_e,$_POST["p_nombre_tutor_empresarial"],$_POST["s_nombre_tutor_empresarial"],$_POST["p_apellido_tutor_empresarial"],$_POST["s_apellido_tutor_empresarial"],$_POST["ntlf_tutor_empresarial"],$_POST["correo_tutor_empresarial"],$_POST["cargo_tutor_empresarial"],$_POST["fecha_formulario"],$cedula_representante);
                            $id_pasante=$PASANTE->registrar_pasantia($testudiante["id_fase"]);
                            $codigo=generar();
                            $respuesta_peticion="si";
                            $mensaje="Registro Completado";
                            $peticion="registrar";
                            header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);
                        }
                        else{
                            $codigo=generar();
                            $respuesta_peticion="no";
                            $mensaje="Error al Registrar (este estudiante no no cumple con los requerimientos minimos para cursar una pasantia)";
                            $peticion="registrar";
                            header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);    
                        
                        }
                    }
                    else{
                        $codigo=generar();
                        $respuesta_peticion="no";
                        $mensaje="Error al Registrar (esta cedula no esta en la base de datos haga Click en <a href='v_representante.php' class='alert-link'>Registrar Representante</a> para poder realizar este registro)";
                        $peticion="registrar";
                        header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);    
                    
                    }
                }
                else{
                    $codigo=generar();
                    $respuesta_peticion="no";
                    $mensaje="Error al Registrar (esta cedula no esta en la base de datos haga Click <a href='v_coordinador.php' class='alert-link'>Registrar Estudiante</a> para poder realizar este registro)";
                    $peticion="registrar";
                    header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);    
                }
            }
            else{
                $codigo=generar();
                $respuesta_peticion="no";
                $mensaje="Error al Registrar (Codigo ya Exitente)";
                $peticion="registrar";
                header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);
            }
        }
    }
    
}

function actualizar_transaccion(){
    $PASANTE=new tlistapasantes();
    $ESTUDIANTE=new testudiante();
    $FASE=new tfases();
    $respuesta="";
    $mensaje="";
    $peticion="";
    $cedula_pasante=$_POST["nacionalidad_pasante"]."-".$_POST["cedula_pasante"];
    $ESTUDIANTE->dato_de_busqueda_estudiante($cedula_pasante);
    $estudiantes=$ESTUDIANTE->buscar_estudiante();
    $testudiante=$ESTUDIANTE->modificar_consulta($estudiantes);
    if($cedula_pasante==$testudiante["cedulaest"]){
        $FASE->dato_de_busqueda_fase($testudiante["id_fase"]);
        $fase=$FASE->consultar_fase();
        $tfase=$FASE->enviar_areglo($fase);
        if($tfase["entrega_fase_uno"]=="si" && $tfase["entrega_fase_dos"]=="si"){
            $cedula_tutor_e=$_POST["nacionalidad_tutor_e"]."-".$_POST["cedula_tutor_empresarilal"];
            $cedula_representante=$_POST["nacionalidad_representante"]."-".$_POST["cedula_representante"];
            $PASANTE->actualizar_datos_pasante($_POST["id_pasante"],$_POST["combo_mencion"],$_POST["combo_cedula_tutor_academico"],$_POST["combo_rif_empresa"],$cedula_tutor_e,$_POST["p_nombre_tutor_empresarial"],$_POST["s_nombre_tutor_empresarial"],$_POST["p_apellido_tutor_empresarial"],$_POST["s_apellido_tutor_empresarial"],$_POST["ntlf_tutor_empresarial"],$_POST["correo_tutor_empresarial"],$_POST["cargo_tutor_empresarial"],$cedula_representante,$_POST["fecha_formulario"]);
            $PASANTE->actualizar_pasante();
            $codigo=generar();
            $respuesta_peticion="si";
            $mensaje="Actualizar Completada";
            $peticion="actualizar";
            header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);
        }
        else{
            $codigo=generar();
            $respuesta_peticion="no";
            $mensaje="Error al Actualizar (este estudiante no no cumple con los requerimientos minimos para cursar una pasantia)";
            $peticion="actualizar";
            header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);    
        }
    }
    else{
        $codigo=generar();
        $respuesta_peticion="no";
        $mensaje="Error al Actualizar (Esta cedula no esta registrada o error al escribir la cedula)";
        $peticion="actualizar";
        header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);
    }
   
}

function consultar_transaccion(){
    $PASANTE=new tlistapasantes();
    $PASANTE->dato_de_busque_pasante($_POST["codigo_pasante"]);
    $pasante=$PASANTE->consulta_pasante();
    $tlistapasantes=$PASANTE->enviar_arreglo($pasante);
    if($tlistapasantes["idpasante"]==$_POST["codigo_pasante"]){
        $identida_estudiante=explode("-",$tlistapasantes["cedulaest"]);
        $identidad_tutor_e=explode("-",$tlistapasantes["cedula_tutor_empresarial"]);
        $fecha=explode("-",$tlistapasantes["fecha_documento"]);
        $fecha_covertida=$fecha[2]."-".$fecha[1]."-".$fecha[0];
        $cedula_explotada_representante=explode("-",$tlistapasantes["cedularepre"]);
        $letra_nacionalidad_representante=$cedula_explotada_representante[0];
        $cedula_representante=$cedula_explotada_representante[1];
        $respuesta_peticion="si";
        $mensaje="Consulta Exitosa";
        $peticion="consultar";
        header("location:../vista/v_transaccion_pasante.php?id_pasante=".$tlistapasantes["idpasante"]."&cedula_pasante=".$identida_estudiante[1]."&letra_cedula_pasan=".$identida_estudiante[0]."&id_mencion=".$tlistapasantes["idmencion"]."&cedula_tutor_a=".$tlistapasantes["cedulatutoraca"]."&rif_empresa=".$tlistapasantes["rifempresa"]."&cedula_tutor_e=".$identidad_tutor_e[1]."&letra_cedula_tutor_e=".$identidad_tutor_e[0]."&p_nombre_tutor_e=".$tlistapasantes["p_nombre_tutor_empresarial"]."&s_nombre_tutor_e=".$tlistapasantes["s_nombre_tutor_empresarial"]."&p_apellido_tutor_e=".$tlistapasantes["p_apellido_tutor_empresarial"]."&s_apellido_tutor_e=".$tlistapasantes["s_apellido_tutor_empresarial"]."&correo_tutor_e=".$tlistapasantes["correo_tutor_empresarial"]."&numerotlf_tutor_e=".$tlistapasantes["numerotlf_tutor_empresarial"]."&cargo_tutor_e=".$tlistapasantes["cargo_tutor_empresarial"]."&fecha_documento=".$fecha_covertida."&estado_pasante=".$tlistapasantes["estado_pasante"]."&letra_nacionalidad_representante=".$letra_nacionalidad_representante."&cedula_representante=".$cedula_representante."&respuesta="."SI"."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);
    }
    else{
        $codigo=generar();
        $respuesta_peticion="no";
        $mensaje="Error al Consultar (Esta codigo no esta registrado o error al escribir al codigo)";
        $peticion="consultar";
        header("location:../vista/v_transaccion_pasante.php?codigo_pasantia=".$codigo."&respuesta_peticion=".$respuesta_peticion."&peticion=".$peticion."&mensaje=".$mensaje);
    
    }
}

function guardar(){
    $PASANTE=new tlistapasantes();
    $FASE=new tfases();
    $ESTUDIANTE=new testudiante();
    $PASANTE->dato_de_busque_pasante($_POST["codigo_pasante"]);
    $pasante=$PASANTE->consulta_pasante();
    $tlistapasantes=$PASANTE->enviar_arreglo($pasante);
    $cedula_pasante=$tlistapasantes["cedulaest"];
    $ESTUDIANTE->dato_de_busqueda_estudiante($cedula_pasante);
    $estudiantes=$ESTUDIANTE->buscar_estudiante();
    $testudiante=$ESTUDIANTE->modificar_consulta($estudiantes);
    $codigo_fase=$testudiante["id_fase"];
    if($_POST["nota"]=="culmino"){
        $FASE->dato_de_busqueda_fase($testudiante["id_fase"]);
        $fase=$FASE->consultar_fase();
        $tfase=mysqli_fetch_array($fase);
        $guardar_nota=$tfase["entrega_fase_uno"]."-".$tfase["entrega_fase_dos"]."-"."culmino";
        if($tfase["historial"]=="vacio"){
            $FASE->cerrar_fase_3($codigo_fase,"culmino",$guardar_nota."#");
            #print $guardar_nota;
        }
        else{
            $guardar_nota=$tfase["historial"].$guardar_nota."#";
            $FASE->cerrar_fase_3($codigo_fase,"culmino",$guardar_nota);
        }
       
    }
   else{
        $FASE->dato_de_busqueda_fase($testudiante["id_fase"]);
        $fase=$FASE->consultar_fase();
        $tfase=mysqli_fetch_array($fase);
        $guardar_nota=$tfase["entrega_fase_uno"]."-".$tfase["entrega_fase_dos"]."-"."no_culmino";
        if($tfase["historial"]=="vacio"){
            $FASE->cerrar_fase_3($codigo_fase,"no_culmino",$guardar_nota."#");
            #print $guardar_nota;
        }
        else{
            $guardar_nota=$tfase["historial"].$guardar_nota."#";
            $FASE->cerrar_fase_3($codigo_fase,"no_culmino",$guardar_nota);
        }
   }
   $PASANTE->cerrar_pasantia($_POST["codigo_pasante"],$_POST["nota"],$_POST["observacion_pasante"]);
   header("location:../vista/v_cerrar_pasantia.php");
}
    

#----Peticiones con AJAX------------------------------------------
if(isset($_GET["cedula_pasante"])){
    $ESTUDIANTE=new testudiante();
    $ESTUDIANTE->dato_de_busqueda_estudiante($_GET["cedula_pasante"]);
    $estudiantes=$ESTUDIANTE->consultar_estudiante();
    $testudiante=mysqli_fetch_array($estudiantes);
    $FASE=new tfases();
    $FASE->dato_de_busqueda_fase($testudiante["id_fase"]);
    $fase=$FASE->consultar_fase();
    $tfases=$FASE->enviar_areglo($fase);
    $fecha_inicio_explotada=explode("-",$tfases["fase_tres"]);
    $fecha_fin_explotada=explode("-",$tfases["fase_tres_minima"]);
    $fecha_inicio=$fecha_inicio_explotada[2]."-".$fecha_inicio_explotada[1]."-".$fecha_inicio_explotada[0];
    $fecha_final=$fecha_fin_explotada[2]."-".$fecha_fin_explotada[1]."-".$fecha_fin_explotada[0];
    $fecha=$fecha_inicio."/".$fecha_final;
    if($_GET["cedula_pasante"]==$testudiante["cedulaest"]){
        $mensaje=["nombre"=>$testudiante["pnombreest"]." ".$testudiante["snombreest"]." ".$testudiante["papellidoest"]." ".$testudiante["sapellidoest"],"fecha"=>$fecha,"estado"=>"true"];
        print json_encode($mensaje);
    }
    else{
        $mensaje=["mensaje"=>"el estudiante no ha sido encontrado","estado"=>"false"];
        print json_encode($mensaje);
    }
}

if(isset($_GET["cedula_representante"])){
    #print "hola";
    $REPRESENTANTE=new trepresentante();
    $REPRESENTANTE->dato_de_busqueda_representante($_GET["cedula_representante"]);
    $representante=$REPRESENTANTE->consultar_representate();
    $trepresentante=$REPRESENTANTE->enviar_areglo($representante);
    if($_GET["cedula_representante"]==$trepresentante["cedularepre"]){
        #print "encontrado";
        $mensaje=["nombre"=>$trepresentante["pnombrerepre"]." ".$trepresentante["snombrerepre"]." ".$trepresentante["papellidorepre"]." ".$trepresentante["sapellidorepre"],"parentesco"=>$trepresentante["parentesco"],"estado"=>"true"];
        print json_encode($mensaje);
    }
    else{
        #print "no encontrado";
        $mensaje=["mensaje"=>"el representante no ha sido encontrado","estado"=>"false"];
        print json_encode($mensaje);

    }
}

if(isset($_GET["rif_empresa"])){
    $EMPRESA=new tempresa();
    $EMPRESA->dato_de_busqueda_empresa($_GET["rif_empresa"]);
    $empresa=$EMPRESA->buscar_empresa();
    $tempresa=$EMPRESA->modificar_arreglo($empresa);
    print json_encode($tempresa);

}

if(isset($_GET["cedula_tutor_academco"])){
    $TUTOR_ACADEMICO=new ttutoracademico();
    $TUTOR_ACADEMICO->dato_de_busqueda_tutor_academico($_GET["cedula_tutor_academco"]);
    $tutor_academico=$TUTOR_ACADEMICO->buscar_tutor_academico();
    $ttutoracademico=$TUTOR_ACADEMICO->modificar_consulata_tutor_academico($tutor_academico);
    print json_encode($ttutoracademico);
}
if(isset($_GET["cedula_estudiante"])){
#print "hola pasante ->".$_GET["cedula_estudiante"];
    $ESTUDIANTE=new testudiante();
    $PASANTE=new tlistapasantes();
    $FASE=new tfases();
    $PASANTE->dato_de_busque_pasante_x_cedula($_GET["cedula_estudiante"]);
    $pasante=$PASANTE->consultar_pasante_cedula_ajax();
    $intentos_pasante=0;
    while($tpasante=mysqli_fetch_array($pasante)){
        $intentos_pasante+=1;
    }
    $ESTUDIANTE->dato_de_busqueda_estudiante($_GET["cedula_estudiante"]);
    $estudiante=$ESTUDIANTE->consultar_estudiante();
    $testudiante=mysqli_fetch_array($estudiante);
    $FASE->dato_de_busqueda_fase($testudiante["id_fase"]);
    $fase=$FASE->consultar_fase();
    $tfase=mysqli_fetch_array($fase);
    $notas=explode("#", $tfase["historial"]);
    $ultimo=sizeof($notas);
    $total_notas=$ultimo-1;
    $ultima_nota=$notas[$ultimo-2];
    if($intentos_pasante==$total_notas){
        #print "se puede hacer una reanudacion";
        $nota=explode("-",$ultima_nota);
        if($nota[2]=="no_culmino"){
            $json=["mensaje"=>"Puedes Reanudad Tu Pasantia","estado"=>"true","id_fase"=>$testudiante["id_fase"]];
            print json_encode($json);
        }
        else{
            $json=["mensaje"=>"No Puedes Reanudad Tu Pasantia Por Que ya la Culminaste","estado"=>"false"];
            print json_encode($json);
        }
    }
}

if(isset($_GET["id_fases"])){
    $id=$_GET["id_fases"];
    $FASE=new tfases();
    $FASE->reanudar_fases($id);

}



#----------MOSTRAR FORMULARIO CERRAR PASANTIA---------------------------------
if(isset($_GET["show"])){
    $ESTUDIANTE=new testudiante();
    $PASANTE=new tlistapasantes();
    $MENCION=new tmencion();
    $EMPRESA=new tempresa();
    $PASANTE->dato_de_busque_pasante($_GET["CODIGO"]);
    $pasante=$PASANTE->consulta_pasante();
    $tlistapasantes=$PASANTE->enviar_arreglo($pasante);
    $nombre_tutor_empresarial=$tlistapasantes["p_nombre_tutor_empresarial"]."&s_nombre_tutor_e=".$tlistapasantes["s_nombre_tutor_empresarial"]."&p_apellido_tutor_e=".$tlistapasantes["p_apellido_tutor_empresarial"]."&s_apellido_tutor_e=".$tlistapasantes["s_apellido_tutor_empresarial"];
    ##############################################
    $ESTUDIANTE->dato_de_busqueda_estudiante($tlistapasantes["cedulaest"]);
    $consulta=$ESTUDIANTE->buscar_estudiante();
    $testudiante=mysqli_fetch_array($consulta);
    $nombre_estudiante=$testudiante["pnombreest"]." ".$testudiante["snombreest"]." ".$testudiante["papellidoest"]." ".$testudiante["sapellidoest"];
    ###############################################
    $MENCION->dato_de_busqueda_mencion($tlistapasantes["idmencion"]);
    $mencion=$MENCION->consultar_mencion();
    $tmencion=$MENCION->enviar_areglo($mencion);
    ###############################################
    $EMPRESA->dato_de_busqueda_empresa($tlistapasantes["rifempresa"]);
    $empresa=$EMPRESA->buscar_empresa();
    $tempresa=mysqli_fetch_array($empresa);
    header("location:../vista/v_cerrar_pasantia.php?id_pasante=".$tlistapasantes["idpasante"]."&cedula=".$tlistapasantes["cedulaest"]."&nombre_pasante=".$nombre_estudiante."&estado=".$tlistapasantes["estado_pasante"]."&mencion=".$tmencion["nombremencion"]."&nombre_empresa=".$tempresa["nombreempresa"]."&cedula_tutor_empre="."&nombre_tutor_empre=".$nombre_tutor_empresarial."&cargo_tutor_empre=".$tlistapasantes["cargo_tutor_empresarial"]);
}
?>