<?php
include_once("../modelo/m_testado.php");
$ESTADO=new testado();
$estado=$ESTADO->solicitar_estado();
$select="";
$combo_estado="";
if(isset($_GET["id_estado"])){
    while($testado=mysqli_fetch_array($estado)){
        if($_GET["id_estado"]==$testado['id_estado']){
            $select="selected";
            $combo_estado .="<option value=".$testado['id_estado']." ".$select." >".$testado["nombre_estado"]."</option>";
        }
        else{
            $combo_estado .="<option value=".$testado['id_estado'].">".$testado["nombre_estado"]."</option>";
        }
    }

}
else{
    while($testado=mysqli_fetch_array($estado)){
        $combo_estado .="<option value=".$testado['id_estado'].">".$testado["nombre_estado"]."</option>";
    }
}
print $combo_estado;
?>