# Flickr by Albums

## Description

Display thumbnail-style galleries of complete Flickr albums using a simple shortcode in your WordPress posts or pages.

Some features:

- Works with Flickr's SSL-only API (non-SSL API deprecated on June 27, 2014).
- Create large (150px) or small (75px) square thumbnail galleries.
- Add a class to the link that wraps the thumbnails to do nifty things like lightbox the full version of the image when the user clicks it.

**Shortcode Usage Instructions:**

Once you've saved your API key, you can use the following shortcode throughout your site:

e.g. `[flickr-album id="1234567890" size="large"]`

Please note:

`id=""` is required and the ID can be found in your browser's address bar when viewing the album in Flickr.
`size=""` is optional and the thumbnail size may be "large" or "small" (defaults to "large" if not specified).

## Installation

### Using FTP

1. Download and unzip `flickr-by-albums-master.zip` and remove "-master" from the directory name.
2. Upload the `flickr-by-albums` folder and its contents to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Go to the Settings > Flickr by Albums page and adjust the settings as needed.

## Frequently Asked Questions

= Why do I need to register for the Flickr API to use this plugin? =

Take this under good authority: it's better to be in control of your own API key. If you rely on a plugin developer to supply an API key for you, and that developer then closes their Flickr account or otherwise vanishes into thin air, then the Flickr feed(s) on your website will stop working.

Don't worry, registering for the Flickr API isn't as onerous as it sounds (and you don't need to to know anything about web development to do it).

### How do I register for the Flickr API?

1. Visit [https://www.flickr.com/services/developer/api/](https://www.flickr.com/services/developer/api/) and log into your normal Instagram account.
2. Follow the steps to request your API key and submit your request.
3. Enter your brand new API key in the Flickr by Albums settings page.

### How do I use the shortcode?

See the directions outlined above, or check out the direction on the plugin's settings page in the WP admin area.

## Screenshots

1. Flickr by Albums settings page

## Changelog

### 1.0.1
* Update phpFlickr library to v3.1.1
* Only load plugin stylesheet where required

### 1.0
* Initial release.

## Updates

This plugin supports the [GitHub Updater](https://github.com/afragen/github-updater) plugin, so if you install that, this plugin becomes automatically updateable direct from GitHub.

## Credit Roll

Flickr by Albums could not have been made possible without:

* Tom McFarlin's time-saving [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate). Why start from scratch when you don't have to?
* Dan Coulter's super-easy-to-work-with [phpflickr library](https://github.com/dan-coulter/phpflickr). Check it out.

## Author Information

This plugin was originally created by [Mandi Wise](http://mandiwise.com/).

## License

Copyright (c) 2014, Mandi Wise

Flickr by Albums is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
