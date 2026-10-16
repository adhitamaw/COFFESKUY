// Perilaku kecil yang dipakai di semua halaman
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    const toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true,
    });
    window.csToast = (icon, title) => toast.fire({ icon, title });

    // Flash message dari server
    const flash = document.getElementById('flash-data');
    if (flash) {
        const data = JSON.parse(flash.textContent);
        Object.entries(data).forEach(([icon, title]) => title && csToast(icon, title));
    }

    // Konfirmasi sebelum submit: <form data-confirm="Hapus cafe ini?">
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form.dataset.confirm || form.dataset.confirmed) return;
        e.preventDefault();
        Swal.fire({
            title: form.dataset.confirm,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjut',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#c8873a',
        }).then((r) => {
            if (r.isConfirmed) {
                form.dataset.confirmed = '1';
                form.submit();
            }
        });
    });

    async function postJson(url) {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        if (res.status === 401) {
            window.location.href = '/login';
            return null;
        }
        if (!res.ok) throw new Error(res.statusText);
        return res.json();
    }

    document.addEventListener('click', async function (e) {
        // Favorit: <button data-favorite="/favorite/1">
        const fav = e.target.closest('[data-favorite]');
        if (fav) {
            e.preventDefault();
            try {
                const data = await postJson(fav.dataset.favorite);
                if (!data) return;
                const icon = fav.querySelector('i');
                icon.classList.toggle('fa-solid', data.status);
                icon.classList.toggle('fa-regular', !data.status);
                csToast(data.status ? 'success' : 'info', data.message);
                if (!data.status && fav.dataset.removeCard !== undefined) {
                    fav.closest('.col')?.remove();
                }
            } catch (err) {
                csToast('error', 'Gagal, coba lagi');
            }
            return;
        }

        // Tambah ke keranjang: <button data-add-cart="/cart/1">
        const add = e.target.closest('[data-add-cart]');
        if (add) {
            e.preventDefault();
            add.disabled = true;
            try {
                const data = await postJson(add.dataset.addCart);
                if (!data) return;
                document.querySelectorAll('[data-cart-count]').forEach((el) => {
                    el.textContent = data.count;
                    el.classList.remove('d-none');
                });
                csToast('success', data.message);
            } catch (err) {
                csToast('error', 'Gagal menambah ke keranjang');
            } finally {
                add.disabled = false;
            }
        }
    });

    // Tombol submit jadi loading supaya tidak dobel klik
    document.addEventListener('submit', function (e) {
        if (e.defaultPrevented) return;
        const btn = e.target.querySelector('[data-loading]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + btn.dataset.loading;
        }
    });
})();
