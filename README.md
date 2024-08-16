## CSV_Import_Test

#### Must have applications on your PC

  1. [Docker](https://www.docker.com/get-started/)
  2. [Docker Compose](https://docs.docker.com/compose/install/)
  3. [Git](https://git-scm.com/downloads)

### Built With

    * [![Laravel][Laravel.com]][Laravel-url]

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
  ./vendor/bin/sail artisan migrate
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

* Copy the `stock.csv` file to public storage folder

  ```sh
  cp stock.csv storage/app/public/stock.csv 
  ```

* Insert the data into the
  ```sh
  ./vendor/bin/sail artisan import:csv stock.csv
  ```

* No insert the data into the('Test' mode)
  ```sh
  ./vendor/bin/sail artisan import:csv stock.csv test
  ```
### Enjoy ...
