<?php
// Include your database connection
include('../assets/custom/connect.php');

// Check maintenance mode status
$query = "SELECT is_maintenance_mode FROM setting WHERE id = 1";
$result = $db->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $is_maintenance = $row['is_maintenance_mode'];

    if ($is_maintenance) {
        // Display maintenance mode message and exit the script
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Under Maintenance</title>
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    font-family: Arial, sans-serif;
                    background-color: white;
                    margin: 0;
                }
                .maintenance-message {
                    text-align: center;
                    color: #333;
                }
               
                .maintenance-message p {
                    font-size: 1.2rem;
                }
					.hidden-btn {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background-color: #ff0000;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    padding: 10px 20px;
                    font-size: 16px;
                    cursor: pointer;
                    opacity: 0; /* Button is barely visible initially */
                    transition: opacity 0.3s ease;
                }

                /* Show button on hover */
                .hidden-btn:hover {
                    opacity: 1; /* Fully visible on hover */
                }
            </style>
        </head>
        <body>
            <div class='maintenance-message'>
               
											<img src='../assets/media/company-logos/logo.jpg' height='200px'>
										
                <p>We are currently performing scheduled maintenance. Please check back later.</p>
            </div>
			<form action='killswitch.php' method='POST'>
                <button class='hidden-btn' type='submit'>Toggle</button>
            </form>
        </body>
        </html>";
        exit(); // Stop further execution of the page
    }
}
?>
<?php

include("userlevel.php");
include("../assets/custom/connect.php");

?>
<!DOCTYPE html>

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

	<!-- begin::Head -->
	<head>
		<base href="">
		<meta charset="utf-8" />
		<title>Easthyde</title>
		<meta name="description" content="Updates and statistics">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="../assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link href="../assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->
		<!--begin::Page Custom Styles(used by this page) -->
		<link href="../assets/css/pages/wizard/wizard-3.css" rel="stylesheet" type="text/css" />

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="../assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<link href="../assets/css/jquery_ui.css" rel="stylesheet" type="text/css" />
		<link href="../assets/plugins/custom/image-picker/image-picker.css" rel="stylesheet" type="text/css" />


		

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="../assets/css/skins/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="../assets/css/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="../assets/css/skins/brand/dark.css" rel="stylesheet" type="text/css" />
		<link href="../assets/css/skins/aside/dark.css" rel="stylesheet" type="text/css" />
		<link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />


		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="../assets/media/logos/favicon.ico" />
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<!-- <body class="kt-page--loading-enabled kt-page--loading kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"> -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-aside--minimize kt-page--loading">

		<!--[html-partial:include:{"file":"partials/_page-loader.html"}]/-->
		<?php include("partials/_page-loader.php"); ?>

		<!--[html-partial:include:{"file":"layout.html"}]/-->
		<?php include("layout.php"); ?>		

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": [
							"#c5cbe3",
							"#a1a8c3",
							"#3d4465",
							"#3e4466"
						],
						"shape": [
							"#f0f3ff",
							"#d9dffa",
							"#afb4d4",
							"#646c9a"
						]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="../assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
		<script src="../assets/js/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="../assets/plugins/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
		<script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
		<script src="../assets/plugins/custom/gmaps/gmaps.js" type="text/javascript"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<script src="../assets/plugins/custom/image-picker/image-picker.min.js" type="text/javascript"></script>
		<script src="../assets/js/pages/dashboard.js" type="text/javascript"></script>
		<script src="../assets/js/pages/my-script.js" type="text/javascript"></script>
		<script src="../assets/js/pages/consignment-mrn.js" type="text/javascript"></script>
		<script src="../assets/js/pages/my-toastr.js" type="text/javascript"></script>
		<script src="../assets/js/pages/calculator.js" type="text/javascript"></script>
		<script src="../assets/js/pages/crud/file-upload/dropzonejs.js" type="text/javascript"></script>
		<script src="../assets/js/pages/custom/jquery.serializejson.js" type="text/javascript"></script>
		<script src="assets/js/pages/custom/user/profile.js" type="text/javascript"></script>
		<script src="../assets/plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
		<script src="../assets/js/pages/crud/datatables/advanced/footer-callback.js" type="text/javascript"></script>

		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>