<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="javad soltani - javad.soli20@gmail.com" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= APP_PUBLIC; ?>images/favicon.png" />
    <title><?=(isset($title)) ? $title : \core\jsConfig::get('APP_NAME'); ?></title>

	<link rel="author" href="https://plus.google.com/109574656633252888054?rel=author"/>
	<link href="https://plus.google.com/109574656633252888054" rel="publisher" />

    <!-- ADDED CSS AND JS INTO HERE -->

    <?php
        if(isset($header_css) and is_array($header_css)){
            foreach($header_css as $cssLink){
                echo '<link href="' . $cssLink . '" rel="stylesheet" />';
            }
        }
	?>
</head>

<body>
