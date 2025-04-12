<section class="content">
<div class="row">
	<div class="col-md-12">

		<h1><i class='bi-truck'></i> Directorio de Proveedores</h1>
<div class="btn-group ">
	<a href="index.php?view=newprovider" class="btn btn-outline-dark"><i class='bi-truck'></i> Nuevo Proveedor</a>
<div class="btn-group">

  <ul class="dropdown-menu" role="menu">
    <li><a class="dropdown-item" href="report/providers-word.php">Word 2007 (.docx)</a></li>
    <li><a class="dropdown-item" href="report/providers-xlsx.php">Excel 2007 (.xlsx)</a></li>
<li><a class="dropdown-item" onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>

  </ul>
</div>
</div><br><br>
		<?php
        $users = PersonData::getProviders();
		if(count($users)>0){
			// si hay usuarios
			?>
<div class="card box-primary">
	<div class="card-header">Listado General De Proveedores</div>
<div class="card-body">
			<table class="table table-bordered datatable table-hover">
			<thead>
			<th style="text-align: center">Nombre Proveedor</th>
			<th style="text-align: center">Direccion</th>
			<th style="text-align: center">Email</th>
			<th style="text-align: center">Telefono</th>
			<th style="text-align: center">Acciones</th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
			
				<td style="text-align: center"><?php echo $user->name." ".$user->lastname; ?></td>
				<td style="text-align: center"><?php echo $user->address1; ?></td>
				<td style="text-align: center"><?php echo $user->email1; ?></td>
				<td style="text-align: center"><?php echo $user->phone1; ?></td>
				<td style="width:130px;">
				<a href="index.php?view=editprovider&id=<?php echo $user->id;?>" class="btn btn-warning btn-sm">Editar</a>
				<a href="index.php?view=delprovider&id=<?php echo $user->id;?>&stock=<?php echo $stock_id;?>" class="btn btn-danger btn-sm">Eliminar</a>

				</td>
				</tr>
				<?php

			}
			?>
			</table>
			</div>
			</div>
			<?php



		}else{
			echo "<p class='alert alert-danger'>No hay proveedores</p>";
		}


		?>


	</div>
</div>
</section>



<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("DIRECTORIO DE PROVEEDORES", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "RFC/RUT", dataKey: "no"}, 
    {title: "Nombre completo", dataKey: "name"}, 
    {title: "Direccion", dataKey: "address"}, 
    {title: "Email", dataKey: "email"}, 
    {title: "Telefono", dataKey: "phone"}, 
];
var rows = [
  <?php foreach($users as $product):
  ?>
    {
      "id": "<?php echo $product->id; ?>",
      "no": "<?php echo $product->no; ?>",
      "name": "<?php echo $product->name." ".$product->lastname; ?>",
      "address": "<?php echo $product->address1; ?>",
      "email": "<?php echo $product->email1; ?>",
      "phone": "<?php echo $product->phone1; ?>",
      },
 <?php endforeach; ?>
];
doc.autoTable(columns, rows, {
    theme: 'grid',
    overflow:'linebreak',
    styles: { 
        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
    },
    columnStyles: {
        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
    },
    margin: {top: 100},
    afterPageContent: function(data) {
    }
});
doc.setFontSize(12);
doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+25);
<?php 
$con = ConfigurationData::getByPreffix("report_image");
if($con!=null && $con->val!=""):
?>
var img = new Image();
img.src= "storage/configuration/<?php echo $con->val;?>";
img.onload = function(){
doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
doc.save('providers-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('providers-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>


