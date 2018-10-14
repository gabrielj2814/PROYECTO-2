<?php
include_once("../modelo/m_tmunicipio.php");
$MUNICIPIO=new tmunicipio();
$areglo_municipio=$MUNICIPIO->consultar_municipio($_GET["id_estado"]);
$select="";
$combo_municipio="";
if(isset($_GET["id_municipio"])){
    while($tmunicipio=mysqli_fetch_array($areglo_municipio)){
       if($_GET["id_municipio"]==$tmunicipio['id_municipio']){
           $select="selected";
        $combo_municipio .="<option value=".$tmunicipio['id_municipio']." ".$select." >".$tmunicipio["nombre_municipio"]."</option>";
       }
       else{
        $combo_municipio .="<option value=".$tmunicipio['id_municipio']." >".$tmunicipio["nombre_municipio"]."</option>";
       }
    }

}
else{
    while($tmunicipio=mysqli_fetch_array($areglo_municipio)){
        $combo_municipio .="<option value=".$tmunicipio['id_municipio']." >".$tmunicipio["nombre_municipio"]."</option>";
    }
}
print $combo_municipio;
?>