<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module;

use Joomla\Registry\Registry;
use Phproberto\Joomla\Traits as CommonTraits;

defined('JPATH_PLATFORM') or die;

/**
 * Module that uses layouts as rendering engine.
 *
 * @since  0.0.1
 */
abstract class LayoutModule extends Module
{
	use CommonTraits\HasLayouts;

	/**
	 * Get the paths where we will search for layouts.
	 *
	 * @return  string[]
	 */
	protected function getLayoutPaths()
	{
		$reflection = new \ReflectionClass($this);

		return array(
			$this->getThemesPath() . '/' . \JFactory::getApplication()->getTemplate() . '/html/layouts/modules/' . $this->element,
			dirname($reflection->getFileName()) . '/layouts'
		);
	}
}
