<?php

require_once __DIR__ . '/vendor/autoload.php';

class BPM 
{ 
  /**
   * Say hello.
   *
   * @param string $id, $bpm
   * @return string $callback
   */
  public function postBPM($id, $bpm)
  {
    $servername = "servername";
    $username = "username";
    $password = "password";
    $database = "database";
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        return "Can't insert value.";
    } 
    echo "Connected successfully";

    $sql = "INSERT INTO bpm_list (device_id, bpm_value) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("ss", $id, $bpm);
      $stmt->execute();
      $stmt->close();
      return "New record created successfully";
    } else {
      return "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}

$serverUrl = "http://127.0.0.1/select1/soap/server.php";
$options = [
    'uri' => $serverUrl,
];
$server = new Zend\Soap\Server(null, $options);

if (isset($_GET['wsdl'])) {
    $soapAutoDiscover = new \Zend\Soap\AutoDiscover(
      new \Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeSequence());
    $soapAutoDiscover->setBindingStyle(array('style' => 'document'));
    $soapAutoDiscover->setOperationBodyStyle(array('use' => 'literal'));
    $soapAutoDiscover->setClass('BPM');
    $soapAutoDiscover->setUri($serverUrl);
    
    header("Content-Type: text/xml");
    echo $soapAutoDiscover->generate()->toXml();
} else {
    $soap = new \Zend\Soap\Server($serverUrl . '?wsdl');
    $soap->setObject(new \Zend\Soap\Server\DocumentLiteralWrapper(new BPM()));
    $soap->handle();
}

?>