<?php
session_start();
require_once "../connect.php";

$from = date('Y-m-d', strtotime($_REQUEST['masters_xml_from']));
$to = date('Y-m-d', strtotime($_REQUEST['masters_xml_to']));

$xmlString = '<ENVELOPE>
    <HEADER>
        <TALLYREQUEST>Import Data</TALLYREQUEST>
    </HEADER>

    <BODY>
        <IMPORTDATA>
            <REQUESTDESC>
                <REPORTNAME>All Masters</REPORTNAME>
                <STATICVARIABLES>
                    <SVCURRENTCOMPANY>AMMAR INDUSTRIAL CORPORATION</SVCURRENTCOMPANY>
                </STATICVARIABLES>
            </REQUESTDESC>
            <REQUESTDATA>';

$export_date = '2021-06-01';
$sql = "SELECT * FROM clients WHERE log_date  BETWEEN '$from' AND '$to'";
$sql = "SELECT * FROM clients";
$query = $db->query($sql);

while($row = $query->fetch_assoc()){
  $name = $row['name'];
  $name = htmlspecialchars($name, ENT_XML1, 'UTF-8');
  $country = $row['country'];
  $state = htmlspecialchars($row['state'], ENT_XML1, 'UTF-8');
  $address = json_decode($row['address'], true);
  $ad1 = $address['address_1'];
  $ad2 = $address['address_2'];
  $ad1 = htmlspecialchars($ad1, ENT_XML1, 'UTF-8');
  $ad2 = htmlspecialchars($ad2, ENT_XML1, 'UTF-8');
  $pincode = $address['pincode'];
  $pincode = htmlspecialchars($pincode, ENT_XML1, 'UTF-8');
  $opening = $row['opening_balance'];


  $gstin = $row['gstin'];
  $gstin_type = $row['gstin_type'];
  $gstin_type = str_replace("Registered", "Regular", $gstin_type);
  $gstin_type = str_replace("Unregistered", "Unregistered/Consumer", $gstin_type);

   $xmlString .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
     <LEDGER NAME="'.$name.'" RESERVEDNAME="">

     <LEDGSTREGDETAILS.LIST>
       <APPLICABLEFROM>20200401</APPLICABLEFROM>
       <GSTREGISTRATIONTYPE>'.$gstin_type.'</GSTREGISTRATIONTYPE>
       <STATE>'.$state.'</STATE>
      <COUNTRYNAME>'.$country.'</COUNTRYNAME>

       <PLACEOFSUPPLY>'.$state.'</PLACEOFSUPPLY>
       <GSTIN>'.$gstin.'</GSTIN>
       <ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>
       <CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>
       <ISTRANSPORTER>No</ISTRANSPORTER>
       <ISCOMMONPARTY>No</ISCOMMONPARTY>
      </LEDGSTREGDETAILS.LIST>
      <LEDMAILINGDETAILS.LIST>
       <ADDRESS.LIST TYPE="String">
        <ADDRESS>'.$ad1.'</ADDRESS>
        <ADDRESS>'.$ad2.'</ADDRESS>
       </ADDRESS.LIST>
       <APPLICABLEFROM>20200401</APPLICABLEFROM>
       <PINCODE>'.$pincode.'</PINCODE>
       <MAILINGNAME>'.$name.'</MAILINGNAME>
       <STATE>'.$state.'</STATE>
       <COUNTRY>'.$country.'</COUNTRY>
      </LEDMAILINGDETAILS.LIST>
      <OLDCOUNTRYNAME>'.$country.'</OLDCOUNTRYNAME>

      <MAILINGNAME.LIST TYPE="String">
       <MAILINGNAME>'.$name.'</MAILINGNAME>
      </MAILINGNAME.LIST>
      <PINCODE>'.$pincode.'</PINCODE>
      <GSTREGISTRATIONTYPE>'.$gstin_type.'</GSTREGISTRATIONTYPE>
      <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
      <PARENT>Sundry Debtors</PARENT>
      <TAXCLASSIFICATIONNAME/>
      <TAXTYPE>Others</TAXTYPE>
      <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
      <GSTTYPE/>
      <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
      <LEDSTATENAME>'.$state.'</LEDSTATENAME>
      <OPENINGBALANCE>-'.$opening.'</OPENINGBALANCE>
      <LANGUAGENAME.LIST>
       <NAME.LIST TYPE="String">
        <NAME>'.$name.'</NAME>
       </NAME.LIST>
       <LANGUAGEID> 1033</LANGUAGEID>
      </LANGUAGENAME.LIST>
     </LEDGER>
    </TALLYMESSAGE>';
}

$sql = "SELECT * FROM suppliers WHERE log_date  BETWEEN '$from' AND '$to'";
// $sql = "SELECT * FROM suppliers";
$query = $db->query($sql);

while($row = $query->fetch_assoc()){
  $name = $row['name'];
  $name = htmlspecialchars($name, ENT_XML1, 'UTF-8');
  $country = $row['country'];
  $state = htmlspecialchars($row['state'], ENT_XML1, 'UTF-8');
  $address = json_decode($row['address'], true);
  $ad1 = $address['address_1'];
  $ad2 = $address['address_2'];
  $ad1 = htmlspecialchars($ad1, ENT_XML1, 'UTF-8');
  $ad2 = htmlspecialchars($ad2, ENT_XML1, 'UTF-8');
  $pincode = $address['pincode'];
  $pincode = htmlspecialchars($pincode, ENT_XML1, 'UTF-8');

  $gstin = $row['gstin'];
  $gstin_type = $row['gstin_type'];

   $xmlString .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
     <LEDGER NAME="'.$name.'" RESERVEDNAME="">
      <ADDRESS.LIST TYPE="String">
       <ADDRESS>'.$ad1.'</ADDRESS>
       <ADDRESS>'.$ad2.'</ADDRESS>
      </ADDRESS.LIST>
      <MAILINGNAME.LIST TYPE="String">
       <MAILINGNAME>'.$name.'</MAILINGNAME>
      </MAILINGNAME.LIST>
      <PINCODE>'.$pincode.'</PINCODE>
      <GSTREGISTRATIONTYPE>'.$gstin_type.'</GSTREGISTRATIONTYPE>
       <COUNTRY>'.$country.'</COUNTRY>
      <OLDCOUNTRYNAME>'.$country.'</OLDCOUNTRYNAME>
      <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
      <PARENT>Sundry Creditors</PARENT>
      <TAXCLASSIFICATIONNAME/>
      <TAXTYPE>Others</TAXTYPE>
      <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
      <GSTTYPE/>
      <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
      <LEDSTATENAME>'.$state.'</LEDSTATENAME>
      <LANGUAGENAME.LIST>
       <NAME.LIST TYPE="String">
        <NAME>'.$name.'</NAME>
       </NAME.LIST>
       <LANGUAGEID> 1033</LANGUAGEID>
      </LANGUAGENAME.LIST>
     </LEDGER>
    </TALLYMESSAGE>';
}


  $xmlString .= '</REQUESTDATA>
        </IMPORTDATA>
    </BODY>
</ENVELOPE>';

$dom = new DOMDocument;
$dom->preserveWhiteSpace = TRUE;
$dom->loadXML($xmlString);

//Save XML as a file
$dom->save('masters.xml');

//View XML document
$dom->formatOutput = TRUE;
echo $dom->saveXml();

?>