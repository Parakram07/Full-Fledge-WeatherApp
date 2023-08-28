
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title><link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">    
</head>
<body>
<div class="container">
    <div class="search">
        <input type="text" placeholder="Enter city name" spellcheck="false">
        <button><img src ="search.png"></button>
    </div>
    <div class="card">
        <div class="error">
            <p>Invalid City Name</p>
        </div>
        <div class="weather">
            <h1>Today</h1>
            <img src="" class="icon">
            <h2 class="temp"></h1>
            <h3 class="city"></h2>
            <div class="details">
                <div class="col">
                    <img src="humidity.png">
                    <div>
                        <p class="humidity"></p>
                        <p>Humidity</p>
                    </div>
                </div>
                <div class="col">
                    <img src="wind.png">
                    <div>
                        <p class="wind"></p>
                        <p>Wind Speed</p>
                    </div>
                </div>
            </div>
            <div class="past-weather">
                <a href="weather.php" target="_blank" id="weather-button" class="weather-link">Forecast Weather</a>
            </div>
            <style>
             .weather-link {
                 font-size: 40px;
                 background-color: transparent;
                 color: #fff;
                 border: none;
                 cursor: pointer;
                 font-weight: bold;
                 text-decoration: none;
                }
            </style>
            <script>
              document.addEventListener('DOMContentLoaded', () => {
                   const searchBtn = document.querySelector('.search button'); 
                   const searchBox = document.querySelector('.search input'); 
                   const weatherButton = document.getElementById('weather-button');
                  searchBtn.addEventListener('click', () => {
                     const searchedCity = searchBox.value;
                     if (searchedCity) {
                         const encodedCity = encodeURIComponent(searchedCity);
                         weatherButton.href = `weather.php?city=${encodedCity}`;
                        }
                    });
                });
            </script>                                   
        </div>
        <div class="separator1"></div>
        <div class="datetime">
            <div class="date">
                <span id="dayname">Day</span>,
                <span id="Month">Month</span>,
                <span id="daynum">00</span>,
                <span id="year">Year</span>,
            </div>
            <div class="time">
                <span id="hour">00</span>,
                <span id="minutes">00</span>,
                <span id="seconds">00</span>,
                <span id="period">AM</span>,
            </div>
        </div>
        <div class="separator"></div>
    </div>
</div>
<script src=script.js></script>
</body>
</html>
