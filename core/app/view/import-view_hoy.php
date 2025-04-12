<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1>Importar Datos</h1>
            <br>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
                Importar datos
            </button>

  
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Importar</h4>
                        </div>
                        <div class="modal-body">


                            <form class="form-horizontal" method="post" id="addproduct" action="index.php?action=import" enctype="multipart/form-data" role="form">

                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-2 control-label">Tipo</label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="kind" required>
                                            <option value="">-- SELECCIONE --</option>
                                            <option value="1">Productos</option>
                                     
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 control-label">Almacen</label>
                                <div class="col-md-10">
                                <select name="almacen" id ="almacen" class="form-control">
                                &nbsp;&nbsp;
                                <option value="">Todos los Almacenes</option>
                                <?php foreach(StockData::getAll() as $stock):?>
                                <option value="<?php echo $stock->id; ?>"><?php echo $stock->name; ?></option>
                                <?php endforeach; ?>
                                </select>
                                </div>
                                &nbsp;&nbsp;
                                
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-2 control-label">Archivo (.csv)*</label>
                                    <div class="col-md-10">
                                        <input type="file" name="name"  id="name" placeholder="Archivo (.csv)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button type="submit" class="btn btn-primary">Generar Proceso</button>
                                    </div>
                                </div>
                            </form>


                        </div>

                    </div>
                </div>
            </div>



</section>