laravel4-views-in-codeigniter
=============================

Laravel 4 View class(es) Natively in CodeIgniter

Many thanks to the Laravel IRC community and a special shoutout to Jason Lewis who helped!

#Disclaimer!!!!
This is a GIANT work in progress. I hope to have it completed soon, but in the meantime use at your own risk.

#Installation

Download the laravel_views library and config from this repo. 

Add the following to your composer.json file
```code
"illuminate/view": "v4.0.6"
"illuminate/container": "v4.0.6"
"illuminate/events": "v4.0.6"
"illuminate/support": "v4.0.6"
"illuminate/filesystem": "v4.0.6"
"symfony/finder": "v2.3.2"
"illuminate/support": "v4.0.6"
```

Run
```code
composer update
```

#Example
```code
$this->load->library('laravel_views');
echo $this->laravel_views->make('welcome_message');
```