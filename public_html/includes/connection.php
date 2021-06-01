<?php
	// LOCAL
	
	/*function conecta(){
		$conec = mysql_connect("localhost","root","") or die("Erro ao conectar com o banco de dados");		
		mysql_select_db("rinplay", $conec) or die("Erro ao selecionar base de dados");
		return $conec;
	}*/
	
	//EXTERNA
	
	function conecta(){
		$conec = new mysqli("us-cdbr-east-03.cleardb.com","b579fb33a9fe9a","431ec88b", "heroku_2fbedc967eed9e1" ) or die("Erro ao conectar com o banco de dados");
		return $conec;
	}

	function desconecta($conec){
	((is_null($___mysqli_res = mysqli_close($conec))) ? false : $___mysqli_res);
	unset($conec);
	}

	function mysqli_result($result, $number, $field=0) {
		
		mysqli_data_seek($result, $number); 
		$type = is_numeric($field) ? MYSQLI_NUM : MYSQLI_ASSOC; 
		$out = mysqli_fetch_array($result, $type);
		if ($out === NULL || $out === FALSE || (!isset($out[$field]))) {
			return FALSE; 
		} 
		return
		$out[$field]; 
	}
?>	