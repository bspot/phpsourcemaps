<?php
require_once __DIR__ . "/../src/SourceMap.php";

$scripts = array('script1.js', 'script2.js');

$scriptinfo = array();
foreach ($scripts as $i => $script) {
  $scriptinfo[$script]['index'] = $i;
  $data = file_get_contents($script);
  $scriptinfo[$script]['data'] = $data;
  $scriptinfo[$script]['lines'] = count(explode("\n", $data));
}


$map = new SourceMap('deliverscript.php', $scripts);

$offset = 0;
foreach ($scriptinfo as $script => $info) {
  //print "  " . $script . ": " . $offset . "\n";

  for ($i=0; $i<$info['lines']; ++$i) {
    $map->mappings[] = array('dest_line' => $offset+$i, 'dest_col' => 0,  'src_index' => $info['index'], 'src_line' => $i, 'src_col' => 0);
  }

  $offset += $info['lines']-1;
}

print $map->generateJSON();
