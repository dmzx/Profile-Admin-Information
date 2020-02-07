<?php
/**
*
* @package phpBB Extension - Profile Admin Information
* @copyright (c) 2020 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\profileadmininfo\core;

use phpbb\profilefields\manager;

class profileadmininfo
{
	/** @var manager */
	protected $profilefields_manager;

	/**
	 * Constructor
	 *
	 * @param manager		$profilefields_manager
	 */
	public function __construct(
		manager $profilefields_manager
	)
	{
		$this->profilefields_manager = $profilefields_manager;
	}

	public function view_profileadmininfo($user_id)
	{
		$profile_fields = $this->profilefields_manager->grab_profile_fields_data($user_id);

		if (!empty($profile_fields) && !empty($profile_fields[$user_id]['profileadmininfo']['value']))
		{
			$profileadmininfo = $profile_fields[$user_id]['profileadmininfo']['value'];
		}
		else
		{
			$profileadmininfo = '';
		}

		$template_array = [
			'PROFILEADMININFO_INFO'	=> $profileadmininfo ? $profileadmininfo : false,
		];

		return $template_array;
	}
}
