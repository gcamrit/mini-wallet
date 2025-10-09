# Mini Wallet

Mini Wallet is a web application that allows users to manage their digital wallet, transfer funds to other users, and view their transaction history.

## Features

- User registration and authentication
- View current balance
- Transfer funds to other users
- Real-time transaction updates
- View transaction history

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- Docker

## Setup Instructions

1.  **Clone the repository:**
    ```bash
    git clone git@github.com:gcamrit/mini-wallet.git
    cd mini-wallet
    ```

2.  **Start the database container:**
    ```bash
    docker compose up -d
    ```

3.  **Create the environment file:**
    ```bash
    cp .env.example .env
    ```

4.  **Configure your `.env` file.** Update the database credentials to match the ones in `compose.yaml`:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mini_wallet
    DB_USERNAME=root
    DB_PASSWORD=supersecretpassword

    BROADCAST_CONNECTION=pusher
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=

    VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    ```

    You will also need to create a free account on [Pusher](https://pusher.com/) to get your App ID, Key, Secret, and Cluster.

5.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

6.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

7.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

8.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

9.  **Run the development servers:**
    This command will start the PHP server, queue listener, log viewer, and Vite development server.
    ```bash
    composer run dev
    ```

    The application will be available at `http://localhost:8000`.

## Running Tests

To run the feature and unit tests, use the following command:

```bash
composer test
```

## API Endpoints

- `POST /api/login`: Login a user
- `POST /api/register`: Register a new user
- `GET /api/whoami`: Get the authenticated user's details
- `GET /api/transactions`: Get the authenticated user's transactions
- `POST /api/transactions`: Transfer funds to another user



## TODOS

- frontend: add pagination on transactions list page
- frontend: user feedback messages
- backend: add logging on transfer service
- backend: define common error message structure
