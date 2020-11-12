## Mini Store

Technology used Laravel version:8.x and Vuejs version:3.0

### Install

Backend:

```
>> cd /path/of/project/backend
>> composer install
# cp .env.example .env and change config of our database
# if ou want to run test before you start the setup
>> php artisan test
# If everything done and i am sure it will be done
>> php artisan migrate
>> php artisan passport:install
>> php artisan db:seed
>> php artisan storage:link
>> php artisan serve
```

frontend;

```
>> cd /path/of/project/frontend
>> cp env.sample .env # do not forget to set the url or ip of your server API (LARAVEL)
>> npm install
>> npm watch
```
# conclusion

This mini store has two kind of user Admin and Moderator
* admin has the possibility to do everything.
* moderator has the possibility to manage categories, provider and products
* for Api request you can find **POSTMAN** file in backend repository **request_test**
Please enjoy
