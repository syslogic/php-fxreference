# php-fxreference
An Foreign Exchange Rate Reference Class for PHP5

## Abstract
This class obtains the current & historical Foreign Exchange Rates from the ECB.

The source XML is updated daily around 16:00 CET, Central European Standard Time.
    
## Historical Data (RSS Feed)
    include_once 'fxreference.php';
    $reference = new fxreference();
    $data = $reference->toArray();
    
## Daily Fix (XML Feed)
    include_once 'fxreference.php';
    $reference = new fxreference('usd');
    $data = $reference->toArray();
