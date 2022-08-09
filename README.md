
# Fluffy Chainsaw

This is an employee management platform used by one user.

## Requirements

To deploy this project run

```bash
  php8 and above
  MySQL Database
```

## Installation and Run Locally

Clone the project

```bash
  https://github.com/nzuzondlovu/fluffy-chainsaw
```

Go to the project directory

```bash
  cd fluffy-chainsaw
```

Install dependencies

```bash
  composer install
```

Add Database connection and Migrate Databse

```bash
  cp .env.example .env
  php artisan migrate
```

Get API KEY from https://apilayer.com/marketplace/exchangerates_data-api
Update the following env variable
```bash
  EXCHANGE_RATE_KEY
```

Start the server

```bash
  php artisan serve
```

