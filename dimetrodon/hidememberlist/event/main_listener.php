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
use phpbb\language\language;
use phpbb\template\twig\twig;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Var Dump Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public function __construct(
		private auth $auth,
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
		// Are we in the full memberlist?
		if ($this->user->page['page'] === 'memberlist.php' )
		{
			//Load the language file.
			$this->language->add_lang('common', 'dimetrodon/hidememberlist');
			
			// Does this user lack administrative user permissions? 
			if (!$this->auth->acl_gets('a_user', 'a_userdel'))
			{
				// Display access denied message.
				trigger_error('MEMBERLIST_FULL_BLOCKED');
			}
		}
		
		// Are we trying to search a user?
		if ($this->user->page['page'] === 'memberlist.php?mode=searchuser' )
		{
			//Load the language file.
			$this->language->add_lang('common', 'dimetrodon/hidememberlist');
			
			// Does this user lack administrative user permissions? 
			if (!$this->auth->acl_gets('a_user', 'a_userdel'))
			{
				// Display access denied message.
				trigger_error('MEMBERLIST_SEARCH_BLOCKED');
			}
		}
		
		
		// This will not prevent viewing direct links to groups but this will prevent navigating to groups from a member profile.
		if ($this->user->page['page'] === 'memberlist.php?mode=group' )
		{
			//Load the language file.
			$this->language->add_lang('common', 'dimetrodon/hidememberlist');
			
			// Does this user lack administrative privileges? 
			if (!$this->auth->acl_gets('a_user', 'a_userdel'))
			{
				// Display access denied message.
				trigger_error('MEMBERLIST_GROUP_BLOCKED');
			}
				
			
		}
		
	}
}
