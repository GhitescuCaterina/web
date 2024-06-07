
<!DOCTYPE html>

<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="pictures/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="styles/welcome.css" />
  </head>

  <body>
    <div class="container">
        <div class="stanga">
            <div class="functii">
              <button class="button" onclick="window.location ='map-viz.php'">
                map view
              </button>
              <button class="button" onclick="window.location ='chart-viz.php'">
                chart view
              </button>
                <button class="login-button" onclick="window.location ='login.html'">
                    login
                </button>
                <button class="signin-button" onclick="window.location ='signup.php'">
                    sign up
                </button>
            </div>
            <div class="contact">
              <button class="about-button" onclick="window.location='about.html'">
                  about us
              </button>
              <button class="contact-button" onclick="window.location='contact.php'">
                  contact
              </button>
          </div>
        </div>
        <div class="dreapta">
          <div class="descriere">
            Welcome to 
          </div>
          <img src="pictures/logo full.png" alt="VroomData Logo" class="logo-image">
        </div>
    </div>
  </body>
</html>