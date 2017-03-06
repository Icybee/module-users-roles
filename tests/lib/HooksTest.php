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

use Icybee\Modules\Users\User;

class HooksTest extends \PHPUnit_Framework_TestCase
{
	use MockSupport;

	public function test_resolve_user_permission()
	{
		$permission = uniqid();
		$target = uniqid();
		$expected = uniqid();

		$role = $this->mock(Role::class, function ($role) use ($permission, $target, $expected) {

			$role->has_permission($permission, $target)
				->shouldBeCalled()
				->willReturn($expected);

		});

		$user = new User;
		$user->role = $role;

		$this->assertSame($expected, Hooks::resolve_user_permission($user, $permission, $target));
	}
}
