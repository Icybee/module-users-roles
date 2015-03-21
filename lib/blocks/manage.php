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

use ICanBoogie\Module\Descriptor;
use ICanBoogie\Operation;

use Brickrouge\A;
use Brickrouge\Button;
use Brickrouge\Document;
use Brickrouge\Element;
use Brickrouge\Form;

/**
 * @property-read \ICanBoogie\Module\ModuleCollection $modules
 */
class ManageBlock extends Form
{
	static protected function add_assets(Document $document)
	{
		parent::add_assets($document);

		$document->css->add(\Icybee\ASSETS . 'css/manage.css', -170);
		$document->css->add('manage.css');
	}

	protected function get_modules()
	{
		return $this->app->modules;
	}

	public function __construct(Module $module, array $attributes = [])
	{
		$this->module = $module;

		$actions = null;

		if ($this->app->user->has_permission(Module::PERMISSION_ADMINISTER, $module))
		{
			$actions = new Button('Save permissions', [

				'class' => 'btn-primary',
				'type' => 'submit',
				'value' => Module::OPERATION_PERMISSIONS

			]);
		}

		parent::__construct($attributes + [

			self::ACTIONS => $actions,
			self::HIDDENS => [

				Operation::DESTINATION => $module->id,
				Operation::NAME => Module::OPERATION_PERMISSIONS

			],

			'class' => 'form-primary',
			'name' => 'roles/manage'

		]);
	}

