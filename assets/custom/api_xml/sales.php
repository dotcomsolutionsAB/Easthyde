<?php

// ini_set('display_errors', '1');
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
 
// $sql = "SELECT * FROM sales_invoice WHERE si_date <= '2020-10-31' AND si_date >= '2020-10-31' AND series != 'SECONDARY' ORDER BY si_no" ;
// $sql = "SELECT * FROM sales_invoice WHERE `id` = '1450' ORDER BY si_no" ;
if($_REQUEST['ids'] == 'all'){
    $sql = "SELECT * FROM sales_invoice WHERE si_date BETWEEN '$dt_start' AND '$dt_end' AND series != 'SECONDARY' ORDER BY si_no ";
}else{
    $sql = "SELECT * FROM sales_invoice WHERE id IN $ids AND series != 'SECONDARY'";
}
$query = $db->query($sql);

while($row = $query->fetch_assoc()){

    // echo $row['si_no'].'<br/>';

  $invoice = $row['si_no'];
  $client = $row['client_name'];
  $invoice_date = date('Ymd', strtotime($row['si_date']));

  // Testing
  // $invoice_date = '20200401';

  $sql_pull = "SELECT * FROM clients WHERE name = '$client'";
  $query_pull = $db->query($sql_pull);
  $row_pull = $query_pull->fetch_assoc();
  
  $state = htmlspecialchars($row_pull['state'], ENT_XML1, 'UTF-8');
  $client = htmlspecialchars($client, ENT_XML1, 'UTF-8');

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
if($row['cancelled'] == '1'){
    $total  = 0;
}
$round  = $addons['roundoff'];

if($state == 'WEST BENGAL'){
   $xmlString .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
    <VOUCHER VCHTYPE="Sales" ACTION="Create" OBJVIEW="Invoice Voucher View">
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
        <GSTREGISTRATIONTYPE>'.$gstin_type.'</GSTREGISTRATIONTYPE>
        <CMPGSTREGISTRATIONTYPE>'.$gstin_type.'</CMPGSTREGISTRATIONTYPE>
        <STATENAME>'.$state.'</STATENAME>
        <VATDEALERTYPE>Regular</VATDEALERTYPE>
        <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
        <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
        <CONSIGNEEMAILINGNAME>'.$client.'</CONSIGNEEMAILINGNAME>
        <PARTYNAME>'.$client.'</PARTYNAME>
        <CMPGSTIN>19AEKPB4862M1Z2</CMPGSTIN>
        <PARTYPINCODE>'.$pincode.'</PARTYPINCODE>
        <VOUCHERTYPENAME>Sales</VOUCHERTYPENAME>
        <VOUCHERNUMBER>'.$invoice.'</VOUCHERNUMBER>
        <PARTYLEDGERNAME>'.$client.'</PARTYLEDGERNAME>
        <BASICBASEPARTYNAME>'.$client.'</BASICBASEPARTYNAME>
        <CSTFORMISSUETYPE />
        <CSTFORMRECVTYPE />
        <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
        <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
        <PLACEOFSUPPLY>'.$state.'</PLACEOFSUPPLY>
        <CONSIGNEEGSTIN>'.$gstin.'</CONSIGNEEGSTIN>
        <BASICSHIPPEDBY>Hand Delivery</BASICSHIPPEDBY>
        <BASICBUYERNAME>'.$client.'</BASICBUYERNAME>
        <BASICFINALDESTINATION>Kolkata</BASICFINALDESTINATION>
        <BASICDUEDATEOFPYMT>100% on Order</BASICDUEDATEOFPYMT>
        <VCHGSTCLASS />
        <CONSIGNEESTATENAME>'.$state.'</CONSIGNEESTATENAME>
        <ISINVOICE>No</ISINVOICE>
        <HASDISCOUNTS>Yes</HASDISCOUNTS>
        <INVOICEORDERLIST.LIST>
            <BASICORDERDATE>'.$invoice_date.'</BASICORDERDATE>
            <BASICPURCHASEORDERNO>XYZ123</BASICPURCHASEORDERNO>
        </INVOICEORDERLIST.LIST>
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
        <LEDGERENTRIES.LIST>
        <LEDGERNAME>'.$client.'</LEDGERNAME>
        <GSTCLASS/>
        <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
        <LEDGERFROMITEM>No</LEDGERFROMITEM>
        <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
        <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
        <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
        <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
        <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
        <AMOUNT>-'.$total.'.00</AMOUNT>
        <BILLALLOCATIONS.LIST>
            <NAME>'.$invoice.'</NAME>
            <BILLTYPE>New Ref</BILLTYPE>
            <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
            <AMOUNT>-'.$total.'.00</AMOUNT>
            <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
            <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
        </BILLALLOCATIONS.LIST>
        </LEDGERENTRIES.LIST>';

        if($addons['pf']['value'] != '0.00'){
            $pf = $addons['pf']['value'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>P&amp;F SALES</LEDGERNAME>
            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$pf.'</AMOUNT>
            <VATEXPAMOUNT>'.$pf.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        if($addons['freight']['value'] != '0.00'){
            $fr = $addons['freight']['value'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>FREIGHT SALES</LEDGERNAME>
            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$fr.'</AMOUNT>
            <VATEXPAMOUNT>'.$fr.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>OUTPUT CGST</LEDGERNAME>
           <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$cgst.'</AMOUNT>
            <VATEXPAMOUNT>'.$cgst.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>OUTPUT SGST</LEDGERNAME>
            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$sgst.'</AMOUNT>
            <VATEXPAMOUNT>'.$sgst.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        if($round != '0.00'){
            $xmlString .= '<LEDGERENTRIES.LIST>
                <LEDGERNAME>ROUND OFF SALES (+/-)</LEDGERNAME>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <AMOUNT>'.$round.'</AMOUNT>
                <VATEXPAMOUNT>'.$round.'</VATEXPAMOUNT>
                </LEDGERENTRIES.LIST>';
        }



        for($i=0;$i<$l;$i++){

          $pr_name = $item_details['product'][$i];
          $pr_name = htmlspecialchars($pr_name, ENT_XML1, 'UTF-8');
          $pr_desc = $item_details['desc'][$i];
          $pr_desc = htmlspecialchars($pr_desc, ENT_XML1, 'UTF-8');
          $pr_rate = $item_details['price'][$i];
          $pr_discount = $item_details['discount'][$i];
          $pr_quantity = $item_details['quantity'][$i];

          $pr_val = $item_details['quantity'][$i]*$item_details['price'][$i];
          $discount = $item_details['discount'][$i];

          if($discount == '')
            $discount = 0;
          $pr_amount = $pr_val * ((100-$discount)/100);

          $xmlString .= '<ALLINVENTORYENTRIES.LIST>
            <BASICUSERDESCRIPTION.LIST TYPE="String">
                <BASICUSERDESCRIPTION>'.$pr_desc.'</BASICUSERDESCRIPTION>
            </BASICUSERDESCRIPTION.LIST>
            <STOCKITEMNAME>'.$pr_name.'</STOCKITEMNAME>
            <RATE>'.$pr_rate.'</RATE>
            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
            <ISAUTONEGATE>No</ISAUTONEGATE>
            <ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
            <ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
            <ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
            <ISPRIMARYITEM>No</ISPRIMARYITEM>
            <ISSCRAP>No</ISSCRAP>
            <DISCOUNT> '.$pr_discount.'</DISCOUNT>
            <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
            <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
            <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            <BATCHALLOCATIONS.LIST>
                <GODOWNNAME>Main Location</GODOWNNAME>
                <BATCHNAME>Primary Batch</BATCHNAME>
                <INDENTNO />
                <ORDERNO />
                <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
                <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
                <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            </BATCHALLOCATIONS.LIST>
            <ACCOUNTINGALLOCATIONS.LIST>
                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                    <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                </OLDAUDITENTRYIDS.LIST>
                <LEDGERNAME>SALES LOCAL</LEDGERNAME>
                <GSTCLASS />
                <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
                <VATEXPAMOUNT>'.round($pr_amount,2).'</VATEXPAMOUNT>
            </ACCOUNTINGALLOCATIONS.LIST>
        </ALLINVENTORYENTRIES.LIST>';
      }

      } else {
        $xmlString .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
    <VOUCHER VCHTYPE="Sales" ACTION="Create" OBJVIEW="Invoice Voucher View">
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
        <GSTREGISTRATIONTYPE>'.$gstin_type.'</GSTREGISTRATIONTYPE>
        <CMPGSTREGISTRATIONTYPE>'.$gstin_type.'</CMPGSTREGISTRATIONTYPE>
        <STATENAME>'.$state.'</STATENAME>
        <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
        <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
        <PARTYNAME>'.$client.'</PARTYNAME>
        <CMPGSTIN>19AEKPB4862M1Z2</CMPGSTIN>
        <PARTYPINCODE>'.$pincode.'</PARTYPINCODE>
        <VOUCHERTYPENAME>Sales</VOUCHERTYPENAME>
        <VOUCHERNUMBER>'.$invoice.'</VOUCHERNUMBER>
        <PARTYLEDGERNAME>'.$client.'</PARTYLEDGERNAME>
        <BASICBASEPARTYNAME>'.$client.'</BASICBASEPARTYNAME>
        <CSTFORMISSUETYPE />
        <CSTFORMRECVTYPE />
        <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
        <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
        <PLACEOFSUPPLY>'.$state.'</PLACEOFSUPPLY>
        <CONSIGNEEGSTIN>'.$gstin.'</CONSIGNEEGSTIN>
        <BASICSHIPPEDBY>Han Delivery</BASICSHIPPEDBY>
        <BASICBUYERNAME>'.$client.'</BASICBUYERNAME>
        <BASICFINALDESTINATION>Kolkata</BASICFINALDESTINATION>
        <BASICDUEDATEOFPYMT>100% on Order</BASICDUEDATEOFPYMT>
        <VCHGSTCLASS />
        <CONSIGNEESTATENAME>'.$state.'</CONSIGNEESTATENAME>
        <ISINVOICE>No</ISINVOICE>
        <HASDISCOUNTS>Yes</HASDISCOUNTS>
        <INVOICEORDERLIST.LIST>
            <BASICORDERDATE>'.$invoice_date.'</BASICORDERDATE>
            <BASICPURCHASEORDERNO>XYZ123</BASICPURCHASEORDERNO>
        </INVOICEORDERLIST.LIST>
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
        <LEDGERENTRIES.LIST>
        <LEDGERNAME>'.$client.'</LEDGERNAME>
        <GSTCLASS/>
        <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
        <LEDGERFROMITEM>No</LEDGERFROMITEM>
        <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
        <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
        <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
        <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
        <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
        <AMOUNT>-'.$total.'.00</AMOUNT>
        <BILLALLOCATIONS.LIST>
            <NAME>'.$invoice.'</NAME>
            <BILLTYPE>New Ref</BILLTYPE>
            <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
            <AMOUNT>-'.$total.'.00</AMOUNT>
            <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
            <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
        </BILLALLOCATIONS.LIST>
        </LEDGERENTRIES.LIST>';

        if($addons['pf']['value'] != '0.00'){
            $pf = $addons['pf']['value'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>P&amp;F SALES</LEDGERNAME>
            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$pf.'</AMOUNT>
            <VATEXPAMOUNT>'.$pf.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        if($addons['freight']['value'] != '0.00'){
            $fr = $addons['freight']['value'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>FREIGHT SALES</LEDGERNAME>
            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$fr.'</AMOUNT>
            <VATEXPAMOUNT>'.$fr.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>OUTPUT IGST</LEDGERNAME>
            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <AMOUNT>'.$igst.'</AMOUNT>
            <VATEXPAMOUNT>'.$igst.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        if($round != '0.00'){
            $xmlString .= '<LEDGERENTRIES.LIST>
                <LEDGERNAME>ROUND OFF SALES (+/-)</LEDGERNAME>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <AMOUNT>'.$round.'</AMOUNT>
                <VATEXPAMOUNT>'.$round.'</VATEXPAMOUNT>
                </LEDGERENTRIES.LIST>';
        }

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
          $discount = $item_details['discount'][$i];

          if($discount == '')
            $discount = 0;
          $pr_amount = $pr_val * ((100-$discount)/100);

          $xmlString .= '<ALLINVENTORYENTRIES.LIST>
            <BASICUSERDESCRIPTION.LIST TYPE="String">
                <BASICUSERDESCRIPTION>'.$pr_desc.'</BASICUSERDESCRIPTION>
            </BASICUSERDESCRIPTION.LIST>
            <STOCKITEMNAME>'.$pr_name.'</STOCKITEMNAME>
            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
            <ISAUTONEGATE>No</ISAUTONEGATE>
            <ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
            <ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
            <ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
            <ISPRIMARYITEM>No</ISPRIMARYITEM>
            <ISSCRAP>No</ISSCRAP>
            <RATE>'.$pr_rate.'</RATE>
            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
            <ISAUTONEGATE>No</ISAUTONEGATE>
            <ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
            <ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
            <ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
            <ISPRIMARYITEM>No</ISPRIMARYITEM>
            <ISSCRAP>No</ISSCRAP>
            <DISCOUNT> '.$pr_discount.'</DISCOUNT>
            <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
            <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
            <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            <BATCHALLOCATIONS.LIST>
                <GODOWNNAME>Main Location</GODOWNNAME>
                <BATCHNAME>Primary Batch</BATCHNAME>
                <INDENTNO />
                <ORDERNO />
                <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
                <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
                <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            </BATCHALLOCATIONS.LIST>
            <ACCOUNTINGALLOCATIONS.LIST>
                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                    <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                </OLDAUDITENTRYIDS.LIST>
                <LEDGERNAME>SALES INTERSTATE</LEDGERNAME>
                <GSTCLASS />
                <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
                <VATEXPAMOUNT>'.round($pr_amount,2).'</VATEXPAMOUNT>
            </ACCOUNTINGALLOCATIONS.LIST>
        </ALLINVENTORYENTRIES.LIST>';
      }

      }

        

    $xmlString .= '</VOUCHER>
</TALLYMESSAGE>';
// echo $invoice.'<br/>';

}


  $xmlString .= '</REQUESTDATA>
  </IMPORTDATA>
 </BODY>
</ENVELOPE>';

$dom = new DOMDocument;
$dom->preserveWhiteSpace = TRUE;
$dom->loadXML($xmlString);

//Save XML as a file
$dom->save('sales.xml');

//View XML document
$dom->formatOutput = TRUE;
echo $dom->saveXml();

?>