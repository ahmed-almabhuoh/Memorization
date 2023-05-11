#!/bin/bash

cd /path/to/your/laravel/app
php artisan queue:work --sleep=3 --tries=3