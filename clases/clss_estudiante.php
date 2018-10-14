<?php
include_once("../modelo/m_db.php");
class estudiante extends MySQL{
    private $cedula;
    function estudiante(){
        $this->cedula="";
        $this->MySQL();
    }
    function reporte_estudiante($cedula){
        $this->cedula=$cedula;
        $SQL="SELECT * FROM testudiante WHERE cedulaest='$this->cedula';";
       
        $estudiante=$this->query($SQL);
        $testudiante=$this->ejecutar_cambio_arreglo($estudiante);
        $cordenadas=explode("-",$testudiante["codigo_ubicacion"]);
        $SQL1="SELECT * FROM testado WHERE id_estado='$cordenadas[0]';";
        $SQL2="SELECT * FROM tmunicipio WHERE id_municipio='$cordenadas[1]';";
        $SQL3="SELECT * FROM tparroquia WHERE id_parroquia='$cordenadas[2]';";
        $estado=$this->query($SQL1);
        $municipio=$this->query($SQL2);
        $parroquia=$this->query($SQL3);
        $testado=$this->ejecutar_cambio_arreglo($estado);
        $tmunicipio=$this->ejecutar_cambio_arreglo($municipio);
        $tparroquia=$this->ejecutar_cambio_arreglo($parroquia);
        $nombre=$testudiante["pnombreest"]." ".$testudiante["snombreest"]." ".$testudiante["papellidoest"]." ".$testudiante["sapellidoest"];
        if($testudiante["sexoest"]=="M"){
            $sexo="Masculino";
        }
        else{
            $sexo="Femenino";
        }
        if($testudiante["estadoest"]=="1"){
            $estado="Activo";
        }
        else{
            $estado="Inactivo";
        }
        $fecha=explode("-",$testudiante["fechanacimientos"]);
        include_once("../fpdf/fpdf.php");
        $PDF=new FPDF("P","mm",array(279,297));
        $PDF->AddPage();
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(0,10,"Estudiante","TLR",0,"C");
        $PDF->Ln(10);
        $PDF->Cell(120,10,"Cedula:","TL",0);
        $PDF->Cell(0,10,$testudiante["cedulaest"],"TR",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Nombre:","L",0);
        $PDF->Cell(0,10,$nombre,"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Fecha de Nacimiento:","L",0);
        $PDF->Cell(0,10,$fecha[2]."-".$fecha[1]."-".$fecha[0],"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Correo:","L",0);
        $PDF->Cell(0,10,$testudiante["correoestudiante"],"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Numero Telefonico:","L",0);
        $PDF->Cell(0,10,$testudiante["ntlfestudiante"],"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Sexo:","L",0);
        $PDF->Cell(0,10,$sexo,"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Estado:","L",0);
        $PDF->Cell(0,10,$estado,"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Codigo Fases:","L",0);
        $PDF->Cell(0,10,$testudiante["id_fase"],"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Estado:","L",0);
        $PDF->Cell(0,10,$testado["nombre_estado"],"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Municipio:","L",0);
        $PDF->Cell(0,10,$tmunicipio["nombre_municipio"],"R",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(120,10,"Parroquia:","LB",0);
        $PDF->Cell(0,10,$tparroquia["nombre_parroquia"],"RB",0);
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->Cell(0,10,"Dirección:","LR",0,"C");
        $PDF->Ln(10);
        $PDF->SetFont("Arial","I",16);
        $PDF->MultiCell(0,10,$testudiante["direccionpasante"],"LRB","C");
        $PDF->Ln(10);
        $PDF->Output(); 
    }
    function listar_todos(){
        $SQL="SELECT * FROM testudiante;";
        #print $SQL;
        $estudiante=$this->query($SQL);
        include_once("../clases/clss_pdf.php");
        $PDF=new PDF("P","mm",array(279,297));
        $PDF->AddPage();
        while($testudiante=mysqli_fetch_array($estudiante)){
            $nombre=$testudiante["pnombreest"]." ".$testudiante["snombreest"]." ".$testudiante["papellidoest"]." ".$testudiante["sapellidoest"];
            if($testudiante["sexoest"]=="M"){
                $sexo="Masculino";
            }
            else{
                $sexo="Femenino";
            }
            $PDF->SetFont("Arial","I",16);
            $PDF->Cell(50,10,$testudiante["cedulaest"],"LRB",0);
            $PDF->Cell(90,10,$nombre,"RB",0);
            $PDF->Cell(30,10,$sexo,"RB",0);
            $PDF->Cell(0,10,$testudiante["id_fase"],"RB",0);
            $PDF->Ln(10);
        }
        $PDF->Output(); 
    }
}
?>