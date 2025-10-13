<?php
// Test file to preview the pickup notification email with cancellation warning

// Sample data for testing
$userName = "John Doe";
$productNames = "• Dog Food Premium (Qty: 2)<br>• Vitamins for Cats (Qty: 1)<br>• Flea Treatment (Qty: 3)";
$reservationDate = "2024-09-30";
$totalAmount = 1250.50;

// Email subject
$subject = "📦 Your Order is Ready for Pickup - VetSync";

// Email message with the enhanced pickup notification
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
        .warning-box { background: #f8d7da; padding: 15px; border-radius: 5px; border-left: 4px solid #dc3545; margin: 20px 0; color: #721c24; }
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
                    <strong>📍 Pickup Information:</strong><br>
                    <strong>Location:</strong> VetSync Veterinary Clinic<br>
                    <strong>Hours:</strong> Monday - Friday: 8:00 AM - 6:00 PM<br>
                    <strong>Saturday:</strong> 8:00 AM - 4:00 PM<br>
                    <strong>Note:</strong> Please bring a valid ID for pickup verification
                </div>
                
                <div class='warning-box'>
                    <strong>⚠️ IMPORTANT PICKUP NOTICE:</strong><br>
                    <strong>Pickup Deadline:</strong> 7 days from ready date (" . date('F j, Y', strtotime($reservationDate . ' +7 days')) . ")<br>
                    <strong>⚠️ Please note:</strong> Items <strong>MUST</strong> be picked up within 7 days or your reservation will be <strong>AUTOMATICALLY CANCELLED</strong> and any payments will be processed for refund.<br>
                    <strong>📞 Contact us immediately</strong> if you cannot pick up within this timeframe.
                </div>
                
                <p><strong>Please pick up your order as soon as possible to avoid automatic cancellation.</strong></p>
                <p>If you have any questions or need to arrange a different pickup time, please contact us immediately at:</p>
                <p><strong>📞 Phone:</strong> +63 123 456 7890<br>
                <strong>📧 Email:</strong> info@vetsync.com</p>
                
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Notification Email Preview - VetSync</title>
    <style>
        body { margin: 0; padding: 20px; background: #f0f0f0; }
        .preview-container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .preview-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #007bff; }
        .preview-info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .email-subject { background: #f8f9fa; padding: 10px; border-left: 4px solid #007bff; margin-bottom: 20px; }
        .email-content { border: 2px solid #ddd; border-radius: 8px; overflow: hidden; }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <h1>📧 Email Preview: Pickup Notification</h1>
            <p>This is how the pickup notification email will appear to customers</p>
        </div>
        
        <div class="preview-info">
            <h3>📋 Test Email Details:</h3>
            <strong>To:</strong> <?= $userName ?> (john.doe@example.com)<br>
            <strong>Order Date:</strong> <?= date('F j, Y', strtotime($reservationDate)) ?><br>
            <strong>Total Amount:</strong> ₱<?= number_format($totalAmount, 2) ?><br>
            <strong>Pickup Deadline:</strong> <?= date('F j, Y', strtotime($reservationDate . ' +7 days')) ?>
        </div>
        
        <div class="email-subject">
            <strong>📧 Subject:</strong> <?= $subject ?>
        </div>
        
        <div class="email-content">
            <?= $message ?>
        </div>
        
        <div style="margin-top: 30px; text-align: center; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h3>🔄 Email Features:</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; text-align: left;">
                <div>
                    ✅ <strong>Clear pickup instructions</strong><br>
                    ✅ <strong>Order details and products</strong><br>
                    ✅ <strong>Clinic hours and location</strong>
                </div>
                <div>
                    ⚠️ <strong>Prominent cancellation warning</strong><br>
                    ⚠️ <strong>7-day pickup deadline</strong><br>
                    ⚠️ <strong>Contact information</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
