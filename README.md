BootPress
=============

This is a starting theme or parent theme for WordPress, using various existing libraries/tools to jumpstart thing. The intention is to use popular widely known works as a basis so that little or no learning is necessary.

__Note: early development, so it doesn't have the basics of a WordPress theme just yet__

Included libraries/tools
-------

* [Twitter Bootstrap 2](https://github.com/twitter/bootstrap/)
* [HTML5 Boilerplate 3](http://html5boilerplate.com/) has been used as the basis for the markup, comments have been preserved, and added to for WordPress support and best practices
* [WP-Less by sanchothefat](https://github.com/sanchothefat/wp-less), allows the LESS stylesheets to be queued using the WordPress default APIs, and auto recompiles via PHP on changes
* [Scribu theme wrapper code](http://scribu.net/wordpress/theme-wrappers.html)
* Animate.css by Dan Eden, with additions for LESS mixin support

Installation
-------
In your WordPress theme folder:

    git clone git://github.com/Tarendai/BootPress.git BootPress
    git submodule update --init

Additional LESS Mixins
-------

To prevent spamming the markup with span1 span2 span3 etc classes, using .columns(1); .columns(2); will now work. For some reason with twitter bootstrap v2 that mixin didn't work out of the box.

Also the animated.css classes now act as mixins, so to make an element fade in using LESS, add the .animated() and .fadeIn() mixins. By default several of the bootstrap elements already have animations, such as the dropdown menus using fadeInUp