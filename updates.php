<?php

namespace Icybee\Modules\Users\Roles;

use ICanBoogie\Updater\AssertionFailed;
use ICanBoogie\Updater\Update;

/**
 * - Rename table `user_roles` as `users_roles`.
 * - Rename column `perms` as `serialized_perms`.
 *
 * @module users.roles
 */
class Update20120101 extends Update
{
	public function update_table_roles()
	{
		$db = $this->app->db;

		if (!$db->table_exists('user_roles'))
		{
			throw new AssertionFailed('assert_table_exists', 'user_roles');
		}

		$db("RENAME TABLE `user_roles` TO `users_roles`");
	}

	public function update_column_serialized_perms()
	{
		$this->module->model
		->assert_has_column('perms')
		->rename_column('perms', 'serialized_perms');
	}
}
