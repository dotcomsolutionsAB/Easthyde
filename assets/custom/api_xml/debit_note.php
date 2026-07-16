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

if($_REQUEST['ids'] == 'all'){
    $sql = "SELECT * FROM debit_note WHERE dn_date BETWEEN '$dt_start' AND '$dt_end' ORDER BY dn_no ";
}else{
    $sql = "SELECT * FROM debit_note WHERE id IN $ids";
}
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

  $invoice = $row['dn_no'];
  $suppier = $row['suppier'];
  $state = htmlspecialchars($row['state'], ENT_XML1, 'UTF-8');
  $invoice_date = date('Ymd', strtotime($row['dn_date']));

  // Testing
  // $invoice_date = '20200401';

  $sql_pull = "SELECT * FROM suppliers WHERE name = '$supplier'";
  $query_pull = $db->query($sql_pull);
  $row_pull = $query_pull->fetch_assoc();

  $supplier = htmlspecialchars($supplier, ENT_XML1, 'UTF-8');

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
    $round  = $addons['roundoff'];

    if($state == 'WEST BENGAL'){
       $xmlString .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
                    <VOUCHER VCHTYPE="Debit Note" ACTION="Create" OBJVIEW="Invoice Voucher View">
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
                        <OLDAUDITENTRYIDS.LIST TYPE="Number">
                            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                        </OLDAUDITENTRYIDS.LIST>
                        <DATE>'.$invoice_date.'</DATE>
                        <GUID>9f45de7d-5fe4-40f4-940b-d0f50f85f75b-00000004</GUID>
                        <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
                        <VATDEALERTYPE />
                        <STATENAME>'.$state.'</STATENAME>
                        <COUNTRYOFRESIDENCE>'.$country.'</COUNTRYOFRESIDENCE>
                        <PARTYGSTIN>'.$gstin.'</PARTYGSTIN>
                        <PLACEOFSUPPLY>'.$state.'</PLACEOFSUPPLY>
                        <PARTYNAME>'.$supplier.'</PARTYNAME>
                        <PARTYLEDGERNAME>'.$supplier.'</PARTYLEDGERNAME>
                        <GSTNATUREOFRETURN>01-Sales Return</GSTNATUREOFRETURN>
                        <VOUCHERTYPENAME>Debit Note</VOUCHERTYPENAME>
                        <PARTYMAILINGNAME>'.$supplier.'</PARTYMAILINGNAME>
                        <PARTYPINCODE>'.$pincode.'</PARTYPINCODE>
                        <CONSIGNEEGSTIN>'.$gstin.'</CONSIGNEEGSTIN>
                        <CONSIGNEEMAILINGNAME>'.$supplier.'</CONSIGNEEMAILINGNAME>
                        <CONSIGNEEPINCODE>'.$pincode.'</CONSIGNEEPINCODE>
                        <CONSIGNEESTATENAME>'.$state.'</CONSIGNEESTATENAME>
                        <VOUCHERNUMBER>'.$invoice.'</VOUCHERNUMBER>
                        <BASICBASEPARTYNAME>'.$supplier.'</BASICBASEPARTYNAME>
                        <CSTFORMISSUETYPE />
                        <CSTFORMRECVTYPE />
                        <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                        <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
                        <BASICBUYERNAME>'.$supplier.'</BASICBUYERNAME>
                        <CONSIGNEECOUNTRYNAME>INDIA</CONSIGNEECOUNTRYNAME>
                        <VCHGSTCLASS />
                        <VCHENTRYMODE>Item Invoice</VCHENTRYMODE>
                        <DIFFACTUALQTY>No</DIFFACTUALQTY>
                        <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
                        <ASORIGINAL>No</ASORIGINAL>
                        <AUDITED>No</AUDITED>
                        <FORJOBCOSTING>No</FORJOBCOSTING>
                        <ISOPTIONAL>No</ISOPTIONAL>
                        <EFFECTIVEDATE>'.$invoice_date.'</EFFECTIVEDATE>
                        <USEFOREXCISE>No</USEFOREXCISE>
                        <ISFORJOBWORKIN>No</ISFORJOBWORKIN>
                        <ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>
                        <USEFORINTEREST>No</USEFORINTEREST>
                        <USEFORGAINLOSS>No</USEFORGAINLOSS>
                        <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
                        <USEFORCOMPOUND>No</USEFORCOMPOUND>
                        <USEFORSERVICETAX>No</USEFORSERVICETAX>
                        <ISDELETED>No</ISDELETED>
                        <ISONHOLD>No</ISONHOLD>
                        <ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>
                        <ISGSTSECSEVENAPPLICABLE>No</ISGSTSECSEVENAPPLICABLE>
                        <ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>
                        <EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>
                        <USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>
                        <IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>
                        <EXCISEOPENING>No</EXCISEOPENING>
                        <USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>
                        <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
                        <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
                        <ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>
                        <INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>
                        <ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>
                        <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
                        <IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>
                        <ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>
                        <ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>
                        <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
                        <ISISDVOUCHER>No</ISISDVOUCHER>
                        <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
                        <ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>
                        <ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>
                        <GSTNOTEXPORTED>No</GSTNOTEXPORTED>
                        <IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>
                        <ISGSTREFUND>No</ISGSTREFUND>
                        <OVRDNEWAYBILLAPPLICABILITY>No</OVRDNEWAYBILLAPPLICABILITY>
                        <ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>
                        <IGNOREEINVVALIDATION>No</IGNOREEINVVALIDATION>
                        <IRNJSONEXPORTED>No</IRNJSONEXPORTED>
                        <IRNCANCELLED>No</IRNCANCELLED>
                        <ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>
                        <ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>
                        <ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>
                        <ISCANCELLED>No</ISCANCELLED>
                        <HASCASHFLOW>No</HASCASHFLOW>
                        <ISPOSTDATED>No</ISPOSTDATED>
                        <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
                        <ISINVOICE>Yes</ISINVOICE>
                        <MFGJOURNAL>No</MFGJOURNAL>
                        <HASDISCOUNTS>No</HASDISCOUNTS>
                        <ASPAYSLIP>No</ASPAYSLIP>
                        <ISCOSTCENTRE>No</ISCOSTCENTRE>
                        <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
                        <ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>
                        <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
                        <ISVOID>No</ISVOID>
                        <ORDERLINESTATUS>No</ORDERLINESTATUS>
                        <VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>
                        <VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>
                        <ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>
                        <VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>
                        <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                        <ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>
                        <ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>
                        <ISDELETEDVCHRETAINED>No</ISDELETEDVCHRETAINED>
                        <CHANGEVCHMODE>No</CHANGEVCHMODE>
                        <RESETIRNQRCODE>No</RESETIRNQRCODE>
                        <ALTERID> 5</ALTERID>
                        <MASTERID> 4</MASTERID>
                        <VOUCHERKEY>189030100631576</VOUCHERKEY>
                        <EWAYBILLDETAILS.LIST> </EWAYBILLDETAILS.LIST>
                        <EXCLUDEDTAXATIONS.LIST> </EXCLUDEDTAXATIONS.LIST>
                        <OLDAUDITENTRIES.LIST> </OLDAUDITENTRIES.LIST>
                        <ACCOUNTAUDITENTRIES.LIST> </ACCOUNTAUDITENTRIES.LIST>
                        <AUDITENTRIES.LIST> </AUDITENTRIES.LIST>
                        <DUTYHEADDETAILS.LIST> </DUTYHEADDETAILS.LIST>';

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
                            <STOCKITEMNAME>'.$pr_name.'</STOCKITEMNAME>
                            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                            <ISAUTONEGATE>No</ISAUTONEGATE>
                            <ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
                            <ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
                            <ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
                            <ISPRIMARYITEM>No</ISPRIMARYITEM>
                            <ISSCRAP>No</ISSCRAP>
                            <RATE>'.$pr_rate.'</RATE>
                            <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
                            <ACTUALQTY>'.$pr_quantity.'</ACTUALQTY>
                            <BILLEDQTY>'.$pr_quantity.'</BILLEDQTY>
                            <BATCHALLOCATIONS.LIST>
                                <GODOWNNAME>Main Location</GODOWNNAME>
                                <BATCHNAME>Primary Batch</BATCHNAME>
                                <INDENTNO />
                                <ORDERNO />
                                <TRACKINGNUMBER />
                                <DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED>
                                <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
                                <ACTUALQTY>'.$pr_quantity.'</ACTUALQTY>
                                <BILLEDQTY>'.$pr_quantity.'</BILLEDQTY>
                                <ADDITIONALDETAILS.LIST> </ADDITIONALDETAILS.LIST>
                                <VOUCHERCOMPONENTLIST.LIST> </VOUCHERCOMPONENTLIST.LIST>
                            </BATCHALLOCATIONS.LIST>
                            <ACCOUNTINGALLOCATIONS.LIST>
                                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                    <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                </OLDAUDITENTRYIDS.LIST>
                                <LEDGERNAME>PURCHASES INTERSTATE</LEDGERNAME>
                                <GSTCLASS />
                                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                <ISPARTYLEDGER>No</ISPARTYLEDGER>
                                <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                                <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                                <AMOUNT>'.round($pr_amount,2).'</AMOUNT>
                                <SERVICETAXDETAILS.LIST> </SERVICETAXDETAILS.LIST>
                                <BANKALLOCATIONS.LIST> </BANKALLOCATIONS.LIST>
                                <BILLALLOCATIONS.LIST> </BILLALLOCATIONS.LIST>
                                <INTERESTCOLLECTION.LIST> </INTERESTCOLLECTION.LIST>
                                <OLDAUDITENTRIES.LIST> </OLDAUDITENTRIES.LIST>
                                <ACCOUNTAUDITENTRIES.LIST> </ACCOUNTAUDITENTRIES.LIST>
                                <AUDITENTRIES.LIST> </AUDITENTRIES.LIST>
                                <INPUTCRALLOCS.LIST> </INPUTCRALLOCS.LIST>
                                <DUTYHEADDETAILS.LIST> </DUTYHEADDETAILS.LIST>
                                <EXCISEDUTYHEADDETAILS.LIST> </EXCISEDUTYHEADDETAILS.LIST>
                                <RATEDETAILS.LIST> </RATEDETAILS.LIST>
                                <SUMMARYALLOCS.LIST> </SUMMARYALLOCS.LIST>
                                <STPYMTDETAILS.LIST> </STPYMTDETAILS.LIST>
                                <EXCISEPAYMENTALLOCATIONS.LIST> </EXCISEPAYMENTALLOCATIONS.LIST>
                                <TAXBILLALLOCATIONS.LIST> </TAXBILLALLOCATIONS.LIST>
                                <TAXOBJECTALLOCATIONS.LIST> </TAXOBJECTALLOCATIONS.LIST>
                                <TDSEXPENSEALLOCATIONS.LIST> </TDSEXPENSEALLOCATIONS.LIST>
                                <VATSTATUTORYDETAILS.LIST> </VATSTATUTORYDETAILS.LIST>
                                <COSTTRACKALLOCATIONS.LIST> </COSTTRACKALLOCATIONS.LIST>
                                <REFVOUCHERDETAILS.LIST> </REFVOUCHERDETAILS.LIST>
                                <INVOICEWISEDETAILS.LIST> </INVOICEWISEDETAILS.LIST>
                                <VATITCDETAILS.LIST> </VATITCDETAILS.LIST>
                                <ADVANCETAXDETAILS.LIST> </ADVANCETAXDETAILS.LIST>
                            </ACCOUNTINGALLOCATIONS.LIST>
                            <DUTYHEADDETAILS.LIST> </DUTYHEADDETAILS.LIST>
                            <SUPPLEMENTARYDUTYHEADDETAILS.LIST> </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
                            <TAXOBJECTALLOCATIONS.LIST> </TAXOBJECTALLOCATIONS.LIST>
                            <REFVOUCHERDETAILS.LIST> </REFVOUCHERDETAILS.LIST>
                            <EXCISEALLOCATIONS.LIST> </EXCISEALLOCATIONS.LIST>
                            <EXPENSEALLOCATIONS.LIST> </EXPENSEALLOCATIONS.LIST>
                        </ALLINVENTORYENTRIES.LIST>';
          }

          } 

            

        $xmlString .= '
        	<LEDGERENTRIES.LIST>
                <OLDAUDITENTRYIDS.LIST TYPE="Number">
                    <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                </OLDAUDITENTRYIDS.LIST>
                <LEDGERNAME>PRAGATI HITECH ENGINEERS</LEDGERNAME>
                <GSTCLASS />
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
                <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                <AMOUNT>-708.00</AMOUNT>
                <SERVICETAXDETAILS.LIST> </SERVICETAXDETAILS.LIST>
                <BANKALLOCATIONS.LIST> </BANKALLOCATIONS.LIST>
                <BILLALLOCATIONS.LIST>
                    <NAME>1</NAME>
                    <BILLTYPE>New Ref</BILLTYPE>
                    <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                    <AMOUNT>-708.00</AMOUNT>
                    <INTERESTCOLLECTION.LIST> </INTERESTCOLLECTION.LIST>
                    <STBILLCATEGORIES.LIST> </STBILLCATEGORIES.LIST>
                </BILLALLOCATIONS.LIST>
                <INTERESTCOLLECTION.LIST> </INTERESTCOLLECTION.LIST>
                <OLDAUDITENTRIES.LIST> </OLDAUDITENTRIES.LIST>
                <ACCOUNTAUDITENTRIES.LIST> </ACCOUNTAUDITENTRIES.LIST>
                <AUDITENTRIES.LIST> </AUDITENTRIES.LIST>
                <INPUTCRALLOCS.LIST> </INPUTCRALLOCS.LIST>
                <DUTYHEADDETAILS.LIST> </DUTYHEADDETAILS.LIST>
                <EXCISEDUTYHEADDETAILS.LIST> </EXCISEDUTYHEADDETAILS.LIST>
                <RATEDETAILS.LIST> </RATEDETAILS.LIST>
                <SUMMARYALLOCS.LIST> </SUMMARYALLOCS.LIST>
                <STPYMTDETAILS.LIST> </STPYMTDETAILS.LIST>
                <EXCISEPAYMENTALLOCATIONS.LIST> </EXCISEPAYMENTALLOCATIONS.LIST>
                <TAXBILLALLOCATIONS.LIST> </TAXBILLALLOCATIONS.LIST>
                <TAXOBJECTALLOCATIONS.LIST> </TAXOBJECTALLOCATIONS.LIST>
                <TDSEXPENSEALLOCATIONS.LIST> </TDSEXPENSEALLOCATIONS.LIST>
                <VATSTATUTORYDETAILS.LIST> </VATSTATUTORYDETAILS.LIST>
                <COSTTRACKALLOCATIONS.LIST> </COSTTRACKALLOCATIONS.LIST>
                <REFVOUCHERDETAILS.LIST> </REFVOUCHERDETAILS.LIST>
                <INVOICEWISEDETAILS.LIST> </INVOICEWISEDETAILS.LIST>
                <VATITCDETAILS.LIST> </VATITCDETAILS.LIST>
                <ADVANCETAXDETAILS.LIST> </ADVANCETAXDETAILS.LIST>
            </LEDGERENTRIES.LIST>
        </VOUCHER>
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