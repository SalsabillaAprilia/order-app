//Ambil nilai totalItem dari localStorage saat halaman dimuat
window.addEventListener('pageshow', function () {
    const savedTotal = localStorage.getItem('totalItem');
    if (savedTotal !== null) {
      const badge = document.getElementById('badge-cart');
      if (badge) badge.innerText = savedTotal;
    }
  });

// Fitur Tambah ke Keranjang
document.querySelectorAll('.form-tambah-keranjang').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault(); // biar gak reload

    const produkId = this.dataset.id;

    fetch('tambah-keranjang.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'produk_id=' + produkId
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        // Update badge jumlah item
        document.getElementById('badge-cart').innerText = data.totalItem;
        // Simpan ke localStorage supaya halaman lain bisa akses
        localStorage.setItem('totalItem', data.totalItem);
      }
    });
  });
});

// Fitur Update Kuantitas (+/-)
document.querySelectorAll('.form-update-kuantitas').forEach(form => {
  const produkId = form.dataset.id;

  form.querySelectorAll('.btn-kuantitas').forEach(btn => {
    btn.addEventListener('click', function () {
      const action = this.dataset.action;

      fetch('update-kuantitas.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${produkId}&action=${action}`
      })
      .then(res => res.json())
      .then(data => {

        if (data.status === 'success') {
            // Simpan total item ke localStorage
          localStorage.setItem('totalItem', data.totalItem);
            // Update kuantitas dan subtotal
          document.getElementById('qty-' + produkId).innerText = data.qty;
          document.getElementById('subtotal-' + produkId).innerText = new Intl.NumberFormat('id-ID').format(data.subtotal);
          document.getElementById('total-harga').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(data.total);
          const badge = document.getElementById('badge-cart');
          if (badge) badge.innerText = data.totalItem;
          if (data.qty <= 0) {
            form.closest('tr').remove();
            if (document.querySelectorAll('tbody tr').length === 0) {
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = "<tr><td colspan='5' class='text-center'>Keranjang kosong</td></tr>";
                document.getElementById('total-harga').innerText = 'Rp0';
            }
          }
        }
      });
    });
  });
});

// Fitur Hapus Produk
document.querySelectorAll('.form-hapus-produk').forEach(form => {
  const produkId = form.dataset.id;

  form.querySelector('button').addEventListener('click', function () {
    fetch('hapus-keranjang.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id=' + produkId
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        // Hapus baris dari tabel
        form.closest('tr').remove();

        const badge = document.getElementById('badge-cart');
        if (badge) badge.innerText = data.totalItem;
        localStorage.setItem('totalItem', data.totalItem);

        const sisaProduk = document.querySelectorAll('tbody tr').length;
        if (sisaProduk === 0) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = "<tr><td colspan='5' class='text-center'>Keranjang kosong</td></tr>";
            document.getElementById('total-harga').innerText = 'Rp0';
        } else {
            document.getElementById('total-harga').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(data.total);
        }
      }
    });
  });
});

