# CakePHP New Relic Plugin

The New Relic plugin is a CakePHP plugin that makes your [New Relic]
(https://newrelic.com) transactions easier to read and understand. It also gives
you better flexibility in ignoring certain transactions (e.g., admin panels). By
default, New Relic transactions are based on the file name and are hard to read.
This plugin organizes them using basic routes, that is, a transaction for:

    /posts/edit/2

is placed in the following New Relic transaction:

   /posts/edit

This allows you to dig down into issues regarding certain actions, rather than
based on filenames. To aggregate based on action, parameters are also ignored.

## Requirements

* CAKEPHP 2.0+
* A New Relic Account
* The New Relic agent on your server (enabled)

## Installation

### Manual

* Download this: http://github.com/jeremyharris/cakephp-newrelic/zipball/master
* Unzip that download.
* Copy the resulting folder to app/Plugin/NewRelic/

### GIT Submodule

In your app directory type:

    git submodule add git://github.com/jeremyharris/cakephp-newrelic.git Plugin/NewRelic
    git submodule update --init

### Composer

Ensure `require` is present in `composer.json`. This will install the plugin into Plugin/NewRelic:

    {
        "require": {
            "jeremyharris/cakephp-newrelic": "dev-master"
        }
    }

## Usage

Load the plugin:

    CakePlugin::loadAll(); // or CakePlugin::load('NewRelic');

Add the following filter to your Dispatch filters (in `bootstrap.php):

    Configure::write('Dispatcher.filters', array(
	    'AssetDispatcher', //default
	    'CacheDispatcher', //default
	    'NewRelic.NewRelicFilter'
    ));

Transactions will now be named by basic routes, that is, `:controller/:action`.

### Ignoring transactions

Sometimes you don't want to report something to New Relic. Things like importing
take a while, and can mess up your averages. Admin panels are also an example of
something you may not want to report to New Relic. You can ignore transactions
based on routes like so:

    Configure::write('NewRelic.ignoreRoutes', array(
         '/admin/:controller/:action/*',
         '/users/import'
    ));

`NewRelic.ignoreRoutes` uses the Routing system, so use them as you would connecting
a route in your `routes.php` file.

### Real User Monitoring (RUM)

If you want to use New Relic's RUM, there's a little helper that let's you do that.
Add the helper to your controller:

    public $helpers = array(
        'NewRelic.NewRelic'
    );

Then add the following to your layout:

    <!DOCTYPE html>
    <html>
      <head>
      <?php echo $this->NewRelic->start(); ?>
      ...
      </head>
      <body>
      ...
      <?php echo $this->NewRelic->end(); ?>
      </body>
    </html>

## License

Copyright (c) Jeremy Harris

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.