<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Management</title>

    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include DataTable CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" />

    <!-- jQuery, Bootstrap, and DataTable JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <style>
        .expense-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }

        .form-group label {
            font-weight: bold;
        }

        .table-container {
            margin-top: 40px;
            overflow-x: auto;
        }

        .form-group label {
            font-size: 17px;
            /* Adjust the value as per your need */
        }

        .form-control {
            font-size: 16px;
        }

        .expense-actions {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        #expense_submit {
            font-size: 17px;
            font-weight: bold;
            padding: 8px 18px;
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .filter-toolbar .form-group {
            margin-bottom: 1rem;
        }

        .filter-wrap {
            max-width: 100%;
        }

        #filter_category,
        #filter_date_from,
        #filter_date_to,
        #filter_cash_bank {
            width: 100% !important;
        }

        @media (max-width: 767.98px) {
            .expense-container {
                padding: 16px;
                margin: 10px;
            }

            .expense-container h1 {
                font-size: 1.5rem;
            }

            .form-group label {
                font-size: 15px;
            }

            .expense-actions {
                justify-content: stretch;
            }

            #expense_submit {
                width: 100%;
                font-size: 16px;
                padding: 10px 14px;
            }

            .filter-wrap {
                max-width: 100%;
            }
        }
    </style>
</head>

