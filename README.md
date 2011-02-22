# XWC Sandbox
## What is XWC Sandbox?
It is a version of the Symfony2 Sandbox that contains the XwcCoreBundle enabled 
by default and pre-configured to work out-of-the-box. XWC is a content 
management system based on Symfony2.

## Requirements
Symfony is only supported on PHP 5.3.2 and up. To check the compatibility of
your environment with Symfony2, you can run the `web/check.php` script, bundled
with this sandbox.

## Installation for Users
Coming soon...
		
## Installation for Developers
1. [Fork the project on github](http://help.github.com/forking/)
2. Clone your fork to a folder on your local machine:

        cd /path/to/workspace
        git clone --recursive git://github.com/tangentlabs/symfony-sandbox.git
   Note that the `--recursive` option will initialise any submodules
3. Change ownership of cache and log folders so Apache can write:

        sudo chown -R www-data:www-data app/cache app/logs
4. Point your apache to the sandbox folder. You may simply symlink it in 
`/var/www/` folder.

        sudo ln -s /path/to/xwc-sandbox /var/www/xwc-sandbox
5. Open in browser

        x-www-browser http://localhost/xwc-sandbox/web/app_dev.php/
   You should see a success screen

### Trouble-shooting

*	Note that you need to install the `php-sqlite3` package:
		
		sudo apt-get install php5-sqlite
		sudo apache2ctl restart

*   You may need to run a `git pull` within the submodule to grab the latest version:

		cd src/Tangent/XwcBundle/
		git pull origin master


### Updating your fork with latest changes in xwc-sandbox
1. In project folder run:
        git pull origin master

### Updating /XwcCoreBundle submodule
        git submodule update

### Pushing your changes to the xwc-sandbox or XwcCoreBundle
For /xwc-sandbox commits in the root of the project folder run:
        git push origin master
To push commits in /XwcCoreBundle submodule:
        cd /path/to/xwc-sandbox/src/Tangent/XwcCoreBundle
        git push origin master
