<?php
	// LOCAL
	
	/*function conecta(){
		$conec = mysql_connect("localhost","root","") or die("Erro ao conectar com o banco de dados");		
		mysql_select_db("rinplay", $conec) or die("Erro ao selecionar base de dados");
		return $conec;
	}*/
	
	//EXTERNA
	
	function conecta(){
		$conec = mysql_connect("rinplay.mysql.dbaas.com.br","rinplay","R1i2n3p4@") or die("Erro ao conectar com o banco de dados");
		mysql_select_db("rinplay", $conec) or die("Erro ao selecionar base de dados");
		return $conec;
	}

	function desconecta($conec){
	mysql_close($conec);
	unset($conec);
	}

?>