(() => {
    'use strict';

    const API = '/api/buku';
    const canManageBooks = Boolean(window.CAN_MANAGE_BOOKS);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const $ = (id) => document.getElementById(id);
    const elements = {
        table: $('tabel-buku'),
        message: $('pesan-api'),
        pageInfo: $('info-halaman'),
        previous: $('btn-sebelumnya'),
        next: $('btn-berikutnya'),
        reload: $('btn-muat-ulang'),
        search: $('cari-buku'),
        category: $('filter-kategori'),
        sort: $('urut-buku'),
        searchButton: $('btn-cari'),
        resetSearch: $('btn-reset-cari'),
        form: $('form-buku'),
        categorySelect: $('kategori_id'),
        categoryPanel: $('panel-kategori-baru'),
        categoryName: $('nama-kategori-baru'),
        categoryMessage: $('pesan-kategori-baru'),
        addCategoryButton: $('btn-tambah-kategori'),
        saveCategoryButton: $('btn-simpan-kategori'),
        cancelCategoryButton: $('btn-batal-kategori'),
    };

    if (!elements.table) return;

    let currentPage = 1;
    let lastPage = 1;
    let currentSearch = '';
    let currentCategory = '';
    let currentSort = 'terbaru';
    let daftarBuku = [];

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    })[char]);

    function tampilPesan(message, type = 'success') {
        if (!elements.message) return;
        elements.message.className = `api-message ${type}`;
        elements.message.textContent = message;
        elements.message.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    async function apiRequest(url, options = {}) {
        const headers = {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            ...(options.headers || {}),
        };
        if (options.body) headers['Content-Type'] = 'application/json';

        let response;
        try {
            response = await fetch(url, { ...options, headers, credentials: 'same-origin' });
        } catch {
            throw new Error('Layanan data tidak dapat diakses. Periksa server Laravel dan koneksi internet.');
        }

        if (response.status === 204) return null;
        const result = await response.json().catch(() => ({}));
        if (!response.ok) {
            if (response.status === 419) throw new Error('Sesi Anda telah berakhir. Muat ulang halaman, lalu coba kembali.');
            if (response.status === 422 && result.errors) {
                throw new Error(Object.values(result.errors).flat().join(' ') || 'Periksa kembali data yang dimasukkan.');
            }
            if (response.status === 401) throw new Error('Sesi Anda telah berakhir. Silakan masuk kembali.');
            if (response.status === 403) throw new Error('Akun Anda tidak memiliki izin untuk melakukan tindakan ini.');
            if (response.status === 404) throw new Error(result.message || 'Data buku tidak ditemukan.');
            throw new Error(result.message || `Permintaan gagal (HTTP ${response.status}).`);
        }
        return result;
    }

    function resetCategoryPanel() {
        if (elements.categoryPanel) elements.categoryPanel.hidden = true;
        if (elements.categoryName) elements.categoryName.value = '';
        if (elements.categoryMessage) elements.categoryMessage.textContent = '';
        if (elements.saveCategoryButton) {
            elements.saveCategoryButton.disabled = false;
            elements.saveCategoryButton.textContent = 'Simpan Kategori';
        }
    }

    function resetForm() {
        if (!elements.form) return;
        elements.form.reset();
        $('buku-id').value = '';
        $('judul-form').textContent = 'Tambah Buku';
        $('btn-simpan').textContent = 'Simpan Buku';
        $('btn-batal').hidden = true;
        $('btn-simpan').disabled = false;
        resetCategoryPanel();
    }

    function addCategoryOption(select, id, name) {
        if (!select) return;
        const exists = Array.from(select.options).some((option) => option.value === String(id));
        if (exists) return;
        const option = document.createElement('option');
        option.value = String(id);
        option.textContent = name;
        select.add(option);
    }

    async function simpanKategoriBaru() {
        if (!canManageBooks || !elements.categoryName || !elements.saveCategoryButton) return;

        const nama = elements.categoryName.value.trim();
        if (nama.length < 2 || nama.length > 80) {
            if (elements.categoryMessage) {
                elements.categoryMessage.textContent = 'Nama kategori harus terdiri dari 2–80 karakter.';
                elements.categoryMessage.className = 'inline-category-message error';
            }
            elements.categoryName.focus();
            return;
        }

        const button = elements.saveCategoryButton;
        button.disabled = true;
        button.textContent = 'Menyimpan...';
        if (elements.categoryMessage) {
            elements.categoryMessage.textContent = '';
            elements.categoryMessage.className = 'inline-category-message';
        }

        try {
            const result = await apiRequest(window.CATEGORY_STORE_URL || '/buku/kategori', {
                method: 'POST',
                body: JSON.stringify({ nama }),
            });

            const kategori = result?.data;
            if (!kategori?.id || !kategori?.nama) {
                throw new Error('Kategori telah diproses, tetapi data dari server belum lengkap. Muat ulang halaman.');
            }

            addCategoryOption(elements.categorySelect, kategori.id, kategori.nama);
            addCategoryOption(elements.category, kategori.id, kategori.nama);
            if (elements.categorySelect) elements.categorySelect.value = String(kategori.id);

            const total = $('total-kategori');
            if (total) {
                const currentTotal = Number(String(total.textContent).replace(/[^0-9]/g, '')) || 0;
                total.textContent = new Intl.NumberFormat('id-ID').format(currentTotal + 1);
            }

            resetCategoryPanel();
            tampilPesan(`Kategori "${kategori.nama}" berhasil ditambahkan dan langsung dipilih.`, 'success');
        } catch (error) {
            if (elements.categoryMessage) {
                elements.categoryMessage.textContent = error.message;
                elements.categoryMessage.className = 'inline-category-message error';
            }
            button.disabled = false;
            button.textContent = 'Simpan Kategori';
        }
    }

    function renderTable(data) {
        daftarBuku = Array.isArray(data) ? data : [];
        const emptyMessage = currentSearch || currentCategory
            ? 'Tidak ada buku yang sesuai dengan pencarian atau filter.'
            : 'Belum ada data buku.';

        if (!daftarBuku.length) {
            elements.table.innerHTML = `<tr><td colspan="${canManageBooks ? 6 : 5}" class="table-empty">${escapeHtml(emptyMessage)}</td></tr>`;
            return;
        }

        elements.table.innerHTML = daftarBuku.map((buku, index) => {
            const id = Number(buku.id);
            const safeId = Number.isSafeInteger(id) && id > 0 ? id : 0;
            const deleteForm = canManageBooks ? `
                <form method="POST" action="/buku/${safeId}" class="inline-delete-form" data-delete-book-form data-title="${escapeHtml(buku.judul)}">
                    <input type="hidden" name="_token" value="${escapeHtml(csrfToken)}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="button danger small" ${Number(buku.peminjamans_count || 0) > 0 ? 'disabled title="Buku memiliki riwayat peminjaman"' : ''}>Hapus</button>
                </form>` : '';

            return `
                <tr>
                    <td>${((currentPage - 1) * 10) + index + 1}</td>
                    <td><span class="category-badge">${escapeHtml(buku.kategori || 'Tanpa kategori')}</span></td>
                    <td><a href="/buku/${safeId}">${escapeHtml(buku.judul)}</a></td>
                    <td>${escapeHtml(buku.penulis)}</td>
                    <td>${escapeHtml(buku.tahun)}</td>
                    ${canManageBooks ? `<td class="action-cell">
                        <button type="button" class="button secondary small" data-action="edit" data-id="${safeId}">Ubah</button>
                        ${deleteForm}
                    </td>` : ''}
                </tr>`;
        }).join('');
    }

    async function muatBuku(page = 1) {
        elements.table.innerHTML = `<tr><td colspan="${canManageBooks ? 6 : 5}" class="table-empty">Memuat data buku...</td></tr>`;
        const params = new URLSearchParams({ page: String(page), per_page: '10', sort: currentSort });
        if (currentSearch) params.set('cari', currentSearch);
        if (currentCategory) params.set('kategori_id', currentCategory);

        try {
            const result = await apiRequest(`${API}?${params.toString()}`);
            currentPage = result.meta?.current_page ?? page;
            lastPage = result.meta?.last_page ?? 1;
            renderTable(result.data ?? []);
            if (elements.pageInfo) {
                const total = result.meta?.total ?? daftarBuku.length;
                elements.pageInfo.textContent = `Menampilkan ${new Intl.NumberFormat('id-ID').format(daftarBuku.length)} dari ${new Intl.NumberFormat('id-ID').format(total)} buku • Halaman ${currentPage} dari ${lastPage}`;
            }
            if (elements.previous) elements.previous.disabled = currentPage <= 1;
            if (elements.next) elements.next.disabled = currentPage >= lastPage;
        } catch (error) {
            elements.table.innerHTML = `<tr><td colspan="${canManageBooks ? 6 : 5}" class="table-empty">Data buku gagal dimuat. Silakan muat ulang halaman.</td></tr>`;
            tampilPesan(error.message, 'error');
            if (elements.pageInfo) elements.pageInfo.textContent = 'Data belum tersedia.';
            if (elements.previous) elements.previous.disabled = true;
            if (elements.next) elements.next.disabled = true;
        }
    }

    if (canManageBooks && elements.form) {
        elements.addCategoryButton?.addEventListener('click', () => {
            if (!elements.categoryPanel) return;
            elements.categoryPanel.hidden = !elements.categoryPanel.hidden;
            if (!elements.categoryPanel.hidden) elements.categoryName?.focus();
            else resetCategoryPanel();
        });

        elements.saveCategoryButton?.addEventListener('click', simpanKategoriBaru);
        elements.categoryName?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                simpanKategoriBaru();
            }
        });
        elements.cancelCategoryButton?.addEventListener('click', resetCategoryPanel);

        elements.form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (!elements.form.reportValidity()) return;

            const id = $('buku-id').value;
            const payload = {
                kategori_id: Number($('kategori_id').value),
                judul: $('judul').value.trim(),
                penulis: $('penulis').value.trim(),
                tahun_terbit: Number($('tahun_terbit').value),
            };
            const saveButton = $('btn-simpan');
            const originalLabel = id ? 'Simpan Perubahan' : 'Simpan Buku';
            saveButton.disabled = true;
            saveButton.textContent = 'Menyimpan...';

            try {
                await apiRequest(id ? `${API}/${id}` : API, {
                    method: id ? 'PUT' : 'POST',
                    body: JSON.stringify(payload),
                });
                tampilPesan(id ? 'Perubahan buku berhasil disimpan.' : 'Buku berhasil ditambahkan ke katalog.');
                resetForm();
                await muatBuku(id ? currentPage : 1);
            } catch (error) {
                tampilPesan(error.message, 'error');
                saveButton.disabled = false;
                saveButton.textContent = originalLabel;
            }
        });

        elements.table.addEventListener('click', (event) => {
            const target = event.target instanceof Element ? event.target : event.target?.parentElement;
            const button = target?.closest('button[data-action="edit"]');
            if (!button) return;

            const id = Number(button.dataset.id);
            const buku = daftarBuku.find((item) => Number(item.id) === id);
            if (!Number.isSafeInteger(id) || id < 1 || !buku) {
                tampilPesan('Data buku tidak ditemukan. Muat ulang daftar buku.', 'error');
                return;
            }

            $('buku-id').value = String(buku.id);
            $('kategori_id').value = String(buku.kategori_id ?? '');
            $('judul').value = buku.judul ?? '';
            $('penulis').value = buku.penulis ?? '';
            $('tahun_terbit').value = buku.tahun ?? '';
            $('judul-form').textContent = `Ubah Buku #${id}`;
            $('btn-simpan').textContent = 'Simpan Perubahan';
            $('btn-batal').hidden = false;
            $('panel-form-buku')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            $('judul')?.focus({ preventScroll: true });
        });

        elements.table.addEventListener('submit', (event) => {
            const form = event.target instanceof Element ? event.target.closest('form[data-delete-book-form]') : null;
            if (!form) return;
            const title = form.dataset.title || 'buku ini';
            if (!window.confirm(`Apakah Anda yakin ingin menghapus buku "${title}" dari katalog? Tindakan ini tidak dapat dibatalkan.`)) {
                event.preventDefault();
            }
        });

        $('btn-batal')?.addEventListener('click', () => {
            resetForm();
            tampilPesan('Perubahan dibatalkan. Formulir siap digunakan untuk menambahkan buku baru.');
        });
    }

    function applyFilters() {
        currentSearch = elements.search?.value.trim() ?? '';
        currentCategory = elements.category?.value ?? '';
        currentSort = elements.sort?.value ?? 'terbaru';
        muatBuku(1);
    }

    elements.searchButton?.addEventListener('click', applyFilters);
    elements.search?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') { event.preventDefault(); applyFilters(); }
    });
    elements.category?.addEventListener('change', applyFilters);
    elements.sort?.addEventListener('change', applyFilters);
    elements.resetSearch?.addEventListener('click', () => {
        if (elements.search) elements.search.value = '';
        if (elements.category) elements.category.value = '';
        if (elements.sort) elements.sort.value = 'terbaru';
        currentSearch = '';
        currentCategory = '';
        currentSort = 'terbaru';
        muatBuku(1);
    });
    elements.reload?.addEventListener('click', () => muatBuku(currentPage));
    elements.previous?.addEventListener('click', () => muatBuku(currentPage - 1));
    elements.next?.addEventListener('click', () => muatBuku(currentPage + 1));

    muatBuku();
})();
