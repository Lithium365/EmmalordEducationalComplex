<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $studentName = $_POST["student_name"];
    $dateOfBirth = $_POST["date_of_birth"];
    $parentName = $_POST["parent_name"];
    $contactNumber = $_POST["contact_number"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $previousSchool = $_POST["previous_school"] ?? "";
    $immunizationRecords = $_FILES["immunization_records"]["name"] ?? "";

    // Validate and sanitize data (you can add more validation)
    $studentName = htmlspecialchars(trim($studentName));
    $parentName = htmlspecialchars(trim($parentName));
    $contactNumber = filter_var($contactNumber, FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars(trim($address));
    $previousSchool = htmlspecialchars(trim($previousSchool));

    // Create a message
    $message = "New Admission Form Submission:\n\n";
    $message .= "Student's Name: $studentName\n";
    $message .= "Date of Birth: $dateOfBirth\n";
    $message .= "Parent/Guardian's Name: $parentName\n";
    $message .= "Contact Number: $contactNumber\n";
    $message .= "Email Address: $email\n";
    $message .= "Residential Address:\n$address\n";
    $message .= "Previous School: $previousSchool\n";

    // Save the message to a file (you might want to use a database in a real application)
    $file = fopen("admission_forms.txt", "a"); // Open file in append mode
    fwrite($file, $message . "\n\n");
    fclose($file);

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.elasticemail.com'; // Replace with your SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'Elwin'; // Replace with your email address
        $mail->Password   = '560141340B9369E8F2E6C4B127DBA76482B6C76450AC8C4411365B18D5835BCD5E1A5E39BA570DB2FE619E442C007DEE'; // Replace with your API key
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS (can be ENCRYPTION_SMTPS for SSL)
        $mail->Port       = 587; // Replace with your SMTP port

        // Recipients
        $mail->setFrom('brempongkumi1@gmail.com', 'Elwin');
        $mail->addAddress('brempongkumi21@gmail.com', 'Kumi');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Admission Form Submission';
        $mail->Body    = $message;

        // Attach immunization records if provided
        if ($immunizationRecords) {
            $mail->addAttachment($_FILES["immunization_records"]["tmp_name"], $immunizationRecords);
        }

        // Send the email
        $mail->send();

        // Redirect to a thank you page or back to the form
        header("Location: thank_you.html");
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // Redirect to the form if accessed directly without submission
    header("Location: ADMISSIONS.HTML ");
    exit();
}

?>
