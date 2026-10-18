const riskMapElement = document.querySelector('[data-risk-map]');
const locationPickerElement = document.querySelector('[data-location-picker]');

document.querySelectorAll('[data-password-toggle]').forEach((button) => {
	const input = document.getElementById(button.getAttribute('aria-controls'));

	if (!input) {
BBB	}

	button.addEventListener('click', () => {
		const isVisible = input.type === 'text';
		input.type = isVisible ? 'password' : 'text';
		button.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
	});
});

document.querySelectorAll('[data-user-avatar]').forEach((avatar) => {
	const image = avatar.querySelector('[data-user-avatar-image]');
	const fallback = avatar.querySelector('[data-user-avatar-fallback]');

	if (!(image instanceof HTMLImageElement) || !fallback) {
		return;
	}

	const showFallback = () => {
		image.classList.add('hidden');
		fallback.classList.remove('hidden');
	};

	image.addEventListener('error', showFallback, { once: true });

	if (image.complete && image.naturalWidth === 0) {
		showFallback();
	}
});

document.querySelectorAll('[data-profile-photo-input]').forEach((input) => {
	const preview = document.querySelector('[data-profile-photo-preview]');
	const initials = document.querySelector('[data-profile-photo-initials]');
	const feedback = document.querySelector('[data-profile-photo-feedback]');
	let previewUrl = null;

	if (!(input instanceof HTMLInputElement) || !preview || !initials || !feedback) {
		return;
	}

	const existingPhotoUrl = preview.getAttribute('src');
	const restorePreview = () => {
		if (previewUrl) {
			URL.revokeObjectURL(previewUrl);
			previewUrl = null;
		}

		if (existingPhotoUrl) {
			preview.src = existingPhotoUrl;
			preview.classList.remove('hidden');
			initials.classList.add('hidden');

			return;
		}

		preview.removeAttribute('src');
		preview.classList.add('hidden');
		initials.classList.remove('hidden');
	};

	input.addEventListener('change', () => {
		const file = input.files?.[0];
		feedback.textContent = '';

		if (!file) {
			restorePreview();

			return;
		}

		if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) {
			feedback.textContent = 'Pilih foto JPG, PNG, atau WebP dengan ukuran maksimal 2 MB.';
			input.value = '';
			restorePreview();

			return;
		}

		if (previewUrl) {
			URL.revokeObjectURL(previewUrl);
		}

		previewUrl = URL.createObjectURL(file);
		preview.src = previewUrl;
		preview.classList.remove('hidden');
		initials.classList.add('hidden');
	});
});

document.querySelectorAll('[data-table-container]').forEach((container) => {
	const tools = container.querySelector('[data-table-tools]');
	const table = container.querySelector('[data-filter-table]');
	const search = tools?.querySelector('[data-table-search]');
	const filter = tools?.querySelector('[data-table-filter]');
	const count = tools?.querySelector('[data-table-count]');
	const body = table?.tBodies[0];
	const filterColumn = Number(container.dataset.filterColumn);

	if (!tools || !table || !search || !filter || !count || !body || !Number.isInteger(filterColumn)) {
		return;
	}

	const rows = Array.from(body.rows).filter((row) => row.cells.length > filterColumn);
	const emptyState = rows.length > 0 ? document.createElement('tr') : null;

	if (emptyState) {
		emptyState.hidden = true;
		emptyState.dataset.tableNoResults = '';
		const message = document.createElement('td');
		message.colSpan = table.tHead?.rows[0]?.cells.length || 1;
		message.className = 'py-6 text-center text-neutral-500';
		message.textContent = 'Tidak ada data yang cocok.';
		emptyState.append(message);
		body.append(emptyState);
	}

	const options = new Map();
	rows.forEach((row) => {
		const cell = row.cells[filterColumn];
		const value = cell.textContent.trim().replace(/\s+/g, ' ');

		if (value && !options.has(value.toLocaleLowerCase())) {
			options.set(value.toLocaleLowerCase(), value);
		}
	});

	Array.from(options.values())
		.sort((first, second) => first.localeCompare(second, 'id'))
		.forEach((value) => {
			const option = document.createElement('option');
			option.value = value.toLocaleLowerCase();
			option.textContent = value;
			filter.append(option);
		});

	const updateRows = () => {
		const query = search.value.trim().toLocaleLowerCase();
		const selectedFilter = filter.value;
		let visibleRows = 0;

		rows.forEach((row) => {
			const matchesSearch = row.textContent.toLocaleLowerCase().includes(query);
			const matchesFilter = !selectedFilter
				|| row.cells[filterColumn].textContent.trim().replace(/\s+/g, ' ').toLocaleLowerCase() === selectedFilter;
			const isVisible = matchesSearch && matchesFilter;

			row.hidden = !isVisible;
			visibleRows += Number(isVisible);
		});

		if (emptyState) {
			emptyState.hidden = visibleRows > 0;
		}

		count.textContent = `Menampilkan ${visibleRows} dari ${rows.length} data di halaman ini`;
	};

	search.addEventListener('input', updateRows);
	filter.addEventListener('change', updateRows);
	updateRows();
});

