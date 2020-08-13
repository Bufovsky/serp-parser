<?PHP

include_once( __DIR__ . '/../classes/getEmails.php' );
$getEmails = new GetEmails;

global $page;

//echo urlencode('https://www.webmaster.sh/');

$getEmails->link = isset( $page->params()[1] ) ? $getEmails->getLink( $page->params()[1] ) : $getEmails->getLink( );

$getEmails->console( $getEmails->link );


?>