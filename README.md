jqSearch.js v1.1
========

## A simple jQuery plugin - Instant search on keyup ##

--------

jqSearch is a jQuery plugin that mimics ajax functionality found in search boxes like Google.
It is configurable as well as customizable.  It's simple enough to be tinkered with.

### Examples ###

These examples are also found in the test folder.


#### Example 1 ####
Simply post to the page and populate the div #results

	$('#search-1').jqSearch('page-to-post-to.php', '#results');

#### Example 2 ####
Do something with the response

	$('#search-1').jqSearch('page-to-post-to.php', function(response) {
	  $('#result-div').html(response);
	)};

#### Example 3 ####
Add extra parameters into the post data sent

	var q = {};
	q.query_object = {'return_type':'json'};
	$('#search-3').jqSearch('page-to-post-to.php', q, function(response) {
		$('#results3').html('JSON Response: '+response);
	});

#### Example 4 ####
Fully Configured

	var q = {};
	q.query = 'search-2';
	q.query_object = {
		'return_type':'post-to-array'
	};
	q.onempty = function() {
		$('#results4').html('Empty string!');
	};
	q.config = {};
	q.config.POP_DIV = '#global-result';
	q.config.MIN_LENGTH = 3;
	q.config.TIMEOUT_SET = 300; // milliseconds
	q.config.ONBLUR = false;
	q.listeners = ['#trigger-1', '#trigger-2'];
	q.loading_function = function(loading_img, loading_dim, loading_div) {
		alert('loading img');
		$(loading_div).html('<img src="'+loading_img+'" width="'+loading_dim[0]+'" height="'+loading_dim[1]+'" />');
	};
	$('#search-4').jqSearch('page-to-post-to.php', q, function(response) {
		$('#results4').html(response);
	});
