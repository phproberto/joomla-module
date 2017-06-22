<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Tests\Exception;

use Phproberto\Joomla\Module\Exception\InvalidModuleException;
use Phproberto\Joomla\Module\Tests\Stubs\SampleModule;

/**
 * Tests for InvalidModuleException class.
 *
 * @since  __DEPLOY_VERSION__
 */
class InvalidModuleExceptionTest extends \TestCase
{
	/**
	 * Test invalidElement method
	 *
	 * @return  void
	 *
	 * @expectedException Phproberto\Joomla\Module\Exception\InvalidModuleException
	 */
	public function testInvalidElement()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);

		throw InvalidModuleException::invalidElement($module);
	}

	/**
	 * Test notLoaded method
	 *
	 * @return  void
	 *
	 * @expectedException Phproberto\Joomla\Module\Exception\InvalidModuleException
	 */
	public function testNotLoaded()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);

		throw InvalidModuleException::notLoaded($module);
	}
}
