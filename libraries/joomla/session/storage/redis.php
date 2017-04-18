<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Session
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Redis session storage handler for PHP
 *
 * @link   https://secure.php.net/manual/en/function.session-set-save-handler.php
 * @since  __DEPLOY_VERSION__
 */
class JSessionStorageRedis extends JSessionStorage
{
	/**
	 * Constructor
	 *
	 * @param   array  $options  Optional parameters.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct($options = array())
	{
		if (!self::isSupported())
		{
			throw new RuntimeException('Redis Extension is not available', 404);
		}

		$config = JFactory::getConfig();

		$this->_servers = array(
			'host' => $config->get('session_redis_server_host', 'localhost'),
			'port' => $config->get('session_redis_server_port', 11211),
		);

		parent::__construct($options);
	}

	/**
	 * Register the functions of this class with PHP's session handler
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function register()
	{
		if (!empty($this->_servers) && isset($this->_servers['host']) && isset($this->_servers['port']))
		{
			$serverConf = current($this->_servers);
	
			ini_set('session.save_path', "{$this->_servers['host']}:{$this->_servers['port']}");
			ini_set('session.save_handler', 'redis');
			ini_set('zlib.output_compression', 'Off'); //this is required if the configuration.php gzip is turned on
		}
	}

	/**
	 * Test to see if the SessionHandler is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function isSupported()
	{
		return extension_loaded('redis') && class_exists('Redis');
	}
}
