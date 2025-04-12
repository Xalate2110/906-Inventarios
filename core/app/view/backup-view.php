<?php if(isset($_GET["opt"]) && $_GET['opt']=="step1"):?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Respaldar Base de Datos</h1>
  <p>Bienvenido a la opcion para respaldar la base de datos del sistema.</p>
	<br>

<!-- Button trigger modal -->
<a href="./?action=backup" class="btn btn-primary"   >
  Respaldar Base de Datos
</a>

	</div>
</div>
</section>

<?php elseif(isset($_GET["opt"]) && $_GET['opt']=="step2"):?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Respaldar Base de Datos</h1>

<p class="alert alert-success">Se ha guardado el respaldo de la base de datos.</p>
<?php if(isset($_SESSION["filename"])):?>
	<a href="<?php echo $_SESSION['filename']?>" class="btn btn-primary" target="_blank">Ver/Descargar Archivo .sql</a>
	<?php endif; ?>
	</div>
</div>
</section>

<?php endif; ?>