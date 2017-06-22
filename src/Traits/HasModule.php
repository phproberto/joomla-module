<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Traits;

use Joomla\Registry\Registry;

defined('JPATH_PLATFORM') || die;

/**
 * Trait to load module data from DB.
 *
 * @since  0.0.1
 */
trait HasModule
{
	/**
	 * Module information from DB.
	 *
	 * @var  \stdClass
	 */
	protected $module;

	/**
	 * Get module information from DB.
	 *
	 * @param   boolean  $reload  Force data reloading.
	 *
	 * @return  \stdClass
	 */
	public function getModule($reload = false)
	{
		if ($reload || null === $this->module)
		{
			$this->module = $this->loadModule();
		}

		return clone $this->module;
	}

	/**
	 * Get a module property.
	 *
	 * @param   string  $property  Name of the property
	 * @param   mixed   $default   Default value
	 *
	 * @return  mixed
	 */
	public function getModuleProperty($property, $default = null)
	{
		$module = new Registry((array) $this->getModule());

		return $module->get($property, $default);
	}

	/**
	 * Load module information from database.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return  \stdClass
	 */
	protected function loadModule()
	{
		if (!$this->hasId())
		{
			return new \stdClass;
		}

		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__modules')
			->where('id = ' . (int) $this->getId());

		$db->setQuery($query);

		return $db->loadObject() ?: new \stdClass;
	}
}
