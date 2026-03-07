(function($) {

	'use strict';

	// Loader
	$.WS_Form.prototype.form_loader = function() {

		var ws_this = this;

		// If loader already exists, skip
		if($('.wsf-loader', this.form_canvas_obj).length) { return; }

		// Prepend loader div to form
		this.form_canvas_obj.prepend(this.form_loader_get_html());

		// CSS
		this.form_loader_css_vars();

		// On resize
		var loader_resize_observer = new ResizeObserver((entries) => {

			ws_this.form_loader_resize();
		})
		loader_resize_observer.observe(this.form_canvas_obj[0]);
	}

	// Set CSS vars
	$.WS_Form.prototype.form_loader_css_vars = function() {

		var css_var_root = this.form_canvas_obj[0];

		// Background color
		this.form_loader_css_var_set_color(css_var_root, 'loader_overlay_color', '--wsf-loader-overlay-color', '#ffffff');

		// Background opacity
		this.form_loader_css_var_set_range(css_var_root, 'loader_overlay_opacity', '--wsf-loader-overlay-opacity', 0.5, 0, 1);

		// Cursor
		this.form_loader_css_var_set_value(css_var_root, 'loader_overlay_cursor', '--wsf-loader-overlay-cursor', 'wait');

		// Fade-in duration
		this.form_loader_css_var_set_range(css_var_root, 'loader_fade_in_duration', '--wsf-loader-fade-in-duration', 0, 0, 5, 's');

		// Fade-out duration
		this.form_loader_css_var_set_range(css_var_root, 'loader_fade_out_duration', '--wsf-loader-fade-out-duration', 0, 0, 5, 's');

		// Sprite animation duration
		this.form_loader_css_var_set_range(css_var_root, 'loader_sprite_animation_duration', '--wsf-loader-sprite-animation-duration', 1, 0.1, 5, 's');

		// Sprite Color
		this.form_loader_css_var_set_color(css_var_root, 'loader_sprite_color', '--wsf-loader-sprite-color', '#000000');

		// Sprite Accent color
		this.form_loader_css_var_set_color(css_var_root, 'loader_sprite_color_accent', '--wsf-loader-sprite-color-accent', '#418fde');

		// Sprite Opacity
		this.form_loader_css_var_set_range(css_var_root, 'loader_sprite_opacity', '--wsf-loader-sprite-opacity', 1, 0, 1);

		// Sprite Accent opacity
		this.form_loader_css_var_set_range(css_var_root, 'loader_sprite_opacity_accent', '--wsf-loader-sprite-opacity-accent', 1, 0, 1);

		// Sprite Size
		this.form_loader_css_var_set_range(css_var_root, 'loader_sprite_size', '--wsf-loader-sprite-size', 48, 10, 400, 'px');

		// Sprite Border width
		this.form_loader_css_var_set_range(css_var_root, 'loader_sprite_border_width', '--wsf-loader-sprite-border', 5, 1, 100, 'px');

		// Text display
		this.form_loader_css_var_set_display(css_var_root, 'loader_text_display', '--wsf-loader-text-display', 'none');

		// Get loader obj
		var loader_obj = $('.wsf-loader', this.form_canvas_obj);
	}

	// Set css variable - Range slider
	$.WS_Form.prototype.form_loader_css_var_set_range = function(css_var_root, meta_key, css_var, default_value, min, max, suffix) {

		if(typeof(suffix) === 'undefined') { suffix = ''; }

		var value = parseFloat(this.get_object_meta_value(this.form, meta_key, default_value));
		if(
			(value < min) ||
			(value > max)
		) {
			value = default_value;
		}

		css_var_root.style.setProperty(css_var, value + suffix);
	}

	// Set css variable - Color
	$.WS_Form.prototype.form_loader_css_var_set_color = function(css_var_root, meta_key, css_var, default_value) {

		var value = this.get_object_meta_value(this.form, meta_key, default_value);

		var rgb_array = this.hex_to_rgb(value);

		if(rgb_array === false) { rgb_array = this.hex_to_rgb(default_value); }

		if(rgb_array === false) { return false; }

		css_var_root.style.setProperty(css_var, rgb_array.r + ', ' + rgb_array.g + ', ' + rgb_array.b);
	}

	// Set css variable - Display
	$.WS_Form.prototype.form_loader_css_var_set_display = function(css_var_root, meta_key, css_var, default_value) {

		var value = this.get_object_meta_value(this.form, meta_key, default_value);

		css_var_root.style.setProperty(css_var, value ? 'block' : 'none');
	}

	// Set css variable - Value
	$.WS_Form.prototype.form_loader_css_var_set_value = function(css_var_root, meta_key, css_var, default_value) {

		var value = this.get_object_meta_value(this.form, meta_key, default_value);

		css_var_root.style.setProperty(css_var, value);
	}

	// Resize
	$.WS_Form.prototype.form_loader_resize = function() {

		// Get form width
		var width = this.form_canvas_obj.width();

		// Get form height
		var height = this.form_canvas_obj.height();

		// Get loader obj
		var loader_obj = $('.wsf-loader', this.form_canvas_obj);

		// Set loader width and height
		loader_obj.width(width).height(height);

		// Get loader inner obj
		var loader_inner_obj = $('.wsf-loader-inner', loader_obj);

		// Get loader inner width
		var loader_inner_width = loader_inner_obj.width();

		// Get loader inner height
		var loader_inner_height = loader_inner_obj.height();

		// Set alignment
		var css_var_root = this.form_canvas_obj[0];

		css_var_root.style.setProperty('--wsf-loader-sprite-offset-top-align', (loader_inner_height * -0.5) + 'px');
		css_var_root.style.setProperty('--wsf-loader-sprite-offset-left-align', (loader_inner_width * -0.5) + 'px');
	}

	// Get HTML
	$.WS_Form.prototype.form_loader_get_html = function() {

		return '<div class="wsf-loader"><div class="wsf-loader-inner" role="alert" aria-live="assertive"></div></div>';
	}

	// Show loader
	$.WS_Form.prototype.form_loader_show = function(loader_event) {

		if(typeof(loader_event) === 'undefined') { return; }

		// If form loader already showing, skip
		if(this.form_loader_showing) { return; }

		var ws_this = this;

		// Build loader show function
		var loader_show = function() {

			// Get loader type
			var loader_sprite_type = ws_this.get_object_meta_value(ws_this.form, 'loader_sprite_type', 'rotate-25-gap');

			// Builder loader HTML according to typr
			var loader_sprite_html = '';

			switch(loader_sprite_type) {

				case '' :

					loader_sprite_html = '';
					break;

				case 'html' :

					loader_sprite_html = ws_this.get_object_meta_value(ws_this.form, 'loader_sprite_html', '');
					break;

				default :

					loader_sprite_html = '<div class="wsf-loader-sprite wsf-loader-sprite-' + ws_this.esc_attr(loader_sprite_type) + '"></div>';

					// Get text
					var loader_text = ws_this.get_object_meta_value(ws_this.form, 'loader_text', '');
					if(loader_text != '') {

						// We allow HTML in the loader text
						loader_sprite_html += '<p>' + loader_text + '</p>';
					}

					break;
			}

			// Set HTML of loader (prompts accessibility reader)
			$('.wsf-loader .wsf-loader-inner', ws_this.form_canvas_obj).html(loader_sprite_html);

			// Show loader
			$('.wsf-loader', ws_this.form_canvas_obj).show();

			// Set form classes
			ws_this.form_obj.addClass('wsf-form-loader-show').removeClass('wsf-form-loader-hide');

			// Resize loader
			ws_this.form_loader_resize();

			// Set start
			ws_this.form_loader_show_start = new Date();

			// Log
			ws_this.log('log_loader_show');
		}

		// Check if loader event is enabled
		if(loader_event != 'conditional') {

			if(!this.get_object_meta_value(this.form, 'loader_event_' + loader_event)) { return; }
		}

		// Set showing
		ws_this.form_loader_showing = true;

		// Get delay
		var loader_event_delay = parseFloat(this.get_object_meta_value(this.form, 'loader_event_' + loader_event + '_delay'));

		// Show loader
		if(loader_event_delay) {

			this.form_loader_timeout_id = setTimeout(function() { loader_show(); }, (loader_event_delay * 1000));

		} else {

			loader_show();
		}
  	}

	// Hide loader
	$.WS_Form.prototype.form_loader_hide = function(timeout) {

		if(typeof(timeout) === 'undefined') { timeout = true; }

		// If form loader is not show, skip
		if(!this.form_loader_showing) { return; }

		// Clear any pending timeouts
		if(this.form_loader_timeout_id) {

			clearTimeout(this.form_loader_timeout_id);
		}

		var ws_this = this;

		// Build loader hide function
		var loader_hide = function() {

			// Set form classes
			ws_this.form_obj.removeClass('wsf-form-loader-show').addClass('wsf-form-loader-hide');

			// Get fade-out duration
			var loader_fade_out_duration = parseFloat(ws_this.get_object_meta_value(ws_this.form, 'loader_fade_out_duration', '0'));

			// Hide loader after fade-out completes
			setTimeout(function() {

				// Hide loader
				$('.wsf-loader', ws_this.form_canvas_obj).hide();

				// Clear HTML of loader (ready for next prompt for accessibility reader)
				$('.wsf-loader .wsf-loader-inner', ws_this.form_canvas_obj).html('');

				// Log
				ws_this.log('log_loader_hide');

			}, (loader_fade_out_duration * 1000));
		}

		// Set not showing
		ws_this.form_loader_showing = false;

		// Calculate timeout. Form will not be locked longer than form_post_lock_duration_max.
		var form_loader_show_duration = new Date() - this.form_loader_show_start;

		var timeout_duration = Math.max(this.form_loader_show_duration_max - form_loader_show_duration, 0);

		// Hide loader
		timeout ? setTimeout(function() { loader_hide(); }, timeout_duration) : loader_hide();
	}

})(jQuery);
