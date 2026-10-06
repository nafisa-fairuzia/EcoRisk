const riskMapElement = document.querySelector('[data-risk-map]');

if (riskMapElement) {
	Promise.all([import('leaflet'), import('leaflet/dist/leaflet.css')]).then(([leafletModule]) => {
		const leaflet = leafletModule.default;
		const points = JSON.parse(riskMapElement.dataset.points || '[]');
		const map = leaflet.map(riskMapElement, {
			scrollWheelZoom: false,
		});

		leaflet
			.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 19,
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
			})
			.addTo(map);

		const getRiskColor = (riskScore) => {
			if (riskScore >= 85) return '#e11d48';
			if (riskScore >= 60) return '#f97316';
			if (riskScore >= 30) return '#f59e0b';

			return '#059669';
		};

		const bounds = [];

		points.forEach((point) => {
			const position = [point.latitude, point.longitude];
			const color = getRiskColor(point.risk_score);
			const marker = leaflet.circleMarker(position, {
				color,
				fillColor: color,
				fillOpacity: 0.78,
				radius: Math.min(15, 8 + point.risk_score / 25),
				weight: 2,
			}).addTo(map);
			const popup = document.createElement('div');
			const address = document.createElement('strong');
			const priority = document.createElement('p');
			const score = document.createElement('p');

			address.textContent = point.address || 'Alamat tidak tersedia';
			priority.textContent = `Prioritas: ${point.priority_level}`;
			score.textContent = `Skor risiko: ${point.risk_score}`;
			popup.append(address, priority, score);
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
	});
}
