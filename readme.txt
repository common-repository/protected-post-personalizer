=== Protected Post Personalizer ===
Contributors: Orin Zebest, Joshua from podq.com
Donate link: http://glot.homepie.org/plugins/protected-post-personalizer/
Tags: password, posts, formatting, titles, title
Requires at least: 2.3
Tested up to: 2.7
Stable tag: 0.6

Customize the display of private and password-protected posts: change title prefixes, choose what content can be previewed, and style password forms.

== Description ==

This plugin is a simple one, but good at what it does. It changes three elements of protected posts to make them more friendly to visitors. 

### Prefixes: ###
* customize prefix for password-protected posts from default "Protected: "
* customize prefix for private posts from "Private: "

### Custom Previews: ###
* ability to use the post's excerpt (if one is saved) when no password is given
* ability to show custom text for all password-protected posts
* if no saved excerpt, show the default OR use custom site-wide text

### Password Form: ###
* change text before the password input box
* change text of submit button
* add custom CSS; set class or ID for theme integration

= Change Log =

0.6 - corrected for Wordpress 2.7, which handles protected and private posts differently.

0.5 - initial public release 

== Installation ==

1. Upload `protected-post-personalizer.php` to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. In the 'Settings' menu click 'Protected Posts Personalizer' and change any text you want, then click Save Changes.

== Frequently Asked Questions ==

= Does this change the way Wordpress handles passwords? =

No, not at all. This plugin is specifically for changing how password-protected and private posts are displayed. Changing these will not affect the password system at all.

= What if I only want to change one thing and nothing else? =

That's fine. All plugin defaults are Wordpress defaults. If you install it and don't change the values, nothing will appear different.

= Can anything go in the 'Assign ID, Class, or Inline Styles' box? =

Tecnically, yes. This option inserts text _directly_ into the &lt;p&gt; tag of the password form. That means that, while you *could* put anything in there, if you put something which is invalid HTML or XHTML odd things will probably happen. Additionally, you're site won't [validate](http://validator.w3.org/).

= What if I want to keep the default post previews, but also want to use a customized password form? =

No can do. Because the text of the  custom password form relies on having a custom function to call it, you must use one of the 'custom' options. It's practically the same thing. Is it that important to tell visitors "There is no excerpt because this post is password protected"? Because, in fact, that's the only difference.

= Neato! But I was really hoping for a protected post plugin that would do... =

Please, please do submit any ideas for the future at the [plugin's homepage](http://glot.homepie.org/plugins/protected-post-personalizer/). If you can think them and I can program them, we've got ourselves a new feature.

== Screenshots ==

1. Seen here with (mostly) default options. The 'Assign ID, Class, or Inline Styles' box has been given an example setting, because Wordpress' default is no id, class, or style.
1. These are my personal settings for [glot.homepie.org](http://glot.homepie.org/) (seen with colorful customized CSS for the Alluric Admin plugin).