# php-fxreference
An Foreign Exchange Rate Reference Class for PHP5

## Abstract
This class obtains the current & historical Foreign Exchange Rates from the ECB.

The source XML is updated daily around 16:00 CET, Central European Standard Time.
    
## Usage: Historical Data (one currency symbol)

    include_once 'fxreference.php';
    $reference = new fxreference('usd');
    
    $data = $reference->toArray();
    
## Usage: Daily Fix (several currency symbols)

    include_once 'fxreference.php';
    $reference = new fxreference();
    
    $data = $reference->toArray();
