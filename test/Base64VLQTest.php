<?php

require_once __DIR__ . "/../src/SourceMap.php";

class Base64VLQTest extends PHPUnit_Framework_TestCase {

  public static $B64STR = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

  /**
   * Test two-complement to funny sign encoding conversion.
   */
  public function testToVLQSigned() {
    $this->assertEquals((int)0x00000000, Base64VLQ::toVLQSigned(0));
    $this->assertEquals((int)0xfffffffe, Base64VLQ::toVLQSigned(2147483647));
    $this->assertEquals((int)0x00000001, Base64VLQ::toVLQSigned(-2147483648));
    $this->assertEquals((int)0x00000003, Base64VLQ::toVLQSigned(-1));

    $this->assertEquals((int)0x7fffffff, Base64VLQ::toVLQSigned(-1073741823));
    $this->assertEquals((int)0x80000000, Base64VLQ::toVLQSigned(1073741824));
    $this->assertEquals((int)0xffffffff, Base64VLQ::toVLQSigned(-2147483647));

    $this->assertEquals((int)0x00000004, Base64VLQ::toVLQSigned(2));
    $this->assertEquals((int)0x00000005, Base64VLQ::toVLQSigned(-2));
  }

  /**
   * Test funny sign encoding to two-complement conversion.
   */
  public function testFromVLQSigned() {
    $this->assertEquals(0,           Base64VLQ::fromVLQSigned((int)0x00000000));
    $this->assertEquals(2147483647,  Base64VLQ::fromVLQSigned((int)0xfffffffe));
    $this->assertEquals(-2147483648, Base64VLQ::fromVLQSigned((int)0x00000001));
    $this->assertEquals(-1,          Base64VLQ::fromVLQSigned((int)0x00000003));

    $this->assertEquals(-1073741823, Base64VLQ::fromVLQSigned((int)0x7fffffff));
    $this->assertEquals(1073741824,  Base64VLQ::fromVLQSigned((int)0x80000000));
    $this->assertEquals(-2147483647, Base64VLQ::fromVLQSigned((int)0xffffffff));
  }

  /**
   * Test base64 encoding of valid digits (i.e. should work).
   *
   * @dataProvider provideValidBase64
   */
  public function testBase64EncodeValid($char, $number) {
    $this->assertEquals($char, Base64VLQ::base64Encode($number));
  }

  /**
   * Test base64 encoding of invalid digits (i.e. should throw).
   *
   * @dataProvider provideInvalidBase64
   */
  public function testBase64EncodeInvalid($char, $number) {
    $this->setExpectedException("Exception", "Must be between 0 and 63: " . $number);
    Base64VLQ::base64Encode($number);
  }

  /**
   * Test base64 decoding of valid chars (i.e. should work).
   *
   * @dataProvider provideValidBase64
   */
  public function testBase64DecodeValid($char, $number) {
    $this->assertEquals($number, Base64VLQ::base64Decode($char));
  }

  /**
   * Test base64 decoding of invalid chars (i.e. should throw).
   *
   * @dataProvider provideInvalidBase64
   */
  public function testBase64DecodeInvalid($char, $number) {
    $this->setExpectedException("Exception", "Not a valid base 64 digit: " . $char);
    Base64VLQ::base64Decode($char);
  }

  /**
   * Provide valid Base64 digits / chars.
   *
   * @return array
   */
  public function provideValidBase64() {
    $tuples = array();
    foreach (str_split(self::$B64STR) as $i => $char) {
      $tuples[] = array($char, $i);
    }
    return $tuples;
  }

  /**
   * Provide invalid Base64 digits / chars.
   *
   * @return array
   */
  public function provideInvalidBase64() {
    return array(
      array('"', -1),
      array('!', 64)
    );
  }

  /**
   * Test Base64 VLQ encoding.
   *
   * @dataProvider provideBase64VLQ
   */
  public function testEncode($number, $enc) {
    $this->assertEquals($enc, Base64VLQ::encode($number));
  }

  /**
   * Test Base64 VLQ decoding.
   *
   * @dataProvider provideBase64VLQ
   */
  public function testDecode($number, $enc) {
    $this->assertEquals($number, Base64VLQ::decode($enc));
  }

  /**
   * Provide number / Base64 VLQ encoded string pairs.
   *
   * @return array
   */
  public function provideBase64VLQ() {
    return array(
      array(0, 'A'),
      array(1, 'C'),
      array(2, 'E'),
      array(4, 'I'),
      array(8, 'Q'),
      array(16, 'gB'),
      array(32, 'gC'),
      array(64, 'gE'),
      array(128, 'gI'),
      array(256, 'gQ'),
      array(512, 'ggB'),
      array(1024, 'ggC'),
      array(2048, 'ggE'),
      array(4096, 'ggI'),
      array(8192, 'ggQ'),
      array(16384, 'gggB'),
      array(32768, 'gggC'),
      array(65536, 'gggE'),
      array(131072, 'gggI'),
      array(262144, 'gggQ'),
      array(524288, 'ggggB'),
      array(1048576, 'ggggC'),
      array(2097152, 'ggggE'),
      array(4194304, 'ggggI'),
      array(8388608, 'ggggQ'),
      array(16777216, 'gggggB'),
      array(33554432, 'gggggC'),
      array(67108864, 'gggggE'),
      array(134217728, 'gggggI'),
      array(268435456, 'gggggQ'),
      array(536870912, 'ggggggB'),
      array(1073741824, 'ggggggC'),
      array(2147483647, '+/////D'),
    );
  }
}
