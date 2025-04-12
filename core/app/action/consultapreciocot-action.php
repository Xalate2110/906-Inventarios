<?php
$products = array();
if(isset($_GET["producto"])){

    $busca = $_GET["producto"];
    $products = ProductData::getLikeStartLimit($busca,0,10);
}
?>

<table class="table table-bordered">
<thead>   
<th style="text-align: center">Codigo</th>
<th style="text-align: center">Descripción Producto</th>
<th style="text-align: center">Precio</th>
<th style="text-align: center">Existencia</th>
<th style="text-align: center">Agregar</th>
</thead>
<?php foreach($products as $p):
    $qxa = OperationData::getQByStock($p->id,StockData::getPrincipal()->id);

    ?>
<tr class="<?php if($qxa==0){ echo "table-danger";}?>">
    <td style="text-align: center"><?php echo $p->code; ?></td>
    <td style="text-align: center"><?php echo $p->name; ?></td>
    <td style="text-align: center"><?php echo $p->price_out; ?></td>
    <td style="text-align: center"><?php echo $qxa;?></td>
    <td style="text-align: center">
    <?php if($qxa>0):?>
    <button id="product-<?php echo $p->id; ?>" class="btn btn-success">+</button>
    <script>
    $("#product-<?php echo $p->id; ?>").click(function(){
    $.post("./?action=addtocot","product_id=<?php echo $p->id; ?>&q=1&discount=0&price=<?php echo $p->price_out; ?>",function(data){
    $.get("./?action=cartofsellcot",null,function(data2){
   $("#cartofsellcot").html(data2);
});});

            });
        </script>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>