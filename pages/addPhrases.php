<?PHP

include_once( __DIR__ . '/../classes/addPhrases.php' );
$addPhrases = new AddPhrases;

echo $addPhrases->checkForm( 'addPhrases' );

echo pageHeader( 'Frazy' , 'Dodaj' , FALSE );
echo content( $addPhrases->drawForm( ) );

?>