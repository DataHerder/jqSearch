/**
 * jqSearch.js - A simple jQuery plugin: Instant search on keyup
 *
 * Version 1.0
 *
 * Copyright (C) 2013  Paul Carlton
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

(function($){

	/**
	 * jqSearch - A keyup event that executes ajax calls
	 *
	 * Function that mimics instant search results upon keyup, like google search
	 *
	 * @param {String} page
	 * @param {Function|Object|String} params
	 * @param {Function} func
	 * @returns {boolean}
	 */
	$.fn.jqSearch = function(page, params, func)
	{

		// conditions that allow params to be
		if ($.isFunction(params)) {
			func = params;
			params = {};
		} else if ($.type(params) === 'string') {
			var tmp = params;
			params = {};
			params.elem_pop = tmp;
		}

		// required parameters
		var pg = page || false,
			p = params || {},
			// function for processing the response default to populating default div
			fn = ($.isFunction(func)) ? func : function(response){
				$(DIV_POP).html(response);
			},


			// NOT REQUIRED
			// query is the renaming of the post variable
			query = p.query || false,
			// query object holds extra post parameters
			query_object = p.query_object || {},
			// the configuration elements
			config = p.config || {},
			// listeners if passed
			listeners = p.listeners || [],
			// on empty string - do something
			onempty = p.onempty || false,

			// CONFIGURATION VARS
			MIN_LENGTH = config.MIN_LENGTH || 3,
			TIMEOUT_SET = config.TIMEOUT_SET || 300,
			// the element for populating the div
			DIV_POP = config.DIV_POP || '#results',
			ONBLUR = (typeof config.ONBLUR == 'boolean')  ? config.ONBLUR : true || true,


			// INTERNAL variables
			// the last saved value
			l = null,
			tset = false,
			timeout = false,
			xhrs = [],
			timer,
			self = this
		;

		// some initial checks to make sure we have at least a page to post to and some kind of
		// parameter setting
		if ($.type(page)!=='string' || page === '') {
			throw new Error('No page specified for the query.');
		} else if (!query) {
			if ($(this).attr('name') == '') {
				throw new Error('No name with attribute');
			} else {
				// assign the name of the input object as the query
				query = $(this).attr('name');
			}
		}

		// listeners with onchange requirements
		_initListeners();

		$(this).keyup(function(e){

			var code = (e.keyCode ? e.keyCode : e.which);
			switch (code) {
				// ignore arrows, shift, return etc...
				case 40: case 38: case 9: case 20: case 13: case 27: case 39: case 37: case 16: case 91: case 18: case 17:
					return;
					break;
				default:
					break;
			}

			var v = l = $(this).val();

			// execute code on empty string value
			if (v.length == 0 && onempty) {
				if ($.isFunction(onempty)) {
					onempty();
				} else {
					$(DIV_POP).html('');
				}
				return;
			} else if (v.length < MIN_LENGTH) {
				// if length is below minimum length, do not query
				return;
			}

			// timeout
			if (!tset && !timeout) {
				tset = true;
				timeout = TIMEOUT_SET;
			}

			// The timeout is for keeping ajax calls down to a minimum -
			// where after the set timeout (300 milliseconds default) an ajax call is made
			// Allows for quick typing without calling ajax request on every key press
			if (timeout == 0) {

				for (var j in xhrs) {
					if (xhrs.hasOwnProperty(j)) {
						xhrs[j].abort();
						xhrs.shift();
					}
				}
				var o = {};
				o[query] = v;
				$.extend(o, query_object);
				var listeners = _parseListeners();
				$.extend(o, listeners);
				var xhr = $.post(pg, o, fn);
				xhrs.push(xhr);
				timeout = TIMEOUT_SET;

			} else {
				clearTimeout(timer);
				timeout = TIMEOUT_SET;
				timer = setTimeout(function(){
					timeout = 0;
					$(self).trigger('keyup');
				}, TIMEOUT_SET)
			}

		}).blur(function(){

			var v = $(this).val();
			if (v.length < MIN_LENGTH) {
				return;
			}

			if ($(this).val() != l || ONBLUR) {
				l = $(this).val();
				for (var j in xhrs) {
					if (xhrs.hasOwnProperty(j)) {
						xhrs[j].abort();
						xhrs.shift();
					}
				}
				var o = {};
				o[query] = $(this).val();
				$.extend(o,query_object);
				var listeners = _parseListeners();
				$.extend(o, listeners);
				var xhr = $.post(pg, o, fn);
				xhrs.push(xhr);
			}

		});


		/**
		 * Listeners have an onchange environment where
		 * on change it triggers the query
		 *
		 * @private
		 */
		function _initListeners()
		{
			if ($.type(listeners) !== 'undefined' && $.type(listeners) !== 'array') {
				throw new Error('Listeners must be type array');
			}

			for (var i = 0; i < listeners.length; i++) {
				$(listeners[i]).each(function(){
					$(this).change(function(){
						$(self).trigger('keyup');
					});
				});
			}
		}


		/**
		 * On keyup, include these listeners in the post parameter
		 *
		 * @returns {{}}
		 * @private
		 */
		var _parseListeners = function()
		{
			if ($.type(listeners) !== 'undefined' && $.type(listeners) !== 'array') {
				throw new Error('Listeners must be an array');
			}

			var q_obj = {};

			for (var i = 0; i < listeners.length; i++) {
				$(listeners[i]).each(function(){
					var name = $(this).attr('name');
					q_obj[name] = $(this).val();
				});
			}
			return q_obj;
		};

		return true;
	};
})(jQuery);
