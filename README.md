# Fruits App

first of all move on project directory quanticedge

step : 1 Composer Install
run : compose install

step : 2 set database configration in .env (DATABASE_URL) and Run Migrations
run : php bin/console doctrine:migrations:migrate

step : 3 Please setup mail configration (I've used https://mailtrap.io/ for this project) 
Please change in .env file
MAILER_DSN,SMTP_HOST,SMTP_PORT,SMTP_USERNAME,SMTP_PASSWORD  

step : 4 Start Symfony Server
run : symfony server:start

step : 5 Run app:get:fruity command for fetch data form api https://fruityvice.com/ and stored in DB and sending mail to admin for every fruit added information. (Once time process)
run : php bin/console app:get:fruity (This step is take a time Please wait...)

please check on https://mailtrap.io/inboxes 
Email Testing > Inboxes > My Inbox

Then you can check main page :https://localhost:8000/
and favorite fruits page : https://localhost:8000/favorite

--- Note:
In this project migrations exists.
But also I've attached sql database file on path db/fruity.sql 
