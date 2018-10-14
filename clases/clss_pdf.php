<?php
include_once("../fpdf/fpdf.php");
class PDF extends FPDF{

    function Header(){
        $this->SetFont("Arial","I",16);
        $this->Cell(0,10,"Lista de Estudiantes",0,0,"C");
        $this->Ln(10);
        $this->Cell(50,10,"Cedula",1,0);
        $this->Cell(90,10,"Nombre","TRB",0);
        $this->Cell(30,10,"Sexo","TRB",0);
        $this->Cell(0,10,"Codigo Fase","TRB",0);
        $this->Ln(10);
    }
   
}

?>