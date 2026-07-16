
<?php
// Enable error reporting and logging for debugging
// ini_set("display_errors", 1);
// ini_set("display_startup_errors", 1);
// ini_set("log_errors", 1);
// ini_set("error_log", "/home/yourusername/public_html/error_log.txt");
// error_reporting(E_ALL);

session_start();
require_once "../assets/custom/connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start = isset($_SESSION['start']) ? $_SESSION['start'] : null;
$end = isset($_SESSION['end']) ? $_SESSION['end'] : null;

if (!$start || !$end) {
    error_log("Session variables 'start' or 'end' not set at " . __FILE__ . ":" . __LINE__);
    exit("Error: Invalid session data.");
}

$start_month = date('m', strtotime($start));
if ($start_month > 3 && $start_month <= 12) {
    $start_year = date('Y', strtotime($start));
    $end_year = $start_year + 1;
} else {
    $start_year = date('Y', strtotime($start)) - 1;
    $end_year = date('Y', strtotime($start));
}

$year = $start_year . '-' . substr($end_year, 2, 2);

$id = $_REQUEST['id'] ?? null;
if (!$id) {
    error_log("Client ID not provided at " . __FILE__ . ":" . __LINE__);
    exit("Error: Client ID missing.");
}

$result = ['particulars' => [], 'date' => [], 'voucher' => [], 'credit' => [], 'debit' => []];

$sql_fetch = "SELECT * FROM clients WHERE id = ?";
$stmt_fetch = $db->prepare($sql_fetch);
if (!$stmt_fetch) {
    error_log("Database prepare failed: " . $db->error);
    exit("Database error.");
}

$stmt_fetch->bind_param("s", $id);
$stmt_fetch->execute();
$query_fetch = $stmt_fetch->get_result();
if ($query_fetch->num_rows === 0) {
    error_log("No client found for ID: $id");
    exit("Error: Client not found.");
}

$row_fetch = $query_fetch->fetch_assoc();
$stmt_fetch->close();

$client = $row_fetch['name'] ?? '';
$client_id = $row_fetch['id'] ?? '';

$opening = 0;

$new_opening_balance = json_decode($row_fetch['new_opening_balance'],true);
$len = sizeof($new_opening_balance['year']);

for($i=0;$i<$len;$i++)
{
    if($new_opening_balance['year'][$i] == $year)
    {
        $opening = $new_opening_balance['balance'][$i];
    }
}

$contacts = json_decode($row_fetch['contacts'], true);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON decode error for contacts: " . json_last_error_msg());
    exit("Error: Invalid JSON data.");
}
$email = isset($contacts['email'][0]) ? $contacts['email'][0] : '';
$mobile = isset($contacts['mobile'][0]) ? $contacts['mobile'][0] : '';

$count = 1;
$total = 0;
$debit = 0;
$credit = 0;
$d_debit = 0;
$c_credit = 0;
$temp_credit = 0;
$temp_debit = 0;
$count = 0;

$sql = "SELECT * FROM sales_invoice WHERE client_name = ? AND si_date BETWEEN ? AND ? AND cancelled = 0 ORDER BY si_date ASC";
$stmt = $db->prepare($sql);
if (!$stmt) {
    error_log("Sales invoice query prepare failed: " . $db->error);
    exit("Database error.");
}
$stmt->bind_param("sss", $client, $start, $end);
$stmt->execute();
$query = $stmt->get_result();

