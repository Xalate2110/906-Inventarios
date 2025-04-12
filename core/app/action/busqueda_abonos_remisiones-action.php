
<br>
<?php
include '/connection/conexion.php';
$idcliente = $_GET['client_id'];



if($idcliente == 0){

$sql="SELECT bitacora_pagos_anticipo.id_cliente,bitacora_pagos_anticipo.id_anticipo,bitacora_pagos_anticipo.remision,cfdis.serie,cfdis.folio,cfdis.mpago,cfdis.UUID,bitacora_pagos_anticipo.pagado,
bitacora_pagos_anticipo.total_remision,bitacora_pagos_anticipo.nuevo_saldo,person.name,bitacora_pagos_anticipo.fecha_operacion,cfdis.tipo_factura
from bitacora_pagos_anticipo 
INNER JOIN person on person.id = bitacora_pagos_anticipo.id_cliente 
INNER JOIN cfdis on cfdis.Folio_venta = bitacora_pagos_anticipo.remision
INNER JOIN bitacora_abonos on bitacora_abonos.idabonos = bitacora_pagos_anticipo.id_anticipo 
where  bitacora_abonos.operacion = 2  and cfdis.tipo_factura in (1,2) and cfdis.timbrado = 1  and bitacora_abonos.facturado = 0 ORDER BY  bitacora_pagos_anticipo.id_anticipo DESC ";

}else{

$sql="SELECT bitacora_pagos_anticipo.id_cliente,bitacora_pagos_anticipo.id_anticipo,bitacora_pagos_anticipo.remision,cfdis.serie,cfdis.folio,cfdis.mpago,cfdis.UUID,bitacora_pagos_anticipo.pagado,
bitacora_pagos_anticipo.total_remision,bitacora_pagos_anticipo.nuevo_saldo,person.name,bitacora_pagos_anticipo.fecha_operacion,cfdis.tipo_factura
from bitacora_pagos_anticipo 
INNER JOIN person on person.id = bitacora_pagos_anticipo.id_cliente 
INNER JOIN cfdis on cfdis.Folio_venta = bitacora_pagos_anticipo.remision
INNER JOIN bitacora_abonos on bitacora_abonos.idabonos = bitacora_pagos_anticipo.id_anticipo 
where  bitacora_abonos.operacion = 2  and cfdis.tipo_factura in (1,2) and cfdis.timbrado = 1 and bitacora_abonos.facturado = 0 and person.id = $idcliente ORDER BY  bitacora_pagos_anticipo.id_anticipo DESC ";
}

$resultado = $mysqli->query($sql);

if(count($sql)>0){
?>
<div class="card box-primary">
<div class="card-header">
Listado de anticipos y/o abonos de clientes.
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
	<thead>
		<th style="text-align: center;">ID Cliente</th>
		<th style="text-align: center;">ID Anticipo</th>
		<th style="text-align: center;">Remisión</th>
		<th style="text-align: center;">Serie Factura</th>
	    <th style="text-align: center;">Folio Factura</th>
		<th style="text-align: center;">Folio UUID</th>
		<th style="text-align: center;">Total Abonado</th>
		<th style="text-align: center;">Total Factura</th>
		<th style="text-align: center;">Saldo Nuevo</th>
		<th style="text-align: center;">Nombre Cliente</th>
		<th style="text-align: center;">Fecha Aplicacion Abono</th>
		<th style="text-align: center;">Seleccionar Abono</th>
	</thead>
	<?php while($mostrar=mysqli_fetch_array($resultado)) {?>

		
	 <tr>
		<td  style="text-align: center"><?php echo $mostrar['id_cliente']; ?></td>
		<td  style="text-align: center"><?php echo $mostrar['id_anticipo']; ?></td>
		<td  style="text-align: center"><?php echo $mostrar['remision'];?></td>
		<td  style="text-align: center"><?php echo $mostrar['serie'];?></td>
		<td  style="text-align: center"><?php echo $mostrar['folio'];?></td>
		<td  style="text-align: center"><?php echo $mostrar['UUID'];?></td>
		<td  style="text-align: center"><?php echo "$ ".number_format($mostrar['pagado'],2,".",",");;?></td>
		<td  style="text-align: center"><?php echo "$ ".number_format($mostrar['total_remision'],2,".",",");;?></td>
		<td  style="text-align: center"><?php echo "$ ".number_format($mostrar['nuevo_saldo'],2,".",",");;?></td>
		<td  style="text-align: center"><?php echo $mostrar['name'];?></td>
		<td  style="text-align: center"><?php echo $mostrar['fecha_operacion'];?></td>
		<form id="formid">
		<input id="id_cliente" name="id_cliente" type="hidden" value="<?php echo $mostrar['id_cliente'];?>" >
		
		<td  style="text-align: center"><input type="checkbox" id="checks" name="checks" value="<?php echo $mostrar['id_anticipo'];?>" ></label></td>
		</form>
	</tr>
	<?php   }?>
</table>
	         			<script>
                            $(document).ready(function() {
                            $('#abonos_remisiones').DataTable( {
                            "paging":   true,
                            "ordering": true,
                            "iDisplayLength": 10,
                            "scrollX": false,
                            "info":     true
                            } );
                            } );
                      </script>

<script>
	  $(document).ready(function() {

      $('#enviar').click(function() {

	  var id_cliente = $("#id_cliente").val();
      // defines un arreglo
      var selected = [];
	  

	  $(":checkbox[name=checks]").each(function() {
      if (this.checked) {
        // agregas cada elemento.
        selected.push($(this).val());
	   }
	
       });
  
	   if (selected.length) {
		
	    	var mensaje;
			var opcion = confirm("¿Deseas Generar El Complemento de Pago Del Abono o Abonos Seleccionados?");
			if (opcion == true) {
				alert("Se va a generar el proceso de timbrado del Complemento de Pago, Espera a que termine el sistema...");
			  $.ajax({
				//cache: true,
				type: 'post',
				//dataType: 'json', // importante para que 
				data: "complementos="+selected + "&idcliente="+id_cliente, // jQuery convierta el array a JSON
				url: 'core/app/facturacion/cfdi_pago.php',
				success: function(data) {
				
					if (data == "1") {
								alert("Se ha generado el Complemento de Pago Correctamete, el sistema te redireccionara al listado de Complementos de Pago.");
								window.location.reload();
							} else
							if (data == "0")
							{
							alert("Error, se ha encontrado un error al Timbrar el Complemento De Pago, revisalo con el administrador del sistema.");
							} else{
							alert("Error, se ha encontrado un error al Timbrar el Complemento De Pago, revisalo con el administrador del sistema.");
							}
					  }
				   });	
			}else{
				alert("Has Cancelado el proceso de Timbrado de Complemento de Pago ...");
			}		   
		   } else {
		  alert('Debes seleccionar al menos una abono, para generar el complemento de pago');
		  }
	
		return false;
	  });  
	});
	</script>
	
	
	
	
	<button id = "enviar" name = "enviar" class="btn btn-success">Generar Complemento de Pago</button>

</div>
  </div><!-- /.box-body -->
  </div><!-- /.box -->





	<?php
}else{
	?>
	<div class="alert alert-info">
		<h2>Sin abnos filtrados para mostrar</h2>
	</div>
	<?php
}

?>