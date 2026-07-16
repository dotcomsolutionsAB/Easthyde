<!-- Begin: Content -->
<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
    <div class="col-lg-12">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">Bank Ledger</h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form class="kt-form">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Select Bank</label>
                            <select class="form-control" id="select_bank">
                                <!-- Banks will be populated here -->
                                <option>Select Bank</option>
                                 <option value = "CASH2">CASH (Primary)</option>
                            </select>
                            <span class="form-text text-muted">Please select a bank</span>
                        </div>

                        <div class="col-sm-3">
                            <label>Select Start Date</label>
                            <input type="text" class="form-control date-picker" id="start_date"
                                placeholder="Start Date">
                        </div>
                        <div class="col-sm-3">
                            <label>Select End Date</label>
                            <input type="text" class="form-control date-picker" id="end_date" placeholder="End Date">
                        </div>
                    </div>
                </form>
                <div class="kt-separator kt-separator--border-dashed"></div>

                <!-- Datatable -->
                <table id="bank_ledger_table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Particular</th>
                            <th>Reference No.</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End: Content -->

<!-- Include necessary JS and CSS libraries -->

<body>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
     $(document).ready(function () {
    // Initialize Select2 for bank selection
    $('#select_bank').select2({
        placeholder: 'Select a Bank',
        ajax: {
            url: '../assets/custom/api_get/get_banks.php', // API to fetch banks
            dataType: 'json',
        }
    });

    // Initialize Date Range Picker
    $('#start_date, #end_date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD-MM-YYYY', // Format as Day-Month-Year
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 0 // Sunday as the first day of the week
        }
    });

    var table = $('#bank_ledger_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        dom: '<"top"lfB>rt<"bottom"ip><"clear">', // Adjusted DOM to place items inline
        ajax: {
            url: '../assets/custom/api_get/get_bank_ledger.php',
            type: 'GET',
            data: function (d) {
                d.bank_id = $('#select_bank').val(); // Set bank_id based on dropdown value
                d.start_date = $('#start_date').val(); // Set start date
                d.end_date = $('#end_date').val(); // Set end date
            }
        },
        columns: [
            {
                data: null, render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1; // Serial number adjusted for pagination
                }
            },
            { data: 'date' },
            { data: 'particular' },
            { data: 'reference_no' },
            { data: 'debit', render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: 'credit', render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: 'balance', render: $.fn.dataTable.render.number(',', '.', 2, '') }
        ],
        buttons: [
            {
                extend: 'pdfHtml5',
                title: 'Bank Ledger Report',
                messageTop: function () {
                    return 'Bank: ' + $('#select_bank').val() + '\n Date Range: ' + $('#start_date').val() + ' to ' + $('#end_date').val();
                },
                customize: function (doc) {
                    doc.content.splice(0, 1, {
                        text: 'Bank Ledger Report',
                        fontSize: 14,
                        alignment: 'center',
                        bold: true
                    });
                    doc.styles.tableHeader = {
                        bold: true,
                        fontSize: 11,
                        color: 'black'
                    };
                    doc['footer'] = (function (page, pages) {
                        return {
                            columns: [
                                {
                                    alignment: 'left',
                                    text: ['Generated by: Easthyde by Dotcom Solutions']
                                },
                                {
                                    alignment: 'right',
                                    text: ['Page ' + page.toString() + ' of ' + pages.toString()]
                                }
                            ],
                            margin: [10, 0]
                        };
                    });
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Bank Ledger Report',
                messageTop: function () {
                    return 'Bank: ' + $('#select_bank').val() + '\n Date Range: ' + $('#start_date').val() + ' to ' + $('#end_date').val();
                },
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var rows = sheet.getElementsByTagName('row');
                    var newRow = '<row r="1"><c t="inlineStr" r="A1"><is><t>Custom Header: Additional Info Here</t></is></c></row>';
                    sheet.getElementsByTagName('sheetData')[0].innerHTML = newRow + sheet.getElementsByTagName('sheetData')[0].innerHTML;
                }
            }
        ],
        columnDefs: [
            { targets: [0], width: '3%' }, // S.No
            { targets: [1], width: '10%' }, // Date
            { targets: [2], width: '30%' }, // Particular
            { targets: [3], width: '15%' }, // Reference No.
            { targets: [4], width: '10%' }, // Debit
            { targets: [5], width: '10%' }, // Credit
            { targets: [6], width: '10%' }  // Balance
        ]
    });

    // Reload table data when filters (bank or start/end date) change
    $('#select_bank, #start_date, #end_date').on('change', function () {
        table.ajax.reload();
    });
});
    </script>
</body>
