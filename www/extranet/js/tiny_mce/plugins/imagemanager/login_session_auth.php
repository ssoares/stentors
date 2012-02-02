<?php

    session_start();
    
	if( isset( $_SESSION['Zend_Auth']) && !empty($_SESSION['Zend_Auth']['storage']) ){
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['user'] = $_SESSION['Zend_Auth']['storage'];
        header("location: " . $_REQUEST['return_url']);
        die;
    }

    $msg = 'Vous devez être authentifié pour accèder à cette fonctionnalité';
?>

<html>
<head>
<title>Tiny_mce</title>
<style>
body { font-family: Arial, Verdana; font-size: 11px; }
fieldset { display: block; width: 170px; }
legend { font-weight: bold; }
label { display: block; }
div { margin-bottom: 10px; }
div.last { margin: 0; }
div.container { position: absolute; top: 50%; left: 50%; margin: -100px 0 0 -85px; }
h1 { font-size: 14px; }
.button { border: 1px solid gray; font-family: Arial, Verdana; font-size: 11px; }
.error { color: red; margin: 0; margin-top: 10px; }
</style>
</head>
<body>

<div class="container">
	
<?php if ($msg) : ?>
	<div class="error">
		<?php echo $msg; ?>
	</div>
<?php endif; ?>
		
</div>

</body>
</html>
