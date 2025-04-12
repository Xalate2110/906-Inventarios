<?php
    
if (isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"] != "" && $_GET["finish_at"] != "") {
    
    $alm    = $_GET['almacen'];
    $inicio = $_GET['start_at'];
    $fin    = $_GET['finish_at'];
    $sql = "select * from sell where operation_type_id=2 and box_id is NULL and p_id=1 and is_draft=0 AND stock_to_id=$alm ";
    $whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
    $sql2 = $sql . " and " . implode(" and ", $whereparams) . " order by created_at desc";
    //echo $sql2;
    
    $alm   = StockData::getPrincipal();
    //$sells = SellData::getSellsUnBoxedByAlm($alm->id);

    $sells = SellData::getAllBySQL3($sql2);

    if(count($sells)){
        $box = new BoxData();
        //$box->stock_id = StockData::getPrincipal()->id;
        $box->stock_id = $alm->id;
        //$b = $box->add();
        $b = $box->addByDate($inicio);
        foreach($sells as $sell){
            $sell->box_id = $b[1];
            $sell->update_box();
        }
        Core::redir("./index.php?view=boxhistory&opt=all");
    }
    
}

?>
 