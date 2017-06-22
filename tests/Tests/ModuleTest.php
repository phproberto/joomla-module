<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Tests;

use Phproberto\Joomla\Module\Module;
use Phproberto\Joomla\Module\Exception\InvalidModuleException;
use Joomla\Registry\Registry;
use Phproberto\Joomla\Module\Tests\Stubs\InvalidModule;
use Phproberto\Joomla\Module\Tests\Stubs\MenuModule;
use Phproberto\Joomla\Module\Tests\Stubs\NoLayoutsModule;
use Phproberto\Joomla\Module\Tests\Stubs\SampleModule;

/**
 * Tests for Module class.
 *
 * @since  0.0.1
 */
class ModuleTest extends \TestCaseDatabase
{
	/**
	 * Testable DB module
	 *
	 * @const
	 */
	const MENU_MODULE_ID = 1;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
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
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();
		parent::tearDown();
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
	 */
	protected function getDataSet()
	{
		$dataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
		$dataSet->addTable('jos_modules', JPATH_TEST_DATABASE . '/jos_modules.csv');
		$dataSet->addTable('jos_extensions', JPATH_TEST_DATABASE . '/jos_extensions.csv');

		return $dataSet;
	}

	/**
	 * Test if module is abstract.
	 *
	 * @return void
	 */
	public function testClassIsAbstract()
	{
		$thrown = false;

		$testClass  = new \ReflectionClass('Phproberto\Joomla\Module\Module');
		$this->assertTrue($testClass->isAbstract());
	}

	/**
	 * Test if module is abstract.
	 *
	 * @expectedException     Phproberto\Joomla\Module\Exception\InvalidModuleException
	 * @expectedExceptionCode 500
	 *
	 * @return void
	 */
	public function testElementNotDefinedThrowsException()
	{
		$module = new InvalidModule;
	}

	/**
	 * Ensure that extension data is not modified by mistake.
	 *
	 * @return  void
	 */
	public function testExtensionCloned()
	{
		$module = new MenuModule(self::MENU_MODULE_ID);

		$extension = $module->getExtension();
		$modifiedExtension = $module->getExtension();
		$this->assertNotEquals(0, count((array) $extension));

		$modifiedExtension->name = 'modified outside module';
		$this->assertEquals($extension, $module->getExtension());
		$this->assertNotEquals($extension, $modifiedExtension);
	}

