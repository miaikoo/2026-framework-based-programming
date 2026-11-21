<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman_model extends MY_Model
{
	protected $table = 'pengumuman';
	protected $order = 'created_at DESC';

	public function rules()
	{
		return array(
			array('field' => 'judul', 'label' => 'Judul', 'rules' => 'trim|required|max_length[150]'),
			array('field' => 'isi',   'label' => 'Isi',   'rules' => 'trim|required'),
			array('field' => 'untuk', 'label' => 'Ditujukan untuk', 'rules' => 'required|in_list[semua,dosen,mahasiswa]'),
		);
	}

	/** Pengumuman yang relevan untuk role tertentu */
	public function untuk_role($role, $limit = 5)
	{
		if ($role !== 'admin')
		{
			$this->db->where_in('untuk', array('semua', $role));
		}
		return $this->db->order_by($this->order)->limit($limit)->get($this->table)->result();
	}
}
