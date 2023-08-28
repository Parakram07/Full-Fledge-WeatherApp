<!DOCTYPE html>
<html>
<head>
    <title>Forecast Weather Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h1>Forecast Weather Data</h1>
    
    <table>
        <tr>
            <th>Day</th>
            <th>City</th>
            <th>Temperature (°C)</th>
            <th>Humidity (%)</th>
            <th>Wind Speed (km/h)</th>
            <th>Weather</th>
        </tr>
        <?php
$servername = "localhost";
$username = "WeatherWebsite";
$password = "Parakramkc1";
$database = "weatherapp";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchedCity = isset($_GET['city']) ? $_GET['city'] : ""; // Get the searched city from the query parameter

if (!empty($searchedCity)) {
    // User has searched for a city
    $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
    $selectSQL = "SELECT * FROM weather_data WHERE city = '$searchedCity' AND date_recorded >= '$sevenDaysAgo'";
    $result = $conn->query($selectSQL);

    if ($result->num_rows > 0) {
        // Display weather data for the searched city
        while ($row = $result->fetch_assoc()) {
            $dayOfWeek = date('l', strtotime($row['date_recorded'])); 
            echo "<tr>";
            echo "<td>{$dayOfWeek}</td>";
            echo "<td>{$row['city']}</td>";
            echo "<td>{$row['temp']}</td>";
            echo "<td>{$row['humidity']}</td>";
            echo "<td>{$row['wind_speed']}</td>";
            echo "<td>{$row['icon']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No weather data available for the last 7 days</td></tr>";
    }
} else {
    // Display weather data for the default city (Guadalajara)
    $defaultCity = "Guadalajara";
    $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));

    $defaultCitySQL = "SELECT * FROM weather_data WHERE city = '$defaultCity' AND date_recorded >= '$sevenDaysAgo'";
    $defaultCityResult = $conn->query($defaultCitySQL);

    if ($defaultCityResult->num_rows > 0) {
        while ($row = $defaultCityResult->fetch_assoc()) {
            $dayOfWeek = date('l', strtotime($row['date_recorded']));
            echo "<tr>";
            echo "<td>{$dayOfWeek}</td>";
            echo "<td>{$row['city']}</td>";
            echo "<td>{$row['temp']}</td>";
            echo "<td>{$row['humidity']}</td>";
            echo "<td>{$row['wind_speed']}</td>";
            echo "<td>{$row['icon']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No weather data available for the last 7 days</td></tr>";
    }
}

$conn->close();
?>
   

    </table>    
</body>
</html>
