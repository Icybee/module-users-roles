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

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\I18n;

/**
 * Primary model of the Roles module (users.roles).
 *
 * @property-read \ICanBoogie\Core|\Icybee\Binding\CoreBindings $app
 */
class RoleModel extends Model
{
	/**
	 * If defined, the property {@link Role::PERMS} is serialized using the {@link json_encode()}
	 * function to set the property {@link Role::SERIALIZED_PERMS}.
	 *
	 * @inheritdoc
	 */
	public function save(array $properties, $key = null, array $options = [])
	{
		if (isset($properties[Role::PERMS]))
		{
			$properties[Role::SERIALIZED_PERMS] = json_encode($properties[Role::PERMS]);
		}

		return parent::save($properties, $key, $options);
	}

	/**
	 * @throws \Exception when on tries to delete the role with identifier "1".
	 *
	 * @inheritdoc
	 */
	public function delete($key)
	{
		if ($key == 1)
		{
			throw new \Exception(\ICanBoogie\format('The role %role (%rid) cannot be deleted.', [
				'%role' => $this->app->translate('Visitor'), '%rid' => $key
			]));
		}

		// FIXME-20110709: deleted role is not removed from users records.

		return parent::delete($key);
	}
}
