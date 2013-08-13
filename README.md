laravel4-views-in-codeigniter
=============================

Laravel 4 View class(es) Natively in CodeIgniter

Many thanks to the Laravel IRC community and a special shoutout to Jason Lewis who helped!

#Objective
Create a transparent "bridge" that utilizes the Laravel classes for views, but uses the natual CodeIgniter syntax. 
This bridge must also be able to utilize the "Blade" template library provided by Laravel.

#Installation

You **MUST** have composer installed in your CodeIgniter installation. The easiest way to enable Composer within CodeIgniter is to add the following code to your index.php file right above the "require_once BASEPATH.'core/CodeIgniter.php';" line

```php
/*
 * --------------------------------------------------------------------
 * LOAD COMPOSER
 * --------------------------------------------------------------------
 *
 * Load Composer if it exists
 */
if (file_exists('vendor/autoload.php'))
{
	require_once('vendor/autoload.php');
}
```


Make sure your composer.json file contains
```code
"illuminate/view": "v4.0.6"
```

Update Composer
```code
composer update
```

Download this repository and add the files to your CodeIgniter installation.

**Beware** that if you have an existing "MY_Controller" class you will need to add the following method to it:

```php
public function view($view, $vars = array(), $return = false)
{
	$CI = & get_instance();
	if (!isset($CI->laravel_views))
	{
		$CI->load->library('Laravel_views');
	}
	
	if (count($vars)>0)
	{
		$CI->load->vars($vars);
	}

	if ($return===false)
	{
		$CI->output->append_output($CI->laravel_views->make($view)->with($CI->load->get_vars()));
	}
	else
	{
		return $CI->laravel_views->make($view)->with($CI->load->get_vars());
	}
}
```

#Blade
The default configuration has Blade enabled. Please refer to  the [Laravel Docs.](http://laravel.com/docs/templates)

#Example
```php
$this->load->view('welcome_message'); //this will load welcome_message.php
//this will transparently be loaded from the Laravel classes and appended to the output class in CodeIgniter
```

You can also use Blade templates and files
```php
$this->load->view('welcome_message'); //this will load welcome_message.blade.php
```