<!-- Begin: Content -->
<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
   
    <div class="col-lg-12">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">Profit Calculation</h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form class="kt-form">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Select Series</label>
                            <select class="form-control" id="select_series">
                                <!-- Banks will be populated here -->
                                <option selected>PRIMARY</option>
                            </select>
                            <span class="form-text text-muted">Please select a Series</span>
                        </div>
                        <div class="col-sm-4">
                            <label>Select Start Date</label>
                            <input type="text" class="form-control date-picker" id="start_date"
                                placeholder="Start Date">
                        </div>
                        <div class="col-sm-4">
                            <label>Select End Date</label>
                            <input type="text" class="form-control date-picker" id="end_date" placeholder="End Date">
                        </div>
                    </div>
                </form>
                <div class="kt-separator kt-separator--border-dashed"></div>

                <!-- Datatable -->
                <table id="profit_calculation_table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Invoice No</th>
                            <th>Notes</th>
                            <th>Sale Price</th>
                            <th>Cost Price</th>
                            <th>Profit</th>
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
	 <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
	
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script>
       
    $(document).ready(function () {
        // Initialize the date picker for the start and end dates
        $('#start_date, #end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 0
            }
        });

        // Initialize Select2 for series selection
        $('#select_series').select2({
            placeholder: 'Select Series',
            ajax: {
                url: '../assets/custom/api_get/get_series.php',
                dataType: 'json',
            }
        });

        var table = $('#profit_calculation_table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            pageLength: 10,
            dom: '<"top"lfB>rt<"bottom"ip><"clear">',
            ajax: {
                url: '../assets/custom/api_get/get_profit.php',
                type: 'GET',
                data: function (d) {
                    d.series = $('#select_series').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
                dataSrc: function (json) {
                    var totalProfit = 0;
                    json.data.forEach(function (row) {
                        var profitValue = row.profit ? row.profit.replace(/,/g, '') : 0;
                        totalProfit += parseFloat(profitValue) || 0;
                    });

                    // Update footer content
                    if ($('#totalProfitCell').length) {
                        $('#totalProfitCell').text($.fn.dataTable.render.number(',', '.', 2, '').display(totalProfit));
                    } else {
                        $('#profit_calculation_table').append(
                            '<tfoot><tr>' +
                            '<td colspan="8" style="text-align:right"><strong>Total Profit:</strong></td>' +
                            '<td id="totalProfitCell">' + $.fn.dataTable.render.number(',', '.', 2, '').display(totalProfit) + '</td>' +
                            '</tr></tfoot>'
                        );
                    }

                    return json.data;
                }
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'date' },
                { data: 'product' },
                { data: 'quantity' },
                { data: 'invoice_no' },
                { data: 'notes' },
                { data: 'sale_price' },
                { data: 'cost_price' },
                { data: 'profit' }
            ],
            buttons: [
                {
                    extend: 'pdfHtml5',
                    title: 'Profit Calculation Report',
                    messageTop: function () {
                        return 'Date Range: ' + $('#start_date').val() + ' to ' + $('#end_date').val();
                    },
                    customize: function (doc) {
                        let tableBody = null;
                        doc.content.forEach(function (contentItem) {
                            if (contentItem.table) {
                                tableBody = contentItem.table.body;
                            }
                        });

                        if (tableBody) {
                            var totalProfit = 0;
                            table.rows().every(function () {
                                var data = this.data();
                                totalProfit += parseFloat(data.profit.replace(/,/g, '')) || 0;
                            });

                            tableBody.push([
                                { text: '', alignment: 'right' },
                                { text: '', alignment: 'right' },
                                { text: '', alignment: 'right' },
                                { text: '', alignment: 'right' },
                                { text: '', alignment: 'right' },
                                { text: '', alignment: 'right' },
                                { text: '', alignment: 'right' },
                                { text: 'Total Profit:', alignment: 'right', bold: true },
                                { text: $.fn.dataTable.render.number(',', '.', 2, '').display(totalProfit), alignment: 'right', bold: true }
                            ]);
                        }
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Profit Calculation Report',
                    messageTop: function () {
                        return 'Date Range: ' + $('#start_date').val() + ' to ' + $('#end_date').val();
                    },
                    footer: true,
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var totalProfit = 0;
                        table.rows().every(function () {
                            var data = this.data();
                            totalProfit += parseFloat(data.profit.replace(/,/g, '')) || 0;
                        });

                        var lastRow = '<row r="' + (table.rows().count() + 2) + '"><c t="inlineStr" r="A' + (table.rows().count() + 2) + '"><is><t>Total Profit: ' +
                            $.fn.dataTable.render.number(',', '.', 2, '').display(totalProfit) +
                            '</t></is></c></row>';

                        $(sheet).find('sheetData').append(lastRow);
                    }
                }
            ],
            columnDefs: [
                { targets: [0], width: '3%' },
                { targets: [1], width: '7%' },
                { targets: [2], width: '35%' },
                { targets: [3], width: '5%' },
                { targets: [4], width: '15%' },
                { targets: [5], width: '10%' },
                { targets: [6], width: '10%' },
                { targets: [7], width: '15%' }
            ]
        });

        $('#select_series, #start_date, #end_date').on('change', function () {
            table.ajax.reload();
        });
    });

    </script>
</body>
