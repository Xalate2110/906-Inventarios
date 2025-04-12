
<!DOCTYPE html><!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.0.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2024 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
-->
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>GHA - FACTURACIÓN</title>
    <link rel="apple-touch-icon" sizes="57x57" href="assets/favicon/inicio.ico">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/favicon/inicio.ico">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/favicon/inicio.ico">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon/inicio.ico">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/favicon/inicio.ico">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/favicon/inicio.ico">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/favicon/inicio.ico">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/favicon/inicio.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/inicio.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon/inicio.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/inicio.ico">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/inicio.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/inicio.ico">
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/icono.ico">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="css/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="css/style.css" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="css/examples.css" rel="stylesheet">
    <!-- We use those styles to style Carbon ads and CoreUI PRO banner, you should remove them in your application.-->
    <link href="css/ads.css" rel="stylesheet">
    <script src="js/config.js"></script>
    <script src="js/color-modes.js"></script>
    <link href="vendors/@coreui/chartjs/css/coreui-chartjs.css" rel="stylesheet">
    <script src="plugins/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="plugins/datatables-new/dataTables.css">
    <script src="plugins/morris/raphael-min.js"></script>
    <script src="plugins/morris/morris.js"></script>
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <link rel="stylesheet" href="plugins/morris/example.css">
    <link href="plugins/apexcharts/apexcharts.css" rel="stylesheet">
    <script type="text/javascript" src="plugins/apexcharts/apexcharts.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="assets/select2/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/select2-bootstrap-5/select2-bootstrap-5-theme.min.css">
    <script type="text/javascript" src="plugins/jspdf/jspdf.min.js"></script>
    <script type="text/javascript" src="plugins/jspdf/jspdf.plugin.autotable.js"></script>
  </head>
  <body>
      <?php if(isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])):?>
    <div class="sidebar sidebar-narrow-unfoldable sidebar-dark sidebar-fixed border-end" id="sidebar">
      <div class="sidebar-header border-bottom">
        <div class="sidebar-brand" style="text-align: center;">

       <img class="sidebar-brand-full" width="230" height="100" src="./storage/configuration/menu.png"/>
      <!-- <h2 class="sidebar-brand-full" style="text-align: center;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ATHENA</h2> -->

      <!-- <img class="sidebar-brand-narrow" width="100" height="100" src="./storage/configuration/layout.png"/> -->
        <h4 class="sidebar-brand-narrow" style="text-align: center;">GHA</h4> 
      </div>

        <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
      </div>
      <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
      <?php if(isset($_SESSION["user_id"])):?>        
      <li class="nav-item"><a class="nav-link" href="./">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-home"></use>
            </svg> Inicio<!--<span class="badge badge-sm bg-info ms-auto">NEW</span>--></a></li>
            <?php if (Core::$user->kind == 1 || Core::$user->kind == 3 || Core::$user->kind == 4): ?>
              <li class="nav-item"><a class="nav-link" href="./?view=sellnew">
              <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-star"></use>
           </svg> Punto de Venta<!--<span class="badge badge-sm bg-info ms-auto">NEW</span>--></a></li>
            <?php endif; ?>  

            <?php if (Core::$user->kind == 1 || Core::$user->kind == 3 || Core::$user->kind == 4): ?>
              <li class="nav-item"><a class="nav-link" href="./?view=cotizador">
              <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-star"></use>
           </svg> Generar Cotización<!--<span class="badge badge-sm bg-info ms-auto">NEW</span>--></a></li>
            <?php endif; ?>  
             
        
            <?php if (Core::$user->kind == 1 || Core::$user->kind == 4 || Core::$user->kind == 3 ||  Core::$user->kind == 5): ?>
           <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="sells"||$_GET["view"]=="bydeliver" ||$_GET["view"]=="bycob"||$_GET["view"]=="sellscancel"||$_GET["view"]=="search"||$_GET["view"]=="sellscredit"||$_GET["view"]=="creditos_liquidados"||$_GET["view"]=="onesell"||$_GET["view"]=="reporte_detallado_remisiones"||$_GET["view"]=="reporte_ventas_categoria"||$_GET["view"]=="prices")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
         
    
        <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cart"></use>
            </svg> Modulo Ventas</a>
          
          <?php $id_almacen =  StockData::getPrincipal()->id; ?>
          <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="./?view=sells"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Ventas del Día</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=sellscredit"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Ventas a Crédito</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=creditos_liquidados"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Créditos Pagados</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=bydeliver"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Ventas Por entregar</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=bycob"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Ventas Por Cobrar</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=sellscancel"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Ventas canceladas</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=search"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Buscar Existencia Producto</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=prices&stock=<?php echo StockData::getPrincipal()->id; ?>"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Administrador De Precios</a></li>

          </ul>
        </li>
        <?php endif; ?>  
       
        <?php if (Core::$user->kind == 1 || Core::$user->kind == 4 || Core::$user->kind == 3 ||  Core::$user->kind == 5): ?>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="cotprocesadas"||$_GET["view"]=="cotizations")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cart"></use>
            </svg> Modulo Cotizaciónes</a>
          <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="./?view=cotizations"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Cotizaciones</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=cotprocesadas"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Cot. Procesadas</a></li>
          </ul>
        </li>
        <?php endif; ?>  


        <?php if (Core::$user->kind == 1 || Core::$user->kind == 4 || Core::$user->kind == 3 ||  Core::$user->kind == 5  ||  Core::$user->kind == 2): ?>

        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="res"||$_GET["view"]=="byreceive" ||$_GET["view"]=="topay"||$_GET["view"]=="rescancel"||$_GET["view"]=="onere"||$_GET["view"]=="creditp"||$_GET["view"]=="creditos_liquidadosp"||$_GET["view"]=="reporte_detallado_compras")){ echo "show"; }?>"><a class="nav-link  nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-clock"></use>
            </svg> Modulo Compras</a>
          <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="./?view=re"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Registrar Nueva compra</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=res"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Compras</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=byreceive"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Compras Por Recibir</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=byscredit"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Compras a Crédito</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=rescancel"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Listado Compra Cancelada</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=creditp"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Listado Crédito Proveedor</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=creditos_liquidadosp"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Créd Pagado A Proveedor</a></li>
          </ul>
        </li>
        <?php endif; ?>  

        <?php if (Core::$user->kind == 1 || Core::$user->kind == 4 || Core::$user->kind == 3 ||  Core::$user->kind == 5): ?>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="products"||$_GET["view"]=="categories" ||$_GET["view"]=="brands"||$_GET["view"]=="clients"||$_GET["view"]=="providers"||$_GET["view"]=="newproduct"||$_GET["view"]=="editproduct"||$_GET["view"]=="productbycategory"||$_GET["view"]=="newclient"||$_GET["view"]=="editclient"||$_GET["view"]=="newprovider"||$_GET["view"]=="editprovider"||$_GET["view"]=="stocks"||$_GET["view"]=="services"||$_GET["view"]=="newservice"||$_GET["view"]=="editservice")){ echo "show"; }?>"><a class="nav-link nav-group-toggle " href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-storage"></use>
            </svg> Modulo Catalogos</a>
            <?php $id_almacen =  StockData::getPrincipal()->id; ?>
          <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="./?view=products&opt=all&stock=<?php echo StockData::getPrincipal()->id; ?>"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=productsdesactivados&opt=all&stock=<?php echo StockData::getPrincipal()->id; ?>"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Productos Desactivados</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=services&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Servicios</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=clients&opt=all&stock=<?php echo $id_almacen; ?>"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Clientes</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=providers&opt=all&stock=<?php echo $id_almacen; ?>"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Proveedores</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=stocks&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Sucursales</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=categories&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Categorias</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=brands&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Marcas</a></li>
           
          </ul>
        </li>
        <?php endif; ?>  

        <?php if (Core::$user->kind == 1 || Core::$user->kind == 4 || Core::$user->kind == 5): ?>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="credit"||$_GET["view"]=="makepayment" ||$_GET["view"]=="paymenthistory"||$_GET["view"]=="balance"||$_GET["view"]=="spends"||$_GET["view"]=="newspend"||$_GET["view"]=="editspend"||$_GET["view"]=="deposits"||$_GET["view"]=="newdeposit"||$_GET["view"]=="editdeposit"||$_GET["view"]=="boxhistory"||$_GET["view"]=="box")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-briefcase"></use>
            </svg> Modulo Contabilidad</a>
          <ul class="nav-group-items compact">
          <li class="nav-item"><a class="nav-link" href="./?view=razonessociales&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Razones Sociales</a></li>
            <!--<li class="nav-item"><a class="nav-link" href="./?view=facturas&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Facturas Por Sucursal</a></li> -->
      
            <li class="nav-item"><a class="nav-link" href="./?view=facturas_general&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado General Facturas</a></li>
        
           <li class="nav-item"><a class="nav-link" href="./?view=abonos&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Abonos</a></li> 
            <li class="nav-item"><a class="nav-link" href="./?view=newabono&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Registro Abono o Anticipo</a></li> 
            <li class="nav-item"><a class="nav-link" href="./?view=complementos&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Complementos</a></li> 
            <li class="nav-item"><a class="nav-link" href="./?view=listado_abonos_remisiones&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Generar Complemento</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=credit&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado De Créditos</a></li>

          </ul>
        </li>
        <?php endif; ?>  

        <?php if (Core::$user->kind == 1 || Core::$user->kind == 4 || Core::$user->kind == 3 ||  Core::$user->kind == 5): ?>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="smallbox"||$_GET["view"]=="boxhistory"||$_GET["view"]=="box")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-briefcase"></use>
            </svg> Modulo Caja</a>
          <ul class="nav-group-items compact">
          <li class="nav-item"><a class="nav-link" href="./?view=spends&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Registro Retiro / Abono</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=boxhistory&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Cortes</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=box"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Generar Corte Caja</a></li>
          </ul>
        </li>
        <?php endif; ?>  

        <?php if (Core::$user->kind == 1 || Core::$user->kind == 2 || Core::$user->kind == 4): ?>
       <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="inventary"||$_GET["view"]=="inventaries"||$_GET["view"]=="selectstock"||$_GET["view"]=="inventaryval"||$_GET["view"]=="ajuste_inv_sobrantes"||$_GET["view"]=="ajustes-sobrantes"||$_GET["view"]=="ajuste_inv_faltantes"||$_GET["view"]=="ajustes_faltantes"||$_GET["view"]=="ajuste_inv_general"||$_GET["view"]=="ajustes-generales"||$_GET["view"]=="informe_pedido")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-library"></use>
            </svg> Modulo Inventario</a>
          <ul class="nav-group-items compact">
   
            <li class="nav-item"><a class="nav-link" href="./?view=inventary&stock=<?php echo StockData::getPrincipal()->id; ?>"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Inventario Principal</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=inventary2&stock=<?php echo StockData::getPrincipal()->id; ?>"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Inventario Filtrado</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=inventaryval&stock=<?php echo StockData::getPrincipal()->id; ?>"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Valor Inventario</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=informe_pedido"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Informe De Pedido</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=ajuste_inv_sobrantes"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Entrada Producto</a></li>        
            <li class="nav-item"><a class="nav-link" href="./?view=ajustes-sobrantes"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Entrada Producto</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=ajuste_inv_faltantes"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Salida Producto</a></li>       
            <li class="nav-item"><a class="nav-link" href="./?view=ajustes_faltantes"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Salida Producto</a></li>  
            <li class="nav-item"><a class="nav-link" href="./?view=ajuste_inv_general"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Ajuste Inventario</a></li>  
            <li class="nav-item"><a class="nav-link" href="./?view=ajustes-generales"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Ajustes Inventario</a></li> 
            <li class="nav-item"><a class="nav-link" href="./?view=inventaries"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Inventario Global</a></li>
        </ul>
        <?php endif; ?>  


        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="selectstock" || $_GET["view"]=="listado_traspasos" || $_GET["view"]=="reporte_detallado_traspasos" ||$_GET["view"]=="traspscancel" )){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-library"></use>
            </svg> Modulo Traspasos</a>
          <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="./?view=selectstock"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Generar Traspaso</a></li>
           <li class="nav-item"><a class="nav-link" href="./?view=listado_traspasos"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Listado Traspasos</a></li>
           <li class="nav-item"><a class="nav-link" href="./?view=traspscancel"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Traspasos Cancelados</a></li>
        </ul>
        
        <?php if (Core::$user->kind == 1 || Core::$user->kind == 4 || Core::$user->kind == 3 ||  Core::$user->kind == 5): ?>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="dev"||$_GET["view"]=="devs")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-library"></use>
            </svg> Modulo Devoluciones</a>
          <ul class="nav-group-items compact">
          <li class="nav-item"><a class="nav-link" href="./?view=dev"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Devolucion</a></li>
          <li class="nav-item"><a class="nav-link" href="./?view=devs"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Listado Devoluciones</a></li>
          </ul>
      </li>
      <?php endif; ?>  
      <?php if (Core::$user->kind == 1 || Core::$user->kind == 4 ||  Core::$user->kind == 5): ?>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="inventorylog"||$_GET["view"]=="sellsbycat" ||$_GET["view"]=="sellreports"||$_GET["view"]=="resreport"||$_GET["view"]=="paymentreport"||$_GET["view"]=="paymentreport"||$_GET["view"]=="clientreports"||$_GET["view"]=="vendorreports"||$_GET["view"]=="popularproductsreport")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bar-chart"></use>
            </svg> Reportes Generales</a>
          <ul class="nav-group-items compact">
          <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="sells"||$_GET["view"]=="bydeliver" ||$_GET["view"]=="bycob"||$_GET["view"]=="sellscancel"||$_GET["view"]=="search"||$_GET["view"]=="sellscredit"||$_GET["view"]=="creditos_liquidados"||$_GET["view"]=="onesell"||$_GET["view"]=="reporte_detallado_remisiones"||$_GET["view"]=="reporte_ventas_categoria"||$_GET["view"]=="prices")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
          <svg class="nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cart"></use>
             </svg>Reportes Ventas</a>
           <ul class="nav-group-items compact">
             <li class="nav-item"><a class="nav-link" href="./?view=reporte_detallado_remisiones"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Reporte Detallado Ventas</a></li>
             <li class="nav-item"><a class="nav-link" href="./?view=reporte_ventas_categoria"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Reporte Venta Por Articulo</a></li>
             <li class="nav-item"><a class="nav-link" href="./?view=rep_utilidad_remisiones"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Reporte Utilidad Remisiones</a></li>
           </ul>
         </li>
         <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="res"||$_GET["view"]=="byreceive" ||$_GET["view"]=="topay"||$_GET["view"]=="rescancel"||$_GET["view"]=="onere"||$_GET["view"]=="creditp"||$_GET["view"]=="creditos_liquidadosp"||$_GET["view"]=="reporte_detallado_compras")){ echo "show"; }?>"><a class="nav-link  nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-clock"></use>
            </svg> Reportes Compras</a>
          <ul class="nav-group-items compact">
          <li class="nav-item"><a class="nav-link" href="./?view=reporte_detallado_compras"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Reporte Detallado Compra</a></li>
             </ul>
        </li>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="selectstock" || $_GET["view"]=="listado_traspasos" || $_GET["view"]=="reporte_detallado_traspasos" ||$_GET["view"]=="traspscancel" )){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-library"></use>
            </svg> Reportes Traspasos</a>
          <ul class="nav-group-items compact">
           <li class="nav-item"><a class="nav-link" href="./?view=reporte_detallado_traspasos"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>Reporte Detallado Traspasos</a></li>
        </ul>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="credit"||$_GET["view"]=="makepayment" ||$_GET["view"]=="paymenthistory"||$_GET["view"]=="balance"||$_GET["view"]=="spends"||$_GET["view"]=="newspend"||$_GET["view"]=="editspend"||$_GET["view"]=="deposits"||$_GET["view"]=="newdeposit"||$_GET["view"]=="editdeposit"||$_GET["view"]=="boxhistory"||$_GET["view"]=="box")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-briefcase"></use>
            </svg> Reportes Contabilidad</a>
          <ul class="nav-group-items compact">
          <li class="nav-item"><a class="nav-link" href="./?view=reporte_completo_facturas&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Reporte Completo Facturas</a></li>
          <li class="nav-item"><a class="nav-link" href="./?view=reporte_facturas_especiales&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Reporte Facturas Especiales</a></li>
          <li class="nav-item"><a class="nav-link" href="./?view=reporte_facturas_canceladas&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Reporte Facturas Canceladas</a></li>
          <li class="nav-item"><a class="nav-link" href="./?view=reporte_gastos_retiros&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Reporte Gastos y Retiros</a></li>
          <li class="nav-item"><a class="nav-link" href="./?view=rep_utilidad_facturas&opt=all"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Reporte Utilidad Facturas</a></li>
          </ul>
        </li>
          

          </ul>
        </li>
        <?php endif; ?>  
        <?php if (Core::$user->kind == 1 || Core::$user->kind == 5): ?>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="users"||$_GET["view"]=="settings" ||$_GET["view"]=="import"||$_GET["view"]=="newuser"||$_GET["view"]=="edituser")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cog"></use>
            </svg> Administracion</a>
          <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="./?view=users"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Usuarios</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=import"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Importar Datos</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=backup&opt=step1"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Respaldar BD</a></li>

          </ul>
          <?php endif; ?>  
        </li>
    
 <?php elseif(isset($_SESSION["client_id"])):?>
  <li class="nav-item"><a class="nav-link" href="./">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-home"></use>
            </svg> Inicio<!--<span class="badge badge-sm bg-info ms-auto">NEW</span>--></a></li>
        <li class="nav-group <?php if(isset($_GET["view"]) && ($_GET["view"]=="sells"||$_GET["view"]=="bydeliver" ||$_GET["view"]=="bycob"||$_GET["view"]=="sellscancel"||$_GET["view"]=="cotizations"||$_GET["view"]=="sellscredit"||$_GET["view"]=="onesell")){ echo "show"; }?>"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cart"></use>
            </svg> Ventas</a>
          <ul class="nav-group-items compact">

            <li class="nav-item"><a class="nav-link" href="./?view=sells"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Ventas</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=sellscredit"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Ventas a credito</a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=bydeliver"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Ventas por Recibir</a></li>

          </ul>
        </li>
 <?php endif; ?>

      </ul>
      <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
      </div>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
      <header class="header header-sticky p-0 mb-4">
        <div class="container-fluid border-bottom px-4">
          <button class="header-toggler" type="button" onclick="toggle_btn()" style="margin-inline-start: -14px;">
            <svg class="icon icon-lg">
              <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
            </svg>
          </button>
          <script type="text/javascript">
            function toggle_btn(){
            coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()
            /*$.get("./?action=togglemainmenu","",function(dx){
            })*/
            }


          </script>
          <ul class="header-nav d-none d-lg-flex">
            <?php if(isset($_SESSION["user_id"])):?>
