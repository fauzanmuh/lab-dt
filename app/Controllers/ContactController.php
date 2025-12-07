<?php

namespace App\Controllers;

use Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController
{
    public function send()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $organization = $_POST['organization'];
        $message = $_POST['message'] ?? '-';

        // Kirim email ke owner
        $this->sendMail(
            "lab.datatech@gmail.com",
            "New Contact Message",
            $this->templateOwner($name, $email, $organization, $message)
        );

        // Kirim copy ke user
        $this->sendMail(
            $email,
            "Thanks for reaching out!",
            $this->templateUser($name, $organization, $message)
        );

        // Redirect atau return response
        $_SESSION['flash_success'] = 'Message sent successfully!';
        header("Location: /");
        exit();
    }

    private function sendMail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP Config
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pbl.labdt@gmail.com';
            $mail->Password = 'zwln apxf nlcp nlrn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender
            $mail->setFrom('pbl.labdt@gmail.com', 'PBL Lab DT');

            // Receiver
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            return $mail->send();
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
    }

    private function templateOwner($name, $email, $organization, $message)
    {
        return "
    <div style='font-family: Arial, sans-serif; background: #f7f9fb; padding: 30px;'>
        <div style='max-width:600px; margin: auto; background: #ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.05);'>
            
            <div style='background:#4a6cf7; padding:20px; text-align:center; color:white;'>
                <img src='/assets/images/jti.png' alt='Logo' style='margin-bottom:10px;'>
                <h2 style='margin:0; font-size:22px;'>New Contact Message</h2>
            </div>

            <div style='padding:25px; color:#333;'>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Organization:</strong> $organization</p>
                <p><strong>Message:</strong><br>$message</p>
            </div>

            <div style='background:#f1f3f7; padding:15px; text-align:center; font-size:12px; color:#777;'>
                This is an automated notification email.
            </div>
        </div>
    </div>
    ";
    }

    private function templateUser($name, $organization, $message)
    {
        return "
    <div style='font-family: Arial, sans-serif; background:#f9fafc; padding:30px;'>
        <div style='max-width:600px; margin:auto; background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.05);'>
            
            <div style='background:#6a77ff; padding:20px 20px 25px; text-align:center; color:white;'>
                <img src='/assets/images/jti.png' alt='Logo' style='margin-bottom:10px;'>
                <h2 style='margin:0; font-size:22px;'>Thanks for Reaching Out!</h2>
            </div>

            <div style='padding:25px; color:#333;'>
                <p>Hi <strong>$name</strong>,</p>
                <p>Thanks for contacting us! Here's a copy of what you sent:</p>

                <p><strong>Organization:</strong> $organization</p>
                <p><strong>Your Message:</strong><br>$message</p>

                <br>
                <a href='#' style='display:inline-block; padding:12px 20px; background:#6a77ff; color:white; text-decoration:none; border-radius:8px;'>
                    Visit Our Website
                </a>

                <br><br>
                <p style='color:#666;'>We'll get back to you ASAP</p>
            </div>

            <div style='background:#f1f3f7; padding:15px; text-align:center; font-size:12px; color:#777;'>
                © " . date('Y') . " Lab DT. All rights reserved.
            </div>

        </div>
    </div>
    ";
    }
}