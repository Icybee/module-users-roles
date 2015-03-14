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

use ICanBoogie\Module\Descriptor;

class PermissionsOperation extends \ICanBoogie\Operation
{
	protected function get_controls()
	{
		return array
		(
			self::CONTROL_PERMISSION => Module::PERMISSION_ADMINISTER
		)

		+ parent::get_controls();
	}

	protected function validate(\ICanboogie\Errors $errors)
	{
		return true;
	}

	protected function process()
	{
		$request = $this->request;
		$model = $this->module->model;

		foreach ($request['roles'] as $rid => $perms)
		{
			$role = $model[$rid];

			$p = array();

			foreach ($perms as $perm => $name)
			{
				if ($name === 'inherit')
				{
					continue;
				}

				if ($name === 'on')
				{
					if (isset($this->app->modules->descriptors[$perm]))
					{
						#
						# the module defines his permission level
						#

						$p[$perm] = $this->app->modules->descriptors[$perm][Descriptor::PERMISSION];

						continue;
					}
					else
					{
						#
						# this is a special permission
						#

						$p[$perm] = true;

						continue;
					}
				}

				$p[$perm] = is_numeric($name) ? $name : Role::$permission_levels[$name];
			}

			$role->perms = $p;
			$role->save();
		}

		$this->response->message = 'Permissions have been saved.';

		return true;
	}
}
