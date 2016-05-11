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

    /**
     * Find all hashtags in the string and wrap them in the selected element
     * type and add the optional attributes.
     *
     * You can also add the tag name to the attribute value by adding {tag} to
     * to the value in the array.
     * 
     * If you wanted to wrap all tags in a span with a highlight class and a
     * data-tagname attribute set to "tag-tagname" then you would call:
     *
     *      $string = "String with #hashtags";
     *      $tagger = new HashTagger($string);
     *      $new_string = $tagger->wrap_tags(
     *          "span",
     *          array(
     *              "class" => "highlight",
     *              "data-tagname" => "tag-{tag}",
     *          )
     *      );
     *
     * @param $element_type The type of element to wrap the tag with (default is a span).
     * @param $attributes An array of attributes to add to this tag (this needs to be a multi-dimensional array).
     */
    public function wrap_tags($element_type = "span", $attributes = array())
    {
        if (!$this->original_string) {
            throw new HashTaggerIncorrectParamException("No string to parse");
        }

        if (!is_array($this->tags)) {
            throw new HashTaggerIncorrectParamException("'Tags' is not an array");
        }

        $string = $this->original_string;

        // Loop through tags and convert
        foreach($this->tags as $tag) {
            // Build the element we want to add
            $element = '<' . $element_type;
            
            if (count($attributes)) {
                foreach ($attributes as $key => $value) {
                    $element .= ' ' . $key . '="';
                    $element .= str_replace('{tag}', str_replace("#", "", $tag), $value);
                    $element .= '"';
                }
            }
            
            $element .= ">" . $tag . "</" . $element_type . ">";

            // Now replace the tags with the element
            $string = str_replace($tag, $element, $string);
        }

        $string;

        return $string;
    }
}
