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
		//Set the location variable. Set up where we are. 
		$location = $this->user->page['page'];

		// Checking to see if the setting is enabled and that we are viewing a page pertaining to the memberlist.
		if ($this->config['dimetrodon_hidememberlist_options'] && substr($this->user->page['page_name'], 0, strpos($this->user->page['page_name'], '.')) === 'memberlist')
		{
			//Load the language file. We only have to do this once now. 
			$this->language->add_lang('common', 'dimetrodon/hidememberlist');


			// Exclude needed modes from admin permission checks.
			if (str_contains($location, 'viewprofile'))
			{
			    return;
			}
			
			if (str_contains($location, 'team'))
			{
			    return;
			}
			
			if (str_contains($location, 'email'))
			{
			    return;
			}
			if (str_contains($location, 'contactadmin'))
			{
			    return;
			}
			// End of exclusions. 
			

			// Are we trying to search a user?
			if ($this->user->page['page'] === 'memberlist.php?mode=searchuser' )
			{
				// Does this user lack administrative user permissions? 
				if (!$this->auth->acl_gets('a_user', 'a_userdel'))
				{
					// Display access denied message.
					trigger_error('MEMBERLIST_SEARCH_BLOCKED');
				}
			}
		
		
			// Are we trying to access group memberships?
			if (str_contains($location, 'group'))
			{
				// Does this user lack administrative privileges? 
				if (!$this->auth->acl_gets('a_user', 'a_userdel'))
				{
					// Display access denied message.
					trigger_error('MEMBERLIST_GROUP_BLOCKED');
				}
				
			
			}

			// Default is full memberlist. This gets loaded if no other conditions are met.
			// Does this user lack administrative user permissions? 
			if (!$this->auth->acl_gets('a_user', 'a_userdel'))
			{
				// Display access denied message.
				trigger_error('MEMBERLIST_FULL_BLOCKED');
			}
			
			

		}
		
	}
}
