<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Assicurati di essere nella root di Sparks

$mail = new PHPMailer(true);

try {
    // SERVER SETTINGS
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;              // Abilita il debug dettagliato
    $mail->isSMTP();                                    // Usa SMTP
    $mail->Host       = 'smtp-mail.outlook.com';           // Server Outlook
    $mail->SMTPAuth   = true;                           // Abilita autenticazione
    $mail->Username   = 'efestotech@outlook.it';          // Il tuo indirizzo email
    $mail->Password   = 'tdrooogowdbgdxmf';          // La password per le app
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Obbligatorio per Outlook
    $mail->Port       = 587;                            // Porta standard per STARTTLS

    // RECIPIENTS
    $mail->setFrom('efestotech@outlook.it', 'Efesto Tech');
    $mail->addAddress('marco.spinelli89@outlook.it');

    // CONTENT
    $mail->isHTML(true);
    $mail->Subject = 'Debug SMTP Outlook';
    $mail->Body    = 'Se leggi questo, la connessione funziona!';

    echo "<pre>";
    $mail->send();
    echo "</pre>";
    echo "<h2 style='color:green'>Inviato con successo!</h2>";

} catch (Exception $e) {
    echo "</pre>";
    echo "<h2 style='color:red'>Errore nell'invio:</h2>";
    echo "L'errore PHPMailer: {$mail->ErrorInfo}";
}
