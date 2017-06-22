<?php
/**
 * Joomla! module.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.htm
 */

namespace Phproberto\Joomla\Module;

defined('_JEXEC') or die;

/**
 * Describes methods required by modules.
 *
 * @since  0.0.1
 */
interface ModuleInterface
{
	/**
	 * Frontend module.
	 *
	 * @const
	 */
	const CLIENT_SITE = 0;

	/**
	 * Backend module.
	 *
	 * @const
	 */
	const CLIENT_ADMIN = 1;

	/**
	 * Unpublished state.
	 *
	 * @const
	 */
	const STATE_UNPUBLISHED = 0;

	/**
	 * Published state.
	 *
	 * @const
	 */
	const STATE_PUBLISHED = 1;

	/**
	 * Title hidden.
	 *
	 * @const
	 */
	const TITLE_HIDDEN = 0;

	/**
	 * Title shown.
	 *
	 * @const
	 */
	const TITLE_SHOWN = 1;

	/**
	 * Get the module identifier.
	 *
	 * @return  integer
	 */
	public function getId();

	/**
	 * Check if this module has an identifier.
	 *
	 * @return  boolean
	 */
	public function hasId();
}
