# php-fxreference
A **Foreign Exchange Rate Reference** Class for **PHP5**

## Abstract
Class `fxreference` retrieves the current & historical Foreign Exchange Rates from the ECB.

The source XML for the daily fix is updated daily around 16:00 CET, Central European Standard Time.

## Installation

Class `fxreference` can be referenced with `include_once` or `require_once`.

    include_once 'fxreference.php';

## Usage: retrieve historical data (one currency symbol only)

    $reference = new fxreference('usd');
    
## Usage: retrieve the daily fix (several currency symbols)

    $reference = new fxreference();
    
## Usage: Results to Array

    $data = $reference->toArray();

## Usage: Results to JSON

    $data = $reference->toJson();
    
