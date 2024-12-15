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

    $sql = "UPDATE appointment SET status = 3 WHERE APID = ?";
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
                $mail->Subject = "Event Cancelled: $eventName";
                $mail->Body = "
                    <p>Hello,</p>
                    <p>We regret to inform you that your event <strong>$eventName</strong> has been cancelled.</p>
                    <p>If you have any questions or need further assistance, please contact us.</p>
                    <p>Best Regards,</p>
                    <p>KKCA BPC Events Team</p>
                ";

                $mail->send();
                echo "Event cancelled successfully and notification email sent.";
            } catch (Exception $e) {
                echo "Event cancelled successfully, but email could not be sent. Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Failed to cancel event.";
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
