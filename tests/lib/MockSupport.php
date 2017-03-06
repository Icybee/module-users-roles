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

trait MockSupport
{
	/**
	 * @param string $class
	 * @param callable|null $init
	 *
	 * @return mixed
	 */
	private function mock($class, callable $init = null)
	{
		$mock = $this->prophesize($class);

		if ($init)
		{
			$init($mock);
		}

		return $mock->reveal();
	}
}
