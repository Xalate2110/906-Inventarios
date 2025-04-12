<section class="content"> 
<div class="row">
	<div class="col-md-12">

<?php 
if(isset($_SESSION["client_id"])):?>
		<h1><i class='bi-shopping-cart'></i> Mis Abonos</h1>
<?php else:?>
		<h1><i class='bi-cart4'></i> Reporte Completo De Facturas</h1>




<?php endif;?>
		<div class="clearfix"></div>

<form id="filterfacturascompleto"  action="./reportes_excel/reporte_completo_facturas.php" method="GET">
<div class="row">
	<div class="col-md-2">
		<label>Almacen</label>
		<select name="stock_id" id="stock_id" required class="form-control">
			<option value="">-- ALMACEN--</option>
			<?php foreach(StockData::getAll() as $stock):?>
				<option value="<?php echo $stock->id; ?>"><?php echo $stock->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

    <div class="col-md-2">
		<label>Fecha inicio</label>
		<input type="date" name="start_at" value="<?php echo date('Y-m-d'); ?>" id ="bd-desde" required class="form-control">
	</div>
	<div class="col-md-2">
		<label>Fecha fin</label>
		<input type="date" name="finish_at" value="<?php echo date('Y-m-d'); ?>" id = "bd-hasta" required class="form-control">
	</div>

    <?php 
include '/connection/conexion.php';

?>
    <div class="form-group col-md-2">
                        <label for="inputState">FORMA DE PAGO</label>
                        <?php
                        $sql_pago = "select * from tblformas_pago";
                        //echo $sql;
                        $resultSet = $mysqli->query($sql_pago);
                        ?>
                        <select id="fpago" name="fpago" class="form-control js-example-basic-single" required="true">
                        <option value="0">Todos</option>
                            <?php
                            while ($fila = $resultSet->fetch_assoc()) {
                                
                                echo "<option value='" . $fila['clave_pago'] . "'>" . $fila['clave_pago'] . " - " . utf8_encode($fila['descripcion']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

	<div class="col-md-2">
		<br>
		<input type="submit" value="Aplicar Filtro" class="btn btn-primary btn-block">
	</div>

</div>
</form>

<div class="allfilterfacturascompleto"></div>


<script type="text/javascript">
	$(document).ready(function(){
		$.get("./?action=filterfacturascompleto",$("#filterfacturascompleto").serialize(),function(data){
			$(".allfilterfacturascompleto").html(data);
		});

		$("#filterfacturascompleto").submit(function(e){
			e.preventDefault();
		$.get("./?action=filterfacturascompleto",$("#filterfacturascompleto").serialize(),function(data){
			$(".allfilterfacturascompleto").html(data);
		});

		})
	});
</script>
	</div>
</div>
</section>

