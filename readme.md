<h1>Estimate delivery Date App</h1>

## Prerequisites
- Requirement PHP 8.1+

## Installation
1. Clone the repo
 ```sh
git clone https://github.com/andreic96/estimated-delivery-app
   ```
2. Install PHP packages
```bash
composer install
```

## Usage
To use the app you need to run:
```bash
php bin/main.php
```

You can choose to estimate a delivery date based on the data in the DB (1), or generate some test data(2).<br>

To get a delivery estimated for the current day you have to input a valid zip code, an optional valid start date(historical data range) and an optional valid end date(historical data range):<br>
```bash
1) Estimate shipping  2) Generate shipping data 1
1

Input the ZipCode: 12345
12345

Input the Start Date for historical data (leave empty if not needed): 2022-03-27
2022-03-27

Input the End Date for historical data (leave empty if not needed): 2023-07-12
2023-07-12

Today is: 2023-10-09
Delivery expected on: 2023-09-19
```
