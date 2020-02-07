<?php
/**
*
* @package phpBB Extension - Profile Admin Information
* @copyright (c) 2020 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\profileadmininfo\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use dmzx\profileadmininfo\core\profileadmininfo;
use phpbb\template\template;

class main_listener implements EventSubscriberInterface
{
	/* @var profileadmininfo */
	protected $profileadmininfo;

	/* @var template */
	protected $template;

	/**
	 * Constructor
	 *
	 * @param profileadmininfo		$profileadmininfo
	 * @param template				$template
	 */
	public function __construct(
		profileadmininfo $profileadmininfo,
		template $template
	)
	{
		$this->profileadmininfo	= $profileadmininfo;
		$this->template			= $template;
	}

	public static function getSubscribedEvents()
	{
		return array(
			'core.user_setup'							=> 'load_language_on_setup',
			'core.memberlist_prepare_profile_data'		=> 'posting_modify_template_vars',
		);
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext	= $event['lang_set_ext'];
		$lang_set_ext[]	= [
			'ext_name' => 'dmzx/profileadmininfo',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function posting_modify_template_vars($event)
	{
		$this->template->assign_block_vars('profileadmininfo',
			$this->profileadmininfo->view_profileadmininfo($event['data']['user_id']
		));
	}
}
