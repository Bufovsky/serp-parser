<?PHP

include_once( __DIR__ . '/../classes/session.php' );
$session = new Session;

$postLogin = isset( $_POST['login'] ) ? $_POST['login'] : NULL ;

if( isset( $postLogin ) )
{
    $login = isset( $_POST['login'] ) ? : NULL;
    $password = isset( $_POST['password'] ) ? : NULL;

    $session->login( $login , $password );
}

?>