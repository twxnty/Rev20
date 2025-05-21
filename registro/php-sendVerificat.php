<?php

    $gmail = "israel.lainesm@educem.net";
    $contraseña = "dwbm sgit ajma lfnx";

    use  PHPMailer\PHPMailer\PHPMailer;
    require  'vendor/autoload.php';
    $mail  =  new  PHPMailer();
    $mail->IsSMTP();
    //Configuració  del  servidor  de  Correu
    //Modificar  a  0  per  eliminar  msg  error
    $mail->SMTPDebug  =  0;
    $mail->SMTPAuth  =  true;
    $mail->SMTPSecure  =  'tls';
    $mail->Host  =  'smtp.gmail.com';
    $mail->Port  =  587;

    //Credencials  del  compte  GMAIL
    $mail->Username  =  $gmail;
    $mail->Password  =  $contraseña;

    //Dades del correu electrònic
    $mail->SetFrom($gmail,'Rev. 20!');
    $mail->Subject='Codigo de activacion de Rev20!';
    $mail->MsgHTML("
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f3f3f3;
                margin: 0;
                padding: 0;
            }
            .container {
                background-color: #ffffff;
                border-radius: 10px;
                padding: 30px;
                max-width: 600px;
                margin: 30px auto;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                text-align: center;
            }
            h1 {
                color: #111;
                font-weight: 700;
                margin-bottom: 10px;
            }
            p {
                color: #333;
                font-size: 16px;
            }
            .btn {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 25px;
                background-color: #000000;
                color: #ffffff;
                text-decoration: none;
                border-radius: 30px;
                font-weight: 600;
                font-size: 16px;
                transition: background-color 0.3s ease;
            }
            .btn:hover {
                background-color: #333333;
            }
            .footer {
                margin-top: 30px;
                font-size: 12px;
                color: #888888;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>¡Bienvenido a Rev. 20!</h1>
            <p>Gracias por registrarte. Para activar tu cuenta, simplemente haz clic en el botón a continuación:</p>
            <a class='btn' href='http://localhost/M9-PROYECTO/registro/php-ActiveAccount.php?token=$token'>Activar cuenta</a>
            <p class='footer'>Si no has solicitado esta cuenta, puedes ignorar este mensaje.</p>
        </div>
    </body>
    </html>
    ");


    //Destinatari
    $address=$correo;
    $mail->AddAddress($address,'Test');
    //Enviament
    $result=$mail->Send();
    if(!$result){
        echo'Error:'.$mail->ErrorInfo;
    }
    
?>