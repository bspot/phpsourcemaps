<?php

require_once __DIR__ . "/Base64VLQ.php";

/**
 * Generate source maps
 *
 * @author bspot
 */
class SourceMap {

  public function __construct($out_file, $source_files) {
    $this->out_file = $out_file;
    $this->source_files = $source_files;

    $this->mappings = array();
  }

  public function generateJSON() {

    return json_encode(array(
      "version" => 3,
      "file" => $this->out_file,
      "sourceRoot" => "",
      "sources" => $this->source_files,
      "names" => array(),
      "mappings" => $this->generateMappings()
    ));
  }

  public function generateMappings() {

    // Group mappings by dest line number.
    $grouped_map = array();
    foreach ($this->mappings as $m) {
      $grouped_map[$m['dest_line']][] = $m;
    }

    ksort($grouped_map);

    $grouped_map_enc = array();

    $last_dest_line = 0;
    $last_src_index = 0;
    $last_src_line = 0;
    $last_src_col = 0;
    foreach ($grouped_map as $dest_line => $line_map) {
      while (++$last_dest_line < $dest_line) {
        $grouped_map_enc[] = ";";
      }

      $line_map_enc = array();
      $last_dest_col = 0;

      foreach ($line_map as $m) {
        $m_enc = Base64VLQ::encode($m['dest_col'] - $last_dest_col);
        $last_dest_col = $m['dest_col'];
        if (isset($m['src_index'])) {
          $m_enc .= Base64VLQ::encode($m['src_index'] - $last_src_index);
          $last_src_index = $m['src_index'];

          $m_enc .= Base64VLQ::encode($m['src_line'] - $last_src_line);
          $last_src_line = $m['src_line'];

          $m_enc .= Base64VLQ::encode($m['src_col'] - $last_src_col);
          $last_src_col = $m['src_col'];
        }
        $line_map_enc[] = $m_enc;
      }

      $grouped_map_enc[] = implode(",", $line_map_enc) . ";";
    }

    $grouped_map_enc = implode($grouped_map_enc);

    return $grouped_map_enc;
  }
};