	public function render()
	{
		$packages = [];
		$modules = $this->modules;

		foreach ($modules->descriptors as $m_id => $descriptor)
		{
			if (!isset($modules[$m_id]))
			{
				continue;
			}

			$name = isset($descriptor[Descriptor::TITLE]) ? $descriptor[Descriptor::TITLE] : $m_id;

			if (isset($descriptor[Descriptor::PERMISSION]))
			{
				if ($descriptor[Descriptor::PERMISSION] != Module::PERMISSION_NONE)
				{
					$name .= ' <em>(';
					$name .= Module::$levels[$descriptor[Descriptor::PERMISSION]];
					$name .= ')</em>';
				}
				else if (empty($descriptor[Descriptor::PERMISSIONS]))
				{
					continue;
				}
			}

			if (isset($descriptor[Descriptor::CATEGORY]))
			{
				$package = $descriptor[Descriptor::CATEGORY];
			}
			else
			{
				list($package) = explode('.', $m_id);
			}

			$package = $this->t($package, [], [ 'scope' => 'module_category', 'default' => $package ]);

			$packages[$package][$this->t($name)] = array_merge($descriptor, [

				Descriptor::ID => $m_id

			]);
		}

		uksort($packages, 'ICanBoogie\unaccent_compare_ci');

		$packages = array_merge([

			$this->t('General') => [

				$this->t('All') => [ Descriptor::ID => 'all' ]

			]

		], $packages);

		#
		# load roles
		#

		$roles = $this->module->model->all;

		//
		// create manager
		//

		$rc = '';

		// table

		$rc .= '<div class="listview"><table class="manage" cellpadding="4" cellspacing="0">';

		//
		// table header
		//

		$span = 1;
		$app = $this->app;
		$context = $app->site->path;

		$rc .= '<thead>';
		$rc .= '<tr>';
		$rc .= '<th>&nbsp;</th>';

		foreach ($roles as $role)
		{
			$span++;

			$rc .= '<th><div>';

			if ($role->rid == 0)
			{
				$rc .= $role->title;
			}
			else
			{
				$rc .= new Element('a', [

					Element::INNER_HTML => $role->name,
					'href' => $context . '/admin/' . $this->module . '/' . $role->rid . '/edit',
					'title' => $this->t('Edit entry')

				]);
			}

			$rc .= '</div></th>';
		}

		$rc .= '</tr>';
		$rc .= '</thead>';

		if (1)
		{
			$n = 0;
			$actions_rows = '';

			foreach ($roles as $role)
			{
				$actions_rows .= '<td>';

				if ($role->rid == 1 || $role->rid == 2)
				{
					$actions_rows .= '&nbsp;';
				}
				else
				{
					++$n;

					$actions_rows .= new A($this->t('Delete', [], [ 'scope' => 'button' ]), \ICanBoogie\Routing\contextualize('/admin/users.roles/' . $role->rid . '/delete'), [

						'class' => 'btn btn-danger'

					]);
				}

				$actions_rows .= '</td>';
			}

			if ($n)
			{
				$rc .= <<<EOT
<tfoot>
	<tr class="footer">
		<td>
		<div class="jobs">
			<a class="operation-delete" href="#" rel="op-delete">Delete the selected entries</a>
		</div>
		</td>

		$actions_rows

	</tr>
</tfoot>
EOT;
			}
		}

		$rc .= '<tbody>';

		//
		//
		//

		$role_options = [];

		foreach (Module::$levels as $i => $level)
		{
			$role_options[$i] = $this->t('permission.' . $level, [], [ 'default' => $level ]);
		}

		$user_has_access = $app->user->has_permission(Module::PERMISSION_ADMINISTER, $this->module);
		$routes = $app->routes;

		foreach ($packages as $p_name => $modules)
		{
			$rc .= '<tr class="listview-divider">';
			$rc .= '<td colspan="' . $span . '">';
			$rc .= $p_name;
			$rc .= '</td>';
			$rc .= '</tr>';

			$n = 0;

			//
			// admins
			//

			uksort($modules, 'ICanBoogie\unaccent_compare_ci');

			foreach ($modules as $m_name => $m_desc)
			{
				$m_id = $m_desc[Descriptor::ID];
				$flat_id = strtr($m_id, '.', '_');


				$rc .= '<tr class="admin">';

				$rc .= '<td>';
				$rc .= $routes->find('/admin/' . $m_id) ? '<a href="' . $context . '/admin/' . $m_id . '">' . $m_name . '</a>' : $m_name;
				$rc .= '</td>';

				foreach ($roles as $role)
				{
					$rc .= '<td>';

					if (isset($m_desc[Descriptor::PERMISSION]))
					{
						if ($m_desc[Descriptor::PERMISSION] != Module::PERMISSION_NONE)
						{
							$level = $m_desc[Descriptor::PERMISSION];

							$rc .= new Element(Element::TYPE_CHECKBOX, [

								'name' => 'roles[' . $role->rid . '][' . $m_id . ']',
								'checked' => isset($role->levels[$m_id]) && ($role->levels[$m_id] = $level)

							]);
						}
						else
						{
							$rc .= '&nbsp;';
						}
					}
					else
					{
						if ($user_has_access)
						{
							$options = $role_options;

							if ($m_id != 'all')
							{
								$options = [ 'inherit' => '' ] + $options;
							}

							$rc .= new Element('select', [

								Element::OPTIONS => $options,

								'name' => 'roles[' . $role->rid . '][' . $m_id . ']',
								'value' => isset($role->perms[$m_id]) ? $role->perms[$m_id] : null

							]);
						}
						else
						{
							$level = isset($role->perms[$m_id]) ? $role->perms[$m_id] : null;

							if ($level)
							{
								$rc .= Module::$levels[$level];
							}
							else
							{
								$rc .= '&nbsp;';
							}
						}
					}

					$rc .= '</td>';
				}

				$rc .= '</tr>';

				#
				# Permissions
				#
				# e.g. "modify own profile"
				#

				if (empty($m_desc[Descriptor::PERMISSIONS]))
				{
					continue;
				}

				$perms = $m_desc[Descriptor::PERMISSIONS];

				foreach ($perms as $pname)
				{
					$columns = '';

					foreach ($roles as $role)
					{
						$columns .= '<td>' . new Element(Element::TYPE_CHECKBOX, [

							'name' => $user_has_access ? 'roles[' . $role->rid . '][' . $pname . ']' : NULL,
							'checked' => $role->has_permission($pname)

						])
						. '</td>';
					}

					$label = $this->t($pname, [], [ 'scope' => [ $flat_id, 'permission' ] ]);

					$rc .= <<<EOT
<tr class="perm">
	<td><span title="$pname">$label</span></td>
	$columns
</tr>
EOT;
				}
			}
		}

		$rc .= '</tbody>';
		$rc .= '</table></div>';

		$this->inner_html = $rc;

		return parent::render();
	}
}
