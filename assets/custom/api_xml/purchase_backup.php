<?php
session_start();
require_once "../connect.php";

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
 
$sql = "SELECT * FROM purchase_invoice WHERE pi_no != '916'";
$query = $db->query($sql);

while($row = $query->fetch_assoc()){

  $invoice = $row['pi_no'];
  $supplier = $row['supplier_name'];
  $supplier = htmlspecialchars($supplier, ENT_XML1, 'UTF-8');
  $state = $row['state'];
  $invoice_date = date('Ymd', strtotime($row['pi_date']));

  $sql_pull = "SELECT * FROM suppliers WHERE name = '$supplier'";
  $query_pull = $db->query($sql_pull);
  $row_pull = $query_pull->fetch_assoc();

  $gstin = $row_pull['gstin'];
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

    $total=0;
    $tax=0;
    for($i=0;$i<$l;$i++){
        $val = $item_details['quantity'][$i]*$item_details['price'][$i];
        if($item_details['discount'][$i] != ''){
            $val = $val * (100-$item_details['discount'][$i])/100;
        }

        if($item_details['tax'][$i] != ''){
            $tax_val = $val * ($item_details['tax'][$i]/100);
            $val = $val * ((100+$item_details['tax'][$i])/100);
        }

        $total += round($val, 2); 
        $tax += round($tax_val, 2); 
    }

    $addons = json_decode($row['addons'], true);

    if($addons['freight'] != '0.00'){
        $total += $addons['freight']*1.18;
        $fr_tax = $addons['freight']*0.18;
        $tax += round($fr_tax, 2); 
    }
    if($addons['pf'] != '0.00'){
        $total += $addons['pf']*1.18;
        $pf_tax = $addons['pf']*0.18;
        $tax += round($pf_tax, 2); 
    }
    // if($addons['discount'] != ''){
    //     $total -= $addons['discount'];
    // }
    if($addons['roundoff'] != ''){
        $total += $addons['roundoff'];
    }

    $total = round($total);
    $tax = round($tax,2);
    $tax_2 = $tax / 2;
    $tax_2 = round($tax_2,2);
    $round = $addons['roundoff'];

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
        <DATE>20200401</DATE>
        <STATENAME>'.$state.'</STATENAME>
        <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
        <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
        <PARTYNAME>'.$supplier.'</PARTYNAME>
        <VOUCHERTYPENAME>Purchase</VOUCHERTYPENAME>
        <VOUCHERNUMBER>'.$invoice.'</VOUCHERNUMBER>
        <PARTYLEDGERNAME>'.$supplier.'</PARTYLEDGERNAME>
        <BASICBASEPARTYNAME>'.$supplier.'</BASICBASEPARTYNAME>
        <CSTFORMISSUETYPE />
        <CSTFORMRECVTYPE />
        <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
        <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
        <PLACEOFSUPPLY>'.$state.'</PLACEOFSUPPLY>
        <CONSIGNEEGSTIN>'.$gstin.'</CONSIGNEEGSTIN>
        <BASICSHIPPEDBY>Han Delivery</BASICSHIPPEDBY>
        <BASICBUYERNAME>'.$supplier.'</BASICBUYERNAME>
        <BASICFINALDESTINATION>Kolkata</BASICFINALDESTINATION>
        <BASICDUEDATEOFPYMT>100% on Order</BASICDUEDATEOFPYMT>
        <VCHGSTCLASS />
        <CONSIGNEESTATENAME>'.$state.'</CONSIGNEESTATENAME>
        <EFFECTIVEDATE>20200401</EFFECTIVEDATE>
        <ISINVOICE>Yes</ISINVOICE>
        <HASDISCOUNTS>Yes</HASDISCOUNTS>
        <INVOICEORDERLIST.LIST>
            <BASICORDERDATE>20200401</BASICORDERDATE>
            <BASICPURCHASEORDERNO>XYZ123</BASICPURCHASEORDERNO>
        </INVOICEORDERLIST.LIST>
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
        <LEDGERENTRIES.LIST>
        <LEDGERNAME>'.$supplier.'</LEDGERNAME>
        <AMOUNT>-'.$total.'.00</AMOUNT>
        </LEDGERENTRIES.LIST>';

        if($addons['pf'] != '0.00'){
            $pf = $addons['pf'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>FREIGHT-INWARD-INTERSTATE</LEDGERNAME>
            <AMOUNT>'.$pf.'</AMOUNT>
            <VATEXPAMOUNT>'.$pf.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        if($addons['freight'] != '0.00'){
            $fr = $addons['freight'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>PACKING AND FORWARDING-INWARD-INTERSTATE</LEDGERNAME>
            <AMOUNT>'.$fr.'</AMOUNT>
            <VATEXPAMOUNT>'.$fr.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>OUTPUT CGST</LEDGERNAME>
            <AMOUNT>'.$tax_2.'</AMOUNT>
            <VATEXPAMOUNT>'.$tax_2.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>OUTPUT SGST</LEDGERNAME>
            <AMOUNT>'.$tax_2.'</AMOUNT>
            <VATEXPAMOUNT>'.$tax_2.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>Rounded Off (+/-)</LEDGERNAME>
            <AMOUNT>'.$round.'</AMOUNT>
            <VATEXPAMOUNT>'.$round.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';



        for($i=0;$i<$l;$i++){

          $pr_name = $item_details['product'][$i];
          $pr_desc = $item_details['desc'][$i];
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
            <DISCOUNT> '.$pr_discount.'</DISCOUNT>
            <AMOUNT>'.$pr_amount.'</AMOUNT>
            <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
            <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            <BATCHALLOCATIONS.LIST>
                <GODOWNNAME>Main Location</GODOWNNAME>
                <BATCHNAME>Primary Batch</BATCHNAME>
                <INDENTNO />
                <ORDERNO />
                <AMOUNT>'.$pr_amount.'</AMOUNT>
                <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
                <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            </BATCHALLOCATIONS.LIST>
            <ACCOUNTINGALLOCATIONS.LIST>
                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                    <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                </OLDAUDITENTRYIDS.LIST>
                <LEDGERNAME>GST LOCAL  PURCHASES@18%</LEDGERNAME>
                <GSTCLASS />
                <AMOUNT>'.$pr_amount.'</AMOUNT>
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
        <DATE>20200401</DATE>
        <STATENAME>'.$state.'</STATENAME>
        <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
        <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
        <PARTYNAME>'.$supplier.'</PARTYNAME>
        <VOUCHERTYPENAME>Purchase</VOUCHERTYPENAME>
        <VOUCHERNUMBER>'.$invoice.'</VOUCHERNUMBER>
        <PARTYLEDGERNAME>'.$supplier.'</PARTYLEDGERNAME>
        <BASICBASEPARTYNAME>'.$supplier.'</BASICBASEPARTYNAME>
        <CSTFORMISSUETYPE />
        <CSTFORMRECVTYPE />
        <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
        <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
        <PLACEOFSUPPLY>'.$state.'</PLACEOFSUPPLY>
        <CONSIGNEEGSTIN>'.$gstin.'</CONSIGNEEGSTIN>
        <BASICSHIPPEDBY>Han Delivery</BASICSHIPPEDBY>
        <BASICBUYERNAME>'.$supplier.'</BASICBUYERNAME>
        <BASICFINALDESTINATION>Kolkata</BASICFINALDESTINATION>
        <BASICDUEDATEOFPYMT>100% on Order</BASICDUEDATEOFPYMT>
        <VCHGSTCLASS />
        <CONSIGNEESTATENAME>'.$state.'</CONSIGNEESTATENAME>
        <EFFECTIVEDATE>20200401</EFFECTIVEDATE>
        <ISINVOICE>Yes</ISINVOICE>
        <HASDISCOUNTS>Yes</HASDISCOUNTS>
        <INVOICEORDERLIST.LIST>
            <BASICORDERDATE>20200401</BASICORDERDATE>
            <BASICPURCHASEORDERNO>XYZ123</BASICPURCHASEORDERNO>
        </INVOICEORDERLIST.LIST>
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
        <LEDGERENTRIES.LIST>
        <LEDGERNAME>'.$supplier.'</LEDGERNAME>
        <AMOUNT>-'.$total.'.00</AMOUNT>
        </LEDGERENTRIES.LIST>';

        if($addons['pf'] != ''){
            $pf = $addons['pf'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>FREIGHT-INWARD-INTERSTATE</LEDGERNAME>
            <AMOUNT>'.$pf.'</AMOUNT>
            <VATEXPAMOUNT>'.$pf.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        if($addons['freight'] != ''){
            $fr = $addons['freight'];
            $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>PACKING AND FORWARDING-INWARD-INTERSTATE</LEDGERNAME>
            <AMOUNT>'.$fr.'</AMOUNT>
            <VATEXPAMOUNT>'.$fr.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';
        }

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>OUTPUT IGST</LEDGERNAME>
            <AMOUNT>'.$tax.'</AMOUNT>
            <VATEXPAMOUNT>'.$tax.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        $xmlString .= '<LEDGERENTRIES.LIST>
            <LEDGERNAME>Rounded Off (+/-)</LEDGERNAME>
            <AMOUNT>'.$round.'</AMOUNT>
            <VATEXPAMOUNT>'.$round.'</VATEXPAMOUNT>
            </LEDGERENTRIES.LIST>';

        for($i=0;$i<$l;$i++){

          $pr_name = $item_details['product'][$i];
          $pr_desc = $item_details['desc'][$i];
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
            <DISCOUNT> '.$pr_discount.'</DISCOUNT>
            <AMOUNT>'.$pr_amount.'</AMOUNT>
            <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
            <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            <BATCHALLOCATIONS.LIST>
                <GODOWNNAME>Main Location</GODOWNNAME>
                <BATCHNAME>Primary Batch</BATCHNAME>
                <INDENTNO />
                <ORDERNO />
                <AMOUNT>'.$pr_amount.'</AMOUNT>
                <ACTUALQTY> '.$pr_quantity.'</ACTUALQTY>
                <BILLEDQTY> '.$pr_quantity.'</BILLEDQTY>
            </BATCHALLOCATIONS.LIST>
            <ACCOUNTINGALLOCATIONS.LIST>
                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                    <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                </OLDAUDITENTRYIDS.LIST>
                <LEDGERNAME>GST INTERSTATE PURCHASES@5%</LEDGERNAME>
                <GSTCLASS />
                <AMOUNT>'.$pr_amount.'</AMOUNT>
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
$dom->formatOutput = TRUE;
echo $dom->saveXml();

?>