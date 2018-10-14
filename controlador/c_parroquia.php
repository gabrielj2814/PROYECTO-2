<?php
include_once("../modelo/m_tparroquia.php");
$PARROQUIA=new tparroquia();
$areglo_parroquia=$PARROQUIA->cunsultar_parroquia($_GET["id_municipio"]);
$select="";
$combo_parroquia="";
if(isset($_GET["id_parroquia"])){
    while($tparroquia=mysqli_fetch_array($areglo_parroquia)){
       if($_GET["id_parroquia"]==$tparroquia['id_parroquia']){
           $select="selected";
           $combo_parroquia .="<option value=".$tparroquia['id_parroquia']." ".$select." hola >".$tparroquia["nombre_parroquia"]."</option>";
       }
       else{
            $combo_parroquia .="<option value=".$tparroquia['id_parroquia']." >".$tparroquia["nombre_parroquia"]."</option>";
       }
    }

}
else{
    while($tparroquia=mysqli_fetch_array($areglo_parroquia)){
        $combo_parroquia .="<option value=".$tparroquia['id_parroquia']." >".$tparroquia["nombre_parroquia"]."</option>";
    }
}
print $combo_parroquia;
?>