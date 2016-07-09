<?php

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
