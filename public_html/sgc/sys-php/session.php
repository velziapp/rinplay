<?php 
session_start(); 
if($_SESSION['Id'] == 0){
	header("location: ../index.php");	
}

$pastaAtual = obterPasta();
$redir = false;
$cx = conecta();

$SQL = "SELECT P.codigo_modulo, M.modulo FROM rp_admin_permissoes P JOIN rp_modulos_admin M";
$SQL.= " ON P.codigo_modulo = M.codigo WHERE P.codigo_admin = ".$_SESSION['Id']." AND M.modulo = '".$pastaAtual."'";
$r = mysqli_query( $cx, $SQL);

if(mysqli_num_rows($r) == 0){	
	$redir = true;
}	
desconecta($cx);

if($redir){
	header("location: ../acesso_negado.php");
}
?>