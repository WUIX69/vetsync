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
        $subject = "Appointment Confirmed - J.A.A Veterinary Clinic";

        $formattedDate = date('F j, Y', strtotime($appointmentDate));
        $formattedTime = $appointmentTime ? date('g:i A', strtotime($appointmentTime)) : 'TBA';

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
                .content { background: white; padding: 30px; }
                .details { background: #f3f4f6; padding: 20px; margin: 20px 0; border-left: 4px solid #2563eb; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin: 0;'>J.A.A Veterinary Clinic</h2>
                    <p style='margin: 5px 0 0 0;'>Professional Pet Care</p>
                </div>
                
                <div class='content'>
                    <h3 style='color: #2563eb;'>Appointment Confirmed</h3>
                    <p>Dear {$userName},</p>
                    <p>Your appointment has been confirmed. Please see details below:</p>
                    
                    <div class='details'>
                        <strong>Pet:</strong> {$petName}<br>
                        <strong>Service:</strong> {$serviceName}<br>
                        <strong>Date:</strong> {$formattedDate}<br>
                        <strong>Time:</strong> {$formattedTime}
                    </div>
                    
                    <p>Please arrive 10 minutes early. If you need to reschedule, contact us at least 24 hours in advance.</p>
                    
                    <p>Thank you,<br><strong>J.A.A Veterinary Clinic</strong><br>Tel: (02) 8888-8888</p>
                </div>
                
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
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
        $subject = "Order Ready for Pickup - J.A.A Veterinary Clinic";

        $formattedDate = date('F j, Y', strtotime($reservationDate));
        $formattedAmount = number_format($totalAmount, 2);

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #16a34a; color: white; padding: 20px; text-align: center; }
                .content { background: white; padding: 30px; }
                .details { background: #f0fdf4; padding: 20px; margin: 20px 0; border-left: 4px solid #16a34a; }
                .warning { background: #fef3c7; padding: 15px; margin: 20px 0; border-left: 4px solid #f59e0b; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin: 0;'>J.A.A Veterinary Clinic</h2>
                    <p style='margin: 5px 0 0 0;'>Professional Pet Care</p>
                </div>
                
                <div class='content'>
                    <h3 style='color: #16a34a;'>Order Ready for Pickup</h3>
                    <p>Dear {$userName},</p>
                    <p>Your order is now ready for pickup at our clinic.</p>
                    
                    <div class='details'>
                        <strong>Order Date:</strong> {$formattedDate}<br>
                        <strong>Total Amount:</strong> ₱{$formattedAmount}<br>
                        <strong>Items:</strong><br>{$productNames}
                    </div>
                    
                    <div class='warning'>
                        <strong>⚠️ Important:</strong> Please pick up within 3 days or the order will be automatically cancelled.
                    </div>
                    
                    <p><strong>Pickup Hours:</strong><br>
                    Monday - Friday: 8:00 AM - 6:00 PM<br>
                    Saturday: 8:00 AM - 4:00 PM<br>
                    Sunday: Closed</p>
                    
                    <p>Please bring a valid ID for verification.</p>
                    
                    <p>Thank you,<br><strong>J.A.A Veterinary Clinic</strong><br>Tel: (02) 8888-8888</p>
                </div>
                
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }

    // Send account verification/validation notification
    public function sendAccountValidated($userEmail, $userName)
    {
        $subject = "Account Verified - J.A.A Veterinary Clinic";

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #16a34a; color: white; padding: 20px; text-align: center; }
                .content { background: white; padding: 30px; }
                .highlight { background: #f0fdf4; padding: 20px; margin: 20px 0; border-left: 4px solid #16a34a; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
                .welcome-features { background: #f3f4f6; padding: 15px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin: 0;'>J.A.A Veterinary Clinic</h2>
                    <p style='margin: 5px 0 0 0;'>Professional Pet Care</p>
                </div>
                    
                    <div class='content'>
                        <h2 style='color: #16a34a;'>Account Verified</h2>
                        <p>Dear {$userName},</p>
                        <p>Your account has been verified and is now active.</p>
                        
                        <div class='highlight'>
                            <strong>Status:</strong> Active<br>
                            <strong>Verified:</strong> " . date('F j, Y') . "
                        </div>
                        
                        <div class='welcome-features'>
                            <strong>You can now:</strong><br>
                            • Book appointments<br>
                            • Order products<br>
                            • View medical records<br>
                            • Receive notifications
                        </div>
                        
                        <p>Welcome to J.A.A Veterinary Clinic!</p>
                        
                        <p>Thank you,<br><strong>J.A.A Veterinary Clinic</strong><br>Tel: (02) 8888-8888</p>
                    </div>
                    
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
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
        $subject = "Welcome to J.A.A Veterinary Clinic";

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
                .content { background: white; padding: 30px; }
                .details { background: #fef3c7; padding: 20px; margin: 20px 0; border-left: 4px solid #f59e0b; }
                .info { background: #f3f4f6; padding: 15px; margin: 15px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin: 0;'>J.A.A Veterinary Clinic</h2>
                    <p style='margin: 5px 0 0 0;'>Professional Pet Care</p>
                </div>
                
                <div class='content'>
                    <h3 style='color: #2563eb;'>Welcome!</h3>
                    <p>Dear {$userName},</p>
                    <p>Thank you for registering with J.A.A Veterinary Clinic.</p>
                    
                    <div class='details'>
                        <strong>Status:</strong> Pending Verification<br>
                        <strong>Email:</strong> {$userEmail}<br>
                        <strong>Registered:</strong> " . date('F j, Y') . "
                    </div>
                    
                    <div class='info'>
                        <strong>What's Next:</strong><br>
                        • Account verification (24-48 hours)<br>
                        • You'll receive an email once approved<br>
                        • Then you can book appointments and order products
                    </div>
                    
                    <p>Thank you,<br><strong>J.A.A Veterinary Clinic</strong><br>Tel: (02) 8888-8888</p>
                </div>
                
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }
}