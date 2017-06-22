<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Tests\Stubs;

use Phproberto\Joomla\Module\Module;

/**
 * Sample module class.
 *
 * @since  __DEPLOY_VERSION__
 */
class MenuModule extends Module
{
	/**
	 * Element of this module.
	 *
	 * @var  string
	 */
	protected $element = 'mod_menu';

	/**
	 * Get the main path to templates folder.
	 *
	 * @return  string
	 *
	 * @codeCoverageIgnore
	 */
	protected function getThemesPath()
	{
		return dirname(dirname(__DIR__)) . '/files/templates';
	}

	/**
	 * Load layout data.
	 *
	 * @return  self
	 */
	protected function loadLayoutData()
	{
		return array_merge(
			parent::loadLayoutData(),
			array('foo' => 'var')
		);
	}

	/**
	 * Get the paths where we will search for layouts.
	 *
	 * @return  string[]
	 */
	protected function getLayoutPaths()
	{
		return array_merge(
			array(
				$this->getThemesPath() . '/' . \JFactory::getApplication()->getTemplate() . '/html/' . $this->element,
				JPATH_TESTS_PHPROBERTO . '/files/modules/' . $this->element . '/tmpl'
			),
			parent::getLayoutPaths()
		);

	}
}
