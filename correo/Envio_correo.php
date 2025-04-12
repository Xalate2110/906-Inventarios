<?php
$correo_recibe = $_POST['userEmail'];
$folio_factura = $_POST['id_cancelacion3'];

if(!empty($_POST["send"])) {
    require_once ('phpmailer/class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SetLanguage("en");
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = TRUE;
    
    $mail->Port = 587;
    $mail->Username = "sistema.alvacar@gmail.com";
    $mail->Password = "tzhridkbtphxfbhh";
  
    $mail->Mailer = "smtp";
    
    if (isset($_POST["userEmail"])) {
        $userEmail = $_POST["userEmail"];
    }
    if (isset($_POST["userName"])) {
        $userName = $_POST["userName"];
    }
    if (isset($_POST["subject"])) {
        $subject = $_POST["subject"];
    }
    if (isset($_POST["userMessage"])) {
        $message = $_POST["userMessage"];
    }
    $mail->SetFrom($userEmail, $userName);
    $mail->AddReplyTo($userEmail, $userName);
    $mail->AddAddress($correo_recibe); // set recipient email address
    
    $mail->Subject = $subject;
    $mail->WordWrap = 80;
    $mail->MsgHTML($message);
    
    $mail->IsHTML(true);
    
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';

    $archivo1 = 'C:/xampp/htdocs/distribuidora_athena/core/app/facturacion/facturas/'.$folio_factura.'/'.$folio_factura.'.xmlcomprobanteTimbrado.xml';
    $archivo2 = 'C:/xampp/htdocs/distribuidora_athena/core/app/facturacion/facturas/'.$folio_factura.'/'.$folio_factura.'.xmlcomprobanteTimbrado.xml.pdf';

    $mail->AddAttachment($archivo1,'Factura.xml');
    $mail->AddAttachment($archivo2,'Factura.pdf');

    /*
    if (! empty($_FILES['attachment'])) {
        $count = count($_FILES['attachment']['name']);
        if ($count > 0) {
            // Attaching multiple files with the email
            for ($i = 0; $i < $count; $i ++) {
                if (! empty($_FILES["attachment"]["name"])) {
                    
                    $tempFileName = $_FILES["attachment"]["tmp_name"][$i];
                    $fileName = $_FILES["attachment"]["name"][$i];
                    $mail->AddAttachment($tempFileName, $fileName);
                }
            }
        }
    } */



    if (! $mail->Send()) {
        echo'<script type="text/javascript">
        alert("Se ha detectado un error al enviar la factura al correo ingresado '."$correo_recibe".' ahora se le redireccionara al listado de Facturas");
        window.location.href="http://server-alvacar.servehttp.com/distribuidora_athena/?view=facturas_general";
        </script>';
        $type = "error";
    } else {
        echo'<script type="text/javascript">
        alert("Se ha enviado correctamente la factura al correo ingresado '."$correo_recibe".' ahora se le redireccionara al listado de Facturas");
        window.location.href="http://server-alvacar.servehttp.com/distribuidora_athena/?view=facturas_general";
        </script>';
        $type = "success";

     

    }
}
 