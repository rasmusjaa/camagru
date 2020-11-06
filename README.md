# <!-- ABOUT THE PROJECT -->
## About The Project

![Screenshot1](https://github.com/rasmusjaa/camagru/blob/master/cam-screenshot.png)

The aim of this project was to create a small Instagram-like site that enables users to make, share, comment and like photos edited with filters. Allowed languages were PHP and JavaScript (just standarad libraries, frameworks forbidden), MySQL and CSS (framework allowed if it doesn't contain JS).

### Notes
* Users can create account and edit account information
* Account creation and password reseting has to be confirmed from emailed links
* Password data is hashed
* SQL and JS/HTML injections are handled
* Users have a page where they can take or upload photos and see and remove their previous photos
* Photos taken with webcam must have at least one filter on them and can have multiple filters
* Users can comment and like photos
* Email is sent to user if their photo is commented. This feature can be turned off in user settings

## Built With
* PHP
* MySQL
* JavaScript
* CSS 
  * [Vital](https://vitalcss.com/)

## Running project

To run this project locally you must have *AMP environment installed, for example Bitnami WAMP/MAMP. Then:
1. Clone the repo to htdocs folder in apache2
2. Change smtp server information to use your own mail. If you use gmail just change username and password (fetched from file on server at the moment to not show password in source code) on htdocs/functions/sendmail.php
3. run installation script on http://localhost:8080/config/setup.php
4. Use app on http://localhost:8080/

To make use of webcam features you have to allow the site to access your webcam with your browser.

![Screenshot2](https://github.com/rasmusjaa/camagru/blob/master/front-screenshot.png)
