<?php require_once "Envio_correo.php";?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Descargar formulario de contacto PHP con archivo adjunto - BaulPHP</title>

<!-- Bootstrap core CSS -->
<link href="dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="assets/sticky-footer-navbar.css" rel="stylesheet">
<link href="assets/estilo.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script type="text/javascript">


function validate() {
    var valid = true;
    $(".info").html("");
    var userName = document.forms["mailForm"]["userName"].value;
    var userEmail = document.forms["mailForm"]["userEmail"].value;
    var subject = document.forms["mailForm"]["subject"].value;
    var userMessage = document.forms["mailForm"]["userMessage"].value;
    
    if (userName == "") {
        $("#userName-info").html("(required)");
        $("#userName").css('background-color', '#FFFFDF');
        valid = false;
    }
    if (userEmail == "") {
        $("#userEmail-info").html("(required)");
        $("#userEmail").css('background-color', '#FFFFDF');
        valid = false;
    }
    if (!userEmail.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/))
    {
        $("#userEmail-info").html("(invalid)");
        $("#userEmail").css('background-color', '#FFFFDF');
        valid = false;
    }

    if (subject == "") {
        $("#subject-info").html("(required)");
        $("#subject").css('background-color', '#FFFFDF');
        valid = false;
    }
    if (userMessage == "") {
        $("#userMessage-info").html("(required)");
        $("#userMessage").css('background-color', '#FFFFDF');
        valid = false;
    }
    return valid;
}

function AgregarArchivos() {
    $(".attachment-row:last").clone().insertAfter(".attachment-row:last");
    $(".attachment-row:last").find("input").val("");
}
</script>
</head>

<body>

<!-- Begin page content -->

<div class="container">
  <h3 class="mt-5">Formulario de Contacto con archivo adjunto</h3>
  <div class="row">
    <div class="col-12 col-md-6"> 
      <!-- Contenido -->
      
     
        <form name="mailForm" id="mailForm" method="post" action="" enctype="multipart/form-data" onsubmit="return validate()">
          <div class="form-group">
            <label style="padding-top: 20px;">Nombres</label>
            <span id="userName-info" class="info"></span><br />
            <input type="text" class="form-control" name="userName" id="userName" />
          </div>
          <div class="form-group">
            <label>Email</label>
            <span id="userEmail-info" class="info"></span><br />
            <input type="text" class="form-control" name="userEmail" id="userEmail" />
          </div>
          <div class="form-group">
            <label>Asunto</label>
            <span id="subject-info" class="info"></span><br />
            <input type="text" class="form-control" name="subject" id="subject" />
          </div>
          <div class="form-group">
            <label>Mensaje / Contenido</label>
            <span id="userMessage-info" class="info"></span><br />
            <textarea name="userMessage" id="userMessage" class="form-control" cols="40" rows="6">
            </textarea>
          </div>
          <div class="attachment-row">
            <input type="file" class="input-field" name="attachment[]">
          </div>
          <div onClick="AgregarArchivos();" class="icon-add-more-attachemnt" title="Agregar más archivos"> <img src="image/addthis.png" alt="Agregar más archivos"> </div>
          <div>
            <input type="submit" name="send" class="btn btn-primary" value="Enviar Ahora" />
            <div id="statusMessage">
              <?php
                if (! empty($message)) {
                    ?>
              <p class='<?php echo $type; ?>Message'><?php echo $message; ?></p>
              <?php
                }
                ?>
            </div>
          </div>
        </form>

      <!-- Fin Contenido --> 
    </div>
  </div>
  <!-- Fin row --> 
  
</div>
<!-- Fin container -->
<footer class="footer">
  <div class="container"> <span class="text-muted">
    <p>Códigos <a href="https://www.baulphp.com/" target="_blank">BaulPHP</a></p>
    </span> </div>
</footer>

<!-- Bootstrap core JavaScript
================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 


<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>