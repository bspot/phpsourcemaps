<?php
$scripts = array('script1.js', 'script2.js');

$scriptinfo = array();
foreach ($scripts as $i => $script) {
  $scriptinfo[$script]['index'] = $i;
  $data = file_get_contents($script);
  $scriptinfo[$script]['data'] = $data;
  $scriptinfo[$script]['lines'] = count(explode("\n", $data));
}

header('Content-Type: text/javascript');
header('X-SourceMap: delivermap.php');

foreach ($scriptinfo as $script) {
  echo $script['data'];
}
