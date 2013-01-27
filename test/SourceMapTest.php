<?php

require_once __DIR__ . "/../src/SourceMap.php";

class SourceMapTest extends PHPUnit_Framework_TestCase {


  public function testBasic() {
    $map = new SourceMap("out.js", array("in1.js", "in2.js"));

    $this->assertEquals(
      '{"version":3,"file":"out.js","sourceRoot":"","sources":["in1.js","in2.js"],"names":[],"mappings":""}',
      $map->generateJSON()
    );
  }
}
