# Post-Preview-card
Post Card Wordpress Plugin V 2.0.3
https://wordpress.org/plugins/post-preview-card/

### Example
Post Card -> https://ibb.co/gyjkL5

## Description

This plugin was build to be used along with [Elementor](https://wordpress.org/plugins/elementor/), [Site Origin Page Builder](https://siteorigin.com/) or [Beaver builder](https://www.wpbeaverbuilder.com/home/?utm_expid=82857025-13.-Mrg6iFCTMyN1GHrIwH9wQ.1&utm_medium=bb&utm_source=plugins-admin-page&utm_campaign=plugins-admin-author&utm_referrer=http%3A%2F%2Flocalhost%2Fwp%2FPluginDevWorkspace%2Fwp-admin%2Fplugins.php). It was only tested with these 3 but it should work with any.
Post Preview Card installs 3 widgets to help non-developers that want to use Elementor to preview blog posts within static Elementor's pages.
list of widgets:
* Multiple Posts: This widget previews multiple posts and you can select how many widgets to be firstly displayed and then the user will load the rest of the posts by clicking in the "plus button" and the posts are going to be reloaded without the need of reloading the page, this is possible because this widget use AJAX to load the others posts, granting a better User Experience.
* Single Post by ID: This widget preview the post with the ID you inserted. 
* Random Post by Category: This widget preview a random post using the category you inserted.

### Layout 
This plugin has 1 layout with two variations so far, both are responsive. We are working to deliver more layouts in the near future.
* Post with thumbnail will be displayed in this order: Thumbnail, Title, Publish date and Category, Post excerpt or call text, read more button 
* Post without thumbnail will be displayed in this order: Title with black background, Post excerpt or call text, read more button

Layout is customizable.

## General Usage 

### Peaw Single Post Preview
  As the name says, this is a widget that preview a single post only. All you need to do is enter an ID
  1. Using Elementor, simply drag and drop the widget wherever you want. This is the widget name 
  2. The widget menu will be prompted. 
  all you need to do here is insert the Post id (Check FAQ to know where to find it), and the last thing is to insert a beatifull text to make people interested about your post. If call text is blank, it will be the Post Excerpt or first 85 char of post content 
  3. All the others fields are optional.
  4. That's it! Here an example: https://ibb.co/gyjkL5

### Peaw Random Post By Category
  1. Using Elementor, simply drag and drop the widget wherever you want. This is the widget name  
  2. All you need to do is choose the Category you want. The call text here is the Post excerpt always.
  3. All the others fields are optional.
  4. That's it!

  If you're receiving the "Something is wrong" message it can be a lot of things but the most commom is that WordPress is not properly saving your widget and all you need to do is to change something in the widget form save, and then return the way it was and save again. This is a WP error, not a plugin error.

### Peaw Multiple Posts
  1. Using Elementor, simply drag and drop the widget wherever you want. 
  2. The widget form will be prompted and you can choose how many posts to be displayed (999 posts will display ALL the blog posts), you can choose then, how many posts to be firstly displayed and how many posts per row.
  3. All the others fields are optional
  4. That's it! -> https://ibb.co/fdoWSk
  
## FAQ
### How can I know the Post id?
This plugin adds the ID column in your "All Posts" section on the wordpress admin panel. You can deactivate this by going to the general settings page of the plugin.

### Installed the plugin and no widget were added?
Go to the Post Preview Card settings and go to Widgets and check the widgets you want to be installed.

### I want more layouts
We will be adding more layouts in the near future but if you want a personalized layout or even a personalized WP Template, you can contact me at fernandoanaelcabral@gmail.com 

### I am a developer and want to help
This plugin is mantained by me and myself, so every help is apreciated, I created some internal API for better creating and registering widgets for the plugin, there are some layout related API as well. So it's very easy to grow this plugin. If you wish to help contact me 
at fernandoanaelcabral@gmail.com 

### What are the next updates?
in priority order:
1. Bug fixes always come first 
2. New Layouts
3. A shortcode for this plugin
4. Work towards universal compatibility with others page builders.

## Known bugs
1. Working only with Elementor 
2. Elementor not rendering css style in the preview mode
3. Elementor sometimes is not saving properly the random post by category widget
