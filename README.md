## Pre-Interview Assignment -  Rails or Laravel Backend Dev
### The Assignments

#### Password generator

- Write a password generator which is able to have small, capital letters, numbers, symbols and minimum length.
- The generator can customize it such as small, capital letters, numbers and minimum length or all above.
- Supported symbols: **['!', '#', '$', '%', '&', '(', ')', '*', '+', '@', '^']**
- **Print out** the password.

#### Pizza ordering system

- Build a simple automatic pizza ordering program.
- Pizza prices:
  - Small pizza: RM15
  - Medium pizza: RM22
  - Large pizza: RM30
- Pizza add-ons:
  - Pepperoni for small pizza: +RM3
  - Pepperoni for medium pizza: +RM5
  - Extra cheese for any size pizza: +RM6
- Based on an user’s order, work out their **final bill**.


## Solution

The solutions are fun and interactive console application built with Laravel 11.

### Stack/Technology used
- PHP 8
- Laravel 11
- PestPHP2

### Deployment

To deploy, clone the repository by running these commands:

```bash
  mkdir billplz-assignment && cd billplz-assignment
  git clone https://github.com/kanwarkamli/billplz-assignment.git .
  cp .env.example .env
  docker compose up -d
```

### Setting up the app

Once the containers are running, run these commands:

```bash
  composer install
  php artisan key:generate
```

### Usage

Once you have the project set up, you can run the commands as follows:

- Generate a password: `php artisan app:generate-password {--length=} {--lowercase} {--uppercase} {--numbers} {--symbols}`
- Order a pizza: `php artisan app:order-pizza`

### Testing
This project includes unit tests. To run the tests, execute the following command:

```bash
  ./vendor/bin/pest
```
