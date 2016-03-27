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

use ICanBoogie\ActiveRecord\RecordNotFound;
use ICanBoogie\ActiveRecord\StatementNotValid;
use ICanBoogie\ErrorCollection;
use ICanBoogie\I18n;

class Module extends \Icybee\Module
{
	const OPERATION_PERMISSIONS = 'permissions';

	static public $levels = [

		self::PERMISSION_NONE => 'none',
		self::PERMISSION_ACCESS => 'access',
		self::PERMISSION_CREATE => 'create',
		self::PERMISSION_MAINTAIN => 'maintain',
		self::PERMISSION_MANAGE => 'manage',
		self::PERMISSION_ADMINISTER => 'administer'

	];

	/**
	 * Overrides the methods to create the "Visitor" and "User" roles.
	 *
	 * @inheritdoc
	 */
	public function install(ErrorCollection $errors)
	{
		$rc = parent::install($errors);

		if (!$rc)
		{
			return $rc;
		}

		$model = $this->model;

		try
		{
			$this->model[1];
		}
		catch (RecordNotFound $e)
		{
			$role = Role::from([

				Role::NAME => $this->app->translate('Visitor')

			], [ $model ]);

			$role->save();
		}

		try
		{
			$this->model[2];
		}
		catch (RecordNotFound $e)
		{
			$role = Role::from([

				Role::NAME => $this->app->translate('User')

			], [ $model ]);

			$role->save();
		}

		return $rc;
	}

	public function is_installed(ErrorCollection $errors)
	{
		if (!parent::is_installed($errors))
		{
			return false;
		}

		try
		{
			$this->model->find([ 1, 2 ]);
		}
		catch (StatementNotValid $e)
		{
			/* the model */
		}
		catch (RecordNotFound $e)
		{
			if (!$e->records[1])
			{
				$errors->add($this->id, "Visitor role is missing");
			}

			if (!$e->records[2])
			{
				$errors->add($this->id, "User role is missing");
			}
		}

		return !$errors->count();
	}
}
