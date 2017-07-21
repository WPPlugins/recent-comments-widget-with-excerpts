=== Recent comments widget with excerpts ===
Contributors: salzano
Donate link: http://www.tacticaltechnique.com/donate/
Tags: recent comments, recent comment excerpts, comment excerpts, recent comments widget, default widgets
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 0.111017

Duplicates the built-in Recent Comments widget and adds functionality to display comment excerpts instead of post titles

== Description ==

This plugin creates a widget similar to the default recent comments widget. Instead of the format "username on post title," the widget will display "username said comment excerpt."

Choose whether or not to include admin user comments.

Customize the character length of the comment excerpt.

== Installation ==

1. Download recent-comments-widget-with-excerpts.zip
2. Decompress the file contents
3. Upload the recent-comments-widget-with-excerpts folder to a Wordpress plugins directory (/wp-content/plugins)
4. Activate the plugin from the Administration Dashboard
5. Open the Widgets page under the Appearance section
6. Drag the widget to an active sidebar

== Frequently Asked Questions ==

= Need help? Have a suggestion? =
[Visit this plugin's home page](http://www.tacticaltechnique.com/wordpress/recent-comments-widget-with-excerpts/)

== Screenshots ==

1. Sample output

== Change Log ==

= 0.111017 =
* Fixed a bug that broke the options

= 0.111012 =
* Allow up to 150 comments instead of 15
* Now using mb_substr instead of substr (for international characters)
* Apply comment filters and strip tags to comment output

= 0.110223 =
* Choose whether or not to include comments by the admin user

= 0.110221 =
* Added an option to control the maximum length of comment excerpts
* Stop showing ellipsis if the comment length is not long enough to be trimmed by the widget

= 0.101109 =
* First build

== Upgrade Notice ==

= 0.111017 = 
I fixed a bug that prevented the options from functioning properly. This problem was pointed out on my blog by Elaine. Thank you, Elaine.

= 0.111012 = 
I introduced fixes and updates that have been provided by the users of this plugin. These fixes work better with unicode/world wide character sets, other plugins that affect comment output and comments that contain HTML tags. Thank you Mike C, Pointbre and Eduardo for your help.

= 0.110223 =
This version includes a new option to include or exclude comments made by the admin user. This feature was requested by Christine C.

= 0.110221 =
The length of comment excerpts can now be changed in the widget options. To achieve this additonal option, I had to alter the widget name. This means updating to this new version of the plugin will remove and require you to add the widget to your widget area again. There is simply no way for me to add functionality without causing this incovenience.

= 0.101109 =
First build
