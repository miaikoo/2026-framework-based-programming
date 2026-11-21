<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
	private $table = 'products';

	/** Aturan validasi form tambah/edit produk */
	public function rules()
	{
		return array(
			array('field' => 'name',        'label' => 'Nama produk', 'rules' => 'trim|required|max_length[100]'),
			array('field' => 'category',    'label' => 'Kategori',    'rules' => 'required|in_list[Lokal,Impor]'),
			array('field' => 'price',       'label' => 'Harga',       'rules' => 'trim|required|is_natural_no_zero'),
			array('field' => 'unit',        'label' => 'Satuan',      'rules' => 'required|in_list[kg,buah,sisir,pack]'),
			array('field' => 'stock',       'label' => 'Stok',        'rules' => 'trim|required|is_natural'),
			array('field' => 'description', 'label' => 'Deskripsi',   'rules' => 'trim|max_length[500]'),
		);
	}

	/**
	 * Ambil semua produk, bisa difilter kata kunci dan kategori.
	 */
	public function get_all($keyword = NULL, $category = NULL, $order = 'name ASC')
	{
		if ($keyword)
		{
			$this->db->group_start()
				->like('name', $keyword)
				->or_like('description', $keyword)
				->group_end();
		}
		if (in_array($category, array('Lokal', 'Impor'), TRUE))
		{
			$this->db->where('category', $category);
		}
		return $this->db->order_by($order)->get($this->table)->result();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where($this->table, array('product_id' => (int) $id))->row();
	}

	public function insert(array $data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id, array $data)
	{
		return $this->db->update($this->table, $data, array('product_id' => (int) $id));
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, array('product_id' => (int) $id));
	}

	/** Angka-angka untuk kartu ringkasan di dashboard */
	public function summary()
	{
		return $this->db->select('COUNT(*) AS total_produk,
				COALESCE(SUM(stock), 0) AS total_stok,
				COALESCE(SUM(stock * price), 0) AS nilai_stok,
				COALESCE(SUM(stock < 20), 0) AS stok_menipis', FALSE)
			->get($this->table)->row();
	}

	public function latest($limit = 5)
	{
		return $this->db->order_by('product_id', 'DESC')->limit($limit)->get($this->table)->result();
	}
}
