<?php
$products = null;
//// RLS
//echo Core::$user->kind; //RLS
    $us = UserData::getById(Core::$user->kind);
    //print_r("Almacen asignado");
    //echo $us->stock_id;
 
//// RLS
// print_r(Core::$user);
if (isset($_SESSION["user_id"])) {
    if (Core::$user->kind == 10) {
        //print_r("no ADMIN");
        $products = SellData::getAllBySQL(" where user_id=" . Core::$user->id . " and operation_type_id=1 and p_id=1 and d_id = 1 and is_draft=0 and credito_liquidado = 1 and cred_pagop = 1  order by fecha_pago desc ");
    } else if (Core::$user->kind == 3 || Core::$user->kind == 4) {
        $products = SellData::getAllBySQL(" where operation_type_id=1 and p_id=1 and d_id=1 and is_draft=0 and credito_liquidado = 1 and stock_to_id=" . Core::$user->stock_id . " and cred_pagop = 1 order by fecha_pago desc");
    } else {
//print_r($_GET);
        //print_r("es ADMIN");
        $sql = "select * from sell ";
        $whereparams = array();
        $whereparams[] = " (operation_type_id=1  and credito_liquidado = 1 and cred_pagop = 1 ) ";
        if (isset($_GET["stock_id"]) && $_GET["stock_id"] != "") {
            $whereparams[] = " stock_to_id=$_GET[stock_id] ";
        }
        if (isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"] != "" && $_GET["finish_at"] != "") {
            $whereparams[] = " ( date(fecha_pago) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
        }

        $sql2 = $sql . " where " . implode(" and ", $whereparams) . " order by fecha_pago desc";

        $products = SellData::getAllBySQL3($sql2);
    }
    //// RLS
    /*
        if($us->stock_id){
            $sql = "select * from sell ";
            $whereparams = array();
            $whereparams[] = " (operation_type_id=2 and credito_liquidado = 1) ";
            $whereparams[] = "stock_to_id=$us->stock_id";

            if (isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"] != "" && $_GET["finish_at"] != "") {
                $whereparams[] = " ( date(fecha_pago) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
            }

            $sql2 = $sql . " where " . implode(" and ", $whereparams) . " order by fecha_pago desc";
        
            $products = SellData::getAllBySQL2($sql2);
        } */
    //// RLS

} else if (isset($_SESSION["client_id"])) {
    $products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=1 and p_id=1 and d_id=1 and is_draft=0 and credito_liquidado = 1 and cred_pagop = 1  order by fecha_pago desc");
}

if (count($products) > 0) {
    ?>
    <br>
    <div class="card box-primary">
<div class="card-header">
Listado Créditos Liquidados 
</div>
<div class="box box-primary table-responsive">
<table class="table table-bordered table-hover table-responsive datatable">
                <thead>
                
                <th style="text-align: center">Folio</th>	
                <th style="text-align: center">Pago</th>
                <th style="text-align: center">Entrega</th>
                <th style="text-align: center">Total</th>
                <th style="text-align: center">Cliente</th>
                <th style="text-align: center">Vendedor</th>
                <th style="text-align: center">Almacen</th>
                <th style="text-align: center">Fecha Venta</th>
                <th style="text-align: center">Fecha Liquidacion</th>
                <th style="text-align: center">Comprobante</th> 
          
                               </thead>
                <?php
                foreach ($products as $sell):
                    $operations = OperationData::getAllProductsBySellId($sell->id);
                    ?>

                    <tr>
                     <td style="text-align: center">R - <?php echo $sell->id; ?></td>

                        <td style="text-align: center"><?php echo $sell->getP()->name; ?></td>
                        <td style="text-align: center"><?php echo $sell->getD()->name; ?></td>
                        
                        <td style="text-align: center">

                            <?php
                            $total = $sell->total;
                            echo "<b>" . Core::$symbol . " " . number_format($total, 2, ".", ",") . "</b>";
                            ?>			
                        </td>
                        
     <input type="hidden" class="form-control" id="total" name = "total" value="<?php $sell->total; ?>"> <!-- Se envia el total de la venta -->
     <input type="hidden" class="form-control" id="f_id" name = "f_id" value="<?php echo $sell->getF()->id; ?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="id_anticipo" name = "id_anticipo" value="<?php echo $sell->id_anticipo; ?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="cliente_id" name = "cliente_id" value="<?php echo $sell->person_id; ?>"> <!-- Se envia el total de la venta -->  
     <input type="hidden" class="form-control" id="p_id" name = "p_id" value="<?php echo $sell->getP()->id;?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="d_id" name = "d_id" value="<?php echo $sell->getD()->id; ?>"> <!-- Se envia el id de la forma de pago -->  
     <input type="hidden" class="form-control" id="stock_id" name = "stock_id" value="<?php echo $sell->getStockTo()->id;?>"> <!-- Se envia el id de la forma de pago -->  
                       
                        <td style="text-align: center"> <?php
                            if ($sell->person_id != null) {
                                $c = $sell->getPerson();
                                $idusuario = $sell->person_id;
                                $fp = $c->forma_pago;
                                $uc = $c->uso_comprobante;
                                $rf = $c->regimen_fiscal;    
                                $si_rs = $c->tiene_rs;    
                                echo $c->name . " " . $c->lastname;
                            }
                            ?> </td>
                        <td style="text-align: center"> <?php
                            if ($sell->user_id != null) {
                                $c = $sell->getUser();
                                echo $c->name . " " . $c->lastname;
                            }
                            ?> </td>
                        <td style="text-align: center"><?php echo $sell->getStockTo()->name; 
                                $id_almacen =  $sell->stock_to_id;
                        ?></td>
                    
                        <td style="text-align: center"><?php echo $sell->created_at; ?></td>
                            
                        <td style="text-align: center"><?php echo $sell->fecha_pago; ?></td>
                        
                         <td  style="text-align: center;">
                         <?php if (Core::$user->kind == 1 || Core::$user->kind == 3 || Core::$user->kind == 4): ?>
                              <a  target="_blank" href="reportes_pdf/comprobantep.php?id=<?php echo $sell->id ?>&cliente=<?php echo $idusuario;?>&sucursal=<?php echo $id_almacen;?>"  class="btn btn-xs btn-prymari" ><i class="bi-ticket"></i></a>
                        </td> 
                              <?php endif; ?></td>
                           
                            
                    </tr>
                

                <?php endforeach; ?>

            </table>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php
} else {
    ?>
    <div class="jumbotron">
        <h2>No hay Créditos </h2>
        <p>No se han liquidado créditos al día</p>
    </div>
    <?php
}
?>

