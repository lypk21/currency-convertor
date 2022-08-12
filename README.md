1. Vue.js project and Laravel project complete the follow tasks:

    1. User registration and login.
    2. Home page shows the list of available currencies to select up to five.
    3. A currency conversion table should be shown for the five selected currencies.
    4. A report request can be submitted by selecting one of the following range and
        interval pairs:
        a. Range: One Year, Interval: Monthly
        b. Range: Six Months, Interval: Weekly
        c. Range: One Month, Interval: Daily
    5. BarChart show report data.
    
2. how to run laravel project:
    
    1.  composer install, copy .env from .env.example, set up your own currencylayer api key as: CURRENCY_API_KEY=****
    2.  recommend use valet, install valet and  cd to /currency-convertor, run: valet link
    2.  setup your database connection in .env, and then run: php artisan migrate
    3.  run: php artisan schedule:work, to retrieve data from time frame API for one year of 5 currencies data, 
        this schedule work will save data to database currency table, if you did not run the schedule work, 
        there is no data for bar chart. The schedule cron run every 5 minutes, please wait for schedule or visit: http://currency-convertor.test/get_data
    4.  to run test case: php artisan test 
        
3. how to run vue.js project:
    1. npm install
    2. setup the base url endpoint in webpack.config.js, if use valet, should be: apiUrl: 'http://currency-convertor.test/api'
    3. launch laravel project first, and then launch vue.js project by run: npm start
    
4. main tech apply: 
   laravel project: Laravel Eloquent ORM, Request Validation, Scheduled Jobs, 
                    API User Authentication, API Token, API Response Encapsulation, 
                    API Exception Handler(please refer app/Exceptions/Handler), API HTTP Status Code  
                    Service Layer for web/api/cron reusable purpose,
                    Laravel Unit Test for testing  api
                    
   Vue.js project:  Vuex, vue-router, vue-chartjs
   
5. How the UI look like: 
   
   Main Screen: show currency with live API, show report data with time frame API that run by schedule and save data list to database <br>
   <img src="https://github.com/lypk21/vue-currency/blob/master/home.png" width="600">
   
   Login Screen: <br>                
   <img src="https://github.com/lypk21/vue-currency/blob/master/login.png" width="600"> 
   
   Register Screen:  <br>               
   <img src="https://github.com/lypk21/vue-currency/blob/master/register.png" width="600">                 
