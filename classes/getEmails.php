<?PHP

class GetEmails
{
	protected $emails = [];
	protected $pages = [];
	protected $match = [];
	protected $find;
	public $link;
	
	public function getLink( string $urlID = NULL ) : array
	{
		global $query;
		
		$checkURL = isset( $url ) ? 'AND `id` = "'. $urlID .'"' : NULL;
		
		$queryLink = $query->db( "SELECT * FROM `urls` WHERE `checked` = '0' AND `url` IS NOT NULL ". $checkURL ." LIMIT 1;" );
		
		return !empty( $queryLink ) ? $queryLink : exit();
	}
	
	private function setLink( int $urlID ) : bool
	{
		global $query;

		$querySetLink = $query->db( "UPDATE `urls` SET `checked` = '1' WHERE `id` = '". $urlID ."';" );
		
		return !empty( $querySetLink ) ? true : false;
	}
	
	private function checkURL( string $url ) : string
	{
		$elements = [ 'www' , 'http' ];
		
		foreach( $elements as $element )
		{
			if ( strpos( $url , $element ) !== false ) {
				return $url;
			}
		}
		
		return sprintf( "%s%s" , $this->link[0]['url'] , $url );
	}

	private function addArray( string $name , string $value ) : void
	{
		array_push( $this->$name , $value );
	}
	
	
	private function getContent( string $link )
	{
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		); 
		
		try
		{
			$html = file_get_contents( $link , false , stream_context_create($arrContextOptions) );
		} catch (Exception $e) {}
		
		if( !empty( $html ) )
		{
			$dom = new DOMDocument;
			@$dom->loadHTML( $html );
		}
		return isset( $dom ) ? $dom : false;
	}
	
	private function getPages( $pageLink ) : void
	{
		$dom = $this->getContent( $pageLink );
		
		if( $dom )
		{
			$links = $dom->getElementsByTagName( 'a' );

			foreach ($links as $link)
			{
				if( !in_array( $link->getAttribute( 'href' ) , $this->pages ) )
				{
					$this->addArray( 'pages' , $this->checkURL( $link->getAttribute( 'href' ) ) );
				}
			}
		}
	}
	
	public function getPageDOM( string $link ) : string
	{
		$ch = curl_init($link);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 180 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 180 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 );
			curl_setopt( $ch, CURLOPT_MAXCONNECTS, 1 );
		
		$html = curl_exec($ch);
		curl_close($ch);
		
		return !empty( $html ) ? $html : '';
	}
	
	private function checkEmail( string $email )
	{
		$parms = explode( "@" , $email );
		$checkDns = getmxrr( $parms[1] , $mx_details );
		return ( !in_array( $email , $this->emails ) && !empty( $mx_details ) ) ? true : false; 
	}
	
	private function getEmailsFromPages( )
	{
		$countPages = count( $this->pages ) > 100 ? 100 : count( $this->pages );
		for( $i = 0 ; $i < $countPages ; $i++ )
		{
			$this->parseEmails( $this->pages[$i] );
		}
		return true;
	}
	
	private function parseEmails( string $url )
	{
		$html = $this->getPageDOM( $url );
		$str = '/[\w.%+-]+@(?:[a-z\d-]+\.)+[a-z]{2,4}/iu';
		preg_match_all($str, $html, $out);
		
		foreach( $out[0] as $email )
		{
			if ( $this->checkEmail( $email ) )
			{
				$this->addArray( 'emails' , $email );
			}
		}

		return true;
	}
	
	private function checkEmails( string $url , array $emails ) : string
	{
		$domain = substr( $url , 4 , strlen( $url ) );
		$alias = [ 'biuro' , 'info' , 'kontakt' , 'bok' ];
		$domains = [ 'gmail.com' , 'wp.pl' , 'interia.pl' , 'o2.pl' , 'onet.pl' ];
		
		foreach( $emails as $email )
		{
			$emailParm = explode( '@' , $email );
			
			if( count( $this->match ) == 0 ){ if( $emailParm[1] == $domain ){ $this->addArray( 'match' , $email ); } }
			if( count( $this->match ) == 0 ){ if( in_array( $emailParm[0] , $alias ) ){ $this->addArray( 'match' , $email ); } }
			if( count( $this->match ) == 0 ){ if( in_array( $emailParm[1] , $domains ) ){ $this->addArray( 'match' , $email ); } }
		}
		
		if( count( $this->match ) == 0 ){ $this->addArray( 'match' , $emails[0] ); }
		
		return $this->match[0];
	}
	
	private function selectEmail( int $urlID  ) : void
	{
		global $query;

		if( count( $this->emails ) > 1 )
		{
			$bestMatch = $this->checkEmails( $this->link[0]['url'] , $this->emails );
		}
		if( count( $this->emails ) >= 1 )
		{
			$email = isset( $bestMatch ) ? $bestMatch : $this->emails[0];
					//echo "INSERT INTO `emails` ( `urlID` , `email` ) VALUES ( '". $urlID ."' , '". $email ."'";
			$query->db( "INSERT INTO `emails` ( `urlID` , `email` ) VALUES ( '". $urlID ."' , '". $email ."' );" );
		}

	}
	
	public function console( array $link ) : void
	{
		$this->setLink( $link[0]['id'] );
		$this->getPages( $link[0]['url'] );
		
		$this->getEmailsFromPages( );
		
		$this->selectEmail( $link[0]['id'] );

		var_dump($this->pages);
		echo "</br></br>";
		var_dump($this->emails);
		echo "</br></br>";
		var_dump($this->match);
		
	}
}

?>