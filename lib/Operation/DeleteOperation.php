<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Users\Roles\Operation;

use ICanBoogie\ErrorCollection;

use Icybee\Modules\Users\Roles\Module;
use Icybee\Modules\Users\Roles\Role;

/**
 * Deletes a role.
 *
 * @property Role $record
 */
class DeleteOperation extends \ICanBoogie\Module\Operation\DeleteOperation
{
	/**
	 * Modifies the following controls:
	 *
	 *     PERMISSION: ADMINISTER
	 *     OWNERSHIP: false
	 *
	 * @inheritdoc
	 */
	protected function get_controls()
	{
		return [

			self::CONTROL_PERMISSION => Module::PERMISSION_ADMINISTER,
			self::CONTROL_OWNERSHIP => false

		] + parent::get_controls();
	}

	/**
	 * The "visitor" (1) and "user" (2) roles cannot be deleted.
	 *
	 * @inheritdoc
	 */
	protected function validate(ErrorCollection $errors)
	{
		if ($this->key == 1 || $this->key == 2)
		{
			$errors->add_generic("The role %name cannot be deleted.", [ 'name' => $this->record->name ]);
		}

		return parent::validate($errors);
	}
}
