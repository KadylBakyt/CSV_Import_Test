## CSV_Import_Test

#### Must have applications on your PC

  1. [Docker](https://www.docker.com/get-started/)
  2. [Docker Compose](https://docs.docker.com/compose/install/)
  3. [Git](https://git-scm.com/downloads)

### Built With

 1. [Laravel](https://laravel.com/)
 2. [MySQL](https://www.mysql.com/)

### Libraries
 1. [League/csv](https://csv.thephpleague.com/)
 2. phpunit/phpunit
 3. laravel/sail

#### Installation
  

  ```sh
  git clone https://github.com/KadylBakyt/CSV_Import_Test.git
  ```

  ```sh
  cd CSV_Import_Test
  ```

  ```sh
  ./vendor/bin/sail install
  ```
  
  ```sh
  ./vendor/bin/sail up -d
  ```

  ```sh
  ./vendor/bin/sail composer install
  ```

 ```sh
  cp .env.example .env
  ```

  ```sh
  ./vendor/bin/sail artisan migrate:fresh
  ```

  ```sh
  ./vendor/bin/sail artisan serve
  ```

  ```sh
  ./vendor/bin/sail artisan storage:link
  ```


#### Laravel main page

> Open the link: [http://localhost:8080](http://localhost:8080)

#### CLI commands

## CLI example: 
> `php artisan import:csv <CSVFileName> <TEST_Mode>` 

* Copy the `stock.csv` file to public storage folder

  ```sh
  cp stock.csv storage/app/public/stock.csv 
  ```

* Insert the data into the DB
  ```sh
  ./vendor/bin/sail artisan import:csv stock.csv
  ```

* OR(on your localPC or in docker exec)
  ```sh
  php artisan import:csv stock.csv
  ```

* No insert the data into the DB('Test' mode)
  ```sh
  ./vendor/bin/sail artisan import:csv stock.csv test 
  ```

* OR(on your localPC or in docker exec)
  ```sh
  php artisan import:csv stock.csv tes
  ```

## Screens 
![image](https://github.com/user-attachments/assets/03ae31c2-138e-4573-93ea-6e0d3c105b52)

# run test mode
![image](https://github.com/user-attachments/assets/45d862cf-37f4-4f52-bad7-cecdeb01dece)

# DB table
![image](https://github.com/user-attachments/assets/6311d71e-7f91-46bf-b6e0-9e24b5548580)

# Testing
![image](https://github.com/user-attachments/assets/fe586991-6677-41fd-8217-6d75a738a700)
