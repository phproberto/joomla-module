<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Test;

use Phproberto\Joomla\Module\Module;
use Joomla\Registry\Registry;
use Phproberto\Joomla\Module\Tests\Stubs\SampleLayoutModule;

/**
 * Tests for LayoutModule class.
 *
 * @since  0.0.1
 */
class LayoutModuleTest extends \TestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();

		$app = $this->getMockCmsApp();

		$app->expects($this->any())
			->method('getTemplate')
			->will($this->returnValue('sample'));

		\JFactory::$application = $app;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();

		parent::tearDown();
	}

	/**
	 * Ensure that cached layout data cannot be modified in layouts.
	 *
	 * @return  void
	 */
	public function testReadOnlyLayoutData()
	{
		$module = new SampleLayoutModule(SampleLayoutModule::SAMPLE_PUBLISHED);
		$this->assertEquals('<h1>Sample layout module - var</h1>', trim($module->render('modifies-data')));
		$this->assertEquals('<h1>Sample layout module - var</h1>', trim($module->render('modifies-data')));
	}

	/**
	 * Test render method.
	 *
	 * @return  void
	 */
	public function testRender()
	{
		$module = new SampleLayoutModule(SampleLayoutModule::SAMPLE_PUBLISHED);
		$this->assertEquals('<h1>Sample layout module - var</h1>', trim($module->render()));
		$this->assertEquals('<h1>Sample layout module override - var</h1>', trim($module->render('override')));
		$this->assertEquals('<h1>Sample layout module override - var-modified</h1>', trim($module->render('override', array('foo' => 'var-modified'))));
		$this->assertEquals('', trim($module->render('unexisting')));
	}
}
