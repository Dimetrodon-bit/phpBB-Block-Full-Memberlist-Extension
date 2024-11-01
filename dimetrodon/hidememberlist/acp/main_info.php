<?php
/**
 *
 * Hide Memberlist. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon], https://phpbbforever.com/home
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dimetrodon\hidememberlist\acp;

/**
 * Hide Memberlist ACP module info.
 */
class main_info
{
	public function module()
	{
		return [
			'filename'	=> '\dimetrodon\hidememberlist\acp\main_module',
			'title'		=> 'ACP_HIDEMEMBERLIST_TITLE',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'ACP_HIDEMEMBERLIST',
					'auth'	=> 'ext_dimetrodon/hidememberlist && acl_a_board',
					'cat'	=> ['ACP_HIDEMEMBERLIST_TITLE'],
				],
			],
		];
	}
}
