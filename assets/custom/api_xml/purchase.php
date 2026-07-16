<?php
session_start();
require_once "../connect.php";

$ids = '('.$_REQUEST['ids'].')';

$dt_start = $_SESSION['start'];
$dt_end = $_SESSION['end'];

$xmlString = '<ENVELOPE>
 <HEADER>
  <TALLYREQUEST>Import Data</TALLYREQUEST>
 </HEADER>
 <BODY>
  <IMPORTDATA>
   <REQUESTDESC>
    <REPORTNAME>Vouchers</REPORTNAME>
    <STATICVARIABLES>
     <SVCURRENTCOMPANY>AMMAR INDUSTRIAL CORPORATION</SVCURRENTCOMPANY>
    </STATICVARIABLES>
   </REQUESTDESC>
   <REQUESTDATA>';

if($_REQUEST['ids'] == 'all'){
    $sql = "SELECT * FROM purchase_invoice WHERE pi_date BETWEEN '$dt_start' AND '$dt_end' AND `series` = 'PRIMARY' ORDER BY pi_no";
}else{
    $sql = "SELECT * FROM purchase_invoice WHERE id IN $ids AND `series` = 'PRIMARY'";
}
$query = $db->query($sql);

while($row = $query->fetch_assoc()){

  $invoice = $row['pi_no'];
  $supplier = $row['supplier_name'];
  $invoice_date = date('Ymd', strtotime($row['pi_date']));

  // Testing
  // $invoice_date = '20200401';

  $sql_pull = "SELECT * FROM suppliers WHERE name = '$supplier'";
  $query_pull = $db->query($sql_pull);
  $row_pull = $query_pull->fetch_assoc();

  $state = $row_pull['state'];
  $gstin = $row_pull['gstin'];
  $gstin_type = $row_pull['gstin_type'];
  $gstin_type = str_replace("Registered", "Regular", $gstin_type);
  $gstin_type = str_replace("Unregistered", "Unregistered/Consumer", $gstin_type);
  $country = $row_pull['country'];
  $address = json_decode($row_pull['address'], true);
  $ad1 = $address['address_1'];
  $ad2 = $address['address_2'];
  $ad1 = htmlspecialchars($ad1, ENT_XML1, 'UTF-8');
  $ad2 = htmlspecialchars($ad2, ENT_XML1, 'UTF-8');
  $pincode = $address['pincode'];
  $city = $address['city'];
  $ad3 = $city.' - '.$pincode;
  $ad3 = htmlspecialchars($ad3, ENT_XML1, 'UTF-8');
  $supplier = htmlspecialchars($supplier, ENT_XML1, 'UTF-8');
  

    //Amounts
    $item_details = json_decode($row['items'], true);
    $l = sizeof($item_details['product']);

    // $total=0;
    $cgst=0;
    $sgst=0;
    $igst=0;
    for($i=0;$i<$l;$i++){
        $cgst  += round($item_details['cgst'][$i], 2); 
        $sgst  += round($item_details['sgst'][$i], 2); 
        $igst  += round($item_details['igst'][$i], 2); 
    }

    $addons = json_decode($row['addons'], true);

    if($addons['freight']['value'] != '0.00'){
        $cgst  += round($addons['freight']['cgst'], 2);
        $sgst  += round($addons['freight']['sgst'], 2);
        $igst  += round($addons['freight']['igst'], 2);
    }
    if($addons['pf']['value'] != '0.00'){
        $cgst  += round($addons['pf']['cgst'], 2);
        $sgst  += round($addons['pf']['sgst'], 2);
        $igst  += round($addons['pf']['igst'], 2);
    }

    $total  = round($row['total'],2);
    if($addons['tcs']!='' || $addons['tcs']!='0')
    {
        $total = $total - $addons['tcs'];
    }
    $round  = $addons['roundoff'];
    $round  = -1 * $round;

if($state == 'WEST BENGAL'){
   $xmlString .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
    <VOUCHER VCHTYPE="Purchase" ACTION="Create" OBJVIEW="Invoice Voucher View">
        <ADDRESS.LIST TYPE="String">
            <ADDRESS>'.$ad1.'</ADDRESS>
            <ADDRESS>'.$ad2.'</ADDRESS>
            <ADDRESS>'.$ad3.'</ADDRESS>
        </ADDRESS.LIST>
        <BASICBUYERADDRESS.LIST TYPE="String">
            <BASICBUYERADDRESS>'.$ad1.'</BASICBUYERADDRESS>
            <BASICBUYERADDRESS>'.$ad2.'</BASICBUYERADDRESS>
            <BASICBUYERADDRESS>'.$ad3.'</BASICBUYERADDRESS>
        </BASICBUYERADDRESS.LIST>
        <BASICORDERTERMS.LIST TYPE="String">
            <BASICORDERTERMS>Immediate</BASICORDERTERMS>
        </BASICORDERTERMS.LIST>
        <OLDAUDITENTRYIDS.LIST TYPE="Number">
            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
        </OLDAUDITENTRYIDS.LIST>
        <DATE>'.$invoice_date.'</DATE>
        <REFERENCEDATE>'.$invoice_date.'</REFERENCEDATE>
        <STATENAME>'.$state.'</STATENAME>
        <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
        <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
        <PARTYNAME>'.$supplier.'</PARTYNAME>
        <CMPGSTIN>19AEKPB4862M1Z2</CMPGSTIN>
        <VOUCHERTYPENAME>Purchase</VOUCHERTYPENAME>
        <REFERENCE>'.$invoice.'</REFERENCE>
        <PARTYLEDGERNAME>'.$supplier.'</PARTYLEDGERNAME>
        <BASICBASEPARTYNAME>'.$supplier.'</BASICBASEPARTYNAME>
        <CSTFORMISSUETYPE />
        <CSTFORMRECVTYPE />
        <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
        <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
        <PLACEOFSUPPLY>WEST BENGAL</PLACEOFSUPPLY>
        <CONSIGNEEGSTIN>'.$gstin.'</CONSIGNEEGSTIN>
        <BASICSHIPPEDBY>Hand Delivery</BASICSHIPPEDBY>
        <BASICBUYERNAME>'.$supplier.'</BASICBUYERNAME>
        <BASICFINALDESTINATION>Kolkata</BASICFINALDESTINATION>
        <BASICDUEDATEOFPYMT>100% on Order</BASICDUEDATEOFPYMT>
        <VCHGSTCLASS />
        <CONSIGNEESTATENAME>'.$state.'</CONSIGNEESTATENAME>
        <ISINVOICE>Yes</ISINVOICE>
        <HASDISCOUNTS>Yes</HASDISCOUNTS>
        <INVOICEORDERLIST.LIST>
            <BASICORDERDATE>'.$invoice_date.'</BASICORDERDATE>
            <BASICPURCHASEORDERNO>XYZ123</BASICPURCHASEORDERNO>
        </INVOICEORDERLIST.LIST>
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
        <LEDGERENTRIES.LIST>
        <LEDGERNAME>'.$supplier.'</LEDGERNAME>
        <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
        <AMOUNT>'.$total.'.00</AMOUNT>
        <BILLALLOCATIONS.LIST>
            <NAME>'.$invoice.'</NAME>
            <BILLTYPE>New Ref</BILLTYPE>
            <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
            <AMOUNT>'.$total.'.00</AMOUNT>
            <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
            <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
        </BILLALLOCATIONS.LIST>
        </LEDGERENTRIES.LIST>';

        if($addons['pf']['value'] != '0.00'){
            $pf = $addons['pf']['value'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>P&amp;F PURCHASES</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>-'.$pf.'</AMOUNT>
            <VATEXPAMOUNT>-'.$pf.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        if($addons['freight']['value'] != '0.00'){
            $fr = $addons['freight']['value'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>FREIGHT PURCHASES</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>-'.$fr.'</AMOUNT>
            <VATEXPAMOUNT>-'.$fr.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>INPUT CGST</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>-'.$cgst.'</AMOUNT>
            <VATEXPAMOUNT>-'.$cgst.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>INPUT SGST</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>-'.$sgst.'</AMOUNT>
            <VATEXPAMOUNT>-'.$sgst.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>ROUND OFF PURCHASES (+/-)</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$round.'</AMOUNT>
            <VATEXPAMOUNT>'.$round.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';



        for($i=0;$i<$l;$i++){

              // $pr_name = $item_details['product'][$i];
              // $pr_desc = $item_details['desc'][$i];
              // $pr_rate = $item_details['price'][$i];
              // $pr_discount = $item_details['discount'][$i];
              // $pr_quantity = $item_details['quantity'][$i];

              $pr_name = $item_details['product'][$i];
              $pr_name = htmlspecialchars($pr_name, ENT_XML1, 'UTF-8');
              $pr_desc = $item_details['desc'][$i];
              $pr_desc = htmlspecialchars($pr_desc, ENT_XML1, 'UTF-8');
              $pr_rate = $item_details['price'][$i];
              $pr_discount = $item_details['discount'][$i];
              $pr_quantity = $item_details['quantity'][$i];

              $pr_val = $item_details['quantity'][$i]*$item_details['price'][$i];
              $pr_amount = $pr_val * ((100-$item_details['discount'][$i])/100);

              $xmlString .= '<ALLINVENTORYENTRIES.LIST>
                <BASICUSERDESCRIPTION.LIST TYPE="String">
                    <BASICUSERDESCRIPTION>'.$pr_desc.'</BASICUSERDESCRIPTION>
                </BASICUSERDESCRIPTION.LIST>
                <STOCKITEMNAME>'.$pr_name.'</STOCKITEMNAME>
                <RATE>'.$pr_rate.'</RATE>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <DISCOUNT> '.$pr_discount.'</DISCOUNT>
                <AMOUNT>-'.round($pr_amount,2).'</AMOUNT>
                <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
                <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
                <BATCHALLOCATIONS.LIST>
                    <GODOWNNAME>Main Location</GODOWNNAME>
                    <BATCHNAME>Primary Batch</BATCHNAME>
                    <INDENTNO />
                    <ORDERNO />
                    <AMOUNT>-'.round($pr_amount,2).'</AMOUNT>
                    <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
                    <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
                </BATCHALLOCATIONS.LIST>
                <ACCOUNTINGALLOCATIONS.LIST>
                    <OLDAUDITENTRYIDS.LIST TYPE="Number">
                        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                    </OLDAUDITENTRYIDS.LIST>
                    <LEDGERNAME>PURCHASES LOCAL</LEDGERNAME>
                    <GSTCLASS />
                    <AMOUNT>-'.round($pr_amount,2).'</AMOUNT>
                    <VATEXPAMOUNT>-'.round($pr_amount,2).'</VATEXPAMOUNT>
                </ACCOUNTINGALLOCATIONS.LIST>
            </ALLINVENTORYENTRIES.LIST>';
          }

      } else {
        $xmlString .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
    <VOUCHER VCHTYPE="Purchase" ACTION="Create" OBJVIEW="Invoice Voucher View">
        <ADDRESS.LIST TYPE="String">
            <ADDRESS>'.$ad1.'</ADDRESS>
            <ADDRESS>'.$ad2.'</ADDRESS>
            <ADDRESS>'.$ad3.'</ADDRESS>
        </ADDRESS.LIST>
        <BASICBUYERADDRESS.LIST TYPE="String">
            <BASICBUYERADDRESS>'.$ad1.'</BASICBUYERADDRESS>
            <BASICBUYERADDRESS>'.$ad2.'</BASICBUYERADDRESS>
            <BASICBUYERADDRESS>'.$ad3.'</BASICBUYERADDRESS>
        </BASICBUYERADDRESS.LIST>
        <BASICORDERTERMS.LIST TYPE="String">
            <BASICORDERTERMS>Immediate</BASICORDERTERMS>
        </BASICORDERTERMS.LIST>
        <OLDAUDITENTRYIDS.LIST TYPE="Number">
            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
        </OLDAUDITENTRYIDS.LIST>
        <DATE>'.$invoice_date.'</DATE>
        <REFERENCEDATE>'.$invoice_date.'</REFERENCEDATE>
        <STATENAME>'.$state.'</STATENAME>
        <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
        <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
        <PARTYNAME>'.$supplier.'</PARTYNAME>
        <CMPGSTIN>19AEKPB4862M1Z2</CMPGSTIN>
        <VOUCHERTYPENAME>Purchase</VOUCHERTYPENAME>
        <REFERENCE>'.$invoice.'</REFERENCE>
        <PARTYLEDGERNAME>'.$supplier.'</PARTYLEDGERNAME>
        <BASICBASEPARTYNAME>'.$supplier.'</BASICBASEPARTYNAME>
        <CSTFORMISSUETYPE />
        <CSTFORMRECVTYPE />
        <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
        <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
        <PLACEOFSUPPLY>WEST BENGAL</PLACEOFSUPPLY>
        <CONSIGNEEGSTIN>'.$gstin.'</CONSIGNEEGSTIN>
        <BASICSHIPPEDBY>Han Delivery</BASICSHIPPEDBY>
        <BASICBUYERNAME>'.$supplier.'</BASICBUYERNAME>
        <BASICFINALDESTINATION>Kolkata</BASICFINALDESTINATION>
        <BASICDUEDATEOFPYMT>100% on Order</BASICDUEDATEOFPYMT>
        <VCHGSTCLASS />
        <CONSIGNEESTATENAME>'.$state.'</CONSIGNEESTATENAME>
        <ISINVOICE>Yes</ISINVOICE>
        <HASDISCOUNTS>Yes</HASDISCOUNTS>
        <INVOICEORDERLIST.LIST>
            <BASICORDERDATE>'.$invoice_date.'</BASICORDERDATE>
            <BASICPURCHASEORDERNO>XYZ123</BASICPURCHASEORDERNO>
        </INVOICEORDERLIST.LIST>
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
        <LEDGERENTRIES.LIST>
        <LEDGERNAME>'.$supplier.'</LEDGERNAME>
        <AMOUNT>'.$total.'.00</AMOUNT>
        <BILLALLOCATIONS.LIST>
            <NAME>'.$invoice.'</NAME>
            <BILLTYPE>New Ref</BILLTYPE>
            <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
            <AMOUNT>'.$total.'.00</AMOUNT>
            <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
            <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
        </BILLALLOCATIONS.LIST>
        </LEDGERENTRIES.LIST>';

        if($addons['pf']['value'] != '0.00'){
            $pf = $addons['pf']['value'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>P&amp;F PURCHASES</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>-'.$pf.'</AMOUNT>
            <VATEXPAMOUNT>-'.$pf.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        if($addons['freight']['value'] != '0.00'){
            $fr = $addons['freight']['value'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>FREIGHT PURCHASES</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>-'.$fr.'</AMOUNT>
            <VATEXPAMOUNT>-'.$fr.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>INPUT IGST</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>-'.$igst.'</AMOUNT>
            <VATEXPAMOUNT>-'.$igst.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>ROUND OFF PURCHASES (+/-)</LEDGERNAME>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$round.'</AMOUNT>
            <VATEXPAMOUNT>'.$round.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        for($i=0;$i<$l;$i++){

          // $pr_name = $item_details['product'][$i];
          // $pr_desc = $item_details['desc'][$i];
          // $pr_rate = $item_details['price'][$i];
          // $pr_discount = $item_details['discount'][$i];
          // $pr_quantity = $item_details['quantity'][$i];

          $pr_name = $item_details['product'][$i];
          $pr_name = htmlspecialchars($pr_name, ENT_XML1, 'UTF-8');
          $pr_desc = $item_details['desc'][$i];
          $pr_desc = htmlspecialchars($pr_desc, ENT_XML1, 'UTF-8');
          $pr_rate = $item_details['price'][$i];
          $pr_discount = $item_details['discount'][$i];
          $pr_quantity = $item_details['quantity'][$i];

          $pr_val = $item_details['quantity'][$i]*$item_details['price'][$i];
          $pr_amount = $pr_val * ((100-$item_details['discount'][$i])/100);

          $xmlString .= '<ALLINVENTORYENTRIES.LIST>
            <BASICUSERDESCRIPTION.LIST TYPE="String">
                <BASICUSERDESCRIPTION>'.$pr_desc.'</BASICUSERDESCRIPTION>
            </BASICUSERDESCRIPTION.LIST>
            <STOCKITEMNAME>'.$pr_name.'</STOCKITEMNAME>
            <RATE>'.$pr_rate.'</RATE>
            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
            <DISCOUNT> '.$pr_discount.'</DISCOUNT>
            <AMOUNT>-'.round($pr_amount,2).'</AMOUNT>
            <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
            <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            <BATCHALLOCATIONS.LIST>
                <GODOWNNAME>Main Location</GODOWNNAME>
                <BATCHNAME>Primary Batch</BATCHNAME>
                <INDENTNO />
                <ORDERNO />
                <AMOUNT>-'.round($pr_amount,2).'</AMOUNT>
                <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
                <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            </BATCHALLOCATIONS.LIST>
            <ACCOUNTINGALLOCATIONS.LIST>
                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                    <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                </OLDAUDITENTRYIDS.LIST>
                <LEDGERNAME>PURCHASES INTERSTATE</LEDGERNAME>
                <GSTCLASS />
                <AMOUNT>-'.round($pr_amount,2).'</AMOUNT>
                <VATEXPAMOUNT>-'.round($pr_amount,2).'</VATEXPAMOUNT>
            </ACCOUNTINGALLOCATIONS.LIST>
        </ALLINVENTORYENTRIES.LIST>';
      }

      }

        

    $xmlString .= '</VOUCHER>
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
$dom->save('purchase.xml');

//View XML document
// $dom->formatOutput = TRUE;
echo $dom->saveXml();

?>