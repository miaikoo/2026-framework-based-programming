<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matakuliah_model extends MY_Model
{
	protected $table = 'matakuliah';
	protected $order = 'kode ASC';

	public function rules($id = NULL)
	{
		return array(
			array('field' => 'kode',     'label' => 'Kode MK',  'rules' => 'trim|required|alpha_numeric|exact_length[6]'.($id ? '|callback__unique_except[kode]' : '|is_unique[matakuliah.kode]')),
			array('field' => 'nama',     'label' => 'Nama MK',  'rules' => 'trim|required|max_length[100]'),
			array('field' => 'sks',      'label' => 'SKS',      'rules' => 'required|integer|greater_than[0]|less_than[7]'),
			array('field' => 'semester', 'label' => 'Semester', 'rules' => 'required|integer|greater_than[0]|less_than[9]'),
		);
	}

	public function exists_except($field, $value, $id)
	{
		return $this->db->where($field, $value)->where('id !=', $id)->count_all_results($this->table) > 0;
	}
}
