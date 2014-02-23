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

$hooks = __NAMESPACE__ . '\Hooks::';

return [

	'permission_resolver_list' => [

		'roles' => $hooks . 'resolve_user_permission'

	],

	'ownership_resolver_list' => [

		'roles' => $hooks . 'resolve_user_ownership'

	]

];