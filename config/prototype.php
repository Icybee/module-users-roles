<?php

namespace Icybee\Modules\Users\Roles;

$hooks = Hooks::class . '::';

return [

	'Icybee\Modules\Users\User::lazy_get_roles' => $hooks . 'user_get_roles',
	'Icybee\Modules\Users\User::lazy_get_role' => $hooks . 'user_get_role',

];
