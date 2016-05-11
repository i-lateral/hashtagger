<?php

namespace HashTagger\Tests;

use HashTagger\HashTagger;

/**
 * Simple test that checks that Hashtagger will find tags, tags with underscores 
 * but not tags with hyphons (-).
 *
 * @package HashTagger
 * @author Mo <morven@ilateral.co.uk>
 */
class HashTaggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $str_tags = array();

    /**
     * @var array
     */
    private $html_tags = array();

    private $str_with_tags = "This is a string with a #hashtag, a #bad-hashtag and an #ok_hashtag";

    private $str_without_tags = "This string contains no hashtags";

    private $html_with_tags = "<p>This is a <strong>string</strong> with a #hashtag, a #bad-hashtag and an <em>#ok_hashtag</em></p>";

    private $good_hashtag = "#hashtag";

    private $ok_hashtag = "#ok_hashtag";

    private $bad_hashtag = "#bad-hashtag";

    public function setUp()
    {
        $tagger = new HashTagger($this->str_with_tags);
        $this->str_tags = $tagger->get_tags();

        $tagger = new HashTagger($this->html_with_tags);
        $this->html_tags = $tagger->get_tags();
    }

    public function testGetHashTagGood()
    {
        $this->assertContains($this->good_hashtag, $this->str_tags);
    }

    public function testGetHashTagOK()
    {
        $this->assertContains($this->ok_hashtag, $this->str_tags);
    }

    public function testGetHashTagBad()
    {
        $this->assertNotContains($this->bad_hashtag, $this->str_tags);
    }

    public function testGetHashTagHTMLGood()
    {
        $this->assertContains($this->good_hashtag, $this->html_tags);
    }

    public function testGetHashTagHTMLOK()
    {
        $this->assertContains($this->ok_hashtag, $this->html_tags);
    }

    public function testGetHashTagHTMLBad()
    {
        $this->assertNotContains($this->bad_hashtag, $this->html_tags);
    }

}
