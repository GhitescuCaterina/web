<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script type='text/javascript'>
        alert('You need to be logged in to access this page.');
        window.location.href = 'login.html';
    </script>";
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="shortcut icon" href="pictures/logo.png" type="image/x-icon" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Raleway"
    />
    <link rel="stylesheet" href="styles/chart.css" />
    <title>Vizualizare Parc Auto</title>
  </head>
  <body>
    <div class="container">
      <h1>Vizualizare Parc Auto</h1>
      <div class="controls">
        <label for="brandSelect">Alege o marca auto:</label>
        <select id="brandSelect"></select>
        <label for="yearSelect">Alege un an:</label>
        <select id="yearSelect"></select>
        <button class="vizualizeaza" id="fetchDataButton">Vizualizeaza!</button>
        <label for="chartTypeSelect">Tipul Graficului:</label>
        <select id="chartTypeSelect">
          <option value="bar">Bar Chart</option>
          <option value="pie">Pie Chart</option>
        </select>
      </div>
      <canvas id="vehicleChart" width="400" height="200"></canvas>
      <div class="export-buttons">
        <button class="export" id="exportWebPButton">exporta ca WebP</button>
        <button class="export" id="exportCSVButton">exporta ca CSV</button>
        <button class="export" onclick="window.location ='map-viz.php'">
          map view
        </button>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      function generateRandomColor() {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            return `rgb(${r},${g},${b})`;
      }

      document.addEventListener("DOMContentLoaded", () => {
        const brandSelect = document.getElementById("brandSelect");
        const yearSelect = document.getElementById("yearSelect");
        const fetchDataButton = document.getElementById("fetchDataButton");
        const chartTypeSelect = document.getElementById("chartTypeSelect");
        const ctx = document.getElementById("vehicleChart").getContext("2d");
        let vehicleChart = new Chart(ctx, {
          type: "bar",
          data: {
            labels: [],
            datasets: [
              {
                label: "Numar total vehicule",
                data: [],
                backgroundColor: [],
                borderColor: "#5f9d78",
                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
            plugins: {
              legend: {
                display: false,
              },
            },
          },
        });

        fetch("api/getBrands.php")
          .then((response) => response.json())
          .then((data) => {
            data.sort(); // Sort brands in alphabetical order
            data.forEach((brand) => {
              const option = document.createElement("option");
              option.value = brand;
              option.textContent = brand;
              brandSelect.appendChild(option);
            });
          });

        // Fetch unique years to populate the dropdown
        fetch("api/getYears.php")
          .then((response) => response.json())
          .then((data) => {
            data.sort((a, b) => a - b); // Sort years in ascending order
            data.forEach((year) => {
              const option = document.createElement("option");
              option.value = year;
              option.textContent = year;
              yearSelect.appendChild(option);
            });
          });

          fetchDataButton.addEventListener('click', () => {
                const selectedBrand = brandSelect.value;
                const selectedYear = yearSelect.value;
                fetch(`api/getData.php?brand=${encodeURIComponent(selectedBrand)}&year=${encodeURIComponent(selectedYear)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error from server:', data.error);
                        return;
                    }

                    // Process new data
                    const labels = data.map(vehicle => `${vehicle.MARCA} (${vehicle.ANUL_STATISTICII})`);
                    const totalVehicles = data.map(vehicle => vehicle.TOTAL_VEHICULE);

                    // Add new data to the chart
                    labels.forEach((label, index) => {
                        vehicleChart.data.labels.push(label);
                        vehicleChart.data.datasets[0].data.push(totalVehicles[index]);
                        vehicleChart.data.datasets[0].backgroundColor.push(generateRandomColor());
                    });

                    vehicleChart.update();
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
            });

        chartTypeSelect.addEventListener("change", () => {
          const selectedType = chartTypeSelect.value;
          if (selectedType === "pie") {
            vehicleChart.config.type = "pie";
            vehicleChart.config.options.plugins.legend.display = true;
          } else {
            vehicleChart.config.type = selectedType;
            vehicleChart.config.options.plugins.legend.display = false;
          }
          vehicleChart.update();
        });

        document
          .getElementById("exportWebPButton")
          .addEventListener("click", () => {
            const link = document.createElement("a");
            link.download = "chart.webp";
            link.href = vehicleChart.toBase64Image("image/webp");
            link.click();
          });

        document
          .getElementById("exportCSVButton")
          .addEventListener("click", () => {
            const labels = vehicleChart.data.labels;
            const data = vehicleChart.data.datasets[0].data;
            let csvContent = "Marca,Numar total vehicule\n";
            labels.forEach((label, index) => {
              csvContent += `${label},${data[index]}\n`;
            });

            const blob = new Blob([csvContent], { type: "text/csv" });
            const link = document.createElement("a");
            link.download = "chart.csv";
            link.href = URL.createObjectURL(blob);
            link.click();
          });
      });
    </script>
  </body>
</html>
