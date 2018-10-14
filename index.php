<?php
include_once("modelo/m_tmencion.php");
include("modelo/g_img.php");
$contador=0;
$inicio=3;
$cartas="";
if(isset($_GET["desde"])){
	$pagina=$_GET["desde"];
	$registros_total=3;
	$inicio=($pagina-1)*$registros_total;
	$MENCION=new tmencion();
	$registros_menciones=$MENCION->consultar_menciones_activas();
	$nuemro_menciones=mysqli_num_rows($registros_menciones);
	$menciones=$MENCION->paginar_menciones_activas($inicio,$registros_total);
	$total_paginas=ceil($nuemro_menciones/$registros_total);
}
else{
	$pagina=1;
	$registros_total=3;
	$inicio=($pagina-1)*$registros_total;
	$MENCION=new tmencion();
	$registros_menciones=$MENCION->consultar_menciones_activas();
	$nuemro_menciones=mysqli_num_rows($registros_menciones);
	$menciones=$MENCION->paginar_menciones_activas($inicio,$registros_total);
	$total_paginas=ceil($nuemro_menciones/$registros_total);
	

}

$query="SELECT * FROM timagen";
	$resultado=$conexion->query($query);
	$row =$resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=divice-wicth, initial-scale=1, maximum-scale=1">
		<title>Inicio</title>		
			<link rel="stylesheet" type="text/css" href="css/fontello.css">
			<link rel="stylesheet" type="text/css" href="css/estilos.css">
			<link rel="stylesheet" type="text/css" href="css/banner.css">
			<link rel="stylesheet" type="text/css" href="css/CSS-menu.css">
			<link rel="stylesheet" type="text/css" href="css/css-menu-horizontal.css">
			<link rel="stylesheet" type="text/css" href="css/info.css">
			<link rel="stylesheet" type="text/css" href="css/content.css">
			<link rel="stylesheet" type="text/css" href="css/estilo-reloj.css">
			<link rel="stylesheet" href="bootstrap4/css/bootstrap-grid.css" type="text/css">
			<link rel="stylesheet" href="bootstrap4/css/bootstrap.css" type="text/css">
			<link rel="stylesheet" href="css/nivo-slider.css">
			<link rel="stylesheet" href="css/mi-slider.css">
			<script type="text/javascript" src="js/jquery-latest.js"></script>
			<script src="js/jquery.nivo.slider.js"></script>
			<script type="text/javascript" src="js/cargando.js"></script>

			<script type="text/javascript"> 
				$(window).on('load', function() {
					$('#slider').nivoSlider(); 
				}); 
			</script>
	</head>
	<body onload="startTime()">
	<div class="banner">
			<ul >
				<li><img src="img/CINTILLO.png" /></li>
				<li><img src="data:image/jpg;base64,<?php echo base64_encode($row['Imagen']); ?>"/></li>
			</ul>
		</div>
			<div id="encabezado-menu-hrz">
				<ul>
					<li><a href="#"><span class="icon-home"></span> Inicio</a></li>
					<li class="submenu"><a href="#"><span class="icon-user"></span> Nosotros <span class="icon-down-dir"></span></a>
						<ul>
							<li><a href="html/content1.html">Mision y Vision <span class="icon-left-dir"></span></a></li>
							<li><a href="html/content3.html">Organigrama<span class="icon-left-dir"></span></a></li>
							<li><a href="html/content2.html">Reseña Historica<span class="icon-left-dir"></span></a></li>
						</ul>
					</li>
					<li><a href="html/content4.html"><span class="icon-doc-text"></span> Recaudos</a></li>
					<li><a href="vista/v_login.php"><span class="icon-login"></span>Iniciar sesion</a></li>
				</ul>
				<div id="clockdate">
					<div class="clockdate-wrapper">
					  <div id="clock"></div>
					  <div id="date"></div>
					</div>
				</div>
			</div>
			<header>
				<input type="checkbox" id="checkbox-menu">
				<div id="encabezado"><div id="zona-menu"><label for="checkbox-menu"><span class="icon-menu"></span></label></div></div>
				<nav class="menu">
					<ul>
						<li><a href="#"><span class="icon-home"></span> Inicio</a></li>
						<li class="submenu"><a href="#"><span class="icon-user"></span> Nosotros <span class="icon-down-dir"></span></a>
							<ul>
								<li><a href="html/content1.html">Mision y Vision <span class="icon-left-dir"></span></a></li>
								<li><a href="html/content3.html">Organigrama<span class="icon-left-dir"></span></a></li>
								<li><a href="html/content2.html">Reseña Historica<span class="icon-left-dir"></span></a></li>
							</ul>
						</li>
						<li><a href="html/content4.html"><span class="icon-doc-text"></span> Recaudos</a></li>
						<li><a href="vista/v_login.php">Iniciar sesion</a></li>
					</ul>
				</nav>
			</header>
		<div class="slider-wrapper theme-mi-slider">
				<div id="slider" class="nivoSlider">     
					<img src="img/CvKSLiqWgAAizai.jpg" alt="" title="#htmlcaption1" />    
					<img src="img/beats-black-photo-wallpapers-1920x1080.jpg" alt="" title="#htmlcaption2" />    
					<img src="img/CvKS8MEWgAUEm80.jpg" alt="" title="#htmlcaption3" />     
				</div> 
				<div id="htmlcaption1" class="nivo-html-caption">     
					<h1>Sean Bienvenidos</h1>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
				</div>
				<div id="htmlcaption2" class="nivo-html-caption">     
					<h1>Acerca de Nosotros</h1>
				</div>
				<div id="htmlcaption3" class="nivo-html-caption">     
					<h1>Gracias por visitar</h1>
				</div>
			</div>
		<section id="info">
				<h3>Menciones</h3>
				<!--
				<div class="contenedor">
					
					<div class="info-menc">
						<h4>Informática</h4>
						<h5> Tiene como propósito ofrecer a los jóvenes la posibilidad de introducirse en la utilización, el conocimiento y el desarrollo de saberes y capacidades que les permitan abordar problemas y encontrar soluciones relacionados con la informática en el marco de las tecnologías de la información y la comunicación.</h5>
					</div>
					<div class="info-menc">
						<h4>Mercadeo</h4>
						<h5>Va dirigido a desarrollar las destrezas necesarias para identificar las necesidades de los individuos y organizaciones con el objetivo de promover aquellos bienes y servicios que proyecten una imagen empresarial de excelencia, un funcionamiento operacional altamente efectivo y una mayor capacidad competitiva.</h5>
					</div>
					<div class="info-menc">
						<h4>Administración</h4>
						<h5> Tiene como propósito exponer a los estudiantes a los principios, normas y leyes vigentes en la práctica de la profesión de Venezuela. Los estudiantes se relacionarán con la teoría y práctica de las diversas áreas de especialización en el campo de la Administración.</h5>
					</div>
					<div class="info-menc">
						<h4>Comercio</h4>
						<h5>son una rama interdisciplinaria que combina varias ciencias sociales y económicas, para analizar y entender las normas que rigen el intercambio de bienes, servicios y la inversión entre los países; así como la interacción de los diversos factores que influyen en las decisiones de internacionalización de las empresas.</h5>
					</div>

				</div>
				-->
				<div class="container-fluid">
					<div class="row mt-3 justify-content-center" style="color:black;">
						<?php
							if(isset($_GET["desde"])){
								paginar_mencione_card($menciones,$total_paginas,$pagina);
							}
							else{
								paginar_mencione_card($menciones,$total_paginas,$pagina);
							}
							function paginar_mencione_card($menciones,$total_paginas,$pagina){
								$open_col="<div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4'>";
								$open_card="<div class='card text-center'>";
								
								$close_card="</div>";
								$close_col="</div>";
								while($mencion=mysqli_fetch_array($menciones)){
									$info_card="";
									$info_card.="<div class='card-header'><h4>".$mencion["nombremencion"]."</h4></div>"."<div class='card-body'>"."<p class='card-text'>".$mencion["descripcionmencion"]."</p>"."</div>";
									print $open_col.$open_card.$info_card.$close_card.$close_col;
								}
								$paginas="";
								if($pagina!=1){
									$ultimo=$pagina-1;
									$ultima_pagina="<li class='page-item'><a class='page-link' style='background:#111;' href='index.php?desde=$ultimo'>".$ultimo."</a></li>";
								}
								else{
									$ultima_pagina="";
								}
								for($numero=$pagina;$numero<=2+($pagina-1);$numero++){
									if($numero!=$total_paginas+1){
										$paginas.="<li class='page-item'><a class='page-link' style='background:#111;' href='index.php?desde=$numero'>".$numero."</a></li>";
									}
								}
								print "<ul class='pagination justify-content-center' style='margin-top:20px;'>".$ultima_pagina.$paginas."</ul>";
								
							}
						?>

							<!--
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">        
								<div class="card text-center">
									<div class="card-header">
									Cabecera
									</div>
									<div class="card-body">
									<h4 class="card-title">Titulo de la tarjeta</h4>
									<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam lectus sem, 
															tempor vitae mattis malesuada, ornare sed erat. Pellentesque nulla dui, congue
															nec tortor sit amet, maximus mattis dui. </p>
									<a href="#" class="btn btn-primary">Entrar</a>
									</div>
									<div class="card-footer">
									Pie de tarjeta
									</div>
								</div>          
							</div>
							-->
							
					</div>

				</div>
			</section>

				<div class="social">
					<ul>
						<li><a href="#" class="icon-facebook-official"></a></li>
						<li><a href="#" class="icon-twitter"></a></li>
						<li><a href="#" class="icon-gplus"></a></li>
						<li><a href="#" class="icon-mail-alt"></a></li>
					</ul>
				</div>
			<p class="copy">&copy; ETCR Ademar Vasquez Chavez - Todos los Derechos Reservados</p>

	<script type="text/javascript" src="js/js-mostrar-menu.js"></script>
	<script type="text/javascript" src="js/reloj.js"></script>
	</body>
</html>
