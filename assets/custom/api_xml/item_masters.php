<?php
session_start();
require_once "../connect.php";

ini_set('arg_separator.output',';');

$from = date('Y-m-d', strtotime($_REQUEST['product_xml_from']));
$to = date('Y-m-d', strtotime($_REQUEST['product_xml_to']));


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

$sql = "SELECT * FROM product WHERE log_date  BETWEEN '$from' AND '$to' ";
$query = $db->query($sql);

while($row = $query->fetch_assoc()){
  $name = strtoupper($row['name']);
  $name = htmlspecialchars($name, ENT_XML1, 'UTF-8');
  $aliases = strtoupper($row['aliases']);
  $aliases = htmlspecialchars($aliases, ENT_XML1, 'UTF-8');
  $group = strtoupper($row['group']);
  $group = htmlspecialchars($group, ENT_XML1, 'UTF-8');
  $description = $row['description'];
  $description = htmlspecialchars($description, ENT_XML1, 'UTF-8');
  $unit = $row['unit'];
  $hsn = $row['hsn'];
  $tax = $row['tax'];
  $tax_2 = $tax / 2;
  $rate = $row['rate'];

  $app = '#4; Applicable';
  $any = '#4; Any';

 

  // $app = htmlspecialchars($app, ENT_XML1, 'UTF-8');
  // $any = htmlspecialchars($any, ENT_XML1, 'UTF-8');

  
   $xmlString .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
                    <STOCKITEM NAME="'.$name.'" RESERVEDNAME="">
                        <OLDAUDITENTRYIDS.LIST TYPE="Number">
                            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                        </OLDAUDITENTRYIDS.LIST>
                        <PARENT>'.$group.'</PARENT>
                        <GSTAPPLICABLE>'.$app.'</GSTAPPLICABLE>
                        <TAXCLASSIFICATIONNAME />
                        <DESCRIPTION>'.$description.'</DESCRIPTION>
                        <GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>
                        <EXCISEAPPLICABILITY>'.$app.'</EXCISEAPPLICABILITY>
                        <SALESTAXCESSAPPLICABLE />
                        <VATAPPLICABLE>'.$app.'</VATAPPLICABLE>
                        <COSTINGMETHOD>Avg. Cost</COSTINGMETHOD>
                        <VALUATIONMETHOD>Avg. Price</VALUATIONMETHOD>
                        <BASEUNITS>'.$unit.'</BASEUNITS>
                        <ADDITIONALUNITS />
                        <EXCISEITEMCLASSIFICATION />
                        <VATBASEUNIT>'.$unit.'</VATBASEUNIT>
                        <GSTDETAILS.LIST>
                            <APPLICABLEFROM>20200401</APPLICABLEFROM>
                            <CALCULATIONTYPE>On Value</CALCULATIONTYPE>
                            <HSNMASTERNAME />
                            <TAXABILITY>Taxable</TAXABILITY>
                            <SRCOFGSTDETAILS>Specify Details Here</SRCOFGSTDETAILS>
                            <GSTCALCSLABONMRP>No</GSTCALCSLABONMRP>
                            <ISREVERSECHARGEAPPLICABLE>No</ISREVERSECHARGEAPPLICABLE>
                            <ISNONGSTGOODS>No</ISNONGSTGOODS>
                            <GSTINELIGIBLEITC>No</GSTINELIGIBLEITC>
                            <INCLUDEEXPFORSLABCALC>No</INCLUDEEXPFORSLABCALC>
                            <STATEWISEDETAILS.LIST>
                                <STATENAME>'.$any.'</STATENAME>
                                <RATEDETAILS.LIST>
                                    <GSTRATEDUTYHEAD>CGST</GSTRATEDUTYHEAD>
                                    <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                                    <GSTRATE> '.$tax_2.'</GSTRATE>
                                </RATEDETAILS.LIST>
                                <RATEDETAILS.LIST>
                                    <GSTRATEDUTYHEAD>SGST/UTGST</GSTRATEDUTYHEAD>
                                    <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                                    <GSTRATE> '.$tax_2.'</GSTRATE>
                                </RATEDETAILS.LIST>
                                <RATEDETAILS.LIST>
                                    <GSTRATEDUTYHEAD>IGST</GSTRATEDUTYHEAD>
                                    <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                                    <GSTRATE> '.$tax.'</GSTRATE>
                                </RATEDETAILS.LIST>
                                <RATEDETAILS.LIST>
                                    <GSTRATEDUTYHEAD>Cess</GSTRATEDUTYHEAD>
                                    <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                                </RATEDETAILS.LIST>
                                <RATEDETAILS.LIST>
                                    <GSTRATEDUTYHEAD>Cess on Qty</GSTRATEDUTYHEAD>
                                    <GSTRATEVALUATIONTYPE>Based on Quantity</GSTRATEVALUATIONTYPE>
                                </RATEDETAILS.LIST>
                                <RATEDETAILS.LIST>
                                    <GSTRATEDUTYHEAD>State Cess</GSTRATEDUTYHEAD>
                                    <GSTRATEVALUATIONTYPE>Based on Value</GSTRATEVALUATIONTYPE>
                                </RATEDETAILS.LIST>
                                <GSTSLABRATES.LIST> </GSTSLABRATES.LIST>
                            </STATEWISEDETAILS.LIST>
                            <HSNDETAILS.LIST>
                               <APPLICABLEFROM>20200401</APPLICABLEFROM>
                               <HSNCODE>'.$hsn.'</HSNCODE>
                               <SRCOFHSNDETAILS>Specify Details Here</SRCOFHSNDETAILS>
                              </HSNDETAILS.LIST>
                            <TEMPGSTDETAILSLABRATES.LIST> </TEMPGSTDETAILSLABRATES.LIST>
                        </GSTDETAILS.LIST>
                        <LANGUAGENAME.LIST>
                            <NAME.LIST TYPE="String">
                                <NAME>'.$name.'</NAME>
                            </NAME.LIST>
                            <LANGUAGEID> 1033</LANGUAGEID>
                        </LANGUAGENAME.LIST>
                        <SCHVIDETAILS.LIST> </SCHVIDETAILS.LIST>
                        <EXCISETARIFFDETAILS.LIST> </EXCISETARIFFDETAILS.LIST>
                        <TCSCATEGORYDETAILS.LIST> </TCSCATEGORYDETAILS.LIST>
                        <TDSCATEGORYDETAILS.LIST> </TDSCATEGORYDETAILS.LIST>
                        <EXCLUDEDTAXATIONS.LIST> </EXCLUDEDTAXATIONS.LIST>
                        <OLDAUDITENTRIES.LIST> </OLDAUDITENTRIES.LIST>
                        <ACCOUNTAUDITENTRIES.LIST> </ACCOUNTAUDITENTRIES.LIST>
                        <AUDITENTRIES.LIST> </AUDITENTRIES.LIST>
                        <MRPDETAILS.LIST> </MRPDETAILS.LIST>
                        <VATCLASSIFICATIONDETAILS.LIST> </VATCLASSIFICATIONDETAILS.LIST>
                        <COMPONENTLIST.LIST> </COMPONENTLIST.LIST>
                        <ADDITIONALLEDGERS.LIST> </ADDITIONALLEDGERS.LIST>
                        <SALESLIST.LIST> </SALESLIST.LIST>
                        <PURCHASELIST.LIST> </PURCHASELIST.LIST>
                        <FULLPRICELIST.LIST> </FULLPRICELIST.LIST>
                        <BATCHALLOCATIONS.LIST> </BATCHALLOCATIONS.LIST>
                        <TRADEREXCISEDUTIES.LIST> </TRADEREXCISEDUTIES.LIST>
                        <STANDARDCOSTLIST.LIST>
                            <DATE>20200401</DATE>
                            <RATE>'.$rate.'</RATE>
                        </STANDARDCOSTLIST.LIST>
                        <STANDARDPRICELIST.LIST>
                            <DATE>20200401</DATE>
                            <RATE></RATE>
                        </STANDARDPRICELIST.LIST>
                        <EXCISEITEMGODOWN.LIST> </EXCISEITEMGODOWN.LIST>
                        <MULTICOMPONENTLIST.LIST> </MULTICOMPONENTLIST.LIST>
                        <LBTDETAILS.LIST> </LBTDETAILS.LIST>
                        <PRICELEVELLIST.LIST> </PRICELEVELLIST.LIST>
                        <GSTCLASSFNIGSTRATES.LIST> </GSTCLASSFNIGSTRATES.LIST>
                        <EXTARIFFDUTYHEADDETAILS.LIST> </EXTARIFFDUTYHEADDETAILS.LIST>
                        <TEMPGSTITEMSLABRATES.LIST> </TEMPGSTITEMSLABRATES.LIST>
                    </STOCKITEM>
                </TALLYMESSAGE>';
}


  $xmlString .= '</REQUESTDATA>
  </IMPORTDATA>
    </BODY>
</ENVELOPE>';

// $xmlString = str_replace("#4;", "&amp;#4;",$xmlString);
// $xmlString = encodeURIComponent($xmlString);

$dom = new DOMDocument;
$dom->preserveWhiteSpace = TRUE;
$dom->loadXML($xmlString);

//Save XML as a file
$dom->save('item_masters.xml');

//View XML document
// $dom->formatOutput = TRUE;
// echo $dom->saveXml();

//read the entire string
$str=file_get_contents('item_masters.xml');

//replace something in the file string - this is a VERY simple example
$str=str_replace("#4;", "&#4;",$str);

//write the entire string
file_put_contents('item_masters.xml', $str);

?>