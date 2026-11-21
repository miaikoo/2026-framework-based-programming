<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?= base_url('assets/sb-admin/scripts.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script>
	// aktifkan pencarian + paginasi untuk setiap tabel dengan class .datatable
	document.querySelectorAll('table.datatable').forEach(function (t) {
		new simpleDatatables.DataTable(t, {
			perPage: 10,
			labels: { placeholder: 'Cari...', perPage: 'baris per halaman', noRows: 'Tidak ada data', info: 'Menampilkan {start}-{end} dari {rows} data' }
		});
	});
</script>
