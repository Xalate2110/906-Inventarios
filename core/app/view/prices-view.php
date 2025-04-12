<!-- Content Header (Page header) -->
<section class="content-header">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<script>
$(document).ready(function () {
$('.js-example-basic-single').select2();
});
</script>
 <h1>
Administrar Precios Sucursal <?php  echo StockData::getPrincipal()->name; ?>
</h1>
</section>
<!-- Main content -->
<section class="content">

<div class="row">
	<div class="col-md-12">
<form class="form-horizontal" id="prices">
  <div class="">
  <input type="hidden" name="view" value="prices">
  <div class="form-group row">
    <div class="col-md-3">
     <!-- <input type="text" name="q" class="form-control" id="inputEmail3" placeholder="Buscar"> -->
     <?php 
			$products = ProductData::getAll2(); 
			?>
			<select name="q" id="q" class="form-control  js-example-basic-single">
				<option value="0">-- Todos Los Productos --</option>
				<?php foreach($products as $p):?>
					<option value="<?php echo $p->id;?>"><?php echo $p->code;?> - <?php echo $p->marca;?> - <?php echo utf8_encode($p->name);?></option>
				<?php endforeach; ?>
			</select>
      </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary">Buscar Producto</button>
    </div>

  </div>
</div>
</form>


<?php
$id_almacen =  StockData::getPrincipal()->id; 
$currency = ConfigurationData::getByPreffix("currency")->val;
$stocks = StockData::getAll2($id_almacen);
$products = array(); //ProductData::getAllByPage(0,25);

if(isset($_GET["q"]) && $_GET['q']!=""){
$products = ProductData::getLike2(htmlentities($_GET["q"]));
}else{
$products = ProductData::getAllByPage(0,25);}

if(count($products)>0){
?>
<br>
<div class="card box-primary">
<div class="card-header">
<span class="box-title">Litado de precios productos por sucursal</span></div>
<div class="card-body">
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center">Codigo</th>
	 	<!--<th style="text-align: center">Imagen</th>-->
		<th style="text-align: center">Nombre</th>
		<th style="text-align: center">Precio Default</th>
		<th style="text-align: center">Lista de precios por sucursal</th>
	</thead>
	<?php foreach($products as $product):
  $price = $product->price_out;?>
	<tr>
		<td style="text-align: center"><?php echo $product->code; ?></td>
	<!--	<td style="text-align: center">
			<?php if($product->image!=""):?>
				<img src="storage/products/<?php echo $product->image;?>" style="width:64px;">
			<?php endif;?>
		</td> -->
		<td style="width:64px;"><?php echo $product->name; ?></td>
		<td style="text-align: center"><?php echo $currency; ?> <?php echo number_format($product->price_out,2,'.',','); ?></td>
		

		<td style="width:500px; ">
    <form method="post" action="./?action=updateprices" id="form-<?php echo $product->id; ?>">
    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
    <input type="hidden" name="stock_id" value="<?php echo $id_almacen; ?>">
    <?php foreach($stocks as $stock):
    $px = PriceData::getByPS($product->id,$stock->id);
    if($px){
      $price=$px->price_out;
      $price2=$px->price_out2;
      $price3=$px->price_out3;
      $price4=$px->price_out4;
      }else{
      $price=$product->price_out;
      $price2=$product->price_out2;
      $price3=$product->price_out3;
      $price4=$product->price_out4;
    }

?>

<div class="input-group">
  <span class="input-group-addon" id="basic-addon1"><?php echo $stock->name; ?></span>
  <span  id="basic-addon1"> P. de Distribuidor </span>
  
  <input type="text" required class="form-control" value="<?php echo $price; ?>" name="price_<?php echo $stock->id; ?>_<?php echo $product->id; ?>" placeholder="Precio en <?php echo $stock->name; ?>">
</div>

<div class="input-group">
  <span  id="basic-addon1"><?php echo $stock->name; ?> P. Publico Gral </span>
  <input type="text" required class="form-control" value="<?php echo $price3; ?>" name="price2_<?php echo $stock->id; ?>_<?php echo $product->id; ?>" placeholder="Precio 2 en <?php echo $stock->name; ?>">
</div>

<div class="input-group">
  <span id="basic-addon1"><?php echo $stock->name; ?> P. Mayoreo </span>
  <input type="text" required class="form-control" value="<?php echo $price2; ?>" name="price3_<?php echo $stock->id; ?>_<?php echo $product->id; ?>" placeholder="Precio 3 en <?php echo $stock->name; ?>">
</div>
<br>

<div class="input-group">
  <span id="basic-addon1"><?php echo $stock->name; ?> P. Amigo</span>
  <input type="text" required class="form-control" value="<?php echo $price4; ?>" name="price4_<?php echo $stock->id; ?>_<?php echo $product->id; ?>" placeholder="Precio 4 en <?php echo $stock->name; ?>">
</div>
<br>

<?php endforeach; ?>
<input type="submit" value="Actualizar" class="btn btn-success">
</form>
<script type="text/javascript">
  $(document).ready(function(){
    $("#form-<?php echo $product->id; ?>").submit(function(e){
      e.preventDefault();
      $.post("./?action=updateprices",$("#form-<?php echo $product->id; ?>").serialize(),function(data){
        console.log(data);
        alert("Se ha realizado la actualización de precios de forma, correcta. ");
      });
    });;
  });
</script>

		</td>
	</tr>
	<?php endforeach;?>
</table>
</div>
  </div><!-- /.box-body -->
</div><!-- /.box -->


	<?php
}else{
	?>
	<div class="alert alert-info">
		<h2>No hay productos</h2>
		<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
	</div>
	<?php
}

?>
	</div>
</div>
        </section><!-- /.content -->

