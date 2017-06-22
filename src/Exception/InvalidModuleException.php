<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Exception;

use Phproberto\Joomla\Module\Module;

defined('_JEXEC') or die;

/**
 * Module errors.
 *
 * @since 0.0.1
 */
class InvalidModuleException extends \RuntimeException implements ExceptionInterface
{
	/**
	 * Module class missing element.
	 *
	 * @param   Module  $module  Module instance
	 *
	 * @return  static
	 */
	public static function invalidElement(Module $module)
	{
		return new static("Invalid element in module `" . get_class($module) . "`.", 500);
	}

	/**
	 * Module class missing element.
	 *
	 * @param   Module  $module  Module instance
	 *
	 * @return  static
	 */
	public static function notLoaded(Module $module)
	{
		return new static("Module not loaded: (`" . get_class($module) . "`).", 500);
	}
}
