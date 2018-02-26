Home automation
-------

## Installation

```
git clone ...
cp .env.example .env
composer install
npm install
npm run prod
```

Setup your database in the `.env` file.

Seed the database with start data.

```
php artisan migrate
php artisan db:seed --class=DeviceTableSeeder
```

Run the nodejs server

```
./node_modules/node/bin/node node-server.js
```

Run the build in web server 

``` 
php artisan serve
```

Open your browser and enjoy!
