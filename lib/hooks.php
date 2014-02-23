<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Users\Roles;

use ICanBoogie\ActiveRecord;

use Icybee\Modules\Users\User;

class Hooks
{
	/**
	 * Resolves a user permission according to the roles managed by the module.
	 *
	 * @param User $user
	 * @param string $permission
	 * @param string $target
	 *
	 * @return mixed The resolved permission
	 */
	static public function resolve_user_permission(User $user, $permission, $target=null)
	{
		return $user->role->has_permission($permission, $target);
	}

	/**
	 * Resolves the user ownership.
	 *
	 * @param ActiveRecord $record
	 *
	 * @return boolean `true` if the user has the ownership of the record.
	 */
	static public function resolve_user_ownership(User $user, ActiveRecord $record)
	{
		$module = $record->model_id; // TODO-20140223: implement a better module resolver

		if (Module::PERMISSION_ADMINISTER == $user->has_permission(Module::PERMISSION_MAINTAIN, $module))
		{
			return true;
		}
	}
}