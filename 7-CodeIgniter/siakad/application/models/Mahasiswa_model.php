<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa_model extends MY_Model
{
	protected $table = 'mahasiswa';
	protected $order = 'nim ASC';

	public function rules($id = NULL)
	{
		// is_unique diabaikan untuk data yang sedang diedit
		$unique = function ($field) use ($id) {
			return $id ? '|callback__unique_except['.$field.']' : '|is_unique[mahasiswa.'.$field.']';
		};
		return array(
			array('field' => 'nim',      'label' => 'NIM',      'rules' => 'trim|required|numeric|exact_length[10]'.$unique('nim')),
			array('field' => 'nama',     'label' => 'Nama',     'rules' => 'trim|required|max_length[100]'),
			array('field' => 'gender',   'label' => 'Jenis kelamin', 'rules' => 'required|in_list[L,P]'),
			array('field' => 'prodi',    'label' => 'Program studi', 'rules' => 'trim|required|max_length[60]'),
			array('field' => 'angkatan', 'label' => 'Angkatan', 'rules' => 'required|integer|greater_than[2000]|less_than[2100]'),
			array('field' => 'email',    'label' => 'Email',    'rules' => 'trim|required|valid_email'.$unique('email')),
		);
	}

	public function exists_except($field, $value, $id)
	{
		return $this->db->where($field, $value)->where('id !=', $id)->count_all_results($this->table) > 0;
	}
}
