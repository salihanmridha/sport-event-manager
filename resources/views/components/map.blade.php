<link href='https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css' rel='stylesheet' />
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
    type="text/css">

<div wire:ignore>
    <div id='map' style='height: {{ $attributes['height'] }};'></div>
</div>

<script src='https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js'></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
<script>
    const defaultLocation = [{{ $attributes['data-long']? $attributes['data-long']: 120.9842 }}, {{ $attributes['data-lat']? $attributes['data-lat']: 14.5995 }}];
    const ACCESS_TOKEN = "{{ env('MAPBOX_ACCESS_KEY') }}"
    mapboxgl.accessToken = ACCESS_TOKEN;
    const map = new mapboxgl.Map({
        container: 'map', // container ID
        style: 'mapbox://styles/mapbox/streets-v11', // style URL
        center: defaultLocation, // starting position [lng, lat]
        zoom: 13, // starting zoom
        projection: 'globe' // display the map as a 3D globe
    });

    function updateLatLong(lat, lng, place_name='') {
        @this.set("{{ $attributes['lat'] }}", lat);
        @this.set("{{ $attributes['lng'] }}", lng);
        if ("{{ $attributes['place_name'] }}") {
            @this.set("{{ $attributes['place_name'] }}", place_name);
        }
    }

    function updatePlaceName(adress){
    @this.set("{{ $attributes['place_name'] }}", adress);
    }

    map.on('style.load', () => {
        map.setFog({}); // Set the default atmosphere style

        // Get lnglat on click
        map.on('click', function(e) {
            mapClickFn(e.lngLat);
            var coordinates = e.lngLat;
            // @this.set("{{ $attributes['lat'] }}", e.lngLat.lat);
            // @this.set("{{ $attributes['lng'] }}", e.lngLat.lng);
            updateLatLong(e.lngLat.lat, e.lngLat.lng);
            marker.setLngLat([e.lngLat.lng, e.lngLat.lat])
        });

        const coordinatesGeocoder = function(query) {
            const matches = query.match(
                /^[ ]*(?:Lat: )?(-?\d+\.?\d*)[, ]+(?:Lng: )?(-?\d+\.?\d*)[ ]*$/i
            );
            if (!matches) {
                return null;
            }

            function coordinateFeature(lng, lat) {
                return {
                    center: [lng, lat],
                    geometry: {
                        type: 'Point',
                        coordinates: [lng, lat]
                    },
                    place_name: 'Lat: ' + lat + ' Lng: ' + lng,
                    place_type: ['coordinate'],
                    properties: {},
                    type: 'Feature'
                };
            }

            const coord1 = Number(matches[1]);
            const coord2 = Number(matches[2]);
            const geocodes = [];

            if (coord1 < -90 || coord1 > 90) {
                // must be lng, lat
                geocodes.push(coordinateFeature(coord1, coord2));
            }

            if (coord2 < -90 || coord2 > 90) {
                // must be lat, lng
                geocodes.push(coordinateFeature(coord2, coord1));
            }

            if (geocodes.length === 0) {
                // else could be either lng, lat or lat, lng
                geocodes.push(coordinateFeature(coord1, coord2));
                geocodes.push(coordinateFeature(coord2, coord1));
            }

            return geocodes;
        };

        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            localGeocoder: coordinatesGeocoder,
            zoom: 13,
            placeholder: 'Search',
            mapboxgl: mapboxgl,
            reverseGeocode: true,
            marker: false
        })
        // Add the control to the map.
        map.addControl(
            geocoder
        );

        geocoder.on('result', (e) => {
            marker.setLngLat(e.result.geometry.coordinates);
            updateLatLong(e.result.geometry.coordinates[1], e.result.geometry.coordinates[0], e.result.place_name);
        });
        const marker = new mapboxgl.Marker({
                draggable: true
            })
            .setLngLat(defaultLocation)
            .addTo(map);

        function onDragEnd() {
            const lngLat = marker.getLngLat();
            // coordinates.style.display = 'block';
            // coordinates.innerHTML = `Longitude: ${lngLat.lng}<br />Latitude: ${lngLat.lat}`;
            // marker.setLngLat([lngLat.lng, lngLat.lat])
            mapClickFn(lngLat)
            updateLatLong(lngLat.lat, lngLat.lng);
        }

        marker.on('dragend', onDragEnd);

        function mapClickFn(coordinates) {
            const url =
                "https://api.mapbox.com/geocoding/v5/mapbox.places/" +
                coordinates.lng +
                "," +
                coordinates.lat +
                ".json?access_token=" +
                ACCESS_TOKEN +
                "&types=poi";
            $.get(url, function (data) {
                if (data.features.length > 0) {
                    let address = data.features[0].place_name;
                    updatePlaceName(address)
                } else {
                    console.log("No address found");
                }
            });
        }
    });
</script>
