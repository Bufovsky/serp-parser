<?PHP

include_once( __DIR__ .'/../classes/parsePhrase.php' );
$parsePhrase = new ParsePhrase;

$phrase = $parsePhrase->getPhrase( );
$parsePhrase->decipher( $phrase );

//echo $parsePhrase->showElements( );


?>