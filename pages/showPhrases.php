<?PHP

include_once( __DIR__ . '/../classes/showPhrases.php' );
$showPhrases = new ShowPhrases;

global $page;
$parameter = isset( $page->params()[1] ) ? $page->params()[1] : NULL;
$phraseName = $showPhrases->getDbQuery( 'phrases' , $parameter )[0]['name'];
$download = isset( $page->params()[2] ) ? $showPhrases->generateCsv( $showPhrases->generateCsvArray( $parameter ) , $phraseName ) : NULL;

$elements = !empty( $parameter ) ? [ 'Fraza' , $phraseName , $showPhrases->drawURLS() ] : [ 'Frazy' , 'Wyświetl' , $showPhrases->drawPhrases( ) ];

echo $download;
echo pageHeader( $elements[0] , $elements[1] , FALSE );
echo content( $elements[2] );


?>