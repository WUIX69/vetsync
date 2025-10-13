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

    // ✨ ENHANCED: Appointment Confirmation with all important details
    public function sendAppointmentConfirmation(
        $userEmail,
        $userName,
        $petName,
        $serviceName,
        $appointmentDate,
        $appointmentTime,
        $userPhone = '',
        $specialInstructions = '',
        $bookingReference = ''
    ) {
        $subject = "✅ Appointment Confirmed - J.A.A Veterinary Clinic";

        $formattedDate = date('F j, Y (l)', strtotime($appointmentDate));
        $formattedTime = $appointmentTime ? date('g:i A', strtotime($appointmentTime)) : 'To Be Assigned';
        $currentYear = date('Y');

        // Generate booking reference if not provided
        if (empty($bookingReference)) {
            $bookingReference = 'APT-' . strtoupper(substr(md5($userEmail . $appointmentDate), 0, 8));
        }

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
                .container { max-width: 650px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 30px; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { padding: 40px 30px; }
                .ref-badge { background: #dbeafe; color: #1e40af; padding: 15px; text-align: center; border-radius: 8px; margin: 25px 0; }
                .ref-badge .ref-number { font-size: 24px; font-weight: bold; letter-spacing: 2px; }
                .details-card { background: #f8fafc; border-left: 4px solid #2563eb; padding: 25px; margin: 25px 0; border-radius: 8px; }
                .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
                .detail-row:last-child { border-bottom: none; }
                .detail-label { font-weight: 600; width: 140px; color: #64748b; }
                .detail-value { color: #1e293b; flex: 1; }
                .reminder-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; margin: 25px 0; border-radius: 5px; }
                .contact-box { background: #f0fdf4; border-left: 4px solid #16a34a; padding: 20px; margin: 25px 0; border-radius: 5px; }
                .footer { background: #f8fafc; padding: 25px; text-align: center; color: #64748b; font-size: 14px; }
                .instructions-box { background: #fef2f2; border-left: 4px solid #dc2626; padding: 20px; margin: 25px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                    <div class='header'>
                    <h1>✅ Appointment Confirmed</h1>
                    <p style='margin: 10px 0 0 0;'>Your appointment has been successfully scheduled</p>
                </div>
                
                <div class='content'>
                    <p style='font-size: 16px;'>Dear <strong>{$userName}</strong>,</p>
                    <p>We're pleased to confirm your appointment at J.A.A Veterinary Clinic. Please review the details below:</p>
                    
                    <div class='ref-badge'>
                        <div style='font-size: 12px; color: #64748b; margin-bottom: 5px;'>BOOKING REFERENCE</div>
                        <div class='ref-number'>{$bookingReference}</div>
                    </div>
                    
                    <div class='details-card'>
                        <h3 style='margin-top: 0; color: #1e293b;'>📋 Appointment Details</h3>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>🐾 Pet Name:</div>
                            <div class='detail-value'><strong>{$petName}</strong></div>
                        </div>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>⚕️ Service:</div>
                            <div class='detail-value'><strong>{$serviceName}</strong></div>
                        </div>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>📅 Date:</div>
                            <div class='detail-value'><strong>{$formattedDate}</strong></div>
                        </div>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>🕐 Time:</div>
                            <div class='detail-value'><strong>{$formattedTime}</strong></div>
                        </div>
                    </div>
                    
                    " . (!empty($specialInstructions) ? "
                    <div class='instructions-box'>
                        <h4 style='margin-top: 0; color: #991b1b;'>📝 Special Instructions</h4>
                        <p style='margin: 0;'>{$specialInstructions}</p>
                    </div>
                    " : "") . "
                    
                    <div class='reminder-box'>
                        <h4 style='margin-top: 0; color: #b45309;'>⚠️ Important Reminders</h4>
                        <ul style='margin: 10px 0; padding-left: 20px;'>
                            <li>Please arrive <strong>10-15 minutes early</strong> for check-in</li>
                            <li>Bring your pet's previous medical records (if any)</li>
                            <li>Keep your pet on a leash or in a carrier</li>
                            <li>Fast your pet for 4-6 hours before surgery/procedures</li>
                            <li>To reschedule, contact us at least <strong>24 hours in advance</strong></li>
                        </ul>
                    </div>
                    
                    <div class='contact-box'>
                        <h4 style='margin-top: 0; color: #16a34a;'>📍 Clinic Information</h4>
                        <p style='margin: 5px 0;'><strong>J.A.A Veterinary Clinic</strong></p>
                        <p style='margin: 5px 0;'>📞 Phone: (02) 8888-8888" . (!empty($userPhone) ? " | Your contact: {$userPhone}" : "") . "</p>
                        <p style='margin: 5px 0;'>📧 Email: vetsync.01@gmail.com</p>
                        <p style='margin: 5px 0;'>🕐 Hours: Mon-Fri: 8:00 AM - 6:00 PM | Sat: 8:00 AM - 4:00 PM</p>
                    </div>
                    
                    <p style='text-align: center; margin-top: 30px;'>
                        We look forward to seeing you and <strong>{$petName}</strong>!
                    </p>
                </div>
                
                <div class='footer'>
                    <p>This confirmation was sent to {$userEmail}</p>
                    <p style='margin: 10px 0;'>Please save this email for your records.</p>
                    <p>© {$currentYear} J.A.A Veterinary Clinic. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";

        return $this->send($userEmail, $subject, $message);
    }

    // ✨ NEW: Send appointment reminder 2 days before
    public function sendAppointmentReminder(
        $userEmail,
        $userName,
        $petName,
        $serviceName,
        $appointmentDate,
        $appointmentTime,
        $bookingReference = '',
        $userPhone = ''
    ) {
        $subject = "⏰ Appointment Reminder - J.A.A Veterinary Clinic";

        $formattedDate = date('F j, Y (l)', strtotime($appointmentDate));
        $formattedTime = $appointmentTime ? date('g:i A', strtotime($appointmentTime)) : 'To Be Assigned';
        $currentYear = date('Y');

        // Calculate days until appointment
        $daysUntil = ceil((strtotime($appointmentDate) - time()) / 86400);

        // Generate booking reference if not provided
        if (empty($bookingReference)) {
            $bookingReference = 'APT-' . strtoupper(substr(md5($userEmail . $appointmentDate), 0, 8));
        }

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
                .container { max-width: 650px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 30px; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { padding: 40px 30px; }
                .countdown-badge { background: #fef3c7; color: #b45309; padding: 20px; text-align: center; border-radius: 8px; margin: 25px 0; border: 2px solid #f59e0b; }
                .countdown-badge .days { font-size: 48px; font-weight: bold; margin: 10px 0; }
                .details-card { background: #f8fafc; border-left: 4px solid #f59e0b; padding: 25px; margin: 25px 0; border-radius: 8px; }
                .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
                .detail-row:last-child { border-bottom: none; }
                .detail-label { font-weight: 600; width: 140px; color: #64748b; }
                .detail-value { color: #1e293b; flex: 1; }
                .warning-box { background: #fef2f2; border: 2px solid #dc2626; padding: 20px; margin: 25px 0; border-radius: 8px; }
                .warning-box h3 { color: #dc2626; margin: 0 0 15px 0; }
                .checklist-box { background: #dbeafe; border-left: 4px solid #2563eb; padding: 20px; margin: 25px 0; border-radius: 5px; }
                .contact-box { background: #f0fdf4; border-left: 4px solid #16a34a; padding: 20px; margin: 25px 0; border-radius: 5px; }
                .footer { background: #f8fafc; padding: 25px; text-align: center; color: #64748b; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>⏰ Appointment Reminder</h1>
                    <p style='margin: 10px 0 0 0;'>Your appointment is coming up soon!</p>
                    </div>
                    
                    <div class='content'>
                    <p style='font-size: 16px;'>Dear <strong>{$userName}</strong>,</p>
                    <p>This is a friendly reminder about your upcoming appointment at J.A.A Veterinary Clinic.</p>
                    
                    <div class='countdown-badge'>
                        <div style='font-size: 14px; color: #78350f;'>YOUR APPOINTMENT IS IN</div>
                        <div class='days'>{$daysUntil}</div>
                        <div style='font-size: 18px; font-weight: 600;'>DAY" . ($daysUntil > 1 ? 'S' : '') . "</div>
                    </div>
                    
                    <div class='details-card'>
                        <h3 style='margin-top: 0; color: #1e293b;'>📋 Appointment Details</h3>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>📌 Reference:</div>
                            <div class='detail-value'><strong>{$bookingReference}</strong></div>
                        </div>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>🐾 Pet Name:</div>
                            <div class='detail-value'><strong>{$petName}</strong></div>
                        </div>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>⚕️ Service:</div>
                            <div class='detail-value'><strong>{$serviceName}</strong></div>
                        </div>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>📅 Date:</div>
                            <div class='detail-value'><strong>{$formattedDate}</strong></div>
                        </div>
                        
                        <div class='detail-row'>
                            <div class='detail-label'>🕐 Time:</div>
                            <div class='detail-value'><strong>{$formattedTime}</strong></div>
                        </div>
                    </div>
                    
                    <div class='warning-box'>
                        <h3>⚠️ IMPORTANT: Cancellation Policy</h3>
                        <p style='margin: 0 0 15px 0;'>
                            <strong>Cancellations or rescheduling must be done at least 24 hours before your appointment.</strong>
                        </p>
                        <p style='margin: 0; color: #991b1b;'>
                            ❌ After this deadline, cancellations will not be accepted and you may be charged a cancellation fee.
                        </p>
                    </div>
                    
                    <div class='checklist-box'>
                        <h4 style='margin-top: 0; color: #1e40af;'>✅ Before You Come</h4>
                        <ul style='margin: 10px 0; padding-left: 20px;'>
                            <li><strong>Arrive 10-15 minutes early</strong> for check-in</li>
                            <li>Bring your pet's <strong>medical records</strong> (if any)</li>
                            <li>Keep your pet on a <strong>leash or in a carrier</strong></li>
                            <li>For surgeries: <strong>Fast your pet 4-6 hours</strong> before</li>
                            <li>Prepare any <strong>questions for the veterinarian</strong></li>
                        </ul>
                    </div>
                    
                    <div class='contact-box'>
                        <h4 style='margin-top: 0; color: #16a34a;'>📞 Need to Reschedule?</h4>
                        <p style='margin: 5px 0;'>Contact us immediately if you need to make changes:</p>
                        <p style='margin: 5px 0;'><strong>Phone:</strong> (02) 8888-8888</p>
                        <p style='margin: 5px 0;'><strong>Email:</strong> vetsync.01@gmail.com</p>
                        <p style='margin: 5px 0;'><strong>Hours:</strong> Mon-Fri: 8:00 AM - 6:00 PM</p>
                    </div>
                    
                    <p style='text-align: center; margin-top: 30px; font-size: 16px;'>
                        We look forward to seeing you and <strong>{$petName}</strong>!<br>
                        <span style='color: #64748b;'>- The J.A.A Veterinary Team</span>
                    </p>
                </div>
                
                <div class='footer'>
                    <p>This reminder was sent to {$userEmail}</p>
                    <p style='margin: 10px 0;'>Booking Reference: <strong>{$bookingReference}</strong></p>
                    <p>© {$currentYear} J.A.A Veterinary Clinic. All rights reserved.</p>
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

    // ✨ ENHANCED: Pickup Notification with all details
    public function sendPickupNotification(
        $userEmail,
        $userName,
        $productNames,
        $reservationDate,
        $totalAmount,
        $orderReference = '',
        $paymentStatus = 'Pending'
    ) {
        $subject = "🎉 Order Ready for Pickup - J.A.A Veterinary Clinic";

        $formattedDate = date('F j, Y', strtotime($reservationDate));
        $formattedAmount = '₱' . number_format($totalAmount, 2);
        $currentYear = date('Y');

        // Generate order reference if not provided
        if (empty($orderReference)) {
            $orderReference = 'ORD-' . strtoupper(substr(md5($userEmail . $reservationDate), 0, 8));
        }

        // Calculate pickup deadline (3 days from now)
        $pickupDeadline = date('F j, Y (l)', strtotime('+3 days'));

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
                .container { max-width: 650px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: white; padding: 30px; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { padding: 40px 30px; }
                .ref-badge { background: #dcfce7; color: #15803d; padding: 15px; text-align: center; border-radius: 8px; margin: 25px 0; }
                .ref-badge .ref-number { font-size: 24px; font-weight: bold; letter-spacing: 2px; }
                .order-summary { background: #f0fdf4; border-left: 4px solid #16a34a; padding: 25px; margin: 25px 0; border-radius: 8px; }
                .product-list { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
                .total-row { border-top: 2px solid #16a34a; padding-top: 15px; margin-top: 15px; font-size: 18px; }
                .warning-box { background: #fef2f2; border: 2px solid #dc2626; padding: 20px; margin: 25px 0; border-radius: 8px; text-align: center; }
                .warning-box h3 { color: #dc2626; margin: 0 0 10px 0; }
                .info-box { background: #dbeafe; border-left: 4px solid #2563eb; padding: 20px; margin: 25px 0; border-radius: 5px; }
                .checklist { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; margin: 25px 0; border-radius: 5px; }
                .footer { background: #f8fafc; padding: 25px; text-align: center; color: #64748b; font-size: 14px; }
                .payment-badge { display: inline-block; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; }
                .payment-pending { background: #fef3c7; color: #b45309; }
                .payment-paid { background: #dcfce7; color: #15803d; }
            </style>
        </head>
        <body>
            <div class='container'>
                    <div class='header'>
                    <h1>🎉 Order Ready!</h1>
                    <p style='margin: 10px 0 0 0;'>Your products are ready for pickup</p>
                </div>
                
                <div class='content'>
                    <p style='font-size: 16px;'>Dear <strong>{$userName}</strong>,</p>
                    <p>Great news! Your order is now ready for pickup at our clinic. Please review the details below:</p>
                    
                    <div class='ref-badge'>
                        <div style='font-size: 12px; color: #064e3b; margin-bottom: 5px;'>ORDER REFERENCE</div>
                        <div class='ref-number'>{$orderReference}</div>
                    </div>
                    
                    <div class='order-summary'>
                        <h3 style='margin-top: 0; color: #15803d;'>📦 Order Summary</h3>
                        
                        <div style='margin-bottom: 10px;'>
                            <strong>Order Date:</strong> {$formattedDate}<br>
                            <strong>Payment Status:</strong> 
                            <span class='payment-badge " . (strtolower($paymentStatus) === 'paid' ? 'payment-paid' : 'payment-pending') . "'>
                                {$paymentStatus}
                            </span>
                        </div>
                        
                        <div class='product-list'>
                            <strong>Items:</strong><br>
                            {$productNames}
                        </div>
                        
                        <div class='total-row'>
                            <strong>Total Amount:</strong> <span style='color: #15803d; font-size: 24px;'>{$formattedAmount}</span>
                        </div>
                        </div>
                        
                    <div class='warning-box'>
                        <h3>⏰ PICKUP DEADLINE</h3>
                        <p style='margin: 0; font-size: 18px;'><strong>{$pickupDeadline}</strong></p>
                        <p style='margin: 10px 0 0 0; font-size: 14px; color: #64748b;'>
                            Please collect your order within 3 days or it will be automatically cancelled
                        </p>
                        </div>
                        
                    <div class='info-box'>
                        <h4 style='margin-top: 0; color: #1e40af;'>🕐 Pickup Hours</h4>
                        <p style='margin: 5px 0;'><strong>Monday - Friday:</strong> 8:00 AM - 6:00 PM</p>
                        <p style='margin: 5px 0;'><strong>Saturday:</strong> 8:00 AM - 4:00 PM</p>
                        <p style='margin: 5px 0;'><strong>Sunday:</strong> Closed</p>
                        <p style='margin: 15px 0 0 0;'>📍 <strong>J.A.A Veterinary Clinic</strong></p>
                        <p style='margin: 5px 0;'>📞 (02) 8888-8888</p>
                    </div>
                    
                    <div class='checklist'>
                        <h4 style='margin-top: 0; color: #b45309;'>✅ What to Bring</h4>
                        <ul style='margin: 10px 0; padding-left: 20px;'>
                            <li><strong>Valid ID</strong> for verification</li>
                            <li><strong>Order reference number:</strong> {$orderReference}</li>
                            " . (strtolower($paymentStatus) !== 'paid' ? "
                            <li><strong>Payment:</strong> {$formattedAmount} (Cash or Card accepted)</li>
                            " : "") . "
                            <li>This confirmation email (printed or on your phone)</li>
                        </ul>
                    </div>
                    
                    <p style='text-align: center; margin-top: 30px;'>
                        <strong>Questions?</strong> Contact us at (02) 8888-8888 or vetsync.01@gmail.com
                    </p>
                </div>
                
                <div class='footer'>
                    <p>This notification was sent to {$userEmail}</p>
                    <p style='margin: 10px 0;'>Thank you for choosing J.A.A Veterinary Clinic!</p>
                    <p>© {$currentYear} J.A.A Veterinary Clinic. All rights reserved.</p>
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

    // Send password reset email
    public function sendPasswordReset($userEmail, $userName, $resetToken)
    {
        $subject = "Password Reset Request - J.A.A Veterinary Clinic";

        $resetLink = "http://vetsync.test/src/app/auth/reset-password.php?token=" . urlencode($resetToken);

        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
                .content { background: white; padding: 30px; }
                .details { background: #fff3cd; padding: 20px; margin: 20px 0; border-left: 4px solid #ffc107; }
                .button { display: inline-block; background: #dc3545; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
                .warning { background: #f8d7da; padding: 15px; margin: 20px 0; border-left: 4px solid #dc3545; color: #721c24; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin: 0;'>J.A.A Veterinary Clinic</h2>
                    <p style='margin: 5px 0 0 0;'>Password Reset Request</p>
                        </div>
                        
                <div class='content'>
                    <h3 style='color: #dc3545;'>Reset Your Password</h3>
                    <p>Dear {$userName},</p>
                    <p>We received a request to reset your password. Click the button below to create a new password:</p>
                    
                    <div style='text-align: center;'>
                        <a href='{$resetLink}' class='button'>Reset Password</a>
                    </div>
                    
                    <div class='details'>
                        <strong>Or copy this link:</strong><br>
                        <a href='{$resetLink}'>{$resetLink}</a>
                    </div>
                    
                    <div class='warning'>
                        <strong>⚠️ Security Notice:</strong><br>
                        • This link expires in 1 hour<br>
                        • If you didn't request this, please ignore this email<br>
                        • Your password won't change unless you click the link
                    </div>
                    
                    <p>If you're having trouble, contact us at J.A.A Veterinary Clinic.</p>
                    
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