<?php

$nPathEnd = strpos( $_SERVER['SCRIPT_FILENAME'], '/admin/index.php' );
$sShopPath = substr( $_SERVER['SCRIPT_FILENAME'], 0, $nPathEnd );
include $sShopPath . '/modules/jxmods/jxgtaxo/application/views/admin/de/jxgtaxo_lang.php';

?>