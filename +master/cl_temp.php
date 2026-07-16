<?php
// ini_set("display_errors",1);
session_start();
require_once "../assets/custom/connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start = $_SESSION['start'];
$end = $_SESSION['end'];

$start_year = date('Y', strtotime($start));
$end_year = date('Y', strtotime($end));

$year = $start_year.'-'.substr($end, 2,2);

$id = $_REQUEST['id'];
$result=array('particulars'=>array(),'date'=>array(),'voucher'=>array(),'credit'=>array(),'debit'=>array());

$sql_fetch = "SELECT * FROM clients WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$client = $row_fetch['name'];
$client_id = $row_fetch['id'];


$new_opening_balance = json_decode($row_fetch['new_opening_balance'],true);
$len = sizeof($new_opening_balance['year']);

for($i=0;$i<$len;$i++)
{
    if($new_opening_balance['year'][$i] == $year)
    {
        $opening = $new_opening_balance['balance'][$i];
    }
}
// $opening_stock = $row_pr['opening_stock'];

// echo $opening;
// $opening = $row_fetch['opening_balance'];
$contacts = json_decode($row_fetch['contacts'], true);
$email=$contacts['email'][0];
$mobile=$contacts['mobile'][0];

$total=0;
$debit=0;
$credit=0;
$d_debit=0;
$c_credit=0;

if($opening != 0 && $opening < 0){
    $opening = abs($opening);
	$result['particulars'][] = 'Opening Balance';
    $result['date'][] = $start;
    $result['voucher'][] = '';
    $result['credit'][] = $opening;
    $result['debit'][] = '';
}
else if($opening != 0 && $opening > 0)
{
    
    $result['particulars'][] = 'Opening Balance';
    $result['date'][] = $start;
    $result['voucher'][] = '';
    $result['credit'][] = '';
    $result['debit'][] = $opening;
}

$sql = "SELECT * FROM sales_invoice WHERE `client_name`='$client' AND `si_date` BETWEEN '$start' AND '$end' AND `series` = 'PRIMARY' ORDER BY `si_date` ASC";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){
	$count++;

	$tax_details = json_decode($row['tax'], true);

    $total = $row['total'];
    $tax = $tax_details['cgst'] + $tax_details['sgst'] + $tax_details['igst'];

    $result['particulars'][] = '<a target="_blank" href="/assets/custom/sales_print.php?id='.$row['si_no'].'&type=print">'.$row['si_no'].'</a>';
    $result['date'][] = $row['si_date'];
    $result['voucher'][] = 'Sales';
    $result['credit'][] = '';
    $result['debit'][] = $total;

}

$sql = "SELECT * FROM credit_note WHERE `client`='$client' AND `cn_date` BETWEEN '$start' AND '$end' ORDER BY `cn_date` ASC";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){
    $count++;

    $tax_details = json_decode($row['tax'], true);

    $total = $row['total'];
    $tax = $tax_details['cgst'] + $tax_details['sgst'] + $tax_details['igst'];

    $result['particulars'][] = '<a target="_blank" href="/assets/custom/credit_note_print.php?id='.$row['cn_no'].'&type=print">'.$row['cn_no'].'</a>';
    $result['date'][] = $row['cn_date'];
    $result['voucher'][] = 'Credit Note';
    $result['credit'][] = $total;
    $result['debit'][] = '';

}

$sql = "SELECT * FROM receipts WHERE `client`='$client' AND `date` BETWEEN '$start' AND '$end' ORDER BY `date` ASC";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){
    $result['particulars'][]    = 'Payment - '.$row['mode'].' ('.$row['instrument'].')';
    $result['date'][] 			= $row['date'];
    $result['voucher'][]        = 'Receipt';
    $result['credit'][] 		= $row['amount'];
    $result['debit'][] 			= '0';

}

// echo $sql;

$len = sizeof($result['date']);