if (riskMapElement || locationPickerElement) {
	Promise.all([import('leaflet'), import('leaflet/dist/leaflet.css')])
		.then(([leafletModule]) => {
			const leaflet = leafletModule.default;
			const tileLayer = (map) => leaflet
				.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					maxZoom: 19,
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
				})
				.addTo(map);

			if (locationPickerElement) {
				const latitudeInput = document.querySelector('[data-location-latitude]');
				const longitudeInput = document.querySelector('[data-location-longitude]');
				const addressInput = document.querySelector('#alamat');
				const status = document.querySelector('[data-location-status]');
				const locationButton = document.querySelector('[data-location-current]');
				const reportForm = locationPickerElement.closest('form');
				const initialLatitude = Number(locationPickerElement.dataset.latitude);
				const initialLongitude = Number(locationPickerElement.dataset.longitude);
				const hasInitialLocation = Number.isFinite(initialLatitude)
					&& Number.isFinite(initialLongitude)
					&& locationPickerElement.dataset.latitude !== ''
					&& locationPickerElement.dataset.longitude !== '';
				const initialPosition = hasInitialLocation
					? [initialLatitude, initialLongitude]
					: [-6.2, 106.8];
				const map = leaflet.map(locationPickerElement, {
					scrollWheelZoom: false,
				}).setView(initialPosition, hasInitialLocation ? 16 : 11);
				let marker = null;

				tileLayer(map);

				const selectLocation = (latitude, longitude) => {
					const position = [latitude, longitude];

					if (marker) {
						marker.setLatLng(position);
					} else {
						marker = leaflet.circleMarker(position, {
							color: '#047857',
							fillColor: '#10b981',
							fillOpacity: 1,
							radius: 9,
							weight: 3,
						}).addTo(map);
					}

					latitudeInput.value = latitude.toFixed(7);
					longitudeInput.value = longitude.toFixed(7);
					addressInput.value = `Titik peta (${latitude.toFixed(7)}, ${longitude.toFixed(7)})`;
					status.textContent = 'Lokasi tersimpan. Klik peta lagi untuk menyesuaikan titik.';
					status.classList.remove('text-rose-600');
					status.classList.add('text-emerald-700');
				};

				map.on('click', (event) => {
					selectLocation(event.latlng.lat, event.latlng.lng);
				});

				reportForm.addEventListener('submit', (event) => {
					if (latitudeInput.value && longitudeInput.value) {
						return;
					}

					event.preventDefault();
					status.textContent = 'Pilih titik lokasi laporan pada peta sebelum mengirim.';
					status.classList.add('text-rose-600');
					locationPickerElement.focus();
				});

				locationButton.addEventListener('click', () => {
					if (!navigator.geolocation) {
						status.textContent = 'Perangkat ini tidak mendukung pembacaan lokasi otomatis. Silakan pilih titik pada peta.';
						status.classList.add('text-rose-600');
						return;
					}

					status.textContent = 'Meminta izin lokasi perangkat…';
					navigator.geolocation.getCurrentPosition(
						(position) => {
							const { latitude, longitude } = position.coords;

							map.setView([latitude, longitude], 17);
							selectLocation(latitude, longitude);
						},
						() => {
							status.textContent = 'Lokasi perangkat tidak tersedia. Pastikan izin lokasi aktif atau pilih titik pada peta.';
							status.classList.add('text-rose-600');
						},
						{ enableHighAccuracy: true, timeout: 10000 },
					);
				});

				if (hasInitialLocation) {
					selectLocation(initialLatitude, initialLongitude);
				}

				window.requestAnimationFrame(() => map.invalidateSize());
			}

			if (riskMapElement) {
				const points = JSON.parse(riskMapElement.dataset.points || '[]');
				const map = leaflet.map(riskMapElement, {
					scrollWheelZoom: false,
				});

				tileLayer(map);

				const priorityRank = {
					low: 1,
					medium: 2,
					high: 3,
					critical: 4,
				};
				const priorityColors = {
					low: '#059669',
					medium: '#f59e0b',
					high: '#f97316',
					critical: '#e11d48',
				};
				const pointsByLocation = new Map();

				points.forEach((point) => {
					const locationKey = `${point.latitude},${point.longitude}`;
					const location = pointsByLocation.get(locationKey) || {
						latitude: point.latitude,
						longitude: point.longitude,
						reports: [],
					};

					location.reports.push(point);
					pointsByLocation.set(locationKey, location);
				});

				const bounds = [];

				pointsByLocation.forEach((location) => {
					const position = [location.latitude, location.longitude];
					const highestPriority = location.reports.reduce((highest, report) => (
						(priorityRank[report.priority_level] || 0) > (priorityRank[highest] || 0)
							? report.priority_level
							: highest
					), 'low');
					const color = priorityColors[highestPriority] || priorityColors.low;
					const marker = leaflet.circleMarker(position, {
						color,
						fillColor: color,
						fillOpacity: 0.78,
						radius: Math.min(13, 7 + location.reports.length),
						weight: 2,
					}).addTo(map);
					const popup = document.createElement('div');
					const coordinates = document.createElement('p');
					const reportCount = document.createElement('strong');

					coordinates.textContent = `Koordinat: ${location.latitude.toFixed(7)}, ${location.longitude.toFixed(7)}`;
					reportCount.textContent = `${location.reports.length} laporan pada titik ini`;
					popup.append(coordinates, reportCount);

					location.reports.forEach((point) => {
						const report = document.createElement('div');
						const address = document.createElement('strong');
						const priority = document.createElement('p');
						const score = document.createElement('p');
						const priorityLabels = {
							low: 'Rendah',
							medium: 'Sedang',
							high: 'Tinggi',
							critical: 'Kritis',
						};

						address.textContent = point.code || point.address || `Laporan #${point.id}`;
						priority.textContent = `Prioritas: ${priorityLabels[point.priority_level] || 'Belum ditentukan'}`;
						score.textContent = `Skor risiko: ${point.risk_score}`;
						report.append(address, priority, score);
						popup.append(report);
					});

					marker.bindPopup(popup);
					bounds.push(position);
				});

				if (bounds.length === 1) {
					map.setView(bounds[0], 14);
				} else if (bounds.length > 1) {
					map.fitBounds(bounds, { padding: [36, 36], maxZoom: 14 });
				} else {
					map.setView([-6.2, 106.8], 11);
				}

				window.requestAnimationFrame(() => map.invalidateSize());
			}
		})
		.catch((error) => {
			console.error('Gagal memuat peta EcoRisk.', error);
			const status = document.querySelector('[data-location-status]');

			if (status) {
				status.textContent = 'Peta gagal dimuat. Muat ulang halaman atau pilih lokasi setelah koneksi tersedia.';
				status.classList.add('text-rose-600');
			}
		});
}
