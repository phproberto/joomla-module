<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module;

defined('JPATH_PLATFORM') || die;

use Joomla\Registry\Registry;
use Phproberto\Joomla\Traits as CommonTraits;

/**
 * Base module class.
 *
 * @since  0.0.1
 */
abstract class Module implements ModuleInterface
{
	use CommonTraits\HasExtension, CommonTraits\HasInstances, CommonTraits\HasLayoutData, CommonTraits\HasParams;
	use Traits\HasModule;

	/**
	 * Element of this module. Example: mod_articles_latest
	 *
	 * @var  string
	 */
	protected $element;

	/**
	 * Module identifier.
	 *
	 * @var  integer
	 */
	protected $id;

	/**
	 * Constructor.
	 *
	 * @param   integer   $id  Module identifier
	 */
	public function __construct($id = null)
	{
		if (empty($this->element))
		{
			throw Exception\InvalidModuleException::invalidElement($this);
		}

		$this->id = (int) $id;
	}

	/**
	 * Get the client/application of the module.
	 *
	 * @return  integer
	 */
	public function getClient()
	{
		return (int) $this->getModuleProperty('client_id', STATIC::CLIENT_SITE);
	}

	/**
	 * Get the content of this module.
	 *
	 * @return  string
	 */
	public function getContent()
	{
		$module = $this->getModule();

		return empty($module->content) ? '' : $module->content;
	}

	/**
	 * Get the element of this module.
	 *
	 * @return  string
	 */
	public function getElement()
	{
		return $this->element;
	}

	/**
	 * Get the module identifier.
	 *
	 * @return  integer
	 */
	public function getId()
	{
		return (int) $this->id;
	}

	/**
	 * Get the module language.
	 *
	 * @return  string
	 */
	public function getLanguage()
	{
		$module = $this->getModule();

		return empty($module->language) ? '' : $module->language;
	}

	/**
	 * Get the path to a layout.
	 *
	 * @param   string  $layoutId  Layout identifier
	 *
	 * @return  mixed   string | false
	 */
	private function getLayoutPath($layoutId)
	{
		$originalTemplate = $template = \JFactory::getApplication()->getTemplate();

		if (strpos($layoutId, ':') !== false)
		{
			// Get the template and file name from the string
			$temp = explode(':', $layoutId);
			$template = ($temp[0] == '_') ? $template : $temp[0];
			$layoutId = $temp[1];
		}

		$defaultTemplate = ($originalTemplate == $template);

		$layoutPaths = $defaultTemplate ? $this->getLayoutPaths() : array_map(
			function ($path) use ($originalTemplate, $template)
			{
				return str_replace(
					$this->getThemesPath() . '/' . $originalTemplate,
					$this->getThemesPath() . '/' . $template,
					$path
				);
			},
			$this->getLayoutPaths()
		);

		$path = \JPath::find($layoutPaths, $layoutId . '.php');

		if ($path || $layoutId == 'default')
		{
			return $path;
		}

		return $this->getLayoutPath('default');
	}

	/**
	 * Get the paths where we will search for layouts.
	 *
	 * @return  string[]
	 */
	protected function getLayoutPaths()
	{
		$reflection = new \ReflectionClass($this);

		return array(
			$this->getThemesPath() . '/' . \JFactory::getApplication()->getTemplate() . '/html/' . $this->element,
			dirname($reflection->getFileName()) . '/tmpl'
		);
	}

	/**
	 * Get the date set as null in the database driver.
	 *
	 * @return  string
	 */
	private function getNullDate()
	{
		return \JFactory::getDbo()->getNullDate();
	}

	/**
	 * Get the module position.
	 *
	 * @return  string
	 */
	public function getPosition()
	{
		return $this->getModuleProperty('position', '');
	}

	/**
	 * Get the main path to templates folder. Mainly for testing puposes.
	 *
	 * @return  string
	 *
	 * @codeCoverageIgnore
	 */
	protected function getThemesPath()
	{
		return JPATH_THEMES;
	}

	/**
	 * Get the title of the module.
	 *
	 * @return  string
	 */
	public function getTitle()
	{
		return $this->getModuleProperty('title', '');
	}

	/**
	 * Check if this module has an identifier.
	 *
	 * @return  boolean
	 */
	public function hasId()
	{
		return !empty($this->id);
	}

	/**
	 * Is this a backend module?
	 *
	 * @return  boolean
	 */
	public function isAdmin()
	{
		return $this->getClient() === static::CLIENT_ADMIN;
	}

	/**
	 * Check if this module is published.
	 *
	 * @return  boolean
	 */
	public function isPublished()
	{
		$published = $this->getModuleProperty('published', static::STATE_UNPUBLISHED);

		if ($published != static::STATE_PUBLISHED)
		{
			return false;
		}

		$publishDown = $this->getModuleProperty('publish_down', $this->getNullDate());
		$publishUp   = $this->getModuleProperty('publish_up', $this->getNullDate());

		$isPublishedUp   = $publishUp == $this->getNullDate() || \JFactory::getDate($publishUp) <= \JFactory::getDate();
		$isPublishedDown = $publishDown != $this->getNullDate() && \JFactory::getDate($publishDown) < \JFactory::getDate();

		return $isPublishedUp && !$isPublishedDown;
	}

	/**
	 * Is this a frontend module?
	 *
	 * @return  boolean
	 */
	public function isSite()
	{
		return $this->getClient() === static::CLIENT_SITE;
	}

	/**
	 * Is the module title set as shown?
	 *
	 * @return  boolean
	 */
	public function isTitleShown()
	{
		return $this->getModuleProperty('showtitle', static::TITLE_SHOWN) == static::TITLE_SHOWN;
	}

	/**
	 * Load extension from DB.
	 *
	 * @return  \stdClass
	 */
	protected function loadExtension()
	{
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__extensions')
			->where('type = ' . $db->quote('module'))
			->where('name = ' . $db->q($this->element));

		$db->setQuery($query);

		return $db->loadObject() ?: new \stdClass;
	}

	/**
	 * Load layout data.
	 *
	 * @return  self
	 */
	protected function loadLayoutData()
	{
		return array(
			'module'         => $this->getModule(),
			'moduleInstance' => $this,
			'params'         => $this->getParams()
		);
	}

	/**
	 * Load module parameters from database.
	 *
	 * @return  Registry
	 */
	protected function loadParams()
	{
		if (!$this->hasId())
		{
			return new Registry;
		}

		return new Registry($this->getModuleProperty('params', array()));
	}

	/**
	 * Render this module.
	 *
	 * @param   string  $layoutId  Layout identifier
	 * @param   array   $data      Optional data
	 *
	 * @return  string
	 */
	public function render($layoutId = null, $data = array())
	{
		$layoutId = $layoutId ?: $this->getParam('layout', 'default');

		$layoutPath = $this->getLayoutPath($layoutId);

		if (!file_exists($layoutPath))
		{
			return '';
		}

		extract(array_merge($this->getLayoutData(), $data));

		ob_start();
		include $layoutPath;
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Save parameters to database.
	 *
	 * @return  Registry
	 */
	public function saveParams()
	{
		$db = \JFactory::getDbo();

		$query = $db->getQuery(true)
			->update('#__modules')
			->set('params = ' . $db->q($this->getParams()->toString()))
			->where('id = ' . (int) $this->getId());

		$db->setQuery($query);

		return $db->execute() ? true : false;
	}
}