while ($row = $query->fetch_assoc()) {
    $count++;
    $tax_details = json_decode($row['tax'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error for sales invoice tax: " . json_last_error_msg());
        continue;
    }

    $total = $row['total'];
    $tax = (isset($tax_details['cgst']) ? $tax_details['cgst'] : 0) +
          (isset($tax_details['sgst']) ? $tax_details['sgst'] : 0) +
          (isset($tax_details['igst']) ? $tax_details['igst'] : 0);

    $result['particulars'][] = '<a target="_blank" href="/assets/custom/sales_print.php?id=' . htmlspecialchars($row['si_no']) . '&type=print">' . htmlspecialchars($row['si_no']) . '</a>';
    $result['date'][] = $row['si_date'];
    $result['voucher'][] = 'Sales';
    $result['credit'][] = '';
    $result['debit'][] = $total;
}
$stmt->close();



$sql = "SELECT * FROM receipts WHERE client = ? AND date BETWEEN ? AND ? ORDER BY date ASC";
$stmt = $db->prepare($sql);
if (!$stmt) {
    error_log("Receipts query prepare failed: " . $db->error);
    exit("Database error.");
}
$stmt->bind_param("sss", $client, $start, $end);
$stmt->execute();
$query = $stmt->get_result();

while ($row = $query->fetch_assoc()) {
    // Decode the JSON stored in 'sales_invoice'
    $sales_invoice = json_decode($row['sales_invoice'], true);
    
    // Check if 'si_no' exists and is an array, then concatenate all elements with commas
    $si_no = isset($sales_invoice['si_no']) ? implode(', ', $sales_invoice['si_no']) : 'N/A';

    // Store the SI numbers in the particulars
    $result['particulars'][] = 'Payment - ' . htmlspecialchars($row['mode']) . ' (' . htmlspecialchars($row['instrument']) . ')<br/>SI #: ' . $si_no;
    $result['date'][] = $row['date'];
    $result['voucher'][] = 'Receipt';
    $result['credit'][] = $row['amount'];
    $result['debit'][] = '0';
}
$stmt->close();

$len = count($result['date']);

for ($m = 0; $m < $len - 1; $m++) {
    for ($n = $m + 1; $n < $len; $n++) {
        if ($result['date'][$m] > $result['date'][$n]) {
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
								<a href="javascript:;" data-toggle="modal" data-target="#kt_modal_supplier_ledger_whatsapp" onclick="Wa_supplier_ledger(<?php echo $client_id; ?>)"class="btn btn-sm btn-icon btn-success btn-icon-lg"><i class="flaticon-whatsapp"></i></a>
								<a data-toggle="modal" data-target="#kt_modal_sl_email" onclick="sendSLEmail(<?php echo $id; ?>)" title="Supplier Ledger" class="btn btn-sm btn-icon btn-brand btn-icon-md" style="vertical-align: left; margin-top: 10px; margin-bottom: 10px;">
    <i class="flaticon2-send"></i>
</a>
<a href="/assets/custom/client_ledger_print.php?id=<?php echo $client_id;?>" class="btn btn-sm btn-icon btn-danger btn-icon-md"><i class="fa fa-file-pdf"></i></a>
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
										<td style="text-align: center;"><?php echo number_format($result['debit'][$i],2); ?></td>
										<td style="text-align: center;"><?php if($result['credit'][$i] != 0){ echo number_format($result['credit'][$i],2); } ?></td>
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
                                        <td style="text-align: center;font-weight: bold;"><?php echo number_format($debit,2); ?></td>
                                        <td style="text-align: center;font-weight: bold;"><?php echo number_format($credit,2); ?></td>
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
                                            <td style="text-align: center;"><?php echo number_format($total,2); ?></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                        }
                                        else if($total<0)
                                        {
                                            $total *= -1;
                                            $c_credit += $total;
                                    ?>
                                        <tr>
                                            <td></td>
                                            <td style="text-align: right;">Closing Balance</td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: center;"><?php echo number_format($total,2); ?></td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: center;font-weight: bold;"><?php echo number_format($d_debit,2); ?></td>
                                        <td style="text-align: center;font-weight: bold;"><?php echo number_format($c_credit,2); ?></td>
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
                                    <td><?php echo number_format($opening_balance,2); ?></td>
                                    <td>
                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_update_supplier_opening_balance" onclick='updateSupplierOpeningBalance("<?php echo $year;?>" , "<?php echo $opening_balance; ?>" , "<?php echo $supplier_id; ?>")' title="Edit">
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

<form class="kt-form kt-form--label-right" id="update_supplier_opening_balance" autocomplete="off">
    <div class="modal fade" id="kt_modal_update_supplier_opening_balance" tabindex="-1" role="dialog" aria-labelledby="addedit_member" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addedit_member" >Update Opening Balance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="kt-portlet__body">
                        <input type="text" name="supplier_id" id="supplier_id" style="display: none;">
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
                    <button id="update_supplier_opening_balance_submit" type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!--begin::Send whatsapp Modal-->
<form class="kt-form kt-form--label-right" id="send_supplier_ledger_whatsapp">
    <div class="modal fade" id="kt_modal_supplier_ledger_whatsapp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Whatsapp</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                        <input name="sl_no_whatsapp" id="sl_no_whatsapp" style="display:none" class="form-control" type="text">

                        <div class="kt-portlet__body">
                            <div class="form-group row" style="margin-bottom: 0">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mobile No (Separted by comma)</label>
                                        <div class="kt-input-icon">
                                            <input name="sl_whatsapp_number" id="sl_whatsapp_number" placeholder="Enter Whatsapp Number(s)" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Form-->
                </div>
                <div class="modal-footer">
                    <button id="sl_whatsapp_submit" type="submit" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end::Send whatapp Modal-->

<!--begin::Send Email Modal-->
<form class="kt-form kt-form--label-right" id="send_sl_email">
    <div class="modal fade" id="kt_modal_sl_email" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                        <div class="kt-portlet__body">
                            <input name="sl_em_id" id="sl_em_id" class="form-control" type="text" style="display:none">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Send To</label>
                                        <div class="kt-input-icon">
                                            <input name="sl_em_email" id="sl_em_email" placeholder="Email Address" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <div class="kt-input-icon">
                                            <input name="sl_em_subject" id="sl_em_subject"  class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>CC</label>
                                        <div class="kt-input-icon">
                                            <input name="sl_em_email_cc" id="sl_em_email_cc" placeholder="Email Address"  class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>BCC</label>
                                        <div class="kt-input-icon">
                                            <input name="sl_em_email_bcc" id="sl_em_email_bcc" placeholder="Email Address" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="kt-input-icon">
                                            <input name="sl_em_message" id="sl_em_message" class="summernote">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Form-->
                </div>
                <div class="modal-footer">
                    <button id="sl_email_submit" type="submit" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end::Send Email Modal-->
