<?php

namespace VetSync\Services;

use Twilio\Rest\Client;

class Sms
{
    private $config;
    private $client;
    private $enabled;

    public function __construct()
    {
        $this->config = include __DIR__ . '/../core/sms-config.php';
        $this->enabled = $this->config['twilio']['enabled'] ?? false;

        if ($this->enabled) {
            $this->client = new Client(
                $this->config['twilio']['account_sid'],
                $this->config['twilio']['auth_token']
            );
        }
    }

    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) == 10 && substr($phone, 0, 1) == '9') {
            return '+63' . $phone;
        } elseif (strlen($phone) == 11 && substr($phone, 0, 2) == '09') {
            return '+63' . substr($phone, 1);
        } elseif (strlen($phone) == 12 && substr($phone, 0, 2) == '63') {
            return '+' . $phone;
        } elseif (strlen($phone) == 13 && substr($phone, 0, 3) == '+63') {
            return $phone;
        }

        return null;
    }

    public function send($to, $message)
    {
        if (!$this->enabled) {
            error_log("SMS service is disabled");
            return [
                'success' => false,
                'message' => 'SMS service is disabled'
            ];
        }

        $to = $this->formatPhoneNumber($to);

        if (!$to) {
            error_log("Invalid phone number format");
            return [
                'success' => false,
                'message' => 'Invalid phone number format'
            ];
        }

        try {
            $result = $this->client->messages->create(
                $to,
                [
                    'from' => $this->config['twilio']['from_number'],
                    'body' => $message
                ]
            );

            error_log("SMS sent successfully to $to. SID: " . $result->sid);

            return [
                'success' => true,
                'message' => 'SMS sent successfully',
                'sid' => $result->sid
            ];

        } catch (\Twilio\Exceptions\RestException $e) {
            error_log("Twilio SMS error: " . $e->getMessage());

            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'not a valid phone number') !== false) {
                $errorMsg = 'Invalid phone number';
            } elseif (strpos($errorMsg, 'Authenticate') !== false) {
                $errorMsg = 'Twilio authentication failed';
            } elseif (strpos($errorMsg, "'To' and 'From'") !== false) {
                $errorMsg = 'Number not verified in Twilio trial account';
            }

            return [
                'success' => false,
                'message' => 'SMS failed: ' . $errorMsg
            ];
        } catch (\Exception $e) {
            error_log("SMS error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'SMS error: ' . $e->getMessage()
            ];
        }
    }

    public function sendAppointmentConfirmation($phoneNumber, $userName, $petName, $serviceName, $date, $time)
    {
        $clinicName = $this->config['messages']['clinic_name'];

        $message = "Hi $userName! Your appointment for $petName ($serviceName) is CONFIRMED.\n\n"
            . "Date: $date\n"
            . "Time: $time\n\n"
            . "$clinicName\n"
            . "See you there!";

        return $this->send($phoneNumber, $message);
    }

    public function sendAppointmentReminder($phoneNumber, $userName, $petName, $serviceName, $date, $time)
    {
        $clinicName = $this->config['messages']['clinic_name'];

        $message = "REMINDER: $userName, you have an appointment tomorrow!\n\n"
            . "Pet: $petName\n"
            . "Service: $serviceName\n"
            . "Date: $date\n"
            . "Time: $time\n\n"
            . "$clinicName";

        return $this->send($phoneNumber, $message);
    }

    public function sendAppointmentReschedule($phoneNumber, $userName, $petName, $newDate, $newTime, $reason = '')
    {
        $clinicName = $this->config['messages']['clinic_name'];

        $message = "Hi $userName, your appointment for $petName has been RESCHEDULED.\n\n"
            . "New Date: $newDate\n"
            . "New Time: $newTime\n";

        if ($reason) {
            $message .= "Reason: $reason\n";
        }

        $message .= "\n$clinicName";

        return $this->send($phoneNumber, $message);
    }

    public function sendAppointmentCancellation($phoneNumber, $userName, $petName, $reason = '')
    {
        $clinicName = $this->config['messages']['clinic_name'];

        $message = "Hi $userName, your appointment for $petName has been CANCELLED.\n\n";

        if ($reason) {
            $message .= "Reason: $reason\n\n";
        }

        $message .= "To reschedule, please contact us or book online.\n$clinicName";

        return $this->send($phoneNumber, $message);
    }

    public function sendProductReadyNotification($phoneNumber, $userName, $productNames)
    {
        $clinicName = $this->config['messages']['clinic_name'];

        $message = "Hi $userName! Your products are ready for pickup!\n\n"
            . "Items: $productNames\n\n"
            . "Pick up at: $clinicName\n"
            . "Hours: 9AM - 8PM (Mon-Sat)";

        return $this->send($phoneNumber, $message);
    }

    public function sendReservationConfirmation($phoneNumber, $userName, $productNames, $date, $time)
    {
        $clinicName = $this->config['messages']['clinic_name'];

        $message = "Hi $userName! Your product reservation is CONFIRMED.\n\n"
            . "Items: $productNames\n"
            . "Pickup Date: $date\n"
            . "Time: $time\n\n"
            . "$clinicName";

        return $this->send($phoneNumber, $message);
    }
}