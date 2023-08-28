<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['forecastData'])) {
        // Forecast data received from the client
        $city = $data['city'];
        $forecastData = $data['forecastData'];

        $host = 'localhost';
        $username = 'WeatherWebsite';
        $password = 'Parakramkc1';
        $database = 'weatherapp';

        $conn = new mysqli($host, $username, $password, $database);
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        foreach ($forecastData as $forecastEntry) {
            $city = $forecastEntry['city'];
            $date = $forecastEntry['date_recorded'];
            $temp = $forecastEntry['temp'];
            $humidity = $forecastEntry['humidity'];
            $wind_speed = $forecastEntry['wind_speed'];
            $icon = $forecastEntry['icon'];

            // Use prepared statement to avoid SQL injection
            $stmt = $conn->prepare("INSERT INTO weather_data (city, date_recorded, temp, humidity, wind_speed, icon) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $city, $date, $temp, $humidity, $wind_speed, $icon);

            if ($stmt->execute()) {
                // Success
            } else {
                // Error
            }

            $stmt->close();
        }

        $conn->close();

        $response = ('**Forecast data stored successfully in MySQL DataBase**');
        
    } else {
        $response = ('**Invalid data format**');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
}
?>
