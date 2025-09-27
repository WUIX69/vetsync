<?php

namespace VetSync\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    private $config;
    private $mailer;

    public function __construct()
    {
        // Load email configuration
        $this->config = include __DIR__ . '/../core/email-config.php';
        $this->setupMailer();
    }

    private function setupMailer()
    {
        $this->mailer = new PHPMailer(true);

        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp']['host'];
            $this->mailer->SMTPAuth = $this->config['smtp']['auth'];
            $this->mailer->Username = $this->config['smtp']['username'];
            $this->mailer->Password = $this->config['smtp']['password'];
            $this->mailer->SMTPSecure = $this->config['smtp']['encryption'];
            $this->mailer->Port = $this->config['smtp']['port'];

            // Default sender (your clinic)
            $this->mailer->setFrom(
                $this->config['smtp']['from_email'],
                $this->config['smtp']['from_name']
            );

            // Character set
            $this->mailer->CharSet = 'UTF-8';
        } catch (Exception $e) {
            error_log("Email setup error: " . $e->getMessage());
        }
    }

    // Send email to user's Gmail (or any email)
    public function send($to, $subject, $message, $attachments = [])
    {
        try {
            // Clear previous recipients
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();

            // Add recipient (user's email - could be Gmail, Yahoo, etc.)
            if (is_array($to)) {
                foreach ($to as $email => $name) {
                    if (is_numeric($email)) {
                        $this->mailer->addAddress($name);
                    } else {
                        $this->mailer->addAddress($email, $name);
                    }
                }
            } else {
                $this->mailer->addAddress($to);
            }

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $message;

            // Attachments
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    if (is_array($attachment)) {
                        $this->mailer->addAttachment($attachment['path'], $attachment['name'] ?? '');
                    } else {
                        $this->mailer->addAttachment($attachment);
                    }
                }
            }

            $this->mailer->send();
            return [
                'success' => true,
                'message' => 'Email sent successfully to user'
            ];
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Email could not be sent. Error: ' . $e->getMessage()
            ];
        }
    }

    // Send appointment confirmation to user's email
    public function sendAppointmentConfirmation($userEmail, $userName, $petName, $serviceName, $appointmentDate, $appointmentTime)
    {
        $subject = "🎉 Appointment Confirmed - VetSync";

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; }
                .email-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .content { margin-bottom: 30px; }
                .highlight { background: #e7f3ff; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; margin: 20px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='email-box'>
                    <div class='header'>
                        <h1>🐾 VetSync</h1>
                        <p>Professional Veterinary Care</p>
                    </div>
                    
                    <div class='content'>
                        <h2>🎉 Appointment Confirmed!</h2>
                        <p>Dear {$userName},</p>
                        <p>Your appointment has been successfully confirmed! Here are the details:</p>
                        
                        <div class='highlight'>
                            <strong>📅 Appointment Details:</strong><br>
                            <strong>Pet:</strong> {$petName}<br>
                            <strong>Service:</strong> {$serviceName}<br>
                            <strong>Date:</strong> " . date('F j, Y', strtotime($appointmentDate)) . "<br>
                            <strong>Time:</strong> " . ($appointmentTime ? date('g:i A', strtotime($appointmentTime)) : 'To be scheduled') . "
                        </div>
                        
                        <p>We look forward to seeing you and {$petName} at our clinic!</p>
                        <p>If you need to reschedule or have any questions, please contact us.</p>
                        
                        <p>Best regards,<br>The VetSync Team</p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated message from VetSync.<br>Please do not reply to this email.</p>
                        <p>© " . date('Y') . " VetSync. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }

    // Send pet deceased notification to user's email
    public function sendPetDeceasedNotification($userEmail, $userName, $petName, $cancelledAppointments = 0)
    {
        $subject = "🌈 Our Condolences - VetSync";

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; }
                .email-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .content { margin-bottom: 30px; }
                .highlight { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='email-box'>
                    <div class='header'>
                        <h1>🐾 VetSync</h1>
                        <p>Professional Veterinary Care</p>
                    </div>
                    
                    <div class='content'>
                        <h2>🌈 Our Deepest Condolences</h2>
                        <p>Dear {$userName},</p>
                        <p>We are deeply sorry for the loss of {$petName}. Our thoughts are with you during this difficult time.</p>
                        
                        <div class='highlight'>
                            <strong>📋 Account Update:</strong><br>
                            {$petName}'s status has been updated in our system.<br>";

        if ($cancelledAppointments > 0) {
            $message .= "We have automatically cancelled {$cancelledAppointments} upcoming appointment(s).<br>";
        }

        $message .= "All medical records will be preserved for your reference.
                        </div>
                        
                        <p>The love and joy that {$petName} brought to your life will always be remembered. Pets leave paw prints on our hearts forever.</p>
                        <p>If you need any assistance accessing medical records or have any questions, please don't hesitate to contact us.</p>
                        
                        <p>With heartfelt sympathy,<br>The VetSync Team</p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated message from VetSync.<br>Please do not reply to this email.</p>
                        <p>© " . date('Y') . " VetSync. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }

    // Send product pickup notification to user's email
    public function sendPickupNotification($userEmail, $userName, $productNames, $reservationDate, $totalAmount)
    {
        $subject = "📦 Your Order is Ready for Pickup - VetSync";

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; }
                .email-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .content { margin-bottom: 30px; }
                .highlight { background: #d4edda; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745; margin: 20px 0; }
                .product-list { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 20px; }
                .pickup-info { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='email-box'>
                    <div class='header'>
                        <h1>🐾 VetSync</h1>
                        <p>Professional Veterinary Care</p>
                    </div>
                    
                    <div class='content'>
                        <h2>📦 Your Order is Ready for Pickup!</h2>
                        <p>Dear {$userName},</p>
                        <p>Great news! Your product order is now ready for pickup at our clinic.</p>
                        
                        <div class='highlight'>
                            <strong>✅ Order Status:</strong> Ready for Pickup<br>
                            <strong>📅 Order Date:</strong> " . date('F j, Y', strtotime($reservationDate)) . "<br>
                            <strong>💰 Total Amount:</strong> ₱" . number_format($totalAmount, 2) . "
                        </div>
                        
                        <div class='product-list'>
                            <strong>📋 Products Ready:</strong><br>
                            {$productNames}
                        </div>
                        
                        <div class='pickup-info'>
                            <strong>�� Pickup Information:</strong><br>
                            <strong>Location:</strong> VetSync Veterinary Clinic<br>
                            <strong>Hours:</strong> Monday - Friday: 8:00 AM - 6:00 PM<br>
                            <strong>Saturday:</strong> 8:00 AM - 4:00 PM<br>
                            <strong>Note:</strong> Please bring a valid ID for pickup verification
                        </div>
                        
                        <p>Please pick up your order within <strong>7 days</strong> to ensure product quality and availability.</p>
                        <p>If you have any questions or need to arrange a different pickup time, please contact us.</p>
                        
                        <p>Thank you for choosing VetSync!<br>The VetSync Team</p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated message from VetSync.<br>Please do not reply to this email.</p>
                        <p>© " . date('Y') . " VetSync. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }

    // Send account verification/validation notification
    public function sendAccountValidated($userEmail, $userName)
    {
        $subject = "✅ Account Verified - Welcome to VetSync!";

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; }
                .email-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .content { margin-bottom: 30px; }
                .highlight { background: #d4edda; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745; margin: 20px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 20px; }
                .welcome-features { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='email-box'>
                    <div class='header'>
                        <h1>🐾 VetSync</h1>
                        <p>Professional Veterinary Care</p>
                    </div>
                    
                    <div class='content'>
                        <h2>�� Welcome to VetSync!</h2>
                        <p>Dear {$userName},</p>
                        <p>Congratulations! Your account has been successfully verified and is now active.</p>
                        
                        <div class='highlight'>
                            <strong>✅ Account Status:</strong> Verified and Active<br>
                            <strong>📅 Verification Date:</strong> " . date('F j, Y') . "<br>
                            <strong>🎯 Access Level:</strong> Full Access
                        </div>
                        
                        <div class='welcome-features'>
                            <strong>🌟 You can now enjoy all VetSync features:</strong><br>
                            • 📅 Book appointments for your pets<br>
                            • �� Order products and schedule pickups<br>
                            • 📋 Access your pet's medical history<br>
                            • 📧 Receive appointment reminders and updates<br>
                            • 💬 Contact our veterinary team
                        </div>
                        
                        <p>We're excited to help you take the best care of your beloved pets!</p>
                        
                        <p>Best regards,<br>The VetSync Team</p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated message from VetSync.<br>Please do not reply to this email.</p>
                        <p>© " . date('Y') . " VetSync. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }

    // Send account rejection notification
    public function sendAccountRejected($userEmail, $userName, $reason = '')
    {
        $subject = "❌ Account Verification Update - VetSync";

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; }
                .email-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .content { margin-bottom: 30px; }
                .highlight { background: #f8d7da; padding: 15px; border-radius: 5px; border-left: 4px solid #dc3545; margin: 20px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 20px; }
                .next-steps { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='email-box'>
                    <div class='header'>
                        <h1>🐾 VetSync</h1>
                        <p>Professional Veterinary Care</p>
                    </div>
                    
                    <div class='content'>
                        <h2>Account Verification Update</h2>
                        <p>Dear {$userName},</p>
                        <p>We regret to inform you that your account verification could not be completed at this time.</p>
                        
                        <div class='highlight'>
                            <strong>❌ Account Status:</strong> Verification Required<br>
                            <strong>📅 Review Date:</strong> " . date('F j, Y') . "<br>";

        if ($reason) {
            $message .= "<strong>�� Reason:</strong> " . htmlspecialchars($reason) . "<br>";
        }

        $message .= "</div>
                        
                        <div class='next-steps'>
                            <strong>📋 Next Steps:</strong><br>
                            • Please review your account information<br>
                            • Ensure all required documents are provided<br>
                            • Contact our support team for assistance<br>
                            • You may resubmit your verification request
                        </div>
                        
                        <p>If you have any questions or need assistance with the verification process, please don't hesitate to contact our support team.</p>
                        
                        <p>Best regards,<br>The VetSync Team</p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated message from VetSync.<br>Please do not reply to this email.</p>
                        <p>© " . date('Y') . " VetSync. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }

    // Send welcome email for new user registration
    public function sendWelcomeEmail($userEmail, $userName)
    {
        $subject = "�� Welcome to VetSync - Account Created!";

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; }
                .email-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .content { margin-bottom: 30px; }
                .highlight { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 20px; }
                .info-box { background: #e7f3ff; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='email-box'>
                    <div class='header'>
                        <h1>🐾 VetSync</h1>
                        <p>Professional Veterinary Care</p>
                    </div>
                    
                    <div class='content'>
                        <h2>�� Welcome to VetSync!</h2>
                        <p>Dear {$userName},</p>
                        <p>Thank you for creating an account with VetSync! We're excited to help you take the best care of your beloved pets.</p>
                        
                        <div class='highlight'>
                            <strong>⏳ Account Status:</strong> Pending Verification<br>
                            <strong>📅 Registration Date:</strong> " . date('F j, Y') . "<br>
                            <strong>�� Email:</strong> {$userEmail}
                        </div>
                        
                        <div class='info-box'>
                            <strong>📋 What's Next?</strong><br>
                            • Our team is reviewing your account information<br>
                            • You'll receive an email once verification is complete<br>
                            • Verification typically takes 24-48 hours<br>
                            • Once verified, you'll have full access to all features
                        </div>
                        
                        <p>While you wait, feel free to explore our website and learn more about our services.</p>
                        <p>If you have any questions, please don't hesitate to contact us.</p>
                        
                        <p>Welcome to the VetSync family!<br>The VetSync Team</p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated message from VetSync.<br>Please do not reply to this email.</p>
                        <p>© " . date('Y') . " VetSync. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }
}