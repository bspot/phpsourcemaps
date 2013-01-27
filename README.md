# PHP Source Maps

(Note that all of this is quick and dirty.)

Generate [source maps][1] using PHP.


## Usage

Include:

    require "SourceMap.php";

Create a new map, giving the name of the file the map is for, as well as the source files:

    $map = new SourceMap("outfile.js", array("infile1.js", infile2.js"));

Add mappings:

    $map->mappings[] = array(
      'dest_line' => 0, // Line in the compiled file
      'dest_col' => 0, // Column in the compiled file
      'src_index' => 0, // Index of the source file
      'src_line' => 0, // Line in the source file
      'src_col' => 0, // Column in the source file
    );

Get JSON representation of the source map:

    $json = $map->generateJSON();




  [1]: http://http://code.google.com/p/closure-compiler/wiki/SourceMaps
