<?php

require_once __DIR__ . '/vendor/autoload.php';

$client = new Zend\Soap\Client('http://127.0.0.1/select1/soap/server.php?wsdl');

try {
    $result = $client->postBPM(['id' => '001', 'bpm' => 60]);
    echo $result->postBPMResult;
} catch (SoapFault $e) {
    echo "Can't insert value.";
}

?>


