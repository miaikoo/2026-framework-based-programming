<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen_model extends MY_Model
{
	protected $table = 'dosen';
	protected $order = 'nama ASC';

	public function rules($id = NULL)
	{
		$unique = function ($field) use ($id) {
			return $id ? '|callback__unique_except['.$field.']' : '|is_unique[dosen.'.$field.']';
		};
		return array(
			array('field' => 'nidn',  'label' => 'NIDN',  'rules' => 'trim|required|numeric|exact_length[10]'.$unique('nidn')),
			array('field' => 'nama',  'label' => 'Nama',  'rules' => 'trim|required|max_length[100]'),
			array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email'.$unique('email')),
		);
	}

	public function exists_except($field, $value, $id)
	{
		return $this->db->where($field, $value)->where('id !=', $id)->count_all_results($this->table) > 0;
	}

	/** Dosen beserta jumlah kelas yang diampu */
	public function with_jumlah_kelas()
	{
		return $this->db->select('d.*, COUNT(k.id) AS jumlah_kelas')
			->from('dosen d')
			->join('kelas k', 'k.dosen_id = d.id', 'left')
			->group_by('d.id')
			->order_by('d.nama')
			->get()->result();
	}
}
