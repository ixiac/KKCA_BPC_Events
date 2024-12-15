<?php
session_start();
include("../partial/db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

if (isset($_POST['APID']) && isset($_POST['email']) && isset($_POST['eventName'])) {
    $APID = $_POST['APID'];
    $email = $_POST['email'];
    $eventName = $_POST['eventName'];

    error_log("APID: $APID, Email: $email, Event Name: $eventName");

    $sql = "UPDATE appointment SET status = 1 WHERE APID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $APID);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            error_log("Database update successful for APID: $APID");

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'kkca.bpc.events@gmail.com';
                $mail->Password = 'txcy efwu nnfp lbjf';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('kkca.bpc.events@gmail.com', 'KKCA BPC Events');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "Event Approved: $eventName";
                $mail->Body = "
                    <p>Hello,</p>
                    <p>We are pleased to inform you that your event request <strong>'$eventName'</strong> has been approved.</p>
                    <p>Thank you for trusting KKCA BPC Events for your event planning needs.</p>
                    <p>Best regards,</p>
                    <p>KKCA BPC Events Team</p>
                ";

                $mail->send();
                error_log("Email sent successfully to: $email");
                echo "Event approved and email sent.";
            } catch (Exception $e) {
                error_log("Email error: " . $mail->ErrorInfo);
                echo "Event approved but email could not be sent.";
            }
        } else {
            error_log("No rows updated for APID: $APID");
            echo "Failed to approve event.";
        }
    } else {
        error_log("Database error: " . $stmt->error);
        echo "Database query failed.";
    }
    $stmt->close();
    $conn->close();
} else {
    error_log("Missing required POST data");
    echo "Invalid request. Missing required data.";
}
?>