@php
    $mapId = $mapId ?? 'map';
    $centerLat = $centerLat ?? -7.75;
    $centerLng = $centerLng ?? 110.23;
    $zoom = $zoom ?? 13;
    $markersJson = $markersJson ?? '[]';
    $openInMapsLabel = __('messages.map.open_in_google_maps');
@endphp

<div id="{{ $mapId }}" class="w-full h-[500px] md:h-[600px] rounded-[2rem] border border-gray-200 shadow-sm overflow-hidden"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function() {
        var map = L.map('{{ $mapId }}').setView([{{ $centerLat }}, {{ $centerLng }}], {{ $zoom }});

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        var markers = [];
        var markerData = {!! $markersJson !!};
        var openInMapsLabel = {!! json_encode($openInMapsLabel) !!};

        markerData.forEach(function(m) {
            if (!m.lat || !m.lng) return;

            // Ikon bentuk rumah untuk homestay, aktivitas untuk kegiatan
            var color = m.type === 'homestay' ? '#00a877' : '#e8a838';
            var iconClass = m.type === 'homestay' ? 'bx-home-alt' : 'bx-run';
            // Label teks dari field title/tittle/kegiatan variabel m
            var labelText = m.name || m.title || m.kegiatan || '';

            // Icon + nama lengkap di bawah (boleh 2 baris)
            var iconHtml = '<div style="display: flex; flex-direction: column; align-items: center; justify-content: flex-start; line-height: 1;">' +
                '<i class="bx ' + iconClass + '" style="font-size: 32px; line-height: 1; color: ' + color + '; text-shadow: 0 1px 3px rgba(0,0,0,0.25);"></i>' +
                '<span style="font-size: 11px; font-weight: 700; line-height: 1.2; color: ' + color + '; text-align: center; white-space: normal; word-wrap: break-word; max-width: 150px; margin-top: 3px; text-shadow: 0 0 3px #ffffff, 0 0 5px #ffffff, 0 1px 2px rgba(255,255,255,0.9);">' + labelText + '</span>' +
                '</div>';
            var icon = L.divIcon({
                className: '',
                html: iconHtml,
                iconSize: [150, 64],
                iconAnchor: [75, 16],
                popupAnchor: [0, -16]
            });

            var marker = L.marker([m.lat, m.lng], {icon: icon}).addTo(map);
            if (m.popup) {
                var mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(m.lat + ',' + m.lng);
                var mapsLink = '<div style="margin-top:8px;">' +
                    '<a href="' + mapsUrl + '" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:#2B6B47;text-decoration:none;">' +
                    '<i class="bx bx-map" style="font-size:15px;"></i>' + openInMapsLabel + '</a></div>';
                marker.bindPopup(m.popup + mapsLink, {direction: 'top', offset: [0, -10]});
            }
            // Add click handler to fly to marker and open popup
            marker.on('click', function() {
                map.flyTo([m.lat, m.lng], map.getZoom() + 1, {animate: true});
                marker.openPopup();
            });
            // Make map accessible globally for list item clicks
            window.map = map;
            markers.push(marker);
        });

        if (markers.length > 0) {
            var group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.15));
        }

        // Function to fly to marker and open popup from list click
        window.flyToMarker = function(lat, lng) {
            map.flyTo([lat, lng], map.getZoom() + 1, {animate: true});
            // Find and open marker popup at these coordinates
            markers.forEach(function(marker) {
                var markerLat = marker.getLatLng().lat;
                var markerLng = marker.getLatLng().lng;
                if (markerLat === lat && markerLng === lng) {
                    marker.openPopup();
                }
            });
        };
    })();
</script>
