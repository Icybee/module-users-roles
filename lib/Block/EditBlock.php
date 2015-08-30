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

use Brickrouge\Element;
use Brickrouge\Form;
use Brickrouge\Text;

use Icybee\Modules\Users\Roles\Role;

/**
 * A block to edit roles.
 *
 * @property Role $record
 */
class EditBlock extends \Icybee\Block\EditBlock
{
	protected function lazy_get_children()
	{
		return array_merge(parent::lazy_get_children(), [

			Role::NAME => new Text([

				Form::LABEL => 'name',
				Element::REQUIRED => true

			])
		]);
	}
}
