Flight::route('POST /status', function(){
    $status = Flight::request()->data->status;
    $db = new PDO('mysql:host=localhost;dbname=control_led', 'username', 'password');
    $stmt = $db->prepare("INSERT INTO led_status (status) VALUES (:status)");
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    
    // Enviar el estado al ESP32
    $serialCommand = ($status == 'on') ? '1' : '0';
    $port = fopen('/dev/ttyUSB0', 'w');
    if ($port) {
        fwrite($port, $serialCommand);
        fclose($port);
    }
    
    Flight::json(['message' => 'Status updated and sent to ESP32']);
});




