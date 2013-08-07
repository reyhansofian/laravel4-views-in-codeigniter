<?php

class Laravel_views {
	
	/**
	 * CodeIgniter Super Global
	 *
	 * The CodeIgniter Super Global Instance
	 *
	 * @access protected
	 * @var object
	 */
	protected $CI;

	/**
	 * EngineResolver
	 *
	 * @access protected
	 * @var Illuminate\View\Engines\EngineResolver
	 */
	protected $engine_resolver = null;

	/**
	 * FileSystem
	 *
	 * @access protected
	 * @var Illuminate\Filesystem\FileSystem
	 */
	protected $file_system = null;

	/**
	 * View Paths
	 *
	 * @access protected
	 * @var array
	 */
	protected $view_paths = array();

	/**
	 * File View Finder
	 *
	 * @access protected
	 * @var Illuminate\View\FileViewFinder
	 */
	protected $file_view_finder = null;

	/**
	 * Dispatcher
	 *
	 * @access protected
	 * @var Illuminate\Events\Dispatcher
	 */
	protected $dispatcher = null;

	/**
	 * Environment
	 *
	 * @access protected
	 * @var Illuminate\View\Environment
	 */
	protected $environment = null;

	/**
	 * Cache Path
	 *
	 * What is the Cache path?
	 *
	 * @access protected
	 * @var string
	 */
	protected $cache_path = null;

	/**
	 * Class Construct
	 *
	 * This method is executed during the setup of this class
	 *
	 * @access public
	 */
	public function __construct()
	{
		$this->CI = &get_instance();
		//load the config file
		$this->CI->config->load('laravel_views',true);

		//set the engine resolver
		$this->engine_resolver = new Illuminate\View\Engines\EngineResolver();

		//load our resolvers
		$this->_load_resolvers();

		//set the file system
		$this->file_system = new Illuminate\Filesystem\FileSystem();

		//set the view paths
		$this->_set_view_paths();

		//set the file view finder
		$this->file_view_finder = new Illuminate\View\FileViewFinder($this->file_system,$this->view_paths);

		//set the dispatcher
		$this->dispatcher =  new Illuminate\Events\Dispatcher();

		//set the environment
		$this->environment = new Illuminate\View\Environment($this->engine_resolver,$this->file_view_finder,$this->dispatcher);
	}

	/**
	 * Set The Cache Path
	 *
	 * Sets the cache path to the config value.
	 * If no the config has no value, then use the default
	 * cache path
	 *
	 * @access private
	 */
	private function _set_cache_path()
	{
		$path = $this->CI->config->item('cache_path','laravel_views');
		if (empty($path))
		{
			$path = $this->CI->config->item('cache_path');

			if (empty($path))
			{
				$path = APPPATH.'cache';
			}
		}

		$this->cache_path = $path;
	}

	/**
	 * View Paths
	 *
	 * With Laravel we can load views from multiple paths, so 
	 * lets add the default CI path, plus others that were specified
	 * in the config file
	 *
	 * @access private
	 */
	private function _set_view_paths()
	{
		$additional_paths = $this->CI->config->item('additional_view_paths');
		$this->view_paths[] = VIEWPATH;

		if (count($additional_paths)>0)
		{
			for ($a=0; $a<count($additional_paths); $a++)
			{
				$this->view_paths[] = $additional_paths[$a];
			}
		}
	}

	/**
	 * Load Resolvers
	 *
	 * Laravel comes packed with a few resolvers,
	 * but we may or may not want to implement them in CI.
	 * Check the config and load from there
	 * 
	 * @access private
	 */
	private function _load_resolvers()
	{
		//grab the engines we should load from the config
		$load_engines = $this->CI->config->item('resolvers','laravel_views');

		for ($a=0; $a<count($load_engines); $a++)
		{
			switch (strtoupper($load_engines[$a]))
			{
				case 'PHP':
					$this->_resolve_php();
				break;

				case 'BLADE':
					$this->_resolve_blade();
				break;

				default:
					show_error('Unknown resolver: '.$load_engines[$a]);
				break;
			}
		}
	}

	/**
	 * PHP Resolver
	 *
	 * This is Laravel's PHP Resolver
	 *
	 * @access private
	 */
	private function _resolve_php()
	{
		$this->engine_resolver->register('php',function()
		{
			return new Illuminate\View\Engines\PhpEngine;
		});
	}

	/**
	 * Blade Resolver
	 *
	 * This is Laravel's Blade Resolver
	 *
	 * @access private
	 */
	private function _resolve_blade()
	{
		$this->_set_cache_path();

		$this->engine_resolver->register('blade',function()
		{
			return new Illuminate\View\Engines\CompilerEngine
			(
				new Illuminate\View\Compilers\BladeCompiler
					(
						new Illuminate\FileSystem\FileSystem(),$this->cache_path
					)
			);
		});
	}

	/**
	 * Return Environment
	 *
	 * Since this library is still a big WIP
	 * we may need to return the environment to
	 * requester.
	 *
	 * @access public
	 * @return Illuminate\View\Environment
	 */
	public function return_environment()
	{
		return $this->environment;
	}
}