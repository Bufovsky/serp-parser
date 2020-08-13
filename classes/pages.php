<?PHP

class Pages
{
    public function params()
    {
        $userParams = explode('/', trim(substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME']))), '/'));
		foreach($userParams as $key => $value)
            $userParams[$key] = urldecode($value);
            
        return $userParams;
    }

    public function content() : string
    {
        ob_start();

			$url = !empty( $this->params() ) ? $this->params()[0] : 'dashboard';
			
			$file = file_exists( 'pages/' . $url . '.php' ) ? 'pages/' . $url . '.php' : 'pages/dashboard.php';

			include_once( $file );

        $main_content = ob_get_clean();

        return $main_content;
    }
}

?>