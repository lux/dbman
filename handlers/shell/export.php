<?php

$this->require_admin ();

$f = new Form ('post', $this);
if (! $f->verify_csrf ()) {
	header ('Location: /admin');
	exit;
}

if (! isset ($_POST['query'])) {
	header ('Location: /admin');
	exit;
}

$page->layout = false;
header ('Cache-control: private');
header ('Content-Type: text/plain');
header ('Content-Disposition: attachment; filename=query-export-' . gmdate ('Y-m-d') . '.csv');

$res = DB::fetch ($_POST['query']);
echo preg_replace ('/[\r\n]+/', ' ', $_POST['query']) . "\n";
if (count ($res) > 0) {
	echo join (',', array_keys ((array) $res[0])) . "\n";
}

foreach ($res as $row) {
	$sep = '';
	foreach ((array) $row as $k => $v) {
		$v = str_replace ('"', '""', $v);
		if (strpos ($v, ',') !== false) {
			$v = '"' . $v . '"';
		}
		$v = str_replace (array ("\n", "\r"), array ('\\n', '\\r'), $v);
		echo $sep . $v;
		$sep = ',';
	}
	echo "\n";
}

?>