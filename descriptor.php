<?php

namespace Icybee\Modules\Users\Roles;

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\Module\Descriptor;

return [

	Descriptor::CATEGORY => 'users',
	Descriptor::DESCRIPTION => 'Role management',
	Descriptor::MODELS => [

		'primary' => [

			Model::SCHEMA => [

				'fields' => [

					'rid' => 'serial',
					'name' => [ 'varchar', 32, 'unique' => true ],
					'serialized_perms' => 'text'

				]
			]
		]
	],

	Descriptor::NS => __NAMESPACE__,
	Descriptor::REQUIRED => true,
	Descriptor::TITLE => 'Roles',
	Descriptor::VERSION => '1.0'

];
