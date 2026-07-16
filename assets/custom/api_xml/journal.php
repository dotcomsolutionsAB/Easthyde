<?php

// ini_set('display_errors', '1');
session_start();
require_once "../connect.php";

$ids = '('.$_REQUEST['ids'].')';

$dt_start = $_SESSION['start'];
$dt_end = $_SESSION['end'];

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

$sql = "SELECT * FROM journal WHERE `date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `id` ";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

	$date = date('Ymd', strtotime($row['date']));
	$id = $row['id'];

	$items = json_decode($row['items'],true);
	$len = sizeof($items['master']);


	$xmlString .='<TALLYMESSAGE xmlns:UDF="TallyUDF">
                    <VOUCHER REMOTEID="fb0a08e7-3fca-4e96-8926-f090dfb9c8ae-000034c9" VCHKEY="fb0a08e7-3fca-4e96-8926-f090dfb9c8ae-0000ad98:00000008" VCHTYPE="Journal" ACTION="Create" OBJVIEW="Accounting Voucher View">
                        <OLDAUDITENTRYIDS.LIST TYPE="Number">
                            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                        </OLDAUDITENTRYIDS.LIST>
                        <DATE>'.$date.'</DATE>
                        <GUID>fb0a08e7-3fca-4e96-8926-f090dfb9c8ae-000034c9</GUID>
                        <VOUCHERTYPENAME>Journal</VOUCHERTYPENAME>
                        <VOUCHERNUMBER>'.$id.'</VOUCHERNUMBER>
                        <CSTFORMISSUETYPE />
                        <CSTFORMRECVTYPE />
                        <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                        <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                        <VCHGSTCLASS />
                        <DIFFACTUALQTY>No</DIFFACTUALQTY>
                        <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
                        <ISDELETED>No</ISDELETED>
                        <ISSECURITYONWHENENTERED>No</ISSECURITYONWHENENTERED>
                        <ASORIGINAL>No</ASORIGINAL>
                        <AUDITED>No</AUDITED>
                        <FORJOBCOSTING>No</FORJOBCOSTING>
                        <ISOPTIONAL>No</ISOPTIONAL>
                        <EFFECTIVEDATE>20210902</EFFECTIVEDATE>
                        <USEFOREXCISE>No</USEFOREXCISE>
                        <ISFORJOBWORKIN>No</ISFORJOBWORKIN>
                        <ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>
                        <USEFORINTEREST>No</USEFORINTEREST>
                        <USEFORGAINLOSS>No</USEFORGAINLOSS>
                        <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
                        <USEFORCOMPOUND>No</USEFORCOMPOUND>
                        <USEFORSERVICETAX>No</USEFORSERVICETAX>
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
                        <ISINVOICE>No</ISINVOICE>
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
                        <ALTERID> 27507</ALTERID>
                        <MASTERID> 13513</MASTERID>
                        <VOUCHERKEY>190868346634248</VOUCHERKEY>
                        <EWAYBILLDETAILS.LIST> </EWAYBILLDETAILS.LIST>
                        <EXCLUDEDTAXATIONS.LIST> </EXCLUDEDTAXATIONS.LIST>
                        <OLDAUDITENTRIES.LIST> </OLDAUDITENTRIES.LIST>
                        <ACCOUNTAUDITENTRIES.LIST> </ACCOUNTAUDITENTRIES.LIST>
                        <AUDITENTRIES.LIST> </AUDITENTRIES.LIST>
                        <DUTYHEADDETAILS.LIST> </DUTYHEADDETAILS.LIST>
                        <SUPPLEMENTARYDUTYHEADDETAILS.LIST> </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
                        <EWAYBILLERRORLIST.LIST> </EWAYBILLERRORLIST.LIST>
                        <IRNERRORLIST.LIST> </IRNERRORLIST.LIST>
                        <INVOICEDELNOTES.LIST> </INVOICEDELNOTES.LIST>
                        <INVOICEORDERLIST.LIST> </INVOICEORDERLIST.LIST>
                        <INVOICEINDENTLIST.LIST> </INVOICEINDENTLIST.LIST>
                        <ATTENDANCEENTRIES.LIST> </ATTENDANCEENTRIES.LIST>
                        <ORIGINVOICEDETAILS.LIST> </ORIGINVOICEDETAILS.LIST>
                        <INVOICEEXPORTLIST.LIST> </INVOICEEXPORTLIST.LIST>';

    for($i=0;$i<$len;$i++)
    {
    	$master 	= $items['master'][$i];
    	$particular = $items['particular'][$i];
    	$debit 		= $items['debit'][$i];
    	$credit 	= $items['credit'][$i];

    	if($debit != '' && $debit != '0')
    	{
    		$xmlString .= '<ALLLEDGERENTRIES.LIST>
                            <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                            </OLDAUDITENTRYIDS.LIST>
                            <LEDGERNAME>'.$master.'</LEDGERNAME>
                            <GSTCLASS />
                            <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                            <LEDGERFROMITEM>No</LEDGERFROMITEM>
                            <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                            <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                            <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
                            <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                            <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                            <AMOUNT>-'.$debit.'</AMOUNT>
                            <VATEXPAMOUNT>-'.$debit.'</VATEXPAMOUNT>
                            <SERVICETAXDETAILS.LIST> </SERVICETAXDETAILS.LIST>
                            <BANKALLOCATIONS.LIST> </BANKALLOCATIONS.LIST>
                            <BILLALLOCATIONS.LIST>
                                <NAME>'.$particular.'</NAME>
                                <BILLTYPE>Agst Ref</BILLTYPE>
                                <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                                <AMOUNT>-'.$debit.'</AMOUNT>
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
                        </ALLLEDGERENTRIES.LIST>';
    	}
    	else if($credit != '' && $credit != '0')
    	{
    		$xmlString .= '<ALLLEDGERENTRIES.LIST>
                            <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                            </OLDAUDITENTRYIDS.LIST>
                            <LEDGERNAME>'.$master.'</LEDGERNAME>
                            <GSTCLASS />
                            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                            <LEDGERFROMITEM>No</LEDGERFROMITEM>
                            <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                            <ISPARTYLEDGER>No</ISPARTYLEDGER>
                            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                            <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                            <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                            <AMOUNT>'.$credit.'</AMOUNT>
                            <VATEXPAMOUNT>'.$credit.'</VATEXPAMOUNT>
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
                        </ALLLEDGERENTRIES.LIST>';
    	}


    }

$xmlString .='<PAYROLLMODEOFPAYMENT.LIST> </PAYROLLMODEOFPAYMENT.LIST>
                        <ATTDRECORDS.LIST> </ATTDRECORDS.LIST>
                        <GSTEWAYCONSIGNORADDRESS.LIST> </GSTEWAYCONSIGNORADDRESS.LIST>
                        <GSTEWAYCONSIGNEEADDRESS.LIST> </GSTEWAYCONSIGNEEADDRESS.LIST>
                        <TEMPGSTRATEDETAILS.LIST> </TEMPGSTRATEDETAILS.LIST>
                    </VOUCHER>
                </TALLYMESSAGE>';

}

$xmlString .='</REQUESTDATA>
        </IMPORTDATA>
    </BODY>
</ENVELOPE>';