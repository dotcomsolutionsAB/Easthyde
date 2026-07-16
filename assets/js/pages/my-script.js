"use strict";
// Global Variables
var manageDashboardTable = '';

var manageProductTable = '';
var manageClientTable = '';
var manageSupplierTable = '';
var manageUsersTable = '';
var manageBankTable = '';
var manageMaterialsReceivedTable = '';
var manageProductHistoryTable = '';
var manageClientHistoryTable = '';

var managePDPurchaseTable = '';
var managePDSalesTable = '';
var managePDQuotationTable = '';
var managePDEnquiryTable = '';
var managePDTimelineTable = '';
var managePDCreditNoteTable = '';
var managePDDebitNoteTable = '';

var manageEnquiryTable = '';
var manageQuotationTable = '';
var manageSalesOrderTable = '';
var manageProformaInvoiceTable = '';
var manageSalesInvoiceTable = '';
var manageSalesSecondaryTable = '';
var manageSalesSecondaryItemsTable = '';
var manageSalesLedgerTable = '';
var manageReceiptsTable = '';
var manageArchivedTable = '';

var managePurchaseBagTable = '';
var managePurchaseOrderTable = '';
var managePurchaseInvoiceTable = '';
var managePurchaseQuotationTable = '';
var manageSecondaryPurchaseInvoiceTable = '';

var managePaymentsTable = '';
var manageAssembliesTable = '';
var manageAssembliesOperationTable = '';
var manageSalesAssembliesOperationTable = '';

var manageClientLedgerTable = '';

var managePaymentFollowupTable = '';
var manageMOQTable = '';
var manageCreditNoteTable = '';
var manageDebitNoteTable = '';

var manageContraTable = '';
var manageJournalTable = '';

var param_page = '';
var selected_supplier = '';
var selected_client = '';
var composite_quantity = '';
var type_receipt = '';

jQuery(document).ready(function () {
    Datatables.init();
    FormRepeater.init();
    Select2.init();
    Modals.init();
    Whatsapp.init();

    Purchase_Bag.init();

    Purchase_Quotation.init();

    Settings.init();
    Product.init();
    Client.init();
    Supplier.init();
    User.init();
    Assemblies.init();
    Payments.init();
    Receipts.init();
    Bank.init();
    Materials_Received.init();
    Contra.init();
    Ledger.init();
    // Journal.init();
    Credit_Note.init();
    Debit_Note.init();
	
	// jQuery function to trigger when the modal is opened
	$('#kt_modal_product').on('show.bs.modal', function (e) {
		// Call your function here
		$("#add_product_submit").attr("disabled", false);
	});

    $('#client_ledger_table').KTDatatable();

    $('#si_print').on("click", function () {
        // console.log("Click");
        $("#print_sales_invoice").modal('hide');

    });

    $(".image-picker").imagepicker();

    let searchParams = new URLSearchParams(window.location.search);

    if (searchParams.has('page')) {
        param_page = searchParams.get('page');
    }

    if (param_page == 'ROP') {
        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function (data, row, column, node) {
                        // Display date column properly in export
                        // return row === 2 ?
                        //     data.substr(36) :
                        //     data;

                        return data;
                    }
                }
            }
        };

        manageMOQTable = $("#rop_datatable").dataTable({
            ajax: "../assets/custom/moq/retrieve.php",
            dom: 'Bfrtip',
            responsive: true,
            fixedHeader: true,
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                $.extend(true, {}, buttonCommon, {
                    extend: 'excelHtml5',
                    exportOptions: {
                        // columns: ':visible'
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        // columns: ':thead tr th:not(.noExport)'
                    },
                    title: "Reorder Point Items"
                }),
                'colvis', 'pageLength'
            ],
            "columnDefs": [
                { "className": "dt-center", "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
            ],
            "processing": false
        });
    }

    if (param_page == 'enquiries') {
        set_enquiry_no();
        $('[data-repeater-list="enquiry"]').empty();
        $('[data-repeater-create="enquiry"]').click();
        var tmp = "input[name$='enquiry[0][e_sn]']";
        $(tmp).val(1);
        Enquiry.init();
    }

    if (param_page == 'journal') {
        set_enquiry_no();
        $('[data-repeater-list="journal"]').empty();
        $('[data-repeater-create="journal"]').click();
        var tmp = "input[name$='journal[0][journal_sn]']";
        $(tmp).val(1);
        Journal.init();
    }

    if (param_page == 'credit_note') {
        set_credit_note_no();
        $('[data-repeater-list="credit_note"]').empty();
        $('[data-repeater-create="credit_note"]').click();
        var tmp = "input[name$='credit_note[0][cn_sn]']";
        $(tmp).val(1);
        Credit_Note.init();
        Credit_Note_Group.init();
    }

    if (param_page == 'debit_note') {
        set_debit_note_no();
        $('[data-repeater-list="debit_note"]').empty();
        $('[data-repeater-create="debit_note"]').click();
        var tmp = "input[name$='debit_note[0][dn_sn]']";
        $(tmp).val(1);
        Debit_Note.init();
        Debit_Note_Group.init();
    }

    if (param_page == 'quotation') {
        set_quotation_no();
        $('[data-repeater-list="quotation"]').empty();
        $('[data-repeater-create="quotation"]').click();
        var tmp = "input[name$='quotation[0][q_sn]']";
        $(tmp).val(1);
        Quotation.init();
    }

    if (param_page == 'sales_order') {
        set_sales_order_no();
        $('[data-repeater-list="sales_order"]').empty();
        $('[data-repeater-create="sales_order"]').click();
        var tmp = "input[name$='sales_order[0][so_sn]']";
        $(tmp).val(1);
        Sales_Order.init();
    }
    function set_purchase_quotation() {
        $.ajax({
            url: '../assets/custom/api_get/get_counter.php',
            type: 'post',
            data: { key: 'purchase_quotation' },
            dataType: 'json',
            success: function (response) {
                $("#purchase_quotation_no").val(response.value);
            }
        });
    }
    if (param_page == 'purchase_quotation') {
        set_purchase_quotation();
        $('[data-repeater-list="purchase_quotation"]').empty();
        $('[data-repeater-create="purchase_quotation"]').click();
        var tmp = "input[name$='purchase_quotation[0][pq_sn]']";
        $(tmp).val(1);
        Purchase_Quotation.init();
    }


    if (param_page == 'proforma_invoice') {
        set_proforma_invoice_no();
        $('[data-repeater-list="proforma_invoice"]').empty();
        $('[data-repeater-create="proforma_invoice"]').click();
        var tmp = "input[name$='proforma_invoice[0][pr_sn]']";
        $(tmp).val(1);
        Proforma_Invoice.init();
    }

    if (param_page == 'sales' || param_page == 'test' ) {
        var data = $('#si_series').val();
        set_sales_invoice_no(data);
        $('[data-repeater-list="sales_invoice"]').empty();
        $('[data-repeater-create="sales_invoice"]').click();
        var tmp = "input[name$='sales_invoice[0][si_sn]']";
        $(tmp).val(1);
        Sales_Invoice.init();
        Sales_Group.init();
        
    }
    if (param_page == 'secondary_sales') {
        var data = $('#si_series').val();
        set_sales_invoice_no(data);
        $('[data-repeater-list="sales_invoice"]').empty();
        $('[data-repeater-create="sales_invoice"]').click();
        var tmp = "input[name$='sales_invoice[0][si_sn]']";
        $(tmp).val(1);
        Sales_Invoice.init();
       
        Secondary_Sales_Group.init();
    }

    if (param_page == 'receipt') {

        Receipt_Group.init();

        $(document).ready(function () {
            // Get URL parameters
            var urlParams = new URLSearchParams(window.location.search);
            var clientName = urlParams.get('client_name');
            var series = urlParams.get('series');
            var totalAmount = urlParams.get('total');

            // Populate the fields on the receipt page
            if (series) {
                $("#sales_receipt1").val(decodeURIComponent(series)).trigger('change');  // Populate series type
            }

            if (clientName) {
                // Populate the client name in Select2
                $("#rc_client").empty().append($("<option/>").val(clientName).text(clientName)).val(clientName).trigger('change');

                // Manually trigger the 'select2:select' event with data
                $('#rc_client').trigger({
                    type: 'select2:select',
                    params: {
                        data: { id: clientName }
                    }
                });
            }

            if (totalAmount) {
                $("#amount").val(totalAmount).trigger('keyup');  // Populate total amount
            }

            // The existing select2:select event logic
            // $('#rc_client').on("select2:select", function (e) {
            //     $('[data-repeater-list="receipt"]').empty();

            //     var id = $(e.currentTarget).val();
            //     id = encodeURIComponent(id);
            //     var type_receipt = encodeURIComponent(type_receipt);

            //     $.ajax({
            //         url: '../assets/custom/api_get/getPendingSales.php',
            //         type: 'post',
            //         data: { member_id: id, rc_type: type_receipt },
            //         dataType: 'json',
            //         success: function (response) {
            //             console.log(response);

            //             var obj = JSON.parse(response.result);
            //             var length = obj.si_details_sn.length;
            //             console.log(length);

            //             var total = 0;
            //             var c = 0;

            //             // Add entries dynamically based on response
            //             for (var i = 0; i < length; i++) {
            //                 $('#rc_btn_add').click();
            //             }
            //             for (var i = 0; i < length; i++) {
            //                 var temp = "input[name$='receipt[" + c + "][rc_invoice_id]']";
            //                 $(temp).val(obj.id[i]);

            //                 temp = "input[name$='receipt[" + c + "][rc_details_sn]']";
            //                 $(temp).val(obj.si_details_sn[i]);

            //                 temp = "input[name$='receipt[" + c + "][rc_details_si]']";
            //                 $(temp).val(obj.si_details_si[i]);

            //                 temp = "input[name$='receipt[" + c + "][rc_details_date]']";
            //                 $(temp).val(obj.si_details_date[i]);

            //                 temp = "input[name$='receipt[" + c + "][rc_details_amount]']";
            //                 $(temp).val(obj.si_details_amount[i]);

            //                 var amount = obj.si_details_amount[i].replace(/,/g, '');
            //                 amount = parseFloat(amount);
            //                 total = total + amount;

            //                 temp = "input[name$='receipt[" + c + "][rc_due]']";
            //                 $(temp).val(obj.due[i]);

            //                 c++;
            //             }

            //             $('#rc_amount_total').text('Total Due: ' + parseFloat(total).toFixed(2));
            //             rc_preview(e);
            //         } // /success
            //     }); // /fetch selected member info
            // });
        });


    }

    if (param_page == 'purchase_order') {
        Purchase_Order_Group.init();
        set_purchase_order_no();
        $('[data-repeater-list="purchase_order"]').empty();
        $('[data-repeater-create="purchase_order"]').click();
        var tmp = "input[name$='purchase_order[0][po_sn]']";
        $(tmp).val(1);
        Purchase_Order.init();
        $("#bulk_discount_btn").click(function (e) { po_discount(e); });
    }

    if (param_page == 'purchase') {
        set_purchase();
        $('[data-repeater-list="purchase_invoice"]').empty();
        $('[data-repeater-create="purchase_invoice"]').click();
        var tmp = "input[name$='purchase_invoice[0][pi_sn]']";
        $(tmp).val(1);
        Purchase_Invoice.init();
        Purchase_Group.init();
        console.log("Working");
    }

    if (param_page == 'secondary_purchase') {
        set_secondary_purchase();
        $('[data-repeater-list="purchase_invoice"]').empty();
        $('[data-repeater-create="purchase_invoice"]').click();
        var tmp = "input[name$='purchase_invoice[0][pi_sn]']";
        $(tmp).val(1);
        Purchase_Invoice.init();
    }



    if (param_page == 'payments') {
        Payment_Group.init();

        $(document).ready(function () {
            // Get URL parameters
            var urlParams = new URLSearchParams(window.location.search);
            var clientName = urlParams.get('client_name');
          
            var totalAmount = urlParams.get('total');

            // Populate the fields on the receipt page
           

            if (clientName) {
                // Populate the client name in Select2
                $("#py_supplier").empty().append($("<option/>").val(clientName).text(clientName)).val(clientName).trigger('change');

                // Manually trigger the 'select2:select' event with data
                $('#py_supplier').trigger({
                    type: 'select2:select',
                    params: {
                        data: { id: clientName }
                    }
                });
            }

            if (totalAmount) {
                $("#payment_amount").val(totalAmount).trigger('keyup');  // Populate total amount
            }

            $('#py_supplier').on("select2:select", function (e) {
                $('[data-repeater-list="payment"]').empty();
                // $('[data-repeater-create="payment"]').click();
                // var tmp = "input[name$='payment[0][py_sn]']";
                // $(tmp).val(1);
    
                var id = $(e.currentTarget).val();
                id = encodeURIComponent(id);
                $.ajax({
                    url: '../assets/custom/api_get/getPendingPurchase.php',
                    type: 'post',
                    data: { member_id: id },
                    dataType: 'json',
                    success: function (response) {
    
                        var temp = '';
                        var obj = JSON.parse(response.result);
    
                        var length = obj.pi_details_sn.length;
    
                        var c = 0;
    
                        var total = 0;
    
                        for (var i = 0; i < length; i++) {
                            $('#py_btn_add').click();
                        }
                        for (var i = 0; i < length; i++) {
    
                            temp = "input[name$='payment[" + c + "][py_invoice_id]']";
                            $(temp).val(obj.id[i]);
                            temp = "input[name$='payment[" + c + "][py_details_sn]']";
                            $(temp).val(obj.pi_details_sn[i]);
                            temp = "input[name$='payment[" + c + "][py_details_pi]']";
                            $(temp).val(obj.pi_details_pi[i]);
                            temp = "input[name$='payment[" + c + "][py_details_date]']";
                            $(temp).val(obj.pi_details_date[i]);
                            temp = "input[name$='payment[" + c + "][py_details_amount]']";
                            $(temp).val(obj.pi_details_amount[i]);
    
    
                            var amount = obj.pi_details_amount[i].replace(/,/g, '');
                            amount = parseFloat(amount);
                            console.log(amount);
    
                            total = total + amount;
    
    
                            temp = "input[name$='payment[" + c + "][py_due]']";
                            $(temp).val(obj.due[i]);
                            c++;
    
                        }
                        console.log(total);
                        $('#py_amount_total').text('Total Due: ' + parseFloat(total).toFixed(2));
    
                        py_preview(e);
                    } // /success
                }); // /fetch selected member info
            });

        });

    }

    if (param_page == 'product') {
        Product_Group.init();
        $('#kt_datatable_check_all').on('click', function () {
            // datatable.setActiveAll(true);
            $('#product_datatable').KTDatatable('setActiveAll', true);
        });
    }

    if (param_page == 'payment_followup') {
        Payment_Followup_Group.init();
    }

    $("#update_products").on("click", function () {

        $.ajax({
            url: '../assets/custom/api_excel/update_products.php',
            type: 'post',
            data: {},
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Products Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    swal.fire({
                        position: 'top-right',
                        type: 'error',
                        title: 'There were some errors in your submission.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

            } // /success
        }); // /fetch selected member info

    });

    $("#load_assembly_items").on("click", function () {


        var composite = $('#composite_product').val();
        var com_quantity = $('#composite_qty').val();
        if (com_quantity == '')
            com_quantity = 1;

        console.log(composite);
        console.log(com_quantity);

        if (composite != '') {
            console.log("fetch_data");
            var temp = '';
            var c = 0;

            $.ajax({
                url: '../assets/custom/api_get/get_assembly_items.php',
                type: 'post',
                data: { composite: composite },
                dataType: 'json',
                success: function (response) {
                    if (response != null) {
                        $('[data-repeater-list="assembly"]').empty();
                        $('[data-repeater-create="assembly"]').click();

                        var spares = JSON.parse(response);
                        var length = spares.product.length;

                        for (var i = 1; i < length; i++) {
                            $('#as_btn_add').click();
                        }

                        for (var i = 0; i < length; i++) {
                            console.log(spares.product[i]);
                            temp = "select[name$='assembly[" + c + "][as_product_name]']";
                            var pr = spares.product[i];
                            $(temp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");

                            temp = "input[name$='assembly[" + c + "][as_qty]']";
                            var quantity = spares.quantity[i] * com_quantity;
                            $(temp).val(quantity);

                            c++;
                        }

                    }
                } // /success
            }); // /fetch selected member info
        }
    });

    $("#add_products").on("click", function () {

        $.ajax({
            url: '../assets/custom/api_excel/add_products.php',
            type: 'post',
            data: {},
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Products Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    swal.fire({
                        position: 'top-right',
                        type: 'error',
                        title: 'There were some errors in your submission.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

            } // /success
        }); // /fetch selected member info

    });

    externalLinks();
});

var Modals = function () {

    var products = function () {
        $('#kt_modal_product').on('hidden.bs.modal', function () {
            $('#add_product')[0].reset();
            $('#product_name').val(null).trigger('change');
            $('#product_group_name').val(null).trigger('change');
            $('#product_vendor_name').val(null).trigger('change');
            $('#product_category').val(null).trigger('change');
            $('#product_sub_category').val(null).trigger('change');
            $('#product_unit').val(null).trigger('change');
            $('#product_tax').val(null).trigger('change');
            console.log("hidden");
        });
    };
    var clients = function () {
        $('#kt_modal_client').on('hidden.bs.modal', function () {
            $('#dcs_add_client')[0].reset();
            $('#client_category').val(null).trigger('change');
            $('#client_state').val(null).trigger('change');
            console.log("hidden");
        });
    };
    var suppliers = function () {
        $('#kt_modal_supplier').on('hidden.bs.modal', function () {
            $('#dcs_add_supplier')[0].reset();
            $('#supplier_category').val(null).trigger('change');
            $('#supplier_state').val(null).trigger('change');
            console.log("hidden");
        });
    };
    var product_history = function () {
        $('#kt_modal_product_history').on('hidden.bs.modal', function () {
            $('#history_product').val(null).trigger('change');
            console.log("hidden");
        });

        $('#history_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            tags: true,
            allowClear: true
        });
    };
    // var client_history = function() {
    //     $('#kt_modal_supplier').on('hidden.bs.modal', function() {
    //         $('#dcs_add_supplier')[0].reset();
    //         $('#supplier_category').val(null).trigger('change');
    //         $('#supplier_state').val(null).trigger('change');
    //         console.log("hidden");
    //     });
    // };
    var client_history = function () {
        // Reset the select2 when the modal is hidden
        $('#kt_modal_client_history').on('hidden.bs.modal', function () {
            $('#history_client').val(null).trigger('change');
            $('#history_client_series').val('');
            if (typeof manageClientHistoryTable !== 'undefined' && manageClientHistoryTable && manageClientHistoryTable.search) {
                manageClientHistoryTable.search('', 'series');
            }
            console.log("Client history modal hidden");
        });

        // Initialize select2 for the client history select dropdown
        $('#history_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client_supplier.php', // Adjust the API URL as needed
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            tags: true,
            allowClear: true
        });
    };


    return {
        init: function () {
            products();
            clients();
            suppliers();
            product_history();
            client_history();

        },
    };
}();

var Datatables = function () {

    var dashboard = function () {

        manageDashboardTable = $('#product_dashboard_datatable').KTDatatable({
            scrollX: true,
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/dashboard/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        // map: function (raw) {
                        //     // sample data mapping
                        //     var dataSet = raw;
                        //     if (typeof raw.data !== 'undefined') {
                        //         dataSet = raw.data;
                        //     }
                        //     return dataSet;
                        // },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true, // Enables vertical scrolling
                height: null, // Optionally, set the height or leave it null for auto
                footer: false,
                scrollX: true, // Enable horizontal scrolling
            },




            // column sorting
            sortable: true,
            filterable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: 'asc',
                width: 15,
                sortable: true,
                filterable: true,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
                autoHide: false,
            }, {
                field: 'Name',
                title: 'Name',
                width: 200,
                sortable: true,
                filterable: true,
                template: function (row) {
                    // return '<strong><a target="_top" href="?page=product_details&pr=' + row.Name + '"  >' + row.Name + '</a></strong><br/><span class="form-text text-muted" style="font-size: 12px;">' + row.Description + '</span><span class="form-text text-muted" style="font-size: 12px;">' + row.Alias + '</span>';


                    return row.Name + '<br/><span class="form-text text-muted" style="font-size: 12px;">' + row.Description + '</span><span class="form-text text-muted" style="font-size: 12px;">' + row.Alias + '</span>';
                },
                autoHide: false,
            }, {
                field: 'Group',
                title: 'Group',
                width: 75,
                sortable: true,
                filterable: true,
                template: function (row) {
                    return '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">' + row.Group + '</span>';
                },
                textAlign: 'center',
                autoHide: false,
            },
            {
                field: 'Category',
                title: 'Category',
                textAlign: 'center',
                autoHide: false,
                sortable: true,
                filterable: true,
            }, {
                field: 'Sub-Category',
                title: 'Sub-Category',
                textAlign: 'center',
                autoHide: false,
                sortable: true,
                filterable: true,
            }, {
                field: 'Rate',
                title: 'Sale Price',
                textAlign: 'center',
                sortable: true,
                filterable: true,
                template: function (row) {

                    var temp = '';

                    if (row.Updated_Price == '1') {
                        temp = '<i class="kt-nav__link-icon flaticon2-correct" style="color: green"></i><br><span style="font-size: 12px;">' + row.Updated_Price_Date + '</span>';
                    }

                    return 'Rs. ' + row.Rate + ' ' + temp;
                },
                autoHide: false,
            }, {
                field: 'HSN',
                title: 'HSN',
                width: 100,
                textAlign: 'center',
                autoHide: false,
                sortable: true,
                filterable: true,
            }, {
                field: 'Unit',
                title: 'Current Stock',
                textAlign: 'center',
                width: 150,
                sortable: true, // enables sorting for this column
                filterable: true ,
                template: function (row) {
                    var stock =  '&nbsp ' + row.Unit + '&nbsp';

                    var temp = '';

                    if (row.Updated_Stock == '1') {
                        temp = '<i class="kt-nav__link-icon flaticon2-correct" style="color: green"></i><br><span style="font-size: 18px;">' + row.Updated_Stock_Date + '</span>';
                    }

                    return '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill" style="font-size: 18px;">' + stock + '</span> ' + temp;

                },
                autoHide: false,
            }, {
                field: 'Actions',
                title: 'Actions',
                width: 80,
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
                sortable: true,
                filterable: true,
            }],


        });

    };

    var archived = function () {

        manageArchivedTable = $('#archived_product_dashboard_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {

                        url: '../assets/custom/dashboard/archive_retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true, // Enables vertical scrolling
                height: null, // Optionally, set the height or leave it null for auto
                footer: false,
                scrollX: true, // Enable horizontal scrolling
            },




            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: 'asc',
                width: 15,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                width: 250,
                template: function (row) {
                    // return '<strong><a target="_top" href="?page=product_details&pr=' + row.Name + '"  >' + row.Name + '</a></strong><br/><span class="form-text text-muted" style="font-size: 12px;">' + row.Description + '</span><span class="form-text text-muted" style="font-size: 12px;">' + row.Alias + '</span>';


                    return row.Name + '<br/><span class="form-text text-muted" style="font-size: 12px;">' + row.Description + '</span><span class="form-text text-muted" style="font-size: 12px;">' + row.Alias + '</span>';
                },
            }, {
                field: 'Group',
                title: 'Group',
                width: 75,
                template: function (row) {
                    return '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">' + row.Group + '</span>';
                },
                textAlign: 'center',
            }, {
                field: 'Category',
                title: 'Category',
                textAlign: 'center',
            }, {
                field: 'Sub-Category',
                title: 'Sub-Category',
                textAlign: 'center',
            }, {
                field: 'Rate',
                title: 'Sale Price',
                textAlign: 'center',
                template: function (row) {

                    var temp = '';

                    if (row.Updated_Price == '1') {
                        temp = '<i class="kt-nav__link-icon flaticon2-correct" style="color: green"></i>';
                    }

                    return 'Rs. ' + row.Rate + ' ' + temp;
                },
            }, {
                field: 'HSN',
                title: 'HSN',
                textAlign: 'center',
            }, {
                field: 'Unit',
                title: 'Current Stock',
                textAlign: 'center',
                template: function (row) {
                    var stock = row.Opening_stock + ' ' + row.Unit;

                    var temp = '';

                    if (row.Updated_Stock == '1') {
                        temp = '<i class="kt-nav__link-icon flaticon2-correct" style="color: green"></i>';
                    }

                    return '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill">' + stock + '</span> ' + temp;

                },
            }, {
                field: 'Actions',
                title: 'Actions',
                width: 80,
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }],


        });

    };

    var products = function () {

        manageProductTable = $('#product_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            toolbar: {
                layout: ['pagination', 'info'],
                placement: ['bottom'],
                items: {
                    pagination: {
                        pageSizeSelect: [10, 25, 50, 100, 250, 500],
                    },
                },
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: 'asc',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {

                    var temp = '';

                    if (row.Archive == '1') {
                        temp = '<i class="kt-nav__link-icon flaticon2-cross" style="color: red"></i>';
                    }

                    return row.Name + ' ' + temp + '<br/><span class="form-text text-muted" style="font-size: 9px;">' + row.Description + "</span>";
                },
            }, {
                field: 'Group',
                title: 'Group',
            }, {
                field: 'Category',
                title: 'Category',
            }, {
                field: 'Sub-Category',
                title: 'Sub-Category',
            },
            {
                field: 'Vendor',
                title: 'Vendor',
            }, {
                field: 'Cost',
                title: 'Cost Price',
                template: function (row) {

                    var temp = '';

                    if (row.Updated_Cost == '1') {
                        temp = '<i class="kt-nav__link-icon flaticon2-correct" style="color: green"></i><br><span style="font-size: 12px;">' + row.Updated_Cost_Date + '</span>';
                    }

                    return 'Rs. ' + row.Cost + ' ' + temp;
                },
            }, {
                field: 'Rate',
                title: 'Sale Price',
                template: function (row) {

                    var temp = '';

                    if (row.Updated_Price == '1') {
                        temp = '<i class="kt-nav__link-icon flaticon2-correct" style="color: green"></i><br><span style="font-size: 12px;">' + row.Updated_Price_Date + '</span>';
                    }

                    return 'Rs. ' + row.Rate + ' ' + temp;
                },
            }, {
                field: 'Tax',
                title: 'Tax',
                template: function (row) {
                    return row.Tax + ' %<br/>HSN : ' + row.HSN;
                },
            }, {
                field: 'Unit',
                title: 'Initial Stock',
                template: function (row) {
                    return row.Opening_stock + ' ' + row.Unit;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var clients = function () {

        manageClientTable = $('#dcs_clients_datatable').KTDatatable({
            // datasource definition
            scrollX: true,  // Enable horizontal scrolling
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/clients/retrieve.php',
                        map: function (raw) {
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 20,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,  // Enable scrolling for smaller screens
                footer: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: false,
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
                autoHide: false,  // Ensure this column is always visible
            }, {
                field: "Name",
                title: "Name",
                template: function (row) {
                    var output = '' +
                        '<div class="kt-user-card-v2">' +
                        '<div class="kt-user-card-v2__pic">';
                    output += '<span class="kt-badge ' + row.KT_Class + ' kt-badge--xl">' + row.Name.charAt(0) + '</span>';
                    output += '</div>' +
                        '<div class="kt-user-card-v2__details">';
                    output += '<a href="?page=client_ledger&id=' + row.Id + '" target="_blank" class="kt-user-card-v2__name">' + row.Name + '</a></br>';
                    output += '<span class="kt-user-card-v2__desc">' + row.GSTIN + '</span>';
                    output += '</div></div>';
                    return output;
                },
                autoHide: false,  // Ensure this column is always visible
            }, {
                field: 'Contact_Name',
                title: 'Contact Details',
                template: function (row) {
                    var output = row.Contact_Name + '</br>' + row.Designation + '</br>' + row.Mobile + '</br>' + row.Email;
                    return output;
                },
                autoHide: false,  // Ensure this column is always visible
            }, {
                field: 'Add1',
                title: 'Address',
                template: function (row) {
                    var output = row.Add1 + '</br>' + row.Add2 + '</br>' + row.City + '-' + row.Pincode + '</br>' + row.State + ', ' + row.Country;
                    return output;
                },
                autoHide: false,  // Ensure this column is always visible
            },
            {
                field: 'Bank_Client',
                title: 'Bank Details',
                template: function (row) {
                    var output = row.Bank_Client + '</br>' + row.Bank_Name + '</br>' + row.Bank_Account + '</br>' + row.Bank_IFSC;
                    return output;
                },
                autoHide: false,  // Ensure this column is always visible
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,  // Ensure this column is always visible
            }
            ],

        });

    };

    var product_history = function () {

        manageProductHistoryTable = $('#product_history_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product/history.php',  // Ensure this endpoint returns the correct JSON
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 20,  // Number of items per page
                serverPaging: true,  // Enable server-side pagination
                serverFiltering: true,  // Enable server-side filtering
                serverSorting: true,  // Enable server-side sorting
                saveState: false,  // Disable saving the table state
            },

            // layout definition
            layout: {
                scroll: true,  // Enable scrolling
                footer: false,  // Disable the footer
            },

            // column sorting
            sortable: true,

            // Enable pagination
            pagination: true,

            // Define search input
            search: {
                input: $('#history_product'),  // Corrected selector for the search input field
            },

            // columns definition
            columns: [
                {
                    field: 'Date',
                    title: 'Date',
                    sortable: true,
                    template: function (row) {
                        return row.Date;  // Display the 'Date' field
                    }
                },
                {
                    field: 'Type',
                    title: 'Type',
                    sortable: true,
                    template: function (row) {
                        return row.Type;  // Display the 'Type' field (e.g., Purchase, Sales, etc.)
                    }
                },
                {
                    field: 'Reference',
                    title: 'Reference',
                    sortable: true,
                    template: function (row) {
                        return row.Reference;  // Corrected 'Reference' field name (e.g., pi_no, si_no, etc.)
                    }
                },
                {
                    field: 'Buyer',
                    title: 'Buyer/Supplier',
                    sortable: true,
                    template: function (row) {
                        return row.Buyer;  // Display the 'Buyer' or 'Supplier' field
                    }
                },
                {
                    field: 'Qty',
                    title: 'Quantity',
                    sortable: true,
                    template: function (row) {
                        return row.Qty;  // Display the quantity field
                    }
                },
                {
                    field: 'Price',
                    title: 'Price',
                    sortable: true,
                    template: function (row) {
                        return row.Price;  // Display the 'Price' field
                    }
                },
                {
                    field: 'Discount',
                    title: 'Discount',
                    sortable: true,
                    template: function (row) {
                        return row.Discount;  // Display the 'Discount' field
                    }
                }
            ]
        });



    };
    var client_history = function () {
        manageClientHistoryTable = $('#client_history_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/clients/history.php',  // Make sure this URL is correct
                        map: function (raw) {
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 20,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },

            layout: {
                scroll: true,
                footer: false,
            },

            sortable: true,
            pagination: true,
            search: {
                input: $('#history_client'),
            },

            columns: [
                {
                    field: 'SN',
                    title: 'Serial',
                    template: function (row) {
                        return row.SN;
                    }
                },
                {
                    field: 'Date',
                    title: 'Date',
                    template: function (row) {
                        return row.Date;
                    }
                },
                {
                    field: 'Type',
                    title: 'Type',
                    template: function (row) {
                        return row.Type;
                    }
                },
                {
                    field: 'TypeNo',
                    title: 'Type No',
                    template: function (row) {
                        return row.TypeNo;
                    }
                },
                {
                    field: 'Series',
                    title: 'Primary / Secondary',
                    template: function (row) {
                        return row.Series || '';
                    }
                },
                {
                    field: 'TotalAmount',
                    title: 'Total Amount',
                    template: function (row) {
                        return row.TotalAmount;
                    }
                },
                {
                    field: 'Status',
                    title: 'Status',
                    template: function (row) {
                        return row.Status;
                    }
                },
                {
                    field: 'ProductDetails', // New field to handle product details in a table format
                    title: 'Product Details',
                    template: function (row) {
                        var productDetails = '<table class="table table-bordered"><thead>';
                        productDetails += '<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Unit</th><th>Tax</th><th>Discount</th><th>Total</th></tr></thead><tbody>';

                        // Loop through each product detail and add it as a row in the nested table
                        row.Details.forEach(function (detail) {
                            productDetails += '<tr>';
                            productDetails += '<td>' + detail.product + '</td>';
                            productDetails += '<td>' + detail.price + '</td>';
                            productDetails += '<td>' + detail.qty + '</td>';
                            productDetails += '<td>' + detail.unit + '</td>';
                            productDetails += '<td>' + detail.tax + '</td>';
                            productDetails += '<td>' + detail.discount + '</td>';
                            productDetails += '<td>' + detail.total + '</td>';
                            productDetails += '</tr>';
                        });

                        productDetails += '</tbody></table>';
                        return productDetails;
                    }
                }
            ]
        });
    };



    var suppliers = function () {

        manageSupplierTable = $('#dcs_suppliers_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/suppliers/retrieve.php',
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 20,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: false,
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: "Name",
                title: "Name",
                template: function (row) {
                    var output = '' +
                        '<div class="kt-user-card-v2">' +
                        '<div class="kt-user-card-v2__pic">';
                    output += '<span class="kt-badge ' + row.KT_Class + ' kt-badge--xl">' + row.Name.charAt(0) + '</span>';
                    output += '</div>' +
                        '<div class="kt-user-card-v2__details">';
                    output += '<a href="?page=supplier_ledger&id=' + row.Id + '" target="_blank"  class="kt-user-card-v2__name">' + row.Name + '</a></br>';
                    output += '<span class="kt-user-card-v2__desc">' + row.GSTIN + '</span>';
                    output += '</div></div>';
                    return output;
                },
            }, {
                field: 'Contact_Name',
                title: 'Contact Details',
                template: function (row) {
                    var output = row.Contact_Name + '</br>' + row.Designation + '</br>' + row.Mobile + '</br>' + row.Email;
                    return output;
                },
            }, {
                field: 'Add1',
                title: 'Address',
                template: function (row) {
                    var output = row.Add1 + '</br>' + row.Add2 + '</br>' + row.City + '</br>' + row.State + '-' + row.Pincode;
                    return output;
                },
            },
            {
                field: 'Bank_Supplier',
                title: 'Bank Details',
                template: function (row) {
                    var output = row.Bank_Supplier + '</br>' + row.Bank_Name + '</br>' + row.Bank_Account + '</br>' + row.Bank_IFSC;
                    return output;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }
            ],

        });

    };

    var users = function () {

        manageUsersTable = $('#users_table').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/users/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 1,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: 'asc',
                width: 30,
                type: 'number',
                selector: false,
                textAlign: 'center',
            }, {
                field: 'name',
                title: 'Name',
            }, {
                field: 'username',
                title: 'Username'
            }, {
                field: 'mobile',
                title: 'Mobile',
            }, {
                field: 'email',
                title: 'Email',
            }, {
                field: 'allowed_fy',
                title: 'Allowed FY',
            }, {
                field: 'userlevel',
                title: 'User Type',
                // callback function support for column rendering
                template: function (row) {
                    var status = {
                        'sadmin_df56fdg': { 'title': 'Admin', 'class': 'kt-badge--success' },
                        'employee_jhkFNDdd': { 'title': 'Employee', 'class': 'kt-badge--brand' },
                        'sales_HgdK5254SHdg': { 'title': 'Sales', 'class': ' kt-badge--info' },
                        'purchase_LK85SDhg6dfd': { 'title': 'Purchase', 'class': ' kt-badge--danger' },
                    };
                    return '<span class="kt-badge ' + status[row.userlevel].class + ' kt-badge--inline kt-badge--pill">' + status[row.userlevel].title + '</span>';
                },
            },
            {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 80,
                overflow: 'visible',
                autoHide: false,
            }
            ],

        });

        $('#kt_user_type').on('change', function () {
            manageUsersTable.search($(this).val().toLowerCase(), 'Usertype');
        });

        $('#kt_user_type').selectpicker();

        $('#userlevel').select2({
            width: '100%',
            placeholder: 'Select User Type'
        });

        $('#edit_userlevel').select2({
            width: '100%',
            placeholder: 'Select User Type'
        });

        $('#allowed_fy').select2({
            ajax: {
                url: '../assets/custom/api_get/get_fy.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select FY',
            allowClear: true
        });

        $('#edit_allowed_fy').select2({
            ajax: {
                url: '../assets/custom/api_get/get_fy.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select FY',
            allowClear: true
        });

    };

    var bank = function () {

        manageBankTable = $('#bank_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/banks/retrieve.php',
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 20,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: false,
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Account_Name',
                title: 'Account Name'
            }, {
                field: "Bank_Name",
                title: "Bank Name"
            }, {
                field: 'Account_Number',
                title: 'Account Number'
            },
            {
                field: 'Bank_IFSC',
                title: 'IFSC Code'
            }, {
                field: 'Opening_Balance',
                title: 'Opening Balance'
            },
            {
                field: 'Actions',
                title: 'Actions'
            }
            ],

        });

    };

    var enquiry = function () {

        manageEnquiryTable = $('#enquiry_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/enquiry/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInitQ,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Client',
                title: 'Client',
                width: 200,

            }, {
                field: 'Enquiry_no',
                title: 'Enquiry No',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Mode',
                title: 'Mode',
            },
            {
                field: 'Status',
                title: 'Status',
                template: function (row) {
                    var status = {
                        0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
                        1: { 'title': 'Completed', 'class': ' kt-badge--success' },
                        2: { 'title': 'Rejected', 'class': ' kt-badge--danger' },
                    };
                    return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }
            ],

        });

        function subTableInitQ(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/enquiry/retrieve_item.php?id=' + e.data.Enquiry_no,

                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 9px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong>';
                    },
                },
                {
                    field: 'Stock',
                    title: 'Stock in Hand',
                    template: function (row) {
                        return '<strong>' + row.Stock + '</strong>';
                    },
                },
                {
                    field: 'Co_Stock',
                    title: 'Stock in Co.',
                    template: function (row) {
                        return '<strong>' + row.Co_Stock + '</strong>';
                    },
                }
                ],
            });
        }

    };

    var quotation = function () {

        manageQuotationTable = $('#quotation_datatable').KTDatatable({
            //scrollX:true,
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/quotation/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInitQ,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Client',
                title: 'Client',
                width: 200,

            },
            {
                field: 'Quotation',
                title: 'Quotation',
                template: function (row) {
                    var output = row.Quotation;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Quotation_no + '</div>';
                    return output;
                }
            },
            {
                field: 'Date',
                title: 'Date',
                template: function (row) {
                    var output = row.Date;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Date + '</div>';
                    return output;
                }
            },
            {
                field: 'Enquiry',
                title: 'Enquiry',
                template: function (row) {
                    var items = JSON.parse(row.Enquiry);
                    var output = '';
                    if (items.cl_enquiry_no[0] != '')
                        output += items.cl_enquiry_no[0];
                    if (items.enquiry_date[0] != '')
                        output += '<br>' + items.enquiry_date[0];
                    return output;
                },
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Status',
                title: 'Status',
                template: function (row) {
                    var status = {
                        0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
                        1: { 'title': 'Completed', 'class': ' kt-badge--success' },
                        2: { 'title': 'Rejected', 'class': ' kt-badge--danger' },
                        9: { 'title': 'Cancelled', 'class': ' kt-badge--danger' },
                    };
                    return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }
            ],

        });

        function subTableInitQ(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/quotation/retrieve_item.php?id=' + e.data.Quotation_no,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 9px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }],
            });
        }



        $('form input').on('keypress', function (e) {
            // console.log(e.which);
            return e.which !== 13;
        });

    };

    var sales_order = function () {

        manageSalesOrderTable = $('#sales_order_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/sales_order/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInit,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '' +
                        '<div class="kt-user-card-v2">' +
                        '<div class="kt-user-card-v2__pic">';
                    output += '<span class="kt-badge ' + row.KT_Class + ' kt-badge--xl">' + row.Name.charAt(0) + '</span>';
                    output += '</div>' +
                        '<div class="kt-user-card-v2__details">';
                    output += '<a href="#" class="kt-user-card-v2__name">' + row.Name + '</a>';
                    output += '</div></div>';

                    output = row.Name;
                    return output;
                },
            },
            {
                field: 'SalesOrder',
                title: 'Order No',
                template: function (row) {
                    var output = row.SalesOrder;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.SalesOrder + '</div>';
                    return output;
                }
            },
            // {
            //     field: 'Number',
            //     title: 'Order Number',
            //     template: function(row) {
            //         var output = row.Number;
            //         if (row.Cancelled == '1')
            //             output = '<div style="text-decoration: line-through;">' + row.Number + '</div>';
            //         return output;
            //     }
            // }, 
            {
                field: 'Date',
                title: 'Date',
                template: function (row) {
                    var output = row.Date;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Date + '</div>';
                    return output;
                }
            }, {
                field: 'MaterialStatus',
                title: 'Status',
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Status',
                title: 'Status',
                template: function (row) {
                    var status = {
                        0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
                        1: { 'title': 'Completed', 'class': ' kt-badge--success' },
                        2: { 'title': 'Partial', 'class': ' kt-badge--warning' },
                        9: { 'title': 'Cancelled', 'class': ' kt-badge--danger' },
                    };
                    return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/sales_order/retrieve_item.php?id=' + e.data.Number,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Received + '</strong> out of <strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }, {
                    field: 'Actions',
                    title: 'Actions',
                    sortable: false,
                    width: 110,
                    overflow: 'visible',
                    autoHide: false,
                }],
            });
        }

    };

    var proforma = function () {

        manageProformaInvoiceTable = $('#proforma_invoice_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/proforma_invoice/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInit,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '' +
                        '<div class="kt-user-card-v2">' +
                        '<div class="kt-user-card-v2__pic">';
                    output += '<span class="kt-badge ' + row.KT_Class + ' kt-badge--xl">' + row.Name.charAt(0) + '</span>';
                    output += '</div>' +
                        '<div class="kt-user-card-v2__details">';
                    output += '<a href="#" class="kt-user-card-v2__name">' + row.Name + '</a>';
                    output += '</div></div>';

                    output = row.Name;
                    return output;
                },
            },
            {
                field: 'Proforma',
                title: 'Order No',
                template: function (row) {
                    var output = row.Proforma;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Proforma + '</div>';
                    return output;
                }
            },
            // {
            //     field: 'Number',
            //     title: 'Order Number',
            // }, 
            {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/proforma_invoice/retrieve_item.php?id=' + e.data.Number,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Received + '</strong> out of <strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }],
            });
        }

    };

    var sales = function () {

        manageSalesInvoiceTable = $('#sales_invoice_datatable').KTDatatable({
            // datasource definition

            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/sales_invoice/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            toolbar: {
                layout: ['pagination', 'info'],
                placement: ['bottom'],
                items: {
                    pagination: {
                        pageSizeSelect: [10, 25, 50, 100, 250, 500],
                    },
                },
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInit,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'RecordID2',
                title: '',
                template: '{{RecordID2}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '';
                    output = '<a href="?page=client_ledger&id=' + row.Client_ID + '" target="_blank">' + row.Name + '</a>';
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Name + '</div>';
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Invoice Number',
                template: function (row) {
                    var output = row.Number;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Number + '</div>';
                    return output;
                }
            }, {
                field: 'Date',
                title: 'Date',
                template: function (row) {
                    var output = row.Date;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Date + '</div>';
                    return output;
                }
            }, {
                field: 'Amount',
                title: 'Amount',
                template: function (row) {
                    var output = row.Amount;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Amount + '</div>';
                    return output;
                }
            },
            {
                field: 'Notes',
                title: 'Notes',
                template: function (row) {
                    // Display only the first 20 characters of the notes
                    let truncatedNotes = row.Notes.length > 20 ? row.Notes.substring(0, 20) + '...' : row.Notes;

                    return `<span data-toggle="tooltip" title="${row.Notes}">${truncatedNotes}</span>`;
                }
            }, {
                field: 'Status',
                title: 'Status',
                template: function (row) {
                    var status = {
                        0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
                        1: { 'title': 'Completed', 'class': ' kt-badge--success' },
                        2: { 'title': 'Partial', 'class': ' kt-badge--warning' },
                        9: { 'title': 'Cancelled', 'class': ' kt-badge--danger' },
                    };
                    return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/sales_invoice/retrieve_item.php?id=' + e.data.Number,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }],
            });
        }

    };

    var sales_secondary = function () {

        manageSalesSecondaryTable = $('#sales_secondary_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/sales_invoice/retrieve_secondary.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInit,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            },{
                field: 'RecordID2',
                title: '',
                template: '{{RecordID2}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '' +
                        '<div class="kt-user-card-v2">' +
                        '<div class="kt-user-card-v2__pic">';
                    output += '<span class="kt-badge ' + row.KT_Class + ' kt-badge--xl">' + row.Name.charAt(0) + '</span>';
                    output += '</div>' +
                        '<div class="kt-user-card-v2__details">';
                    output += '<a href="#" class="kt-user-card-v2__name">' + row.Name + '</a>';
                    output += '</div></div>';
                    output = row.Name;
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Order Number',
                template: function (row) {
                    var output = row.Number;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Number + '</div>';
                    return output;
                }
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Amount',
                title: 'Amount',
            },
            {
                field: 'Notes',
                title: 'Notes',
                template: function (row) {
                    // Display only the first 20 characters of the notes
                    let truncatedNotes = row.Notes.length > 20 ? row.Notes.substring(0, 20) + '...' : row.Notes;

                    return `<span data-toggle="tooltip" title="${row.Notes}">${truncatedNotes}</span>`;
                }
            }, {
                field: 'Status',
                title: 'Status',
                template: function (row) {
                    var status = {
                        0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
                        1: { 'title': 'Completed', 'class': ' kt-badge--success' },
                        2: { 'title': 'Partial', 'class': ' kt-badge--warning' },
                        9: { 'title': 'Cancelled', 'class': ' kt-badge--danger' },

                    };
                    return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/sales_invoice/retrieve_item.php?id=' + e.data.Number,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }],
            });
        }

    };

    var sales_secondary_items = function () {

        manageSalesSecondaryItemsTable = $('#sales_secondary_items_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/sales_invoice/retrieve_secondary_items.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            // detail: {
            //     title: 'Load products',
            //     content: subTableInit,
            // },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Product',
                title: 'Product',
            }, {
                field: 'HSN',
                title: 'HSN',
            }, {
                field: 'SI',
                title: 'SI',
            }, {
                field: 'Quantity',
                title: 'Quantity',
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/sales_invoice/retrieve_item.php?id=' + e.data.Number,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }],
            });
        }

    };

    var sales_ledger = function () {

        manageSalesLedgerTable = $('#sales_ledger_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/reports/retreive_sales_ledger.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = row.Name;
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Invoice Number',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Tax',
                title: 'Tax',
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };


    var receipts = function () {

        manageReceiptsTable = $('#receipts_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/receipts/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            toolbar: {
                layout: ['pagination', 'info'],
                placement: ['bottom'],
                items: {
                    pagination: {
                        pageSizeSelect: [10, 25, 50, 100, 250, 500],
                    },
                },
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'ID',
                title: 'SN',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Client',
                title: 'Client',
            }, {
                field: 'Sale_Invoice',
                title: 'Sale Invoices',
            }, {
                field: 'Mode',
                title: 'Mode',
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Actions',
                title: 'Actions',
            }],

        });



    };






    var payment_followup = function () {

        managePaymentFollowupTable = $('#payment_followup_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/payment_followup/retrieve.php',
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 20,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: false,
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: "Name",
                title: "Name",
                template: function (row) {
                    var output = '';
                    output += '<a href="?page=client_ledger&id=' + row.Id + '" target="_blank" class="kt-user-card-v2__name">' + row.Name + '</a></br>';

                    return output;
                },
            }, {
                field: "Particulars",
                title: "Particulars",
            },
            {
                field: "Due",
                title: "Due",
            },
            {
                field: 'Actions',
                title: 'Actions',
            }
            ],

        });

    };

    var purchase_bag = function () {

        managePurchaseBagTable = $('#purchase_bag_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/purchase_bag/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
            }, {
                field: 'Group',
                title: 'Group',
            }, {
                field: 'Category',
                title: 'Category',
            }, {
                field: 'Sub_Category',
                title: 'Sub Category',
            }, {
                field: 'Quantity',
                title: 'Quantity',
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '</br>' + row.Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
            }

            ],

        });

    };

    var purchase_order = function () {

        managePurchaseOrderTable = $('#purchase_order_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/purchase_order/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInit,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '' +
                        '<div class="kt-user-card-v2">' +
                        '<div class="kt-user-card-v2__pic">';
                    output += '<span class="kt-badge ' + row.KT_Class + ' kt-badge--xl">' + row.Name.charAt(0) + '</span>';
                    output += '</div>' +
                        '<div class="kt-user-card-v2__details">';
                    output += '<a href="#" class="kt-user-card-v2__name">' + row.Name + '</a>';
                    output += '</div></div>';

                    output = row.Name;
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Invoice Number',
                template: function (row) {
                    var output = row.Number;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Number + '</div>';
                    return output;
                }
            }, {
                field: 'Date',
                title: 'Date',
                template: function (row) {
                    var output = row.Date;
                    if (row.Cancelled == '1')
                        output = '<div style="text-decoration: line-through;">' + row.Date + '</div>';
                    return output;
                }
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Status',
                title: 'Status',
                template: function (row) {
                    var status = {
                        0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
                        1: { 'title': 'Completed', 'class': ' kt-badge--success' },
                        2: { 'title': 'Partial', 'class': ' kt-badge--warning' },
                        9: { 'title': 'Cancelled', 'class': ' kt-badge--danger' },
                    };
                    return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/purchase_order/retrieve_item.php?id=' + e.data.Number,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Received + '</strong> out of <strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }],
            });
        }

    };

    var purchase = function () {

        managePurchaseInvoiceTable = $('#purchase_invoice_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/purchase_invoice/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            toolbar: {
                layout: ['pagination', 'info'],
                placement: ['bottom'],
                items: {
                    pagination: {
                        pageSizeSelect: [10, 25, 50, 100, 250, 500],
                    },
                },
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInit,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'RecordID2',
                title: '',
                template: '{{RecordID2}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '';
                    output = '<a href="?page=supplier_ledger&id=' + row.Supplier_ID + '" target="_blank">' + row.Name + '</a>';
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Invoice Number',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Status',
                title: 'Status',
                template: function (row) {
                    var status = {
                        0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
                        1: { 'title': 'Completed', 'class': ' kt-badge--success' },
                        2: { 'title': 'Partial', 'class': ' kt-badge--warning' },
                    };
                    return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/purchase_invoice/retrieve_item.php?id=' + e.data.ID,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.ID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }],
            });
        }

    };


    var purchase_quotation = function () {

        managePurchaseQuotationTable = $('#purchase_quotation_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/purchase_quotation/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            toolbar: {
                layout: ['pagination', 'info'],
                placement: ['bottom'],
                items: {
                    pagination: {
                        pageSizeSelect: [10, 25, 50, 100, 250, 500],
                    },
                },
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInit,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'RecordID2',
                title: '',
                template: '{{RecordID2}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '';
                    output = '<a href="?page=supplier_ledger&id=' + row.Supplier_ID + '" target="_blank">' + row.Name + '</a>';
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Quotation Number',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Amount',
                title: 'Amount',
             }, //{
            //     field: 'Status',
            //     title: 'Status',
            //     template: function (row) {
            //         var status = {
            //             0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
            //             1: { 'title': 'Completed', 'class': ' kt-badge--success' },
            //             2: { 'title': 'Partial', 'class': ' kt-badge--warning' },
            //         };
            //         return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
            //     },
            // }, 
            {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/purchase_quotation/retrieve_item.php?id=' + e.data.ID,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.ID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return row.Tax + ' %';
                    }
                }],
            });
        }

    };









    var secondary_purchase = function () {

        manageSecondaryPurchaseInvoiceTable = $('#secondary_purchase_invoice_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/purchase_invoice/retrieve_secondary.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInit,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'RecordID2',
                title: '',
                template: '{{RecordID2}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '';
                    output = '<a href="?page=supplier_ledger&id=' + row.Supplier_ID + '" target="_blank">' + row.Name + '</a>';
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Invoice Number',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Status',
                title: 'Status',
                template: function (row) {
                    var status = {
                        0: { 'title': 'Pending', 'class': 'kt-badge--primary' },
                        1: { 'title': 'Completed', 'class': ' kt-badge--success' },
                        2: { 'title': 'Partial', 'class': ' kt-badge--warning' },
                    };
                    return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/purchase_invoice/retrieve_item.php?id=' + e.data.ID,
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.ID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong>';
                    },
                }, {
                    field: 'Price',
                    title: 'Rate',
                }, {
                    field: 'Discount',
                    title: 'Discount',
                    template: function (row) {
                        var output = '';
                        if (row.Discount != '')
                            output += row.Discount + ' %';

                        return output;
                    },
                }, {
                    field: 'HSN',
                    title: 'HSN',
                }, {
                    field: 'Tax',
                    title: 'Tax',
                    template: function (row) {
                        return 0;
                    }
                }],
            });
        }

    };

    var purchase_ledger = function () {

        manageSalesLedgerTable = $('#purchase_ledger_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/reports/retreive_purchase_ledger.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = row.Name;
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Invoice Number',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Tax',
                title: 'Tax',
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var payments = function () {

        managePaymentsTable = $('#payments_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/payments/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            stateSave: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'ID',
                title: 'SN',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Supplier',
                title: 'Supplier',
            }, {
                field: 'Purchase_Invoice',
                title: 'Purchase Invoices',
            }, {
                field: 'Mode',
                title: 'Mode',
            }, {
                field: 'Amount',
                title: 'Amount',
            }, {
                field: 'Actions',
                title: 'Actions',
            }],

        });

    };

    var assemblies = function () {

        manageAssembliesTable = $('#assemblies_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/assemblies/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '',
                template: '{{RecordID}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Composite',
                title: 'Composite',
            }, {
                field: 'Spares',
                title: 'Spares',
            }, {
                field: 'Log_user',
                title: 'User',
                template: function (row) {
                    return row.Log_user + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
            }],

        });

    };

    var materials_received = function () {

        manageMaterialsReceivedTable = $('#materials_received_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/materials_received/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            detail: {
                title: 'Load products',
                content: subTableInitQ,
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '',
                width: 20,
                textAlign: 'center',
            }, {
                field: 'Supplier',
                title: 'Supplier',
                width: 200,
                textAlign: 'center',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Log_user',
                title: 'User',
                template: function (row) {
                    return row.Log_user + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

        function subTableInitQ(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '../assets/custom/materials_received/retrieve_item.php?id=' + e.data.ID,

                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 300,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [{
                    field: 'RecordID',
                    title: '#',
                    sortable: false,
                    width: 30,
                }, {
                    field: 'Product',
                    title: 'Product',
                    template: function (row) {
                        return '<strong>' + row.Product + '</strong><br/><span class="form-text text-muted" style="font-size: 9px;">' + row.Description;
                    },
                }, {
                    field: 'Quantity',
                    title: 'Quantity',
                    template: function (row) {
                        return '<strong>' + row.Quantity + '</strong><br/>' + row.Unit;
                    },
                }, {
                    field: 'Rate',
                    title: 'Rate',
                    template: function (row) {
                        return row.Rate ? '<strong>' + row.Rate + '</strong>' : '-';
                    },
                }],
            });
        }

    };

    var assemblies_operation = function () {

        manageAssembliesOperationTable = $('#assemblies_operation_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/assemblies/retrieve_operation.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '',
                width: 20,
                textAlign: 'center',
            }, {
                field: 'composite',
                title: 'Composite',
            }, {
                field: 'spares',
                title: 'Spares',
            }, {
                field: 'operation',
                title: 'Operation',
            }, {
                field: 'quantity',
                title: 'Quantity',
            }, {
                field: 'log_user',
                title: 'User',
                template: function (row) {
                    return row.log_user + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.log_date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
            }],

        });

    };

    var sales_assemblies_operation = function () {

        manageSalesAssembliesOperationTable = $('#sales_invoice_assembly_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/assemblies/retrieve_sales_operation.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '',
                width: 20,
                textAlign: 'center',
            }, {
                field: 'composite',
                title: 'Composite',
            }, {
                field: 'spares',
                title: 'Spares',
            }, {
                field: 'operation',
                title: 'Operation',
            }, {
                field: 'quantity',
                title: 'Quantity',
            }, {
                field: 'log_user',
                title: 'User',
                template: function (row) {
                    return row.log_user + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.log_date;
                },
            }],

        });

    };

    var pd_timeline = function () {

        managePDTimelineTable = $('#pd_timeline').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product_details/retrieve_timeline.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#search_pd_timeline'),
            },

            // columns definition
            columns: [{
                field: 'Date',
                title: 'Date',
                width: 80,
            }, {
                field: 'Masters',
                title: 'Masters',
                template: function (row) {
                    var output = row.Masters + '<br>' + row.Reference;
                    return output;
                },

            }, {
                field: 'Type',
                title: 'Type',
                textAlign: 'center',
                width: 100
            }, {
                field: 'Qty',
                title: 'Qty',
                textAlign: 'center',
                width: 40,
            }, {
                field: 'Rate',
                title: 'Amount',
                textAlign: 'center',
            }],
        });
    };

    var pd_purchase = function () {

        managePDPurchaseTable = $('#pd_purchase').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product_details/retrieve_purchase.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#search_pd_purchase'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: 'SN',
                template: '{{SN}}',
                width: 20,
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'PI_Date',
                title: 'Date',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Supplier',
                title: 'Supplier',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'PI',
                title: 'Invoice #',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Qty',
                title: 'Quantity',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Rate',
                title: 'Rate',
                textAlign: 'left',
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var pd_sales = function () {

        managePDSalesTable = $('#pd_sales').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product_details/retrieve_sales.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#search_pd_sales'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: 'SN',
                template: '{{SN}}',
                width: 20,
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'SI_Date',
                title: 'Date',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Client',
                title: 'Client',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'SI',
                title: 'Invoice #',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Qty',
                title: 'Quantity',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Rate',
                title: 'Rate',
                textAlign: 'left',
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var pd_quotation = function () {

        managePDQuotationTable = $('#pd_quotation').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product_details/retrieve_quotation.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#search_pd_quotation'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: 'SN',
                template: '{{SN}}',
                width: 20,
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Q_Date',
                title: 'Date',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Client',
                title: 'Client',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'QN',
                title: 'Quotation #',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Qty',
                title: 'Quantity',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Rate',
                title: 'Rate',
                textAlign: 'left',
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var pd_enquiry = function () {

        managePDEnquiryTable = $('#pd_enquiry').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product_details/retrieve_enquiry.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#search_pd_enquiry'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: 'SN',
                template: '{{SN}}',
                width: 20,
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'E_Date',
                title: 'Date',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Client',
                title: 'Client',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'EN',
                title: 'Enquiry #',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Qty',
                title: 'Quantity',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var moq = function () {

        manageMOQTable = $('#moq_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/moq/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 500,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: false,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: 'asc',
                width: 15,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                width: 250,
                template: function (row) {
                    // return '<strong><a target="_top" href="?page=product_details&pr=' + row.Name + '"  >' + row.Name + '</a></strong><br/><span class="form-text text-muted" style="font-size: 12px;">' + row.Description + '</span><span class="form-text text-muted" style="font-size: 12px;">' + row.Alias + '</span>';
                    return row.Name + '<br/><span class="form-text text-muted" style="font-size: 12px;">' + row.Description + '</span><span class="form-text text-muted" style="font-size: 12px;">' + row.Alias + '</span>';
                },
            }, {
                field: 'Group',
                title: 'Group',
                width: 75,
                template: function (row) {
                    return '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">' + row.Group + '</span>';
                },
                textAlign: 'center',
            }, {
                field: 'Category',
                title: 'Category',
                textAlign: 'center',
            }, {
                field: 'Sub-Category',
                title: 'Sub-Category',
                textAlign: 'center',
            }, {
                field: 'Rate',
                title: 'Sale Price',
                textAlign: 'center',
                template: function (row) {
                    return 'Rs. ' + row.Rate;
                },
            }, {
                field: 'HSN',
                title: 'HSN',
                textAlign: 'center',
            }, {
                field: 'Unit',
                title: 'Current Stock',
                textAlign: 'center',
                template: function (row) {
                    var stock = row.Opening_stock + ' ' + row.Unit;
                    return '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill">' + stock + '</span>';

                },
            },
            {
                field: 'MOQ',
                title: 'MOQ',
                textAlign: 'center',
            },
                // {
                //     field: 'Actions',
                //     title: 'Actions',
                //     width: 80,
                //     textAlign: 'center',
                //     overflow: 'visible',
                //     autoHide: false,
                // }
            ],


        });

    };

    var credit_note = function () {

        manageCreditNoteTable = $('#credit_note_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/credit_note/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            // detail: {
            //     title: 'Load products',
            //     content: subTableInit,
            // },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'RecordID2',
                title: '',
                template: '{{RecordID2}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '';
                    output = '<a href="?page=client_ledger&id=' + row.Client_ID + '" target="_blank">' + row.Name + '</a>';
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Credit Note #',
            }, {
                field: 'Sales_No',
                title: 'Sales Invoice #',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Amount',
                title: 'Amount',
            },
            {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var debit_note = function () {

        manageDebitNoteTable = $('#debit_note_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/debit_note/retrieve.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            // detail: {
            //     title: 'Load products',
            //     content: subTableInit,
            // },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '',
                sortable: false,
                width: 30,
                textAlign: 'center',
            }, {
                field: 'RecordID2',
                title: '',
                template: '{{RecordID2}}',
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: 'Name',
                title: 'Name',
                template: function (row) {
                    var output = '';
                    output = '<a href="?page=supplier_ledger&id=' + row.Supplier_ID + '" target="_blank">' + row.Name + '</a>';
                    return output;
                },
            }, {
                field: 'Number',
                title: 'Debit Note #',
            }, {
                field: 'Purchase_No',
                title: 'Purchase Invoice #',
            }, {
                field: 'Date',
                title: 'Date',
            }, {
                field: 'Amount',
                title: 'Amount',
            },
            {
                field: 'User',
                title: 'User',
                template: function (row) {
                    return row.User + '<br/><span class="form-text text-muted" style="font-size: 11px;">' + row.Log_Date;
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var pd_credit_note = function () {

        managePDCreditNoteTable = $('#pd_credit_note').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product_details/retrieve_credit_note.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#search_pd_credit_note'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: 'SN',
                template: '{{SN}}',
                width: 20,
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'CN_Date',
                title: 'Date',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Client',
                title: 'Client',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'CN',
                title: 'Credit Note #',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Qty',
                title: 'Quantity',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Rate',
                title: 'Rate',
                textAlign: 'left',
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var pd_debit_note = function () {

        managePDDebitNoteTable = $('#pd_debit_note').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/product_details/retrieve_debit_note.php',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: false,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            // column sorting
            sortable: false,

            pagination: true,

            search: {
                input: $('#search_pd_debit_note'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: 'SN',
                template: '{{SN}}',
                width: 20,
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'DN_Date',
                title: 'Date',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Supplier',
                title: 'Supplier',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'DN',
                title: 'Debit Note #',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Qty',
                title: 'Quantity',
                textAlign: 'center',
                overflow: 'visible',
                autoHide: false,
            }, {
                field: 'Rate',
                title: 'Rate',
                textAlign: 'left',
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var contra = function () {

        manageContraTable = $('#contra_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/contra/retrieve.php',
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 20,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            rows: {
                autoHide: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: false,
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: "Date",
                title: "Date",
            }, {
                field: 'Transfer_from',
                title: 'Transfer From',

            }, {
                field: 'Transfer_to',
                title: 'Transfer To',

            }, {
                field: 'Amount',
                title: 'Amount',

            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    var journal = function () {

        manageJournalTable = $('#journal_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '../assets/custom/journal/retrieve.php',
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 20,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },

            // layout definition
            layout: {
                scroll: true,
                footer: false,
            },

            rows: {
                autoHide: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                field: 'SN',
                title: '#',
                sortable: false,
                width: 20,
                selector: {
                    class: 'kt-checkbox--solid'
                },
                textAlign: 'center',
            }, {
                field: "Id",
                title: "ID",
            }, {
                field: "date",
                title: "Date",
            }, {
                field: 'entry',
                title: 'Entry',

            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 110,
                overflow: 'visible',
                autoHide: false,
            }],

        });

    };

    return {
        init: function () {
            dashboard();
            archived();
            products();
            clients();
            suppliers();
            users();
            bank();
            quotation();
            enquiry();
            purchase_bag();
            purchase_order();
            purchase();
            purchase_quotation();
            secondary_purchase();
            purchase_ledger();
            sales_order();
            proforma();
            sales();
            sales_secondary();
            sales_secondary_items();
            sales_ledger();
            payments();
            receipts();
            assemblies();
            assemblies_operation();
            pd_purchase();
            pd_sales();
            pd_quotation();
            pd_enquiry();
            payment_followup();
            pd_timeline();
            materials_received();
            credit_note();
            debit_note();
            pd_credit_note();
            pd_debit_note();
            contra();
            sales_assemblies_operation();
            journal();
            product_history();
            client_history();
            // moq();
        },
    };
}();

var FormRepeater = function () {

    var enquiry = function () {

        var options_repeater_en = jQuery('#kt_repeater_enquiry');

        options_repeater_en.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.e_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    tags: true,
                    allowClear: true
                });

                $('.e_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.e_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='enquiry[" + name + "][e_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='enquiry[" + name + "][e_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='enquiry[" + name + "][e_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='enquiry[" + name + "][e_tax]']";
                            $(temp).val(response.tax).trigger("change");

                            temp = "textarea[name$='enquiry[" + name + "][e_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);

                        } // /success
                    }); // /fetch selected member info

                });

                $('#enq_btn_add').on("click", function (e) { e_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.e_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    tags: true,
                    allowClear: true
                });

                $('.e_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.e_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='enquiry[" + name + "][e_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='enquiry[" + name + "][e_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='enquiry[" + name + "][e_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='enquiry[" + name + "][e_tax]']";
                            $(temp).val(response.tax).trigger("change");


                        } // /success
                    }); // /fetch selected member info
                });

                $('#enq_btn_add').on("click", function (e) { e_preview(e); });
            },
            isFirstItemUndeletable: true
        });

        jQuery("#enquiry_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var quotation = function () {

        var options_repeater_q = jQuery('#kt_repeater_q');

        options_repeater_q.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.q_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    tags: true,
                    allowClear: true
                });

                $('.q_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true
                });

                $('.q_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    tags: true
                });

                $('.q_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make'
                });

                $('.q_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "span[name$='quotation[" + name + "][product_stock]']";
                            $(temp).html('<span style="padding-left: 5px; font-weight: 500;">Stock: ' + response + '</span>');
                        } // Success
                    }); // Fetch selected member info

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='quotation[" + name + "][q_rate]']";
                            $(temp).val(response.rate);
                            temp = "select[name$='quotation[" + name + "][q_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "input[name$='quotation[" + name + "][q_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='quotation[" + name + "][q_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='quotation[" + name + "][q_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='quotation[" + name + "][q_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "textarea[name$='quotation[" + name + "][q_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);

                        } // /success
                    }); // /fetch selected member info
                });

                $(".q_qty").change(function (e) { q_preview(e); });
                $(".q_rate").change(function (e) { q_preview(e); });
                $(".q_dsc").change(function (e) { q_preview(e); });
                $('.q_tax-select2').on("select2:select", function (e) { q_preview(e); });
                $("#q_freight").change(function (e) { q_preview(e); });
                $("#q_pf").change(function (e) { q_preview(e); });
                $("#q_tot_discount").change(function (e) { q_preview(e); });
                $("#q_round").change(function (e) { q_preview(e); });
                $('#qtn_btn_add').on("click", function (e) { q_preview(e); });
                $('[data-ktwizard-type="action-next"]').on("click", function (e) { q_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.q_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    tags: true,
                    allowClear: true
                });

                $('.q_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true
                });

                $('.q_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    tags: true
                });

                $('.q_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make'
                });

                $('.q_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "span[name$='quotation[" + name + "][product_stock]']";
                            $(temp).html('<span style="padding-left: 5px; font-weight: 500;">Stock: ' + response + '</span>');
                        } // Success
                    }); // Fetch selected member info

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='quotation[" + name + "][q_rate]']";
                            $(temp).val(response.rate);
                            temp = "select[name$='quotation[" + name + "][q_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "input[name$='quotation[" + name + "][q_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='quotation[" + name + "][q_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='quotation[" + name + "][q_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='quotation[" + name + "][q_display_make]']";
                            $(temp).val(response.default_make).trigger("change");

                        } // /success
                    }); // /fetch selected member info
                });

                $(".q_qty").change(function (e) { q_preview(e); });
                $(".q_rate").change(function (e) { q_preview(e); });
                $(".q_dsc").change(function (e) { q_preview(e); });
                $('.q_tax-select2').on("select2:select", function (e) { q_preview(e); });
                $("#q_freight").change(function (e) { q_preview(e); });
                $("#q_pf").change(function (e) { q_preview(e); });
                $("#q_tot_discount").change(function (e) { q_preview(e); });
                $("#q_round").change(function (e) { q_preview(e); });
                $('#qtn_btn_add').on("click", function (e) { q_preview(e); });
                $('[data-ktwizard-type="action-next"]').on("click", function (e) { q_preview(e); });
            },
            isFirstItemUndeletable: false
        });

        jQuery("#quotation_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var sales_order = function () {

        var options_repeater_so = jQuery('#kt_repeater_so');

        options_repeater_so.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.so_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.so_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true,
                    tags: true,
                });

                $('.so_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.so_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.so_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "span[name$='sales_order[" + name + "][product_stock]']";
                            $(temp).html('<span style="padding-left: 5px; font-weight: 500;">Stock: ' + response + '</span>');
                        } // Success
                    }); // Fetch selected member info

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='sales_order[" + name + "][so_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='sales_order[" + name + "][so_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='sales_order[" + name + "][so_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='sales_order[" + name + "][so_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='sales_order[" + name + "][so_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='sales_order[" + name + "][so_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "textarea[name$='sales_order[" + name + "][so_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);

                            var tmp = "input[name$='sales_order[" + name + "][so_display_make][]']";
                            console.log(tmp);
                            if (response.default_make == 1)
                                $(tmp).attr('checked', 'checked');
                            else
                                $(tmp).removeAttr('checked');

                        } // /success
                    }); // /fetch selected member info
                });

                $(".so_qty").change(function (e) { so_preview(e); });
                $(".so_rate").keyup(function (e) { so_preview(e); });
                $(".so_dsc").keyup(function (e) { so_preview(e); });
                $('.so_tax-select2').on("select2:select", function (e) { so_preview(e); });
                $(".so_delete").click(function (e) { so_preview(e); });
                $("#so_freight").change(function (e) { so_preview(e); });
                $("#so_pf").change(function (e) { so_preview(e); });
                $("#so_tot_discount").change(function (e) { so_preview(e); });
                $("#so_round").change(function (e) { so_preview(e); });
                $('#so_btn_add').on("click", function (e) { so_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.so_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.so_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true,
                    tags: true,
                });

                $('.so_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.so_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.so_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "span[name$='sales_order[" + name + "][product_stock]']";
                            $(temp).html('<span style="padding-left: 5px; font-weight: 500;">Stock: ' + response + '</span>');
                        } // Success
                    }); // Fetch selected member info

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='sales_order[" + name + "][so_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='sales_order[" + name + "][so_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='sales_order[" + name + "][so_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='sales_order[" + name + "][so_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='sales_order[" + name + "][so_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='sales_order[" + name + "][so_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");

                        } // /success
                    }); // /fetch selected member info
                });

                $(".so_qty").change(function (e) { so_preview(e); });
                $(".so_rate").keyup(function (e) { so_preview(e); });
                $(".so_dsc").keyup(function (e) { so_preview(e); });
                $('.so_tax-select2').on("select2:select", function (e) { so_preview(e); });
                $(".so_delete").click(function (e) { so_preview(e); });
                $("#so_freight").change(function (e) { so_preview(e); });
                $("#so_pf").change(function (e) { so_preview(e); });
                $("#so_tot_discount").change(function (e) { so_preview(e); });
                $("#so_round").change(function (e) { so_preview(e); });
                $('#so_btn_add').on("click", function (e) { so_preview(e); });

            },
            isFirstItemUndeletable: true
        });

        jQuery("#sales_order_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var credit_note = function () {

        var options_repeater_so = jQuery('#kt_repeater_cn');

        options_repeater_so.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.cn_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.cn_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.cn_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.cn_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.cn_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='credit_note[" + name + "][cn_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='credit_note[" + name + "][cn_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='credit_note[" + name + "][cn_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='credit_note[" + name + "][cn_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='credit_note[" + name + "][cn_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='credit_note[" + name + "][cn_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "textarea[name$='credit_note[" + name + "][cn_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);

                            var tmp = "input[name$='credit_note[" + name + "][cn_display_make][]']";
                            if (response.default_make == 1)
                                $(tmp).attr('checked', 'checked');
                            else
                                $(tmp).removeAttr('checked');

                        } // /success
                    }); // /fetch selected member info
                });

                $(".cn_qty").change(function (e) { cn_preview(e); });
                $(".cn_rate").keyup(function (e) { cn_preview(e); });
                $(".cn_dsc").keyup(function (e) { cn_preview(e); });
                $('.cn_tax-select2').on("select2:select", function (e) { cn_preview(e); });
                $(".cn_delete").click(function (e) { cn_preview(e); });
                $("#cn_freight").change(function (e) { cn_preview(e); });
                $("#cn_pf").change(function (e) { cn_preview(e); });
                $("#cn_tot_discount").change(function (e) { cn_preview(e); });
                $('#cn_btn_add').on("click", function (e) { cn_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.cn_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.cn_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.cn_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.cn_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.cn_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='credit_note[" + name + "][cn_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='credit_note[" + name + "][cn_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='credit_note[" + name + "][cn_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='credit_note[" + name + "][cn_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='credit_note[" + name + "][cn_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='credit_note[" + name + "][cn_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");

                        } // /success
                    }); // /fetch selected member info
                });

                $(".cn_qty").change(function (e) { cn_preview(e); });
                $(".cn_rate").keyup(function (e) { cn_preview(e); });
                $(".cn_dsc").keyup(function (e) { cn_preview(e); });
                $('.cn_tax-select2').on("select2:select", function (e) { cn_preview(e); });
                $(".cn_delete").click(function (e) { cn_preview(e); });
                $("#cn_freight").change(function (e) { cn_preview(e); });
                $("#cn_pf").change(function (e) { cn_preview(e); });
                $("#cn_tot_discount").change(function (e) { cn_preview(e); });
                $('#cn_btn_add').on("click", function (e) { cn_preview(e); });

            },
            isFirstItemUndeletable: true
        });

        jQuery("#credit_note_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var debit_note = function () {

        var options_repeater_so = jQuery('#kt_repeater_dn');

        options_repeater_so.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.dn_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.dn_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.dn_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.dn_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.dn_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='debit_note[" + name + "][dn_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='debit_note[" + name + "][dn_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='debit_note[" + name + "][dn_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='debit_note[" + name + "][dn_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='debit_note[" + name + "][dn_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='debit_note[" + name + "][dn_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "textarea[name$='debit_note[" + name + "][dn_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);

                            var tmp = "input[name$='debit_note[" + name + "][dn_display_make][]']";
                            console.log(tmp);
                            if (response.default_make == 1)
                                $(tmp).attr('checked', 'checked');
                            else
                                $(tmp).removeAttr('checked');

                        } // /success
                    }); // /fetch selected member info
                });

                $(".dn_qty").change(function (e) { dn_preview(e); });
                $(".dn_rate").keyup(function (e) { dn_preview(e); });
                $(".dn_dsc").keyup(function (e) { dn_preview(e); });
                $('.dn_tax-select2').on("select2:select", function (e) { dn_preview(e); });
                $(".dn_delete").click(function (e) { dn_preview(e); });
                $("#dn_freight").change(function (e) { dn_preview(e); });
                $("#dn_pf").change(function (e) { dn_preview(e); });
                $("#dn_tot_discount").change(function (e) { dn_preview(e); });
                $('#dn_btn_add').on("click", function (e) { dn_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.dn_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.dn_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.dn_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.dn_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.dn_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='debit_note[" + name + "][dn_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='debit_note[" + name + "][dn_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='debit_note[" + name + "][dn_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='debit_note[" + name + "][dn_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='debit_note[" + name + "][dn_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='debit_note[" + name + "][dn_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");

                        } // /success
                    }); // /fetch selected member info
                });

                $(".dn_qty").change(function (e) { dn_preview(e); });
                $(".dn_rate").keyup(function (e) { dn_preview(e); });
                $(".dn_dsc").keyup(function (e) { dn_preview(e); });
                $('.dn_tax-select2').on("select2:select", function (e) { dn_preview(e); });
                $(".dn_delete").click(function (e) { dn_preview(e); });
                $("#dn_freight").change(function (e) { dn_preview(e); });
                $("#dn_pf").change(function (e) { dn_preview(e); });
                $("#dn_tot_discount").change(function (e) { dn_preview(e); });
                $('#dn_btn_add').on("click", function (e) { dn_preview(e); });

            },
            isFirstItemUndeletable: true
        });

        jQuery("#debit_note_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var proforma_invoice = function () {

        var options_repeater_pr = jQuery('#kt_repeater_pr');

        options_repeater_pr.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.pr_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true,
                    tags: true
                });

                $('.pr_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true,
                    tags: true
                });

                $('.pr_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.pr_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.pr_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "span[name$='proforma_invoice[" + name + "][product_stock]']";
                            $(temp).html('<span style="padding-left: 5px; font-weight: 500;">Stock: ' + response + '</span>');
                        } // Success
                    }); // Fetch selected member info

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='proforma_invoice[" + name + "][pr_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='proforma_invoice[" + name + "][pr_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='proforma_invoice[" + name + "][pr_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='proforma_invoice[" + name + "][pr_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='proforma_invoice[" + name + "][pr_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='proforma_invoice[" + name + "][pr_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "textarea[name$='proforma_invoice[" + name + "][pr_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);

                            var tmp = "input[name$='proforma_invoice[" + name + "][pr_display_make][]']";
                            console.log(tmp);
                            if (response.default_make == 1)
                                $(tmp).attr('checked', 'checked');
                            else
                                $(tmp).removeAttr('checked');

                        } // /success
                    }); // /fetch selected member info
                });

                $(".pr_qty").change(function (e) { pr_preview(e); });
                $(".pr_rate").keyup(function (e) { pr_preview(e); });
                $(".pr_dsc").keyup(function (e) { pr_preview(e); });
                $('.pr_tax-select2').on("select2:select", function (e) { pr_preview(e); });
                $(".pr_delete").click(function (e) { pr_preview(e); });
                $("#pr_freight").change(function (e) { pr_preview(e); });
                $("#pr_pf").change(function (e) { pr_preview(e); });
                $("#pr_tot_discount").change(function (e) { pr_preview(e); });
                $("#pr_round").change(function (e) { pr_preview(e); });
                $('#pr_btn_add').on("click", function (e) { pr_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.pr_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true,
                    tags: true
                });

                $('.pr_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true,
                    tags: true
                });

                $('.pr_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.pr_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.pr_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "span[name$='proforma_invoice[" + name + "][product_stock]']";
                            $(temp).html('<span style="padding-left: 5px; font-weight: 500;">Stock: ' + response + '</span>');
                        } // Success
                    }); // Fetch selected member info

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='proforma_invoice[" + name + "][pr_rate]']";
                            $(temp).val(response.rate);
                            temp = "input[name$='proforma_invoice[" + name + "][pr_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='proforma_invoice[" + name + "][pr_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='proforma_invoice[" + name + "][pr_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='proforma_invoice[" + name + "][pr_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='proforma_invoice[" + name + "][pr_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");

                        } // /success
                    }); // /fetch selected member info
                });

                $(".pr_qty").change(function (e) { pr_preview(e); });
                $(".pr_rate").keyup(function (e) { pr_preview(e); });
                $(".pr_dsc").keyup(function (e) { pr_preview(e); });
                $('.pr_tax-select2').on("select2:select", function (e) { pr_preview(e); });
                $(".pr_delete").click(function (e) { pr_preview(e); });
                $("#pr_freight").change(function (e) { pr_preview(e); });
                $("#pr_pf").change(function (e) { pr_preview(e); });
                $("#pr_tot_discount").change(function (e) { pr_preview(e); });
                $("#pr_round").change(function (e) { pr_preview(e); });
                $('#pr_btn_add').on("click", function (e) { pr_preview(e); });

            },
            isFirstItemUndeletable: true
        });

        jQuery("#proforma_invoice_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var sales_invoice = function () {

        var options_repeater_si = jQuery('#kt_repeater_si');

        options_repeater_si.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.si_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.si_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true,
                    tags: true

                });

                $('.si_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.si_adjustment-select2').select2({
                    width: '100%',
                    placeholder: 'Adj',
                });

                $('.si_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true
                });

                $('.si_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "span[name$='sales_invoice[" + name + "][product_stock]']";
                            $(temp).html('<span style="padding-left: 5px; font-weight: 500;">Stock: ' + response + '</span>');
                        } // Success
                    }); // Fetch selected member info

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='sales_invoice[" + name + "][si_rate]']";
                            $(temp).val(response.rate);
                            temp = "textarea[name$='sales_invoice[" + name + "][si_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='sales_invoice[" + name + "][si_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='sales_invoice[" + name + "][si_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='sales_invoice[" + name + "][si_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='sales_invoice[" + name + "][si_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "textarea[name$='sales_invoice[" + name + "][si_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);
                        } // /success
                    }); // /fetch selected member info
                });

                $(".si_qty").keyup(function (e) { si_preview(e); });
                $(".si_rate").keyup(function (e) { si_preview(e); });
                $(".si_dsc").keyup(function (e) { si_preview(e); });
                //$("#si_round").keyup(function (e) { si_preview(e); });
                $('.si_tax-select2').on("select2:select", function (e) { si_preview(e); });
                $(".si_delete").click(function (e) { si_preview(e); });
                $("#si_freight").change(function (e) { si_preview(e); });
                $("#si_pf").change(function (e) { si_preview(e); });
                $("#si_tot_discount").change(function (e) { si_preview(e); });
                $("#si_round").change(function(e) { si_preview(e); });
                $('#si_btn_add').on("click", function (e) { si_preview(e); });
                $('[data-ktwizard-type="action-next"]').on("click", function (e) { si_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.si_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.si_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true,
                    tags: true

                });

                $('.si_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.si_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true
                });

                $('.si_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "span[name$='sales_invoice[" + name + "][product_stock]']";
                            $(temp).html('<span style="padding-left: 5px; font-weight: 500;">Stock: ' + response + '</span>');
                        } // Success
                    }); // Fetch selected member info

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='sales_invoice[" + name + "][si_rate]']";
                            $(temp).val(response.rate);
                            temp = "textarea[name$='sales_invoice[" + name + "][si_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='sales_invoice[" + name + "][si_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='sales_invoice[" + name + "][si_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='sales_invoice[" + name + "][si_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='sales_invoice[" + name + "][si_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");

                        } // /success
                    }); // /fetch selected member info
                });

                $(".si_qty").keyup(function (e) { si_preview(e); });
                $(".si_rate").keyup(function (e) { si_preview(e); });
                $(".si_dsc").keyup(function (e) { si_preview(e); });
               // $("#si_round").keyup(function (e) { si_preview(e); });
                $('.si_tax-select2').on("select2:select", function (e) { si_preview(e); });
                $(".si_delete").click(function (e) { si_preview(e); });
                $("#si_freight").change(function (e) { si_preview(e); });
                $("#si_pf").change(function (e) { si_preview(e); });
                $("#si_tot_discount").change(function (e) { si_preview(e); });
                $("#si_round").change(function(e) { si_preview(e); });
                $('#si_btn_add').on("click", function (e) { si_preview(e); });
                $('[data-ktwizard-type="action-next"]').on("click", function (e) { si_preview(e); });
            },
            isFirstItemUndeletable: false
        });

        jQuery("#sales_invoice_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var receipt = function () {

        $('#kt_repeater_rc').repeater({
            initEmpty: false,

            show: function () {
                $(this).slideDown();

                $(".rc_completed").change(function (e) {
                    if (e.handled !== true) {
                        e.handled = true;

                        if (this.checked) {

                            var total = $('#amount').val();
                            var collected = 0;

                            console.log("Total : " + total);

                            var rep = document.getElementById('receipt_list');
                            var rowsCount = rep.childNodes.length;

                            for (var i = 0; i < rowsCount; i++) {
                                var tmp = "input[name$='receipt[" + i + "][rc_amount]']";
                                var temp = $(tmp).val();
                                if (temp != '') {
                                    temp = temp.replace(',', '');
                                    temp = parseFloat(temp);
                                    collected += temp;
                                }
                                console.log("Collected : " + i + "  " + collected);

                            }
                            total = total - collected;
                            var total_pending = total;

                            var name = e.currentTarget.name;
                            if (name != null) {
                                var start = name.indexOf("[");
                                var end = name.indexOf("]");
                                start += 1;
                                name = name.substring(start, end);

                                tmp = "input[name$='receipt[" + name + "][rc_due]']";
                                var due = $(tmp).val();

                                due = due.replace(/,/g, '');
                                due = parseFloat(due);

                                tmp = "input[name$='receipt[" + name + "][rc_amount]']";
                                if (due <= total) {
                                    $(tmp).val(due.toFixed(2));
                                    total_pending -= due;
                                } else {
                                    $(tmp).val(total.toFixed(2));
                                    total_pending -= total;
                                }
                            }

                            $('#rc_amount_entered').text('Total Pending: ' + total_pending.toFixed(2));
                        } else {
                            var name = e.currentTarget.name;
                            if (name != null) {
                                var start = name.indexOf("[");
                                var end = name.indexOf("]");
                                start += 1;
                                name = name.substring(start, end);

                                tmp = "input[name$='receipt[" + name + "][rc_amount]']";
                                $(tmp).val('');
                            }
                        }

                        return;
                    }

                });

                // $(".rc_amount").keyup(function(e) { rc_preview(e); });
                // $('#rc_btn_add').on("click", function(e) { rc_preview(e); });

            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {

                $(".rc_completed").change(function (e) {
                    console.log("Changed");
                });

            }
        });
    }

    var purchase_order = function () {

        var options_repeater_po = jQuery('#kt_repeater_po');

        options_repeater_po.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.po_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.po_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.po_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.po_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.po_product_name-select2').on("change", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='purchase_order[" + name + "][po_rate]']";
                            $(temp).val(response.cost);
                            temp = "input[name$='purchase_order[" + name + "][po_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='purchase_order[" + name + "][po_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='purchase_order[" + name + "][po_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='purchase_order[" + name + "][po_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='purchase_order[" + name + "][po_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "textarea[name$='purchase_order[" + name + "][po_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);
                        } // /success
                    }); // /fetch selected member info
                    // po_preview(e);
                });

                $(".po_qty").keyup(function (e) { po_preview(e); });
                $(".po_rate").keyup(function (e) { po_preview(e); });
                $(".po_dsc").keyup(function (e) { po_preview(e); });
                $('.po_tax-select2').on("select2:select", function (e) { po_preview(e); });
                $(".po_delete").click(function (e) { po_preview(e); });
                $("#po_freight").change(function (e) { po_preview(e); });
                $("#po_pf").change(function (e) { po_preview(e); });
                $("#po_tot_discount").change(function (e) { po_preview(e); });
                $("#po_round").change(function (e) { po_preview(e); });
                // $('#po_btn_add').on("click", function(e) { po_preview(e); });



            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.po_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });

                $('.po_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.po_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.po_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true,
                    allowClear: true
                });

                $('.po_product_name-select2').on("change", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='purchase_order[" + name + "][po_rate]']";
                            $(temp).val(response.cost);
                            temp = "input[name$='purchase_order[" + name + "][po_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='purchase_order[" + name + "][po_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='purchase_order[" + name + "][po_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            temp = "select[name$='purchase_order[" + name + "][po_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='purchase_order[" + name + "][po_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");

                        } // /success
                    }); // /fetch selected member info
                    // po_preview(e);
                });

                $(".po_qty").keyup(function (e) { po_preview(e); });
                $(".po_rate").keyup(function (e) { po_preview(e); });
                $(".po_dsc").keyup(function (e) { po_preview(e); });
                $('.po_tax-select2').on("select2:select", function (e) { po_preview(e); });
                $(".po_delete").click(function (e) { po_preview(e); });
                $("#po_freight").change(function (e) { po_preview(e); });
                $("#po_pf").change(function (e) { po_preview(e); });
                $("#po_tot_discount").change(function (e) { po_preview(e); });
                $("#po_round").change(function (e) { po_preview(e); });
                // $('#po_btn_add').on("click", function(e) { po_preview(e); });
            },
            isFirstItemUndeletable: false
        });

        jQuery("#purchase_order_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var purchase_invoice = function () {

        var options_repeater_pi = jQuery('#kt_repeater_pi');

        options_repeater_pi.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.pi_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });
                $('#purchase_series').select2({
                    width: '100%',
                    placeholder: 'Filter Series',
                    allowClear: true
                });

                $('.pi_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.pi_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.pi_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true
                });

                $('.pi_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            var series = $("#purchase_series").val();
                            console.log("ser", series);

                            temp = "input[name$='purchase_invoice[" + name + "][pi_rate]']";
                            $(temp).val(response.cost);
                            temp = "input[name$='purchase_invoice[" + name + "][pi_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='purchase_invoice[" + name + "][pi_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='purchase_invoice[" + name + "][pi_tax]']";
                            if (series == "SECONDARY")
                                $(temp).val(0).trigger("change");
                            else
                                $(temp).val(response.tax).trigger("change");
                            //console.log("tax2",$(temp).val(response.tax).trigger("change"));
                            temp = "select[name$='purchase_invoice[" + name + "][pi_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                            temp = "select[name$='purchase_invoice[" + name + "][pi_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "textarea[name$='purchase_invoice[" + name + "][pi_product_add_description]']";
                            var temp_textarea = $(temp);
                            autosize(temp_textarea);
                        } // /success
                    }); // /fetch selected member info
                });

                // $(".pi_qty").keyup(function(e) { pi_preview(e); });
                // $(".pi_rate").keyup(function(e) { pi_preview(e); });
                // $(".pi_dsc").keyup(function(e) { pi_preview(e); });
                // $('.pi_tax-select2').on("select2:select", function(e) { pi_preview(e); });
                // $(".pi_delete").click(function(e) { pi_preview(e); });
                // $("#pi_freight").change(function(e) { pi_preview(e); });
                // $("#pi_pf").change(function(e) { pi_preview(e); });
                // $("#pi_tot_discount").change(function(e) { pi_preview(e); });
                // $("#pi_round").change(function(e) { pi_preview(e); });
                // $('#pi_btn_add').on("click", function(e) { pi_preview(e); });

                $('#pi_preview_btn').on("click", function (e) { pi_preview(e); });

            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.pi_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select SKU/Part No',
                    allowClear: true
                });
                $('#kt_si_series').select2({
                    width: '100%',
                    placeholder: 'Filter Series',
                    allowClear: true
                });

                $('.pi_tax-select2').select2({
                    width: '100%',
                    placeholder: 'Tax',
                    allowClear: true
                });

                $('.pi_display_make-select2').select2({
                    width: '100%',
                    placeholder: 'Make',
                });

                $('.pi_unit-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_units.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Unit',
                    tags: true
                });

                $('.pi_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';

                            temp = "input[name$='purchase_invoice[" + name + "][pi_rate]']";
                            $(temp).val(response.cost);
                            temp = "input[name$='purchase_invoice[" + name + "][pi_product_description]']";
                            $(temp).val(response.description);
                            temp = "input[name$='purchase_invoice[" + name + "][pi_hsn]']";
                            $(temp).val(response.hsn);
                            temp = "select[name$='purchase_invoice[" + name + "][pi_tax]']";
                            $(temp).val(response.tax).trigger("change");
                            console.log("tax4", tax);
                            temp = "select[name$='purchase_invoice[" + name + "][pi_unit]']";
                            $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                            temp = "select[name$='purchase_invoice[" + name + "][pi_display_make]']";
                            $(temp).val(response.default_make).trigger("change");
                        } // /success
                    }); // /fetch selected member info
                });

                // $(".pi_qty").keyup(function(e) { pi_preview(e); });
                // $(".pi_rate").keyup(function(e) { pi_preview(e); });
                // $(".pi_dsc").keyup(function(e) { pi_preview(e); });
                // $('.pi_tax-select2').on("select2:select", function(e) { pi_preview(e); });
                // $(".pi_delete").click(function(e) { pi_preview(e); });
                // $("#pi_freight").change(function(e) { pi_preview(e); });
                // $("#pi_pf").change(function(e) { pi_preview(e); });
                // $("#pi_tot_discount").change(function(e) { pi_preview(e); });
                // $("#pi_round").change(function(e) { pi_preview(e); });
                // $('#pi_btn_add').on("click", function(e) { pi_preview(e); });

                $('#pi_preview_btn').on("click", function (e) { pi_preview(e); });

            },
            isFirstItemUndeletable: false
        });

        jQuery("#purchase_invoice_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var payment = function () {

        $('#kt_repeater_py').repeater({
            initEmpty: false,

            show: function () {
                $(this).slideDown();

                $(".py_completed").change(function (e) {
                    if (e.handled !== true) {
                        e.handled = true;

                        if (this.checked) {

                            var total = $('#payment_amount').val();
                            var collected = 0;

                            console.log("Total : " + total);

                            var rep = document.getElementById('payment_list');
                            var rowsCount = rep.childNodes.length;

                            for (var i = 0; i < rowsCount; i++) {
                                var tmp = "input[name$='payment[" + i + "][py_amount]']";
                                var temp = $(tmp).val();
                                if (temp != '') {
                                    temp = temp.replace(',', '');
                                    temp = parseFloat(temp);
                                    collected += temp;
                                }
                                // collected = collected.toFixed(2);
                                console.log("Collected : " + i + "  " + collected.toFixed(2));


                            }

                            total = total - collected.toFixed(2);
                            var total_pending = total;
                            total = Math.round(total * 100) / 100;
                            console.log("Total Revised: " + total);

                            var name = e.currentTarget.name;
                            if (name != null) {
                                var start = name.indexOf("[");
                                var end = name.indexOf("]");
                                start += 1;
                                name = name.substring(start, end);

                                tmp = "input[name$='payment[" + name + "][py_due]']";
                                var due = $(tmp).val();

                                due = due.replace(/,/g, '');
                                due = parseFloat(due);

                                tmp = "input[name$='payment[" + name + "][py_amount]']";
                                if (due <= total) {
                                    $(tmp).val(due);
                                    total_pending -= due;
                                } else {
                                    $(tmp).val(total);
                                    total_pending -= total;
                                }
                            }
                            $('#py_amount_entered').text('Total Pending: ' + total_pending.toFixed(2));
                        } else {
                            var name = e.currentTarget.name;
                            if (name != null) {
                                var start = name.indexOf("[");
                                var end = name.indexOf("]");
                                start += 1;
                                name = name.substring(start, end);

                                tmp = "input[name$='payment[" + name + "][py_amount]']";
                                $(tmp).val('');
                            }
                        }



                        return;
                    }

                });

                $(".py_amount").change(function (e) { py_preview(e); });
                // $('#py_btn_add').on("click", function(e) { py_preview(e); });

            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {

            }
        });
    }

    var supplier = function () {

        $('#kt_modal_supplier').on('show.bs.modal', function () {
            $('[data-repeater-list="supplier"]').empty();
            $('[data-repeater-create="add_supplier_contact"]').click();
        });

        $('#kt_repeater_supplier').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {

            },
            isFirstItemUndeletable: false

        });
    }

    var edit_supplier = function () {

        $('#kt_modal_edit_supplier').on('show.bs.modal', function () {
            $('[data-repeater-list="edit_supplier"]').empty();
            // $('[data-repeater-create="edit_supplier_btn"]').click();
        });

        $('#kt_repeater_supplier_edit').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {

            },
            isFirstItemUndeletable: false

        });
    }

    var client = function () {

        $('#kt_modal_client').on('show.bs.modal', function () {
            $('[data-repeater-list="client"]').empty();
            $('[data-repeater-create="add_client_contact"]').click();
        });

        $('#kt_repeater_client').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {

            },
            isFirstItemUndeletable: false

        });
    }

    var edit_client = function () {

        $('#kt_modal_edit_client').on('show.bs.modal', function () {
            $('[data-repeater-list="edit_client"]').empty();
            // $('[data-repeater-create="edit_client_btn"]').click();
        });

        $('#kt_repeater_client_edit').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {

            },
            isFirstItemUndeletable: false

        });
    }

    var assemblies = function () {
        $('#kt_repeater_assemblies').repeater({
            initEmpty: false,

            show: function () {
                $(this).slideDown();

                $('.a_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Spare Product',
                    tags: true,
                    allowClear: true
                });

                $('.a_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            temp = "input[name$='assemblies[" + name + "][a_desc]']";
                            $(temp).val(response.description);

                        } // /success
                    }); // /fetch selected member info

                });

                $('#a_btn_add').on("click", function (e) { a_preview(e); });
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {

                $('.a_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Spare Product',
                    tags: true,
                    allowClear: true
                });

                $('.a_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            temp = "input[name$='assemblies[" + name + "][a_desc]']";
                            $(temp).val(response.description);

                        } // /success
                    }); // /fetch selected member info

                });

                $('#a_btn_add').on("click", function (e) { a_preview(e); });
            }
        });
    }

    var assembly_operation = function () {

        var options_repeater_assembly = jQuery('#kt_repeater_as');

        options_repeater_assembly.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.as_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Product',
                    allowClear: true
                });

                $('.as_product_name-select2').on("select2:select", function (e) {

                    var temp = '';

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {

                            temp = "input[name$='assembly[" + name + "][current_spare_stock]']";
                            $(temp).val(response);
                        } // /success
                    }); // /fetch selected member info
                });

                $(".as_qty").on("change", function (e) {

                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    var quantity = $("input[name$='assembly[" + name + "][as_qty]']").val();

                    var type = $("#as_type").val();
                    var current_spare_stock = $("input[name$='assembly[" + name + "][current_spare_stock]']").val();

                    console.log(quantity);
                    console.log(current_spare_stock);

                    var temp = '';

                    if (type == 'Assembled') {
                        var result_stock = current_spare_stock - (composite_quantity * quantity);
                    }
                    else {
                        var result_stock = +current_spare_stock + +(composite_quantity * quantity);
                    }

                    temp = "input[name$='assembly[" + name + "][result_spare_stock]']";
                    $(temp).val(result_stock);

                });

            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.as_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Product',
                    allowClear: true
                });

                $('.as_product_name-select2').on("select2:select", function (e) {

                    var temp = '';

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_stock.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {

                            temp = "input[name$='assembly[" + name + "][current_spare_stock]']";
                            $(temp).val(response);
                        } // /success
                    }); // /fetch selected member info
                });

                $(".as_qty").on("change", function (e) {

                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    var quantity = $("input[name$='assembly[" + name + "][as_qty]']").val();
                    var type = $("#as_type").val();
                    var current_spare_stock = $("input[name$='assembly[" + name + "][current_spare_stock]']").val();

                    console.log(quantity);
                    console.log(current_spare_stock);

                    var temp = '';

                    if (type == 'Assembled') {
                        var result_stock = current_spare_stock - (composite_quantity * quantity);
                    }
                    else {
                        var result_stock = +current_spare_stock + +(composite_quantity * quantity);
                    }

                    temp = "input[name$='assembly[" + name + "][result_spare_stock]']";
                    $(temp).val(result_stock);

                });

            },
            isFirstItemUndeletable: false
        });

        jQuery("#assembly_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater_transfer.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var materials_received = function () {
        $('#kt_repeater_materials_received').repeater({
            initEmpty: false,

            show: function () {
                $(this).slideDown();

                $('.mr_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Product',
                    tags: true,
                    allowClear: true
                });

                $('.mr_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            temp = "input[name$='materials_received[" + name + "][mr_desc]']";
                            $(temp).val(response.description);

                            temp = "input[name$='materials_received[" + name + "][mr_unit]']";
                            $(temp).val(response.unit);

                        } // /success
                    }); // /fetch selected member info

                });

                $('#mr_btn_add').on("click", function (e) { mr_preview(e); });
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function () {

                $('.mr_product_name-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_product.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Product',
                    tags: true,
                    allowClear: true
                });

                $('.mr_product_name-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_product_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            temp = "input[name$='materials_received[" + name + "][mr_desc]']";
                            $(temp).val(response.description);

                            temp = "input[name$='materials_received[" + name + "][mr_unit]']";
                            $(temp).val(response.unit);

                        } // /success
                    }); // /fetch selected member info

                });

                $('#mr_btn_add').on("click", function (e) { mr_preview(e); });
            }
        });
    }

    var journal_debit = function () {

        var selected_value = '';

        var options_repeater_en = jQuery('#kt_repeater_journal_debit');

        options_repeater_en.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.journal_debit_supplier-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_supplier.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Supplier',
                    selectOnClose: true
                });

                $('.journal_debit_supplier-select2').on("select2:select", function (e) {

                    selected_value = $(e.currentTarget).val();
                });

                $('.journal_debit_particular-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_supplier_particular.php',
                        type: 'POST',
                        dataType: 'json',
                        data: function (term, page) {
                            return {
                                q: term, // search term
                                supplier: selected_value //Get your value from other elements using Query, for example.
                            };
                        }
                    },
                    width: '100%',
                    placeholder: 'Select Particular',
                });

                $('#journal_debit_btn_add').on("click", function (e) { journal_debit_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.journal_debit_supplier-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_supplier.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Supplier',
                    selectOnClose: true
                });

                $('.journal_debit_supplier-select2').on("select2:select", function (e) {

                    selected_value = $(e.currentTarget).val();
                });

                $('.journal_debit_particular-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_supplier_particular.php',
                        type: 'POST',
                        dataType: 'json',
                        data: function (term, page) {
                            return {
                                q: term, // search term
                                supplier: selected_value //Get your value from other elements using Query, for example.
                            };
                        }
                    },
                    width: '100%',
                    placeholder: 'Select Particular',
                });

                $('#journal_debit_btn_add').on("click", function (e) { journal_debit_preview(e); });
            },
            isFirstItemUndeletable: false
        });

        jQuery("#journal_debit_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var journal_credit = function () {

        var selected_value = '';

        var options_repeater_en = jQuery('#kt_repeater_journal_credit');

        options_repeater_en.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.journal_credit_client-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_client.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Client',
                    selectOnClose: true
                });

                $('.journal_credit_client-select2').on("select2:select", function (e) {

                    selected_value = $(e.currentTarget).val();
                });

                $('.journal_credit_particular-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_client_particular.php',
                        type: 'POST',
                        dataType: 'json',
                        data: function (term, page) {
                            return {
                                q: term, // search term
                                client: selected_value //Get your value from other elements using Query, for example.
                            };
                        }
                    },
                    width: '100%',
                    placeholder: 'Select Particular',
                });

                $('#journal_credit_btn_add').on("click", function (e) { journal_credit_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.journal_credit_client-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_client.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Client',
                    selectOnClose: true
                });

                $('.journal_credit_client-select2').on("select2:select", function (e) {

                    selected_value = $(e.currentTarget).val();
                });

                $('.journal_credit_particular-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_client_particular.php',
                        type: 'POST',
                        dataType: 'json',
                        data: function (term, page) {
                            return {
                                q: term, // search term
                                client: selected_value //Get your value from other elements using Query, for example.
                            };
                        }
                    },
                    width: '100%',
                    placeholder: 'Select Particular',
                });

                $('#journal_credit_btn_add').on("click", function (e) { journal_credit_preview(e); });
            },
            isFirstItemUndeletable: false
        });

        jQuery("#journal_credit_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    var journal = function () {

        var selected_value = '';
        var selected_type = '';
        var options_repeater_en = jQuery('#kt_repeater_journal');

        options_repeater_en.repeater({
            show: function () {
                jQuery(this).slideDown();

                $('.journal_master-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_master.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Master',
                    selectOnClose: true
                });

                $('.journal_master-select2').on("select2:select", function (e) {

                    selected_value = $(e.currentTarget).val();

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_master_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            temp = "input[name$='journal[" + name + "][journal_type]']";
                            $(temp).val(response);

                            temp = "select[name$='journal[" + name + "][journal_particular]']";
                            $(temp).val(null).trigger("change");

                            temp = "input[name$='journal[" + name + "][journal_debit]']";
                            $(temp).val('');

                            temp = "input[name$='journal[" + name + "][journal_credit]']";
                            $(temp).val('');

                            selected_type = response;

                        } // /success
                    }); // /fetch selected member info
                });

                $('.journal_particular-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_particular.php',
                        type: 'POST',
                        dataType: 'json',
                        data: function (term, page) {
                            return {
                                q: term, // search term
                                master: selected_value,
                                type: selected_type, //Get your value from other elements using Query, for example.
                            };
                        }
                    },
                    width: '100%',
                    placeholder: 'Select Particular',
                });

                $('.journal_particular-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_particular_total.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            if (selected_type == 0) {
                                temp = "input[name$='journal[" + name + "][journal_debit]']";
                                $(temp).val(response).trigger('change');

                            }
                            else {
                                temp = "input[name$='journal[" + name + "][journal_credit]']";
                                $(temp).val(response).trigger('change');

                            }

                        } // /success
                    }); // /fetch selected member info
                });

                $('#journal_btn_add').on("click", function (e) { journal_preview(e); });
                $(".journal_debit").change(function (e) { journal_preview(e); });
                $(".journal_credit").change(function (e) { journal_preview(e); });
            },
            hide: function (deleteElement) {
                jQuery(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

                $('.journal_master-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_master.php',
                        dataType: 'json'
                    },
                    width: '100%',
                    placeholder: 'Select Master',
                    selectOnClose: true
                });

                $('.journal_master-select2').on("select2:select", function (e) {

                    selected_value = $(e.currentTarget).val();

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_master_info.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            temp = "input[name$='journal[" + name + "][journal_type]']";
                            $(temp).val(response);

                            temp = "select[name$='journal[" + name + "][journal_particular]']";
                            $(temp).val(null).trigger("change");

                            temp = "input[name$='journal[" + name + "][journal_debit]']";
                            $(temp).val('');

                            temp = "input[name$='journal[" + name + "][journal_credit]']";
                            $(temp).val('');

                            selected_type = response;

                        } // /success
                    }); // /fetch selected member info
                });

                $('.journal_particular-select2').select2({
                    ajax: {
                        url: '../assets/custom/api_get/get_particular.php',
                        type: 'POST',
                        dataType: 'json',
                        data: function (term, page) {
                            return {
                                q: term, // search term
                                master: selected_value,
                                type: selected_type, //Get your value from other elements using Query, for example.
                            };
                        }
                    },
                    width: '100%',
                    placeholder: 'Select Particular',
                });

                $('.journal_particular-select2').on("select2:select", function (e) {

                    var id = $(e.currentTarget).val();
                    var name = e.currentTarget.name;
                    var start = name.indexOf("[");
                    var end = name.indexOf("]");
                    start += 1;
                    name = name.substring(start, end);

                    $.ajax({
                        url: '../assets/custom/api_get/get_particular_total.php',
                        type: 'post',
                        data: { member_id: id },
                        dataType: 'json',
                        success: function (response) {
                            var temp = '';
                            if (selected_type == 0) {
                                temp = "input[name$='journal[" + name + "][journal_debit]']";
                                $(temp).val(response).trigger('change');

                            }
                            else {
                                temp = "input[name$='journal[" + name + "][journal_credit]']";
                                $(temp).val(response).trigger('change');

                            }

                        } // /success
                    }); // /fetch selected member info
                });

                $('#journal_btn_add').on("click", function (e) { journal_preview(e); });
                $(".journal_debit").change(function (e) { journal_preview(e); });
                $(".journal_credit").change(function (e) { journal_preview(e); });
            },
            isFirstItemUndeletable: false
        });

        jQuery("#journal_list").sortable({
            axis: "y",
            cursor: 'pointer',
            opacity: 0.5,
            placeholder: "row-dragging",
            delay: 150,
            update: function (event, ui) {
                options_repeater.repeater('setIndexes');
            }

        }).disableSelection();
    }

    return {
        init: function () {
            enquiry();
            quotation();
            sales_order();
            sales_invoice();
            receipt();
            purchase_order();
            purchase_invoice();
            payment();
            supplier();
            edit_supplier();
            client();
            edit_client();
            assemblies();
            assembly_operation();
            materials_received();
            proforma_invoice();
            credit_note();
            debit_note();
            journal_debit();
            journal_credit();
            journal();
        }
    };
}();

var Select2 = function () {

    var search = function () {
        $('#kt_product_group').on("change", function (e) {
            $('#kt_product_category').val(null).trigger('change');
            $('#kt_product_sub_category').val(null).trigger('change');
            manageDashboardTable.search($(this).val(), 'group');
        });
        $('#kt_product_vendor').on("change", function (e) {
            $('#kt_product_category').val(null).trigger('change');
            $('#kt_product_sub_category').val(null).trigger('change');
            manageDashboardTable.search($(this).val(), 'vendor');
        });

        $('#kt_product_category').on("change", function (e) {
            $('#kt_product_sub_category').val(null).trigger('change');
            manageDashboardTable.search($(this).val(), 'category');
        });

        $('#kt_product_sub_category').on("change", function (e) {
            manageDashboardTable.search($(this).val(), 'sub_category');
        });
        $('#kt_product_stock').on("change", function (e) {
            manageDashboardTable.search($(this).val(), 'positive');
        });


        $('#kt_pr_product_archive').on("change", function (e) {
            manageProductTable.search($(this).val(), 'archive');
        });

        $('#kt_pr_product_group').on("change", function (e) {
            $('#kt_pr_product_category').val('').trigger('change');
            $('#kt_pr_product_sub_category').val('').trigger('change');
            $('#kt_pr_product_vendor').val('').trigger('change');
            manageProductTable.search($(this).val(), 'group');
        });

        $('#kt_pr_product_vendor').on("change", function (e) {
            manageProductTable.search($(this).val(), 'vendor');
        });



        $('#kt_pr_product_category').on("change", function (e) {
            $('#kt_pr_product_sub_category').val('').trigger('change');
            manageProductTable.search($(this).val(), 'category');
        });

        $('#kt_pr_product_sub_category').on("change", function (e) {
            manageProductTable.search($(this).val(), 'sub_category');
        });

        $('#kt_sales_invoice_user').on("change", function (e) {
            manageSalesInvoiceTable.search($(this).val(), 'user');
        });

        $('#kt_sales_invoice_product').on("change", function (e) {
            manageSalesInvoiceTable.search($(this).val(), 'product');
        });

        $('#kt_ssales_invoice_status').on("change", function (e) {
           
            manageSalesSecondaryTable.search($(this).val(), 'status');
            
        });
        $('#kt_sales_invoice_status').on("change", function (e) {
            manageSalesInvoiceTable.search($(this).val(), 'status');
          
            
        });

        $('#kt_si_series').on("change", function (e) {
            manageSalesInvoiceTable.search($(this).val(), 'series');
        });

        $('#kt_purchase_invoice_user').on("change", function (e) {
            managePurchaseInvoiceTable.search($(this).val(), 'user');
        });

        $('#kt_purchase_invoice_product').on("change", function (e) {
            managePurchaseInvoiceTable.search($(this).val(), 'product');
        });

        $('#kt_purchase_invoice_status').on("change", function (e) {
            managePurchaseInvoiceTable.search($(this).val(), 'status');
           
        });
        $('#kt_ppurchase_invoice_status').on("change", function (e) {
            manageSecondaryPurchaseInvoiceTable.search($(this).val(), 'status');
           
        });

        $('#kt_sales_order_user').on("change", function (e) {
            manageSalesOrderTable.search($(this).val(), 'user');
        });

        $('#kt_sales_order_product').on("change", function (e) {
            manageSalesOrderTable.search($(this).val(), 'product');
        });

        $('#kt_sales_order_status').on("change", function (e) {
            manageSalesOrderTable.search($(this).val(), 'status');
        });

        $('#kt_credit_note_user').on("change", function (e) {
            manageCreditNoteTable.search($(this).val(), 'user');
        });

        $('#kt_credit_note_product').on("change", function (e) {
            manageCreditNoteTable.search($(this).val(), 'product');
        });

        $('#kt_credit_note_status').on("change", function (e) {
            manageCreditNoteTable.search($(this).val(), 'status');
        });

        $('#kt_debit_note_user').on("change", function (e) {
            manageDebitNoteTable.search($(this).val(), 'user');
        });

        $('#kt_debit_note_product').on("change", function (e) {
            manageDebitNoteTable.search($(this).val(), 'product');
        });

        $('#kt_debit_note_status').on("change", function (e) {
            manageDebitNoteTable.search($(this).val(), 'status');
        });

        $('#kt_purchase_order_user').on("change", function (e) {
            managePurchaseOrderTable.search($(this).val(), 'user');
        });

        $('#kt_purchase_order_product').on("change", function (e) {
            managePurchaseOrderTable.search($(this).val(), 'product');
        });

        $('#kt_purchase_order_status').on("change", function (e) {
            managePurchaseOrderTable.search($(this).val(), 'status');
        });

        $('#kt_quotation_user').on("change", function (e) {
            manageQuotationTable.search($(this).val(), 'user');
        });

        $('#kt_quotation_product').on("change", function (e) {
            manageQuotationTable.search($(this).val(), 'product');
        });

        $('#kt_quotation_status').on("change", function (e) {
            manageQuotationTable.search($(this).val(), 'status');
        });

        $('#kt_proforma_invoice_user').on("change", function (e) {
            manageProformaInvoiceTable.search($(this).val(), 'user');
        });

        $('#kt_proforma_invoice_product').on("change", function (e) {
            manageProformaInvoiceTable.search($(this).val(), 'product');
        });

        $('#history_product').on("change", function (e) {
            manageProductHistoryTable.search($(this).val(), 'product');
        });
        $('#history_client').on("change", function (e) {
            manageClientHistoryTable.search($(this).val(), 'client');
        });
        $('#history_client_series').on("change", function (e) {
            manageClientHistoryTable.search($(this).val(), 'series');
        });

    }

    var products = function () {

        $('#kt_product_group').select2({
            ajax: {
                url: '../assets/custom/api_get/get_group.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Filter Group',
            allowClear: true
        });
        $('#kt_product_vendor').select2({
            ajax: {
                url: '../assets/custom/api_get/get_vendor.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Filter Vendor',
            allowClear: true
        });



        $('#kt_product_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_category.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Filter Category',
            allowClear: true
        });

        $('#kt_product_stock').select2({
           
            width: '100%',
            placeholder: 'Stock',
            allowClear: true
        });


        $('#kt_product_sub_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_sub_category.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Filter Sub Category',
            allowClear: true
        });

        $('#kt_pr_product_group').select2({
            ajax: {
                url: '../assets/custom/api_get/get_pr_group.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Filter Group',
            allowClear: true
        });

        $('#kt_pr_product_vendor').select2({
            ajax: {
                url: '../assets/custom/api_get/get_pr_vendor.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Filter Vendor',
            allowClear: true
        });

        $('#kt_pr_product_archive').select2({

            width: '100%',
            placeholder: 'Filter Status',
            allowClear: true
        });

        $('#kt_pr_product_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_pr_category.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Filter Category',
            allowClear: true
        });

        $('#kt_pr_product_sub_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_pr_sub_category.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Filter Sub Category',
            allowClear: true
        });

        $('#product_name').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product_add.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Product Name',
            tags: true,
            allowClear: true,
            selectOnClose: true
        });

        $('#product_group_name').select2({
            ajax: {
                url: '../assets/custom/api_get/get_group.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Group',
            tags: true,
            selectOnClose: true
        });
        $('#product_vendor_name').select2({
            ajax: {
                url: '../assets/custom/api_get/get_vendor.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Vendor',
            tags: true,
            selectOnClose: true
        });

        $('#product_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_category.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Category',
            tags: true,
            selectOnClose: true
        });

        $('#product_sub_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_sub_category.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Sub Category',
            tags: true,
            selectOnClose: true
        });

        $('#product_unit').select2({
            width: '100%',
            placeholder: 'Select Unit',
            tags: true,
            selectOnClose: true
        });

        $('#technical_pdf').select2({
            width: '100%',
            placeholder: 'Select Your Choice',
            selectOnClose: true
        });

        $('#product_tax').select2({
            width: '100%',
            placeholder: 'Select Tax',
            selectOnClose: true
        });

        $('#edit_product_name').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Product Name',
            tags: true,
            allowClear: true,
            selectOnClose: true
        });

        $('#edit_product_group_name').select2({
            ajax: {
                url: '../assets/custom/api_get/get_group.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Group',
            tags: true,
            selectOnClose: true
        });
        $('#edit_product_vendor_name').select2({
            ajax: {
                url: '../assets/custom/api_get/get_vendor.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Vendor',
            tags: true,
            selectOnClose: true
        });

        $('#edit_product_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_category.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Category',
            tags: true,
            selectOnClose: true
        });

        $('#edit_product_sub_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_sub_category.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Sub Category',
            tags: true,
            selectOnClose: true
        });

        $('#edit_product_unit').select2({
            width: '100%',
            placeholder: 'Select Unit',
            tags: true,
            selectOnClose: true
        });

        $('#edit_product_tax').select2({
            width: '100%',
            placeholder: 'Select Tax',
            selectOnClose: true
        });

        $('#product_xml_from').datepicker({
            dateFormat: 'dd-mm-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
        });

        $('#product_xml_to').datepicker({
            dateFormat: 'dd-mm-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
        });

        $('#masters_xml_from').datepicker({
            dateFormat: 'dd-mm-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
        });

        $('#masters_xml_to').datepicker({
            dateFormat: 'dd-mm-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
        });
    };

    var clients = function () {

        $('#client_name').keyup(function (e) {
            var temp = $('#client_name').val();
            $('#client_print_name').val(temp);
        });

        $('#edit_client_name').keyup(function (e) {
            var temp = $('#edit_client_name').val();
            $('#edit_client_print_name').val(temp);
        });

        $('#client_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client_type.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select client Type',
            tags: true,
            allowClear: true,
            selectOnClose: true
        });

        $('#edit_client_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client_type.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select client Type',
            tags: true,
            allowClear: true,
            selectOnClose: true
        });

        $('#client_state').select2({
            ajax: {
                url: '../assets/custom/api_get/get_states.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select State',
            // tags: true,
            allowClear: true
        });

        $('#edit_client_state').select2({
            ajax: {
                url: '../assets/custom/api_get/get_states.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select State',
            // tags: true,
            allowClear: true
        });

        $('#client_gstin_type').select2({
            width: '100%'
        });

        $('#edit_client_gstin_type').select2({
            width: '100%'
        });

        $('#kt_form_status').on('change', function () {
            manageClientTable.search($(this).val().toLowerCase(), 'Status');
        });

        $('#kt_form_type').on('change', function () {
            manageClientTable.search($(this).val().toLowerCase(), 'Type');
        });

        $('#kt_form_status,#kt_form_type').selectpicker();
    };

    var suppliers = function () {

        $('#supplier_name').keyup(function (e) {
            var temp = $('#supplier_name').val();
            $('#supplier_print_name').val(temp);
        });

        $('#edit_supplier_name').keyup(function (e) {
            var temp = $('#edit_supplier_name').val();
            $('#edit_supplier_print_name').val(temp);
        });

        $('#supplier_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_supplier_type.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Supplier Type',
            tags: true,
            allowClear: true,
            selectOnClose: true
        });

        $('#edit_supplier_category').select2({
            ajax: {
                url: '../assets/custom/api_get/get_supplier_type.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Supplier Type',
            tags: true,
            allowClear: true,
            selectOnClose: true
        });

        $('#supplier_state').select2({
            ajax: {
                url: '../assets/custom/api_get/get_states.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select State',
            // tags: true,
            allowClear: true
        });

        $('#edit_supplier_state').select2({
            ajax: {
                url: '../assets/custom/api_get/get_states.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select State',
            // tags: true,
            allowClear: true
        });

        $('#supplier_gstin_type').select2({
            width: '100%'
        });

        $('#edit_supplier_gstin_type').select2({
            width: '100%'
        });

        $('#kt_form_status').on('change', function () {
            manageSupplierTable.search($(this).val().toLowerCase(), 'Status');
        });

        $('#kt_form_type').on('change', function () {
            manageSupplierTable.search($(this).val().toLowerCase(), 'Type');
        });

        $('#kt_form_status,#kt_form_type').selectpicker();
    };

    var users = function () {
        $('#kt_user_type').on('change', function () {
            manageUsersTable.search($(this).val().toLowerCase(), 'Usertype');
        });

        $('#kt_user_type').selectpicker();

        $('#userlevel').select2({
            width: '100%',
            placeholder: 'Select User Type'
        });

        $('#edit_userlevel').select2({
            width: '100%',
            placeholder: 'Select User Type'
        });
    };

    var enquiry = function () {
        var portlet = new KTPortlet('kt_portlet_add_e');

        $('#enquiry_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('.enquiry-select2').select2({
            width: '100%',
            placeholder: 'Mode'
        });

        $('.enquiry-status-select2').select2({
            width: '100%',
            placeholder: 'Status'
        });

        $('#e_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            selectOnClose: true
        });
    }

    var quotation = function () {
        var portlet = new KTPortlet('kt_portlet_add_q');

        $('#kt_quotation_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });

        $('#kt_quotation_user').select2({
            ajax: {
                url: '../assets/custom/api_get/get_user.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select User',
            allowClear: true
        });

        $('#kt_quotation_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            allowClear: true,
            tags: true
        });

        $('#quotation_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('.quotation-status-select2').select2({
            width: '100%',
            placeholder: 'Status'
        });

        $('#q_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            selectOnClose: true,
            tags: true
        });

        $('#q_enquiry_no').select2({
            ajax: {
                url: '../assets/custom/api_get/get_enquiry.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                        client: selected_client //Get your value from other elements using Query, for example.
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Enquiry',
            multiple: true
        });

        $('#q_client').on("select2:select", function (e) {
            $('[data-repeater-list="quotation"]').empty();
            $('[data-repeater-create="quotation"]').click();
            var tmp = "input[name$='quotation[0][q_sn]']";
            $(tmp).val(1);
            selected_client = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_client_address.php',
                type: 'post',
                data: { member_id: selected_client },
                dataType: 'json',
                success: function (response) {
                    if (response != null) {
                        var address = response.address;
                        var getmob = response.contacts;
                        if (getmob != null) {
                            getmob = JSON.parse(getmob);
                        }
                        if (getmob.mobile[0] != null) {
                            $("#mobile").val(getmob.mobile[0]);
                        }
                        else {
                            $("#mobile").val('');
                        }
                        var state = response.state;
                        if (state != null) {
                            $("#q_state").val(state);
                            $("#state").val(state);
                        } else {
                            $("#q_state").val('');
                            $("#state").val('');
                        }
                        if (address != null) {
                            address = JSON.parse(address);
                            $("#address_1").val(address.address_1);
                            $("#address_2").val(address.address_2);
                            $("#city").val(address.city);
                            $("#pincode").val(address.pincode);
                            $("#country").val(response.country);
                        } else {
                            $("#address_1").val('');
                            $("#address_2").val('');
                            $("#city").val('');
                            $("#pincode").val('');
                            $("#country").val('');
                        }
                    } else {
                        $("#address_1").val('');
                        $("#address_2").val('');
                        $("#city").val('');
                        $("#pincode").val('');
                        $("#q_state").val('');
                        $("#state").val('');
                        $("#country").val('');
                    }
                } // Success
            });
            $('.q_enquiry_no-select2').val(null).trigger('change');
            $('#q_enquiry_date').val('');
        });

        $('.q_enquiry_no-select2').on("select2:select", function (e) {
            $('[data-repeater-list="quotation"]').empty();
            $('[data-repeater-create="quotation"]').click();
            var tmp = "input[name$='quotation[0][q_sn]']";
            $(tmp).val(1);

            var id = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/enquiry/getSelectedEnquiryPull.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response1) {

                    var temp = '';
                    $('#q_enquiry_date').val(response1.enquiry_date);
                    $('#q_cl_enquiry_no').val(response1.cl_enquiry);
                    var obj = JSON.parse(response1.items);
                    var length = obj.product.length;
                    var count = 0;
                    var c = 0;

                    for (var i = 1; i < length; i++) {
                        $('#qtn_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        if (obj.quantity[i] > 0) {
                            temp = "select[name$='quotation[" + c + "][q_product_name]']";
                            var pr = obj.product[i];
                            $(temp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");

                            temp = "input[name$='quotation[" + c + "][q_qty]']";
                            $(temp).val(obj.quantity[i]);
                            temp = "input[name$='quotation[" + c + "][q_product_description]']";
                            $(temp).val(obj.desc[i]);
                            temp = "textarea[name$='quotation[" + c + "][q_product_add_description]']";

                            var temp_val = obj.long_desc[i];
                            temp_val = temp_val.replace(/\|/g, "\r\n");
                            $(temp).val(temp_val);

                            $.ajax({
                                url: '../assets/custom/api_get/get_product_info.php',
                                type: 'post',
                                data: { member_id: pr },
                                dataType: 'json',
                                async: false,
                                success: function (response) {
                                    temp = "input[name$='quotation[" + c + "][q_rate]']";
                                    $(temp).val(response.rate);
                                    temp = "select[name$='quotation[" + c + "][q_unit]']";
                                    $(temp).empty().append($("<option/>").val(response.unit).text(response.unit)).val(response.unit).trigger("change");
                                    temp = "input[name$='quotation[" + c + "][q_hsn]']";
                                    $(temp).val(response.hsn);
                                    temp = "select[name$='quotation[" + c + "][q_tax]']";
                                    $(temp).val(response.tax).trigger("change");
                                    temp = "select[name$='quotation[" + c + "][q_display_make]']";
                                    $(temp).val(response.default_make).trigger("change");

                                }
                            });
                            c++;
                        }

                    }
                    q_preview(e);

                }
            });
        });
    };

    var sales_order = function () {

        var portlet = new KTPortlet('kt_portlet_add_so');

        $('#kt_sales_order_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });

        $('#kt_sales_order_user').select2({
            ajax: {
                url: '../assets/custom/api_get/get_user.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select User',
            allowClear: true
        });

        $('#kt_sales_order_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            allowClear: true
        });

        $('#sales_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });



        $('#so_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            selectOnClose: true
        });

        $('.so_status-select2').select2({
            width: '100%',
            placeholder: 'Status',
            allowClear: true
        });

        $('.so_collected-select2').select2({
            width: '100%',
            placeholder: 'Status'
        });

        $('#so_quotation').select2({
            ajax: {
                url: '../assets/custom/api_get/get_quotation.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                        client: selected_client //Get your value from other elements using Query, for example.
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Quotation',
            multiple: true
        });

        $('#so_client').on("select2:select", function (e) {
            $('[data-repeater-list="sales_order"]').empty();
            $('[data-repeater-create="sales_order"]').click();
            var tmp = "input[name$='sales_order[0][so_sn]']";
            $(tmp).val(1);
            selected_client = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_client_address.php',
                type: 'post',
                data: { member_id: selected_client },
                dataType: 'json',
                success: function (response) {
                    var getmob = response.contacts;
                    if (getmob != null) {
                        getmob = JSON.parse(getmob);
                    }
                    if (getmob.mobile[0] != null) {
                        $("#mobile").val(getmob.mobile[0]);
                    }
                    else {
                        $("#mobile").val('');
                    }
                    $("#so_state").val(response.state);
                } // /success
            });
            $('.so_quotation-select2').val(null).trigger('change');
        });

        $('.so_quotation-select2').on("select2:select", function (e) {
            $('[data-repeater-list="sales_order"]').empty();
            $('[data-repeater-create="sales_order"]').click();
            var tmp = "input[name$='sales_order[0][so_sn]']";
            $(tmp).val(1);

            var id = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/quotation/getSelectedQuotationPull.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response1) {

                    var temp = '';
                    var obj = JSON.parse(response1.items);
                    var addons = JSON.parse(response1.addons);
                    var length = obj.product.length;
                    var count = 0;
                    var c = 0;
                    var getmob = response1.mobile;
                    if (getmob != null) {

                        $("#mobile").val(getmob);
                    }
                    else {
                        $("#mobile").val('');
                    }

                    $('#so_freight').val(addons.freight);
                    $('#so_pf').val(addons.pf);
                    $('#so_tot_discount').val(addons.discount);


                    for (var i = 1; i < length; i++) {
                        $('#so_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        if (obj.quantity[i] > 0) {
                            temp = "select[name$='sales_order[" + c + "][so_product_name]']";
                            var pr = obj.product[i];
                            $(temp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");
                            temp = "input[name$='sales_order[" + c + "][so_qty]']";
                            $(temp).val(obj.quantity[i]);
                            temp = "select[name$='sales_order[" + c + "][so_unit]']";
                            $(temp).empty().append($("<option/>").val(obj.unit[i]).text(obj.unit[i])).val(obj.unit[i]).trigger("change");
                            temp = "input[name$='sales_order[" + c + "][so_rate]']";
                            $(temp).val(obj.price[i]);
                            temp = "input[name$='sales_order[" + c + "][so_dsc]']";
                            $(temp).val(obj.discount[i]);
                            temp = "input[name$='sales_order[" + c + "][so_hsn]']";
                            $(temp).val(obj.hsn[i]);
                            temp = "input[name$='sales_order[" + c + "][so_product_description]']";
                            $(temp).val(obj.desc[i]);
                            temp = "select[name$='sales_order[" + c + "][so_tax]']";
                            $(temp).val(obj.tax[i]).trigger("change");
                            temp = "select[name$='sales_order[" + c + "][so_display_make]']";
                            $(temp).val(obj.group[i]).trigger("change");

                            temp = "textarea[name$='sales_order[" + c + "][so_product_add_description]']";
                            var temp_val = obj.long_desc[i];
                            temp_val = temp_val.replace(/\|/g, "\r\n");
                            $(temp).val(temp_val);

                            var temp_textarea = $(temp);
                            autosize(temp_textarea);

                            c++;
                        }

                    }
                    so_preview(e);

                }
            });
        });
    };

    var credit_note = function () {

        var portlet = new KTPortlet('kt_portlet_add_cn');

        $('#kt_credit_note_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });

        $('#kt_credit_note_user').select2({
            ajax: {
                url: '../assets/custom/api_get/get_user.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select User',
            allowClear: true
        });

        $('#kt_credit_note_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            allowClear: true
        });

        $('#cn_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });


        $('#cn_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            selectOnClose: true
        });

        $('#cn_client').on("select2:select", function (e) {
            $('[data-repeater-list="credit_note"]').empty();
            $('[data-repeater-create="credit_note"]').click();
            var tmp = "input[name$='credit_note[0][cn_sn]']";
            $(tmp).val(1);
            selected_client = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_client_address.php',
                type: 'post',
                data: { member_id: selected_client },
                dataType: 'json',
                success: function (response) {
                    $("#cn_state").val(response.state);
                } // /success
            });
        });
    };

    var debit_note = function () {

        var portlet = new KTPortlet('kt_portlet_add_dn');

        $('#kt_debit_note_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });

        $('#kt_debit_note_user').select2({
            ajax: {
                url: '../assets/custom/api_get/get_user.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select User',
            allowClear: true
        });

        $('#kt_debit_note_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            allowClear: true
        });

        $('#dn_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#dn_pi_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });


        $('#dn_supplier').select2({
            ajax: {
                url: '../assets/custom/api_get/get_supplier.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Supplier',
            selectOnClose: true
        });

        $('#dn_supplier').on("select2:select", function (e) {
            $('[data-repeater-list="debit_note"]').empty();
            $('[data-repeater-create="debit_note"]').click();
            var tmp = "input[name$='debit_note[0][dn_sn]']";
            $(tmp).val(1);
            selected_client = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_supplier_address.php',
                type: 'post',
                data: { member_id: selected_client },
                dataType: 'json',
                success: function (response) {
                    $("#dn_state").val(response.state);
                } // /success
            });
        });
    };

    var proforma_invoice = function () {

        var portlet = new KTPortlet('kt_portlet_add_pr');

        $('#kt_proforma_invoice_user').select2({
            ajax: {
                url: '../assets/custom/api_get/get_user.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select User',
            allowClear: true
        });

        $('#kt_proforma_invoice_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            allowClear: true,
            tags: true
        });

        $('#pr_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#pr_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            selectOnClose: true,
            tags: true
        });

        $('.pr_status-select2').select2({
            width: '100%',
            placeholder: 'Status',
            allowClear: true
        });

        $('.pr_collected-select2').select2({
            width: '100%',
            placeholder: 'Status'
        });

        $('#pr_sales_order').select2({
            ajax: {
                url: '../assets/custom/api_get/get_sales_order.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                        client: selected_client //Get your value from other elements using Query, for example.
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Sales Order#',
            multiple: true
        });

        $('#pr_client').on("select2:select", function (e) {
            $('[data-repeater-list="proforma_invoice"]').empty();
            $('[data-repeater-create="proforma_invoice"]').click();
            var tmp = "input[name$='proforma_invoice[0][pi_sn]']";
            $(tmp).val(1);
            selected_client = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_client_address.php',
                type: 'post',
                data: { member_id: selected_client },
                dataType: 'json',
                success: function (response) {
                    if (response != null) {
                        var address = response.address;
                        var state = response.state;
                        var getmob = response.contacts;
                        if (getmob != null) {
                            getmob = JSON.parse(getmob);
                        }
                        if (getmob.mobile[0] != null) {
                            $("#mobile").val(getmob.mobile[0]);
                        }
                        else {
                            $("#mobile").val('');
                        }
                        if (state != null) {
                            $("#pr_state").val(state);
                            $("#state").val(state);
                        } else {
                            $("#pr_state").val('');
                            $("#state").val('');
                        }
                        if (address != null) {
                            address = JSON.parse(address);
                            $("#address_1").val(address.address_1);
                            $("#address_2").val(address.address_2);
                            $("#city").val(address.city);
                            $("#pincode").val(address.pincode);
                        } else {
                            $("#address_1").val('');
                            $("#address_2").val('');
                            $("#city").val('');
                            $("#pincode").val('');
                        }
                    } else {
                        $("#address_1").val('');
                        $("#address_2").val('');
                        $("#city").val('');
                        $("#pincode").val('');
                        $("#pr_state").val('');
                        $("#state").val('');
                    }
                } // /success
            });
            $('.pr_sales_order-select2').val(null).trigger('change');
        });

        $('.pr_sales_order-select2').on("select2:select", function (e) {
            $('[data-repeater-list="proforma_invoice"]').empty();
            $('[data-repeater-create="proforma_invoice"]').click();
            var tmp = "input[name$='proforma_invoice[0][pi_sn]']";
            $(tmp).val(1);

            var id = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/sales_order/getSelectedSOPull.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response1) {

                    var temp = '';
                    var obj = JSON.parse(response1.items);
                    var addons = JSON.parse(response1.addons);
                    var length = obj.product.length;
                    var count = 0;
                    var c = 0;
                    var getmob = response1.mobile;
                    if (getmob != null) {

                        $("#mobile").val(getmob);
                    }
                    else {
                        $("#mobile").val('');
                    }

                    $('#pr_freight').val(addons.freight);
                    $('#pr_pf').val(addons.pf);
                    $('#pr_tot_discount').val(addons.discount);

                    $('#client_so_no').val(response1.client_so_no);

                    for (var i = 1; i < length; i++) {
                        $('#pr_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        if (obj.quantity[i] > 0) {
                            temp = "select[name$='proforma_invoice[" + c + "][pr_product_name]']";
                            var pr = obj.product[i];
                            $(temp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");
                            temp = "input[name$='proforma_invoice[" + c + "][pr_qty]']";
                            $(temp).val(obj.quantity[i]);
                            temp = "select[name$='proforma_invoice[" + c + "][pr_unit]']";
                            $(temp).empty().append($("<option/>").val(obj.unit[i]).text(obj.unit[i])).val(obj.unit[i]).trigger("change");
                            temp = "input[name$='proforma_invoice[" + c + "][pr_rate]']";
                            $(temp).val(obj.price[i]);
                            temp = "input[name$='proforma_invoice[" + c + "][pr_dsc]']";
                            $(temp).val(obj.discount[i]);
                            temp = "input[name$='proforma_invoice[" + c + "][pr_hsn]']";
                            $(temp).val(obj.hsn[i]);
                            temp = "input[name$='proforma_invoice[" + c + "][pr_product_description]']";
                            $(temp).val(obj.desc[i]);
                            temp = "select[name$='proforma_invoice[" + c + "][pr_tax]']";
                            $(temp).val(obj.tax[i]).trigger("change");
                            temp = "select[name$='proforma_invoice[" + c + "][pr_display_make]']";
                            $(temp).val(obj.group[i]).trigger("change");

                            temp = "textarea[name$='proforma_invoice[" + c + "][pr_product_add_description]']";
                            var temp_val = obj.long_desc[i];
                            temp_val = temp_val.replace(/\|/g, "\r\n");
                            $(temp).val(temp_val);

                            var temp_textarea = $(temp);
                            autosize(temp_textarea);

                            c++;
                        }

                    }
                    pr_preview(e);

                }
            });
        });
    };

    var sales = function () {
        var portlet = new KTPortlet('kt_portlet_add_si');

        $('#kt_si_series').select2({
            width: '100%',
            placeholder: 'Filter Series',
            allowClear: true
        });

        $('#kt_sales_invoice_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });
        
        $('#kt_ssales_invoice_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });

        $('#kt_sales_invoice_user').select2({
            ajax: {
                url: '../assets/custom/api_get/get_user.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select User',
            allowClear: true
        });

        $('#kt_sales_invoice_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            allowClear: true
        });

        $('#sales_invoice_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#buyer_order_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#despatch_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#si_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            selectOnClose: true
        });

        $('#si_series').select2({
            width: '100%',
            placeholder: 'Select Series'
        });

        $('#si_series').on('select2:select', function (e) {
            var data = e.params.data.id;
            set_sales_invoice_no(data);
        });

        $('#si_start').select2({
            width: '100%',
            placeholder: 'Select Format'
        });

        $('#shipping_state').select2({
            ajax: {
                url: '../assets/custom/api_get/get_states.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select State',
            // tags: true,
            allowClear: true
        });

        $('#si_sales_order').select2({
            ajax: {
                url: '../assets/custom/api_get/get_sales_order.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                        client: selected_client //Get your value from other elements using Query, for example.
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Sales Order',
            multiple: true
        });

        $('#si_quotation').select2({
            ajax: {
                url: '../assets/custom/api_get/get_quotation.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                        client: selected_client //Get your value from other elements using Query, for example.
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Quotation',
            multiple: true
        });

        $('#si_proforma_invoice').select2({
            ajax: {
                url: '../assets/custom/api_get/get_proforma_invoice.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                        client: selected_client //Get your value from other elements using Query, for example.
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Proforma',
            multiple: true
        });

        $('#si_client').on("select2:select", function (e) {
            // $('[data-repeater-list="sales_invoice"]').empty();
            // $('[data-repeater-create="sales_invoice"]').click();
            var tmp = "input[name$='sales_invoice[0][si_sn]']";
            $(tmp).val(1);
            selected_client = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_client_address.php',
                type: 'post',
                data: { member_id: selected_client },
                dataType: 'json',
                success: function (response) {
                    // Updated version for outputting discount information
                    $(".product_discount").html('<span style="padding-left: 5px; padding-top: 5px; font-size: 15px;font-weight:500;">Discount:  ' + response.vendor_discount + '</span>');

                    $("#shipping_name").val(response.print_name);
                    $("#si_state").val(response.state);
                    var getmob = response.contacts;
                    if (getmob != null) {
                        getmob = JSON.parse(getmob);
                    }
                    if (getmob.mobile[0] != null) {
                        $("#mobile").val(getmob.mobile[0]);
                    }
                    else {
                        $("#mobile").val('');
                    }


                    var address = JSON.parse(response.address);
                    $("#shipping_add_1").val(address.address_1);
                    $("#shipping_add_2").val(address.address_2);
                    $("#shipping_city").val(address.city);
                    $("#shipping_pincode").val(address.pincode);
                    $("#shipping_state").empty().append($("<option/>").val(response.state).text(response.state)).val(response.state).trigger("change");

                    $("#shipping_country").val(response.country);

                } // /success
            }); // /fetch selected member info
            $('.si_sales_order-select2').val(null).trigger('change');
        });

        $('.si_sales_order-select2').on("select2:select", function (e) {
            $('[data-repeater-list="sales_invoice"]').empty();
            $('[data-repeater-create="sales_invoice"]').click();
            var tmp = "input[name$='sales_invoice[0][si_sn]']";
            $(tmp).val(1);

            var id = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/sales_order/getSelectedSOPull.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {

                    var temp = '';
                    var obj = JSON.parse(response.items);
                    var addons = JSON.parse(response.addons);

                    var length = obj.product.length;
                    var count = 0;
                    var c = 0;

                    $('#si_freight').val(addons.freight);
                    $('#si_pf').val(addons.pf);
                    $('#si_tot_discount').val(addons.discount);

                    for (var i = 0; i < length; i++) {
                        if (obj.quantity[i] - obj.received[i] > 0) {
                            count++;
                        }
                    }

                    for (var i = 1; i < count; i++) {
                        $('#si_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        if (obj.quantity[i] - obj.received[i] > 0) {
                            temp = "select[name$='sales_invoice[" + c + "][si_product_name]']";
                            var pr = obj.product[i];
                            $(temp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");

                            temp = "input[name$='sales_invoice[" + c + "][si_qty]']";
                            $(temp).val(obj.quantity[i] - obj.received[i]);
                            temp = "select[name$='sales_invoice[" + c + "][si_unit]']";
                            $(temp).empty().append($("<option/>").val(obj.unit[i]).text(obj.unit[i])).val(obj.unit[i]).trigger("change");
                            temp = "input[name$='sales_invoice[" + c + "][si_rate]']";
                            $(temp).val(obj.price[i]);
                            temp = "input[name$='sales_invoice[" + c + "][si_dsc]']";
                            $(temp).val(obj.discount[i]);
                            temp = "input[name$='sales_invoice[" + c + "][si_hsn]']";
                            $(temp).val(obj.hsn[i]);
                            temp = "select[name$='sales_invoice[" + c + "][si_tax]']";
                            $(temp).val(obj.tax[i]).trigger("change");
                            temp = "select[name$='sales_invoice[" + c + "][si_display_make]']";
                            $(temp).val(obj.group[i]).trigger("change");
                            temp = "textarea[name$='sales_invoice[" + c + "][si_product_description]']";
                            $(temp).val(obj.desc[i]);

                            temp = "textarea[name$='sales_invoice[" + c + "][si_product_add_description]']";
                            var temp_val = obj.long_desc[i];
                            temp_val = temp_val.replace(/\|/g, "\r\n");
                            $(temp).val(temp_val);

                            var temp_textarea = $(temp);
                            autosize(temp_textarea);
                            c++;
                        }

                    }
                    $("#buyer_order_no").val(response.client_so_no);
                    $("#other_ref").val(response.q_no);
                    var so_date = new Date(response.so_date);
                    var formatted_date = appendLeadingZeroes(so_date.getDate()) + "-" + appendLeadingZeroes(so_date.getMonth() + 1) + "-" + so_date.getFullYear();
                    $("#buyer_order_date").val(formatted_date);
                    si_preview(e);
                } // /success
            }); // /fetch selected member info
        });

        $('.si_proforma_invoice-select2').on("select2:select", function (e) {
            $('[data-repeater-list="sales_invoice"]').empty();
            $('[data-repeater-create="sales_invoice"]').click();
            var tmp = "input[name$='sales_invoice[0][si_sn]']";
            $(tmp).val(1);

            var id = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/proforma_invoice/getSelectedProformaPull.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {

                    var temp = '';
                    var obj = JSON.parse(response.items);
                    var addons = JSON.parse(response.addons);

                    var length = obj.product.length;
                    var count = 0;
                    var c = 0;

                    $('#si_freight').val(addons.freight);
                    $('#si_pf').val(addons.pf);
                    $('#si_tot_discount').val(addons.discount);

                    for (var i = 0; i < length; i++) {
                        if (obj.quantity[i] - obj.received[i] > 0) {
                            count++;
                        }
                    }

                    for (var i = 1; i < count; i++) {
                        $('#si_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        if (obj.quantity[i] - obj.received[i] > 0) {
                            temp = "select[name$='sales_invoice[" + c + "][si_product_name]']";
                            var pr = obj.product[i];
                            $(temp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");

                            temp = "input[name$='sales_invoice[" + c + "][si_qty]']";
                            $(temp).val(obj.quantity[i] - obj.received[i]);
                            temp = "select[name$='sales_invoice[" + c + "][si_unit]']";
                            $(temp).empty().append($("<option/>").val(obj.unit[i]).text(obj.unit[i])).val(obj.unit[i]).trigger("change");
                            temp = "input[name$='sales_invoice[" + c + "][si_rate]']";
                            $(temp).val(obj.price[i]);
                            temp = "input[name$='sales_invoice[" + c + "][si_dsc]']";
                            $(temp).val(obj.discount[i]);
                            temp = "input[name$='sales_invoice[" + c + "][si_hsn]']";
                            $(temp).val(obj.hsn[i]);
                            temp = "select[name$='sales_invoice[" + c + "][si_tax]']";
                            $(temp).val(obj.tax[i]).trigger("change");
                            temp = "select[name$='sales_invoice[" + c + "][si_display_make]']";
                            $(temp).val(obj.group[i]).trigger("change");
                            temp = "textarea[name$='sales_invoice[" + c + "][si_product_description]']";
                            $(temp).val(obj.desc[i]);

                            temp = "textarea[name$='sales_invoice[" + c + "][si_product_add_description]']";
                            var temp_val = obj.long_desc[i];
                            temp_val = temp_val.replace(/\|/g, "\r\n");
                            $(temp).val(temp_val);

                            var temp_textarea = $(temp);
                            autosize(temp_textarea);
                            c++;
                        }

                    }

                    si_preview(e);
                } // /success
            }); // /fetch selected member info
        });

        $('.si_quotation-select2').on("select2:select", function (e) {
            $('[data-repeater-list="sales_invoice"]').empty();
            $('[data-repeater-create="sales_invoice"]').click();
            var tmp = "input[name$='sales_invoice[0][si_sn]']";
            $(tmp).val(1);

            var id = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/quotation/getSelectedQuotationPull.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {

                    var temp = '';
                    var obj = JSON.parse(response.items);
                    var addons = JSON.parse(response.addons);

                    var length = obj.product.length;
                    var count = 0;
                    var c = 0;

                    $('#si_freight').val(addons.freight);
                    $('#si_pf').val(addons.pf);
                    $('#si_tot_discount').val(addons.discount);
                    $('#mobile').val(response.mobile);
                    for (var i = 0; i < length; i++) {
                        if (obj.quantity[i] > 0) {
                            count++;
                        }
                    }

                    console.log(count);

                    for (var i = 1; i < count; i++) {
                        $('#si_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        if (obj.quantity[i] > 0) {
                            temp = "select[name$='sales_invoice[" + c + "][si_product_name]']";
                            var pr = obj.product[i];
                            $(temp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");

                            temp = "input[name$='sales_invoice[" + c + "][si_qty]']";
                            $(temp).val(obj.quantity[i]);
                            temp = "select[name$='sales_invoice[" + c + "][si_unit]']";
                            $(temp).empty().append($("<option/>").val(obj.unit[i]).text(obj.unit[i])).val(obj.unit[i]).trigger("change");
                            temp = "input[name$='sales_invoice[" + c + "][si_rate]']";
                            $(temp).val(obj.price[i]);
                            temp = "input[name$='sales_invoice[" + c + "][si_dsc]']";
                            $(temp).val(obj.discount[i]);
                            temp = "input[name$='sales_invoice[" + c + "][si_hsn]']";
                            $(temp).val(obj.hsn[i]);
                            temp = "select[name$='sales_invoice[" + c + "][si_tax]']";
                            $(temp).val(obj.tax[i]).trigger("change");
                            temp = "textarea[name$='sales_invoice[" + c + "][si_product_description]']";
                            $(temp).val(obj.desc[i]);

                            temp = "textarea[name$='sales_invoice[" + c + "][si_product_add_description]']";
                            var temp_val = obj.long_desc[i];
                            temp_val = temp_val.replace(/\|/g, "\r\n");
                            $(temp).val(temp_val);

                            var temp_textarea = $(temp);
                            autosize(temp_textarea);
                            c++;
                        }

                    }
                    $("#other_ref").val(response.q_no);
                    si_preview(e);
                } // /success
            }); // /fetch selected member info
        });
    };

    var whatsapp = function () {
        var tmp;
        tmp = "textarea[name$='whatsapp_message']";
        var temp_textarea = $(tmp);
        autosize(temp_textarea);
    };

    var receipts = function () {

        $('.rc_mode-select2').select2({
            width: '100%',
            placeholder: 'Select Mode',
            allowClear: true
        });


        $('.rc_bank-select2').select2({
            ajax: {
                url: '../assets/custom/api_get/get_bank.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Bank',
            allowClear: true
        });

        $('.rc_bank-select2').on("select2:select", function (e) {
            var id = $(e.currentTarget).val();
            id = encodeURIComponent(id);
            console.log(id);

            var amount = $('#amount').val();

            if (id != 'Cash' && amount != '' && !isNaN(amount)) {
                document.getElementById("bank_details").style.display = "inline-flex";
                document.getElementById("bank_details_title").style.display = "inline-flex";
            } else {
                document.getElementById("bank_details").style.display = "none";
                document.getElementById("bank_details_title").style.display = "none";
            }
        });

        $('#amount').keyup(function (e) {
            var amount = $('#amount').val();

            var bank = $('.rc_bank-select2').val();

            if (amount != '' && !isNaN(amount)) {
                document.getElementById("invoice_details").style.display = "inline-flex";
                document.getElementById("invoice_details_title").style.display = "inline-flex";
                console.log(bank);
                if (bank != 'Cash') {
                    document.getElementById("bank_details").style.display = "inline-flex";
                    document.getElementById("bank_details_title").style.display = "inline-flex";
                } else {
                    document.getElementById("bank_details").style.display = "none";
                    document.getElementById("bank_details_title").style.display = "none";
                }

            } else {
                document.getElementById("invoice_details").style.display = "none";
                document.getElementById("invoice_details_title").style.display = "none";

                document.getElementById("bank_details").style.display = "none";
                document.getElementById("bank_details_title").style.display = "none";
            }



        });

        $('#kt_sales_receipt').on("change", function (e) {
            manageReceiptsTable.search($(this).val(), 'rc_type');

        });

        $('#kt_sales_receipt').select2({
            width: '100%',
            placeholder: 'Receipt Type',
            allowClear: true
        });

        $('#sales_receipt1').on("change", function (e) {
            type_receipt = $(this).val();
            $('#rc_client').val(null).trigger('change');
        });

        $('#sales_receipt1').select2({
            width: '100%',
            placeholder: 'Select Receipt Type',
            allowClear: true
        });

        $('.edit_rc_bank-select2').select2({
            ajax: {
                url: '../assets/custom/api_get/get_bank.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Bank',
            allowClear: true,
            selectOnClose: true
        });

        $('#rc_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#rc_ins_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#edit_rc_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#rc_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client'
        });

        $('#edit_rc_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            tags: true,
            selectOnClose: true
        });

        $('#rc_mode').select2({
            width: '100%',
            placeholder: 'Select Mode',
            selectOnClose: true
        });



        $('#edit_rc_mode').select2({
            width: '100%',
            placeholder: 'Select Mode',
            selectOnClose: true
        });

        $('#rc_btn_advance').on("click", function (e) {
            document.getElementById("rc_advance_amount").style.display = "block";

        });

        $('#rc_client').on("select2:select", function (e) {
            $('[data-repeater-list="receipt"]').empty();
            // $('[data-repeater-create="receipt"]').click();
            // var tmp = "input[name$='receipt[0][rc_sn]']";
            // $(tmp).val(1);

            var id = $(e.currentTarget).val();


            id = encodeURIComponent(id);

            type_receipt = encodeURIComponent(type_receipt);

            $.ajax({
                url: '../assets/custom/api_get/getPendingSales.php',
                type: 'post',
                data: { member_id: id, rc_type: type_receipt },
                dataType: 'json',
                success: function (response) {

                    console.log(response);

                    var temp = '';
                    var obj = JSON.parse(response.result);

                    var length = obj.si_details_sn.length;
                    console.log(length);

                    var total = 0;
                    var c = 0;

                    for (var i = 0; i < length; i++) {
                        $('#rc_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        temp = "input[name$='receipt[" + c + "][rc_invoice_id]']";
                        $(temp).val(obj.id[i]);
                        temp = "input[name$='receipt[" + c + "][rc_details_sn]']";
                        $(temp).val(obj.si_details_sn[i]);
                        temp = "input[name$='receipt[" + c + "][rc_details_si]']";
                        $(temp).val(obj.si_details_si[i]);
                        temp = "input[name$='receipt[" + c + "][rc_details_date]']";
                        $(temp).val(obj.si_details_date[i]);
                        temp = "input[name$='receipt[" + c + "][rc_details_amount]']";
                        $(temp).val(obj.si_details_amount[i]);

                        var amount = obj.si_details_amount[i].replace(/,/g, '');
                        amount = parseFloat(amount);
                        console.log(amount);

                        total = total + amount;

                        temp = "input[name$='receipt[" + c + "][rc_due]']";
                        $(temp).val(obj.due[i]);
                        c++;

                    }

                    $('#rc_amount_total').text('Total Due: ' + parseFloat(total).toFixed(2));

                    rc_preview(e);
                } // /success
            }); // /fetch selected member info
        });

        $('#edit_rc_sales_invoice').select2({
            ajax: {
                url: '../assets/custom/api_get/get_sale_invoice.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Sale Invoice'
        });
    };



    var purchase_order = function () {

        var portlet = new KTPortlet('kt_portlet_add_po');

        $('#kt_purchase_order_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });

        $('#kt_purchase_order_user').select2({
            ajax: {
                url: '../assets/custom/api_get/get_user.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select User',
            allowClear: true
        });

        $('#kt_purchase_order_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            allowClear: true
        });

        $('#purchase_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#po_supplier').select2({
            ajax: {
                url: '../assets/custom/api_get/get_supplier.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Supplier',
            selectOnClose: true
        });

        $('#po_supplier').on("select2:select", function (e) {
            selected_supplier = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_supplier_address.php',
                type: 'post',
                data: { member_id: selected_supplier },
                dataType: 'json',
                success: function (response) {
                    $("#po_state").val(response.state);
                } // /success
            });
        });

        // $('#po_shipping_state').select2({
        //     ajax: {
        //         url: '../assets/custom/api_get/get_states.php',
        //         dataType: 'json'
        //     },
        //     width: '100%',
        //     placeholder: 'Select State',
        //     // tags: true,
        //     allowClear: true
        // });

        // $('#po_supplier').on("select2:select", function(e) {
        //     $('[data-repeater-list="purchase_order"]').empty();
        //     $('[data-repeater-create="purchase_order"]').click();
        //     var tmp = "input[name$='purchase_order[0][po_sn]']";
        //     $(tmp).val(1);
        //     selected_client = $(e.currentTarget).val();
        //     $.ajax({
        //         url: '../assets/custom/api_get/get_supplier_address.php',
        //         type: 'post',
        //         data: { member_id: selected_client },
        //         dataType: 'json',
        //         success: function(response) {

        //             $("#po_shipping_name").val(response.print_name);

        //             var address = JSON.parse(response.address);
        //             $("#po_shipping_add_1").val(address.address_1);
        //             $("#po_shipping_add_2").val(address.address_2);
        //             $("#po_shipping_city").val(address.city);
        //             $("#po_shipping_pincode").val(address.pincode);
        //             $("#po_shipping_state").empty().append($("<option/>").val(response.state).text(response.state)).val(response.state).trigger("change");

        //             $("#po_shipping_country").val(response.country);

        //         } // /success
        //     }); // /fetch selected member info
        // });

        // $('#po_supplier').on("select2:select", function(e) {
        //     $('[data-repeater-list="purchase_order"]').empty();
        //     $('[data-repeater-create="purchase_order"]').click();
        //     var tmp = "input[name$='purchase_order[0][po_sn]']";
        //     $(tmp).val(1);
        //     selected_client = $(e.currentTarget).val();
        //     $('.po_quotation-select2').val(null).trigger('change');
        // });
    };

    var purchase = function () {
        var portlet = new KTPortlet('kt_portlet_add_pi');

        $('#kt_purchase_invoice_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });
        $('#kt_ppurchase_invoice_status').select2({
            width: '100%',
            placeholder: 'Select Status',
            allowClear: true
        });

        $('#kt_purchase_invoice_user').select2({
            ajax: {
                url: '../assets/custom/api_get/get_user.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select User',
            allowClear: true
        });

        $('#kt_purchase_invoice_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            allowClear: true
        });

        $('#purchase_invoice_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#pi_supplier').select2({
            ajax: {
                url: '../assets/custom/api_get/get_supplier.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Supplier',
            selectOnClose: true
        });

        $('#pi_purchase_order').select2({
            ajax: {
                url: '../assets/custom/api_get/get_purchase_order.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                        supplier: selected_supplier //Get your value from other elements using Query, for example.
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Purchase Order',
            multiple: true
        });

        $('#pi_supplier').on("select2:select", function (e) {
            $('[data-repeater-list="purchase_invoice"]').empty();
            $('[data-repeater-create="purchase_invoice"]').click();
            var tmp = "input[name$='purchase_invoice[0][pi_sn]']";
            $(tmp).val(1);
            selected_supplier = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_supplier_address.php',
                type: 'post',
                data: { member_id: selected_supplier },
                dataType: 'json',
                success: function (response) {
                    $("#pi_state").val(response.state);

                    var getmob = response.contacts;
                    if (getmob != null) {
                        getmob = JSON.parse(getmob);
                    }
                    if (getmob.mobile[0] != null) {
                        $("#mobile").val(getmob.mobile[0]);
                    }
                    else {
                        $("#mobile").val('');
                    }
                } // /success
            });
            $('.pi_purchase_order-select2').val(null).trigger('change');

        });

        $('.pi_purchase_order-select2').on("select2:select", function (e) {
            $('[data-repeater-list="purchase_invoice"]').empty();
            $('[data-repeater-create="purchase_invoice"]').click();
            var tmp = "input[name$='purchase_invoice[0][pi_sn]']";
            $(tmp).val(1);

            var id = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/purchase_order/getSelectedPOPull.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {

                    var temp = '';
                    var obj = JSON.parse(response.items);
                    var addons = JSON.parse(response.addons);

                    var length = obj.product.length;
                    var count = 0;
                    var c = 0;

                    $('#pi_freight').val(addons.freight);
                    $('#pi_pf').val(addons.pf);
                    $('#pi_tot_discount').val(addons.discount);

                    for (var i = 0; i < length; i++) {
                        if (obj.quantity[i] - obj.received[i] > 0) {
                            count++;
                        }
                    }

                    for (var i = 1; i < count; i++) {
                        $('#pi_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        if (obj.quantity[i] - obj.received[i] > 0) {
                            temp = "select[name$='purchase_invoice[" + c + "][pi_product_name]']";
                            var pr = obj.product[i];
                            $(temp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");

                            temp = "input[name$='purchase_invoice[" + c + "][pi_qty]']";
                            $(temp).val(obj.quantity[i] - obj.received[i]);
                            temp = "select[name$='purchase_invoice[" + c + "][pi_unit]']";
                            $(temp).empty().append($("<option/>").val(obj.unit[i]).text(obj.unit[i])).val(obj.unit[i]).trigger("change");
                            temp = "input[name$='purchase_invoice[" + c + "][pi_rate]']";
                            $(temp).val(obj.price[i]);
                            temp = "input[name$='purchase_invoice[" + c + "][pi_dsc]']";
                            $(temp).val(obj.discount[i]);
                            temp = "input[name$='purchase_invoice[" + c + "][pi_hsn]']";
                            $(temp).val(obj.hsn[i]);
                            temp = "select[name$='purchase_invoice[" + c + "][pi_tax]']";
                            $(temp).val(obj.tax[i]).trigger("change");
                            temp = "input[name$='purchase_invoice[" + c + "][pi_product_description]']";
                            $(temp).val(obj.desc[i]);

                            temp = "textarea[name$='purchase_invoice[" + c + "][pi_product_add_description]']";
                            var temp_val = obj.long_desc[i];
                            temp_val = temp_val.replace(/\|/g, "\r\n");
                            $(temp).val(temp_val);

                            var temp_textarea = $(temp);
                            autosize(temp_textarea);
                            c++;
                        }

                    }
                    pi_preview(e);
                } // /success
            }); // /fetch selected member info
        });
    };

    var payments = function () {

        $('.py_mode-select2').select2({
            width: '100%',
            placeholder: 'Select Mode',
            allowClear: true
        });

        $('.py_bank-select2').select2({
            ajax: {
                url: '../assets/custom/api_get/get_bank.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Bank',
            allowClear: true
        });

        $('.edit_py_bank-select2').select2({
            ajax: {
                url: '../assets/custom/api_get/get_bank.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Bank',
            allowClear: true,
            selectOnClose: true
        });

        $('#py_btn_advance').on("click", function (e) {
            document.getElementById("py_advance_amount").style.display = "block";

        });

        $('.py_bank-select2').on("select2:select", function (e) {
            var id = $(e.currentTarget).val();
            id = encodeURIComponent(id);
            console.log(id);

            var amount = $('#amount').val();

            if (id != 'Cash' && amount != '' && !isNaN(amount)) {
                document.getElementById("bank_details").style.display = "inline-flex";
                document.getElementById("bank_details_title").style.display = "inline-flex";
            } else {
                document.getElementById("bank_details").style.display = "none";
                document.getElementById("bank_details_title").style.display = "none";
            }
        });

        $('#payment_amount').keyup(function (e) {
            var amount = $('#payment_amount').val();

            var bank = $('.py_bank-select2').val();
            if (amount != '' && !isNaN(amount)) {
                document.getElementById("invoice_details").style.display = "inline-flex";
                document.getElementById("invoice_details_title").style.display = "inline-flex";

                if (bank != 'Cash') {
                    document.getElementById("bank_details").style.display = "inline-flex";
                    document.getElementById("bank_details_title").style.display = "inline-flex";
                } else {
                    document.getElementById("bank_details").style.display = "none";
                    document.getElementById("bank_details_title").style.display = "none";
                }

            } else {
                document.getElementById("invoice_details").style.display = "none";
                document.getElementById("invoice_details_title").style.display = "none";

                document.getElementById("bank_details").style.display = "none";
                document.getElementById("bank_details_title").style.display = "none";
            }



        });

        $('#py_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#py_ins_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#edit_py_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#py_supplier').select2({
            ajax: {
                url: '../assets/custom/api_get/get_supplier.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Supplier'
        });

        $('#edit_py_client').select2({
            ajax: {
                url: '../assets/custom/api_get/get_client.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Client',
            tags: true,
            selectOnClose: true
        });

        $('#py_mode').select2({
            width: '100%',
            placeholder: 'Select Mode',
            selectOnClose: true
        });

        $('#edit_py_mode').select2({
            width: '100%',
            placeholder: 'Select Mode',
            selectOnClose: true
        });

        $('#py_supplier').on("select2:select", function (e) {
            $('[data-repeater-list="payment"]').empty();
            // $('[data-repeater-create="payment"]').click();
            // var tmp = "input[name$='payment[0][py_sn]']";
            // $(tmp).val(1);

            var id = $(e.currentTarget).val();
            id = encodeURIComponent(id);
            $.ajax({
                url: '../assets/custom/api_get/getPendingPurchase.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {

                    var temp = '';
                    var obj = JSON.parse(response.result);

                    var length = obj.pi_details_sn.length;

                    var c = 0;

                    var total = 0;

                    for (var i = 0; i < length; i++) {
                        $('#py_btn_add').click();
                    }
                    for (var i = 0; i < length; i++) {

                        temp = "input[name$='payment[" + c + "][py_invoice_id]']";
                        $(temp).val(obj.id[i]);
                        temp = "input[name$='payment[" + c + "][py_details_sn]']";
                        $(temp).val(obj.pi_details_sn[i]);
                        temp = "input[name$='payment[" + c + "][py_details_pi]']";
                        $(temp).val(obj.pi_details_pi[i]);
                        temp = "input[name$='payment[" + c + "][py_details_date]']";
                        $(temp).val(obj.pi_details_date[i]);
                        temp = "input[name$='payment[" + c + "][py_details_amount]']";
                        $(temp).val(obj.pi_details_amount[i]);


                        var amount = obj.pi_details_amount[i].replace(/,/g, '');
                        amount = parseFloat(amount);
                        console.log(amount);

                        total = total + amount;


                        temp = "input[name$='payment[" + c + "][py_due]']";
                        $(temp).val(obj.due[i]);
                        c++;

                    }
                    console.log(total);
                    $('#py_amount_total').text('Total Due: ' + parseFloat(total).toFixed(2));

                    py_preview(e);
                } // /success
            }); // /fetch selected member info
        });

        $('#edit_py_sales_invoice').select2({
            ajax: {
                url: '../assets/custom/api_get/get_purchase_invoice.php',
                type: 'POST',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term, // search term
                    };
                }
            },
            width: '100%',
            placeholder: 'Select Purchase Invoice'
        });
    };

    var assemblies = function () {

        $('#assembly_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#composite_product').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            tags: true,
            selectOnClose: true
        });
        $('#composite_product').on("select2:select", function (e) {

            var id = $(e.currentTarget).val();

            $.ajax({
                url: '../assets/custom/api_get/get_product_stock.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    $("#current_composite_stock").val(response);
                } // /success
            }); // /fetch selected member info
        });

        $('#composite_product_2').select2({
            ajax: {
                url: '../assets/custom/api_get/get_product.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Product',
            tags: true,
            selectOnClose: true
        });
        $('#composite_product_2').on("select2:select", function (e) {

            var id = $(e.currentTarget).val();

            $.ajax({
                url: '../assets/custom/api_get/get_product_info.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    $("#composite_product_2_description").val(response.description);
                } // /success
            }); // /fetch selected member info
        });

        $('#as_type').select2({
            width: '100%',
            placeholder: 'Select Type',
            selectOnClose: true
        });

        $('#as_type').on("select2:select", function (e) {

            var type = $(e.currentTarget).val();
            var quantity = $("#composite_qty").val();
            var current_composite_stock = $("#current_composite_stock").val();

            if (type == 'Assembled') {
                var result_stock = +current_composite_stock + +quantity;
            }
            else {
                var result_stock = current_composite_stock - quantity;
            }

            $("#result_composite_stock").val(result_stock);
        });

        $('#composite_qty').on("change", function (e) {
            composite_quantity = $("#composite_qty").val();
        });
    };

    var settings = function () {

        $('.settings_group-select2').select2({
            ajax: {
                url: '../assets/custom/api_get/get_group.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Group',
            allowClear: true
        });

        $('.settings_group-select2').on("select2:select", function (e) {

            var id = $(e.currentTarget).val();
            $.ajax({
                url: '../assets/custom/api_get/get_default_make.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {

                    // console.log(response.default_make);
                    if (response.default_make == '1') {
                        $("#settings_make").attr('checked', 'checked');
                    } else
                        $("#settings_make").removeAttr('checked');

                }
            });
        });
    };

    var materials_received = function () {
        $('#mr_supplier_name').select2({
            ajax: {
                url: '../assets/custom/api_get/get_supplier.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Supplier',
            tags: true,
            selectOnClose: true
        });

        $('#mr_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });
    };

    var contra = function () {

        $('.contra_bank_1-select2').select2({
            ajax: {
                url: '../assets/custom/api_get/get_bank.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Bank',
            allowClear: true
        });

        $('.contra_bank_2-select2').select2({
            ajax: {
                url: '../assets/custom/api_get/get_bank.php',
                dataType: 'json'
            },
            width: '100%',
            placeholder: 'Select Bank',
            allowClear: true
        });

        $('#contra_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });
    };

    var journal = function () {

        $('#journal_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });
    };

    return {
        init: function () {
            search();
            payments();
            receipts();
            products();
            clients();
            suppliers();
            quotation();
            purchase_order();
            purchase();
            sales_order();
            sales();
            enquiry();
            assemblies();
            settings();
            materials_received();
            whatsapp();
            proforma_invoice();
            credit_note();
            debit_note();
            contra();
            journal();
        },
    };
}();

function externalLinks() {
    if (!document.getElementsByTagName)
        return;

    var anchors = document.getElementsByTagName("a");
    for (var i = 0; i < anchors.length; i++) {
        var anchor = anchors[i];
        if (anchor.getAttribute("href") && anchor.getAttribute("rel") == "external")
            anchor.target = "_blank";
    }
}

function setStatus(id = true, status, script) {
    if (id) {
        $.ajax({
            url: '../assets/custom/api_set/set_status.php',
            type: 'post',
            data: { member_id: id, status: status, script: script },
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Status Changed',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    if (script == "quotation") {
                        manageQuotationTable.reload();
                    } else if (script == "sales_order") {
                        manageSalesOrderTable.reload();
                    } else if (script == "sales_invoice") {
                        manageSalesInvoiceTable.reload();
                    } else if (script == "purchase_order") {
                        managePurchaseOrderTable.reload();
                    } else if (script == "purchase_invoice") {
                        managePurchaseInvoiceTable.reload();
                    } else if (script == "enquiry") {
                        manageEnquiryTable.reload();
                    }

                } else {
                    swal.fire({
                        position: 'top-right',
                        type: 'error',
                        title: 'There were some errors in your submission.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function appendLeadingZeroes(n) {
    if (n <= 9) {
        return "0" + n;
    }
    return n
}

function dcstoFixed(number, decimals) {
    var x = Math.pow(10, Number(decimals) + 1);
    return (Number(number) + (1 / x)).toFixed(decimals)
}

//***************************************************** -Bulk Actions- *****************************************************

var Product_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        manageProductTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = manageProductTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count
            console.log("Clicked");

            $('#kt_subheader_group_selected_rows').html(count);

            if (count > 0) {
                $('#kt_subheader_search').addClass('kt-hidden');
                $('#kt_subheader_group_actions').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search').removeClass('kt-hidden');
                $('#kt_subheader_group_actions').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

        $('#kt_subheader_group_actions_product_excel').on('click', function () {

            var ids = manageProductTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';


            }

            var url = 'https://www.easthyde.com/assets/custom/api_excel/export_product.php?ids=' + id_list;
            console.log(url);
            window.open(url, '_blank');
            generateExcel("Excel file is being generated, kindly wait for 5 mins and then download the file");


        });
    
    

        $('#kt_subheader_group_actions_product_excel_all').on('click', function () {
            // Instead of selecting specific IDs, export all products
            var url = 'https://www.easthyde.com/assets/custom/api_excel/export_product.php?ids=all';
            console.log(url);
            window.open(url, '_blank');
            generateExcel("Excel file is being generated, kindly wait for 5 mins and then download the file");
        });
    
    }
    

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();


var Purchase_Order_Group = function () {
    var selectionPO = function () {
        // event handler on check and uncheck on records
        managePurchaseBagTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = managePurchaseBagTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count
            // console.log("Clicked");
            $('#kt_subheader_group_selected_rows_PO').html(count);

            if (count > 0) {
                $('#kt_subheader_search').addClass('kt-hidden');
                $('#kt_subheader_group_actions_purchase_order').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_purchase_order').addClass('kt-hidden');
            }
        });
    }

    var selectedAdd = function () {

        $('#kt_subheader_group_actions_bag_po').on('click', function () {

            var ids = managePurchaseBagTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';
            var id = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';

                // id = ids[i];
                var flag = 0;

            }

            $.ajax({
                url: '../assets/custom/purchase_bag/getSelectedPurchaseBag.php',
                type: 'post',
                data: { member_id: id_list },
                dataType: 'json',
                success: function (response) {

                    console.log(response);
                    var array = JSON.parse(response.data);
                    console.log(array);

                    // var quantity = JSON.parse(response.quantity);

                    var rep = document.getElementById('purchase_order_list');
                    var rowsCount = rep.childNodes.length;

                    var flag = 0;

                    for (var i = 0, l = array.items.length; i < l; i++) {

                        var tmp = "input[name$='purchase_order[0][po_product_description]']";
                        var desc = $(tmp).val();
                        // console.log(desc);

                        if (rowsCount == 1 && desc == '' && flag == '0') {
                            rowsCount -= 1;
                            flag = 1;
                        } else {
                            $('[data-repeater-create="purchase_order"]').click();
                        }

                        var tmp = "select[name$='purchase_order[" + rowsCount + "][po_product_name]']";
                        var pr = array.items[i];
                        $(tmp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");
                        // console.log(tmp);

                        tmp = "input[name$='purchase_order[" + rowsCount + "][po_qty]']";
                        $(tmp).val(array.quantity[i]);
                        $("#po_pf").val('0');
                        rowsCount++;
                    }

                }
            });


            managePurchaseBagTable.reload();
            swal.fire({
                position: 'top-right',
                type: 'info',
                title: 'Products Added in the list above.',
                showConfirmButton: false,
                timer: 1500
            });


        });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selectionPO();
            selectedAdd();
        },
    };
}();

var Payment_Followup_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        managePaymentFollowupTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = managePaymentFollowupTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count
            console.log("Clicked");

            $('#kt_subheader_group_selected_rows_payment_followup').html(count);

            if (count > 0) {
                $('#kt_subheader_search').addClass('kt-hidden');
                $('#kt_subheader_group_actions_payment_followup').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_payment_followup').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

        $('#kt_subheader_group_actions_payment_followup').on('click', function () {

            var ids = managePaymentFollowupTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';


            }

            var url = '../assets/custom/payment_follow_up.php?ids=' + id_list;
            window.open(url, '_blank');
            generateExcel("Excel file is being generated, kindly wait for 5 mins and then download the file");


        });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();

var Purchase_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        managePurchaseInvoiceTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = managePurchaseInvoiceTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count

            $('#kt_subheader_group_selected_rows_purchase_xml').html(count);

            console.log(count);

            if (count > 0) {
                $('#kt_subheader_search_purchase_xml').addClass('kt-hidden');
                $('#kt_subheader_group_actions_purchase_xml').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search_purchase_xml').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_purchase_xml').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

        $('#kt_subheader_group_actions_purchase_excel').on('click', function () {

            var ids = managePurchaseInvoiceTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }

            var url = '../assets/custom/api_excel/purchase.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'Excel file generated successfully',
                html: 'You may download the Excel <a href="../assets/custom/api_excel/purchase.xlsx" download="purchase.xlsx" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });

        $('#kt_subheader_group_actions_purchase_all_excel').on('click', function () {

            var url = '../assets/custom/api_excel/purchase.php?ids=all';
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the Excel file <a href="../assets/custom/api_excel/purchase.xlsx" download="purchase.excel" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();

var Payment_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        managePaymentsTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = managePaymentsTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count

            $('#kt_subheader_group_selected_rows_payment_xml').html(count);

            if (count > 0) {
                $('#kt_subheader_search_payment').addClass('kt-hidden');
                $('#kt_subheader_group_actions_payment_xml').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search_payment').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_payment_xml').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

        $('#kt_subheader_group_actions_payment_xml_btn').on('click', function () {

            var ids = managePaymentsTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }

            var url = '../assets/custom/api_xml/payments.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the XML <a href="../assets/custom/api_xml/payments.xml" download="payments.xml" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });

        $('#kt_subheader_group_actions_payment_excel').on('click', function () {

            var url = '../assets/custom/api_excel/payments.php?ids=all';
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'Excel file generated successfully',
                html: 'You may download the Excel <a href="../assets/custom/api_excel/payments.xlsx" download="payments.xlsx" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();

var Sales_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        manageSalesInvoiceTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = manageSalesInvoiceTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count

            $('#kt_subheader_group_selected_rows_sales_xml').html(count);

            if (count > 0) {
                $('#kt_subheader_search_sales').addClass('kt-hidden');
                $('#kt_subheader_group_actions_sales_xml').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search_sales').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_sales_xml').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

       
        $('#kt_subheader_group_actions_sales_excel').on('click', function () {

            var ids = manageSalesInvoiceTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });
        
            var id_list = '';
        
            for (var i = 0; i < ids.length; i++) {
                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }
        
            var url = '../assets/custom/api_excel/sales.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });
        
            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'Excel file generated successfully',
                html: 'You may download the Excel <a href="../assets/custom/api_excel/sales.xlsx" download="sales.xlsx" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });
        
        });
       
                

        $('#kt_subheader_group_actions_sales_all_xml').on('click', function () {

            var url = '../assets/custom/api_xml/sales.php?ids=all';
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the XML <a href="../assets/custom/api_xml/sales.xml" download="sales.xml" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();
var Secondary_Sales_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        manageSalesSecondaryTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = manageSalesSecondaryTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count

            $('#kt_subheader_group_selected_rows_secondary_sales_xml').html(count);

            if (count > 0) {
                $('#kt_subheader_search_sales').addClass('kt-hidden');
                $('#kt_subheader_group_actions_sales_xml').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search_sales').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_sales_xml').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

       
        $('#kt_subheader_group_actions_sales_excel').on('click', function () {

            var ids = manageSalesSecondaryTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });
        
            var id_list = '';
        
            for (var i = 0; i < ids.length; i++) {
                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }
        
            var url = '../assets/custom/api_excel/s_sales.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });
        
            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'Excel file generated successfully',
                html: 'You may download the Excel <a href="../assets/custom/api_excel/s_sales.xlsx" download="s_sales.xlsx" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });
        
        });
       
                

       
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();

var Receipt_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        manageReceiptsTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = manageReceiptsTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count

            $('#kt_subheader_group_selected_rows_receipt_xml').html(count);

            if (count > 0) {
                $('#kt_subheader_search_receipt').addClass('kt-hidden');
                $('#kt_subheader_group_actions_receipt_xml').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search_receipt').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_receipt_xml').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

        $('#kt_subheader_group_actions_receipt_xml_btn').on('click', function () {

            var ids = manageReceiptsTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }

            var url = '../assets/custom/api_xml/receipt.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the XML <a href="../assets/custom/api_xml/receipt.xml" download="receipt.xml" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });

        $('#kt_subheader_group_actions_receipt_excel').on('click', function () {

            var ids = manageReceiptsTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }

            var url = '../assets/custom/api_excel/receipt.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'Excel file generated successfully',
                html: 'You may download the Excel <a href="../assets/custom/api_excel/receipts.xlsx" download="receipts.xlsx" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });

        $('#kt_subheader_group_actions_receipt_pdf').on('click', function () {

            var ids = manageReceiptsTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }

            var url = '../assets/custom/api_pdf/receipt.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'PDF file generated successfully',
                html: 'You may download the PDF <a href="../assets/custom/api_pdf/receipts.pdf" download="receipts.pdf" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });

        $('#kt_subheader_group_actions_receipt_all_xml').on('click', function () {

            var url = '../assets/custom/api_xml/receipt.php?ids=all';
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the XML <a href="../assets/custom/api_xml/receipt.xml" download="receipt.xml" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();

var Credit_Note_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        manageCreditNoteTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = manageCreditNoteTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count

            $('#kt_subheader_group_selected_rows_credit_note_xml').html(count);

            if (count > 0) {
                $('#kt_subheader_search_credit_note').addClass('kt-hidden');
                $('#kt_subheader_group_actions_credit_note_xml').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search_credit_note').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_credit_note_xml').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

        $('#kt_subheader_group_actions_credit_note_xml_btn').on('click', function () {

            var ids = manageCreditNoteTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }

            var url = '../assets/custom/api_xml/credit_note.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the XML <a href="../assets/custom/api_xml/credit_note.xml" download="credit_note.xml" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });

        $('#kt_subheader_group_actions_credit_note_all_xml').on('click', function () {

            var url = '../assets/custom/api_xml/credit_note.php?ids=all';
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the XML <a href="../assets/custom/api_xml/credit_note.xml" download="credit_note.xml" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();

var Debit_Note_Group = function () {

    var selection = function () {

        // event handler on check and uncheck on records
        manageDebitNoteTable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
            var checkedNodes = manageDebitNoteTable.rows('.kt-datatable__row--active').nodes(); // get selected records
            var count = checkedNodes.length; // selected records count

            $('#kt_subheader_group_selected_rows_debit_note_xml').html(count);

            if (count > 0) {
                $('#kt_subheader_search_debit_note').addClass('kt-hidden');
                $('#kt_subheader_group_actions_debit_note_xml').removeClass('kt-hidden');
            } else {
                $('#kt_subheader_search_debit_note').removeClass('kt-hidden');
                $('#kt_subheader_group_actions_debit_note_xml').addClass('kt-hidden');
            }
        });
    }

    var selectedExport = function () {

        $('#kt_subheader_group_actions_debit_note_xml_btn').on('click', function () {

            var ids = manageDebitNoteTable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                return $(chk).val();
            });

            var id_list = '';

            for (var i = 0; i < ids.length; i++) {

                if (i == (ids.length - 1))
                    id_list += ids[i];
                else
                    id_list += ids[i] + ',';
            }

            var url = '../assets/custom/api_xml/debit_note.php?ids=' + id_list;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the XML <a href="../assets/custom/api_xml/debit_note.xml" download="debit_note.xml" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });

        $('#kt_subheader_group_actions_debit_note_all_xml').on('click', function () {

            var url = '../assets/custom/api_xml/debit_note.php?ids=all';
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (response) { }
            });

            swal.fire({
                position: 'top-right',
                type: 'success',
                title: 'XML file generated successfully',
                html: 'You may download the XML <a href="../assets/custom/api_xml/debit_note.xml" download="debit_note.xml" target="_blank">here</a>.',
                showConfirmButton: false,
                showCancelButton: true
            });

        });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    return {
        // public functions
        init: function () {
            selection();
            selectedExport();
        },
    };
}();

//***************************************************** -Preview Functions- *****************************************************

function e_preview(e) {

    setTimeout(() => {

        var rep = document.getElementById('enquiry_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            for (var i = 0; i < rowsCount; i++) {

                tmp = "input[name$='enquiry[" + i + "][e_sn]']";
                $(tmp).val(i + 1);
            }
        }

    }, 100);
}

function q_preview(e) {

    var client = $("#q_client").val();
    var state = $("#q_state").val();

    setTimeout(() => {

        var rep = document.getElementById('quotation_list');
        var rowsCount = rep.childNodes.length;

        function dcs_round(num) {
            var m = Number((Math.abs(num) * 100).toPrecision(15));
            return Math.round(m) / 100 * Math.sign(num);
        }

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var qty = 0;
            var price = 0;
            var discount = 0;
            var tax = 0;

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='quotation[" + i + "][q_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='quotation[" + i + "][q_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='quotation[" + i + "][q_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='quotation[" + i + "][q_tax]']";
                tax = $(tmp).val();

                tmp = "input[name$='quotation[" + i + "][q_sn]']";
                $(tmp).val(i + 1);

                tmp = "textarea[name$='quotation[" + i + "][q_product_add_description]']";
                var temp_textarea = $(tmp);
                autosize(temp_textarea);

                price = price.replace(/,/g, '');
                price = parseFloat(price);

                var total = 0;

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }
                total = qty * price;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;
                var tax_pr = '0';
                var gross_pr = total;

                if (discount != '') {
                    total = total * ((100 - parseFloat(discount)) / 100);
                    gross_pr = gross_pr * ((100 - parseFloat(discount)) / 100);
                }

                if (state == 'WEST BENGAL') {
                    if (tax != '') {
                        tax = tax / 2;
                        cgst = total * (parseFloat(tax) / 100);
                        sgst = total * (parseFloat(tax) / 100);
                        cgst = Math.round(cgst * 100) / 100;
                        sgst = Math.round(sgst * 100) / 100;
                        total = total + cgst + sgst;
                    }
                } else {
                    if (tax != '') {
                        igst = total * (parseFloat(tax) / 100);
                        igst = Math.round(igst * 100) / 100;
                        total = total + igst;
                    }
                }

                tax_pr = cgst + sgst + igst;

                total_final += Math.round(total * 100) / 100;
                tax_final += Math.round(tax_pr * 100) / 100;
                gross_final += Math.round(gross_pr * 100) / 100;

                total = Math.round(total * 100) / 100;
                tax_pr = Math.round(tax_pr * 100) / 100;
                gross_pr = Math.round(gross_pr * 100) / 100;

                if (qty != '' && price != '') {
                    tmp = "input[name$='quotation[" + i + "][q_gross_pr]']";
                    $(tmp).val(gross_pr.toFixed(2));
                    tmp = "input[name$='quotation[" + i + "][q_cgst]']";
                    $(tmp).val(cgst.toFixed(2));
                    tmp = "input[name$='quotation[" + i + "][q_sgst]']";
                    $(tmp).val(sgst.toFixed(2));
                    tmp = "input[name$='quotation[" + i + "][q_igst]']";
                    $(tmp).val(igst.toFixed(2));
                    tmp = "input[name$='quotation[" + i + "][q_tax_pr]']";
                    $(tmp).val(tax_pr.toFixed(2));
                    tmp = "input[name$='quotation[" + i + "][q_total_pr]']";
                    $(tmp).val(total.toFixed(2));
                } else {
                    tmp = "input[name$='quotation[" + i + "][q_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='quotation[" + i + "][q_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='quotation[" + i + "][q_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='quotation[" + i + "][q_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='quotation[" + i + "][q_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='quotation[" + i + "][q_total_pr]']";
                    $(tmp).val('');
                }

            }

            var freight = $('#q_freight').val();
            var pf = $('#q_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            var addon = parseFloat(freight) + parseFloat(pf);
            freight = parseFloat(freight);
            pf = parseFloat(pf);

            if (state != 'WEST BENGAL') {
                var freight_tax = freight * 18 / 100;
                var pf_tax = pf * 18 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#q_freight_igst").val(freight_tax);
                $("#q_pf_igst").val(pf_tax);

                tax_final = parseFloat(tax_final) + parseFloat(freight_tax) + parseFloat(pf_tax);

            }
            else {
                var freight_tax = freight * 9 / 100;
                var pf_tax = pf * 9 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#q_freight_cgst").val(freight_tax);
                $("#q_freight_sgst").val(freight_tax);
                $("#q_pf_cgst").val(pf_tax);
                $("#q_pf_sgst").val(pf_tax);

                tax_final = tax_final + parseFloat(freight_tax) + parseFloat(freight_tax) + parseFloat(pf_tax) + parseFloat(pf_tax);
            }
            total_final = parseFloat(gross_final) + parseFloat(addon) + parseFloat(tax_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;

            var roundoff = $('#q_round').val();
            console.log(roundoff);

            if (roundoff != '0') {

                if (fraction >= 0.5) {
                    var add_fraction = 1 - fraction;
                    $('#q_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                } else {
                    var add_fraction = -1 * fraction;
                    $('#q_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                }
            }

            console.log(tax_final);
            console.log(gross_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            console.log(tax_final);
            console.log(gross_final);

            $(".q_gross_final").val(gross_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".q_tax_final").val(tax_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".q_total_final").val(total_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#q_freight").val(freight.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#q_pf").val(pf.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
        }
    }, 1000);
}

function so_preview(e) {

    var client = $("#so_client").val();
    var state = $("#so_state").val();

    setTimeout(() => {
        var rep = document.getElementById('sales_order_list');
        var rowsCount = rep.childNodes.length;

        function dcs_round(num) {
            var m = Number((Math.abs(num) * 100).toPrecision(15));
            return Math.round(m) / 100 * Math.sign(num);
        }

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var qty = 0;
            var price = 0;
            var discount = 0;
            var tax = 0;

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='sales_order[" + i + "][so_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='sales_order[" + i + "][so_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='sales_order[" + i + "][so_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='sales_order[" + i + "][so_tax]']";
                tax = $(tmp).val();

                tmp = "input[name$='sales_order[" + i + "][so_sn]']";
                $(tmp).val(i + 1);

                price = price.replace(/,/g, '');
                price = parseFloat(price);

                var total = 0;

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }
                total = qty * price;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;
                var tax_pr = '0';
                var gross_pr = total;

                if (discount != '') {
                    total = total * ((100 - parseFloat(discount)) / 100);
                    gross_pr = gross_pr * ((100 - parseFloat(discount)) / 100);
                }

                if (state == 'WEST BENGAL') {
                    if (tax != '') {
                        tax = tax / 2;
                        cgst = total * (parseFloat(tax) / 100);
                        sgst = total * (parseFloat(tax) / 100);
                        cgst = Math.round(cgst * 100) / 100;
                        sgst = Math.round(sgst * 100) / 100;
                        total = total + cgst + sgst;
                    }
                } else {
                    if (tax != '') {
                        igst = total * (parseFloat(tax) / 100);
                        igst = Math.round(igst * 100) / 100;
                        total = total + igst;
                    }
                }

                tax_pr = cgst + sgst + igst;

                total_final += Math.round(total * 100) / 100;
                tax_final += Math.round(tax_pr * 100) / 100;
                gross_final += Math.round(gross_pr * 100) / 100;

                total = Math.round(total * 100) / 100;
                tax_pr = Math.round(tax_pr * 100) / 100;
                gross_pr = Math.round(gross_pr * 100) / 100;

                if (qty != '' && price != '') {
                    tmp = "input[name$='sales_order[" + i + "][so_gross_pr]']";
                    $(tmp).val(gross_pr);
                    tmp = "input[name$='sales_order[" + i + "][so_cgst]']";
                    $(tmp).val(cgst.toFixed(2));
                    tmp = "input[name$='sales_order[" + i + "][so_sgst]']";
                    $(tmp).val(sgst.toFixed(2));
                    tmp = "input[name$='sales_order[" + i + "][so_igst]']";
                    $(tmp).val(igst.toFixed(2));
                    tmp = "input[name$='sales_order[" + i + "][so_tax_pr]']";
                    $(tmp).val(tax_pr);
                    tmp = "input[name$='sales_order[" + i + "][so_total_pr]']";
                    $(tmp).val(total);
                } else {
                    tmp = "input[name$='sales_order[" + i + "][so_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_order[" + i + "][so_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_order[" + i + "][so_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_order[" + i + "][so_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_order[" + i + "][so_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_order[" + i + "][so_total_pr]']";
                    $(tmp).val('');
                }

            }

            var freight = $('#so_freight').val();
            var pf = $('#so_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            var addon = parseFloat(freight) + parseFloat(pf);
            freight = parseFloat(freight);
            pf = parseFloat(pf);

            if (state != 'WEST BENGAL') {
                var freight_tax = freight * 18 / 100;
                var pf_tax = pf * 18 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#so_freight_igst").val(freight_tax);
                $("#so_pf_igst").val(pf_tax);

                tax_final = parseFloat(tax_final) + parseFloat(freight_tax) + parseFloat(pf_tax);

            }
            else {
                var freight_tax = freight * 9 / 100;
                var pf_tax = pf * 9 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#so_freight_cgst").val(freight_tax);
                $("#so_freight_sgst").val(freight_tax);
                $("#so_pf_cgst").val(pf_tax);
                $("#so_pf_sgst").val(pf_tax);

                tax_final = tax_final + parseFloat(freight_tax) + parseFloat(freight_tax) + parseFloat(pf_tax) + parseFloat(pf_tax);
            }
            total_final = parseFloat(gross_final) + parseFloat(addon) + parseFloat(tax_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;

            var roundoff = $('#so_round').val();
            console.log(roundoff);

            if (roundoff != '0') {

                if (fraction >= 0.5) {
                    var add_fraction = 1 - fraction;
                    $('#so_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                } else {
                    var add_fraction = -1 * fraction;
                    $('#so_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                }
            }

            console.log(tax_final);
            console.log(gross_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            console.log(tax_final);
            console.log(gross_final);

            $(".so_gross_final").val(gross_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".so_tax_final").val(tax_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".so_total_final").val(total_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#so_freight").val(freight.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#so_pf").val(pf.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

        }
    }, 1000);
}

function cn_preview(e) {

    var client = $("#cn_client").val();

    var state = $("#cn_state").val();

    setTimeout(() => {
        var rep = document.getElementById('credit_note_list');
        var rowsCount = rep.childNodes.length;

        // rowsCount -= 2;

        // console.log(rowsCount);

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var qty = 0;
            var price = 0;
            var discount = 0;
            var tax = 0;

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='credit_note[" + i + "][cn_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='credit_note[" + i + "][cn_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='credit_note[" + i + "][cn_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='credit_note[" + i + "][cn_tax]']";
                tax = $(tmp).val();

                tmp = "input[name$='credit_note[" + i + "][cn_sn]']";
                $(tmp).val(i + 1);

                price = parseFloat(price);

                var total = 0;

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }
                total = qty * price;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;
                var tax_pr = '0';
                var gross_pr = total;

                if (discount != '') {
                    total = total * ((100 - parseFloat(discount)) / 100);
                    gross_pr = gross_pr * ((100 - parseFloat(discount)) / 100);
                }

                if (state == 'WEST BENGAL') {
                    if (tax != '') {
                        tax = tax / 2;
                        cgst = total * (parseFloat(tax) / 100);
                        sgst = total * (parseFloat(tax) / 100);
                        cgst = Math.round(cgst * 100) / 100;
                        sgst = Math.round(sgst * 100) / 100;
                        total = total + cgst + sgst;
                    }
                } else {
                    if (tax != '') {
                        igst = total * (parseFloat(tax) / 100);
                        igst = Math.round(igst * 100) / 100;
                        total = total + igst;
                    }
                }

                tax_pr = cgst + sgst + igst;


                total_final += Math.round(total * 100) / 100;
                tax_final += Math.round(tax_pr * 100) / 100;
                gross_final += Math.round(gross_pr * 100) / 100;

                total = Math.round(total * 100) / 100;
                tax_pr = Math.round(tax_pr * 100) / 100;
                gross_pr = Math.round(gross_pr * 100) / 100;

                if (qty != '' && price != '') {
                    tmp = "input[name$='credit_note[" + i + "][cn_gross_pr]']";
                    $(tmp).val(gross_pr);
                    tmp = "input[name$='credit_note[" + i + "][cn_cgst]']";
                    $(tmp).val(cgst.toFixed(2));
                    tmp = "input[name$='credit_note[" + i + "][cn_sgst]']";
                    $(tmp).val(sgst.toFixed(2));
                    tmp = "input[name$='credit_note[" + i + "][cn_igst]']";
                    $(tmp).val(igst.toFixed(2));
                    tmp = "input[name$='credit_note[" + i + "][cn_tax_pr]']";
                    $(tmp).val(tax_pr);
                    tmp = "input[name$='credit_note[" + i + "][cn_total_pr]']";
                    $(tmp).val(total);
                } else {
                    tmp = "input[name$='credit_note[" + i + "][cn_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='credit_note[" + i + "][cn_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='credit_note[" + i + "][cn_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='credit_note[" + i + "][cn_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='credit_note[" + i + "][cn_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='credit_note[" + i + "][cn_total_pr]']";
                    $(tmp).val('');
                }

            }

            var freight = $('#cn_freight').val();
            var pf = $('#cn_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            var addon = parseFloat(freight) + parseFloat(pf);

            if (state != 'WEST BENGAL')
                tax_final = tax_final + addon * 18 / 100;
            else {
                var addon_tax = addon * 9 / 100;
                tax_final = tax_final + addon_tax + addon_tax;
            }
            total_final = parseFloat(gross_final) + parseFloat(addon) + parseFloat(tax_final);

            total_final = Math.round(total_final * 100) / 100;
            tax_final = Math.round(tax_final * 100) / 100;
            gross_final = Math.round(gross_final * 100) / 100;

            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;


            total_final = Math.round(total_final * 100) / 100;
            tax_final = Math.round(tax_final * 100) / 100;
            gross_final = Math.round(gross_final * 100) / 100;

            $(".cn_gross_final").val(gross_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".cn_tax_final").val(tax_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".cn_total_final").val(total_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#cn_freight").val(freight.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#cn_pf").val(pf.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
        }
    }, 1000);
}

function dn_preview(e) {

    var supplier = $("#dn_supplier").val();

    var state = $("#dn_state").val();

    setTimeout(() => {
        var rep = document.getElementById('debit_note_list');
        var rowsCount = rep.childNodes.length;

        function dcs_round(num) {
            var m = Number((Math.abs(num) * 100).toPrecision(15));
            return Math.round(m) / 100 * Math.sign(num);
        }

        // rowsCount -= 2;

        // console.log(rowsCount);

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var qty = 0;
            var price = 0;
            var discount = 0;
            var tax = 0;

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='debit_note[" + i + "][dn_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='debit_note[" + i + "][dn_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='debit_note[" + i + "][dn_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='debit_note[" + i + "][dn_tax]']";
                tax = $(tmp).val();

                tmp = "input[name$='debit_note[" + i + "][dn_sn]']";
                $(tmp).val(i + 1);

                price = parseFloat(price);

                var total = 0;

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }
                total = qty * price;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;
                var tax_pr = '0';
                var gross_pr = total;

                if (discount != '') {
                    total = total * ((100 - parseFloat(discount)) / 100);
                    gross_pr = gross_pr * ((100 - parseFloat(discount)) / 100);
                }

                if (state == 'WEST BENGAL') {
                    if (tax != '') {
                        tax = tax / 2;
                        cgst = total * (parseFloat(tax) / 100);
                        sgst = total * (parseFloat(tax) / 100);
                        cgst = Math.round(cgst * 100) / 100;
                        sgst = Math.round(sgst * 100) / 100;
                        total = total + cgst + sgst;
                    }
                } else {
                    if (tax != '') {
                        igst = total * (parseFloat(tax) / 100);
                        igst = Math.round(igst * 100) / 100;
                        total = total + igst;
                    }
                }

                tax_pr = cgst + sgst + igst;


                total_final += Math.round(total * 100) / 100;
                tax_final += Math.round(tax_pr * 100) / 100;
                gross_final += Math.round(gross_pr * 100) / 100;

                total = Math.round(total * 100) / 100;
                tax_pr = Math.round(tax_pr * 100) / 100;
                gross_pr = Math.round(gross_pr * 100) / 100;

                if (qty != '' && price != '') {
                    tmp = "input[name$='debit_note[" + i + "][dn_gross_pr]']";
                    $(tmp).val(gross_pr);
                    tmp = "input[name$='debit_note[" + i + "][dn_cgst]']";
                    $(tmp).val(cgst.toFixed(2));
                    tmp = "input[name$='debit_note[" + i + "][dn_sgst]']";
                    $(tmp).val(sgst.toFixed(2));
                    tmp = "input[name$='debit_note[" + i + "][dn_igst]']";
                    $(tmp).val(igst.toFixed(2));
                    tmp = "input[name$='debit_note[" + i + "][dn_tax_pr]']";
                    $(tmp).val(tax_pr);
                    tmp = "input[name$='debit_note[" + i + "][dn_total_pr]']";
                    $(tmp).val(total);
                } else {
                    tmp = "input[name$='debit_note[" + i + "][dn_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='debit_note[" + i + "][dn_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='debit_note[" + i + "][dn_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='debit_note[" + i + "][dn_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='debit_note[" + i + "][dn_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='debit_note[" + i + "][dn_total_pr]']";
                    $(tmp).val('');
                }

            }

            var freight = $('#dn_freight').val();
            var pf = $('#dn_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            var addon = parseFloat(freight) + parseFloat(pf);

            if (state != 'WEST BENGAL')
                tax_final = tax_final + addon * 18 / 100;
            else {
                var addon_tax = addon * 9 / 100;
                tax_final = tax_final + addon_tax + addon_tax;
            }
            total_final = parseFloat(gross_final) + parseFloat(addon) + parseFloat(tax_final);

            total_final = Math.round(total_final * 100) / 100;
            tax_final = Math.round(tax_final * 100) / 100;
            gross_final = Math.round(gross_final * 100) / 100;


            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;

            var roundoff = $('#dn_round').val();
            console.log(roundoff);

            if (roundoff != '0') {

                if (fraction >= 0.5) {
                    var add_fraction = 1 - fraction;
                    $('#dn_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                } else {
                    var add_fraction = -1 * fraction;
                    $('#dn_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                }
            }

            console.log(tax_final);
            console.log(gross_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            console.log(tax_final);
            console.log(gross_final);

            $(".dn_gross_final").val(gross_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".dn_tax_final").val(tax_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".dn_total_final").val(total_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#dn_freight").val(freight.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#dn_pf").val(pf.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
        }
    }, 1000);
}

function pr_preview(e) {

    var client = $("#pr_client").val();
    var state = $("#pr_state").val();

    setTimeout(() => {
        var rep = document.getElementById('proforma_invoice_list');
        var rowsCount = rep.childNodes.length;

        function dcs_round(num) {
            var m = Number((Math.abs(num) * 100).toPrecision(15));
            return Math.round(m) / 100 * Math.sign(num);
        }

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var qty = 0;
            var price = 0;
            var discount = 0;
            var tax = 0;

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='proforma_invoice[" + i + "][pr_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='proforma_invoice[" + i + "][pr_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='proforma_invoice[" + i + "][pr_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='proforma_invoice[" + i + "][pr_tax]']";
                tax = $(tmp).val();

                tmp = "input[name$='proforma_invoice[" + i + "][pr_sn]']";
                $(tmp).val(i + 1);

                price = price.replace(/,/g, '');
                price = parseFloat(price);

                var total = 0;

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }
                total = qty * price;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;
                var tax_pr = '0';
                var gross_pr = total;

                if (discount != '') {
                    total = total * ((100 - parseFloat(discount)) / 100);
                    gross_pr = gross_pr * ((100 - parseFloat(discount)) / 100);
                }

                if (state == 'WEST BENGAL') {
                    if (tax != '') {
                        tax = tax / 2;
                        cgst = total * (parseFloat(tax) / 100);
                        sgst = total * (parseFloat(tax) / 100);
                        cgst = Math.round(cgst * 100) / 100;
                        sgst = Math.round(sgst * 100) / 100;
                        total = total + cgst + sgst;
                    }
                } else {
                    if (tax != '') {
                        igst = total * (parseFloat(tax) / 100);
                        igst = Math.round(igst * 100) / 100;
                        total = total + igst;
                    }
                }

                tax_pr = cgst + sgst + igst;

                total_final += Math.round(total * 100) / 100;
                tax_final += Math.round(tax_pr * 100) / 100;
                gross_final += Math.round(gross_pr * 100) / 100;

                total = Math.round(total * 100) / 100;
                tax_pr = Math.round(tax_pr * 100) / 100;
                gross_pr = Math.round(gross_pr * 100) / 100;

                if (qty != '' && price != '') {
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_gross_pr]']";
                    $(tmp).val(gross_pr);
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_cgst]']";
                    $(tmp).val(cgst.toFixed(2));
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_sgst]']";
                    $(tmp).val(sgst.toFixed(2));
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_igst]']";
                    $(tmp).val(igst.toFixed(2));
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_tax_pr]']";
                    $(tmp).val(tax_pr);
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_total_pr]']";
                    $(tmp).val(total);
                } else {
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_total_pr]']";
                    $(tmp).val('');
                }

            }

            var freight = $('#pr_freight').val();
            var pf = $('#pr_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            var addon = parseFloat(freight) + parseFloat(pf);
            freight = parseFloat(freight);
            pf = parseFloat(pf);

            if (state != 'WEST BENGAL') {
                var freight_tax = freight * 18 / 100;
                var pf_tax = pf * 18 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#pr_freight_igst").val(freight_tax);
                $("#pr_pf_igst").val(pf_tax);

                tax_final = parseFloat(tax_final) + parseFloat(freight_tax) + parseFloat(pf_tax);

            }
            else {
                var freight_tax = freight * 9 / 100;
                var pf_tax = pf * 9 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#pr_freight_cgst").val(freight_tax);
                $("#pr_freight_sgst").val(freight_tax);
                $("#pr_pf_cgst").val(pf_tax);
                $("#pr_pf_sgst").val(pf_tax);

                tax_final = tax_final + parseFloat(freight_tax) + parseFloat(freight_tax) + parseFloat(pf_tax) + parseFloat(pf_tax);
            }
            total_final = parseFloat(gross_final) + parseFloat(addon) + parseFloat(tax_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;

            var roundoff = $('#pr_round').val();
            console.log(roundoff);

            if (roundoff != '0') {

                if (fraction >= 0.5) {
                    var add_fraction = 1 - fraction;
                    $('#pr_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                } else {
                    var add_fraction = -1 * fraction;
                    $('#pr_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                }
            }

            console.log(tax_final);
            console.log(gross_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            console.log(tax_final);
            console.log(gross_final);

            $(".pr_gross_final").val(gross_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".pr_tax_final").val(tax_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".pr_total_final").val(total_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#pr_freight").val(freight.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#pr_pf").val(pf.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

        }
    }, 1000);
}

function si_preview(e) {

    var client = $("#si_client").val();
    var series = $("#si_series").val();
    var state = $("#si_state").val();
    console.log(state);

    setTimeout(() => {
        var rep = document.getElementById('sales_invoice_list');
        var rowsCount = rep.childNodes.length;

        function dcs_round(num) {
            var m = Number((Math.abs(num) * 100).toPrecision(15));
            return Math.round(m) / 100 * Math.sign(num);
        }

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var qty = 0;
            var price = 0;
            var discount = 0;
            var tax = 0;

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='sales_invoice[" + i + "][si_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='sales_invoice[" + i + "][si_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='sales_invoice[" + i + "][si_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='sales_invoice[" + i + "][si_tax]']";
                tax = $(tmp).val();

                tmp = "input[name$='sales_invoice[" + i + "][si_sn]']";
                $(tmp).val(i + 1);

                price = price.replace(/,/g, '');
                price = parseFloat(price);

                var total = 0;

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }
                total = qty * price;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;
                var tax_pr = '0';
                var gross_pr = total;

                if (discount != '') {
                    total = total * ((100 - parseFloat(discount)) / 100);
                    gross_pr = gross_pr * ((100 - parseFloat(discount)) / 100);
                }

                if (series != 'SECONDARY') {
                    if (state == 'WEST BENGAL') {
                        if (tax != '') {
                            tax = tax / 2;
                            cgst = total * (parseFloat(tax) / 100);
                            sgst = total * (parseFloat(tax) / 100);
                            cgst = dcs_round(cgst);
                            sgst = dcs_round(sgst);
                            total = total + cgst + sgst;
                        }
                    } else {
                        if (tax != '') {
                            igst = total * (parseFloat(tax) / 100);
                            igst = dcs_round(igst);
                            total = total + igst;
                        }
                    }
                }

                tax_pr = parseFloat(cgst) + parseFloat(sgst) + parseFloat(igst);

                console.log(tax_pr);

                console.log(tax_final);
                console.log(gross_final);

                total = dcstoFixed(total, 2);
                tax_pr = dcstoFixed(tax_pr, 2);
                gross_pr = dcstoFixed(gross_pr, 2);

                total_final += parseFloat(total);
                tax_final += parseFloat(tax_pr);
                gross_final += parseFloat(gross_pr);

                console.log(tax_final);
                console.log(gross_final);



                if (qty != '' && price != '') {
                    tmp = "input[name$='sales_invoice[" + i + "][si_gross_pr]']";
                    $(tmp).val(gross_pr);
                    tmp = "input[name$='sales_invoice[" + i + "][si_cgst]']";
                    $(tmp).val(cgst.toFixed(2));
                    tmp = "input[name$='sales_invoice[" + i + "][si_sgst]']";
                    $(tmp).val(sgst.toFixed(2));
                    tmp = "input[name$='sales_invoice[" + i + "][si_igst]']";
                    $(tmp).val(igst.toFixed(2));
                    tmp = "input[name$='sales_invoice[" + i + "][si_tax_pr]']";
                    $(tmp).val(tax_pr);
                    tmp = "input[name$='sales_invoice[" + i + "][si_total_pr]']";
                    $(tmp).val(total);
                } else {
                    tmp = "input[name$='sales_invoice[" + i + "][si_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_total_pr]']";
                    $(tmp).val('');
                }

            }

            var freight = $('#si_freight').val();
            var pf = $('#si_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            var addon = parseFloat(freight) + parseFloat(pf);
            freight = parseFloat(freight);
            pf = parseFloat(pf);

            if (state != 'WEST BENGAL') {
                var freight_tax = freight * 18 / 100;
                var pf_tax = pf * 18 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#si_freight_igst").val(freight_tax);
                $("#si_pf_igst").val(pf_tax);

                tax_final = parseFloat(tax_final) + parseFloat(freight_tax) + parseFloat(pf_tax);

            }
            else {
                var freight_tax = freight * 9 / 100;
                var pf_tax = pf * 9 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#si_freight_cgst").val(freight_tax);
                $("#si_freight_sgst").val(freight_tax);
                $("#si_pf_cgst").val(pf_tax);
                $("#si_pf_sgst").val(pf_tax);

                tax_final = tax_final + parseFloat(freight_tax) + parseFloat(freight_tax) + parseFloat(pf_tax) + parseFloat(pf_tax);
            }
            total_final = parseFloat(gross_final) + parseFloat(addon) + parseFloat(tax_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;

            var roundoff = $('#si_round').val();
            console.log(roundoff);

            if (roundoff != '0') {

                if (fraction >= 0.5) {
                    var add_fraction = 1 - fraction;
                    $('#si_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                } else {
                    var add_fraction = -1 * fraction;
                    $('#si_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                }
            }

            console.log(tax_final);
            console.log(gross_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            console.log(tax_final);
            console.log(gross_final);


            $(".si_gross_final").val(gross_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".si_tax_final").val(tax_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".si_total_final").val(total_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#si_freight").val(freight.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#si_pf").val(pf.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
        }
    }, 1000);
}

function si_preview_new(e) {

    var client = $("#si_client").val();
    var series = $("#si_series").val();
    var state = $("#si_state").val();

    setTimeout(() => {
        var rep = document.getElementById('sales_invoice_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {

            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {

                var qty = 0;
                var price = 0;
                var discount = 0;
                var tax = 0;

                var line_gross = 0;
                var line_tax = 0;
                var line_total = 0;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;

                tmp = "input[name$='sales_invoice[" + i + "][si_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='sales_invoice[" + i + "][si_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='sales_invoice[" + i + "][si_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='sales_invoice[" + i + "][si_tax]']";
                tax = $(tmp).val();

                tmp = "input[name$='sales_invoice[" + i + "][si_sn]']";
                $(tmp).val(i + 1);

                price = price.replace(/,/g, '');
                price = parseFloat(price);

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }

                price = price.toFixed(2);

                line_gross = qty * price;

                if (discount != '') {
                    line_gross = line_gross * ((100 - parseFloat(discount)) / 100);
                }

                line_gross = line_gross.toFixed(2);

                if (series != 'SECONDARY') {
                    if (state == 'WEST BENGAL') {
                        if (tax != '') {

                            tax = tax / 2;

                            cgst = line_gross * (parseFloat(tax) / 100);
                            sgst = line_gross * (parseFloat(tax) / 100);

                            cgst = cgst.toFixed(2);
                            sgst = sgst.toFixed(2);

                            line_tax = cgst + sgst;

                        }

                    }
                    else {
                        if (tax != '') {
                            igst = line_gross * (parseFloat(tax) / 100);
                            igst = igst.toFixed(2);

                            line_tax = igst;
                        }
                    }
                }

                line_total = line_gross + line_tax;


                tax_final += line_tax;
                gross_final += line_gross;
                total_final += line_total;

                if (qty != '' && price != '') {
                    tmp = "input[name$='sales_invoice[" + i + "][si_gross_pr]']";
                    $(tmp).val(line_gross);
                    tmp = "input[name$='sales_invoice[" + i + "][si_cgst]']";
                    $(tmp).val(cgst);
                    tmp = "input[name$='sales_invoice[" + i + "][si_sgst]']";
                    $(tmp).val(sgst);
                    tmp = "input[name$='sales_invoice[" + i + "][si_igst]']";
                    $(tmp).val(igst);
                    tmp = "input[name$='sales_invoice[" + i + "][si_tax_pr]']";
                    $(tmp).val(line_tax);
                    tmp = "input[name$='sales_invoice[" + i + "][si_total_pr]']";
                    $(tmp).val(line_total);
                } else {
                    tmp = "input[name$='sales_invoice[" + i + "][si_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='sales_invoice[" + i + "][si_total_pr]']";
                    $(tmp).val('');
                }
            }

            var freight = $('#si_freight').val();
            var pf = $('#si_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            freight = freight.toFixed(2);
            pf = pf.toFixed(2);

            var addon = freight + pf;

            if (state != 'WEST BENGAL') {
                var addon_tax = addon * 18 / 100;
                addon_tax = addon_tax.toFixed(2);

                tax_final = tax_final + addon_tax;
            }
            else {
                var addon_tax = addon * 9 / 100;
                addon_tax = addon_tax.toFixed(2);

                tax_final = tax_final + addon_tax + addon_tax;
            }

            total_final = gross_final + addon + tax_final;

            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;

            var roundoff = $('#so_round').val();

            if (roundoff != '0') {

                if (fraction >= 0.5) {
                    var add_fraction = 1 - fraction;
                    $('#si_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                } else {
                    var add_fraction = -1 * fraction;
                    $('#si_round').val(add_fraction.toFixed(2));
                    total_final += add_fraction;
                }
            }

            $(".si_gross_final").val(gross_final.replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".si_tax_final").val(tax_final.replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".si_total_final").val(total_final.replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#si_freight").val(freight.replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#si_pf").val(pf.replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
        }
    }, 1000);
}

function rc_preview(e) {

    setTimeout(() => {
        var rep = document.getElementById('receipt_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';
            var amount = 0;
            var tot_amount = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='receipt[" + i + "][rc_amount]']";
                amount = $(tmp).val();

                tmp = "input[name$='receipt[" + i + "][rc_sn]']";
                $(tmp).val(i + 1);

                tot_amount += parseFloat(amount);

            }

            $("#rc_amount").val(tot_amount.toFixed(2));
            // 
            console.log(tot_amount);
        }
    }, 1000);
}


function po_preview(e) {

    var supplier = $("#po_supplier").val();
    var state = $("#po_state").val();

    function dcs_round(num) {
        var m = Number((Math.abs(num) * 100).toPrecision(15));
        return Math.round(m) / 100 * Math.sign(num);
    }

    setTimeout(() => {
        var rep = document.getElementById('purchase_order_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var qty = 0;
            var price = 0;
            var discount = 0;
            var tax = 0;

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='purchase_order[" + i + "][po_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='purchase_order[" + i + "][po_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='purchase_order[" + i + "][po_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='purchase_order[" + i + "][po_tax]']";
                tax = $(tmp).val();

                tmp = "input[name$='purchase_order[" + i + "][po_sn]']";
                $(tmp).val(i + 1);

                price = price.replace(/,/g, '');
                price = parseFloat(price);

                var total = 0;

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }
                total = qty * price;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;
                var tax_pr = '0';
                var gross_pr = total;

                if (discount != '') {
                    total = total * ((100 - parseFloat(discount)) / 100);
                    gross_pr = gross_pr * ((100 - parseFloat(discount)) / 100);
                }

                if (state == 'WEST BENGAL') {
                    if (tax != '') {
                        tax = tax / 2;
                        cgst = total * (parseFloat(tax) / 100);
                        sgst = total * (parseFloat(tax) / 100);
                        cgst = Math.round(cgst * 100) / 100;
                        sgst = Math.round(sgst * 100) / 100;
                        total = total + cgst + sgst;
                    }
                } else {
                    if (tax != '') {
                        igst = total * (parseFloat(tax) / 100);
                        igst = Math.round(igst * 100) / 100;
                        total = total + igst;
                    }
                }

                tax_pr = cgst + sgst + igst;

                total_final += Math.round(total * 100) / 100;
                tax_final += Math.round(tax_pr * 100) / 100;
                gross_final += Math.round(gross_pr * 100) / 100;

                total = Math.round(total * 100) / 100;
                tax_pr = Math.round(tax_pr * 100) / 100;
                gross_pr = Math.round(gross_pr * 100) / 100;

                if (qty != '' && price != '') {
                    tmp = "input[name$='purchase_order[" + i + "][po_gross_pr]']";
                    $(tmp).val(gross_pr);
                    tmp = "input[name$='purchase_order[" + i + "][po_cgst]']";
                    $(tmp).val(cgst.toFixed(2));
                    tmp = "input[name$='purchase_order[" + i + "][po_sgst]']";
                    $(tmp).val(sgst.toFixed(2));
                    tmp = "input[name$='purchase_order[" + i + "][po_igst]']";
                    $(tmp).val(igst.toFixed(2));
                    tmp = "input[name$='purchase_order[" + i + "][po_tax_pr]']";
                    $(tmp).val(tax_pr);
                    tmp = "input[name$='purchase_order[" + i + "][po_total_pr]']";
                    $(tmp).val(total);
                } else {
                    tmp = "input[name$='purchase_order[" + i + "][po_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_order[" + i + "][po_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_order[" + i + "][po_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_order[" + i + "][po_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_order[" + i + "][po_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_order[" + i + "][po_total_pr]']";
                    $(tmp).val('');
                }

            }

            var freight = $('#po_freight').val();
            var pf = $('#po_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            var addon = parseFloat(freight) + parseFloat(pf);
            freight = parseFloat(freight);
            pf = parseFloat(pf);

            if (state != 'WEST BENGAL') {
                var freight_tax = freight * 18 / 100;
                var pf_tax = pf * 18 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#po_freight_igst").val(freight_tax);
                $("#po_pf_igst").val(pf_tax);

                tax_final = parseFloat(tax_final) + parseFloat(freight_tax) + parseFloat(pf_tax);

            }
            else {
                var freight_tax = freight * 9 / 100;
                var pf_tax = pf * 9 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#po_freight_cgst").val(freight_tax);
                $("#po_freight_sgst").val(freight_tax);
                $("#po_pf_cgst").val(pf_tax);
                $("#po_pf_sgst").val(pf_tax);

                tax_final = tax_final + parseFloat(freight_tax) + parseFloat(freight_tax) + parseFloat(pf_tax) + parseFloat(pf_tax);
            }
            total_final = parseFloat(gross_final) + parseFloat(addon) + parseFloat(tax_final);

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;

            var roundoff = $('#po_round').val();
            roundoff = parseFloat(roundoff);
            console.log(roundoff);
            if (isNaN(roundoff)) {
                roundoff = 0;
            }

            total_final += roundoff;

            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            $(".po_gross_final").val(gross_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".po_tax_final").val(tax_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".po_total_final").val(total_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#po_freight").val(freight.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#po_pf").val(pf.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
        }
    }, 1000);
}

function po_discount(e) {

    var discount = $("#bulk_discount").val();
    console.log(discount);
    setTimeout(() => {
        var rep = document.getElementById('purchase_order_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='purchase_order[" + i + "][po_dsc]']";
                $(tmp).val(discount);
            }
        }
    }, 1000);

    po_preview(e);
}

function pi_preview(e) {

    var supplier = $("#pi_supplier").val();
    var state = $("#pi_state").val();

    function dcs_round(num) {
        var m = Number((Math.abs(num) * 100).toPrecision(15));
        return Math.round(m) / 100 * Math.sign(num);
    }

    setTimeout(() => {
        var rep = document.getElementById('purchase_invoice_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var qty = 0;
            var price = 0;
            var discount = 0;
            var tax = 0;
            var data = $('#si_series').val();
            console.log("Seriest type", data);

            var total_final = 0;
            var tax_final = 0;
            var gross_final = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='purchase_invoice[" + i + "][pi_qty]']";
                qty = $(tmp).val();

                tmp = "input[name$='purchase_invoice[" + i + "][pi_rate]']";
                price = $(tmp).val();

                tmp = "input[name$='purchase_invoice[" + i + "][pi_dsc]']";
                discount = $(tmp).val();

                tmp = "select[name$='purchase_invoice[" + i + "][pi_tax]']";
                tax = $(tmp).val();
                console.log("Tax", tax);

                //if(data=="SECONDARY")
                console.log("Seriest type", data);
                console.log("tax", tax);


                tmp = "input[name$='purchase_invoice[" + i + "][pi_sn]']";
                $(tmp).val(i + 1);

                price = price.replace(/,/g, '');
                price = parseFloat(price);
                qty = parseFloat(qty);

                var total = 0;

                if (isNaN(tax)) {
                    tax = 0;
                }
                if (isNaN(qty)) {
                    qty = 0;
                }
                if (isNaN(price)) {
                    price = 0;
                }
                total = qty * price;

                var cgst = 0;
                var sgst = 0;
                var igst = 0;
                var tax_pr = '0';
                var gross_pr = total;

                if (discount != '') {
                    total = total * ((100 - parseFloat(discount)) / 100);
                    gross_pr = gross_pr * ((100 - parseFloat(discount)) / 100);
                }
                if (state == 'WEST BENGAL') {
                    if (tax != '') {
                        tax = tax / 2;
                        cgst = total * (parseFloat(tax) / 100);
                        sgst = total * (parseFloat(tax) / 100);

                        cgst = dcs_round(cgst);
                        sgst = dcs_round(sgst);
                        total = total + cgst + sgst;
                    }
                } else {
                    if (tax != '') {
                        igst = total * (parseFloat(tax) / 100);
                        igst = dcs_round(igst);
                        total = total + igst;
                    }
                }

                tax_pr = cgst + sgst + igst;

                total_final += dcs_round(total);
                tax_final += dcs_round(tax_pr);
                gross_final += dcs_round(gross_pr);

                total = dcs_round(total);
                tax_pr = dcs_round(tax_pr);
                gross_pr = dcs_round(gross_pr);

                if (qty != '' && price != '') {
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_gross_pr]']";
                    $(tmp).val(gross_pr);
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_cgst]']";
                    $(tmp).val(cgst.toFixed(2));
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_sgst]']";
                    $(tmp).val(sgst.toFixed(2));
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_igst]']";
                    $(tmp).val(igst.toFixed(2));
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_tax_pr]']";
                    $(tmp).val(tax_pr);
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_total_pr]']";
                    $(tmp).val(total);
                } else {
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_gross_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_cgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_sgst]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_igst]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_tax_pr]']";
                    $(tmp).val('');
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_total_pr]']";
                    $(tmp).val('');
                }

            }

            var freight = $('#pi_freight').val();
            var tcs = $('#pi_tcs').val();

            if (tcs == '')
                tcs = 0;
            if (isNaN(tcs)) {
                tcs = 0;
            }

            var pf = $('#pi_pf').val();

            if (freight == '')
                freight = 0;
            else {
                var n = freight.indexOf("%");
                if (n == '-1')
                    freight = parseFloat(freight.replace(/,/g, ''));
                else {
                    var percent = parseFloat(freight.replace("%", ""));
                    freight = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            if (pf == '')
                pf = 0;
            else {
                var n = pf.indexOf("%");
                if (n == '-1')
                    pf = parseFloat(pf.replace(/,/g, ''));
                else {
                    var percent = parseFloat(pf.replace("%", ""));
                    pf = parseFloat(gross_final) * parseFloat(percent) / 100;
                }
            }

            var addon = parseFloat(freight) + parseFloat(pf);
            freight = parseFloat(freight);
            pf = parseFloat(pf);

            if (state != 'WEST BENGAL') {
                var freight_tax = freight * 18 / 100;
                var pf_tax = pf * 18 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#pi_freight_igst").val(freight_tax);
                $("#pi_pf_igst").val(pf_tax);

                tax_final = parseFloat(tax_final) + parseFloat(freight_tax) + parseFloat(pf_tax);

            }
            else {
                var freight_tax = freight * 9 / 100;
                var pf_tax = pf * 9 / 100;

                freight_tax = dcstoFixed(freight_tax, 2);
                pf_tax = dcstoFixed(pf_tax, 2);

                $("#pi_freight_cgst").val(freight_tax);
                $("#pi_freight_sgst").val(freight_tax);
                $("#pi_pf_cgst").val(pf_tax);
                $("#pi_pf_sgst").val(pf_tax);

                tax_final = tax_final + parseFloat(freight_tax) + parseFloat(freight_tax) + parseFloat(pf_tax) + parseFloat(pf_tax);
            }


            total_final = parseFloat(gross_final) + parseFloat(addon) + parseFloat(tax_final) + parseFloat(tcs);

            var decimal = Math.floor(total_final);
            var fraction = total_final - decimal;

            var roundoff = $('#pi_round').val();
            roundoff = parseFloat(roundoff);

            if (isNaN(roundoff) || roundoff == '') {
                roundoff = 0;
            }

            total_final += roundoff;

            total_final = parseFloat(total_final);
            total_final = dcs_round(total_final);
            tax_final = dcs_round(tax_final);
            gross_final = dcs_round(gross_final);

            $(".pi_gross_final").val(gross_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".pi_tax_final").val(tax_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $(".pi_total_final").val(total_final.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));

            $("#pi_freight").val(freight.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
            $("#pi_pf").val(pf.toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,'));
        }
    }, 1000);
}

function py_preview(e) {

    setTimeout(() => {
        var rep = document.getElementById('payment_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';
            var amount = 0;
            var tot_amount = 0;

            for (var i = 0; i < rowsCount; i++) {
                tmp = "input[name$='payment[" + i + "][py_amount]']";
                amount = $(tmp).val();

                tmp = "input[name$='payment[" + i + "][py_sn]']";
                $(tmp).val(i + 1);

                tot_amount += parseFloat(amount).toFixed(2);

            }

            $("#py_amount").val(tot_amount);
            console.log(tot_amount);
        }
    }, 1000);
}

function a_preview(e) {

    setTimeout(() => {

        var rep = document.getElementById('assemblies_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            for (var i = 0; i < rowsCount; i++) {

                tmp = "input[name$='assemblies[" + i + "][a_sn]']";
                $(tmp).val(i + 1);
            }
        }

    }, 100);
}

function mr_preview(e) {

    setTimeout(() => {

        var rep = document.getElementById('materials_received_list');
        var rowsCount = rep.childNodes.length;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            for (var i = 0; i < rowsCount; i++) {

                tmp = "input[name$='materials_received[" + i + "][mr_sn]']";
                $(tmp).val(i + 1);
            }
        }

    }, 100);
}

function journal_debit_preview(e) {

    setTimeout(() => {

        var rep = document.getElementById('journal_debit_list');
        var rowsCount = rep.childNodes.length;

        rowsCount = rowsCount - 2;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            for (var i = 0; i < rowsCount; i++) {

                tmp = "input[name$='journal_debit[" + i + "][journal_debit_sn]']";
                $(tmp).val(i + 1);

                console.log(tmp);
            }
        }

    }, 100);
}

function journal_credit_preview(e) {

    setTimeout(() => {

        var rep = document.getElementById('journal_credit_list');
        var rowsCount = rep.childNodes.length;

        rowsCount = rowsCount - 2;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            for (var i = 0; i < rowsCount; i++) {

                tmp = "input[name$='journal_credit[" + i + "][journal_credit_sn]']";
                $(tmp).val(i + 1);

                console.log(tmp);
            }
        }

    }, 100);
}

function journal_preview(e) {

    setTimeout(() => {

        var rep = document.getElementById('journal_list');
        var rowsCount = rep.childNodes.length;

        // rowsCount = rowsCount - 2;

        var name = e.currentTarget.name;
        if (name != null) {
            var start = name.indexOf("[");
            var end = name.indexOf("]");
            start += 1;
            name = name.substring(start, end);

            var tmp = '';

            var debit = 0;
            var credit = 0;

            var total_debit = 0;
            var total_credit = 0;


            for (var i = 0; i < rowsCount; i++) {

                tmp = "input[name$='journal[" + i + "][journal_sn]']";
                $(tmp).val(i + 1);


                tmp = "input[name$='journal[" + i + "][journal_debit]']";
                debit = $(tmp).val();

                debit = debit.replace(/,/g, '');
                debit = parseFloat(debit);

                if (isNaN(debit) || debit == '') {
                    debit = 0;
                }

                total_debit = total_debit + debit;


                tmp = "input[name$='journal[" + i + "][journal_credit]']";
                credit = $(tmp).val();

                credit = credit.replace(/,/g, '');
                credit = parseFloat(credit);

                if (isNaN(credit) || credit == '') {
                    credit = 0;
                }

                total_credit = total_credit + credit;


            }

            console.log(total_debit);
            console.log(total_credit);


            $('#journal_debit_final').val(total_debit);
            $('#journal_credit_final').val(total_credit);

        }

    }, 100);
}

//***************************************************** -Settings- *****************************************************

var Settings = function () {

    var handleUpdateMake = function () {
        // console.log("loaded");

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/settings/update_make.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Successfully updated the products.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#form_default_make')[0].reset();
                    // close the modal
                    location.reload();
                }
            });

            return false;
        }

        $('#form_default_make').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#edit_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#form_default_make input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#form_default_make').validate().form()) {
                    ajaxAdd($('#form_default_make')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateSerial = function () {
        // console.log("loaded");

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/settings/update.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Series updated successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#serial_numbering')[0].reset();
                    // close the modal
                    location.reload();
                }
            });

            return false;
        }

        $('#serial_numbering').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#edit_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#serial_numbering input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#serial_numbering').validate().form()) {
                    ajaxAdd($('#serial_numbering')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateExtrasToggle = function () {
        // console.log("loaded");

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/settings/update_extras_toggle.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Successfully updated.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    // //Reset The Form
                    // $('#form_default_make')[0].reset();
                    // close the modal
                    location.reload();
                }
            });

            return false;
        }

        $('#form_extras_toggle').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#edit_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#form_extras_toggle input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#form_extras_toggle').validate().form()) {
                    ajaxAdd($('#form_extras_toggle')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var productXML = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/api_xml/item_masters.php",
                data: form.serialize(),
                dataType: 'json',
                complete: function (response) {

                    console.log("Fired");

                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'XML file generated successfully',
                        html: 'You may download the XML <a href="../assets/custom/api_xml/item_masters.xml" download="product.xml" target="_blank">here</a>.',
                        showConfirmButton: false,
                        showCancelButton: true
                    });


                    //Reset The Form
                    $('#form_product_xml')[0].reset();
                    // close the modal
                }
            });

            return false;
        }

        $('#form_product_xml').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#edit_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#form_product_xml input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#form_product_xml').validate().form()) {
                    ajaxAdd($('#form_product_xml')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var mastersXML = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/api_xml/masters.php",
                data: form.serialize(),
                dataType: 'json',
                complete: function (response) {

                    console.log("Fired");

                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'XML file generated successfully',
                        html: 'You may download the XML <a href="../assets/custom/api_xml/masters.xml" download="masters.xml" target="_blank">here</a>.',
                        showConfirmButton: false,
                        showCancelButton: true
                    });


                    //Reset The Form
                    $('#form_masters_xml')[0].reset();
                    // close the modal
                }
            });

            return false;
        }

        $('#form_masters_xml').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#edit_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#form_masters_xml input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#form_masters_xml').validate().form()) {
                    ajaxAdd($('#form_masters_xml')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleUpdateMake();
            handleUpdateSerial();
            handleUpdateExtrasToggle();
            productXML();
            mastersXML();
        }
    };
}();

//***************************************************** -Products- *****************************************************

var Product = function () {

    var product = $('#add_product');

    var handleAddProduct = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/product/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        addProductToast(response.messages);
                    else
                        addProductToastError(response.messages);

                    //Reset The Form
                    $('#add_product')[0].reset();

                    // close the modal
                    $("#kt_modal_product").modal('hide');
                    manageProductTable.reload();
                    $('#product_name').val(null).trigger('change');
                    $('#product_group_name').val(null).trigger('change');
                    $('#product_vendor_name').val(null).trigger('change');
                    $('#product_category').val(null).trigger('change');
                    $('#product_sub_category').val(null).trigger('change');
                    $('#product_unit').val(null).trigger('change');
                    $('#product_tax').val(null).trigger('change');
                    $("#add_product_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#add_product').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                product_name: {
                    required: true,
                    remote: {
                        url: "../assets/custom/api_check/check_product.php",
                        type: "post"
                    }
                },
                product_opening_stock: {
                    required: true
                },
                product_unit: {
                    required: true
                },
                product_rate: {
                    required: true,
                    number: true
                }
            },
            messages: {
                product_name: {
                    required: 'This field is required!',
                    remote: "This product already exists"
                },
                product_opening_stock: {
                    required: 'This field is required!'
                },
                product_unit: {
                    required: 'This field is required!'
                },
                product_rate: {
                    required: 'This field is required!',
                    number: 'Only Numeric value allowed'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#add_product_submit").attr("disabled", true);
            }
        });

        $('#add_product input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_product').validate().form()) {
                    ajaxAdd($('#add_product')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateProduct = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/product/update.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        editProductToast(response.messages);
                    else
                        editProductToastError(response.messages);

                    //Reset The Form
                    $('#edit_product')[0].reset();
                    // close the modal
                    $("#kt_modal_e_product").modal('hide');
                    manageProductTable.reload();
                }
            });

            return false;
        }

        $('#edit_product').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                edit_product_name: {
                    required: true
                },
                edit_product_opening_stock: {
                    required: true
                },
                edit_product_unit: {
                    required: true
                },
                edit_product_rate: {
                    required: true
                }
            },
            messages: {
                edit_product_name: {
                    required: 'This field is required!'
                },
                edit_product_opening_stock: {
                    required: 'This field is required!'
                },
                edit_product_unit: {
                    required: 'This field is required!'
                },
                edit_product_rate: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#edit_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#edit_product input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#edit_product').validate().form()) {
                    ajaxAdd($('#edit_product')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateProductOpeningStock = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/product/update_opening_stock.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Opening Stock updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    location.reload();
                }
            });

            return false;
        }

        $('#update_product_opening_stock').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                update_opening_stock: {
                    required: true
                }
            },
            messages: {
                update_opening_stock: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#update_product_opening_stock input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#update_product_opening_stock').validate().form()) {
                    ajaxAdd($('#update_product_opening_stock')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddProduct();
            handleUpdateProduct();
            handleUpdateProductOpeningStock();
        }
    };
}();

function editProduct(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/product/getSelectedProduct.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_product_id").val(response.id);
                $("#edit_product_name").empty().append($("<option/>").val(response.name).text(response.name)).val(response.name).trigger("change");
                $("#edit_product_description").val(response.description);
                $("#edit_product_alias").val(response.aliases);
                $("#edit_product_group_name").empty().append($("<option/>").val(response.group).text(response.group)).val(response.group).trigger("change");
                $("#edit_product_vendor_name").empty().append($("<option/>").val(response.vendor).text(response.vendor)).val(response.vendor).trigger("change");
                $("#edit_product_category").empty().append($("<option/>").val(response.category).text(response.category)).val(response.category).trigger("change");
                $("#edit_product_sub_category").empty().append($("<option/>").val(response.sub_category).text(response.sub_category)).val(response.sub_category).trigger("change");
                $("#edit_product_opening_stock").val(response.opening_stock);
                $("#edit_product_unit").val(response.unit).trigger("change");
                $("#edit_product_rate").val(response.rate);
                $("#edit_product_cost").val(response.cost);
                $("#edit_product_tax").val(response.tax).trigger("change");
                $("#edit_product_hsn").val(response.hsn);
                $("#edit_product_moq").val(response.moq);
                $("#edit_product_pdf").val(response.pdf);
                $("#edit_product_images").val(response.images);

                if (response.updated_price == '1') {
                    $('#edit_product_update').prop('checked', true);
                }
                else {
                    $('#edit_product_update').prop('checked', false);
                }



                var opening_stock = JSON.parse(response.new_opening_stock);
                $("#edit_product_opening_stock").val(opening_stock['stock'][1]);
                console.log(opening_stock['stock'][1]);


            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeProduct(id = true) {
    if (id) {
        //click remove button
        $('#delete_product_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/product/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        deleteProductToast(response.messages);
                    } else {
                        deleteProductToastError(response.messages);
                    }

                    // close the modal
                    $("#kt_modal_d_product").modal('hide');
                    manageProductTable.reload();
                }
            });
        });
        // click remove button
    } else {
        alert('Error : Please refresh the page');
    }
}

function updated_stock_toggle(id) {
    if (id) {

        $.ajax({
            url: '../assets/custom/product/updated_stock_toggle.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Request executed successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    swal.fire({
                        position: 'top-right',
                        type: 'error',
                        title: 'There were some errors in your submission.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

                manageDashboardTable.reload();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function archive_product(id) {
    if (id) {

        $.ajax({
            url: '../assets/custom/product/archive.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Request executed successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    swal.fire({
                        position: 'top-right',
                        type: 'error',
                        title: 'There were some errors in your submission.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                if (param_page == '')
                    manageDashboardTable.reload();
                else
                    manageProductTable.reload();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function updateProductOpeningStock(year, stock, product_id) {

    $("#update_opening_stock_year").val(year);
    $("#update_opening_stock").val(stock);
    $("#product_id").val(product_id);
}
//***************************************************** -Assemblies- *****************************************************

var Assemblies = function () {

    var handleAddAssemblies = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/assemblies/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your Assembly has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_assembly')[0].reset();
                    // close the modal
                    manageAssembliesTable.reload();
                    $('#composite_product').val(null).trigger('change');

                    $('[data-repeater-list="assemblies"]').empty();
                    $('[data-repeater-create="assemblies"]').click();
                    var tmp = "input[name$='assemblies[0][a_sn]']";
                    $(tmp).val(1);
                }
            });

            return false;
        }

        $('#add_assembly').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                composite_product: {
                    required: true
                },
            },
            messages: {
                composite_product: {
                    required: 'This field is required'
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_assembly input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_assembly').validate().form()) {
                    ajaxAdd($('#add_assembly')); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    }
    // var handleAddAssemble = function() {

    //     var ajaxAdd = function(form) {
    //         form = $(form);
    //         $.ajax({
    //             type: "POST",
    //             url: "../assets/custom/assemblies/assemble.php",
    //             data: form.serialize(),
    //             dataType: 'json',
    //             success: function(response) {
    //                 if (response.success == true) {
    //                     swal.fire({
    //                         position: 'top-right',
    //                         type: 'success',
    //                         title: 'Assemble Operation has been saved',
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     });
    //                 } else {
    //                     swal.fire({
    //                         position: 'top-right',
    //                         type: 'error',
    //                         title: 'There were some errors in your submission.',
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     });
    //                 }
    //                 $("#kt_modal_a_assemblies").modal('hide');
    //                 //Reset The Form
    //                 $('#assemblies_assemble')[0].reset();
    //                 // close the modal
    //                 manageAssembliesOperationTable.reload();

    //             }
    //         });

    //         return false;
    //     }

    //     $('#assemblies_assemble').validate({
    //         errorElement: 'span', //default input error message container
    //         errorClass: 'help-block', // default input error message class
    //         focusInvalid: false, // do not focus the last invalid input
    //         rules: {
    //             assemble_qty: {
    //                 required: true
    //             },
    //         },
    //         messages: {
    //             assemble_qty: {
    //                 required: 'This field is required'
    //             },
    //         },

    //         invalidHandler: function(event, validator) {
    //             var alert = $('#add_product_msg');
    //             alert.removeClass('kt--hide').show();
    //             KTUtil.scrollTop();
    //         },

    //         errorPlacement: function(error, element) {
    //             var group = element.closest('.kt-input-icon');
    //             if (group.length) {
    //                 group.after(error.addClass('invalid-feedback'));
    //             } else {
    //                 element.after(error.addClass('invalid-feedback'));
    //             }
    //         },

    //         submitHandler: function(form) {
    //             ajaxAdd(form);
    //         }
    //     });

    //     $('#assemblies_assemble input').keypress(function(e) {
    //         $('.alert').hide();
    //         $('.alert span').html("");
    //         if (e.which == 13) {
    //             if ($('#assemblies_assemble').validate().form()) {
    //                 ajaxAdd($('#assemblies_assemble')); //form validation success, call ajax form submit
    //             }
    //             return false;
    //         }
    //     });

    // }
    // var handleAddDisassemble = function() {

    //     var ajaxAdd = function(form) {
    //         form = $(form);
    //         $.ajax({
    //             type: "POST",
    //             url: "../assets/custom/assemblies/disassemble.php",
    //             data: form.serialize(),
    //             dataType: 'json',
    //             success: function(response) {
    //                 if (response.success == true) {
    //                     swal.fire({
    //                         position: 'top-right',
    //                         type: 'success',
    //                         title: 'Disassemble Operation has been saved',
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     });
    //                 } else {
    //                     swal.fire({
    //                         position: 'top-right',
    //                         type: 'error',
    //                         title: 'There were some errors in your submission.',
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     });
    //                 }
    //                 $("#kt_modal_dis_assemblies").modal('hide');
    //                 //Reset The Form
    //                 $('#assemblies_disassemble')[0].reset();
    //                 // close the modal
    //                 manageAssembliesOperationTable.reload();

    //             }
    //         });

    //         return false;
    //     }

    //     $('#assemblies_disassemble').validate({
    //         errorElement: 'span', //default input error message container
    //         errorClass: 'help-block', // default input error message class
    //         focusInvalid: false, // do not focus the last invalid input
    //         rules: {
    //             assemble_qty: {
    //                 required: true
    //             },
    //         },
    //         messages: {
    //             disassemble_qty: {
    //                 required: 'This field is required'
    //             },
    //         },

    //         invalidHandler: function(event, validator) {
    //             var alert = $('#add_product_msg');
    //             alert.removeClass('kt--hide').show();
    //             KTUtil.scrollTop();
    //         },

    //         errorPlacement: function(error, element) {
    //             var group = element.closest('.kt-input-icon');
    //             if (group.length) {
    //                 group.after(error.addClass('invalid-feedback'));
    //             } else {
    //                 element.after(error.addClass('invalid-feedback'));
    //             }
    //         },

    //         submitHandler: function(form) {
    //             ajaxAdd(form);
    //         }
    //     });

    //     $('#assemblies_disassemble input').keypress(function(e) {
    //         $('.alert').hide();
    //         $('.alert span').html("");
    //         if (e.which == 13) {
    //             if ($('#assemblies_disassemble').validate().form()) {
    //                 ajaxAdd($('#assemblies_disassemble')); //form validation success, call ajax form submit
    //             }
    //             return false;
    //         }
    //     });

    // }

    var handleAddAssembleOperation = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/assemblies/create_operation.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Operation has been saved successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    //Reset The Form
                    $('#add_assembly_operation')[0].reset();
                    // close the modal
                    manageAssembliesOperationTable.reload();

                }
            });

            return false;
        }

        $('#add_assembly_operation').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
            },
            messages: {
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_assembly_operation input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_assembly_operation').validate().form()) {
                    ajaxAdd($('#add_assembly_operation')); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    }

    var handleAddTagInvoice = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/assemblies/tag_invoice.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Invoice tagged successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    //Reset The Form
                    $('#dcs_tag_invoice')[0].reset();
                    $("#kt_modal_tag_invoice").modal('hide');

                    // close the modal
                    manageAssembliesOperationTable.reload();

                }
            });

            return false;
        }

        $('#dcs_tag_invoice').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
            },
            messages: {
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#dcs_tag_invoice input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#dcs_tag_invoice').validate().form()) {
                    ajaxAdd($('#dcs_tag_invoice')); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    }


    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddAssemblies();
            // handleAddAssemble();
            // handleAddDisassemble();
            handleAddAssembleOperation();
            handleAddTagInvoice();
        }
    };
}();

function removeAssemblies(id = true) {
    if (id) {
        $('#delete_assemblies_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/assemblies/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Assembly has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_assemblies").modal('hide');
                    manageAssembliesTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function assemble(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/assemblies/getSelectedAssembly.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#a_id").val(response.id);
                $("#composite_assemble").val(response.composite);
            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function disassemble(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/assemblies/getSelectedAssembly.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#d_id").val(response.id);
                $("#composite_disassemble").val(response.composite);
            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function tagInvoiceAssemblyOperation(id) {

    if (id) {
        $.ajax({
            url: '../assets/custom/assemblies/getSelectedAssemblyOperation.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#assemby_tag_id").val(response.id);
                $("#assemby_invoice").val(response.invoice);
            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }

}

function removeAssemblyOperation(id = true) {
    if (id) {
        $('#delete_assemblies_operation_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/assemblies/delete_operation.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Assembly Operation has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_assemblies_operation").modal('hide');
                    manageAssembliesOperationTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}
//***************************************************** -Clients- *****************************************************

var client_id;

var Client = function () {

    var client = $('#dcs_add_client');

    var handleAddClient = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/clients/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        addClientToast();
                    else
                        addClientToastError(response.messages);

                    //Reset The Form
                    $('#dcs_add_client')[0].reset();
                    // close the modal
                    $("#kt_modal_client").modal('hide');
                    manageClientTable.reload();
                }
            });

            return false;
        }

        $('#dcs_add_client').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                client_name: {
                    required: true,
                    remote: {
                        url: "../assets/custom/api_check/check_client.php",
                        type: "post"
                    }
                },
                client_add_3: {
                    required: true
                },
                client_state: {
                    required: true
                }
            },
            messages: {
                client_name: {
                    required: 'This field is required!',
                    remote: 'This name already exists! (Kindly use another name)'
                },
                client_add_3: {
                    required: 'This field is required!'
                },
                client_state: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#dcs_add_client input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#dcs_add_client').validate().form()) {
                    ajaxAdd($('#dcs_add_client')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateClient = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/clients/update.php?id=" + client_id,
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        editClientToast();
                    else
                        editClientToastError(response.messages);

                    //Reset The Form
                    $('#dcs_edit_client')[0].reset();
                    // close the modal
                    $("#kt_modal_edit_client").modal('hide');
                    manageClientTable.reload();
                }
            });

            return false;
        }

        $('#dcs_edit_client').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                edit_client_name: {
                    required: true,
                    remote: {
                        url: "../assets/custom/api_check/check_client.php",
                        type: "post"
                    }
                },
                edit_client_add_3: {
                    required: true
                },
                edit_client_state: {
                    required: true
                }
            },
            messages: {
                edit_client_name: {
                    required: 'This field is required!',
                    remote: 'This name already exists! (Kindly use another name)'
                },
                edit_client_add_3: {
                    required: 'This field is required!'
                },
                edit_client_state: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#dcs_edit_client input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#dcs_edit_client').validate().form()) {
                    ajaxAdd($('#dcs_edit_client')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateClientOpeningBalance = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/clients/update_opening_balance.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Opening Balance updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    location.reload();
                }
            });

            return false;
        }

        $('#update_client_opening_balance').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                update_opening_balance_amount: {
                    required: true
                }
            },
            messages: {
                update_opening_balance_amount: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#update_client_opening_balance input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#update_client_opening_balance').validate().form()) {
                    ajaxAdd($('#update_client_opening_balance')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddClient();
            handleUpdateClient();
            handleUpdateClientOpeningBalance();
        }
    };
}();

function editClient(id) {
    client_id = id;
    if (id) {
        $.ajax({
            url: '../assets/custom/clients/getSelectedClient.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_client_name").val(response.name);
                $("#edit_client_print_name").val(response.print_name);
                $("#edit_client_address").val(response.address);
                var address = JSON.parse(response.address);
                $("#edit_client_add_1").val(address['address_1']);
                $("#edit_client_add_2").val(address['address_2']);
                $("#edit_client_city").val(address['city']);
                $("#edit_client_pincode").val(address['pincode']);

                $("#edit_client_state").empty() //empty select
                    .append($("<option/>") //add option tag in select
                        .val(response.state) //set value for option to post it
                        .text(response.state)) //set a text for show in select
                    .val(response.state) //select option of select2
                    .trigger("change");

                $("#edit_client_country").val(response.country);
                $("#edit_vendor_code").val(response.vendor_code);
                $("#edit_vendor_discount").val(response.vendor_discount);


                $("#edit_client_category").empty() //empty select
                    .append($("<option/>") //add option tag in select
                        .val(response.type) //set value for option to post it
                        .text(response.type)) //set a text for show in select
                    .val(response.type) //select option of select2
                    .trigger("change");

                var bank = JSON.parse(response.bank_details);
                $("#edit_bank_client").val(bank['name']);
                $("#edit_bank_name").val(bank['bank_name']);
                $("#edit_bank_account").val(bank['account']);
                $("#edit_bank_ifsc").val(bank['ifsc']);
                var temp = '';
                var obj = JSON.parse(response.contacts);
                var length = obj.name.length;

                for (var i = 0; i < length; i++) {
                    $('#edit_client_btn').click();
                }

                for (var i = 0; i < length; i++) {
                    temp = "input[name$='edit_client[" + i + "][edit_client_person]']";
                    $(temp).val(obj.name[i]);
                    temp = "input[name$='edit_client[" + i + "][edit_client_designation]']";
                    $(temp).val(obj.designation[i]);
                    temp = "input[name$='edit_client[" + i + "][edit_client_mobile]']";
                    $(temp).val(obj.mobile[i]);
                    temp = "input[name$='edit_client[" + i + "][edit_client_email]']";
                    $(temp).val(obj.email[i]);
                }

                $("#edit_client_gstin").val(response.gstin);
                $("#edit_client_gstin_type").val(response.gstin_type).trigger("change");

                $("#edit_client_credit").val(response.credit_period);
                $("#edit_client_opening").val(response.opening_balance);

            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeClient(id = true) {
    if (id) {
        //click remove button
        $('#dcs_delete_client_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/clients/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        deleteClientToast(response.messages);
                    } else {
                        deleteClientToastError(response.messages);
                    }

                    // close the modal
                    $("#kt_modal_d_client").modal('hide');
                    manageClientTable.reload();
                }
            });
        });
        // click remove button
    } else {
        alert('Error : Please refresh the page');
    }
}

function updateCLientOpeningBalance(year, balance, client_id) {

    $("#update_opening_balance_year").val(year);
    $("#update_opening_balance_amount").val(balance);
    $("#client_id").val(client_id);
}

//***************************************************** -Suppliers- *****************************************************

var supplier_id;

var Supplier = function () {

    var supplier = $('#dcs_add_supplier');

    var handleAddSupplier = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/suppliers/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        addSupplierToast(response.messages);
                    else
                        addSupplierToastError(response.messages);

                    //Reset The Form
                    $('#dcs_add_supplier')[0].reset();
                    // close the modal
                    $("#kt_modal_supplier").modal('hide');
                    manageSupplierTable.reload();
                }
            });

            return false;
        }

        $('#dcs_add_supplier').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                supplier_name: {
                    required: true
                },
                supplier_add_3: {
                    required: true
                },
                supplier_state: {
                    required: true
                }
            },
            messages: {
                supplier_name: {
                    required: 'This field is required!'
                },
                supplier_add_3: {
                    required: 'This field is required!'
                },
                supplier_state: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#dcs_add_supplier input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#dcs_add_supplier').validate().form()) {
                    ajaxAdd($('#dcs_add_supplier')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateSupplier = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/suppliers/update.php?id=" + supplier_id,
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        editSupplierToast(response.messages);
                    else
                        editSupplierToastError(response.messages);

                    //Reset The Form
                    $('#dcs_edit_supplier')[0].reset();
                    // close the modal
                    $("#kt_modal_edit_supplier").modal('hide');
                    manageSupplierTable.reload();
                }
            });

            return false;
        }

        $('#dcs_edit_supplier').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                edit_supplier_name: {
                    required: true
                },
                edit_supplier_add_3: {
                    required: true
                },
                edit_supplier_state: {
                    required: true
                }
            },
            messages: {
                edit_supplier_name: {
                    required: 'This field is required!'
                },
                edit_supplier_add_3: {
                    required: 'This field is required!'
                },
                edit_supplier_state: {
                    required: 'This field is required!'
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#dcs_edit_supplier input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#dcs_edit_supplier').validate().form()) {
                    ajaxAdd($('#dcs_edit_supplier')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateSupplierOpeningBalance = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/suppliers/update_opening_balance.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Opening Balance updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    location.reload();
                }
            });

            return false;
        }

        $('#update_supplier_opening_balance').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                update_opening_balance_amount: {
                    required: true
                }
            },
            messages: {
                update_opening_balance_amount: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#update_supplier_opening_balance input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#update_supplier_opening_balance').validate().form()) {
                    ajaxAdd($('#update_supplier_opening_balance')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddSupplier();
            handleUpdateSupplier();
            handleUpdateSupplierOpeningBalance();
        }
    };
}();

function editSupplier(id) {
    supplier_id = id;
    if (id) {
        $.ajax({
            url: '../assets/custom/suppliers/getSelectedSupplier.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_supplier_name").val(response.name);
                $("#edit_supplier_print_name").val(response.print_name);
                $("#edit_supplier_address").val(response.address);
                var address = JSON.parse(response.address);
                $("#edit_supplier_add_1").val(address['address_1']);
                $("#edit_supplier_add_2").val(address['address_2']);
                $("#edit_supplier_city").val(address['city']);
                $("#edit_supplier_pincode").val(address['pincode']);
                $("#edit_supplier_country").val(response['country']);


                $("#edit_supplier_state").empty() //empty select
                    .append($("<option/>") //add option tag in select
                        .val(response.state) //set value for option to post it
                        .text(response.state)) //set a text for show in select
                    .val(response.state) //select option of select2
                    .trigger("change");

                $("#edit_supplier_category").empty() //empty select
                    .append($("<option/>") //add option tag in select
                        .val(response.type) //set value for option to post it
                        .text(response.type)) //set a text for show in select
                    .val(response.type) //select option of select2
                    .trigger("change");

                var bank = JSON.parse(response.bank_details);
                $("#edit_bank_supplier").val(bank['name']);
                $("#edit_bank_name").val(bank['bank_name']);
                $("#edit_bank_account").val(bank['account']);
                $("#edit_bank_ifsc").val(bank['ifsc']);
                var temp = '';
                var obj = JSON.parse(response.contacts);
                var length = obj.name.length;

                for (var i = 0; i < length; i++) {
                    $('#edit_supplier_btn').click();
                }

                for (var i = 0; i < length; i++) {
                    temp = "input[name$='edit_supplier[" + i + "][edit_supplier_person]']";
                    $(temp).val(obj.name[i]);
                    temp = "input[name$='edit_supplier[" + i + "][edit_supplier_designation]']";
                    $(temp).val(obj.designation[i]);
                    temp = "input[name$='edit_supplier[" + i + "][edit_supplier_mobile]']";
                    $(temp).val(obj.mobile[i]);
                    temp = "input[name$='edit_supplier[" + i + "][edit_supplier_email]']";
                    $(temp).val(obj.email[i]);
                }

                $("#edit_supplier_gstin").val(response.gstin);
                $("#edit_supplier_gstin_type").val(response.gstin_type).trigger("change");

                $("#edit_supplier_credit").val(response.credit_period);
                $("#edit_supplier_opening").val(response.opening_balance);
            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeSupplier(id = true) {
    if (id) {
        //click remove button
        $('#dcs_delete_supplier_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/suppliers/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        deleteSupplierToast(response.messages);
                    } else {
                        deleteSupplierToastError(response.messages);
                    }

                    // close the modal
                    $("#kt_modal_d_supplier").modal('hide');
                    manageSupplierTable.reload();
                }
            });
        });
        // click remove button
    } else {
        alert('Error : Please refresh the page');
    }
}

function updateSupplierOpeningBalance(year, balance, supplier_id) {

    $("#update_opening_balance_year").val(year);
    $("#update_opening_balance_amount").val(balance);
    $("#supplier_id").val(supplier_id);
}

//***************************************************** -User- *****************************************************

var User = function () {

    var handleAddUser = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/users/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        addUserToast(response.messages);
                    else
                        addUserToastError(response.messages);

                    //Reset The Form
                    $('#add_user')[0].reset();
                    // close the modal
                    $("#kt_modal_user").modal('hide');
                    manageUsersTable.reload();
                    $('#userlevel').val(null).trigger('change');
                    $('#allowed_fy').val(null).trigger('change');
                }
            });

            return false;
        }

        $('#add_user').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                username: {
                    required: 'This field is required!'
                },
                password: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_user input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_user').validate().form()) {
                    ajaxAdd($('#add_user')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateUser = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/users/update.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        editUserToast(response.messages);
                    else
                        editUserToastError(response.messages);

                    //Reset The Form
                    $('#am_edit_user')[0].reset();
                    // close the modal
                    $("#kt_modal_e_user").modal('hide');
                    manageUsersTable.reload();
                    $('#edit_userlevel').val(null).trigger('change');
                    $('#edit_allowed_fy').val(null).trigger('change');
                }
            });

            return false;
        }

        $('#am_edit_user').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                edit_userlevel: {
                    required: true
                },
                edit_name: {
                    required: true
                }
            },
            messages: {
                edit_userlevel: {
                    required: 'This field is required!'
                },
                edit_name: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#am_edit_user input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            console.log("keys pressed");
            if (e.which == 13) {
                if ($('#am_edit_user').validate().form()) {
                    console.log("Call Ajax");

                    ajaxAdd($('#am_edit_user')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdateUser_User = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/users/update_user.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        editUserToast(response.messages);
                    else
                        editUserToastError(response.messages);

                    location.reload();
                }
            });

            return false;
        }

        $('#profile_info').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                full_name: {
                    required: true
                }
            },
            messages: {
                full_name: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#profile_info input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            console.log("keys pressed");
            if (e.which == 13) {
                if ($('#profile_info').validate().form()) {
                    ajaxAdd($('#profile_info')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleUpdatePassword = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/users/change_password.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        editPasswordToast(response.messages);
                    else
                        editPasswordToastError(response.messages);

                    //Reset The Form
                    $('#change_password')[0].reset();
                }
            });

            return false;
        }

        $('#change_password').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                new_pass: "required",
                recheck_pass: {
                    equalTo: "#new_pass"
                }
            },
            messages: {
                new_pass: " Enter Password",
                recheck_pass: " Enter Confirm Password Same as Password"
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#change_password input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#change_password').validate().form()) {
                    ajaxAdd($('#change_password')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddUser();
            handleUpdateUser();
            handleUpdateUser_User();
            handleUpdatePassword();
        }
    };
}();

function editUser(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/users/getSelectedUser.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_id").val(response.id);
                $("#edit_username").val(response.username);
                $("#edit_name").val(response.name);
                $("#edit_mobile").val(response.mobile);
                $("#edit_email").val(response.email);

                $("#edit_userlevel").val(response.userlevel) //select option of select2
                    .trigger("change"); //apply to select2
                $("#edit_allowed_fy").empty().append($("<option/>").val(response.allowed_fy).text(response.allowed_fy)).val(response.allowed_fy).trigger("change");

            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeUser(id = true) {
    if (id) {
        //click remove button
        $('#dcs_delete_user_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/users/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        deleteUserToast(response.messages);
                    } else {
                        deleteUserToastError(response.messages);
                    }

                    // close the modal
                    $("#kt_modal_d_user").modal('hide');
                    manageUsersTable.reload();
                }
            });
        });
        // click remove button
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Purchase Order- *****************************************************

var Purchase_Order = function () {

    var handleAddPurchaseOrder = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/purchase_order/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Purchase order has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            type: "POST",
                            url: "../assets/custom/pdf_master/_purchase_order.php",
                            data: { id: response.po },
                            dataType: 'json',
                            success: function (response) {
                                console.log("saved");
                            }
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_purchase_order')[0].reset();
                    // close the modal
                    managePurchaseOrderTable.reload();
                    managePurchaseBagTable.reload();
                    set_purchase_order_no();
                    $('#po_supplier').val(null).trigger('change');
                    $('[data-repeater-list="purchase_order"]').empty();
                    $('[data-repeater-create="purchase_order"]').click();
                    var tmp = "input[name$='purchase_order[0][po_sn]']";
                    $(tmp).val(1);
                    $("#purchase_order_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#add_purchase_order').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                po_supplier: {
                    required: true
                },
                purchase: {
                    required: true
                },
                purchase_date: {
                    required: true
                },
            },
            messages: {
                po_supplier: {
                    required: 'This field is required!'
                },
                purchase: {
                    required: 'This field is required!'
                },
                purchase_date: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#purchase_order_submit").attr("disabled", true);
            }
        });

        $('#add_purchase_order input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_purchase_order').validate().form()) {
                    ajaxAdd($('#add_purchase_order')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    var handleSendPOEmail = function () {

        var ajaxAdd = function (form) {
            // $('.summernote').each( function() {
            //     $(this).val($(this).code());
            // });
            form = $(form);

            $.ajax({
                type: "POST",
                url: "../assets/custom/purchase_order_email.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Email Sent!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in sending the email.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#send_po_email')[0].reset();
                    $("#kt_modal_po_email").modal('hide');
                }
            });

            return false;
        }

        $('#send_po_email').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#send_po_email input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_po_email').validate().form()) {
                    ajaxAdd($('#send_po_email')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddPurchaseOrder();
            handleSendPOEmail();
        }
    };
}();

function set_purchase_order_no() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'purchase_order' },
        dataType: 'json',
        success: function (response) {
            $("#purchase").val(response.value);
        }
    });
}

function editPurchaseOrder(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/purchase_order/getSelectedPO.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_po_id").val(response.id);
                $("#po_supplier").empty().append($("<option/>").val(response.supplier_name).text(response.supplier_name)).val(response.supplier_name).trigger("change");
                $("#purchase").val(response.po_no);

                var po_date = new Date(response.po_date);
                var formatted_date = appendLeadingZeroes(po_date.getDate()) + "-" + appendLeadingZeroes(po_date.getMonth() + 1) + "-" + po_date.getFullYear();
                $("#purchase_date").val(formatted_date);

                var shipping = JSON.parse(response.shipping);
                $("#po_shipping_name").val(shipping.name);
                $("#po_shipping_add_1").val(shipping.address_1);
                $("#po_shipping_add_2").val(shipping.address_2);
                $("#po_shipping_city").val(shipping.city);
                $("#po_shipping_pincode").val(shipping.pincode);
                $("#po_shipping_country").val(shipping.country);

                $("#po_shipping_state").empty().append($("<option/>").val(response.state).text(response.state)).val(response.state).trigger("change");

                var top = JSON.parse(response.top);
                $("#po_mode").val(top.mode);
                $("#po_supplier_ref").val(top.supplier_ref);
                $("#po_other_ref").val(top.other_ref);
                $("#po_despatch").val(top.despatch);
                $("#po_destination").val(top.destination);
                $("#po_terms").val(top.terms);

                var addons = JSON.parse(response.addons);
                $("#po_freight").val(addons.freight.value);
                $("#po_pf").val(addons.pf.value);

                var items = JSON.parse(response.items);
                var len = items.product.length;

                $('[data-repeater-list="purchase_order"]').empty();
                $('[data-repeater-create="purchase_order"]').click();

                for (var i = 1; i < len; i++) {
                    $('#po_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='purchase_order[" + i + "][po_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "select[name$='purchase_order[" + i + "][po_product_name]']";
                    $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                    tmp = "input[name$='purchase_order[" + i + "][po_product_description]']";
                    $(tmp).val(items.desc[i]);
                    tmp = "textarea[name$='purchase_order[" + i + "][po_product_add_description]']";
                    var temp = items.long_desc[i];
                    temp = temp.replace(/\|/g, "\r\n");
                    $(tmp).val(temp);

                    var temp_textarea = $(tmp);
                    autosize(temp_textarea);

                    tmp = "input[name$='purchase_order[" + i + "][po_qty]']";
                    $(tmp).val(items.quantity[i]);
                    tmp = "select[name$='purchase_order[" + i + "][po_unit]']";
                    $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                    tmp = "input[name$='purchase_order[" + i + "][po_rate]']";
                    $(tmp).val(items.price[i]);
                    // console.log(items.price[i]);
                    tmp = "input[name$='purchase_order[" + i + "][po_dsc]']";
                    $(tmp).val(items.discount[i]);
                    tmp = "input[name$='purchase_order[" + i + "][po_hsn]']";
                    $(tmp).val(items.hsn[i]);
                    tmp = "select[name$='purchase_order[" + i + "][po_tax]']";
                    $(tmp).empty().append($("<option/>").val(items.tax[i]).text(items.tax[i])).val(items.tax[i]).trigger("change");
                    tmp = "select[name$='purchase_order[" + i + "][po_display_make]']";
                    $(tmp).val(items.group[i]).trigger("change");
                }
                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removePurchaseOrder(id = true) {
    if (id) {
        $('#delete_purchase_order_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/purchase_order/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Purchase order deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#delete_purchase_order").modal('hide');
                    managePurchaseOrderTable.reload();
                    set_purchase_order_no();

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function cancelPurchaseOrder(id = true) {
    if (id) {
        $('#cancel_purchase_order_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/purchase_order/cancel.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Purchase Order cancelled successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#cancel_purchase_order").modal('hide');
                    managePurchaseOrderTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function sendPOEmail(id) {
    if (id) {
        $("#po_em_id").val(id);
        $.ajax({
            url: '../assets/custom/purchase_order/email_message.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#po_em_email").val(response.email);
                    $("#po_em_subject").val(response.subject);
                    // $("#q_em_message").innerHTML(response.em_message);
                    $('#po_em_message').summernote('code', response.em_message);
                    $('#po_em_email_bcc').val('sentmail@easthyde.com');
                }

            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Purchase Invoice- *****************************************************

var Purchase_Invoice = function () {

    var handleAddPurchaseInvoice = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/purchase_invoice/create.php",
                // data: form.serialize(),
                data: JSON.stringify(form.serializeJSON()),
                // var data = JSON.stringify(form.serializeJSON());
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your purchase invoice has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            type: "POST",
                            url: "../assets/custom/pdf_master/_purchase_invoice.php",
                            data: { id: response.pi },
                            dataType: 'json',
                            success: function (response) {
                                console.log("saved");
                            }
                        });
                        $.ajax({
                            url: "../assets/custom/api_set/set_purchase_purchaseorder.php",
                            type: "POST",
                            data: { pi: response.pi },
                            dataType: 'json',
                            success: function (response) { }
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_purchase_invoice')[0].reset();
                    // close the modal
                    if (param_page == 'purchase') {
                        managePurchaseInvoiceTable.reload();
                        set_purchase();
                    }

                    if (param_page == 'secondary_purchase') {
                        manageSecondaryPurchaseInvoiceTable.reload();
                        set_secondary_purchase();
                    }

                    $('#pi_supplier').val(null).trigger('change');
                    $('#pi_purchase_order').val(null).trigger('change');
                    $('#pi_product_name').val(null).trigger('change');
                    $('#pi_tax').val(null).trigger('change');
                    $('[data-repeater-list="purchase_invoice"]').empty();
                    $('[data-repeater-create="purchase_invoice"]').click();
                    var tmp = "input[name$='purchase_invoice[0][pi_sn]']";
                    $(tmp).val(1);
                    $("#purchase_invoice_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#add_purchase_invoice').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                pi_client: {
                    required: true
                },
                purchase: {
                    required: true
                },
                purchase_invoice_date: {
                    required: true
                },
            },
            messages: {
                pi_client: {
                    required: 'This field is required!'
                },
                purchase: {
                    required: 'This field is required!'
                },
                purchase_invoice_date: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#purchase_invoice_submit").attr("disabled", true);
            }
        });

        $('#add_purchase_invoice input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_purchase_invoice').validate().form()) {
                    ajaxAdd($('#add_purchase_invoice')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }



    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddPurchaseInvoice();
        }
    };
}();

function editPurchaseInvoice(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/purchase_invoice/getSelectedPI.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_pi_id").val(response.id);
                $("#pi_state").val(response.state);
                $("#pi_supplier").empty().append($("<option/>").val(response.supplier_name).text(response.supplier_name)).val(response.supplier_name).trigger("change");
                $("#purchase_invoice_no").val(response.pi_no);
                $("#purchase_invoice_pno").val(response.spi_no);
                $("#mobile").val(response.mobile);

                var pi_date = new Date(response.pi_date);
                var formatted_date = appendLeadingZeroes(pi_date.getDate()) + "-" + appendLeadingZeroes(pi_date.getMonth() + 1) + "-" + pi_date.getFullYear();
                console.log(formatted_date);
                $("#purchase_invoice_date").val(formatted_date);

                var e_nos = JSON.parse(response.po_no);

                $('#pi_purchase_order').empty();
                for (var i = 0, l = e_nos.length; i < l; i++) {

                    var po_no = e_nos[i];
                    $('#pi_purchase_order').append($("<option/>") //add option tag in select
                        .val(po_no) //set value for option to post it
                        .text(po_no));
                }

                var Values = new Array();
                for (var i = 0, l = e_nos.length; i < l; i++) {
                    Values.push(e_nos[i]);
                }
                $("#pi_purchase_order").val(Values).trigger('change');
                $("#pi_purchase_order").attr('readonly', true);

                var shipping = JSON.parse(response.shipping);
                $("#shipping_add_1").val(shipping.address1);
                $("#shipping_add_2").val(shipping.address2);
                $("#shipping_add_3").empty().append($("<option/>").val(shipping.address3).text(shipping.address3)).val(shipping.address3).trigger("change");

                var addons = JSON.parse(response.addons);
                $("#pi_freight").val(addons.freight.value);
                $("#pi_pf").val(addons.pf.value);
                $("#pi_round").val(addons.roundoff);
                $("#pi_tcs").val(addons.tcs);



                var items = JSON.parse(response.items);
                var len = items.product.length;

                $('[data-repeater-list="purchase_invoice"]').empty();
                $('[data-repeater-create="purchase_invoice"]').click();

                for (var i = 1; i < len; i++) {
                    $('#pi_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='purchase_invoice[" + i + "][pi_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "select[name$='purchase_invoice[" + i + "][pi_product_name]']";
                    $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_product_description]']";
                    $(tmp).val(items.desc[i]);
                    tmp = "textarea[name$='purchase_invoice[" + i + "][pi_product_description]']";
                    var temp = items.long_desc[i];
                    temp = temp.replace(/\|/g, "\r\n");
                    $(tmp).val(temp);

                    var temp_textarea = $(tmp);
                    autosize(temp_textarea);

                    tmp = "input[name$='purchase_invoice[" + i + "][pi_qty]']";
                    $(tmp).val(items.quantity[i]);
                    tmp = "select[name$='purchase_invoice[" + i + "][pi_unit]']";
                    $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_rate]']";
                    $(tmp).val(items.price[i]);
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_dsc]']";
                    $(tmp).val(items.discount[i]);
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_hsn]']";

                    $(tmp).val(items.hsn[i]);
                    tmp = "select[name$='purchase_invoice[" + i + "][pi_tax]']";
                    $(tmp).empty().append($("<option/>").val(items.tax[i]).text(items.tax[i])).val(items.tax[i]).trigger("change");
                    tmp = "select[name$='purchase_invoice[" + i + "][pi_display_make]']";
                    $(tmp).val(items.group[i]).trigger("change");
                }
                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removePurchaseInvoice(id = true) {
    if (id) {
        $('#delete_purchase_invoice_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/purchase_invoice/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Item has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            url: "../assets/custom/api_set/set_purchase_purchaseorder.php",
                            type: "POST",
                            data: { so: response.so },
                            dataType: 'json',
                            success: function (response) { }
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#delete_purchase_invoice").modal('hide');
                    managePurchaseInvoiceTable.reload();
                    set_secondary_purchase();

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function set_secondary_purchase() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'secondary_purchase' },
        dataType: 'json',
        success: function (response) {
            $("#purchase_invoice_no").val(response.value);
        }
    });
}
function set_purchase() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'purchase_invoice' },
        dataType: 'json',
        success: function (response) {
            $("#purchase_invoice_no").val(response.value);
        }
    });
}
function paymentt(id) {
    if (id) {
        // Perform an AJAX call to fetch the necessary invoice data
        $.ajax({
            url: '../assets/custom/purchase_invoice/getSelectedPI.php',  // Backend URL to fetch invoice details
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                // Extract the required details from the response
                var clientName = response.supplier_name;
                
                var totalAmount = response.total;  // Make sure 'total_amount' is part of the response

                // Redirect to the receipt page with these details as URL parameters
                window.location.href = `https://www.easthyde.com/_admin/?page=payments&client_name=${encodeURIComponent(clientName)}&total=${totalAmount}`;
            },
            error: function () {
                alert('Error: Could not fetch invoice details.');
            }
        });
    } else {
        alert('Error: No ID provided.');
    }
}
//***************************************************** -Purchase Quotation- *****************************************************

var Purchase_Quotation = function () {

    var handleAddPurchaseQuotation = function () {

        var ajaxAdd = function (form) {
            form = $(form);
    
            var formData = new FormData(form[0]); // Ensure the form is properly passed into FormData
            
            // If you are manually adding the file, ensure it's appended correctly
            let quotation_file = document.getElementById("quotation_file").files[0];
            if (quotation_file) {
                formData.append("quotation_file", quotation_file);
            }
        
            $.ajax({
                type: "POST",
                url: "../assets/custom/purchase_quotation/create.php",
                data: formData, // Send the FormData object
                contentType: false, // Necessary for FormData
                processData: false, // Necessary for FormData
                dataType: 'json', // Expect a JSON response from the server
                success: function (response) {
                    if (response.success === true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your purchase quotation has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
        
                    // Reset the form
                    $('#add_purchase_quotation')[0].reset();
        
                    
                  
                    // Reset select2 and other dynamic fields
                    $('#pi_supplier').val(null).trigger('change');
                    $('#pi_purchase_order').val(null).trigger('change');
                    $('#pi_product_name').val(null).trigger('change');
                    $('#pi_tax').val(null).trigger('change');
                    $('[data-repeater-list="purchase_quotation"]').empty();
                    $('[data-repeater-create="purchase_quotation"]').click();
        
                    // Reset serial number for the first product
                    var tmp = "input[name$='purchase_quotation[0][pq_sn]']";
                    $(tmp).val(1);
                    set_purchase_quotation();
                    managePurchaseQuotationTable.reload();
                    $("#purchase_quotation_submit").attr("disabled", false);
                },
                error: function () {
                    swal.fire({
                        position: 'top-right',
                        type: 'error',
                        title: 'An error occurred during submission.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        
            return false; // Prevent default form submission
        };
        

        $('#add_purchase_quotation').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                pi_client: {
                    required: true
                },
                purchase_quotation_no: {
                    required: true
                },
                purchase_invoice_date: {
                    required: true
                },
            },
            messages: {
                pi_client: {
                    required: 'This field is required!'
                },
                purchase_quotation_no: {
                    required: 'This field is required!'
                },
                purchase_invoice_date: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
               
                $("#purchase_invoice_submit").attr("disabled", true);
            }
        });

        $('#add_purchase_quotation input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_purchase_quotation').validate().form()) {
                    ajaxAdd($('#add_purchase_quotation')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    function set_purchase_quotation() {
        $.ajax({
            url: '../assets/custom/api_get/get_counter.php',
            type: 'post',
            data: { key: 'purchase_quotation' },
            dataType: 'json',
            success: function (response) {
                $("#purchase_quotation_no").val(response.value);
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddPurchaseQuotation();
            set_purchase_quotation();
        }
    };
}();

function editPurchaseQuotation(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/purchase_quotation/getSelectedPI.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_pi_id").val(response.id);
              
                $("#pi_supplier").empty().append($("<option/>").val(response.supplier_name).text(response.supplier_name)).val(response.supplier_name).trigger("change");
                $("#purchase_quotation_no").val(response.pq_no);
                $("#mobile").val(response.mobile);

                var pi_date = new Date(response.pi_date);
                var formatted_date = appendLeadingZeroes(pi_date.getDate()) + "-" + appendLeadingZeroes(pi_date.getMonth() + 1) + "-" + pi_date.getFullYear();
                console.log(formatted_date);
                $("#purchase_invoice_date").val(formatted_date);


                var addons = JSON.parse(response.addons);
                $("#pi_freight").val(addons.freight.value);
                $("#pi_pf").val(addons.pf.value);
                $("#pi_round").val(addons.roundoff);
                $("#pi_tcs").val(addons.tcs);



                var items = JSON.parse(response.items);
                var len = items.product.length;

                $('[data-repeater-list="purchase_invoice"]').empty();
                $('[data-repeater-create="purchase_invoice"]').click();

                for (var i = 1; i < len; i++) {
                    $('#pi_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='purchase_invoice[" + i + "][pi_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "select[name$='purchase_invoice[" + i + "][pi_product_name]']";
                    $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_product_description]']";
                    $(tmp).val(items.desc[i]);
                    tmp = "textarea[name$='purchase_invoice[" + i + "][pi_product_description]']";
                    var temp = items.long_desc[i];
                    temp = temp.replace(/\|/g, "\r\n");
                    $(tmp).val(temp);

                    var temp_textarea = $(tmp);
                    autosize(temp_textarea);

                    tmp = "input[name$='purchase_invoice[" + i + "][pi_qty]']";
                    $(tmp).val(items.quantity[i]);
                    tmp = "select[name$='purchase_invoice[" + i + "][pi_unit]']";
                    $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_rate]']";
                    $(tmp).val(items.price[i]);
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_dsc]']";
                    $(tmp).val(items.discount[i]);
                    tmp = "input[name$='purchase_invoice[" + i + "][pi_hsn]']";

                    $(tmp).val(items.hsn[i]);
                    tmp = "select[name$='purchase_invoice[" + i + "][pi_tax]']";
                    $(tmp).empty().append($("<option/>").val(items.tax[i]).text(items.tax[i])).val(items.tax[i]).trigger("change");
                    // tmp = "select[name$='purchase_invoice[" + i + "][pi_display_make]']";
                    // $(tmp).val(items.group[i]).trigger("change");
                }
                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removePurchaseQuotation(id = true) {
    if (id) {
        $('#delete_purchase_quotation_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/purchase_quotation/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Item has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                       
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#delete_purchase_quotation").modal('hide');
                    managePurchaseQuotationTable.reload();
                    set_purchase_quotation();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}



//***************************************************** -Purchase Bag- *****************************************************

var Purchase_Bag = function () {

    var showErrorMsg = function (form, type, msg) {
        var alert = $('<div class="alert alert-' + type + ' alert-dismissible" role="alert">\
            <div class="alert-text">' + msg + '</div>\
            <div class="alert-close">\
                <i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\
            </div>\
        </div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        //alert.animateClass('fadeIn animated');
        KTUtil.animateClass(alert[0], 'fadeIn animated');
        alert.find('span').html(msg);
    }

    var handleAddPurchaseBag = function () {
        $('#add_pb_submit').click(function (e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {}

            });

            if (!form.valid()) {
                return;
            }

            form.ajaxSubmit({
                type: "POST",
                url: "../assets/custom/purchase_bag/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true)
                        addPurchaseBagToast(response.messages);
                    else
                        addPurchaseBagToastError(response.messages);

                    //Reset The Form
                    $('#add_purchase_bag')[0].reset();
                    // close the modal
                    $("#kt_modal_add_purchase_bag").modal('hide');
                }
            });
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddPurchaseBag();
        }
    };
}();

function PurchaseBagLoad(name) {
    $("#pb_product").val(name);
}

function PurchaseBagLoadSO(name, qty) {
    $("#pb_product").val(name);
    $("#pb_quantity").val(qty);
}

function addToPurchaseOrder(id) {

    $.ajax({
        url: '../assets/custom/purchase_bag/getSelectedPurchaseBag.php',
        type: 'post',
        data: { member_id: id },
        dataType: 'json',
        success: function (response) {

            console.log()

            var tmp = "input[name$='purchase_order[0][po_product_description]']";
            var desc = $(tmp).val();

            var rep = document.getElementById('purchase_order_list');
            var rowsCount = rep.childNodes.length;

            if (rowsCount == 1 && desc == '') {
                rowsCount -= 1;
            } else {
                $('[data-repeater-create="purchase_order"]').click();
            }

            tmp = "select[name$='purchase_order[" + rowsCount + "][po_product_name]']";
            // var pr = response.product_name;
            var pr = JSON.parse(response.data).items[0];
            $(tmp).empty().append($("<option/>").val(pr).text(pr)).val(pr).trigger("change");
            console.log(tmp);

            tmp = "input[name$='purchase_order[" + rowsCount + "][po_qty]']";
            $(tmp).val(response.quantity);
            $("#po_pf").val('0');

            managePurchaseBagTable.reload();

            KTUtil.scrollTop();

            // swal.fire({
            //     position: 'top-right',
            //     type: 'info',
            //     title: 'Product Added in the list above.',
            //     showConfirmButton: false,
            //     timer: 1500
            // });
        }
    });
}

function addPurchaseBagToast(msg) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    toastr.success(msg, "Successfully Added");
}

function addPurchaseBagToastError(msg) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    toastr.error(msg, "Error !!");
}

function removePurchaseBag(id = true) {
    if (id) {
        $('#delete_purchase_bag_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/purchase_bag/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Item has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#delete_item_purchase_bag").modal('hide');
                    managePurchaseBagTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Payments- *****************************************************

var payment_id;

var Payments = function () {

    var handleAddPayments = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/payments/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your payment has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            url: "../assets/custom/api_set/set_purchase_payment.php",
                            type: "POST",
                            data: { py_no: response.py_no },
                            dataType: 'json',
                            success: function (response) { }
                        });
                        //Reset The Form
                        $('#add_payment')[0].reset();
                        // close the modal
                        managePaymentsTable.reload();
                        $('#py_supplier').val(null).trigger('change');
                        $('#py_bank').val(null).trigger('change');
                        $('#py_mode').val(null).trigger('change');
                        $('[data-repeater-list="payment"]').empty();
                        $('[data-repeater-create="payment"]').click();
                        var tmp = "input[name$='payment[0][py_sn]']";
                        $(tmp).val(1);

                        document.getElementById("bank_details").style.display = "none";
                        document.getElementById("bank_details_title").style.display = "none";

                        document.getElementById("invoice_details").style.display = "none";
                        document.getElementById("invoice_details_title").style.display = "none";

                    } else if (response.success == 'mismatch') {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'The totals do not tally.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }


                }
            });

            return false;
        }

        $('#add_payment').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                py_supplier: {
                    required: true
                }
            },
            messages: {
                py_supplier: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_payment input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_payment').validate().form()) {
                    ajaxAdd($('#add_payment')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddPayments();
        }
    };
}();

function editPayments(id) {

    if (id) {
        $.ajax({
            url: '../assets/custom/payments/getSelectedPayment.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#py_id").val(response.id);
                $("#py_no").val(response.r_no);
                $("#py_supplier").empty().append($("<option/>").val(response.supplier).text(response.supplier)).val(response.supplier).trigger("change");
                var receipts_date = new Date(response.date);
                var formatted_date = appendLeadingZeroes(receipts_date.getDate()) + "-" + appendLeadingZeroes(receipts_date.getMonth() + 1) + "-" + receipts_date.getFullYear();
                // console.log(formatted_date);
                $("#py_date").val(formatted_date);
                $("#py_bank").empty().append($("<option/>").val(response.account).text(response.account)).val(response.account).trigger("change");

                $("#py_supplier").attr("readonly", true);

                var purchase_invoice = JSON.parse(response.purchase_invoice);
                var len = purchase_invoice.pi_no.length;

                $('[data-repeater-list="payment"]').empty();
                $('[data-repeater-create="payment"]').click();

                for (var i = 1; i < len; i++) {
                    $('#py_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='payment[" + i + "][py_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "input[name$='payment[" + i + "][py_pi_no]']";
                    $(tmp).val(purchase_invoice.pi_no[i]);
                    tmp = "input[name$='payment[" + i + "][py_pi_amount]']";
                    $(tmp).val(purchase_invoice.due[i]);
                    tmp = "input[name$='payment[" + i + "][py_pi_amount_due]']";
                    $(tmp).val(purchase_invoice.due[i]);
                    tmp = "input[name$='payment[" + i + "][py_amount]']";
                    $(tmp).val(purchase_invoice.amount[i]);



                    KTUtil.scrollTop();
                }

                $("#py_mode").val(response.mode).trigger("change");
                $("#py_bank_name").val(response.bank_name);
                $("#py_cheque").val(response.cheque);
                $("#py_ifsc").val(response.ifsc);


            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removePayments(id = true) {
    if (id) {
        //click remove button
        $('#delete_payment_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/payments/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        deletePaymentsToast(response.messages);
                    } else {
                        deletePaymentsToastError(response.messages);
                    }

                    // close the modal
                    $("#kt_modal_d_payment").modal('hide');
                    managePaymentsTable.reload();
                }
            });
        });
        // click remove button
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Enquitry- *****************************************************

var Enquiry = function () {

    var handleAddEnquiry = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/enquiry/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your enquiry has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_enquiry')[0].reset();
                    // close the modal
                    manageEnquiryTable.reload();
                    $('#e_client').val(null).trigger('change');
                    $('#enquiry_mode').val(null).trigger('change');

                    $('[data-repeater-list="enquiry"]').empty();
                    $('[data-repeater-create="enquiry"]').click();
                    var tmp = "input[name$='enquiry[0][e_sn]']";
                    $(tmp).val(1);
                    set_enquiry_no();
                    $("#enquiry_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#add_enquiry').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                e_client: {
                    required: true
                },
                enquiry_no: {
                    required: true
                },
                enquiry_date: {
                    required: true
                },
            },
            messages: {
                e_client: {
                    required: 'This field is required'
                },
                enquiry_no: {
                    required: 'This field is required'
                },
                enquiry_date: {
                    required: 'This field is required'
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#enquiry_submit").attr("disabled", true);
            }
        });

        $('#add_enquiry input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_enquiry').validate().form()) {
                    ajaxAdd($('#add_enquiry')); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddEnquiry();
        }
    };
}();

function set_enquiry_no() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'enquiry' },
        dataType: 'json',
        success: function (response) {
            $("#enquiry_no").val(response.value);
        }
    });
}

function editEnquiry(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/enquiry/getSelectedEnquiry.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#e_id").val(response.id);
                $("#e_client").empty().append($("<option/>").val(response.client).text(response.client)).val(response.client).trigger("change");
                $("#enquiry_no").val(response.enquiry_no);

                let enquiry_date = new Date(response.enquiry_date);
                let formatted_date = appendLeadingZeroes(enquiry_date.getDate()) + "-" + appendLeadingZeroes(enquiry_date.getMonth() + 1) + "-" + enquiry_date.getFullYear();
                console.log(formatted_date);

                $("#enquiry_date").val(formatted_date);
                $("#enquiry_mode").val(response.mode).trigger("change");
                $("#enquiry_status").val(response.status).trigger("change");
                $("#client_enquiry_no").val(response.cl_enquiry_no);

                var items = JSON.parse(response.items);
                var len = items.product.length;

                $('[data-repeater-list="enquiry"]').empty();
                $('[data-repeater-create="enquiry"]').click();

                for (var i = 1; i < len; i++) {
                    $('#enq_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='enquiry[" + i + "][e_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "select[name$='enquiry[" + i + "][e_product_name]']";
                    $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                    tmp = "input[name$='enquiry[" + i + "][e_product_description]']";
                    $(tmp).val(items.desc[i]);
                    tmp = "textarea[name$='enquiry[" + i + "][e_product_add_description]']";
                    var temp = items.long_desc[i];
                    temp = temp.replace(/\|/g, "\r\n");
                    $(tmp).val(temp);

                    var temp_textarea = $(tmp);
                    autosize(temp_textarea);

                    tmp = "input[name$='enquiry[" + i + "][e_qty]']";
                    $(tmp).val(items.quantity[i]);
                    tmp = "input[name$='enquiry[" + i + "][e_current_stock]']";
                    $(tmp).val(items.stock[i]);
                    tmp = "input[name$='enquiry[" + i + "][e_company_stock]']";
                    $(tmp).val(items.co_stock[i]);
                }
                KTUtil.scrollTop();

            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeEnquiry(id = true) {
    if (id) {
        $('#delete_enquiry_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/enquiry/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Enquiry has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_enquiry").modal('hide');
                    manageEnquiryTable.reload();
                    set_enquiry_no();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Ledger- ********************************************************

function sendCLEmail(id) {
    if (id) {
        $("#cl_em_id").val(id);
        $.ajax({
            url: '../assets/custom/clients/email_message.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#cl_em_email").val(response.email);
                    $("#cl_em_subject").val(response.subject);
                    $('#cl_em_message').summernote('code', response.em_message);
                }

            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function sendSLEmail(id) {
    if (id) {
        $("#sl_em_id").val(id);
        $.ajax({
            url: '../assets/custom/suppliers/email_message.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#sl_em_email").val(response.email);
                    $("#sl_em_subject").val(response.subject);
                    $('#sl_em_message').summernote('code', response.em_message);
                }

            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}
var Ledger = function () {

    var handleSendCLEmail = function () {

        var ajaxAdd = function (form) {
            // $('.summernote').each( function() {
            //     $(this).val($(this).code());
            // });
            form = $(form);

            $.ajax({
                type: "POST",
                url: "../assets/custom/client_ledger_email.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Email Sent!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in sending the email.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#send_cl_email')[0].reset();
                    $("#kt_modal_cl_email").modal('hide');
                }
            });

            return false;
        }

        $('#send_cl_email').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#send_cl_email input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_cl_email').validate().form()) {
                    ajaxAdd($('#send_cl_email')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleSendSLEmail = function () {

        var ajaxAdd = function (form) {
            // $('.summernote').each( function() {
            //     $(this).val($(this).code());
            // });
            form = $(form);

            $.ajax({
                type: "POST",
                url: "../assets/custom/supplier_ledger_email.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Email Sent!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in sending the email.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#send_sl_email')[0].reset();
                    $("#kt_modal_sl_email").modal('hide');
                }
            });

            return false;
        }

        $('#send_sl_email').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#send_sl_email input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_sl_email').validate().form()) {
                    ajaxAdd($('#send_sl_email')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    return {
        init: function () {
            handleSendCLEmail();
            handleSendSLEmail();
        }
    };
}();

//***************************************************** -Quotation- *****************************************************

var Quotation = function () {

    var wizardEl;
    var formEl;
    var validator;
    var wizard;

    var initWizard = function () {
        wizard = new KTWizard('kt_wizard_v3', {
            startStep: 1, // initial active step number
            clickableSteps: false // allow step clicking
        });

        wizard.on('beforeNext', function (wizardObj) {
            if (validator.form() !== true) {
                wizardObj.stop(); // don't go to the next step
            }
        });

        wizard.on('beforePrev', function (wizardObj) {
            if (validator.form() !== true) {
                wizardObj.stop(); // don't go to the next step
            }
        });

        wizard.on('change', function (wizard) {
            KTUtil.scrollTop();
        });
    }

    var initValidation = function () {
        validator = formEl.validate({
            ignore: ":hidden",

            rules: {},

            invalidHandler: function (event, validator) {
                KTUtil.scrollTop();
            },

            submitHandler: function (form) {

            }
        });
    }

    var initSubmit = function () {
        var btn = formEl.find('[data-ktwizard-type="action-submit"]');

        btn.on('click', function (e) {
            e.preventDefault();

            if (validator.form()) { }
        });
    }

    var handleAddQuotation = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/quotation/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your quotation has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            type: "POST",
                            url: "../assets/custom/pdf_master/_quotation.php",
                            data: { id: response.q_no },
                            dataType: 'json',
                            success: function (response) {
                                console.log("saved");
                            }
                        });
                        $.ajax({
                            url: "../assets/custom/api_set/set_enquiry_quotation.php",
                            type: "POST",
                            data: { q_no: response.q_no },
                            dataType: 'json',
                            success: function (response) { }
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_quotation')[0].reset();
                    // close the modal
                    manageQuotationTable.reload();
                    $('#q_client').val(null).trigger('change');
                    $('[data-repeater-list="quotation"]').empty();
                    $('[data-repeater-create="quotation"]').click();
                    var tmp = "input[name$='quotation[0][q_sn]']";
                    $(tmp).val(1);

                    wizard.goFirst();
                    set_quotation_no();
                    $("#quotation_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#add_quotation').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                q_client: {
                    required: true
                },
                quotation_no: {
                    required: true
                },
                quotation_date: {
                    required: true
                },
            },
            messages: {
                q_client: {
                    required: 'This field is required!'
                },
                quotation_no: {
                    required: 'This field is required!'
                },
                quotation_date: {
                    required: 'This field is required!'
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#quotation_submit").attr("disabled", true);
            }
        });

        $('#quotation_submit').on('click', function (e) {

            $('.alert').hide();
            $('.alert span').html("");
            if ($('#add_quotation').validate().form()) {
                ajaxAdd($('#add_quotation')); //form validation success, call ajax form submit
            }
            return false;
        });
    }

    var handleAddNoteQuotation = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/quotation/create_note.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Note added successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#add_qnote')[0].reset();
                    $("#kt_modal_a_qnote").modal('hide');
                }
            });

            return false;
        }

        $('#add_qnote').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_qnote input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_qnote').validate().form()) {
                    ajaxAdd($('#add_qnote')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleSendQEmail = function () {

        var ajaxAdd = function (form) {
            // $('.summernote').each( function() {
            //     $(this).val($(this).code());
            // });
            form = $(form);

            $.ajax({
                type: "POST",
                url: "../assets/custom/quotation_email.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Email Sent!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in sending the email.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#send_q_email')[0].reset();
                    $("#kt_modal_q_email").modal('hide');
                }
            });

            return false;
        }

        $('#send_q_email').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#send_q_email input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_q_email').validate().form()) {
                    ajaxAdd($('#send_q_email')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    return {
        init: function () {
            wizardEl = KTUtil.get('kt_wizard_v3');
            formEl = $('#add_quotation');

            initWizard();
            initValidation();
            initSubmit();

            handleAddQuotation();
            handleAddNoteQuotation();
            handleSendQEmail();

        }
    };
}();

function set_quotation_no() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'quotation' },
        dataType: 'json',
        success: function (response) {
            $("#quotation_no").val(response.value);
        }
    });
}

function editQuotation(id) {

    if (id) {
        $.ajax({
            url: '../assets/custom/quotation/getSelectedQuotation.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#q_id").val(response.id);
                $("#q_state").val(response.state);
                $('#mobile').val(response.mobile);

                $("#q_client").empty().append($("<option/>").val(response.client).text(response.client)).val(response.client).trigger("change");
                $("#state").val(response.state);
                $('#quotation_no').val(response.quotation_no);
                $("#country").val(response.country);

                var address = response.address;
                if (address != null) {
                    address = JSON.parse(address);
                    $("#address_1").val(address.address_1);
                    $("#address_2").val(address.address_2);
                    $("#city").val(address.city);
                    $("#pincode").val(address.pincode);
                } else {
                    $("#address_1").val('');
                    $("#address_2").val('');
                    $("#city").val('');
                    $("#pincode").val('');
                }

                var terms = JSON.parse(response.terms);
                $("#prices").val(terms.prices);
                $("#pf").val(terms.pf);
                $("#freight").val(terms.freight);
                $("#delivery").val(terms.delivery);
                $("#payment").val(terms.payment);
                $("#validity").val(terms.validity);
                $("#remarks").val(terms.remarks);

                $("#q_client").attr("readonly", true);
                $("#quotation_no").attr("readonly", true);

                var e_nos = JSON.parse(response.quotation_top);

                $('#q_enquiry_no').empty();
                for (var i = 0, l = e_nos.enquiry_no.length; i < l; i++) {

                    var enquiry_no = e_nos.enquiry_no[i];
                    $('#q_enquiry_no').append($("<option/>") //add option tag in select
                        .val(enquiry_no) //set value for option to post it
                        .text(enquiry_no));
                }

                var Values = new Array();
                for (var i = 0, l = e_nos.enquiry_no.length; i < l; i++) {
                    Values.push(e_nos.enquiry_no[i]);
                }
                $("#q_enquiry_no").val(Values).trigger('change');
                $("#q_enquiry_no").attr("readonly", true);


                // $("#e16,#e16_2").select2("readonly", true);

                $("#q_cl_enquiry_no").val(JSON.stringify(e_nos.cl_enquiry_no));
                $("#q_enquiry_date").val(JSON.stringify(e_nos.enquiry_date));

                var addons = JSON.parse(response.addons);
                $("#q_freight").val(addons.freight.value);
                $("#q_pf").val(addons.pf.value);

                var quotation_date = new Date(response.quotation_date);
                var formatted_date = appendLeadingZeroes(quotation_date.getDate()) + "-" + appendLeadingZeroes(quotation_date.getMonth() + 1) + "-" + quotation_date.getFullYear();
                console.log(formatted_date);
                $("#quotation_date").val(formatted_date);

                var items = JSON.parse(response.items);
                var len = items.product.length;

                $('[data-repeater-list="quotation"]').empty();
                $('[data-repeater-create="quotation"]').click();

                for (var i = 1; i < len; i++) {
                    $('#qtn_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='quotation[" + i + "][q_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "select[name$='quotation[" + i + "][q_product_name]']";
                    $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                    tmp = "input[name$='quotation[" + i + "][q_product_description]']";
                    $(tmp).val(items.desc[i]);
                    tmp = "textarea[name$='quotation[" + i + "][q_product_add_description]']";
                    var temp = items.long_desc[i];
                    temp = temp.replace(/\|/g, "\r\n");
                    $(tmp).val(temp);

                    var temp_textarea = $(tmp);
                    autosize(temp_textarea);

                    tmp = "input[name$='quotation[" + i + "][q_qty]']";
                    $(tmp).val(items.quantity[i]);
                    tmp = "select[name$='quotation[" + i + "][q_unit]']";
                    $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                    tmp = "input[name$='quotation[" + i + "][q_rate]']";
                    $(tmp).val(items.price[i]);
                    tmp = "input[name$='quotation[" + i + "][q_dsc]']";
                    $(tmp).val(items.discount[i]);
                    tmp = "input[name$='quotation[" + i + "][q_hsn]']";
                    $(tmp).val(items.hsn[i]);
                    tmp = "select[name$='quotation[" + i + "][q_tax]']";
                    $(tmp).val(items.tax[i]).trigger("change");
                    tmp = "select[name$='quotation[" + i + "][q_display_make]']";
                    $(tmp).val(items.group[i]).trigger("change");

                    KTUtil.scrollTop();
                }
            }



        });

        $('#quotation_submit').on('click', function (e) {
            location.reload();
        });


    } else {
        alert('Error : Please refresh the page');
    }
}



function removeQuotation(id = true) {
    if (id) {
        $('#delete_quotation_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/quotation/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your quotation has been deleted Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_quotation").modal('hide');
                    manageQuotationTable.reload();
                    set_quotation_no();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function cancelQuotation(id = true) {
    if (id) {
        $('#cancel_quotation_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/quotation/cancel.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Quotation cancelled successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#cancel_quotation").modal('hide');
                    manageQuotationTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function toggleQHSN(id = true) {
    if (id) {
        $('#toggle_quotation_hsn_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/quotation/hsn.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Quotation updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#toggle_quotation_hsn").modal('hide');

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function toggleQTotals(id = true) {
    if (id) {
        $('#toggle_quotation_totals_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/quotation/totals.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Quotation updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#toggle_quotation_totals").modal('hide');

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function duplicateQuotation(id = true) {
    if (id) {
        $('#duplicate_quotation_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/quotation/duplicate.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your quotation has been duplicated Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_duplicate_quotation").modal('hide');
                    manageQuotationTable.reload();
                    set_quotation_no();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function addNoteQuotation(id) {
    if (id) {
        $("#an_q_no").val(id);
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeNoteQuotation(id, index) {
    console.log(id);

    if (id) {
        $('#delete_qnote_submit').unbind('click').bind('click', function () {
            $.ajax({
                url: '../assets/custom/quotation/delete_note.php',
                type: 'post',
                data: { member_id: id, index: index },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Note has been deleted Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_qnote").modal('hide');
                    location.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function sendQEmail(id) {
    if (id) {
        $("#q_em_id").val(id);
        $.ajax({
            url: '../assets/custom/quotation/email_message.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#q_em_email").val(response.email);
                    $("#q_em_subject").val(response.subject);
                    // $("#q_em_message").innerHTML(response.em_message);
                    $('#q_em_message').summernote('code', response.em_message);
                    $('#q_em_email_bcc').val('sentmail@easthyde.com');
                }

            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Sales Order- *****************************************************

var Sales_Order = function () {

    var handleAddSalesOrder = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/sales_order/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your sales order has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            type: "POST",
                            url: "../assets/custom/pdf_master/_sales_order.php",
                            data: { id: response.so },
                            dataType: 'json',
                            success: function (response) {
                                console.log("saved");
                            }
                        });
                        $.ajax({
                            url: "../assets/custom/api_set/set_quotation_so.php",
                            type: "POST",
                            data: { so: response.so },
                            dataType: 'json',
                            success: function (response) { }
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_sales_order')[0].reset();
                    // close the modal
                    manageSalesOrderTable.reload();
                    set_sales_order_no();
                    $('#so_client').val(null).trigger('change');
                    $('#so_quotation').val(null).trigger('change');
                    $('[data-repeater-list="sales_order"]').empty();
                    $('[data-repeater-create="sales_order"]').click();
                    var tmp = "input[name$='sales_order[0][so_sn]']";
                    $(tmp).val(1);
                    $("#sales_order_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#add_sales_order').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                so_client: {
                    required: true
                },
                sales: {
                    required: true
                },
                sales_date: {
                    required: true
                },
            },
            messages: {
                so_client: {
                    required: 'This field is required!'
                },
                sales: {
                    required: 'This field is required!'
                },
                sales_date: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#sales_order_submit").attr("disabled", true);
            }
        });

        $('#add_sales_order input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_sales_order').validate().form()) {
                    ajaxAdd($('#add_sales_order')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    var handleSendSOEmail = function () {

        var ajaxAdd = function (form) {
            // $('.summernote').each( function() {
            //     $(this).val($(this).code());
            // });
            form = $(form);

            $.ajax({
                type: "POST",
                url: "../assets/custom/sales_order_email.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Email Sent!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in sending the email.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#send_so_email')[0].reset();
                    $("#kt_modal_so_email").modal('hide');
                }
            });

            return false;
        }

        $('#send_so_email').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#send_so_email input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_so_email').validate().form()) {
                    ajaxAdd($('#send_so_email')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    var handleAddNoteSalesOrder = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/sales_order/create_note.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Note added successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#add_so_note')[0].reset();
                    $("#kt_modal_so_note").modal('hide');
                }
            });

            return false;
        }

        $('#add_so_note').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_so_note input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_so_note').validate().form()) {
                    ajaxAdd($('#add_so_note')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddSalesOrder();
            handleSendSOEmail();
            handleAddNoteSalesOrder();
        }
    };
}();

function set_sales_order_no() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'sales_order' },
        dataType: 'json',
        success: function (response) {
            $("#sales").val(response.value);
        }
    });
}

function editSalesOrder(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/sales_order/getSelectedSO.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_so_id").val(response.id);
                $("#so_client").empty().append($("<option/>").val(response.client_name).text(response.client_name)).val(response.client_name).trigger("change");
                $("#client_so_no").val(response.client_so_no);
                $("#so_state").val(response.state);


                $("#so_collected").val(response.collected).trigger("change");


                if (response.q_no != '' && response.q_no != null) {
                    var e_nos = JSON.parse(response.q_no);
                    $('#so_quotation').empty();
                    for (var i = 0, l = e_nos.length; i < l; i++) {

                        var quotation_no = e_nos[i];
                        $('#so_quotation').append($("<option/>") //add option tag in select
                            .val(quotation_no) //set value for option to post it
                            .text(quotation_no));
                    }

                    var Values = new Array();
                    for (var i = 0, l = e_nos.length; i < l; i++) {
                        Values.push(e_nos[i]);
                    }
                    $("#so_quotation").val(Values).trigger('change');
                    $("#so_quotation").attr('readonly', true);
                }

                $("#sales").val(response.so_no);

                var so_date = new Date(response.so_date);
                var formatted_date = appendLeadingZeroes(so_date.getDate()) + "-" + appendLeadingZeroes(so_date.getMonth() + 1) + "-" + so_date.getFullYear();
                console.log(formatted_date);
                $("#sales_date").val(formatted_date);

                if (response.addons != '' && response.addons != null) {
                    var addons = JSON.parse(response.addons);
                    $("#so_freight").val(addons.freight.value);
                    $("#so_pf").val(addons.pf.value);
                }

                if (response.items != '' && response.items != null) {
                    var items = JSON.parse(response.items);
                    var len = items.product.length;

                    $('[data-repeater-list="sales_order"]').empty();
                    $('[data-repeater-create="sales_order"]').click();

                    for (var i = 1; i < len; i++) {
                        $('#so_btn_add').click();
                    }

                    var tmp = '';
                    for (var i = 0; i < len; i++) {

                        tmp = "input[name$='sales_order[" + i + "][so_sn]']";
                        $(tmp).val(i + 1);
                        tmp = "select[name$='sales_order[" + i + "][so_product_name]']";
                        $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                        tmp = "input[name$='sales_order[" + i + "][so_product_description]']";
                        $(tmp).val(items.desc[i]);
                        tmp = "textarea[name$='sales_order[" + i + "][so_product_description]']";
                        var temp = items.long_desc[i];
                        temp = temp.replace(/\|/g, "\r\n");
                        $(tmp).val(temp);

                        var temp_textarea = $(tmp);
                        autosize(temp_textarea);

                        tmp = "input[name$='sales_order[" + i + "][so_qty]']";
                        $(tmp).val(items.quantity[i]);
                        tmp = "select[name$='sales_order[" + i + "][so_unit]']";
                        $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                        tmp = "input[name$='sales_order[" + i + "][so_rate]']";
                        $(tmp).val(items.price[i]);
                        tmp = "input[name$='sales_order[" + i + "][so_dsc]']";
                        $(tmp).val(items.discount[i]);
                        tmp = "input[name$='sales_order[" + i + "][so_hsn]']";
                        $(tmp).val(items.hsn[i]);
                        tmp = "select[name$='sales_order[" + i + "][so_tax]']";
                        $(tmp).val(items.tax[i]).trigger("change");
                        tmp = "select[name$='sales_order[" + i + "][so_display_make]']";
                        $(tmp).val(items.group[i]).trigger("change");
                    }

                    $("#so_client").attr('readonly', true);
                } else {


                }

                KTUtil.scrollTop();

            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function createProforma(id) {
    // console.log(id);
    if (id) {
        $('#create_proforma_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/sales_order/create_proforma.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your proforma invoice have been created.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            type: "POST",
                            url: "../assets/custom/pdf_master/_proforma_invoice.php",
                            data: { id: response.so },
                            dataType: 'json',
                            success: function (response) {
                                console.log("saved");
                            }
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#create_proforma").modal('hide');
                    manageProformaInvoiceTable.reload();

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }

}

function removeSalesOrder(id = true) {
    if (id) {
        $('#delete_sales_order_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/sales_order/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your sales order has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#delete_sales_order").modal('hide');
                    manageSalesOrderTable.reload();
                    set_sales_order_no();

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function cancelSalesOrder(id = true) {
    if (id) {
        $('#cancel_sales_order_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/sales_order/cancel.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Sales Order cancelled successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#cancel_sales_order").modal('hide');
                    manageSalesOrderTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function sendSOEmail(id) {
    if (id) {
        $("#so_em_id").val(id);
        $.ajax({
            url: '../assets/custom/sales_order/email_message.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#so_em_email").val(response.email);
                    $("#so_em_subject").val(response.subject);
                    // $("#q_em_message").innerHTML(response.em_message);
                    $('#so_em_message').summernote('code', response.em_message);
                    $('#so_em_email_bcc').val('sentmail@easthyde.com');
                }

            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function addNoteSalesOrder(id) {
    if (id) {
        $("#an_so_no").val(id);
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeNoteSalesOrder(id, index) {
    console.log(id);

    if (id) {
        $('#delete_sales_order_note_submit').unbind('click').bind('click', function () {
            $.ajax({
                url: '../assets/custom/sales_order/delete_note.php',
                type: 'post',
                data: { member_id: id, index: index },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Note has been deleted Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_so_note").modal('hide');
                    location.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Sales Invoive- *****************************************************

var Sales_Invoice = function () {

    var wizardEl;
    var formEl;
    var validator;
    var wizard;

    var initWizard = function () {
        wizard = new KTWizard('kt_wizard_sales', {
            startStep: 1, // initial active step number
            clickableSteps: true // allow step clicking
        });

        wizard.on('beforeNext', function (wizardObj) {
            if (validator.form() !== true) {
                wizardObj.stop(); // don't go to the next step
            }
        });

        wizard.on('beforePrev', function (wizardObj) {
            if (validator.form() !== true) {
                wizardObj.stop(); // don't go to the next step
            }
        });

        wizard.on('change', function (wizard) {
            KTUtil.scrollTop();
        });
    }

    var initValidation = function () {
        validator = formEl.validate({
            ignore: ":hidden",

            rules: {},

            invalidHandler: function (event, validator) {
                KTUtil.scrollTop();
            },

            submitHandler: function (form) {

            }
        });
    }

    var initSubmit = function () {
        var btn = formEl.find('[data-ktwizard-type="action-submit"]');

        btn.on('click', function (e) {
            e.preventDefault();

            if (validator.form()) { }
        });
    }

    var handleAddSalesInvoice = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/sales_invoice/create1.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your sales invoice has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            type: "POST",
                            url: "../assets/custom/pdf_master/_sales_invoice.php",
                            data: { id: response.si },
                            dataType: 'json',
                            success: function (response) {
                                console.log("saved");
                            }
                        });
                        $.ajax({
                            url: "../assets/custom/api_set/set_sales_salesorder.php",
                            type: "POST",
                            data: { si: response.si },
                            dataType: 'json',
                            success: function (response) { }
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_sales_invoice')[0].reset();
                    // close the modal

                    if (param_page == 'sales') {
                        manageSalesInvoiceTable.reload();
                    }

                    if (param_page == 'secondary_sales') {
                        manageSalesSecondaryTable.reload();
                    }
                    var data = $('#si_series').val();
                    set_sales_invoice_no(data);

                    $('#si_client').val(null).trigger('change');
                    $('#si_sales_order').val(null).trigger('change');
                    $('#si_product_name').val(null).trigger('change');
                    $('#si_tax').val(null).trigger('change');
                    $('#shipping_state').val(null).trigger('change');
                    $('[data-repeater-list="sales_invoice"]').empty();
                    $('[data-repeater-create="sales_invoice"]').click();
                    var tmp = "input[name$='sales_invoice[0][si_sn]']";
                    $(tmp).val(1);
                    wizard.goFirst();
                    $("#sales_invoice_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#add_sales_invoice').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                si_client: {
                    required: true
                },
                sales: {
                    required: true
                },
                sales_invoice_date: {
                    required: true
                },
            },
            messages: {
                si_client: {
                    required: 'This field is required!'
                },
                sales: {
                    required: 'This field is required!'
                },
                sales_invoice_date: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#sales_invoice_submit").attr("disabled", true);
            }
        });

        $('#sales_invoice_submit').on('click', function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if ($('#add_sales_invoice').validate().form()) {
                ajaxAdd($('#add_sales_invoice')); //form validation success, call ajax form submit
            }
            return false;
        });
    }

    var handleSendSIEmail = function () {

        var ajaxAdd = function (form) {
            // $('.summernote').each( function() {
            //     $(this).val($(this).code());
            // });
            form = $(form);

            $.ajax({
                type: "POST",
                url: "../assets/custom/sales_invoice_email.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Email Sent!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in sending the email.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#send_si_email')[0].reset();
                    $("#kt_modal_si_email").modal('hide');
                }
            });

            return false;
        }

        $('#send_si_email').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#send_si_email input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_si_email').validate().form()) {
                    ajaxAdd($('#send_si_email')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {

            wizardEl = KTUtil.get('kt_wizard_sales');
            formEl = $('#add_sales_invoice');

            initWizard();
            initValidation();
            initSubmit();

            handleAddSalesInvoice();
            handleSendSIEmail();
        }
    };
}();

function set_sales_invoice_no(data) {
    if (data == 'PRIMARY') {
        data = 'sales_invoice';
    } else if (data == 'ECOMMERCE') {
        data = 'e-commerce';
    } else if (data == 'SECONDARY') {
        data = 'secondary';
    }

    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: data },
        dataType: 'json',
        success: function (response) {
            $("#sales_invoice_no").val(response.value);
        }
    });
    console.log('hello');
}

function editSalesInvoice(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/sales_invoice/getSelectedSI.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_si_id").val(response.id);
                $("#si_state").val(response.state);
                $("#mobile").val(response.mobile);


                $("#si_client").empty().append($("<option/>").val(response.client_name).text(response.client_name)).val(response.client_name).trigger("change");
                // if (response.cancelled != '1')
                //     $("#si_client").attr('readonly', true);
                $("#sales_invoice_no").val(response.si_no);
                if (response.cancelled == '1')
                    $("#sales_invoice_no").attr('readonly', true);
                if (response.si_date != '') {
                    var si_date = new Date(response.si_date);
                    var formatted_date = appendLeadingZeroes(si_date.getDate()) + "-" + appendLeadingZeroes(si_date.getMonth() + 1) + "-" + si_date.getFullYear();
                    console.log(formatted_date);
                    $("#sales_invoice_date").val(formatted_date);
                    if (response.cancelled == '1') {
                        $("#sales_invoice_date").attr('readonly', true);
                        $("#sales_invoice_date").removeClass("date-picker");
                    }
                }

                $("#si_series").empty().append($("<option/>").val(response.series).text(response.series)).val(response.series).trigger("change");
                if (response.cancelled == '1')
                    $("#si_series").attr('readonly', true);

                if (response.shipping != '') {
                    var shipping = JSON.parse(response.shipping);
                    $("#shipping_name").val(shipping.name);
                    $("#shipping_add_1").val(shipping.address_1);
                    $("#shipping_add_2").val(shipping.address_2);
                    $("#shipping_city").val(shipping.city);
                    $("#shipping_pincode").val(shipping.pincode);
                    $("#shipping_country").val(shipping.country);
                }

                $("#shipping_state").empty().append($("<option/>").val(response.state).text(response.state)).val(response.state).trigger("change");

                if (response.so_no != '') {
                    var e_nos = JSON.parse(response.so_no);

                    $('#si_sales_order').empty();
                    for (var i = 0, l = e_nos.length; i < l; i++) {

                        var si_sales_order = e_nos[i];
                        $('#si_sales_order').append($("<option/>") //add option tag in select
                            .val(si_sales_order) //set value for option to post it
                            .text(si_sales_order));
                    }

                    var Values = new Array();
                    for (var i = 0, l = e_nos.length; i < l; i++) {
                        Values.push(e_nos[i]);
                    }
                    $("#si_sales_order").val(Values).trigger('change');
                    if (response.cancelled != '1')
                        $("#si_sales_order").attr('readonly', true);
                }

                if (response.q_no != '' && response.q_no != null) {
                    var e_nos = JSON.parse(response.q_no);

                    $('#si_quotation').empty();
                    for (var i = 0, l = e_nos.length; i < l; i++) {

                        var quotation_no = e_nos[i];
                        $('#si_quotation').append($("<option/>") //add option tag in select
                            .val(quotation_no) //set value for option to post it
                            .text(quotation_no));
                    }

                    var Values = new Array();
                    for (var i = 0, l = e_nos.length; i < l; i++) {
                        Values.push(e_nos[i]);
                    }
                    $("#si_quotation").val(Values).trigger('change');
                    if (response.cancelled != '1')
                        $("#si_quotation").attr('readonly', true);
                }

                if (response.addons != '') {
                    var addons = JSON.parse(response.addons);
                    $("#si_freight").val(addons.freight.value);
                    $("#si_pf").val(addons.pf.value);
                }

                if (response.items != '') {
                    var items = JSON.parse(response.items);
                    var len = items.product.length;

                    $('[data-repeater-list="sales_invoice"]').empty();
                    $('[data-repeater-create="sales_invoice"]').click();

                    for (var i = 1; i < len; i++) {
                        $('#si_btn_add').click();
                    }

                    var tmp = '';
                    for (var i = 0; i < len; i++) {

                        tmp = "input[name$='sales_invoice[" + i + "][si_sn]']";
                        $(tmp).val(i + 1);
                        tmp = "select[name$='sales_invoice[" + i + "][si_product_name]']";
                        $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                        tmp = "textarea[name$='sales_invoice[" + i + "][si_product_description]']";
                        $(tmp).val(items.desc[i]);
                        tmp = "textarea[name$='sales_invoice[" + i + "][si_product_add_description]']";
                        var temp = items.long_desc[i];
                        temp = temp.replace(/\|/g, "\r\n");
                        $(tmp).val(temp);

                        var temp_textarea = $(tmp);
                        autosize(temp_textarea);

                        tmp = "input[name$='sales_invoice[" + i + "][si_qty]']";
                        $(tmp).val(items.quantity[i]);
                        tmp = "select[name$='sales_invoice[" + i + "][si_unit]']";
                        $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                        tmp = "input[name$='sales_invoice[" + i + "][si_rate]']";
                        $(tmp).val(items.price[i]);
                        tmp = "input[name$='sales_invoice[" + i + "][si_dsc]']";
                        $(tmp).val(items.discount[i]);
                        tmp = "input[name$='sales_invoice[" + i + "][si_hsn]']";
                        $(tmp).val(items.hsn[i]);
                        tmp = "select[name$='sales_invoice[" + i + "][si_tax]']";
                        $(tmp).val(items.tax[i]).trigger("change");
                        tmp = "select[name$='sales_invoice[" + i + "][si_display_make]']";
                        $(tmp).val(items.group[i]).trigger("change");
                    }
                }
                // Assuming 'notes' contains the value you want to set in the textarea
                $('#notes').val(response.notes); // Set the value dynamically


                if (response.invoice_details != '') {
                    var invoice_details = JSON.parse(response.invoice_details);
                    $("#buyer_order_no").val(invoice_details.buyer_order);



                    if (invoice_details.order_date != '') {
                        var order_date = new Date(invoice_details.order_date);
                        var formatted_date = appendLeadingZeroes(order_date.getDate()) + "-" + appendLeadingZeroes(order_date.getMonth() + 1) + "-" + order_date.getFullYear();
                        console.log(formatted_date);
                        $("#buyer_order_date").val(formatted_date);
                    }

                    $("#terms_payment").val(invoice_details.payment_terms);
                    $("#terms_delivery").val(invoice_details.delivery_terms);
                    $("#other_ref").val(invoice_details.other_ref);

                    $("#despatch_medium").val(invoice_details.despatch_medium);
                    $("#despatch_doc_no").val(invoice_details.despatch_doc_no);

                    if (invoice_details.despatch_date != '') {
                        var despatch_date = new Date(invoice_details.despatch_date);
                        var formatted_date = appendLeadingZeroes(despatch_date.getDate()) + "-" + appendLeadingZeroes(despatch_date.getMonth() + 1) + "-" + despatch_date.getFullYear();
                        console.log(formatted_date);
                        $("#despatch_date").val(formatted_date);
                    }

                    $("#despatch_destination").val(invoice_details.despatch_destination);
                }
                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function makePrimaryInvoice(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/sales_invoice/getSelectedSI.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_si_id").val(response.id);
                $("#si_state").val(response.state);
                $("#mobile").val(response.mobile);

                $("#si_client").empty().append($("<option/>").val(response.client_name).text(response.client_name)).val(response.client_name).trigger("change");

                // Fetch sales invoice number from get_counter and set the formatted value
                $.ajax({
                    url: '../assets/custom/api_get/get_counter.php',
                    type: 'post',
                    data: { key: 'sales_invoice' },
                    dataType: 'json',
                    success: function (response) {
                        $("#sales_invoice_no").val(response.value);
                    },
                    error: function () {
                        alert('Error fetching sales invoice counter');
                    }
                });

                // Set the current date in DD-MM-YYYY format
                var currentDate = new Date();
                var formattedDate = appendLeadingZeroes(currentDate.getDate()) + "-" +
                    appendLeadingZeroes(currentDate.getMonth() + 1) + "-" +
                    currentDate.getFullYear();
                $("#sales_invoice_date").val(formattedDate).attr('readonly', true); // Set the current date and make it readonly

                // Set the series to "primary" as a fixed value
                $("#si_series").empty().append($("<option/>").val('primary').text('Primary')).val('primary').trigger("change");
                $("#si_series").attr('readonly', true);

                if (response.shipping != '') {
                    var shipping = JSON.parse(response.shipping);
                    $("#shipping_name").val(shipping.name);
                    $("#shipping_add_1").val(shipping.address_1);
                    $("#shipping_add_2").val(shipping.address_2);
                    $("#shipping_city").val(shipping.city);
                    $("#shipping_pincode").val(shipping.pincode);
                    $("#shipping_country").val(shipping.country);
                }

                $("#shipping_state").empty().append($("<option/>").val(response.state).text(response.state)).val(response.state).trigger("change");

                if (response.so_no != '') {
                    var e_nos = JSON.parse(response.so_no);

                    $('#si_sales_order').empty();
                    for (var i = 0, l = e_nos.length; i < l; i++) {
                        var si_sales_order = e_nos[i];
                        $('#si_sales_order').append($("<option/>").val(si_sales_order).text(si_sales_order));
                    }

                    var Values = [];
                    for (var i = 0, l = e_nos.length; i < l; i++) {
                        Values.push(e_nos[i]);
                    }
                    $("#si_sales_order").val(Values).trigger('change');
                    if (response.cancelled != '1')
                        $("#si_sales_order").attr('readonly', true);
                }

                if (response.q_no != '' && response.q_no != null) {
                    var e_nos = JSON.parse(response.q_no);

                    $('#si_quotation').empty();
                    for (var i = 0, l = e_nos.length; i < l; i++) {
                        var quotation_no = e_nos[i];
                        $('#si_quotation').append($("<option/>").val(quotation_no).text(quotation_no));
                    }

                    var Values = [];
                    for (var i = 0, l = e_nos.length; i < l; i++) {
                        Values.push(e_nos[i]);
                    }
                    $("#si_quotation").val(Values).trigger('change');
                    if (response.cancelled != '1')
                        $("#si_quotation").attr('readonly', true);
                }

                if (response.addons != '') {
                    var addons = JSON.parse(response.addons);
                    $("#si_freight").val(addons.freight.value);
                    $("#si_pf").val(addons.pf.value);
                }

                if (response.items != '') {
                    var items = JSON.parse(response.items);
                    var len = items.product.length;

                    $('[data-repeater-list="sales_invoice"]').empty();
                    $('[data-repeater-create="sales_invoice"]').click();

                    for (var i = 1; i < len; i++) {
                        $('#si_btn_add').click();
                    }

                    var tmp = '';
                    for (var i = 0; i < len; i++) {
                        tmp = "input[name$='sales_invoice[" + i + "][si_sn]']";
                        $(tmp).val(i + 1);
                        tmp = "select[name$='sales_invoice[" + i + "][si_product_name]']";
                        $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                        tmp = "textarea[name$='sales_invoice[" + i + "][si_product_description]']";
                        $(tmp).val(items.desc[i]);
                        tmp = "textarea[name$='sales_invoice[" + i + "][si_product_add_description]']";
                        var temp = items.long_desc[i];
                        temp = temp.replace(/\|/g, "\r\n");
                        $(tmp).val(temp);

                        var temp_textarea = $(tmp);
                        autosize(temp_textarea);

                        tmp = "input[name$='sales_invoice[" + i + "][si_qty]']";
                        $(tmp).val(items.quantity[i]);
                        tmp = "select[name$='sales_invoice[" + i + "][si_unit]']";
                        $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                        tmp = "input[name$='sales_invoice[" + i + "][si_rate]']";
                        $(tmp).val(items.price[i]);
                        tmp = "input[name$='sales_invoice[" + i + "][si_dsc]']";
                        $(tmp).val(items.discount[i]);
                        tmp = "input[name$='sales_invoice[" + i + "][si_hsn]']";
                        $(tmp).val(items.hsn[i]);
                        tmp = "select[name$='sales_invoice[" + i + "][si_tax]']";
                        $(tmp).val(items.tax[i]).trigger("change");
                        tmp = "select[name$='sales_invoice[" + i + "][si_display_make]']";
                        $(tmp).val(items.group[i]).trigger("change");
                    }
                }
                $('#notes').val(response.notes);

                if (response.invoice_details != '') {
                    var invoice_details = JSON.parse(response.invoice_details);
                    $("#buyer_order_no").val(invoice_details.buyer_order);

                    if (invoice_details.order_date != '') {
                        var order_date = new Date(invoice_details.order_date);
                        var formatted_date = appendLeadingZeroes(order_date.getDate()) + "-" + appendLeadingZeroes(order_date.getMonth() + 1) + "-" + order_date.getFullYear();
                        console.log(formatted_date);
                        $("#buyer_order_date").val(formatted_date);
                    }

                    $("#terms_payment").val(invoice_details.payment_terms);
                    $("#terms_delivery").val(invoice_details.delivery_terms);
                    $("#other_ref").val(invoice_details.other_ref);

                    $("#despatch_medium").val(invoice_details.despatch_medium);
                    $("#despatch_doc_no").val(invoice_details.despatch_doc_no);

                    if (invoice_details.despatch_date != '') {
                        var despatch_date = new Date(invoice_details.despatch_date);
                        var formatted_date = appendLeadingZeroes(despatch_date.getDate()) + "-" + appendLeadingZeroes(despatch_date.getMonth() + 1) + "-" + despatch_date.getFullYear();
                        console.log(formatted_date);
                        $("#despatch_date").val(formatted_date);
                    }

                    $("#despatch_destination").val(invoice_details.despatch_destination);
                }
                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}


function printSalesInvoice(id) {
    console.log(id);
    $("#id").val(id);
}

function pay(id) {
    if (id) {
        // Perform an AJAX call to fetch the necessary invoice data
        $.ajax({
            url: '../assets/custom/sales_invoice/getSelectedSI.php',  // Backend URL to fetch invoice details
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                // Extract the required details from the response
                var clientName = response.client_name;
                var seriesType = response.series;
                var totalAmount = response.total;  // Make sure 'total_amount' is part of the response

                // Redirect to the receipt page with these details as URL parameters
                window.location.href = `https://www.easthyde.com/_admin/?page=receipt&client_name=${encodeURIComponent(clientName)}&series=${encodeURIComponent(seriesType)}&total=${totalAmount}`;
            },
            error: function () {
                alert('Error: Could not fetch invoice details.');
            }
        });
    } else {
        alert('Error: No ID provided.');
    }
}


function toggleHSN(id = true) {
    if (id) {
        $('#toggle_hsn_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/sales_invoice/hsn.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Sales invoice updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#toggle_sales_hsn").modal('hide');
                    // manageSalesInvoiceTable.reload();
                    // set_sales_invoice_no();

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function updateAWB(id = true) {
    if (id) {
        $.ajax({
            url: '../assets/custom/sales_invoice/getSelectedSI.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                var invoice_details = JSON.parse(response.invoice_details);
                $("#si_awb").val(invoice_details.despatch_doc_no);
            }
        });


        $('#update_awb_submit').unbind('click').bind('click', function () {

            var awb = $('#si_awb').val();
            // console.log(awb);

            $.ajax({
                url: '../assets/custom/sales_invoice/awb.php',
                type: 'post',
                data: { member_id: id, awb_no: awb },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Sales invoice updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#update_awb_sales").modal('hide');
                    $('#si_awb').val("");

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeSalesInvoice(id = true) {
    if (id) {
        $('#delete_sales_invoice_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/sales_invoice/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Sales invoice cancelled successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#delete_sales_invoice").modal('hide');
                    if (param_page == 'sales') {
                        manageSalesInvoiceTable.reload();
                    }

                    if (param_page == 'secondary_sales') {
                        manageSalesSecondaryTable.reload();
                    }
                    var data = $('#si_series').val();
                    set_sales_invoice_no(data);

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function cancelSalesInvoice(id = true) {
    if (id) {
        $('#cancel_sales_invoice_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/sales_invoice/cancel.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Sales invoice cancelled successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There was some error saving the record!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#cancel_sales_invoice").modal('hide');
                    manageSalesInvoiceTable.reload();
                    // set_sales_invoice_no();

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function sendSIEmail(id) {
    if (id) {
        $("#si_em_id").val(id);
        $.ajax({
            url: '../assets/custom/sales_invoice/email_message.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#si_em_email").val(response.email);
                    $("#si_em_subject").val(response.subject);
                    // $("#q_em_message").innerHTML(response.em_message);
                    $('#si_em_message').summernote('code', response.em_message);
                    $('#si_em_email_bcc').val('sentmail@easthyde.com');
                }

            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function viewAssembly(id) {
    if (id) {
        manageSalesAssembliesOperationTable.search(id, 'invoice');
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Receipts- *****************************************************

var receipt_id;

var Receipts = function () {

    var handleAddReceipts = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/receipts/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your receipt has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            url: "../assets/custom/api_set/set_sales_receipt.php",
                            type: "POST",
                            data: { r_no: response.r_no },
                            dataType: 'json',
                            success: function (response) { }
                        });
                        //Reset The Form
                        $('#add_receipt')[0].reset();
                        // close the modal
                        manageReceiptsTable.reload();
                        $('#rc_client').val(null).trigger('change');
                        $('#rc_bank').val(null).trigger('change');
                        $('#rc_mode').val(null).trigger('change');
                        $('[data-repeater-list="receipt"]').empty();
                        $('[data-repeater-create="receipt"]').click();
                        var tmp = "input[name$='receipt[0][rc_sn]']";
                        $(tmp).val(1);

                        document.getElementById("bank_details").style.display = "none";
                        document.getElementById("bank_details_title").style.display = "none";

                        document.getElementById("invoice_details").style.display = "none";
                        document.getElementById("invoice_details_title").style.display = "none";

                    } else if (response.success == 'mismatch') {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'The totals do not tally.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }


                }
            });

            return false;
        }

        $('#add_receipt').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                rc_client: {
                    required: true
                },
                rc_bank: {
                    required: true
                }
            },
            messages: {
                rc_client: {
                    required: 'This field is required!'
                },
                rc_bank: {
                    required: 'Please select the account to credit.'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_receipt input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_receipt').validate().form()) {
                    ajaxAdd($('#add_receipt')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddReceipts();
        }
    };
}();

function editReceipts(id) {

    if (id) {
        $.ajax({
            url: '../assets/custom/receipts/getSelectedReceipt.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#rc_id").val(response.id);
                $("#r_no").val(response.r_no);
                $("#rc_client").empty().append($("<option/>").val(response.client).text(response.client)).val(response.client).trigger("change");
                var receipts_date = new Date(response.date);
                var formatted_date = appendLeadingZeroes(receipts_date.getDate()) + "-" + appendLeadingZeroes(receipts_date.getMonth() + 1) + "-" + receipts_date.getFullYear();
                // console.log(formatted_date);
                $("#rc_date").val(formatted_date);
                $("#rc_bank").empty().append($("<option/>").val(response.account).text(response.account)).val(response.account).trigger("change");

                $("#rc_client").attr("readonly", true);

                var sales_invoice = JSON.parse(response.sales_invoice);
                var len = sales_invoice.si_no.length;

                $('[data-repeater-list="receipt"]').empty();
                $('[data-repeater-create="receipt"]').click();

                for (var i = 1; i < len; i++) {
                    $('#rc_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='receipt[" + i + "][rc_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "input[name$='receipt[" + i + "][rc_si_no]']";
                    $(tmp).val(sales_invoice.si_no[i]);
                    tmp = "input[name$='receipt[" + i + "][rc_si_amount]']";
                    $(tmp).val(sales_invoice.due[i]);
                    tmp = "input[name$='receipt[" + i + "][rc_si_amount_due]']";
                    $(tmp).val(sales_invoice.due[i]);
                    tmp = "input[name$='receipt[" + i + "][rc_amount]']";
                    $(tmp).val(sales_invoice.amount[i]);



                    KTUtil.scrollTop();
                }

                $("#rc_mode").val(response.mode).trigger("change");
                $("#rc_bank_name").val(response.bank_name);
                $("#rc_cheque").val(response.cheque);
                $("#rc_ifsc").val(response.ifsc);


            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeReceipts(id = true) {
    if (id) {
        //click remove button
        $('#delete_receipt_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/receipts/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        deleteReceiptsToast(response.messages);
                    } else {
                        deleteReceiptsToastError(response.messages);
                    }

                    // close the modal
                    $("#kt_modal_d_receipt").modal('hide');
                    manageReceiptsTable.reload();
                }
            });
        });
        // click remove button
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Bank- *****************************************************

var Bank = function () {

    var handleAddBank = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/banks/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Bank has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_bank')[0].reset();
                    // close the modal
                    manageBankTable.reload();
                }
            });

            return false;
        }

        $('#add_bank').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                bank_name: {
                    required: true
                },
            },
            messages: {
                bank_name: {
                    required: 'This field is required'
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_bank input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_bank').validate().form()) {
                    ajaxAdd($('#add_bank')); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddBank();
        }
    };
}();

function editBank(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/banks/getSelectedBank.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_bank_id").val(response.id);
                $("#account_name").val(response.account_name);
                $("#bank_name").val(response.bank_name);
                $("#account_number").val(response.account_number);
                $("#ifsc").val(response.ifsc);
                $("#opening_balance").val(response.opening_balance);
                $("#date").val(response.updated_on);
                KTUtil.scrollTop();

            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeBank(id = true) {
    if (id) {
        $('#delete_bank_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/banks/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Bank has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_bank").modal('hide');
                    manageBankTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}


//***************************************************** -Materials Received- *****************************************************

var Materials_Received = function () {

    var handleAddMaterialsReceived = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/materials_received/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Your entry has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_materials_received')[0].reset();
                    // close the modal
                    manageMaterialsReceivedTable.reload();
                    $('#mr_supplier_name').val(null).trigger('change');

                    $('[data-repeater-list="materials_received"]').empty();
                    $('[data-repeater-create="materials_received"]').click();
                    var tmp = "input[name$='materials_received[0][mr_sn]']";
                    $(tmp).val(1);
                }
            });

            return false;
        }

        $('#add_materials_received').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                e_client: {
                    required: true
                },
                enquiry_no: {
                    required: true
                },
                enquiry_date: {
                    required: true
                },
            },
            messages: {
                e_client: {
                    required: 'This field is required'
                },
                enquiry_no: {
                    required: 'This field is required'
                },
                enquiry_date: {
                    required: 'This field is required'
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_materials_received input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_materials_received').validate().form()) {
                    ajaxAdd($('#add_materials_received')); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddMaterialsReceived();
        }
    };
}();

function editMaterialsReceived(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/materials_received/getSelectedMaterialsReceived.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#mr_edit_id").val(response.id);
                $("#mr_supplier_name").empty().append($("<option/>").val(response.supplier_name).text(response.supplier_name)).val(response.supplier_name).trigger("change");

                let date = new Date(response.date);
                let formatted_date = appendLeadingZeroes(date.getDate()) + "-" + appendLeadingZeroes(date.getMonth() + 1) + "-" + date.getFullYear();
                console.log(formatted_date);

                $("#mr_date").val(formatted_date);

                var items = JSON.parse(response.items);
                var len = items.product.length;

                $('[data-repeater-list="materials_received"]').empty();
                $('[data-repeater-create="materials_received"]').click();

                for (var i = 1; i < len; i++) {
                    $('#mr_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='materials_received[" + i + "][mr_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "select[name$='materials_received[" + i + "][mr_product_name]']";
                    $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                    tmp = "input[name$='materials_received[" + i + "][mr_desc]']";
                    $(tmp).val(items.desc[i]);

                    tmp = "input[name$='materials_received[" + i + "][mr_qty]']";
                    $(tmp).val(items.quantity[i]);
                    tmp = "input[name$='materials_received[" + i + "][mr_unit]']";
                    $(tmp).val(items.unit[i]);
                    tmp = "input[name$='materials_received[" + i + "][mr_rate]']";
                    $(tmp).val(items.rate && items.rate[i] ? items.rate[i] : '');
                }
                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeMaterialsReceived(id = true) {
    if (id) {
        $('#delete_materials_received_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/materials_received/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Entry has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_materials_received").modal('hide');
                    manageMaterialsReceivedTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Whatsapp- *****************************************************
var Whatsapp = function () {

    var client = $('#dcs_add_client');

    var handleWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/send_message.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_whatsapp')[0].reset();
                    $("#whatsapp_submit").attr("disabled", false);
                    // $("#remove_file_whatsapp")[0].click();
                    location.reload();

                    // close the modal
                }
            });

            return false;
        }

        $('#send_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_whatsapp').validate().form()) {
                    ajaxAdd($('#send_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleSalesInvoiceWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/sales_invoice.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_sales_invoice_whatsapp')[0].reset();
                    $("#si_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_sales_invoice_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_sales_invoice_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#si_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_sales_invoice_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_sales_invoice_whatsapp').validate().form()) {
                    ajaxAdd($('#send_sales_invoice_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleSalesOrderWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/sales_order.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_sales_order_whatsapp')[0].reset();
                    $("#so_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_sales_order_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_sales_order_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#so_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_sales_order_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_sales_order_whatsapp').validate().form()) {
                    ajaxAdd($('#send_sales_order_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleProformaInvoiceWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/proforma_invoice.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_proforma_whatsapp')[0].reset();
                    $("#pr_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_proforma_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_proforma_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#pr_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_proforma_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_proforma_whatsapp').validate().form()) {
                    ajaxAdd($('#send_proforma_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleQuotationWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/quotation.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_quotation_whatsapp')[0].reset();
                    $("#q_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_quotation_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_quotation_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#q_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_quotation_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_quotation_whatsapp').validate().form()) {
                    ajaxAdd($('#send_quotation_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handlePurchaseInvoiceWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/purchase_invoice.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_purchase_invoice_whatsapp')[0].reset();
                    $("#pi_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_purchase_invoice_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_purchase_invoice_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#pi_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_purchase_invoice_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_purchase_invoice_whatsapp').validate().form()) {
                    ajaxAdd($('#send_purchase_invoice_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handlePurchaseOrderWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/purchase_order.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_purchase_order_whatsapp')[0].reset();
                    $("#po_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_purchase_order_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_purchase_order_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#po_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_purchase_order_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_purchase_order_whatsapp').validate().form()) {
                    ajaxAdd($('#send_purchase_order_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleClientLedgerWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/client_ledger.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_client_ledger_whatsapp')[0].reset();
                    $("#cl_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_client_ledger_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_client_ledger_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#cl_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_client_ledger_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_client_ledger_whatsapp').validate().form()) {
                    ajaxAdd($('#send_client_ledger_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleSupplierLedgerWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/supplier_ledger.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_supplier_ledger_whatsapp')[0].reset();
                    $("#sl_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_supplier_ledger_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_supplier_ledger_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#sl_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_supplier_ledger_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_supplier_ledger_whatsapp').validate().form()) {
                    ajaxAdd($('#send_supplier_ledger_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleProductDetailsWhatsapp = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/product_details.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#send_product_details_whatsapp')[0].reset();
                    $("#pd_whatsapp_submit").attr("disabled", false);

                    // close the modal
                    $("#kt_modal_product_details_whatsapp").modal('hide');
                }
            });

            return false;
        }

        $('#send_product_details_whatsapp').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#pd_whatsapp_submit").attr("disabled", true);
            }
        });

        $('#send_product_details_whatsapp input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_product_details_whatsapp').validate().form()) {
                    ajaxAdd($('#send_product_details_whatsapp')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleWhatsappMessage = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/whatsapp/whatsapp_message.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    // if (response.success == true) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Whatsapp message sent!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // } else {
                    //     swal.fire({
                    //         position: 'top-right',
                    //         type: 'error',
                    //         title: 'There were some errors in your submission.',
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    // }

                    //Reset The Form
                    $('#form_whatsapp_message')[0].reset();
                    $("#whatsapp_message_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#form_whatsapp_message').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#dcs_add_client_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#whatsapp_message_submit").attr("disabled", true);
            }
        });

        $('#form_whatsapp_message input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#form_whatsapp_message').validate().form()) {
                    ajaxAdd($('#form_whatsapp_message')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleWhatsapp();
            handleSalesInvoiceWhatsapp();
            handleSalesOrderWhatsapp();
            handleQuotationWhatsapp();
            handlePurchaseInvoiceWhatsapp();
            handlePurchaseOrderWhatsapp();
            handleClientLedgerWhatsapp();
            handleSupplierLedgerWhatsapp();
            handleProformaInvoiceWhatsapp();
            handleProductDetailsWhatsapp();
            handleWhatsappMessage();
        }
    };
}();

function Wa_client_ledger(id) {
    if (id) {

        $.ajax({
            url: '../assets/custom/client_ledger_save.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') { }
            }
        });

        $("#cl_no_whatsapp").val(id);

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_supplier_ledger(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/supplier_ledger_save.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') { }
            }
        });

        $("#sl_no_whatsapp").val(id);

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_payment_followup(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/payment_followup.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#whatsapp_message").val(response.message);
                    var temp_textarea = $("#whatsapp_message");
                    autosize(temp_textarea);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_quotation(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/quotation.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#q_no_whatsapp").val(response.q_no);
                    $("#q_whatsapp_number").val(response.mobile);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_sales_invoice(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/sales_invoice.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#si_no_whatsapp").val(response.si_no);
                    $("#si_whatsapp_number").val(response.mobile);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_purchase_invoice(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/purchase_invoice.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#pi_no_whatsapp").val(response.pi_no);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_sales_order(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/sales_order.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#so_no_whatsapp").val(response.so_no);
                    $("#so_whatsapp_number").val(response.mobile);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_purchase_order(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/purchase_order.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#po_no_whatsapp").val(response.po_no);
                    $("#po_whatsapp_number").val(response.mobile);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_product_details(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/product_details.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#pd_whatsapp_id").val(id);

                    $("#pd_whatsapp_message").val(response.message);
                    var temp_textarea = $("#pd_whatsapp_message");
                    autosize(temp_textarea);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_receipt(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/receipt.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#whatsapp_message").val(response.message);
                    var temp_textarea = $("#whatsapp_message");
                    autosize(temp_textarea);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_payment(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/payment.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#whatsapp_message").val(response.message);
                    var temp_textarea = $("#whatsapp_message");
                    autosize(temp_textarea);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_despatch_details(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/despatch_details.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#whatsapp_message").val(response.message);
                    var temp_textarea = $("#whatsapp_message");
                    autosize(temp_textarea);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function Wa_proforma_invoice(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/wa_messages/proforma_invoice.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#pr_no_whatsapp").val(response.pr_no);
                }
            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Proforma Invoice- *****************************************************

var Proforma_Invoice = function () {

    var handleAddProformaInvoice = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/proforma_invoice/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Proforma Invoice has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $.ajax({
                            type: "POST",
                            url: "../assets/custom/pdf_master/_proforma_invoice.php",
                            data: { id: response.so },
                            dataType: 'json',
                            success: function (response) {
                                console.log("saved");
                            }
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_proforma_invoice')[0].reset();
                    // close the modal
                    manageProformaInvoiceTable.reload();
                    set_proforma_invoice_no();
                    $('#pr_client').val(null).trigger('change');
                    $('#pr_sales_order').val(null).trigger('change');
                    $('[data-repeater-list="proforma_invoice"]').empty();
                    $('[data-repeater-create="proforma_invoice"]').click();
                    var tmp = "input[name$='proforma_invoice[0][pi_sn]']";
                    $(tmp).val(1);
                    $("#proforma_invoice_submit").attr("disabled", false);
                }
            });

            return false;
        }

        $('#add_proforma_invoice').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                pi_client: {
                    required: true
                },
                pi_no: {
                    required: true
                },
                pi_date: {
                    required: true
                },
            },
            messages: {
                pi_client: {
                    required: 'This field is required!'
                },
                pi_no: {
                    required: 'This field is required!'
                },
                pi_date: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
                $("#proforma_invoice_submit").attr("disabled", true);
            }
        });

        $('#add_proforma_invoice input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_proforma_invoice').validate().form()) {
                    ajaxAdd($('#add_proforma_invoice')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    var handleSendPIEmail = function () {

        var ajaxAdd = function (form) {
            // $('.summernote').each( function() {
            //     $(this).val($(this).code());
            // });
            form = $(form);

            $.ajax({
                type: "POST",
                url: "../assets/custom/proforma_invoice_email.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Email Sent!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in sending the email.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#send_pr_email')[0].reset();
                    $("#kt_modal_pr_email").modal('hide');
                }
            });

            return false;
        }

        $('#send_pr_email').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#send_pr_email input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#send_pr_email').validate().form()) {
                    ajaxAdd($('#send_pr_email')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    var handleAddNoteProforma = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/proforma_invoice/create_note.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Note added successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $('#add_pr_note')[0].reset();
                    $("#kt_modal_pr_note").modal('hide');
                }
            });

            return false;
        }

        $('#add_pr_note').validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {},
            messages: {},

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_pr_note input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_pr_note').validate().form()) {
                    ajaxAdd($('#add_pr_note')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddProformaInvoice();
            handleSendPIEmail();
            handleAddNoteProforma();
        }
    };
}();

function set_proforma_invoice_no() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'proforma' },
        dataType: 'json',
        success: function (response) {
            $("#pr_no").val(response.value);
        }
    });
}

function editProformaInvoice(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/proforma_invoice/getSelectedProforma.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_pr_id").val(response.id);
                $("#pr_state").val(response.state);

                $("#pr_client").empty().append($("<option/>").val(response.client_name).text(response.client_name)).val(response.client_name).trigger("change");
                $("#pr_client").attr('readonly', true);
                $('#client_so_no').val(response.client_so_no);
                var e_nos = JSON.parse(response.so_no);
                $("#pr_collected").val(response.collected).trigger("change");

                $('#pr_sales_order').empty();
                for (var i = 0, l = e_nos.length; i < l; i++) {

                    var sales_order = e_nos[i];
                    $('#pr_sales_order').append($("<option/>") //add option tag in select
                        .val(sales_order) //set value for option to post it
                        .text(sales_order));
                }

                var Values = new Array();
                for (var i = 0, l = e_nos.length; i < l; i++) {
                    Values.push(e_nos[i]);
                }
                $("#pr_sales_order").val(Values).trigger('change');
                $("#pr_sales_order").attr('readonly', true);

                $("#pr_no").val(response.pr_no);

                var pr_date = new Date(response.pr_date);
                var formatted_date = appendLeadingZeroes(pr_date.getDate()) + "-" + appendLeadingZeroes(pr_date.getMonth() + 1) + "-" + pr_date.getFullYear();
                console.log(formatted_date);
                $("#pr_date").val(formatted_date);

                var addons = JSON.parse(response.addons);
                $("#pr_freight").val(addons.freight.value);
                $("#pr_pf").val(addons.pf.value);

                var items = JSON.parse(response.items);
                var len = items.product.length;

                $('[data-repeater-list="proforma_invoice"]').empty();
                $('[data-repeater-create="proforma_invoice"]').click();

                for (var i = 1; i < len; i++) {
                    $('#pr_btn_add').click();
                }

                var tmp = '';
                for (var i = 0; i < len; i++) {

                    tmp = "input[name$='proforma_invoice[" + i + "][pr_sn]']";
                    $(tmp).val(i + 1);
                    tmp = "select[name$='proforma_invoice[" + i + "][pr_product_name]']";
                    $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_product_description]']";
                    $(tmp).val(items.desc[i]);
                    tmp = "textarea[name$='proforma_invoice[" + i + "][pr_product_description]']";
                    var temp = items.long_desc[i];
                    temp = temp.replace(/\|/g, "\r\n");
                    $(tmp).val(temp);

                    var temp_textarea = $(tmp);
                    autosize(temp_textarea);

                    tmp = "input[name$='proforma_invoice[" + i + "][pr_qty]']";
                    $(tmp).val(items.quantity[i]);
                    tmp = "select[name$='proforma_invoice[" + i + "][pr_unit]']";
                    $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_rate]']";
                    $(tmp).val(items.price[i]);
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_dsc]']";
                    $(tmp).val(items.discount[i]);
                    tmp = "input[name$='proforma_invoice[" + i + "][pr_hsn]']";
                    $(tmp).val(items.hsn[i]);
                    tmp = "select[name$='proforma_invoice[" + i + "][pr_tax]']";
                    $(tmp).val(items.tax[i]).trigger("change");
                    tmp = "select[name$='proforma_invoice[" + i + "][pr_display_make]']";
                    $(tmp).val(items.group[i]).trigger("change");
                }
                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeProformaInvoice(id = true) {
    if (id) {
        $('#delete_proforma_invoice_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/proforma_invoice/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Proforma Invoice deleted successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#delete_proforma_invoice").modal('hide');
                    manageProformaInvoiceTable.reload();
                    set_proforma_invoice_no();

                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

function sendPIEmail(id) {
    if (id) {
        $("#pr_em_id").val(id);
        $.ajax({
            url: '../assets/custom/proforma_invoice/email_message.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status == '200') {
                    $("#pr_em_email").val(response.email);
                    $("#pr_em_subject").val(response.subject);
                    // $("#q_em_message").innerHTML(response.em_message);
                    $('#pr_em_message').summernote('code', response.em_message);
                    $('#pr_em_email_bcc').val('sentmail@easthyde.com');
                }

            }
        });

    } else {
        alert('Error : Please refresh the page');
    }
}

function addNoteProforma(id) {
    if (id) {
        $("#an_pr_no").val(id);
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeNoteProforma(id, index) {
    console.log(id);

    if (id) {
        $('#delete_proforma_note_submit').unbind('click').bind('click', function () {
            $.ajax({
                url: '../assets/custom/proforma_invoice/delete_note.php',
                type: 'post',
                data: { member_id: id, index: index },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Note has been deleted Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_pr_note").modal('hide');
                    location.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Credit Note- *****************************************************


var Credit_Note = function () {

    var handleAddCreditNote = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/credit_note/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Credit Note has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        //Reset The Form
                        $('#add_credit_note')[0].reset();
                        // close the modal
                        manageCreditNoteTable.reload();
                        $('#cn_client').val(null).trigger('change');

                        $('[data-repeater-list="credit_note"]').empty();
                        $('[data-repeater-create="credit_note"]').click();
                        var tmp = "input[name$='credit_note[0][cn_sn]']";
                        $(tmp).val(1);
                        set_credit_note_no();

                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });

            return false;
        }

        $('#add_credit_note').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                cn_client: {
                    required: true
                }
            },
            messages: {
                cn_client: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_credit_note input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_credit_note').validate().form()) {
                    ajaxAdd($('#add_credit_note')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddCreditNote();
        }
    };
}();

function removeCreditNote(id = true) {
    if (id) {
        //click remove button
        $('#delete_credit_note_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/credit_note/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Credit Note deleted successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    // close the modal
                    $("#delete_credit_note").modal('hide');
                    manageCreditNoteTable.reload();
                    set_credit_note_no();
                }
            });
        });
        // click remove button
    } else {
        alert('Error : Please refresh the page');
    }
}

function set_credit_note_no() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'credit_note' },
        dataType: 'json',
        success: function (response) {
            $("#cn_cn_no").val(response.value);
        }
    });
}

function editCreditNote(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/credit_note/getSelectedCreditNote.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_cn_id").val(response.id);
                $("#cn_state").val(response.state);
                $("#cn_client").empty().append($("<option/>").val(response.client).text(response.client)).val(response.client).trigger("change");
                $("#cn_si_no").val(response.sales_invoice);
                $("#cn_cn_no").val(response.cn_no);

                if (response.cn_date != '') {
                    var cn_date = new Date(response.cn_date);
                    var formatted_date = appendLeadingZeroes(cn_date.getDate()) + "-" + appendLeadingZeroes(cn_date.getMonth() + 1) + "-" + cn_date.getFullYear();
                    console.log(formatted_date);
                    $("#cn_date").val(formatted_date);
                }

                if (response.addons != '') {
                    var addons = JSON.parse(response.addons);
                    $("#cn_freight").val(addons.freight.value);
                    $("#cn_pf").val(addons.pf.value);
                }

                if (response.items != '') {
                    var items = JSON.parse(response.items);
                    var len = items.product.length;

                    $('[data-repeater-list="credit_note"]').empty();
                    $('[data-repeater-create="credit_note"]').click();

                    for (var i = 1; i < len; i++) {
                        $('#cn_btn_add').click();
                    }

                    var tmp = '';
                    for (var i = 0; i < len; i++) {

                        tmp = "input[name$='credit_note[" + i + "][cn_sn]']";
                        $(tmp).val(i + 1);
                        tmp = "select[name$='credit_note[" + i + "][cn_product_name]']";
                        $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                        tmp = "input[name$='credit_note[" + i + "][cn_product_description]']";
                        $(tmp).val(items.desc[i]);
                        tmp = "textarea[name$='credit_note[" + i + "][cn_product_add_description]']";
                        var temp = items.long_desc[i];
                        temp = temp.replace(/\|/g, "\r\n");
                        $(tmp).val(temp);

                        var temp_textarea = $(tmp);
                        autosize(temp_textarea);

                        tmp = "input[name$='credit_note[" + i + "][cn_qty]']";
                        $(tmp).val(items.quantity[i]);
                        tmp = "select[name$='credit_note[" + i + "][cn_unit]']";
                        $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                        tmp = "input[name$='credit_note[" + i + "][cn_rate]']";
                        $(tmp).val(items.price[i]);
                        tmp = "input[name$='credit_note[" + i + "][cn_dsc]']";
                        $(tmp).val(items.discount[i]);
                        tmp = "input[name$='credit_note[" + i + "][cn_hsn]']";
                        $(tmp).val(items.hsn[i]);
                        tmp = "select[name$='credit_note[" + i + "][cn_tax]']";
                        $(tmp).val(items.tax[i]).trigger("change");
                        tmp = "select[name$='credit_note[" + i + "][cn_display_make]']";
                        $(tmp).val(items.group[i]).trigger("change");
                    }
                }

                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}


//***************************************************** -Debit Note- *****************************************************


var Debit_Note = function () {

    var handleAddDebitNote = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/debit_note/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Debit Note has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        //Reset The Form
                        $('#add_debit_note')[0].reset();
                        // close the modal
                        manageDebitNoteTable.reload();
                        $('#dn_supplier').val(null).trigger('change');

                        $('[data-repeater-list="debit_note"]').empty();
                        $('[data-repeater-create="debit_note"]').click();
                        var tmp = "input[name$='debit_note[0][dn_sn]']";
                        $(tmp).val(1);
                        set_debit_note_no();

                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });

            return false;
        }

        $('#add_debit_note').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                dn_supplier: {
                    required: true
                }
            },
            messages: {
                dn_supplier: {
                    required: 'This field is required!'
                }
            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_debit_note input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_debit_note').validate().form()) {
                    ajaxAdd($('#add_debit_note')); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddDebitNote();
        }
    };
}();

function removeDebitNote(id = true) {
    if (id) {
        //click remove button
        $('#delete_debit_note_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/debit_note/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Debit Note deleted successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    // close the modal
                    $("#delete_debit_note").modal('hide');
                    manageDebitNoteTable.reload();
                    set_debit_note_no();
                }
            });
        });
        // click remove button
    } else {
        alert('Error : Please refresh the page');
    }
}

function set_debit_note_no() {
    $.ajax({
        url: '../assets/custom/api_get/get_counter.php',
        type: 'post',
        data: { key: 'debit_note' },
        dataType: 'json',
        success: function (response) {
            $("#dn_dn_no").val(response.value);
        }
    });
}

function editDebitNote(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/debit_note/getSelectedDebitNote.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#edit_dn_id").val(response.id);
                $("#dn_state").val(response.state);
                $("#dn_supplier").empty().append($("<option/>").val(response.supplier).text(response.supplier)).val(response.supplier).trigger("change");
                $("#dn_pi_no").val(response.purchase_invoice);
                $("#dn_dn_no").val(response.dn_no);

                if (response.dn_date != '') {
                    var dn_date = new Date(response.dn_date);
                    var formatted_date = appendLeadingZeroes(dn_date.getDate()) + "-" + appendLeadingZeroes(dn_date.getMonth() + 1) + "-" + dn_date.getFullYear();
                    console.log(formatted_date);
                    $("#dn_date").val(formatted_date);
                }

                if (response.dn_pi_date != '') {
                    var dn_pi_date = new Date(response.dn_pi_date);
                    var formatted_date = appendLeadingZeroes(dn_pi_date.getDate()) + "-" + appendLeadingZeroes(dn_pi_date.getMonth() + 1) + "-" + dn_pi_date.getFullYear();
                    console.log(formatted_date);
                    $("#dn_pi_date").val(formatted_date);
                }

                if (response.addons != '') {
                    var addons = JSON.parse(response.addons);
                    $("#dn_freight").val(addons.freight.value);
                    $("#dn_pf").val(addons.pf.value);
                }

                if (response.items != '') {
                    var items = JSON.parse(response.items);
                    var len = items.product.length;

                    $('[data-repeater-list="debit_note"]').empty();
                    $('[data-repeater-create="debit_note"]').click();

                    for (var i = 1; i < len; i++) {
                        $('#dn_btn_add').click();
                    }

                    var tmp = '';
                    for (var i = 0; i < len; i++) {

                        tmp = "input[name$='debit_note[" + i + "][dn_sn]']";
                        $(tmp).val(i + 1);
                        tmp = "select[name$='debit_note[" + i + "][dn_product_name]']";
                        $(tmp).empty().append($("<option/>").val(items.product[i]).text(items.product[i])).val(items.product[i]).trigger("change");
                        tmp = "input[name$='debit_note[" + i + "][dn_product_description]']";
                        $(tmp).val(items.desc[i]);
                        tmp = "textarea[name$='debit_note[" + i + "][dn_product_add_description]']";
                        var temp = items.long_desc[i];
                        temp = temp.replace(/\|/g, "\r\n");
                        $(tmp).val(temp);

                        var temp_textarea = $(tmp);
                        autosize(temp_textarea);

                        tmp = "input[name$='debit_note[" + i + "][dn_qty]']";
                        $(tmp).val(items.quantity[i]);
                        tmp = "select[name$='debit_note[" + i + "][dn_unit]']";
                        $(tmp).empty().append($("<option/>").val(items.unit[i]).text(items.unit[i])).val(items.unit[i]).trigger("change");
                        tmp = "input[name$='debit_note[" + i + "][dn_rate]']";
                        $(tmp).val(items.price[i]);
                        tmp = "input[name$='debit_note[" + i + "][dn_dsc]']";
                        $(tmp).val(items.discount[i]);
                        tmp = "input[name$='debit_note[" + i + "][dn_hsn]']";
                        $(tmp).val(items.hsn[i]);
                        tmp = "select[name$='debit_note[" + i + "][dn_tax]']";
                        $(tmp).val(items.tax[i]).trigger("change");
                        tmp = "select[name$='debit_note[" + i + "][dn_display_make]']";
                        $(tmp).val(items.group[i]).trigger("change");
                    }
                }

                KTUtil.scrollTop();
            }
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Contra- *****************************************************

var Contra = function () {

    var handleAddContra = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/contra/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Contra entry has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_contra')[0].reset();
                    $('#contra_bank_1').val(null).trigger('change');
                    $('#contra_bank_2').val(null).trigger('change');
                    // close the modal
                    manageContraTable.reload();
                }
            });

            return false;
        }

        $('#add_contra').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_contra input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_contra').validate().form()) {
                    ajaxAdd($('#add_contra')); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddContra();
        }
    };
}();

function editContra(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/contra/getSelectedContra.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#contra_edit_id").val(response.id);
                $("#contra_amount").val(response.amount);

                $("#contra_bank_1").empty().append($("<option/>").val(response.transfer_from).text(response.transfer_from)).val(response.transfer_from).trigger("change");
                $("#contra_bank_2").empty().append($("<option/>").val(response.transfer_to).text(response.transfer_to)).val(response.transfer_to).trigger("change");

                var date = new Date(response.date);
                var formatted_date = appendLeadingZeroes(date.getDate()) + "-" + appendLeadingZeroes(date.getMonth() + 1) + "-" + date.getFullYear();
                // console.log(formatted_date);
                $("#contra_date").val(formatted_date);

                KTUtil.scrollTop();

            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeContra(id = true) {
    if (id) {
        $('#delete_contra_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/contra/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Contra entry has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_contra").modal('hide');
                    manageContraTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}

//***************************************************** -Journal- *****************************************************

var Journal = function () {

    var handleAddJournal = function () {

        var ajaxAdd = function (form) {
            form = $(form);
            $.ajax({
                type: "POST",
                url: "../assets/custom/journal/create.php",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Journal entry has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    //Reset The Form
                    $('#add_journal')[0].reset();
                    $('[data-repeater-list="journal"]').empty();
                    $('[data-repeater-create="journal"]').click();
                    var tmp = "input[name$='journal[0][journal_sn]']";
                    $(tmp).val(1);

                    // close the modal
                    manageJournalTable.reload();
                }
            });

            return false;
        }

        $('#add_journal').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) {
                var alert = $('#add_product_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
            },

            errorPlacement: function (error, element) {
                var group = element.closest('.kt-input-icon');
                if (group.length) {
                    group.after(error.addClass('invalid-feedback'));
                } else {
                    element.after(error.addClass('invalid-feedback'));
                }
            },

            submitHandler: function (form) {
                ajaxAdd(form);
            }
        });

        $('#add_journal input').keypress(function (e) {
            $('.alert').hide();
            $('.alert span').html("");
            if (e.which == 13) {
                if ($('#add_journal').validate().form()) {
                    ajaxAdd($('#add_journal')); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleAddJournal();
        }
    };
}();

function editJournal(id) {
    if (id) {
        $.ajax({
            url: '../assets/custom/journal/getSelectedJournal.php',
            type: 'post',
            data: { member_id: id },
            dataType: 'json',
            success: function (response) {
                $("#journal_edit_id").val(response.id);

                var date = new Date(response.date);
                var formatted_date = appendLeadingZeroes(date.getDate()) + "-" + appendLeadingZeroes(date.getMonth() + 1) + "-" + date.getFullYear();
                // console.log(formatted_date);
                $("#journal_date").val(formatted_date);

                if (response.items != '') {
                    var items = JSON.parse(response.items);
                    var len = items.length;

                    $('[data-repeater-list="journal"]').empty();
                    $('[data-repeater-create="journal"]').click();

                    for (var i = 1; i < len; i++) {
                        $('#journal_btn_add').click();
                    }

                    var tmp = '';
                    for (var i = 0; i < len; i++) {

                        tmp = "input[name$='journal[" + i + "][journal_sn]']";
                        $(tmp).val(i + 1);

                        tmp = "select[name$='journal[" + i + "][journal_master]']";
                        $(tmp).empty().append($("<option/>").val(items[i].master).text(items[i].master)).val(items[i].master).trigger("change");

                        tmp = "select[name$='journal[" + i + "][journal_particular]']";
                        $(tmp).empty().append($("<option/>").val(items[i].particular).text(items[i].particular)).val(items[i].particular).trigger("change");

                        tmp = "input[name$='journal[" + i + "][journal_debit]']";
                        $(tmp).val(items[i].debit).trigger("change");

                        tmp = "input[name$='journal[" + i + "][journal_credit]']";
                        $(tmp).val(items[i].credit).trigger("change");

                    }
                }

                KTUtil.scrollTop();

            } // /success
        }); // /fetch selected member info
    } else {
        alert('Error : Please refresh the page');
    }
}

function removeJournal(id = true) {
    if (id) {
        $('#delete_journal_submit').unbind('click').bind('click', function () {

            $.ajax({
                url: '../assets/custom/journal/delete.php',
                type: 'post',
                data: { member_id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        swal.fire({
                            position: 'top-right',
                            type: 'success',
                            title: 'Journal entry has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        swal.fire({
                            position: 'top-right',
                            type: 'error',
                            title: 'There were some errors in your submission.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    $("#kt_modal_d_journal").modal('hide');
                    manageJournalTable.reload();
                }
            });
        });
    } else {
        alert('Error : Please refresh the page');
    }
}