<li class="nav-item"><a class="nav-link" href="./?view=sellnew" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Punto de Venta">
                <svg class="icon icon-lg">
                  <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-calculator"></use>
                </svg></a></li>
<li class="nav-item"><a class="nav-link" href="./?view=mycashregister" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Caja Registradora">
                <svg class="icon icon-lg">
                  <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cash"></use>
                </svg></a></li>
              <?php endif; ?>
          </ul>
          <ul class="header-nav ms-auto">
                        <?php if(isset($_SESSION["user_id"])):?>

            <li class="nav-item"><a class="nav-link" href="./?view=alerts" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Alertas">
                <svg class="icon icon-lg">
                  <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bell"></use>
                </svg></a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=notifs" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Notificaciones">
                <svg class="icon icon-lg">
                  <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bell-exclamation"></use>
                </svg></a></li>
            <li class="nav-item"><a class="nav-link" href="./?view=messages&opt=all" data-coreui-toggle="tooltip" data-coreui-placement="bottom" title="Mensajes">
                <svg class="icon icon-lg">
                  <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
                </svg></a></li>
              <?php endif; ?>
          </ul>

          <ul class="header-nav">
            <li class="nav-item py-1">
              <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown">
              <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                <svg class="icon icon-lg theme-icon-active">
                  <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-contrast"></use>
                </svg>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="light">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-sun"></use>
                    </svg>Light
                  </button>
                </li>
                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-moon"></use>
                    </svg>Dark
                  </button>
                </li>
                <li>
                  <button class="dropdown-item d-flex align-items-center active" type="button" data-coreui-theme-value="auto">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-contrast"></use>
                    </svg>Auto
                  </button>
                </li>
              </ul>
            </li>
         
            <li class="nav-item py-1">
            <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>

            <?php $user = UserData::getById($_SESSION["user_id"]);?>
            <li class="nav-item dropdown"><a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <div>

            <span class="nav-item py-1"><?php
            if (isset($_SESSION["user_id"])) {
            echo UserData::getById($_SESSION["user_id"])->name;
            if (Core::$user->kind == 1) {
              echo " (Administrador)";
            } else if (Core::$user->kind == 2) {
              echo " (Almacenista)";
            } else if (Core::$user->kind == 3) {
              echo " (Vendedor - Mostrador)";
            } else if (Core::$user->kind == 4) {
              echo " (Administrador Sucursal)";
            }else if (Core::$user->kind == 5) {
              echo " (Contabilidad)";}
            
            } else if (isset($_SESSION["client_id"])) {
            echo PersonData::getById($_SESSION["client_id"])->name . "(Cliente)";
            }
            ?></span>
              
            <style>
            .avatar-img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            }  
            </style>
             <?php
             if($user->image!=""){
             $url = "storage/profiles/".$user->image;
             if(file_exists($url)){
             echo "<img class='avatar-img' src='$url'>";
            }
          }
           ?>  
           </div>
           </a>

              <div class="dropdown-menu dropdown-menu-end pt-0">
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">Configuración</div>
                <?php if(isset($_SESSION["user_id"])):?>
                <a class="dropdown-item" href="./?view=profile">
                  <svg class="icon me-2">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                  </svg>Mi Perfil</a>

                <a class="dropdown-item" href="./?view=messages&opt=all">
                  <svg class="icon me-2">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
                  </svg> Mensajes</a>

            

                  

                <div class="dropdown-divider"></div>
              <?php endif; ?>
                <a class="dropdown-item" href="./logout.php">
                  <svg class="icon me-2">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                  </svg> Cerrar sesi&oacute;n</a>
              </div>
            </li>
          </ul>
        </div>

      </header>
      <div class="body flex-grow-1">

        <div class="container-fluid px-4">
         
