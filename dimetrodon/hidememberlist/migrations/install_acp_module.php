<?php
/**
 *
 * Hide Memberlist. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon], https://phpbbforever.com/home
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dimetrodon\hidememberlist\migrations;

class install_acp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['dimetrodon_hidememberlist_options']);
		return isset($this->config['dimetrodon_hideteam_options']);
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function update_data()
	{
		return [
			['config.add', ['dimetrodon_hidememberlist_options', 0]],
			['config.add', ['dimetrodon_hideteam_options', 0]],

			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_HIDEMEMBERLIST_TITLE'
			]],
			['module.add', [
				'acp',
				'ACP_HIDEMEMBERLIST_TITLE',
				[
					'module_basename'	=> '\dimetrodon\hidememberlist\acp\main_module',
					'modes'				=> ['settings'],
				],
			]],
		];
	}
}
