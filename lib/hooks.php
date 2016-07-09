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
	static public function resolve_user_permission(User $user, $permission, $target = null)
	{
		return $user->role->has_permission($permission, $target);
	}

	/**
	 * Resolves the user ownership.
	 *
	 * @param User $user
	 * @param ActiveRecord $record
	 *
	 * @return bool `true` if the user has the ownership of the record.
	 */
	static public function resolve_user_ownership(User $user, ActiveRecord $record)
	{
		$module = $record->model_id; // TODO-20140223: implement a better module resolver

		if (Module::PERMISSION_ADMINISTER == $user->has_permission(Module::PERMISSION_MAINTAIN, $module))
		{
			return true;
		}
	}

	/*
	 * Prototype
	 */

	/**
	 * Returns all the roles associated with the user.
	 *
	 * @param User $user
	 *
	 * @return Role[]
	 */
	static public function user_get_roles(User $user)
	{
		$models = $user->model->models;

		try
		{
			if (!$user->uid)
			{
				return [ $models['users.roles'][Role::GUEST_RID] ];
			}
		}
		catch (\Exception $e)
		{
			return [];
		}

		$rids = $models['users/has_many_roles']
			->select('rid')
			->filter_by_uid($user->uid)
			->all(\PDO::FETCH_COLUMN);

		if (!in_array(Role::USER_RID, $rids))
		{
			array_unshift($rids, Role::USER_RID);
		}

		try
		{
			return $models['users.roles']->find($rids);
		}
		catch (ActiveRecord\RecordNotFound $e)
		{
			trigger_error($e->getMessage());

			return array_filter($e->records);
		}
	}
}
