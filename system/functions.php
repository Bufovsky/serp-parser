<?PHP

define('URL', "//" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);


function generateSearch( ) : string
{
	$main = '<ul class="breadcrumb-elements">
						<form action="#" method="post" style="width:100%;">
							<div class="input-group">
							<input type="text" name="wyszukaj" class="form-control" placeholder="Wyszukaj">
								<span class="input-group-btn">
									<input type="submit" class="btn btn-default" type="button" value="&#x1F50D;"/>
								</span>
							</div>
						</form>
					</ul>';
	return $main;
}

function pageHeader( $firstPart , $secondPart , $search ) : string
{
	$searchForm = $search == TRUE ? generateSearch() : NULL;
	
	$main = '
		<div class="page-header">
			<div class="page-header-content">
				<div class="page-title">
					<h4><a href="/main"><i class="icon-arrow-left52 position-left"></i></a> <span class="text-semibold headerTitle">'. $firstPart .'</span> - '. $secondPart .'</h4>
				</div>
			</div>

			<div class="breadcrumb-line breadcrumb-line-component">
				<ul class="breadcrumb">
					<li><a href="/main"><i class="icon-home2 position-left"></i> Dashboard</a></li>
					<li class="active">'. $firstPart .'</li>
				</ul>
				'. $searchForm .'
			</div>
		</div>';
		
	return $main;
}

function content( $content ) : string
{
	$main = '
		<div class="content">
			<div class="panel panel-flat">
				<div class="content">
					'. $content .'
				</div>
			</div>
		</div>';

	return $main;
}

function isActive( $url ) : string
{
	$urlParams = explode('/', trim(substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME']))), '/'));
	return $urlParams[0] == $url ? 'class="active"' : '';
}

?>