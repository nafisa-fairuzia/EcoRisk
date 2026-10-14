@props(['filterLabel' => 'kategori'])

<div class="table-tools" data-table-tools>
    <label class="table-tools__search">
        <span class="sr-only">Cari data tabel</span>
        <input
            type="search"
            data-table-search
            placeholder="Cari data pada tabel..."
            autocomplete="off" />
    </label>
    <label>
        <span class="sr-only">Filter berdasarkan {{ $filterLabel }}</span>
        <select data-table-filter>
            <option value="">Semua {{ $filterLabel }}</option>
        </select>
    </label>
    <p class="table-tools__count" data-table-count aria-live="polite"></p>
</div>
