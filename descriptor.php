<?php

namespace Icybee\Modules\Users\Roles;

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\Module\Descriptor;

return array
(
	Descriptor::CATEGORY => 'users',
	Descriptor::DESCRIPTION => 'Role management',
	Descriptor::MODELS => array
	(
		'primary' => array
		(
			Model::T_SCHEMA => array
			(
				'fields' => array
				(
					'rid' => 'serial',
					'name' => array('varchar', 32, 'unique' => true),
					'serialized_perms' => 'text'
				)
			)
		)
	),

	Descriptor::NS => __NAMESPACE__,
	Descriptor::REQUIRED => true,
	Descriptor::TITLE => 'Roles',
	Descriptor::VERSION => '1.0'
);