for($m=0;$m<$len-1;$m++){
	for($n=$m+1;$n<$len;$n++){
		if($result['date'][$m] > $result['date'][$n]){
			$temp = $result['date'][$m];
			$result['date'][$m] = $result['date'][$n];
			$result['date'][$n] = $temp;

			$temp = $result['particulars'][$m];
			$result['particulars'][$m] = $result['particulars'][$n];
			$result['particulars'][$n] = $temp;

            $temp = $result['voucher'][$m];
            $result['voucher'][$m] = $result['voucher'][$n];
            $result['voucher'][$n] = $temp;

			$temp = $result['credit'][$m];
			$result['credit'][$m] = $result['credit'][$n];
			$result['credit'][$n] = $temp;

			$temp = $result['debit'][$m];
			$result['debit'][$m] = $result['debit'][$n];
			$result['debit'][$n] = $temp;
		}
	}
}

?>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
            <div class="kt-grid__item kt-app__toggle kt-app__aside" style="width:35%">
                <div class="kt-portlet kt-portlet--height-fluid-" >
                    <div class="kt-portlet__head kt-portlet__head--noborder">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">

                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <!-- <a href="#" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                <i class="flaticon-more-1"></i>
                            </a> -->
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <!--begin::Widget -->
                        <div class="kt-widget kt-widget--user-profile-2">
                            <div class="kt-widget__head" style="margin-bottom: 20px;">
                                <div class="kt-widget__media">
                                    
									<span class="kt-badge kt-badge--info kt-badge--xl kt-badge--rounded" style="width: 90px; height:90px; font-size: 53px;">
                                        <?php 
                                            echo $client[0];
                                        ?>
                                    </span>
                                </div>
                                <div class="kt-widget__info">
                                    <div class="kt-widget__section">

                                        <a href="#" class="kt-widget__username">
                                            <?php echo $client; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kt-widget__body">
                                <div class="kt-widget__content">
									<div class="kt-widget__info">
										<span class="kt-widget__label">Email:</span>
										<a href="#" class="kt-widget__data"><?php echo $email; ?></a>
									</div>
								</div>
								<div class="kt-widget__content">
									<div class="kt-widget__info">
										<span class="kt-widget__label">Mobile:</span>
										<a href="#" class="kt-widget__data"><?php echo $mobile; ?></a>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-grid__item kt-app__toggle kt-app__content" style="width:65%">
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Ledger
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
							<div class="kt-portlet__head-group">
								<a href="javascript:;" data-toggle="modal" data-target="#kt_modal_client_ledger_whatsapp" onclick="Wa_client_ledger(<?php echo $id; ?>)" class="btn btn-sm btn-icon btn-success btn-icon-lg"><i class="flaticon-whatsapp"></i></a>
								<a href="#" class="btn btn-sm btn-icon btn-brand btn-icon-md"><i class="flaticon2-send"></i></a>
								<a href="/assets/custom/client_ledger_print.php?id=<?php echo $id;?>" class="btn btn-sm btn-icon btn-danger btn-icon-md"><i class="fa fa-file-pdf"></i></a>
							</div>
						</div>
                    </div>
                    <div class="kt-portlet__body">
                    	<div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded">
							<table class="table table-striped table-bordered">
								<thead class="table-primary">
									<tr>
										<th style="text-align: center">Date</th>
										<th style="text-align: center">Particulars</th>
                                        <th style="text-align: center">Vch Type</th>
										<th style="text-align: center">Debit</th>
										<th style="text-align: center">Credit</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$count = 1;
                                        $total=0;
										$len = sizeof($result['particulars']);
										for($i=0;$i<$len;$i++){ ?>
									<tr>
										<td style="text-align: center"><?php echo date('d-m-Y',strtotime($result['date'][$i])); ?></td>
										<td><?php echo $result['particulars'][$i]; ?></td>
                                        <td style="text-align: center"><?php echo $result['voucher'][$i]; ?></td>
										<td style="text-align: center;"><?php if($result['debit'][$i] != 0){ echo money_format('%!i', $result['debit'][$i]); } ?></td>
										<td style="text-align: center;"><?php echo money_format('%!i', $result['credit'][$i]); ?></td>
									</tr>
    								<?php
                                        $total=$total+$result['credit'][$i]-$result['debit'][$i];
                                        $debit=$debit+$result['debit'][$i];
                                        $credit=$credit+$result['credit'][$i];
                                        } 
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: center;font-weight: bold;"><?php echo money_format('%!i',$debit); ?></td>
                                        <td style="text-align: center;font-weight: bold;"><?php echo money_format('%!i',$credit); ?></td>
                                    </tr>
                                    <?php
                                        $c_credit = $credit;
                                        $d_debit = $debit;
                                        if($total > 0){
                                            $d_debit += $total;
                                    ?>
                                        <tr>
                                            <td></td>
                                            <td style="text-align: right;">Closing Balance</td>
                                            <td></td>
                                            <td style="text-align: center;"><?php echo money_format('%!i',$total); ?></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                        }
                                        else if($total<0)
                                        {
                                            $total = abs($total);
                                            $c_credit += $total;
                                    ?>
                                        <tr>
                                            <td></td>
                                            <td style="text-align: right;">Closing Balance</td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: center;"><?php echo money_format('%!i',$total); ?></td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: center;font-weight: bold;"><?php echo money_format('%!i',$d_debit); ?></td>
                                        <td style="text-align: center;font-weight: bold;"><?php echo money_format('%!i',$c_credit); ?></td>
                                    </tr>
								</tbody>
							</table>
						</div>
                    </div>
                </div>

                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Opening Balance
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded">
                            <table class="table table-striped table-bordered" style="text-align: center">
                                <tr>
                                    <th>Year</th>
                                    <th>Opening Balance</th>
                                    <th>Actions</th>
                                </tr>
                                <?php
                                    
                                    $new_opening_balance = json_decode($row_fetch['new_opening_balance'],true);
                                    $len = sizeof($new_opening_balance['year']);

                                    for($i=0;$i<$len;$i++){

                                        $year = $new_opening_balance['year'][$i];
                                        $opening_balance = $new_opening_balance['balance'][$i];

                                ?>
                                <tr>
                                    <td><?php echo $year; ?></td>
                                    <td><?php echo money_format('%.0n',$opening_balance); ?></td>
                                    <td>
                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_update_client_opening_balance" onclick='updateCLientOpeningBalance("<?php echo $year;?>" , "<?php echo $opening_balance; ?>" , "<?php echo $client_id; ?>")' title="Edit">
                                            <i style="color: #6d3e12" class="flaticon2-paper"></i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <?php           
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form class="kt-form kt-form--label-right" id="update_client_opening_balance" autocomplete="off">
    <div class="modal fade" id="kt_modal_update_client_opening_balance" tabindex="-1" role="dialog" aria-labelledby="addedit_member" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addedit_member" >Update Opening Balance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="kt-portlet__body">
                        <input type="text" name="client_id" id="client_id" style="display: none;">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Year</label>
                                    <div class="kt-input-icon">
                                        <input name="update_opening_balance_year" placeholder="Year" id="update_opening_balance_year" class="form-control" type="text" readonly="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Opening Balance</label>
                                    <div class="kt-input-icon">
                                        <input name="update_opening_balance_amount" placeholder="Amount" id="update_opening_balance_amount" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="update_client_opening_balance_submit" type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!--begin::Send whatsapp Modal-->
<form class="kt-form kt-form--label-right" id="send_client_ledger_whatsapp">
    <div class="modal fade" id="kt_modal_client_ledger_whatsapp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Whatsapp</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                        <input name="cl_no_whatsapp" id="cl_no_whatsapp" style="display:none" class="form-control" type="text">

                        <div class="kt-portlet__body">
                            <div class="form-group row" style="margin-bottom: 0">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mobile No (Separted by comma)</label>
                                        <div class="kt-input-icon">
                                            <input name="cl_whatsapp_number" id="cl_whatsapp_number" placeholder="Enter Whatsapp Number(s)" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Form-->
                </div>
                <div class="modal-footer">
                    <button id="cl_whatsapp_submit" type="submit" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end::Send whatapp Modal-->
