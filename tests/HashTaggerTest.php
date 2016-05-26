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
     * @var HashTagger
     */
    private $html_tagger;
 
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

    private $html_with_tags = "<p>This is a <strong>string</strong> with a &#39; special character, #hashtag, a #bad-hashtag and an <em>#ok_hashtag</em></p>";

    private $html_character = "#39";

    private $good_hashtag = "#hashtag";

    private $ok_hashtag = "#ok_hashtag";

    private $bad_hashtag = "#bad-hashtag";

    public function setUp()
    {
        $tagger = new HashTagger($this->str_with_tags);
        $this->str_tags = $tagger->get_tags();

        $this->html_tagger = new HashTagger($this->html_with_tags);
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

    public function testGetHashTagSpecialCharacter()
    {
        $this->assertNotContains($this->html_character, $this->html_tags);
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

    /**
     * Test wrapping a tag in a span (default)
     */
    public function testWrapTags()
    {
        $html = $this
            ->html_tagger
            ->wrap_tags();
                
        $this->assertContains('<span>#hashtag</span>', $html);
    }

    /**
     * Test wrapping a tag in a span with an attribute
     */
    public function testWrapTagsAttribute()
    {
        $html = $this
            ->html_tagger
            ->wrap_tags("span", array("class" => "hashtag"));
                
        $this->assertContains('<span class="hashtag">#hashtag</span>', $html);
    }

    /**
     * Test wrapping a tag in an a with a href using the tag name
     */
    public function testWrapTagsAnchor()
    {
        $html = $this
            ->html_tagger
            ->wrap_tags("a", array("href" => "http://site.com/link/to/{tag}"));
                
        $this->assertContains('<a href="http://site.com/link/to/hashtag">#hashtag</a>', $html);
    }
}
