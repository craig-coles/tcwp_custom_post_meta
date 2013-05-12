This is a super simple class for making custom post meta boxes for your wordpress posts, pages or what have you.
So if you just want to get stuff done instead of learning how to install a wp plugin, use the wp plugin etc. 
And you are familiar with wordpress and PHP oop methods than this class is for you!

*NOTES: currently this only works on text inputs, but I am working on it so that soon it will be all form inputs

USAGE:

(this is a class so you need to include it in your page some where before the following)

$params = array(
		"id" => "awesome_sauce",
		"title" => "Awesome Sauce",
		"context" =>"advanced",
		"label" =>"add some custom stuff",
		"priority" => "default",
		"callback_args"=>array('someCoolStuff'=>"the cool stuff")
		
	);
	
$customPostMeta = new TCWPCustomPostMeta($params);

done! now on your posts you will see a custom post type at the bottom with the title "Awesome Sauce"

also a very big hat tip to Justin Tadlock and smashing magazine

I based a lot of this of this article

http://wp.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/

you can map the params back to the wordpress add_meta_box function directly

http://codex.wordpress.org/Function_Reference/add_meta_box

happy coding.