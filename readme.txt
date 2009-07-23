=== Vote 2 Republish ===
Contributors: LeoGermani
Donate link: http://pirex.com.br/wordpress-plugins
Tags: republish, wpmu, vote, collaborative, community
Requires at least: 1.5
Tested up to: 2.8.2
Stable tag: 1.3

Wordpress MU Plugin: Adds a box in every post of every blog in the community. The post with a certains number of votes is republished into the "main blog"

== Description ==

Part of the RePublish set of tools for Wordpress MU.

Vote2Publish is a wpMU plugin wich allows you to define a "main blog", and then displays a vote box in every post of every other blog in the community.

When a post gets a certain number of votes it is automatically republished in the "main blog", preserving the original author and adding a note on the top of it with a link to where it was originally posted.

*Each blog owner can customize the vote box to match to his/her theme.. or even choose not to display it.

*Ajax powered. 
*Localization ready
*Comes with pt_BR translation

Note: Requires wp-xajax plugin.

== Installation ==

0. Install <a href="http://wordpress.org/extend/plugins/wp-xajax/">wp-xajax</a> plugin
1. Copy the files to your root mu-plugins folder, so you have a tree like:

.../wp-content/mu-plugins/vote_republish.php
.../wp-content/mu-plugins/vote2publish/bg-1.gif
.../wp-content/mu-plugins/vote2publish/...all the other files


== Usage ==

Go to Site Admin > Vote 2 Republish

. check the box to activate the plugin

. Define the "main blog", wich is the blog the posts should be republished to

. Set the number of votes a post must get to be republished

. Choose the category the posts will be republish into the "main blog"

. Choose wether you want to allow anonymous visitors to vote (controlled via cookie)

. Choose wether you want to allow only one vote per IP address when anonymous are voting

Now, whenever a user is logged in and visiting the blogs from the community, he/she will see a box for every post where they can vote. The vote count is updated right away.

The blog owners may go to Design > Vote Box layout and change wether they want to have this on their blogs or not. They also can change the colour and the aligmenr of the box.

== Screenshots ==

1. The plugin in action
2. The blog owner settings page

== ChangeLog ==

1.3 (23/07/09)
. Option to allow anonymous users to vote
. Option to choose in wich category the posts should be published

1.0 (06/06/2008)
. Released