<?php View::load("index");?>




        </div>
      </div>
      <footer class="footer px-4">
        <div>Copyright &copy; 2024 - Sistema Alvacar</a></div>
        <div class="ms-auto">Version 2.0</div>
      </footer>
    </div>
    <?php else:?>
    <div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
        <?php if(isset($_GET["view"]) && $_GET["view"]=="clientaccess"):?>

  <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card-group d-block d-md-flex row">
              <div class="card col-md-7 p-4 mb-0">
                <div class="card-body">
                
                  <h1>Sistema Alvacar<small> Panel Cliente</small></h1>
                  <form action="./?action=processloginclient" method="post">
                  <p class="text-body-secondary">Acceder con tu nombre de usuario y contrase&ntilde;a</p>
                  <div class="input-group mb-3"><span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                      </svg></span>
                    <input class="form-control" name="username" type="text" placeholder="Nombre de usuario">
                  </div>
                  <div class="input-group mb-4"><span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                      </svg></span>
                    <input class="form-control" name="password" type="password" placeholder="Password">
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <button class="btn btn-primary px-4" type="submit">Acceder al Sistema</button>
                    </div>

                  </div>
                </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<?php else:?>
  <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card-group d-block d-md-flex row">
              <div class="card col-md-7 p-4 mb-0">
                <div class="card-body">
                <div class="login-logo">
                <div>
                <center>   <img style="margin-top:-12px" src="./storage/configuration/login.png"  height="250"> <b></b> </center>
                </div><!-- /.login-logo -->
                </div><!-- /.login-logo -->
                <br>
                     <center> <form action="./?action=processlogin" method="post">
                     <!--<center><p class="text-body-secondary">Acceder con tu nombre de usuario y contrase&ntilde;a</p></center> -->
                  <div class="input-group mb-3"><span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                      </svg></span>
                    <input class="form-control" name="username" type="text" placeholder="Nombre de usuario">
                  </div>
                  <div class="input-group mb-4"><span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                      </svg></span>
                    <input class="form-control" name="password" type="password" placeholder="Password">
                  </div>
                  <div class="input-group">
                    <div class="col-12">
                     <button class="btn btn-primary px-4" type="submit" >Acceder al Sistema </button>
                    </div>

                  </div>
                </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif;?>
    </div>
    <?php endif; ?>
    <!-- CoreUI and necessary plugins-->
    <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="vendors/simplebar/js/simplebar.min.js"></script>
    <script>
      const header = document.querySelector('header.header');

      document.addEventListener('scroll', () => {
        if (header) {
          header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
        }
      });
    </script>
    <!-- Plugins and scripts required by this view-->
    <script src="vendors/chart.js/js/chart.umd.js"></script>
    <script src="vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
    <script src="vendors/@coreui/utils/js/index.js"></script>
    <script src="js/main.js"></script>
    <script>
    </script>
<script src="plugins/datatables-new/datatables.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $(".datatable").DataTable({

          "pageLength":25,
          "language": {
        "sProcessing":    "Procesando...",
        "sLengthMenu":    "Mostrar _MENU_ registros",
        "sZeroRecords":   "No se encontraron resultados",
        "sEmptyTable":    "Ningún dato disponible en esta tabla",
        "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":   "",
        "sSearch":        "Buscar:",
        "sUrl":           "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":    "Último",
            "sNext":    "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
        });
      });
const tooltipTriggerList = document.querySelectorAll('[data-coreui-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new coreui.Tooltip(tooltipTriggerEl))
const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new coreui.Dropdown(dropdownToggleEl))
    </script>
    <script type="text/javascript" src="assets/select2/js/select2.full.min.js"></script>
<script type="text/javascript">

function toggle_btn2(){
coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()            
}

<?php if(isset($_SESSION['toggle_main']) && $_SESSION['toggle_main']==1):?>
$(document).ready(function(){
  toggle_btn2();
});
<?php endif;?>
toggle_btn2();

</script>
  </body>
</html>