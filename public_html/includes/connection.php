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
	mysql_close($conec);
	unset($conec);
	}

?>	