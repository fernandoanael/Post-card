=== Post Preview Card ===
Contributors: fernandoanael
Donate link: http://example.com/
Tags: post, preview, card, elementor, widget for elementor, beaver, siteorigin, page builder
Requires at least: 4.0.1
Tested up to: 4.8.0
Stable tag: 2.0.1
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Post Preview Card is a Plugin that adds 3 beatiful widgets which previews posts in card shape. Made to be used with Elementor, Beaver or SiteOrigin page builder.

== Description ==

This plugin was build to be used along with page builders.
Post Preview Card installs 3 widgets to help non-developers that want to use page builders to preview blog posts within static page builder's pages.
list of widgets:
*	Multiple Posts: This widget previews multiple posts and you can select how many widgets to be firstly displayed and then the user will load the rest of the posts by clicking in the "plus button" and the posts are going to be reloaded without the need of reloading the page, this is possible because this widget use AJAX to load the others posts, granting a better User Experience.
*	Single Post by ID: This widget preview the post with the ID you inserted. 
*	Random Post by Category: This widget preview a random post using the category you inserted.

= Beatiful Layout =
This plugin has 1 layout with two variations so far, both are responsive. We are working to deliver more layouts in the near future.
*	Post with thumbnail will be displayed in this order: Thumbnail, Title, Publish date and Category, Post excerpt or call text, read more button 
*	Post without thumbnail will be displayed in this order: Title with black background, Post excerpt or call text, read more button

Layout is customizable.

More layout options and full customization is being developed, so you'll be able to choose from optionals layouts, colors, default images for posts without featured images or complete remove the feature image.

== Easy to Use == 
= Peaw Single Post Preview =
  As the name says, this is a widget that preview a single post only. All you need to do is enter an ID
  1. Using a page builder, simply drag and drop the widget wherever you want. This is the widget name 
  2. The widget menu will be prompted. 
  all you need to do here is insert the Post id (Check FAQ to know where to find it), and the last thing is to insert a beatifull text to make people interested about your post. If call text is blank, it will be the Post Excerpt or first 85 char of post content 
  3. All the others fields are optional.
  4. That's it! Here an example of 3 Peaw widget together: https://tinyurl.com/m9hxqgl

= Peaw Random Post By Category =
  1. Using a page builder, simply drag and drop the widget wherever you want. This is the widget name  
  2. All you need to do is choose the Category you want. The call text here is the Post excerpt always.
  If you insert an invalid Category this will be the result ->https://tinyurl.com/lo5qbbd
  3. All the others fields are optional.
  4. That's it!

  If you're receiving the "Something is wrong" message it can be a lot of things but the most commom is that WordPress is not properly saving your widget and all you need to do is to change something in the widget form save, and then return the way it was and save again. This is a WP error, not a plugin error.

= Peaw Multiple Posts =
  1. Using a page builder, simply drag and drop the widget wherever you want. 
  2. The widget form will be prompted and you can choose how many posts to be displayed (999 posts will display ALL the blog posts), you can choose then, how many posts to be firstly displayed and how many posts per row.
  3. All the others fields are optional
  4. That's it! -> https://ibb.co/fdoWSk


== Installation ==

YOU MUST CHECK MANUALLY THE WIDGETS YOU WANT TO INSTALL IN: post-preview-card settings menu -> widget tab 

1. Upload the plugin files to the `/wp-content/plugins/Post-Preview-Card` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. And that's it! Simple as that. You can now use the widgets with your page builder.
4. Go to the Post Preview Card settings menu and make sure you have all the widgets installed.


== Frequently Asked Questions ==

= I cannot see my changes in the elementor live mode =
That's because Elementor is not rendering all styling files in the live mode and we can't change Elementor's code. But you should have no problem after saving the page in elementor and going to the real page.

= How can I know the Post id? =
This plugin adds the ID column in your "All Posts" section on the wordpress admin panel. You can deactivate this by going to the general settings page of the plugin.

= Installed the plugin and no widget were added? =
Go to the Post Preview Card settings and go to Widgets and check the widgets you want to be installed.

= I want more layouts =
We will be adding more layouts in the near future but if you want a personalized layout or even a personalized WP Template, you can contact me at fernandoanaelcabral@gmail.com 

= I am a developer and want to help =
This plugin is mantained by me and myself, so every help is apreciated, I created some internal API for better creating and registering widgets for the plugin, there are some layout related API as well. So it's very easy to grow this plugin. If you wish to help contact me 
at fernandoanaelcabral@gmail.com 

= What are the next updates? =
in priority order:
1. Bug fixes always come first 
2. New Layouts
3. A shortcode for this plugin
4. Work towards universal compatibility with others page builders.

== Screenshots ==

== Changelog ==

= 1.0 =
First Released Version

= 2.0 =
1. Multiple post widget added, faking a blog loop within page builders.
2. Internal code was re-made, making it faster and easier to grow this plugin, that's why a new version was released.
3. Original Layout added, responsive layout.
3. New customizable options added.

= 2.0.1 =
Bug fix: plugin wasn't working with others page builders but Elementor.
Now plugins is working and tested with:
1. [Elementor] (https://wordpress.org/plugins/elementor/)
2. [Page Builder by SiteOrigin] (https://siteorigin.com/)
3. [Beaver Builder Plugin](https://www.wpbeaverbuilder.com/home/?utm_expid=82857025-13.-Mrg6iFCTMyN1GHrIwH9wQ.1&utm_medium=bb&utm_source=plugins-admin-page&utm_campaign=plugins-admin-author&utm_referrer=http%3A%2F%2Flocalhost%2Fwp%2FPluginDevWorkspace%2Fwp-admin%2Fplugins.php) 
