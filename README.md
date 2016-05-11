# HashTagger hashtag parser

This is a simple class that is designed to extract hashtags from within a string.

## Installation

The prefered way to run this is via composer:

    # composer require i-lateral/hashtagger

## Usage

First you must instantiate the HashTagger class:

    use HashTagger\HashTagger

    $string = "String with #hashtags";
    $tagger = new HashTagger($string);

HashTagger will run an initial parsing of the provided string and cache the
extracted tags, these can be accessed via:

    $tagger->get_tags(); 

**NOTE** That each tag will be preceeded with the # symbol, EG:

    var_dump($tagger->get_tags());

Outputs:

    array(1) {
      [0] =>
      string(8) "#hashtag"
    }

You can add or remove tags from the final list via the add_tag and remove_tag
methods:

    $tagger->remove_tag("#hashtag");
    $tagger->add_tag("#newtag");

    var_dump($tagger->get_tags());

Outputs:

    array(1) {
      [0] =>
      string(8) "#newtag"
    }

## Wrapping/Converting tags into XML/HTML

You can convert tags in the string into HTML elements using the "wrap_tags"
method.

Wrappping all tags in a strong element:

        $new_string = $tagger->wrap_tags("strong");

You can also add custom attributes to these elements, EG wrapping all tags in a
strong element with the class "hashtag":

        $new_string = $tagger->wrap_tags(
            "strong",
            array("class" => "hashtag")
        );

Finally, you can customise the attributes value to use the tag name (for example
to build custom links) by adding the string {tag} to your attribute value, EG:

        $new_string = $tagger->wrap_tags(
            "a",
            array("href" => "http://site.com/tag/{tag}")
        );

The above will wrap the tag in an anchor element with a custom href specifically
for that tag.

## Future Development

This is intended as a proof of concept. In time it would be nice to add a method
to add html "a" tags to the provided string.
