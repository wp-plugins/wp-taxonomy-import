=== WP Taxonomy Import ===
Contributors: Nakashima Masahiro
Tags: category, taxonomy, import 
Requires at least: 3.0 or higher
Tested up to: 4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a plugin allowing the user to create large amount of taxonomies.

== Description ==

This plugin based on "Batch categories import" https://wordpress.org/plugins/batch-category-import/.


== Installation ==

This section describes how to install the plugin and get it working.

1. Make a new directory inside `/wp-content/plugins/` named `wp-taxonomy-import`
2. Upload all files into `/wp-content/plugins/wp-taxonomy-import/` directory
3. The control panel of WP Taxonomy Import is in 'Tools > Taxonomy Import'.

== Frequently Asked Questions ==

= How do I create taxnomies? =

Tools > Taxonomy Import,
In the text area create taxnomies separated by a new line, e.g. :

* Category A
* Category B
* Category C

= What about nested taxnomies? =

Great question!
In the same text area, you can create child taxnomies as well!
Each Taxonomy who has a parent is written with a '->' separating parent from child, e.g. :

* Category A -> Category B -> Category C

And for massive assignment, e.g. :

* Category A -> Category B
* Category B -> Category C
* Category B -> Category D
* Category A -> Category E -> Category F
* Category F -> Category H

wasn't that easy?

= What slug is being set for each of my categories? =

Well, the default slug is the category name with hyphens instead of white spaces and all lowercase.

= What if I would like to change the slug? =

No problem!
When setting a category just add the dollar-sign '$' delimiter after the category name and your new slug!
e.g. :

* Category A$foo -> Category B$bar -> Category C$cool-isnt-it

= What about adding descriptions? =

Easy, just add another dollar-sign '$' delimiter after the category slug and wrap your description with double-quotes "". e.g. :
(Don't forget to escape double-quotes inside your description!)

* Category A$foo$"this is bar!"

= And if I don't like your silly dollar-sign delimiter? =

Just change it!
Just before the text area there's a small input bar that you can put whatever delimiter you want.
Notice: currently it is impossible to use the '->' sign as a delimiter.

== Screenshots ==

== Changelog ==

