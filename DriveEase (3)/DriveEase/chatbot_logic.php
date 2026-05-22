<?php
header('Content-Type: application/json');

$message = strtolower(trim($_POST['message'] ?? ''));

$reply = "I'm sorry, I didn't quite catch that. You can ask me about our vehicles, pricing, booking process, or contact support.";
$options = []; 

// Smart Keyword Matching Logic
if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false || strpos($message, 'hey') !== false) {
    $reply = "Hello there! Welcome to Drive Ease. What would you like to know about?";
    $options = ["Price per day", "Cars", "Payments", "Delivery", "Driver", "Contact", "Book"];
} 
elseif (strpos($message, 'car') !== false || strpos($message, 'vehicle') !== false || strpos($message, 'fleet') !== false) {
    $reply = "We have a wide range of vehicles including Luxury Sedans, SUVs, Hatchbacks, and Vans. Scroll up to the 'Explore Our Fleet' section to see them all!";
}
elseif (strpos($message, 'thank you') !== false || strpos($message, 'thanks') !== false) {
    $reply = "You are very welcome! Let me know if you need help with anything else.";
}
elseif ($message === 'ok' || $message === 'ok!' || strpos($message, 'okay') !== false) {
    $reply = "Great! Feel free to ask if you have any more questions or need assistance with your booking.";
}
elseif (strpos($message, 'price') !== false || strpos($message, 'cost') !== false || strpos($message, 'rate') !== false) {
    $reply = "Our prices vary depending on the vehicle. You can view specific LKR per-day rates in the 'Explore Our Fleet' section on this page.";
} 
elseif (strpos($message, 'book') !== false || strpos($message, 'rent') !== false || strpos($message, 'how') !== false) {
    $reply = "To book a vehicle, simply browse our fleet and click 'Book Now'. You will need to log into the Customer Portal first to secure your dates!";
} 
elseif (strpos($message, 'contact') !== false || strpos($message, 'support') !== false || strpos($message, 'number') !== false) {
    $reply = "You can reach our 24/7 support team at support@driveease.lk or call us directly at +94 11 234 5678.";
} 
elseif (strpos($message, 'delivery') !== false || strpos($message, 'drop') !== false || strpos($message, 'bring') !== false) {
    $reply = "Yes! We offer vehicle delivery directly to your address for a flat additional fee of LKR 5000.00. You can select this option during checkout.";
} 
elseif (strpos($message, 'driver') !== false) {
    $reply = "You can rent vehicles for self-drive, or contact us to arrange a professional driver. If you ARE a driver, please click the 'Driver Login' portal at the bottom of the page.";
}
elseif (strpos($message, 'payment') !== false || strpos($message, 'pay') !== false || strpos($message, 'card') !== false) {
    $reply = "We accept all major Credit/Debit cards directly through our booking portal, as well as Bank Transfers to Commercial Bank.";
}

// Return the text AND the options array to JavaScript
echo json_encode(['status' => 'success', 'reply' => $reply, 'options' => $options]);
?>