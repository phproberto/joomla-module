<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module\Tests\Stubs;

use Phproberto\Joomla\Module\Module;
use Joomla\Registry\Registry;

/**
 * Base module class.
 *
 * @since  0.0.1
 */
class SampleModule extends Module
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
	protected $element = 'mod_sample_module';

	/**
	 * Load extension from DB.
	 *
	 * @return  \stdClass
	 */
	protected function loadExtension()
	{
		return self::databaseExtension();
	}

	/**
	 * Load layout data.
	 *
	 * @return  self
	 */
	protected function loadLayoutData()
	{
		return array_merge(parent::loadLayoutData(), array('foo' => 'var'));
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

	/**
	 * Fake method that simulates extension data returned by DB.
	 *
	 * @return  \stdClass
	 */
	public static function databaseExtension()
	{
		return (object) array(
			'element' => 'mod_sample_module',
			'name'    => 'Sample module name',
			'params'  => '{"database":"params"}',
			'type'    => 'module'
		);
	}

	/**
	 * Load module information from database.
	 *
	 * @return  \stdClass
	 */
	public function getDatabaseModule()
	{
		$modules = static::getSampleModules();

		$id = $this->getId();

		if ($id && isset($modules[$id]))
		{
			return $modules[$id];
		}

		return $modules[self::SAMPLE_PUBLISHED];
	}

	/**
	 * Get the main path to templates folder.
	 *
	 * @return  string
	 *
	 * @codeCoverageIgnore
	 */
	protected function getThemesPath()
	{
		return dirname(__DIR__) . '/templates';
	}

	/**
	 * Get sample modules data.
	 *
	 * @return  array
	 */
	public static function getSampleModules()
	{
		return array(
			self::SAMPLE_PUBLISHED => (object) array(
				'content'      => '<div>Sample content</div>',
				'title'        => 'Published module',
				'language'     => 'es-ES',
				'module'       => 'mod_sample_module',
				'position'     => 'test-position',
				'client_id'    => static::CLIENT_SITE,
				'params'       => '{"layout":"_:default","moduleclass_sfx":"my-suffix"}',
				'published'    => static::STATE_PUBLISHED,
				'publish_up'   => '0000-00-00 00:00:00',
				'publish_down' => '0000-00-00 00:00:00',
				'showtitle'    => static::TITLE_SHOWN
			),
			self::SAMPLE_UNPUBLISHED => (object) array(
				'content'      => '<div>Sample content</div>',
				'title'        => 'Unpublished module',
				'language'     => 'es-ES',
				'module'       => 'mod_sample_module',
				'position'     => 'test-position',
				'client_id'    => static::CLIENT_SITE,
				'params'       => '{"layout":"_:default","moduleclass_sfx":"my-unpublished-suffix"}',
				'published'    => static::STATE_UNPUBLISHED,
				'publish_up'   => '0000-00-00 00:00:00',
				'publish_down' => '0000-00-00 00:00:00',
				'showtitle'    => static::TITLE_HIDDEN
			),
			self::SAMPLE_UNPUBLISHED_BY_PUBLISH_UP => (object) array(
				'content'      => '<div>Sample content</div>',
				'title'        => 'Unpublished module by publish_up',
				'language'     => 'es-ES',
				'module'       => 'mod_sample_module',
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
				'module'       => 'mod_sample_module',
				'position'     => 'test-position',
				'client_id'    => static::CLIENT_ADMIN,
				'published'    => static::STATE_PUBLISHED,
				'publish_up'   => '2017-06-01 00:00:00',
				'publish_down' => '2017-06-02 00:00:00',
				'showtitle'    => static::TITLE_HIDDEN
			)
		);
	}
}
