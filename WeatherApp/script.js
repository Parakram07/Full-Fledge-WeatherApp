const apiKey = "da367fc24ba793de8eff595872d99280";
const weatherApiUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=";
const forecastApiUrl = "https://api.openweathermap.org/data/2.5/forecast?units=metric&q=";
const timezoneApiUrl = "http://api.timezonedb.com/v2.1/get-time-zone";
const searchForm = document.querySelector(".search");
const searchBox = document.querySelector(".search input");
const searchBtn = document.querySelector(".search button");
const weatherDisplay = document.querySelector('.weather-display');
const Icon = document.querySelector(".icon");
const defaultCity = "Guadalajara";
let timezoneTimer = null;

// Function to fetch forecast data
async function fetchForecast(city) {
  const forecastResponse = await fetch(`${forecastApiUrl}${city}&appid=${apiKey}`);
  const forecastData = await forecastResponse.json();
  return forecastData;
}

// Function to update weather data and store it
async function updateWeatherData(city) {
  try {
    const weatherResponse = await fetch(`${weatherApiUrl}${city}&appid=${apiKey}`);
    const weatherData = await weatherResponse.json();
    console.log("Current Weather:", weatherData);
    
    if (weatherResponse.status === 404) {
      throw new Error("City Not Found");
    }
        
    const timezoneResponse = await fetch(`${timezoneApiUrl}?key=4IQNGNYZRIXT&format=json&by=position&lat=${weatherData.coord.lat}&lng=${weatherData.coord.lon}`);
    const timezoneData = await timezoneResponse.json();
    

    document.querySelector(".error").innerHTML = "";
    document.querySelector(".city").innerHTML = weatherData.name;
    document.querySelector(".temp").innerHTML = Math.round(weatherData.main.temp) + "°C";
    document.querySelector(".humidity").innerHTML = weatherData.main.humidity + "%";
    document.querySelector(".wind").innerHTML = Math.round(weatherData.wind.speed) + "km/h";

    if (weatherData.weather[0].main == "Clouds") {
      Icon.src = "clouds.png";
    } else if (weatherData.weather[0].main == "Clear") {
      Icon.src = "sunny.png";
    } else if (weatherData.weather[0].main == "Rain") {
      Icon.src = "rain.png";
    } else if (weatherData.weather[0].main == "Drizzle") {
      Icon.src = "drizzle.png";
    } else if (weatherData.weather[0].main == "Snow") {
      Icon.src = "snowy.png";
    } else if (weatherData.weather[0].main == "Mist") {
      Icon.src = "mist.png";
    } else if (weatherData.weather[0].main == "Haze") {
      Icon.src = "haze.png";  
    }


    
    
    const weatherForData = {
      city: weatherData.name,
      temp: Math.round(weatherData.main.temp) + "°C",
      humidity: weatherData.main.humidity + "%",
      wind_speed: Math.round(weatherData.wind.speed) + "km/h",
      icon: weatherData.weather[0].main,
      date_recorded: new Date().toISOString() 
    };
    
    let storedWeatherData = JSON.parse(localStorage.getItem('Current Weather')) || {};
    storedWeatherData[city] = storedWeatherData[city] || [];
    storedWeatherData[city].push(weatherForData);
    localStorage.setItem('Current Weather', JSON.stringify(storedWeatherData));
    console.log('**Current Weather stored successfully in localStorage**');



    // Inside the updateWeatherData function after sending the current weather data
    const forecastData = await fetchForecast(city);
    const filteredForecastData = forecastData.list.filter(item => item.dt_txt.includes("12:00:00"));
    const next7DaysWeather = filteredForecastData.slice(1, 5);
    const forecastForData = next7DaysWeather.map(item => ({
      city: city,
      date_recorded: item.dt_txt,
      temp: Math.round(item.main.temp) + "°C",
      humidity: item.main.humidity + "%",
      wind_speed: Math.round(item.wind.speed) + "km/h",
      icon: item.weather[0].main
    }));
    
    fetch('store_weather_data.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ forecastData: forecastForData, city: city })
    })
    .then(response => response.json())
    .then(result => {
      // Create an array to store the past week's weather information
      const pastWeekWeatherArray = [];
      
      forecastForData.forEach(day => {
        pastWeekWeatherArray.push({
          Date: day.date_recorded,
          City: day.city,
          Temperature: day.temp,
          Humidity: day.humidity,
          Wind_Speed: day.wind_speed,
          Weather_Icon: day.icon
        });
      });   
    
      console.log('Forecast Weather:', pastWeekWeatherArray);
      console.log('', result);
    })
    .catch(error => {
      console.error('Error storing forecast data:', error);
    });
    

    // Update the date and time display
    clearInterval(timezoneTimer);
    updateDateTime(timezoneData.zoneName);
  } catch (error) {
    console.error('Error:', error);
    document.querySelector(".error").textContent = "Invalid city name";
    document.querySelector(".city").textContent = "";
    document.querySelector(".temp").textContent = "--";
    document.querySelector(".humidity").textContent = "--";
    document.querySelector(".wind").textContent = "--";
    Icon.src = "";
  }
}

// Function to update the date and time display
const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
const monthsOfYear = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

function updateDateTime(timezone) {
  const now = new Date();
  const locationTime = new Date(now.toLocaleString("en-US", { timeZone: timezone }));

  const dayName = daysOfWeek[locationTime.getDay()];
  const monthName = monthsOfYear[locationTime.getMonth()];
  const dayNum = locationTime.getDate();
  const year = locationTime.getFullYear();
  const hour = locationTime.getHours();
  const minutes = locationTime.getMinutes();
  const seconds = locationTime.getSeconds();
  const period = hour >= 12 ? "PM" : "AM";

  document.getElementById("dayname").textContent = dayName;
  document.getElementById("Month").textContent = monthName;
  document.getElementById("daynum").textContent = dayNum;
  document.getElementById("year").textContent = year;
  document.getElementById("hour").textContent = formatTime(hour);
  document.getElementById("minutes").textContent = formatTime(minutes);
  document.getElementById("seconds").textContent = formatTime(seconds);
  document.getElementById("period").textContent = period;

  timezoneTimer = setTimeout(() => updateDateTime(timezone), 1000);
}

// Function to format time
function formatTime(time) {
  return time < 10 ? "0" + time : time;
}

// Load default city weather data
updateWeatherData(defaultCity);

// Add event listener for search button
document.addEventListener('DOMContentLoaded', () => {
  searchBtn.addEventListener('click', () => {
    const city = searchBox.value.trim();
    updateWeatherData(city);
  });
});
