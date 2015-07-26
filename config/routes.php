<?php

namespace Icybee\Modules\Users\Roles;

use ICanBoogie\HTTP\Request;
use Icybee\Routing\RouteMaker as Make;

return Make::admin('users.roles', Routing\RolesAdminController::class, [

	'only' => [ 'index', 'create', 'edit' ]

]);
