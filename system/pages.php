<?PHP

include_once( __DIR__ . '/../classes/pages.php' );
$page = new Pages;

define( "CONTENT", $page->content() );

?>