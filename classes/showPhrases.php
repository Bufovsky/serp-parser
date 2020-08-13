<?PHP

class showPhrases
{
	protected $complete = [ '<span class="badge badge-danger">NIE</span>' , '<span class="badge badge-success">TAK</span>' ];
	
	public function getDbQuery( string $table = 'phrases' , string $phraseID = NULL ) : array
	{
		global $query;
		
		$row = [ 'phrases' => [ 'id' , 'DESC' ] , 'urls' => [ 'phraseID' , 'ASC' ] , 'emails' => [ 'urlID' , 'ASC LIMIT 1' ] ];
		$setPhrase = isset( $phraseID ) ? "WHERE `". $row[ $table ][0] ."` = '". $phraseID ."'" : NULL;
		
		$phrases = $query->db( "SELECT * FROM `". $table ."` ". $setPhrase ." ORDER BY `id` ". $row[ $table ][1] .";" );
		
		return $phrases;
	}
	
	private function phrasesElements( ) : string
	{
		$phrases = $this->getDbQuery( );
		$main = '';
		
		foreach( $phrases as $phrase )
		{
			$main.= '<tr><td><a href="'. URL .'/'. $phrase["id"] .'">'. $phrase["name"] .'</a></td><td>'. $this->complete[ $this->checkComplite( $phrase["id"] ) ] .'</td><td><a href="'. URL .'/'. $phrase["id"] .'/download">Pobierz CSV</a></td></tr>';
		}

		return $main;
	}
	
	private function checkComplite( string $phraseID , int $urlID = NULL ) : bool
	{
		global $query;
		
		$setUrlID = isset( $urlID ) ? 'AND `id` = "'. $urlID .'" LIMIT 1' : NULL;
		
		$getElements = $query->db( "SELECT COUNT(*) FROM `urls` WHERE `phraseID` = '". $phraseID ."' ". $setUrlID .";" );
		$getElementsComplete = $query->db( "SELECT COUNT(*) FROM `urls` WHERE `phraseID` = '". $phraseID ."' AND `checked` = '1' ". $setUrlID .";" );
		
		return $getElements == $getElementsComplete ? TRUE : FALSE;
	}
	
	public function drawPhrases( ) : string
	{
		$main = '<div class="table-responsive">
					<table class="table">
						<thead>
							<tr class="table-active">
								<th>Fraza</th>
								<th>Sprawdzono</th>
								<th>Pobierz CSV</th>
							</tr>
						</thead>
						<tbody>
							'. $this->phrasesElements(  ) .'
						</tbody>
					</table>
				</div>';
				
		return $main;
	}
	
	private function getEmail( int $urlID ) : string
	{
		global $query;
		
		$email = $this->getDbQuery( 'emails' , $urlID );
		
		return !empty( $email ) ? $email[0]['email'] : '';
	}
	
	private function phrasesURL( ) : string
	{
		$urls = $this->getDbQuery( 'urls' );
		$main = '';
		
		foreach( $urls as $url )
		{
			$main.= '<tr><td>'. $url['position'] .'</td><td>'. $url['url'] .'</td><td>'. $this->getEmail( $url['id'] ) .'</td><td>'. $this->complete[ $this->checkComplite( $url["phraseId"] , $url["id"] ) ] .'</td></tr>';
		}

		return $main;
	}
	
	public function drawURLS( ) : string
	{
		$main = '<div class="table-responsive">
					<table class="table">
						<thead>
							<tr class="table-active">
								<th>Pozycja</th>
								<th>Adres URL</th>
								<th>Email</th>
								<th>Sprawdzono</th>
							</tr>
						</thead>
						<tbody>
							'. $this->phrasesURL(  ) .'
						</tbody>
					</table>
				</div>';
				
		return $main;
	}
	
	public function generateCsvArray( int $phraseID ) : array
	{
		$urls = $this->getDbQuery( 'urls' , $phraseID );
		$csvArray = [];
		$phraseName = $this->getDbQuery( 'phrases' , $phraseID )[0]['name'];
		
		foreach( $urls as $url )
		{
			array_push( $csvArray , [ $phraseName , $url['position'] , $url['url'] , $this->getEmail( $url['id'] ) ] );
		}
		
		return $csvArray;
	}
	
	public function generateCsv( array $data , string $name ) : void
	{
		$fp = fopen( 'download/'. $name .'.csv' , 'w' ); 

		foreach ( $data as $fields ) { 
			fputcsv( $fp , $fields , ';' ); 
		} 
		  
		fclose( $fp ); 
		
		header( 'Content-Encoding: UTF-8' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: text/csv charset=utf-8' );
		header( 'Content-Disposition: attachment; filename='. $name .'.csv' );
		ob_clean();
		flush();
		readfile( 'download/'. $name .'.csv' );
		exit;
		header( 'Location: '. URL .'/showPhrases' );

	}
}

?>