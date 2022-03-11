# Guest-post-plugin

The Guest-post plugin will provide functionality of adding custom post type posts from frontend side through ajax with essential validations for author user role only in draft mode. Admin can publish it after reviewing it.

It also shows the list of pending status posts through shortcode and gutenberg block.

The shortcode for form to show : [guest-posts]
Form will be only display if user is logged in as a author.

The shortcode for display pending posts to show : [pending-guest-posts]


To run this plugin, The user need to follow few steps.

- Create a page and add shortcode to display form. Form will be only display if user is logged in as a author

- Create another page to display all pending post by adding gutenberg block "guest post block" or by shortcode for post to display

