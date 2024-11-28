<?php
/**
 *
 * Hide Memberlist. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon]
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dimetrodon\hidememberlist\event;

/**
 * @ignore
 */
use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\language\language;
use phpbb\template\twig\twig;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Hide Memberlist Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public function __construct(
		private auth $auth,
		private config $config,
		private language $language,
		private twig $twig,
		private user $user,
		
	)
	{
	}
	
	public static function getSubscribedEvents(): array
	{
		return [
			'core.page_header_after' => 'header_after',
		];
	}



	/**
	 * Loads after the page header.
	 * Blocks access to memberlist to non-Admins. 
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function header_after($event): void
	{
		// Let's set our page variable. 
		$page = $this->user->page['page'];
		$exclude = ['viewprofile', 'team', 'email', 'contactadmin'];
		
		// Checking to see if the team page is disabled.
		if ($this->config['dimetrodon_hideteam_options'])
		{
			// Globally removing team link for all users.
			$this->twig->assign_var('U_TEAM', false);
			unset($exclude[1]);
		}
		
		// Globally removing memberlist links for non-admins if the extension is enabled. 
		if (!$this->auth->acl_gets('a_user', 'a_userdel'))
		{
			$this->twig->assign_var('S_DISPLAY_MEMBERLIST', false);
		}
		
		// Checking to see if we are viewing a page pertaining to the memberlist.
		if (str_contains($page, 'memberlist'))
		{
			//Load the language file. We only have to do this once now. 
			$this->language->add_lang('common', 'dimetrodon/hidememberlist');

			if (str_contains($page, 'mode'))
			{
				$page = substr($page, strpos($page, 'mode') + 5);
				$page = explode('/', str_replace(['=', '&'], '/', $page))[0];
			}

			if (in_array($page, $exclude))
			{
				return;
			}

			// Trigger denied message
			$this->access_denied_message($page);

		}	
	}
	
	private function access_denied_message($page)
	{
		$message = 'MEMBERLIST_' . strtoupper($page) . '_BLOCKED';
		if ($this->language->is_set($message) !== true)
		{
			$message = 'MEMBERLIST_FULL_BLOCKED';
		}

		// Display access denied message.
		if (!$this->auth->acl_gets('a_user', 'a_userdel'))
		{
			trigger_error($message);
		}
	}
}
