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
    private $str_tagger;

   /**
     * @var array
     */
    private $str_tags = array();

    /**
     * @var HashTagger
     */
    private $html_tagger;

    /**
     * @var array
     */
    private $html_tags = array();

    private $str_with_tags = "This is a string with a #hashtag, a #bad-hashtag and an #ok_hashtag and a link http://www.ilateral.co.uk/work/#linktag";

    private $str_without_tags = "This string contains no hashtags";

    private $html_with_tags = '<p>This is a <strong>string</strong> with a &#39; special character, #hashtag, a #bad-hashtag, an <em>#ok_hashtag</em> and a <a href="http://www.ilateral.co.uk/work/#linktag">link</a></p>';

    private $html_character = "#39";

    private $link_hashtag = "#linktag";

    private $good_hashtag = "#hashtag";

    private $ok_hashtag = "#ok_hashtag";

    private $bad_hashtag = "#bad-hashtag";

    public function setUp()
    {
        $tagger = new HashTagger($this->str_with_tags);
        $this->str_tagger = $tagger;
        $this->str_tags = $tagger->get_tags();

        $html_tagger = new HashTagger($this->html_with_tags);
        $this->html_tagger = $html_tagger;
        $this->html_tags = $html_tagger->get_tags();
    }

    /**
     * Does hashtagger find a "standard" tag with only alpha numeric
     * characters in strings.
     */
    public function testGetHashTagGood()
    {
        $this->assertContains($this->good_hashtag, $this->str_tags);
    }

    /**
     * Does hashtagger include underscores (_) from strings?
     */
    public function testGetHashTagOK()
    {
        $this->assertContains($this->ok_hashtag, $this->str_tags);
    }

    /**
     * Does hashtagger ignore hyphens (-)?
     */
    public function testGetHashTagBad()
    {
        $this->assertNotContains($this->bad_hashtag, $this->str_tags);
    }
    
    /**
     * Does hashtagger ignore urls with a # in them?
     */
    public function testGetHashTagLink()
    {   
        $this->assertNotContains($this->link_hashtag, $this->str_tags);
    }

    /**
     * Does hashtagger ignore special characters that use a #?
     */
    public function testGetHashTagSpecialCharacter()
    {
        $this->assertNotContains($this->html_character, $this->html_tags);
    }

    /**
     * Does hashtagger find tags in HTML?
     */
    public function testGetHashTagHTMLGood()
    {
        $this->assertContains($this->good_hashtag, $this->html_tags);
    }

    /**
     * Does hashtagger include underscores (_) in HTML?
     */
    public function testGetHashTagHTMLOK()
    {
        $this->assertContains($this->ok_hashtag, $this->html_tags);
    }


    /**
     * Does hashtagger ignore hyphens (-) in HTML?
     */
    public function testGetHashTagHTMLBad()
    {
        $this->assertNotContains($this->bad_hashtag, $this->html_tags);
    }

    /**
     * Does hashtagger ignore links with a # in them in HTML?
     */
    public function testGetHashTagHTMLLink()
    {   
        $this->assertNotContains($this->link_hashtag, $this->html_tags);
    }

    /**
     * Test wrapping a tag in a span (default)
     */
    public function testWrapTags()
    {
        $html = $this
            ->html_tagger
            ->wrap_tags();
                
        $this->assertContains("<span>$this->good_hashtag</span>", $html);
    }

    /**
     * Test wrapping a tag in a span with an attribute
     */
    public function testWrapTagsAttribute()
    {
        $html = $this
            ->html_tagger
            ->wrap_tags("span", array("class" => "hashtag"));
                
        $this->assertContains('<span class="hashtag">' . $this->good_hashtag . '</span>', $html);
    }

    /**
     * Test wrapping a tag in an a with a href using the tag name
     */
    public function testWrapTagsAnchor()
    {
        $html = $this
            ->html_tagger
            ->wrap_tags("a", array("href" => "http://site.com/link/to/{tag}"));
                
        $this->assertContains('<a href="http://site.com/link/to/hashtag">' . $this->good_hashtag . '</a>', $html);
    }
}
