<?php

// Richiede SimplePie!
// http://simplepie.org/
include_once ('/usr/share/php/simplepie/simplepie.inc');

$parser = new SimplePie ();
$parser->set_feed_url ('../feed-filter/news_linuxday.xml');
$parser->init ();
$parser->handle_content_type ();
if ($parser->error ())
        exit;

?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="it-IT">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"  />
</head>

<body style="font-family: Helvetica,Arial,sans-serif">
<?php

for ($items = $parser->get_items (), $i = 0; $i < 10 && $i < count ($items); $i++) {
	$item = $items [$i];

	?>

	<p style="margin: 0px; margin-top: 10px; padding: 0px; text-align: right; font-size: 25px">
		<a href="<?php echo $item->get_link () ?>"><?php echo $item->get_title () ?></a>
	</p>

	<div style="margin-left: 10px; margin-right: 10px; font-size: 12px">
		<?php echo $item->get_content () ?>
	</div>

	<div style="clear: both; width: 0px; height: 0px; margin: 0px; padding: 0px"></div>

	<?php
}

?>

</body>
</html>
