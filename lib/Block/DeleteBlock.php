<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Users\Roles\Block;

use Icybee\Modules\Users\Roles\Role;

/**
 * @property Role $record
 */
class DeleteBlock extends \Icybee\Block\DeleteBlock
{
	protected function get_record_name()
	{
		return $this->record->name;
	}
}
