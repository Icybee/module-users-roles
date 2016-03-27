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
use ICanBoogie\Module\Descriptor;
use ICanBoogie\Operation;

use Icybee\Modules\Users\Roles\Module;
use Icybee\Modules\Users\Roles\Role;

class PermissionsOperation extends Operation
{
	protected function get_controls()
	{
		return [

			self::CONTROL_PERMISSION => Module::PERMISSION_ADMINISTER

		] + parent::get_controls();
	}

	/**
	 * @inheritdoc
	 */
	protected function validate(ErrorCollection $errors)
	{
		return true;
	}

	protected function process()
	{
		$request = $this->request;
		$model = $this->module->model;
		$descriptors = $this->app->modules->descriptors;

		foreach ($request['roles'] as $rid => $perms)
		{
			/* @var $role Role */

			$role = $model[$rid];

			$p = [];

			foreach ($perms as $perm => $name)
			{
				if ($name === 'inherit')
				{
					continue;
				}

				if (filter_var($name, FILTER_VALIDATE_BOOLEAN))
				{
					if (isset($descriptors[$perm]))
					{
						#
						# the module defines his permission level
						#

						$p[$perm] = $descriptors[$perm][Descriptor::PERMISSION];

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

		$this->response->message = $this->format('Permissions have been saved.');
		$this->response->location = $request->uri;

		return true;
	}
}
