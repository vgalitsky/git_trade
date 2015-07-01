<pre>
<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once 'app/core/const.php';
require_once 'app/core/autoload.php';
require_once 'config.php';
require_once "app/app.php";

/** @var app $app */
$app = new app( $___config );

$app->run();
?>
<pre>
<?php
var_dump($app);