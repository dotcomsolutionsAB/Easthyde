"use strict";

// Class definition
var KTDashboard = function () {

    // Daterangepicker Init
    var daterangepickerInit = function () {

        moment.locale("en");

        if ($("#kt_dashboard_daterangepicker").length == 0) {
            return;
        }

        var picker = $("#kt_dashboard_daterangepicker");

        // Default range (will be overwritten by session range via getrange.php)
        var start = moment();
        var end = moment();

        var quarter = moment().quarter();

        var sem_start = 2;
        var sem_end = 3;
        var add_year = 0;
        var sub_year = 0;
        var y_sub = 0;

        if (quarter == 4) {
            sem_start = 4;
            sem_end = 1;
            add_year = 1;
        }

        if (quarter == 1) {
            sem_start = 4;
            sem_end = 1;
            sub_year = 1;
            y_sub = 1;
        }

        function renderLabelRange(start, end, label) {
            var title = "";
            var range = "";

            if ((end - start) < 100 || label === "Today") {
                title = "Today:";
                range = start.format("MMM D, YYYY");
            } else if (label === "Yesterday") {
                title = "Yesterday:";
                range = start.format("MMM D, YYYY");
            } else {
                range = start.format("MMM D, YYYY") + " - " + end.format("MMM D, YYYY");
            }

            $("#kt_dashboard_daterangepicker_date").html(range);
            $("#kt_dashboard_daterangepicker_title").html(title);
        }

        // ✅ Save range to session (JSON payload)
        function saveRange(start, end) {
            return $.ajax({
                url: "../assets/custom/api_set/setrange.php",
                method: "POST",
                contentType: "application/json; charset=UTF-8",
                dataType: "json",
                data: JSON.stringify({
                    start: start.format("YYYY-MM-DD"),
                    end: end.format("YYYY-MM-DD"),
                }),
            });
        }

        // ✅ Load range from session
        function loadRange() {
            return $.ajax({
                url: "../assets/custom/api_get/getrange.php",
                method: "GET",
                dataType: "json",
            });
        }

        // Called when user changes selection
        function cb(start, end, label) {
            renderLabelRange(start, end, label);

            saveRange(start, end)
                .done(function (response) {
                    if (response && response.success) {
                        // optional: confirm saved values
                        // console.log("Saved range:", response.data);
                        location.reload();
                    } else {
                        console.log("setrange not success:", response);
                        alert("Failed to set range");
                    }
                })
                .fail(function (xhr) {
                    console.log("setrange error:", xhr.status, xhr.responseText);
                    alert("setrange failed: " + xhr.status);
                });
        }

        // Init daterangepicker (with defaults)
        picker.daterangepicker(
            {
                direction: KTUtil.isRTL(),
                startDate: start,
                endDate: end,
                changeYear: true,
                opens: "left",
                ranges: {
                    Today: [moment(), moment()],
                    "This Month": [moment().startOf("month"), moment().endOf("month")],
                    "Last Month": [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                    "Current Financial Year (26-27)": [
                        moment("2026-04-01", "YYYY-MM-DD"),
                        moment("2027-03-31", "YYYY-MM-DD"),
                    ],
                    "FY 25-26": [
                        moment("2025-04-01", "YYYY-MM-DD"),
                        moment("2026-03-31", "YYYY-MM-DD"),
                    ],
                    "FY 24-25": [
                        moment("2024-04-01", "YYYY-MM-DD"),
                        moment("2025-03-31", "YYYY-MM-DD"),
                    ],
                    "FY 23-24": [
                        moment("2023-04-01", "YYYY-MM-DD"),
                        moment("2024-03-31", "YYYY-MM-DD"),
                    ],
                },
            },
            cb
        );

        // ✅ On page load, fetch session range and apply it to UI + picker
        loadRange()
            .done(function (response) {
                // New format: { success:true, data:{ start:'YYYY-MM-DD', end:'YYYY-MM-DD' } }
                var s = response?.data?.start || "";
                var e = response?.data?.end || "";

                if (s && e) {
                    start = moment(s, "YYYY-MM-DD");
                    end = moment(e, "YYYY-MM-DD");
                }

                // Update label
                renderLabelRange(start, end, "");

                // Update picker UI selection
                var drp = picker.data("daterangepicker");
                if (drp) {
                    drp.setStartDate(start);
                    drp.setEndDate(end);
                }
            })
            .fail(function (xhr) {
                console.log("getrange error:", xhr.status, xhr.responseText);

                // fallback display
                renderLabelRange(start, end, "");
            });
    };

    return {
        init: function () {
            daterangepickerInit();

            var loading = new KTDialog({
                type: "loader",
                placement: "top center",
                message: "Loading ...",
            });

            setTimeout(function () {
                loading.hide();
            }, 3000);
        },
    };
}();

// Class initialization on page load
jQuery(document).ready(function () {
    KTDashboard.init();
});
