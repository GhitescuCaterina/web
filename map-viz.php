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
    <link rel="stylesheet" href="styles/map.css" />
    <title>Romania Map</title>
  </head>
  <body>
    <div class="content">
      <div class="map-container">
        <div class="tooltip" id="tooltip"></div>
        <object
          type="image/svg+xml"
          data="romania.svg"
          id="romania-map"
        ></object>
        <div class="filter-container">
          <label for="filter-year">Filter by year:</label>
          <select id="filter-year">
            <option value="all">all years</option>
          </select>
        </div>
      </div>
      <div class="sidebar">
        <h2>Instrucțiuni</h2>
        <p>
          Utilizați dropdown-ul de mai jos pentru a filtra datele în funcție de
          an.
        </p>
        <p>
          Când mutați cursorul peste un județ, veți vedea detalii suplimentare.
        </p>
        <p>
          Pentru a reveni la vizualizarea inițială, selectați "all years" din
          dropdown.
        </p>
        <p>
          Datele sunt preluate dintr-o bază de date și reprezintă numărul total
          de automobile înmatriculate în fiecare județ.
        </p>
        <p>Have fun!</p>
        <button class="button" onclick="window.location ='chart-viz.php'">
          chart view
        </button>
        <button class="button" onclick="window.location ='welcome.php'">
          home
        </button>
        <div class="export-buttons">
          <button class="export" id="export-svg">exporta ca SVG</button>
        </div>
      </div>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const map = document.getElementById("romania-map");
        const filterYear = document.getElementById("filter-year");
        const tooltip = document.getElementById("tooltip");
        const exportButton = document.getElementById("export-svg");

        fetch("api/getYears.php")
          .then((response) => response.json())
          .then((data) => {
            data.sort((a, b) => a - b);
            data.forEach((year) => {
              const option = document.createElement("option");
              option.value = year;
              option.textContent = year;
              filterYear.appendChild(option);
            });
          });

        fetchDataAndDisplay();

        function fetchDataAndDisplay() {
          const yearValue = filterYear.value;
          let url = `api/getMapdata.php?year=${yearValue}`;
          fetch(url)
            .then((response) => response.json())
            .then((data) => {
              displayData(data);
            });
        }

        function displayData(data) {
          const svgDoc = map.contentDocument;

          svgDoc.querySelectorAll("path").forEach((path) => {
            path.removeEventListener("mouseover", handleMouseOver);
            path.removeEventListener("mouseout", handleMouseOut);
            path.style.fill = "";
            path.removeAttribute("title");
            path.classList.add("judet");
          });

          data.forEach((item) => {
            const elements = svgDoc.getElementsByClassName(item.JUDET);

            if (elements.length > 0) {
              const label = elements[0];
              const element = svgDoc.getElementById(label.id);
              console.log(element);

              element.style.fill = "#5f9d78";
              element.dataset.tooltip = `Judet: ${
                item.JUDET
              }<br>Total Automobile: ${Intl.NumberFormat().format(
                item.TOTAL_JUDET
              )}`;

              element.addEventListener("mouseover", handleMouseOver);
              element.addEventListener("mouseout", handleMouseOut);
            }
          });
        }

        function handleMouseOver(event) {
          const target = event.target;
          target.style.fill = "#b4c6b4";
          const tooltipText = target.dataset.tooltip;
          tooltip.innerHTML = tooltipText;
          tooltip.style.display = "block";
          tooltip.style.left = `${event.pageX + 10}px`;
          tooltip.style.top = `${event.pageY + 10}px`;
        }

        function handleMouseOut(event) {
          const target = event.target;
          target.style.fill = "#5f9d78";
          tooltip.style.display = "none";
        }

        filterYear.addEventListener("change", fetchDataAndDisplay);

        document.addEventListener("mousemove", (event) => {
          if (tooltip.style.display === "block") {
            tooltip.style.left = `${event.pageX + 10}px`;
            tooltip.style.top = `${event.pageY + 10}px`;
          }
        });

        exportButton.addEventListener("click", () => {
          const svgDoc = map.contentDocument;
          const serializer = new XMLSerializer();
          const svgString = serializer.serializeToString(
            svgDoc.documentElement
          );
          const blob = new Blob([svgString], {
            type: "image/svg+xml;charset=utf-8",
          });
          const url = URL.createObjectURL(blob);
          const a = document.createElement("a");
          a.href = url;
          a.download = "romania-map.svg";
          a.click();
          URL.revokeObjectURL(url);
        });
      });
    </script>
  </body>
</html>
