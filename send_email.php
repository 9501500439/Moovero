<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log errors
function logError($message) {
    $logMessage = "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    file_put_contents('whatsapp_errors.log', $logMessage, FILE_APPEND);
    return $logMessage;
}

// Function to send WhatsApp message
function sendWhatsAppMessage($message) {
    // WhatsApp Business API credentials
    $apiKey = 'YOUR_WHATSAPP_API_KEY'; // Replace with your actual API key
    $fromNumber = '916006722587'; // Your WhatsApp Business number (6006722587 with country code)
    $toNumber = 'YOUR_PERSONAL_NUMBER'; // Your personal number to receive notifications
    
    // Remove any non-numeric characters from phone numbers
    $fromNumber = preg_replace('/[^0-9]/', '', $fromNumber);
    $toNumber = preg_replace('/[^0-9]/', '', $toNumber);
    
    // Ensure country code is present
    if (substr($fromNumber, 0, 2) !== '91') {
        $fromNumber = '91' . ltrim($fromNumber, '0');
    }
    if (substr($toNumber, 0, 2) !== '91') {
        $toNumber = '91' . ltrim($toNumber, '0');
    }
    
    // API endpoint (using WhatsApp Business API)
    $url = "https://api.whatsapp.com/send?phone=$toNumber&text=" . urlencode($message);
    
    // For actual API call, you would use cURL like this:
    /*
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.whatsapp.com/v1/messages");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'to' => $toNumber,
        'from' => $fromNumber,
        'type' => 'text',
        'text' => ['body' => $message]
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode == 200 || $httpCode == 201;
    */
    
    // For now, we'll use the direct WhatsApp URL which will open in the WhatsApp app
    // This is a fallback method that works without API
    header("Location: $url");
    logError("Redirecting to WhatsApp: " . $url);
    return true;
}

// Set content type to JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data with validation
        $name = isset($_POST["name"]) ? strip_tags(trim($_POST["name"])) : '';
        $email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
        $phone = isset($_POST["phone"]) ? strip_tags(trim($_POST["phone"])) : '';
        $moveDate = isset($_POST["moveDate"]) ? strip_tags(trim($_POST["moveDate"])) : '';
        $from = isset($_POST["from"]) ? strip_tags(trim($_POST["from"])) : '';
        $to = isset($_POST["to"]) ? strip_tags(trim($_POST["to"])) : '';
        $service = isset($_POST["service"]) ? strip_tags(trim($_POST["service"])) : '';
        
        // Validate required fields
        if (empty($name) || empty($phone)) {
            throw new Exception('Please fill in all required fields.');
        }
        
        // Build WhatsApp message
        $whatsapp_message = "*New Quote Request*%0A%0A";
        $whatsapp_message .= "*Name:* $name%0A";
        if (!empty($email)) $whatsapp_message .= "*Email:* $email%0A";
        $whatsapp_message .= "*Phone:* $phone%0A";
        if (!empty($moveDate)) $whatsapp_message .= "*Moving Date:* $moveDate%0A";
        if (!empty($from)) $whatsapp_message .= "*From:* $from%0A";
        if (!empty($to)) $whatsapp_message .= "*To:* $to%0A";
        if (!empty($service)) $whatsapp_message .= "*Service Type:* $service%0A";
        
        // Create WhatsApp URL
        $whatsapp_url = "https://wa.me/916006722587?text=" . $whatsapp_message;
        
        // Log the WhatsApp URL for debugging
        logError("Generated WhatsApp URL: " . $whatsapp_url);
        
        // Return success with redirect URL
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'redirect' => $whatsapp_url,
            'message' => 'Opening WhatsApp...'
        ]);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        logError("Form submission error: " . $e->getMessage());
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}
?>
