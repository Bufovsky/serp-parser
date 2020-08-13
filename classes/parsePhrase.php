<?PHP

class ParsePhrase
{
	protected $elements = [];
	protected $domelements = [];
	
	public function getPhrase( )
	{
		global $query;
		
		$phrase = $query->db( "SELECT * FROM `phrases` WHERE `checked` = 0 LIMIT 1;" );
		
		return !empty( $phrase ) ? $phrase[0] : exit();
	}
	
	private function checkString( $string ) : string
	{
		return filter_var( htmlspecialchars( trim( preg_replace( "/[^a-zA-Z0-9]+/" , "" , $string ) ) ) , FILTER_SANITIZE_STRING );
	}
	
	private function getPageDOM( string $html ) : object
	{
		$dom = new DOMDocument;
		@$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html );
		return $dom;
	}
	
	private function getContent( array $phrase , int $side ) : string
	{
		$page = $side * 10;
		$url = 'http://www.google.co.in/search?q='. urlencode( $phrase['name'] ) .'&start='. $page;
		
		$ch = curl_init( $url );
		
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 60 );
		curl_setopt( $ch, CURLOPT_TIMEOUT,60 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 );
			curl_setopt( $ch, CURLOPT_MAXCONNECTS, 1 );
		
		$html = utf8_encode( curl_exec( $ch ) );
		curl_close( $ch );
		
		//var_dump( $html );
		
		return $html;
	}
	
	private function in_array_r( $needle, $haystack, $strict = false ) : bool
	{
		foreach( $haystack as $item ) {
			if( ( $strict ? $item === $needle : $item == $needle ) || ( is_array( $item ) && $this->in_array_r( $needle, $item, $strict ) ) ) {
				return true;
			}
		}

		return false;
	}
	
	private function insertArray( string $array , string $element ) : void
	{
		array_push( $this->$array , $element );
	}
	
	private function showDOMNode( DOMNode $domNode ) : void
	{
		for( $i = 0 ; $i < count( $domNode->childNodes ) ; $i++ )
		{
			//echo $domNode->childNodes[$i]->nodeName.':'.$domNode->childNodes[$i]->nodeValue .'</br>';
			$this->insertArray( 'domelements' , $domNode->childNodes[$i]->nodeName.':'.$domNode->childNodes[$i]->nodeValue );
			
			if($domNode->childNodes[$i]->hasChildNodes()) {
				$this->showDOMNode($domNode->childNodes[$i] );
			}
		}    
	}
	
	private function clearLink( string $link ) : string
	{
		$clearSrc = explode( ' ' , $link );
		if( isset( $clearSrc[0] ) ){ $clearType = explode( ':' , $clearSrc[0] ); }
		if( isset( $clearType[2] ) ){ $clear = explode( '/' , $clearType[2] ); }
		
		return isset( $clear[2] ) ? $clearType[1] . $clear[0] .'://'. $clear[2] : '';
	}
	
	private function array_search_r( $element , $array )
	{
		foreach( $array as $index => $value )
		{
			if( $value[0] == $element ){ return $index; }
		}
		return FALSE;
	}
	
	private function findLink( string $node , string $linkNode )
	{
		$nodeElement = explode( ':', $node );
		$title = $this->checkString( $nodeElement[1] );
		
		if( $this->in_array_r( $title , $this->elements ) && $nodeElement[0] == '#text' )
		{
			$keyNumber = $this->array_search_r( $title , $this->elements );
			$this->elements[ $keyNumber ][1] = $this->clearLink( $linkNode );
		}
	}

	private function getTitles( DOMNode $pageDOM , int $page ) : void
	{
		$titles = $pageDOM->getElementsByTagName( 'h3' );
		$countTitles = count( $titles ) + count( $this->elements );
		$position = 0;

		for( $i = count( $this->elements ) ; $i < $countTitles ; $i++ )
		{
			$this->elements[$i] = [ $this->checkString( $titles[$position++]->nodeValue ) , '' ];
		}
	}
	
	private function getLinks( DOMNode $pageDOM , int $page ) : void
	{
		$this->showDOMNode( $pageDOM );
		
		for( $i = 0 ; $i < count( $this->domelements ) - 1 ; $i++ )
		{
			$this->findLink( $this->domelements[$i] , $this->domelements[$i+1] );
		}
	}
	
	public function decipher( array $phrase ) : void
	{
		for( $page = 0 ; $page < 2 ; $page++ )
		{
			$html = $this->getContent( $phrase , $page );
			$pageDOM = $this->getPageDOM( $html );

			$this->getTitles( $pageDOM , $page );
			$this->getLinks( $pageDOM , $page );
		}
		
		$this->addToDatabase( $phrase , $this->elements );
	}
	
	private function queryPhrase( string $phrase ) : string
	{
		global $query;
		$phraseQuery = $query->db( "SELECT `id` FROM `phrases` WHERE `name` = '". $phrase ."' LIMIT 1;" );
		return $phraseQuery[0]['id'];
	}
	
	private function addToDatabase( array $phrase , array $elements ) : void
	{
		global $query;
		
		$phraseID = $this->queryPhrase( $phrase['name'] );
		
		for( $i = 0 ; $i < count( $elements ) ; $i++ )
		{
			$standarizeURL = $elements[$i][1];
			$query->db( "INSERT INTO `urls` ( `phraseId` , `position` , `url` ) VALUES ( '". $phraseID ."', '". ( $i + 1 ) ."', '". $standarizeURL ."' );" );
		}
		
		$query->db( "UPDATE `phrases` SET `checked` = '1' WHERE `id` = ". $phraseID .";" );
	}
	
	public function showElements( ) : string
	{
		$main = '';
		
		for( $i = 0 ; $i < count( $this->elements ) ; $i++ )
		{
			$main.= $i .'. '. $this->elements[$i][0] .' >> '. $this->elements[$i][1] .'</br>';
		}
		
		return $main;
	}
	
	public function __get( string $name )
	{
		if( isset( $this->$name ) )
			return $this->$name;
		
		return false;
	}
}

?>