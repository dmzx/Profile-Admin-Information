<?php
/**
*
* @package phpBB Extension - Profile Admin Information
* @copyright (c) 2020 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\profileadmininfo\migrations;

use phpbb\db\migration\profilefield_base_migration;
use phpbb\db\sql_insert_buffer;

class profileadmininfo_install extends profilefield_base_migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT COUNT(field_id) as field_count
			FROM ' . PROFILE_FIELDS_TABLE . "
			WHERE field_name = 'profileadmininfo'";
		$result = $this->db->sql_query($sql);
		$field_count = (int) $this->db->sql_fetchfield('field_count');
		$this->db->sql_freeresult($result);

		return $field_count;
	}

	public static function depends_on()
	{
		return [
			'\phpbb\db\migration\data\v330\v330'
		];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'create_custom_field']]],
		];
	}

	protected $profilefield_name = 'profileadmininfo';

	protected $profilefield_database_type = ['MTEXT', ''];

	protected $profilefield_data = [
		'field_name'			=> 'profileadmininfo',
		'field_type'			=> 'profilefields.type.text',
		'field_ident'			=> 'profileadmininfo',
		'field_length'			=> '5|30',
		'field_minlen'			=> '2',
		'field_maxlen'			=> '500',
		'field_novalue'			=> '',
		'field_default_value'	=> '',
		'field_validation'		=> '',
		'field_required'		=> 0,
		'field_show_novalue'	=> 0,
		'field_show_on_reg'		=> 0,
		'field_show_on_pm'		=> 0,
		'field_show_on_vt'		=> 0,
		'field_show_profile'	=> 1,
		'field_no_view'			=> 0,
		'field_active'			=> 1,
		'field_hide'			=> 1,
		'field_is_contact'		=> 0,
		'field_contact_desc'	=> '',
		'field_contact_url'		=> '',
	];

	public function create_custom_field()
	{
		$sql = 'SELECT MAX(field_order) as max_field_order
			FROM ' . PROFILE_FIELDS_TABLE;
		$result = $this->db->sql_query($sql);
		$max_field_order = (int) $this->db->sql_fetchfield('max_field_order');
		$this->db->sql_freeresult($result);

		$sql_ary = array_merge($this->profilefield_data, [
			'field_order'			=> $max_field_order + 1,
		]);

		$sql = 'INSERT INTO ' . PROFILE_FIELDS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
		$this->db->sql_query($sql);
		$field_id = (int) $this->db->sql_nextid();

		$insert_buffer = new sql_insert_buffer($this->db, PROFILE_LANG_TABLE);

		$sql = 'SELECT lang_id
			FROM ' . LANG_TABLE;
		$result = $this->db->sql_query($sql);
		$lang_name = (strpos($this->profilefield_name, 'phpbb_') === 0) ? strtoupper(substr($this->profilefield_name, 6)) : strtoupper($this->profilefield_name);
		while ($lang_id = (int) $this->db->sql_fetchfield('lang_id'))
		{
			$insert_buffer->insert([
				'field_id'				=> (int) $field_id,
				'lang_id'				=> (int) $lang_id,
				'lang_name'				=> $lang_name,
				'lang_explain'			=> $lang_name . '_EXPLAIN',
				'lang_default_value'	=> '',
			]);
		}
		$this->db->sql_freeresult($result);

		$insert_buffer->flush();
	}
}
