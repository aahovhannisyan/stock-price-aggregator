# Stock Price Aggregator

This Laravel application is designed to fetch and store data periodically from the Alpha Vantage API
and provide reports based on that data. The application is set up using Laravel Sail for local development.

## Prerequisites

Before you begin, ensure you have the following installed:
- Windows Subsystem for Linux (WSL 2)
- Docker
## Installation

Follow these steps to set up and run the application locally:
- Clone the Repository
- Install the application dependencies using the following command:
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

- Build and start the Docker containers using Sail:
```shell
./vendor/bin/sail up
```

This command will build the Docker images and start the containers. It may take a few minutes to complete.
- Copy the example environment file to create your own .env file:
```shell
cp .env.example .env
```

- Update the .env file with your Alpha Vantage API key and any other necessary configurations.
- Generate a new application key with:
```shell
./vendor/bin/sail artisan key:generate
```

- Apply the database migrations to set up your schema:
```shell
./vendor/bin/sail artisan migrate
```

- Build the artifacts:
```shell
./vendor/bin/sail npm i
./vendor/bin/sail npm run build
```

- To fetch data from Alpha Vantage API, execute the following command:
```shell
./vendor/bin/sail artisan prices:fetch
```
Or set the `schedule:run` command to be run every minute via cron.

## API endpoints

The application exposes 2 REST API endpoints:
- /api/latest-stock-price
- /api/report

The OpenAPI Specification for these endpoints can be found here: `http://your-domain/api/documentation`

## User Interface

The application provides a UI that shows the latest stock price with visual indicators (e.g: color-coded arrows)
to display positive or negative changes. It can be accessed via the following URL: `/report?symbol={symbol}`
