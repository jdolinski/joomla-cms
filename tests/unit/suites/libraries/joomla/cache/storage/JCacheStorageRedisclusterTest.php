<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Cache
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
/**
 * Test class for JCacheStorageRedis.
 */
class JCacheStorageRedisclusterTest extends TestCaseCache
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		if (!JCacheStorageRediscluster::isSupported())
		{
			$this->markTestSkipped('The Redis Cluster cache handler is not supported on this system.');
		}
		parent::setUp();
		// Mock the returns on JApplicationCms::get() to use the default values
		JFactory::$application->expects($this->any())
			->method('get')
			->willReturnArgument(1);
		$this->handler = new JCacheStorageRediscluster;
		// This adapter doesn't throw an Exception on a connection failure so we'll have to use Reflection to get into the class to check it
		if (!(TestReflection::getValue($this->handler, '_rediscluster') instanceof RedisCluster))
		{
			$this->markTestSkipped('Failed to connect to Redis Cluster');
		}
		// Override the lifetime because the JCacheStorage API multiplies it by 60 (converts minutes to seconds)
		$this->handler->_lifetime = 2;
	}
}