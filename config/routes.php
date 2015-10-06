<?php

namespace Icybee\Modules\Users\Roles;

use ICanBoogie\HTTP\Request;
use Icybee\Routing\RouteMaker as Make;

return Make::admin('users.roles', Routing\RolesAdminController::class, [

	'id_name' => 'rid',
	'only' => [ Make::ACTION_INDEX, Make::ACTION_NEW, Make::ACTION_EDIT, Make::ACTION_CONFIRM_DELETE ]

]);
