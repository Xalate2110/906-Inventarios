<?php
include '/connection/conexion.php';
$stock = $_GET['stock_id'];
$fecha_inicio = $_GET['start_at'];
$fecha_final  = $_GET['finish_at'];

$sql = 'SELECT idabonos, nombre_cliente, cantidad,cant_ingresada, idcliente,operacion,stock_id, factura_electronica, fecha from bitacora_abonos
where fecha BETWEEN "'.$fecha_inicio.' 00:00:00" AND  "'.$fecha_final.' 23:59:00" and stock_id ="'.$stock.'" and operacion in (1,2)';
$resultado = $mysqli->query($sql);

if(count($sql)>0){
?>
<br>
<div class="card box-primary">
<div class="card-header">
Listado Anticipos y Abonos de Clientes. 
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered datatable table-hover table-responsive datatable	">
	<thead>
		<th style="text-align: center">Folio</th>	
		<th style="text-align: center">Id Cliente</th>
		<th style="text-align: center">Nombre Cliente</th>
		<th style="text-align: center">Disponible</th>
		<th style="text-align: center">Ingresada</th>
		<th style="text-align: center">Id Transacción</th>
		<th style="text-align: center">Fecha Registro</th>
        <th style="text-align: center">Anticipo</th>
        <th style="text-align: center">Abono</th>
        <th style="text-align: center">Comprobante</th>
        <th style="text-align: center">Cambiar A Abono</th>
        <th style="text-align: center">Cambiar A Anticipo</th>
        <th style="text-align: center">Cancelar</th>

	    </thead>
	    <?php 
        while($mostrar=mysqli_fetch_array($resultado)){
        $idabono=$mostrar['idabonos'];
        $idcliente=$mostrar['idcliente'];
        $stock = $mostrar['stock_id'];
        ?>
        <td style="text-align: center">#<?php echo $mostrar['idabonos']  ?></td>
        <td style="text-align: center"><?php echo $mostrar['idcliente'] ?></td>
        <td style="text-align: center"><?php echo utf8_encode($mostrar['nombre_cliente']) ?></td>
        <td style="text-align: center"><?php echo $mostrar['cantidad'] ?></td>
        <td style="text-align: center"><?php echo $mostrar['cant_ingresada'] ?></td>
        <td style="text-align: center"><?php echo $mostrar['factura_electronica'] ?></td>
        <td style="text-align: center"><?php echo $mostrar['fecha'] ?></td>
        <td style="text-align: center">
        <?php
        if($mostrar['operacion'] == "1"){
        echo $estado = "Anticipo";}?>  
        </td>
        <td style="color:green">
        <?php
        if($mostrar['operacion'] == "2"){
        echo $estado = "Abono";}
        ?>  
        </td>
        <td style="text-align: center">
        <a target="_blank" href="mov_abonos_clientes.php?id=<?php echo $idabono;?>&idcliente=<?php echo $idcliente?>&stock=<?php echo $stock?>"class="btn btn-sm btn-success" ><i class="bi-receipt"></i></a> 
        </td>
        <td style="text-align: center">
        <a href="index.php?action=cambiar_a_abono&idanticipo=<?php echo $idabono;?>"class="btn btn-sm btn-success" >Cambiar<i class="bi-receipt"></i></a> 
        </td>
        <td style="text-align: center">
        <a href="index.php?action=cambiar_a_anticipo&idanticipo=<?php echo $idabono;?>"class="btn btn-sm btn-success" >Cambiar<i class="bi-receipt"></i></a> 
        </td>
        <td style="text-align: center">
        <a href="index.php?action=cancelar_abonos&idanticipo=<?php echo $idabono;?>"class="btn btn-sm btn-danger" ><i class="bi-trash"></i></a> 
        </td>
        </tr>
        <?php }?>
        </table>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php
    }else{
        ?>
        <div class="jumbotron">
        <br>

        <?php echo "a donde entre?"?>
            <p>No se ha realizado ninguna venta.</p>
        </div>
        <?php
    }

    ?>

    <div class="modal fade" id="clave" tabindex="-1"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clave">Favor de Ingresar Datos</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </button>
                </div>

                <div class="modal-body">
                    <form>
        
                        <input type="hidden" class="form-control" id="id_cancelacion"> 
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Contraseña:</label>
                            <input type="password" class="form-control" id="pass">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Motivo de Cancelacion:</label>
                            <textarea class="form-control" id="motivo"></textarea>
                        </div>

                        <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">Cerrar Ventana</button>
                        <button type="button" class="btn btn-primary" onclick="cancela_ticket();">Cancelar Ticket</button>
                        </div>
                    </form>
                </div>
                <div> <label class="error"></label></div>
        
            </div>
        </div>
    </div>