<body style="background-color: #D3D3D3;">


    <div class="container expense-container">
        <h1 class="text-center">Expense Management</h1>

        <!-- Begin Form to Add Expenses -->
        <form class="kt-form" id="add_expense_form" style="margin-top:20px">
            <div class="form-group row">
                <!-- Description Field -->
                <input type="hidden" id="expense_id" name="expense_id">
                <!-- Bank Field -->

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>Bank <span class="text-danger">*</span></label>
                        <select name="bank" id="bank" class="form-control rc_bank-select2" required>
                            <!-- Options will be loaded via AJAX -->
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>Description <span class="text-danger">*</span></label>
                        <input name="description" id="description" class="form-control" placeholder="Enter description"
                            type="text" maxlength="100" required>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>Date <span class="text-danger">*</span></label>
                        <input name="date" id="date" class="form-control" type="text" inputmode="numeric"
                            placeholder="dd-mm-yyyy" maxlength="10" autocomplete="off" required>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <!-- Amount Field -->
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>Amount <span class="text-danger">*</span></label>
                        <input name="amount" id="amount" class="form-control" placeholder="Enter amount" type="number"
                            min="0" required>
                    </div>
                </div>

                
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>Category <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-control rc_category-select2" required>
                            <!-- Options will be loaded via AJAX -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="expense-actions">
                <button id="expense_submit" type="button" class="btn btn-primary">
                    Submit
                </button>
            </div>

        </form>
        <!-- End Form to Add Expenses -->
        <div class="row filter-toolbar align-items-end">
            <div class="col-12 col-md-4">
                <div class="form-group filter-wrap mb-0">
                    <label for="filter_category">Filter by Category:</label>
                    <select id="filter_category" class="form-control">
                        <option value="">All Categories</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group mb-0">
                    <label for="filter_date_from">From date:</label>
                    <input type="text" id="filter_date_from" class="form-control" inputmode="numeric"
                        placeholder="dd-mm-yyyy" maxlength="10" autocomplete="off" />
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group mb-0">
                    <label for="filter_date_to">To date:</label>
                    <input type="text" id="filter_date_to" class="form-control" inputmode="numeric"
                        placeholder="dd-mm-yyyy" maxlength="10" autocomplete="off" />
                </div>
            </div>
        </div>
        <div class="row filter-toolbar align-items-end">
            <div class="col-12 col-md-4">
                <div class="form-group filter-wrap mb-0">
                    <label for="filter_cash_bank">Cash / Bank:</label>
                    <select id="filter_cash_bank" class="form-control">
                        <option value="">All</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Begin Expenses Table -->
        <div class="table-container">
            <table id="expenses_table" class="display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                    <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Bank</th>
                        <th>Category</th>
                        <th>Actions</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
        <!-- End Expenses Table -->
    </div>

    <script>$(document).ready(function () {

    function formatDateDdMmYyyy(raw) {
        if (raw == null || raw === '') {
            return '';
        }
        var s = String(raw).trim();
        var iso = s.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (iso) {
            return iso[3] + '-' + iso[2] + '-' + iso[1];
        }
        if (/^\d{2}-\d{2}-\d{4}$/.test(s)) {
            return s;
        }
        return s;
    }

    function parseFlexibleDateToIso(val) {
        if (val == null || String(val).trim() === '') {
            return '';
        }
        var s = String(val).trim();
        if (/^\d{4}-\d{2}-\d{2}$/.test(s)) {
            return s;
        }
        var m = s.match(/^(\d{2})-(\d{2})-(\d{4})$/);
        if (!m) {
            return '';
        }
        var d = parseInt(m[1], 10);
        var mo = parseInt(m[2], 10);
        var y = parseInt(m[3], 10);
        if (mo < 1 || mo > 12 || d < 1 || d > 31) {
            return '';
        }
        var dt = new Date(y, mo - 1, d);
        if (dt.getFullYear() !== y || dt.getMonth() !== mo - 1 || dt.getDate() !== d) {
            return '';
        }
        return y + '-' + String(mo).padStart(2, '0') + '-' + String(d).padStart(2, '0');
    }

    function todayDdMmYyyy() {
        var d = new Date();
        var dd = String(d.getDate()).padStart(2, '0');
        var mm = String(d.getMonth() + 1).padStart(2, '0');
        return dd + '-' + mm + '-' + d.getFullYear();
    }

    // Initialize Select2 for Category with AJAX
    $('.rc_category-select2').select2({
        ajax: {
            url: '../assets/custom/api_get/get_categoryb.php',  // URL to fetch category data
            dataType: 'json'
        },
        width: '100%',
        placeholder: 'Select Category',
        allowClear: true,
        tags: true
    });

    // Initialize Select2 for Bank with AJAX
    $('.rc_bank-select2').select2({
        ajax: {
            url: '../assets/custom/api_get/get_bank.php',  // URL to fetch bank data
            dataType: 'json',
           
        },
        width: '100%',
        placeholder: 'Select Bank',
        allowClear: true
    });
    function loadCategoryFilter() {
        $.ajax({
            url: "../assets/custom/api_get/get_distinct_categories.php", // Server-side script to fetch categories
            method: "GET",
            success: function (response) {
                var categories = JSON.parse(response);
                $('#filter_category').empty().append('<option value="">All Categories</option>');
                categories.forEach(function (category) {
                    $('#filter_category').append('<option value="' + category + '">' + category + '</option>');
                });
            },
            error: function (xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }

    // Load categories on page load
    loadCategoryFilter();

    function initExpenseTable() {
    // Initialize DataTable with AJAX to fetch data from the server
    var table = $('#expenses_table').DataTable({
        "ajax": {
            "url": "../assets/custom/expense/fetch_expenses.php", // Fetch expenses data from this PHP file
            "type": "GET",
            "data": function (d) {
                d.category = $('#filter_category').val();
                d.date_from = parseFlexibleDateToIso($('#filter_date_from').val());
                d.date_to = parseFlexibleDateToIso($('#filter_date_to').val());
                d.account_filter = $('#filter_cash_bank').val();
            },
            "dataSrc": ""  // No need for a key if the PHP response is a simple array
        },
        "columns": [
            {
                "data": "date",
                "render": function (data, type) {
                    if (type === 'sort' || type === 'type') {
                        var s = String(data || '').trim();
                        var isoPrefix = s.match(/^(\d{4}-\d{2}-\d{2})/);
                        if (isoPrefix) {
                            return isoPrefix[1];
                        }
                        return parseFlexibleDateToIso(formatDateDdMmYyyy(data)) || '';
                    }
                    return formatDateDdMmYyyy(data);
                }
            },
            { "data": "description" },   // Maps to the description column in the table
            { "data": "amount" },        // Maps to the amount column
            { "data": "account" },          // Maps to the bank column
            { "data": "category" } ,     // Maps to the category column
            {
                "data": null,
                "defaultContent": "<button class='btn btn-primary btn-sm edit-btn mr-1 mb-1'>Edit</button><button class='btn btn-danger btn-sm delete-btn mb-1'>Delete</button>"  // Add Edit and Delete buttons
            }
        ],
        "responsive": {
            "details": false
        },
        "paging": true,       // Enable pagination
        "searching": true,    // Enable searching
        "ordering": true      // Enable sorting
    });

    $('#filter_category').on('change', function () {
        table.ajax.reload();
    });

    $('#filter_date_from, #filter_date_to').on('change', function () {
        table.ajax.reload();
    });

    $('#filter_cash_bank').on('change', function () {
        table.ajax.reload();
    });

    $('#date').val(todayDdMmYyyy());
    // Handle Edit button click
    $('#expenses_table tbody').on('click', '.edit-btn', function () {
        var data = table.row($(this).parents('tr')).data();  // Get row data

        // Populate the form fields with data from the row
        $('#expense_id').val(data.id);  // Assuming there's a hidden ID field for editing
        $('#description').val(data.description);
        $('#amount').val(data.amount);
        $('#date').val(formatDateDdMmYyyy(data.date));

        // Populate Select2 fields
        if (data.bank) {
            var newOptionBank = new Option(data.bank, data.bank, true, true);
            $('.rc_bank-select2').append(newOptionBank).trigger('change');
        }

        if (data.category) {
            var newOptionCategory = new Option(data.category, data.category, true, true);
            $('.rc_category-select2').append(newOptionCategory).trigger('change');
        }

        // Scroll to the top of the page
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Handle Delete button click
    $('#expenses_table tbody').on('click', '.delete-btn', function () {
        var data = table.row($(this).parents('tr')).data();  // Get row data
        var expenseId = data.id;  // Assuming 'id' is the field representing the record ID

        if (confirm("Are you sure you want to delete this record?")) {
            // Send AJAX request to delete the record
            $.ajax({
                url: '../assets/custom/expense/delete.php',  // PHP file to handle deletion
                method: 'POST',
                data: { id: expenseId },  // Send the record ID to be deleted
                success: function (response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert(result.messages);
                        table.ajax.reload();  // Reload the DataTable to show updated data
                    } else {
                        alert(result.messages);
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error: " + error);
                    alert("An error occurred while processing the request.");
                }
            });
        }
    });

    // Handle form submission to create or edit an expense
    $('#expense_submit').on('click', function () {
        var id = $('#expense_id').val();  // Hidden field for ID if editing
        var description = $('#description').val();
        var amount = $('#amount').val();
        var bank = $('#bank').val();
        var category = $('#category').val();
        var dateRaw = $('#date').val();
        var dateIso = parseFlexibleDateToIso(dateRaw);

        // Validate form data
        if (description && amount && bank && category && dateIso) {
            var formData = {
                id: id, // Send ID for updating or leave empty for a new entry
                description: description,
                amount: amount,
                bank: bank,
                category: category,
                date: dateIso
            };

            // Send data to the server for saving in the database
            $.ajax({
                url: '../assets/custom/expense/create.php',  // Handle the form submission
                method: 'POST',
                data: formData,
                success: function (response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert(result.messages);
                         $('#add_expense_form')[0].reset(); // Reset the form inputs
                    $('#expense_id').val('');          // Clear the hidden ID field

                    // Reset Select2 dropdowns
                    $('.rc_bank-select2').val(null).trigger('change');  // Reset bank dropdown
                    $('.rc_category-select2').val(null).trigger('change');  // Reset category dropdown

                    // Set today's date again in the date field
                    $('#date').val(todayDdMmYyyy());

                    // Reload the DataTable to show updated data from the database
                    table.ajax.reload();
                    } else {
                        alert(result.messages);
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error: " + error);
                    alert("An error occurred while processing the request.");
                }
            });
        } else {
            if (!dateIso && dateRaw) {
                alert('Please enter a valid date as dd-mm-yyyy.');
            } else {
                alert("All fields are mandatory. Please fill in all the fields.");
            }
        }
    });
    }

    // Default date filters from session range (same as rest of admin), then load table
    $.ajax({
        url: '../assets/custom/api_get/getrange.php',
        method: 'GET',
        dataType: 'json'
    }).done(function (res) {
        if (res && res.success && res.data) {
            if (res.data.start) {
                $('#filter_date_from').val(formatDateDdMmYyyy(res.data.start));
            }
            if (res.data.end) {
                $('#filter_date_to').val(formatDateDdMmYyyy(res.data.end));
            }
        }
    }).always(function () {
        initExpenseTable();
    });

});

    </script>

</body>

</html>