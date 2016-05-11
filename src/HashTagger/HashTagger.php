<?php

namespace HashTagger;

use HashTagger\Exceptions\HashTaggerException;
use HashTagger\Exceptions\HashTaggerIncorrectParamException;

/**
 * Simple library to extract hash tags from a string or convert a string with
 * hashtags to be wrapped with a tags instead.
 *
 * @author Mo <morven@ilateral.co.uk>
 * @package HashTagger
 */
class HashTagger
{

    /**
     * String that needs processing
     * 
     * @var string
     */
    private $original_string;

    /**
     * Getter for original_string
     *
     * @return String
     */
    public function get_original_string()
    {
        return $this->original_string;
    }

    /**
     * Setter for original_string
     *
     * @param String
     * @return HashTagger
     */
    public function set_original_string($string)
    {
        $this->original_string = $string;
        return $this;
    }

    /**
     * Array of tags found in the string we are querying
     *
     * @var array
     */
    private $tags = array();

    /**
     * Getter for tags
     *
     * @return Arrag
     */
    public function get_tags()
    {
        return $this->tags;
    }

    /**
     * Add a tag to the list
     *
     * @param String
     * @return HashTagger
     */
    public function add_tag($string)
    {
        $this->tags[] = $string;
        return $this;
    }

    /**
     * Remove a tag from the list
     *
     * @param String
     * @return HashTagger
     */
    public function remove_tag($string)
    {
        for ($x = 0; $x < count($this->tags); $x++) {
            if ($this->tags[$x] == $string) {
                unset($this->tags[$x]);
                // Reset array keys
                $this->tags = array_values($this->tags);
            }
        }
        return $this;
    }


    /**
     * Constructor for this class, accespts the string we want to use.
     *
     */
    public function __construct($string)
    {
        $this->original_string = $string;

        // Run an initial parsing
        $this->extract();
    }

    /**
     * Get all hash tags from the set string
     *
     */
    public function extract()
    {
        if (!$this->original_string) {
            throw new HashTaggerIncorrectParamException("No string to parse");
        }

        preg_match_all("/(#\w+)/", $this->original_string, $results);

        if (is_array($results) && is_array($results[0])) {
            $this->tags = $results[0];
        } elseif(is_array($results)) {
            $this->tags = $results;
        }

        return $this->tags;
    }
}
