# Akeneo PIM PHP API Script

This script utilizes the Akeneo PIM PHP API to fetch and process product data from an Akeneo PIM instance. 
It retrieves product information, attributes, and media files, and saves the processed data into a JSON file.

## Prerequisites

Before running this script, ensure that you have met the following requirements:

- PHP version 7.2 or higher
- [Composer](https://getcomposer.org/) installed

## Installation

1. Clone or download the script files to your local machine.
2. Navigate to the script directory using the command line.
3. Run the following command to install the required dependencies:

```
composer install
```

## Configuration

Open the script file `products_api.php` in a text editor and update the following variables with your Akeneo PIM credentials:

```php
$client_id = '';       // Your client ID
$secret = '';          // Your client secret
$username = '';        // Your username
$password = '';        // Your password
$url = '';             // URL of your Akeneo PIM instance
```

## Usage

To run the script, execute the following command in the script directory:

```
php products_api.php
```

The script will connect to your Akeneo PIM instance using the provided credentials and retrieve product data. 
It will process the data, including attributes and media files, and save the results to a file named `results.json` in the script directory.

## Script Overview

1. The script includes the necessary dependencies using the autoloader provided by Composer.
2. It establishes a connection to the Akeneo PIM instance using the provided credentials.
3. The script constructs a search query to filter products based on a completeness condition.
4. It retrieves a list of products that match the search query, including their associated attributes and media files.
5. Attribute data is fetched and stored in an array for reference during product processing.
6. The script processes each product, extracting relevant information such as identifier, categories, and attribute values.
7. If a product has an associated image, it is downloaded and saved to the local `products` directory.
8. The processed product data is stored in an output array.
9. Finally, the output array is saved as a JSON file named `results.json` in the script directory.

Please note that this is a basic overview of the script's functionality.
