<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Tests\Stubs;

use Phproberto\Joomla\Module\LayoutModule;

/**
 * Base layout module class.
 *
 * @since  0.0.1
 */
class SampleLayoutModule extends LayoutModule
{
	const SAMPLE_PUBLISHED = 1;
	const SAMPLE_UNPUBLISHED = 2;
	const SAMPLE_UNPUBLISHED_BY_PUBLISH_UP = 3;
	const SAMPLE_UNPUBLISHED_BY_PUBLISH_DOWN = 4;

	/**
	 * Element of this module.
	 *
	 * @var  string
	 */
	protected $element = 'mod_sample_layout_module';

	/**
	 * Load module information from database.
	 *
	 * @return  \stdClass
	 */
	public function getDatabaseModule()
	{
		$modules = $this->getSampleModules();
		$id = $this->getId();

		if ($id && isset($modules[$id]))
		{
			return $modules[$id];
		}

		return $modules[self::SAMPLE_PUBLISHED];
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
				$this->getThemesPath() . '/' . \JFactory::getApplication()->getTemplate() . '/html/layouts/modules/' . $this->element,
				JPATH_TESTS_PHPROBERTO . '/files/modules/' . $this->element . '/layouts'
			),
			parent::getLayoutPaths()
		);

	}

	/**
	 * Get sample modules data.
	 *
	 * @return  array
	 */
	public function getSampleModules()
	{
		return array(
			self::SAMPLE_PUBLISHED => (object) array(
				'content'      => '<div>Sample content</div>',
				'title'        => 'Published module',
				'language'     => 'es-ES',
				'module'       => 'mod_sample_layout_module',
				'position'     => 'test-position',
				'client_id'    => static::CLIENT_SITE,
				'published'    => static::STATE_PUBLISHED,
				'publish_up'   => '0000-00-00 00:00:00',
				'publish_down' => '0000-00-00 00:00:00',
				'showtitle'    => static::TITLE_SHOWN
			),
			self::SAMPLE_UNPUBLISHED => (object) array(
				'content'      => '<div>Sample content</div>',
				'title'        => 'Unpublished module',
				'language'     => 'es-ES',
				'module'       => 'mod_sample_layout_module',
				'position'     => 'test-position',
				'client_id'    => static::CLIENT_SITE,
				'published'    => static::STATE_UNPUBLISHED,
				'publish_up'   => '0000-00-00 00:00:00',
				'publish_down' => '0000-00-00 00:00:00',
				'showtitle'    => static::TITLE_HIDDEN
			),
			self::SAMPLE_UNPUBLISHED_BY_PUBLISH_UP => (object) array(
				'content'      => '<div>Sample content</div>',
				'title'        => 'Unpublished module by publish_up',
				'language'     => 'es-ES',
				'module'       => 'mod_sample_layout_module',
				'position'     => 'test-position',
				'client_id'    => static::CLIENT_ADMIN,
				'published'    => static::STATE_PUBLISHED,
				'publish_up'   => '2100-01-01 00:00:00',
				'publish_down' => '2100-01-02 00:00:00',
				'showtitle'    => static::TITLE_SHOWN
			),
			self::SAMPLE_UNPUBLISHED_BY_PUBLISH_DOWN => (object) array(
				'content'      => '<div>Sample content</div>',
				'title'        => 'Unpublished module by publish_down',
				'language'     => 'es-ES',
				'module'       => 'mod_sample_layout_module',
				'position'     => 'test-position',
				'client_id'    => static::CLIENT_ADMIN,
				'published'    => static::STATE_PUBLISHED,
				'publish_up'   => '2017-06-01 00:00:00',
				'publish_down' => '2017-06-02 00:00:00',
				'showtitle'    => static::TITLE_HIDDEN
			)
		);
	}

	/**
	 * Get the main path to templates folder.
	 *
	 * @return  string
	 */
	protected function getThemesPath()
	{
		return JPATH_TESTS_PHPROBERTO . '/files/templates';
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
	 * Load module information from database.
	 *
	 * @return  \stdClass
	 */
	protected function loadModule()
	{
		if (!$this->hasId())
		{
			return new \stdClass;
		}

		return $this->getDatabaseModule();
	}
}
