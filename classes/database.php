<?PHP

class Query
{
	public function db( string $sql )
	{
		global $db;
		
		try
		{
			$query = $db->query( $sql )->fetchAll();
		}
		catch (Exception $e)
		{
			echo 'INFO: ' . $e->getMessage();
		}

		return $query;
	}
}

?>