<?PHP

class Session
{
    public function login( string $login , string $password ) : void
    {
        if( !empty( $login ) && !empty( $password ) )
        {
            $loginQuery = $query->db( "SELECT * FROM `accounts` WHERE `login` = '" . $login . "' AND `password` = '" . $password . "'" );
    
            if( !empty( $loginQuery ) )
            {
                $_SESSION['logged'] = $loginQuery[0]['id'];
            }
        }
    }
    
    public function isLogged() : bool
    {
        if( isset( $_SESSION['logged'] ) )
        {
            return true;
        }
        return false;
    }
}

?>