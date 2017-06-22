<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Tests\Exception;

use Phproberto\Joomla\Module\Exception\InvalidModuleParametersException;
use Phproberto\Joomla\Module\Tests\Stubs\SampleModule;

/**
 * Tests for InvalidModuleException class.
 *
 * @since  __DEPLOY_VERSION__
 */
class InvalidModuleParametersExceptionTest extends \TestCase
{
	/**
	 * Test notRegistryInstance method
	 *
	 * @return  void
	 *
	 * @expectedException Phproberto\Joomla\Module\Exception\InvalidModuleParametersException
	 */
	public function testNotRegistryInstance()
	{
		$module = new SampleModule(SampleModule::SAMPLE_PUBLISHED);

		throw InvalidModuleParametersException::notRegistryInstance($module);
	}
}
