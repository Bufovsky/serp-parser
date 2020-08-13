<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <title>Pozycjoner</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex,nofollow">
		<link rel="icon" href="images/logo.png">

        <!-- Global stylesheets -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
        <link href="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
        <link href="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/css/colors.css" rel="stylesheet" type="text/css">
        <!-- /global stylesheets -->

        <!-- Core JS files -->
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/loaders/pace.min.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/core/libraries/jquery.min.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/core/libraries/bootstrap.min.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/loaders/blockui.min.js"></script>
        <!-- /core JS files -->

        <!-- Theme JS files -->
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/visualization/d3/d3.min.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/forms/styling/switchery.min.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/forms/styling/uniform.min.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/ui/moment/moment.min.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/plugins/pickers/daterangepicker.js"></script>
        <script type="text/javascript" src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/js/core/app.js"></script>
    </head>
    <body>
	
		<!-- Main navbar -->
		<div class="navbar navbar-default header-highlight">
			<div class="navbar-header">
				<a href="//<?PHP echo $_SERVER['HTTP_HOST']; ?>" class="navbar-brand"><img src="//<?=$_SERVER["SERVER_NAME"];?>/theme/assets/images/logo.png" alt=""></a>
				<ul class="nav navbar-nav visible-xs-block">
					<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
					<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
				</ul>
			</div>

			<div class="navbar-collapse collapse" id="navbar-mobile">
				<ul class="nav navbar-nav">
					<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
				</ul>

				<p class="navbar-text"><span class="label bg-success">Online</span></p>

				<!--
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown dropdown-user">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<img src="assets/images/user.png" alt="">
							<span>Admin</span>
							<i class="caret"></i>
						</a>

						<ul class="dropdown-menu dropdown-menu-right">
								<li><a href="/konto"><i class="icon-user-plus"></i>Konto</a></li>
								<li class="divider"></li>
							<li><a href="/logout"><i class="icon-switch2"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
				-->
			</div>

		</div>
		<!-- /main navbar -->

		<!-- Page container -->
		<div class="page-container">

			<!-- Page content -->
			<div class="page-content">

				<!-- Main sidebar -->
				<div class="sidebar sidebar-main">
					<div class="sidebar-content">

						<!-- Main navigation -->
						<div class="sidebar-category sidebar-category-visible">
							<div class="category-content no-padding">
								<ul class="navigation navigation-main navigation-accordion">

									<!-- Main -->
									<li class="navigation-header"><span>MENU</span> <i class="icon-menu" title="Main pages"></i></li>
									<li <?=isActive( 'addPhrases' );?>><a href="/addPhrases"><i class="icon-copy"></i> <span>Dodaj Frazy</span></a></li>
									<li <?=isActive( 'showPhrases' );?>><a href="/showPhrases"><i class="icon-stack2"></i> <span>Wyświetl Frazy</span></a></li>

								</ul>
							</div>
						</div>
						<!-- /main navigation -->

					</div>
				</div>
				<!-- /main sidebar -->

				<!-- Main content -->
				<div class="content-wrapper">

					<!-- Content area -->
					<div class="content">

						<?=CONTENT;?>

					</div>
					<!-- /content area -->

				</div>
				<!-- /main content -->

			</div>
			<!-- /page content -->

		</div>
		<!-- /page container -->

    </body>
</html>
