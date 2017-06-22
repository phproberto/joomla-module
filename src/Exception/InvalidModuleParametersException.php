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
class InvalidModuleParametersException extends \RuntimeException implements ExceptionInterface
{
	/**
	 * Parameters not instance of Registry.
	 *
	 * @param   mixed  $params  Parameters
	 *
	 * @return  static
	 */
	public static function notRegistryInstance($params)
	{
		return new static("Module parameters are not an instance of Registry (`" . gettype($params) . "`)", 500);
	}
}