	/**
	 * Test getClient method.
	 *
	 * @return  void
	 */
	public function testGetClient()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);
		$this->assertEquals(SampleModule::CLIENT_SITE, $module->getClient());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_DOWN);
		$this->assertEquals(SampleModule::CLIENT_ADMIN, $module->getClient());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED);
		$this->assertEquals(SampleModule::CLIENT_SITE, $module->getClient());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_UP);
		$this->assertEquals(SampleModule::CLIENT_ADMIN, $module->getClient());
	}

	/**
	 * Test getContent method.
	 *
	 * @return  void
	 */
	public function testGetContent()
	{
		$module = new SampleModule;
		$this->assertEquals('', $module->getContent());

		$module = new SampleModule('22');
		$this->assertEquals('<div>Sample content</div>', $module->getContent());
	}

	/**
	 * Test getElement method.
	 *
	 * @return  void
	 */
	public function testGetElement()
	{
		$module = new SampleModule;
		$this->assertEquals('mod_sample_module', $module->getElement());

		$module = new SampleModule('23');
		$this->assertEquals('mod_sample_module', $module->getElement());
	}

	/**
	 * Test getExtension method
	 *
	 * @return  void
	 */
	public function testGetExtension()
	{
		$module = new SampleModule;
		$this->assertEquals(SampleModule::databaseExtension(), $module->getExtension());

		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);
		$this->assertEquals(SampleModule::databaseExtension(), $module->getExtension());
	}

	/**
	 * Test getId method.
	 *
	 * @return  void
	 */
	public function testGetId()
	{
		$module = new SampleModule;
		$this->assertEquals(0, $module->getId());

		$module = new SampleModule('32');
		$this->assertEquals(32, $module->getId());

		$module = new SampleModule('22foobar');
		$this->assertEquals(22, $module->getId());

		$module = new SampleModule('foobar');
		$this->assertEquals(0, $module->getId());
	}

	/**
	 * Test getInstance method.
	 *
	 * @return  void
	 */
	public function testGetInstanceReturnsCached()
	{
		$module = SampleModule::getInstance(SampleModule::SAMPLE_PUBLISHED);
		$this->assertEquals(null, $module->getParam('cached'));
		$module->setParam('cached', true);
		$this->assertEquals(true, $module->getParam('cached'));

		$module2 = SampleModule::getInstance(SampleModule::SAMPLE_PUBLISHED);
		$this->assertEquals(true, $module2->getParam('cached'));

		SampleModule::clearInstance(SampleModule::SAMPLE_PUBLISHED);
		$module3 = SampleModule::getInstance(SampleModule::SAMPLE_PUBLISHED);
		$this->assertEquals(null, $module3->getParam('cached'));
	}

	/**
	 * Test getLanguage method.
	 *
	 * @return  void
	 */
	public function testGetLanguage()
	{
		$module = new SampleModule;
		$this->assertEquals('', $module->getLanguage());

		$module = new SampleModule('22');
		$this->assertEquals('es-ES', $module->getLanguage());
	}

	/**
	 * Test getModule method.
	 *
	 * @return  void
	 */
	public function testGetModule()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);
		$data = $module->getSampleModules()[SampleModule::SAMPLE_PUBLISHED];
		$this->assertEquals($data, $module->getModule());

		$module = new SampleModule;
		$this->assertEquals(new \stdClass, $module->getModule());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED);
		$data = $module->getSampleModules()[SampleModule::SAMPLE_UNPUBLISHED];
		$this->assertEquals($data, $module->getModule());
	}

	/**
	 * Test getParams method.
	 *
	 * @return  void
	 */
	public function testGetParams()
	{
		$module = new MenuModule(self::MENU_MODULE_ID);
		$this->assertNotEquals(new Registry, $module->getParams());

		$module = new MenuModule;
		$this->assertEquals(new Registry, $module->getParams());
	}

	/**
	 * Test getPosition method.
	 *
	 * @return  void
	 */
	public function testGetPosition()
	{
		$module = new SampleModule;
		$this->assertEquals('', $module->getPosition());

		$module = new SampleModule('22');
		$this->assertEquals('test-position', $module->getPosition());
	}

	/**
	 * Test getTitle method.
	 *
	 * @return  void
	 */
	public function testGetTitle()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);
		$this->assertEquals('Published module', $module->getTitle());

		$module = new SampleModule;
		$this->assertEquals('', $module->getTitle());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_UP);
		$this->assertEquals('Unpublished module by publish_up', $module->getTitle());
	}

	/**
	 * Test hasId method.
	 *
	 * @return  void
	 */
	public function testHasId()
	{
		$module = new SampleModule;
		$this->assertFalse($module->hasId());

		$module = new SampleModule('foobar');
		$this->assertFalse($module->hasId());

		$module = new SampleModule('32');
		$this->assertTrue($module->hasId());

		$module = new SampleModule('32erer');
		$this->assertTrue($module->hasId());
	}

	/**
	 * Test isAdmin method.
	 *
	 * @return  void
	 */
	public function testIsAdmin()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);
		$this->assertFalse($module->isAdmin());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_DOWN);
		$this->assertTrue($module->isAdmin());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED);
		$this->assertFalse($module->isAdmin());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_UP);
		$this->assertTrue($module->isAdmin());
	}

	/**
	 * Test getTitle method.
	 *
	 * @return  void
	 */
	public function testIsPublished()
	{
		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_UP);
		$this->assertFalse($module->isPublished());

		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);
		$this->assertTrue($module->isPublished());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_DOWN);
		$this->assertFalse($module->isPublished());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED);
		$this->assertFalse($module->isPublished());
	}

	/**
	 * Test isSite method.
	 *
	 * @return  void
	 */
	public function testIsSite()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);
		$this->assertTrue($module->isSite());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_DOWN);
		$this->assertFalse($module->isSite());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED);
		$this->assertTrue($module->isSite());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_UP);
		$this->assertFalse($module->isSite());
	}

	/**
	 * Test isTitleShown method.
	 *
	 * @return  void
	 */
	public function testIsTitleShown()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);
		$this->assertTrue($module->isTitleShown());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED);
		$this->assertFalse($module->isTitleShown());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_UP);
		$this->assertTrue($module->isTitleShown());

		$module = new SampleModule(SampleModule::SAMPLE_UNPUBLISHED_BY_PUBLISH_DOWN);
		$this->assertFalse($module->isTitleShown());
	}

	/**
	 * Ensures that using getModule() always returns a clone of the instance module to avoid undesired modifications.
	 *
	 * @return  void
	 */
	public function testModuleCloned()
	{
		$module = new MenuModule(self::MENU_MODULE_ID);
		$originalModule = $module->getModule();
		$mod = $module->getModule();
		$mod->test = 'modified outside module';
		$this->assertTrue(!property_exists($module->getModule(), 'test'));
		$this->assertEquals($originalModule, $module->getModule());
		$this->assertNotEquals($originalModule, $mod);
	}

	/**
	 * Test render method.
	 *
	 * @return  void
	 */
	public function testRender()
	{
		$module = new MenuModule(self::MENU_MODULE_ID);

		$this->assertEquals('<h1>Sample module - var</h1>', trim($module->render()));
		$this->assertEquals('<h1>Sample module override - var</h1>', trim($module->render('override')));
		$this->assertEquals('<h1>Sample module override - var-modified</h1>', trim($module->render('override', array('foo' => 'var-modified'))));
		$this->assertEquals('<h1>Sample module override on inactive template - var</h1>', trim($module->render('inactive:override')));
		$this->assertEquals('<h1>Sample module override - var</h1>', trim($module->render('_:override')));
		$this->assertEquals('<h1>Sample module - var</h1>', trim($module->render('unexisting')));

		$module = new NoLayoutsModule;
		$this->assertEquals('', trim($module->render()));
	}

	/**
	 * Test saveParams method.
	 *
	 * @return  void
	 */
	public function testSaveParams()
	{
		$module = MenuModule::getInstance(self::MENU_MODULE_ID);
		$defaultParams = $module->getParams();
		$this->assertNotEquals(0, count($defaultParams->toArray()));

		$module->setParam('custom-param', 'my-value');
		$module->saveParams();
		$this->assertNotEquals($defaultParams, $module->getParams());

		$module = MenuModule::getFreshInstance(self::MENU_MODULE_ID);
		$this->assertEquals('my-value', $module->getParam('custom-param'));
	}
}
