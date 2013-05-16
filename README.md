THIS IS A WORDPRESS custom post meta class.

*NOTES: currently this class only works with text inputs and checkboxes, but I am working on it so that soon it will be all form inputs

USAGE:
(this is a class so you need to include it in your page somewhere before you instantiate it)

example (add this code to functions.php)

include('TCWPCustomPostMeta.php');

$params = array(
		"id" => "awesome_sauce",
		"title" => "Awesome Sauce",
		"context" =>"advanced",
		"before_save_filter" => "sanitize_html_class",
		"label" =>"add some custom stuff",
		"priority" => "default",
		"callback_args"=>array('someCoolStuff'=>"the cool stuff")
		
);
	
$customPostMeta = new TCWPCustomPostMeta($params);

Done! 

Now on your posts you will see a custom post type at the bottom with the title "Awesome Sauce"

also a very big hat tip to Justin Tadlock and smashing magazine

I based a lot of this on this article

http://wp.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/

you can map the params back to the wordpress add_meta_box function

http://codex.wordpress.org/Function_Reference/add_meta_box

happy coding.

*Update*

- added a customizable 'before_save_filter' so you can pass in a function that will receive the post meta value to sanitize before saving