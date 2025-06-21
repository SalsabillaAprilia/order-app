document.addEventListener("DOMContentLoaded", function () {
  //Reload halaman dari server kalau user balik pakai tombol Back
  window.addEventListener("pageshow", function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
      if (window.location.pathname.includes('keranjang.php')) {
        window.location.reload();
      }
    }

    //Ambil nilai totalItem dari localStorage saat halaman dimuat
    const savedTotal = localStorage.getItem('totalItem');
      if (savedTotal !== null) {
        const badge = document.getElementById('badge-cart');
        if (badge) badge.innerText = savedTotal;
      }
  });

  // navlink
  const navLinks = document.querySelectorAll('.nav-link-click');

  navLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      // Untuk menandai link aktif secara visual (bukan navigasi)
      navLinks.forEach(l => l.classList.remove('clicked'));
      this.classList.add('clicked');
    });
  }); 
  
  document.querySelectorAll('.cart-icon').forEach(icon => {
    icon.addEventListener('click', function () {
      icon.classList.add('clicked');
      setTimeout(() => {
        icon.classList.remove('clicked');
      }, 150); // 150ms efek mengecil lalu balik normal
    });
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
          //Tambahkan efek klik icon cart
          const tombol = this.querySelector('.btn-cart-submit');
          tombol.classList.add('clicked');

          setTimeout(() => {
            tombol.classList.remove('clicked');
          }, 300);
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

                  //Update data-kosong
                  const btnCheckout = document.getElementById('btnCheckout');
                  if (btnCheckout) {
                      btnCheckout.dataset.kosong = "1";
                }
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

          const btnCheckout = document.getElementById('btnCheckout');
          if (sisaProduk === 0) {
              const tbody = document.querySelector('tbody');
              tbody.innerHTML = "<tr><td colspan='5' class='text-center'>Keranjang kosong</td></tr>";
              document.getElementById('total-harga').innerText = 'Rp0';

              //Update data-kosong
              if (btnCheckout) {
                  btnCheckout.dataset.kosong = "1";
              }
          }
        }
      });
    });
  });

  // popup keranjang kosong
  const btnCheckout = document.getElementById('btnCheckout');
  if (btnCheckout) {
    btnCheckout.addEventListener('click', function () {
      const keranjangKosong = this.dataset.kosong === "1";
      if (keranjangKosong) {
        Swal.fire({
          icon: 'warning',
          title: 'Keranjang Kosong!',
          text: 'Silakan tambahkan produk terlebih dahulu.',
          confirmButtonColor: '#6b6042'
        });
      } else {
        window.location.href = "checkout.php";
      }
    });
  }

  // hitung ongkir
  const ongkirKelurahan = {
    "Cibadak": 3000,
    "Kedung Badak": 4000,
    "Kedung Waringin": 5000,
    "Kayumanis": 6000,
    "Kencana": 6000,
    "Kedung Jaya": 7000,
    "Mekarwangi": 7000,
    "Semplak": 7000,
    "Tanah Sareal": 6000
  };

  const kelurahanSelect = document.getElementById("kelurahan");
  const ongkirDisplay = document.getElementById("ongkirDisplay");
  const ongkirInput = document.getElementById("ongkirInput");
  const totalAkhirEl = document.getElementById("totalAkhir");

  const ringkasan = document.getElementById("ringkasanPesanan");
  const totalBelanja = ringkasan ? parseInt(ringkasan.dataset.total) : 0;


  if (kelurahanSelect) {
    kelurahanSelect.addEventListener("change", function () {
      const kelurahan = this.value;
      const ongkir = ongkirKelurahan[kelurahan] || 0;

      if (ongkirDisplay) ongkirDisplay.innerText = `Rp${ongkir.toLocaleString("id-ID")}`;
      if (ongkirInput) ongkirInput.value = ongkir;

      if (totalAkhirEl) {
        const totalAkhir = totalBelanja + ongkir;
        totalAkhirEl.innerText = `Rp${totalAkhir.toLocaleString("id-ID")}`;
      }
    });
  }

  // cek stok sebelum bayar
  const btnBayar = document.getElementById('btnBayar');
  const form = document.getElementById('formCheckout');

  if (btnBayar && form) {
      btnBayar.addEventListener('click', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }
        
        const kelurahan = kelurahanSelect.value;
        const ongkir = ongkirKelurahan[kelurahan] || 0;
        if (ongkirInput) ongkirInput.value = ongkir;

        const formData = new FormData(form);

        fetch('get-snap-token.php', {
          method: 'POST',
          body: formData
        })
          .then(res => res.text())
          .then(snapToken => {
            if (snapToken.includes("habis")) {
              Swal.fire("Maaf 😓", snapToken, "warning");
              return;
            }
            snap.pay(snapToken, {
              onSuccess: function(result) {
                console.log('SUKSES:', result);
                window.location.href = 'pembayaran.php'; // redirect ke halaman pembayaran
              },
              onPending: function(result) {
                console.log('PENDING:', result);
                window.location.href = 'pembayaran.php';
              },
              onError: function(result) {
                console.log('ERROR:', result);
                Swal.fire("Error", "Pembayaran gagal 😢", "error");
              },
              onClose: function() {
                Swal.fire("Dibatalkan", "Kamu menutup pop-up pembayaran.", "info");
              }
            });
          });
      });
  }
  
  // Simpan ke localStorage jika ada di body (halaman pembayaran)
  const whatsapp = document.body.dataset.whatsapp;
  if (whatsapp) {
    localStorage.setItem('whatsapp', whatsapp);
  }

  // Ambil dari localStorage ke cookie jika cookie belum ada (halaman riwayat)
  const cookieHasWhatsapp = document.cookie.split(';').some(cookie => cookie.trim().startsWith('whatsapp='));
  const localWhatsapp = localStorage.getItem('whatsapp');

  if (!cookieHasWhatsapp && localWhatsapp) {
    document.cookie = "whatsapp=" + localWhatsapp + "; path=/";
    location.reload();
  }

});




