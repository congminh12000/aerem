(function($) {

	'use strict';

	// Google Map
	$.WS_Form.prototype.form_google_map = function() {

		var ws_this = this;

		// Get Google Map objects
		var google_map_objects = $('[data-google-map]:not([data-init-google-map])', this.form_canvas_obj);
		var google_map_objects_count = google_map_objects.length;
		if(!google_map_objects_count) { return false;}

		// Google API Init
		this.form_google_maps_api_init();

		// Reset Google Maps arrays
		this.google_maps = [];

		google_map_objects.each(function() {

			$(this).attr('data-init-google-map', '');

			// Get field ID
			var field_id = ws_this.get_field_id($(this));

			// Get field object
			var field = ws_this.get_field($(this));

			// Build google_map object
			var google_map = {};

			// ID
			google_map.id = $(this).attr('id');

			// Map ID
			google_map.id_map = google_map.id + '-map';

			// $(this)
			google_map.obj = $(this);

			// Map obj
			google_map.obj_map = $('#' + google_map.id_map, ws_this.form_canvas_obj);

			// Height
			google_map.height = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_height', '')).output;
			if(google_map.height) {

				google_map.obj_map.css({

					'height': 0,
					'overflow': 'hidden',
					'padding-bottom': google_map.height,
					'position': 'relative'
				});
			}

			// Latitude
			google_map.lat = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_lat', '')).output;

			// Longitude
			google_map.lng = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_lng', '')).output;

			// Zoom
			google_map.zoom = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_zoom', '')).output;
			if(google_map.zoom) {

				google_map.zoom = parseInt(google_map.zoom, 10);

			} else {

				google_map.zoom = 14;
			}

			// Type
			google_map.type = ws_this.get_object_meta_value(field, 'google_map_type', '');
			if(!google_map.type) {

				google_map.type = 'roadmap';
			}

			// Search field ID
			google_map.search_field_id = parseInt(ws_this.get_object_meta_value(field, 'google_map_search_field_id', 0), 10);

			// Marker - Title
			google_map.marker_icon_title = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_marker_icon_title', '')).output;

			// Marker - Icon URL
			google_map.marker_icon_url = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_marker_icon_url', '')).output;

			// Routing - Icon URL - Origin (Might add origin and destination icon settings in future)
			google_map.routing_icon_url_origin = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_routing_icon_url_origin', '')).output;

			// Routing - Polyline Color
			google_map.routing_polyline_color = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_routing_polyline_color', '#418fde')).output;

			// Routing - Polyline Weight
			google_map.routing_polyline_weight = parseInt(ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_routing_polyline_weight', 5)).output, 10);
			if(google_map.routing_polyline_weight === 0) { google_map.routing_polyline_weight = 5; }

			// Style
			google_map.style = ws_this.parse_variables_process(ws_this.get_object_meta_value(field, 'google_map_style', '')).output;

			// Controls
			google_map.control_type = (ws_this.get_object_meta_value(field, 'google_map_control_type', 'on') !== '');
			google_map.control_full_screen = (ws_this.get_object_meta_value(field, 'google_map_control_full_screen', 'on') !== '');
			google_map.control_street_view = (ws_this.get_object_meta_value(field, 'google_map_control_street_view', 'on') !== '');
			google_map.control_zoom = (ws_this.get_object_meta_value(field, 'google_map_control_zoom', 'on') !== '');

			// Add to google_map array
			ws_this.google_maps[field_id] = google_map;

			ws_this.google_map_process(google_map);
		});
	}

	// Wait until Google Maps loaded, then process
	$.WS_Form.prototype.google_map_process = function(google_map, total_ms_start) {

		var ws_this = this;

		// Reposition flag
		google_map.reposition = true;

		// Timeout check
		if(typeof(total_ms_start) === 'undefined') { total_ms_start = new Date().getTime(); }
		if((new Date().getTime() - total_ms_start) > this.timeout_google_maps) {

			this.error('error_timeout_google_maps');
			return false;
		}

		if(window.google && window.google.maps) {

			wsf_google_maps_loaded = true;
		}

		// Check to see if Google Maps loaded
		if(!wsf_google_maps_loaded) {

			setTimeout(function() { ws_this.google_map_process(google_map, total_ms_start); }, this.timeout_interval);

			return false;
		}

		// Save default value
		google_map.obj.attr('data-default-value', google_map.obj.val());

		// Geocoder
		google_map.geocoder = new google.maps.Geocoder();

		// Height
		if(google_map.height) {

			google_map.obj_map.css({

				'height': 0,
				'overflow': 'hidden',
				'padding-bottom': google_map.height,
				'position': 'relative'
			});
		}

		// Run geolocator
		google_map.geolocate_process = function(position) {

			// Geocoder
			google_map.geocoder.geocode({ location: position }, function(results, status) {

				if(
					(status === 'OK') &&
					results[0]
				) {

					if(google_map.search_field_obj) {

						google_map.search_field_obj.val(results[0].formatted_address);
					}

					google_map.marker_set_position(position, results[0]);

				} else {

					if(google_map.search_field_obj) {

						google_map.search_field_obj.val('');
					}

					google_map.marker_set_position(position);
				}
			});
		}

		// Set field value
		google_map.set_field_value = function(field_value_obj) {

			// Build latitude,longitude string
			var field_value = JSON.stringify(field_value_obj);

			// Set hidden value
			var trigger = (google_map.obj.val() !== field_value);
			google_map.obj.val(field_value);
			if(trigger) { google_map.obj.trigger('change'); }
		}

		// Marker position function
		google_map.marker_set_position = function(position, place) {

			// Get latitude and longitude
			var latitude = position.lat();
			var longitude = position.lng();

			var field_value_obj = {

				'lat': latitude,
				'lng': longitude,
				'zoom' : google_map.map.getZoom(),
				'map_type_id' : google_map.map.getMapTypeId(),
			}

			if(typeof(place) !== 'undefined') {

				// Place ID
				field_value_obj.place_id = (typeof(place.place_id) ? place.place_id : '');

				// Address
				field_value_obj.address = (typeof(place.formatted_address) ? place.formatted_address : '');

				// Name
				field_value_obj.name = (typeof(place.name) ? place.name : '');

				if(typeof(place.address_components) !== 'undefined') {

					place.address_components.forEach(function(address_component) {

						var types = address_component.types;

						// City
						if(types.indexOf('locality') !== -1) { field_value_obj.city = address_component.long_name; }

						// State
						if(types.indexOf('administrative_area_level_1') !== -1) { field_value_obj.state = address_component.long_name; }

						// Country Short / Long
						if(types.indexOf('country') !== -1) {

							field_value_obj.country = address_component.long_name;
							field_value_obj.country_short = address_component.short_name;
						}
					});
				}
			}

			google_map.set_field_value(field_value_obj);
		}

		// On change event
		google_map.obj.on('change', function() {

			var marker_visible = false;

			var field_value = $(this).val();

			try {

				// Check for regular format
				var field_value_obj = JSON.parse(field_value);

			} catch (e) {

				// Support for comma separated values
				var field_value_array = field_value.split(',');

				if(field_value_array.length === 2) {

					var field_value_obj = {

						lat: field_value_array[0],
						lng: field_value_array[1]
					};

					if(typeof(field_value_array[2]) !== 'undefined') {

						field_value_obj.zoom = field_value_array[2]
					}

					if(typeof(field_value_array[3]) !== 'undefined') {

						field_value_obj.map_type_id = field_value_array[3]
					}

					$(this).val(JSON.stringify(field_value_obj));

					google_map.geolocate_process(new google.maps.LatLng(parseFloat(field_value_obj.lat), parseFloat(field_value_obj.lng)));

				} else {

					var field_value_obj = false;
				}
			}

			if(field_value_obj !== false) {

				var position = new google.maps.LatLng(parseFloat(field_value_obj.lat), parseFloat(field_value_obj.lng));
				marker_visible = true;

				// Populate address
				if(
					google_map.search_field_obj &&
					field_value_obj.address &&
					(field_value_obj.address != google_map.search_field_obj.val())
				) {

					google_map.search_field_obj.val(field_value_obj.address);
				}

				// Set zoom
				if(
					field_value_obj.zoom &&
					(field_value_obj.zoom != google_map.map.getZoom())
				) {

					google_map.map.setZoom(field_value_obj.zoom);
				}

				// Set map type ID
				if(
					field_value_obj.map_type_id &&
					(field_value_obj.map_type_id != google_map.map.getMapTypeId())
				) {

					google_map.map.setMapTypeId(field_value_obj.map_type_id);
				}

			} else {

				if(google_map.lat && google_map.lng) {

					var position = new google.maps.LatLng(parseFloat(google_map.lat), parseFloat(google_map.lng));

				} else {

					var position = new google.maps.LatLng(29.95, -90.08);	// Fallback to New Orleans, LA
				}

				// Reset zoom
				google_map.map.setZoom(google_map.zoom);

				// Reset type
				google_map.map.setMapTypeId(google_map.type);
			}

			// Move the marker
			google_map.marker.setPosition(position);
			google_map.marker.setVisible(marker_visible);

			// Move map
			if(google_map.reposition) {

				google_map.map.setCenter(position);
			}
		});

		// Default options
		var options = {

			id: google_map.id_map,
			zoom: google_map.zoom,
			mapTypeId: google_map.type,
			clickableIcons: false,
			gestureHandling: 'cooperative',
			mapTypeControl: google_map.control_type,
			fullscreenControl: google_map.control_full_screen,
			streetViewControl: google_map.control_street_view,
			zoomControl: google_map.control_zoom
		};

		if(google_map.style) {

			try {

				options.styles = JSON.parse(google_map.style);

			} catch(e) {

				this.error('error_google_map_style_js');
			}
		}

		// Create map
		var map = new google.maps.Map(google_map.obj_map[0], options);
		google_map.map = map;

		// Initialize directions renderer
		google_map.directions_renderer = false;

		// On zoom changed event
		google_map.map.addListener('zoom_changed', function() {

			var field_value = google_map.obj.val();
			if(field_value == '') { return; }

			try {

				// Check for regular format
				var field_value_obj = JSON.parse(field_value);

			} catch (e) { return; }

			if(typeof(field_value_obj) === 'object') {

				field_value_obj.zoom = google_map.map.getZoom();
				google_map.set_field_value(field_value_obj);
			}
		});

		// On map type change
		google_map.map.addListener('maptypeid_changed', function() {

			var field_value = google_map.obj.val();
			if(field_value == '') { return; }

			try {

				// Check for regular format
				var field_value_obj = JSON.parse(field_value);

			} catch (e) { return; }

			if(typeof(field_value_obj) === 'object') {

				field_value_obj.map_type_id = google_map.map.getMapTypeId();
				google_map.set_field_value(field_value_obj);
			}
		});

		// Map click event handler
		google_map.map.addListener('click', function(e) {

			google_map.reposition = false;

			google_map.marker_set_position(e.latLng);

			google_map.geolocate_process(e.latLng);
		});

		// Build map marker options
		var options_marker = {

			map: map,
			visible: false,
			draggable: true
		};

		// Marker - Icon
		if(google_map.marker_icon_url) {

			if(typeof(options_marker.icon) === 'undefined') { options_marker.icon = {}; }
			options_marker.icon.url = google_map.marker_icon_url;
		}

		// Marker - Title
		if(google_map.marker_icon_title) {

			options_marker.title = google_map.marker_icon_title;
		}

		// Add marker
		google_map.marker = new google.maps.Marker(options_marker);

		// Marker events
		google_map.marker.addListener('dragend', function() {

			google_map.reposition = false;

			var latitude = this.getPosition().lat();
			var longitude = this.getPosition().lng();

			google_map.geolocate_process(new google.maps.LatLng(latitude, longitude));
		})

		// Clear event
		google_map.obj.parent().find('[data-action="wsf-google-map-clear"]').on('click', function(e) {

			e.preventDefault();

			google_map.obj.val('').trigger('change');
		});

		// Search field ID
		if(google_map.search_field_id) {

			// Get section repeatable index
			var section_repeatable_suffix = ws_this.get_section_repeatable_suffix(google_map.obj);

			// Get search input
			var field_id = parseInt(google_map.search_field_id, 10);
			var search_field_obj = $('[id^="' + this.esc_selector(this.form_id_prefix + 'field-' + field_id.toString() + section_repeatable_suffix) + '"]', ws_this.form_canvas_obj);
			if(section_repeatable_suffix && !search_field_obj.length) {

				// Check outside of repeater
				var search_field_obj = $('[id^="' + this.esc_selector(ws_this.form_id_prefix + 'field-' + field_id.toString()) + '"]', ws_this.form_canvas_obj);
			}
			if(search_field_obj.length) {

				google_map.search_field_obj = search_field_obj;

				// Set up search box
				var search_field_element = search_field_obj[0];
				google_map.search_box = new google.maps.places.SearchBox(search_field_element);

				google_map.map.addListener('bounds_changed', function() {

					google_map.search_box.setBounds(google_map.map.getBounds());
				});

				google_map.search_box.addListener('places_changed', function() {

					google_map.reposition = true;

					var places = google_map.search_box.getPlaces();

					if(places.length == 0) { return; }

					places.forEach(function(place) {

						if(!place.geometry) { return; }

						google_map.marker_set_position(place.geometry.location, place);
					});
				});
			}
		}

		// Initial change
		google_map.obj.trigger('change');
	}

})(jQuery);
