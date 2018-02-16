# mgallery

This is a simple web gallery that doesn't require a database.

The idea was to give photographers a quick and convenient way to publish a photo journal or a simple gallery, and what's more convenient than simply throwing your images in a folder? I was a hobbyist photographer for 5 years so it started from a personal need.

I never completed the project; left it in 2014. It needs responsive images (for displays with 200+ ppi), a decent default stylesheet (and a couple alternative ones), and a simple and easy way to create albums using txt files. `vars.txt` explains what each variable does.

In order to start using this script make a directory called `images` and place your photos there. The `manage.php` script will allow you to make thumbnails (if imagemagick is installed in the server), remove potentially sensitive metadata, and a number of other things. There is a passphrase (default is `secret`) to protect the script from casual abuse. You can also rename the script or remove it altogether. To configure the script head over to `config.php`. You can configure quite a few things, like the pagination.

I'm publishing this in the hopes that it'll be useful to someone, or that someone might fork this.

I ask for you leniency as I'm not actually a programmer. This is, mostly, a decent hack.

Released under GPL v3. See COPYING.txt.