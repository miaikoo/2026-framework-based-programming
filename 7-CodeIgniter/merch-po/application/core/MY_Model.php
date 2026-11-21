<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model dasar berisi operasi CRUD yang sama untuk semua tabel.
 * Model turunan cukup mengisi $table (dan $order bila perlu).
 */
class MY_Model extends CI_Model
{
	protected $table;
	protected $primary_key = 'id';
	protected $order = 'id ASC';

	public function all()
	{
		return $this->db->order_by($this->order)->get($this->table)->result();
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, array($this->primary_key => $id))->row();
	}

	public function insert(array $data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id, array $data)
	{
		return $this->db->update($this->table, $data, array($this->primary_key => $id));
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, array($this->primary_key => $id));
	}

	public function count()
	{
		return $this->db->count_all($this->table);
	}

	/** Pilihan untuk dropdown: array(id => label) */
	public function dropdown($label_field)
	{
		$options = array();
		foreach ($this->all() as $row)
		{
			$options[$row->{$this->primary_key}] = $row->$label_field;
		}
		return $options;
	}
}
