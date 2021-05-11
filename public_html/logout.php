<?php
session_start();
// Eliminar todas as vari&aacute;veis de sess&atilde;o.
//session_unset();
// Finalmente, destrui&ccedil;&atilde;o da sess&atilde;o.
//session_destroy();
$_SESSION['logado'] = "";
session_unregister(logado);
header("location: index.php");
?>