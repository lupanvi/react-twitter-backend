## Laravel Backend for the Twitter React Frontend

This open source project was made with:

* Laravel 8
* Sanctum
* PHPunit
* ElasticSearch

## Installation

### Prerequisites

* To run this project, you must have PHP 7 installed.

### Step 1

 Begin by cloning this repository to your machine, and installing the Composer dependencies.

```bash
git clone https://github.com/lupanvi/react-twitter-backend.git
cd react-twitter-backend
composer install
php artisan key:generate
php artisan migrate --seed
```

### Step 2

Next, boot up the php server that comes with Laravel

```bash
php artisan serve --host localhost
```

In order to work properly, your frontend requests must point to the same main domain