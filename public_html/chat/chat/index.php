<?php

require_once dirname(__FILE__)."/src/phpfreechat.class.php";

$params["serverid"] = md5(__FILE__); // calculate a unique id for this chat
$params["language"] = "pt_BR";
$chat = new phpFreeChat( $params );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Rinplay.com Chat</title>

    <?php $chat->printJavascript(); ?>
    <?php $chat->printStyle(); ?>

  </head>

  <body>
    <?php $chat->printChat(); ?>

  </body>
</html>
