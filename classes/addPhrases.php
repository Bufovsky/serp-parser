<?PHP

class AddPhrases
{
	private function drawTextarea( string $name , string $placeholder = NULL ) : string
	{
		$placeholderValue = isset( $placeholder ) ? "placeholder='". $placeholder ."'" : NULL;
		return "<textarea name='". $name ."' rows='3' cols='3' class='form-control' ". $placeholderValue ."></textarea>";
	}
	
	private function drawInput( string $name , string $type , string $placeholder = NULL , string $value = NULL ) : string
	{
		$val = isset( $value ) ? $value : NULL;
		return "<input name='". $name ."' class='btn btn-primary' type='". $type ."' ". $val ."/>";
	}
	
	private function checkIsset( $input )
	{
		return isset( $_POST[ $input ] ) ? $_POST[ $input ] : NULL;
	}
	
	public function checkForm( string $name )
	{
		global $query;
		
		if( isset( $_POST[ $name ] ) )
		{
			$phrases = $this->checkIsset( 'phrases' );

			if( !empty( $phrases ) )
			{
				$phrasesArray = explode( PHP_EOL , $phrases );
				
				foreach( $phrasesArray as $phrase )
				{
					$query->db( "INSERT INTO `phrases` ( `name` , `checked` ) VALUES ( '". $phrase ."' , '0' );" );
				}
				
				return 'Dodano frazy.';
			}
		}
		
		return false;
	}
	
	public function drawForm( ) : string
	{
		$main = "<form action='". URL ."' method='post'>";
		$main.= $this->drawTextarea( 'phrases' , 'Wprowadź frazy oddzielone Enterem' ) .'</br>';
		$main.= $this->drawInput( 'addPhrases' , 'submit' , NULL , 'Dodaj Frazy' );
		$main.= '</form>';
		
		return $main;
	}
}

?>