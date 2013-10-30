<?php

$post_to = $_SERVER['REQUEST_URI'];

if (!empty($_POST)) {
	if (is('search-1')) {
		$val = p('search-1');
	} elseif (is('search-2')) {
		$val = p('search-2');
	} elseif (is('search-3')) {
		$val = p('search-3');
	} elseif (is('search-4')) {
		$val = p('search-4');
	} else {
		$val = '';
	}

	$return_val = "The input you gave was '$val'";
	if (is('return_type')) {
		if (p('return_type') == 'json') {
			print json_encode(array('string'=>$return_val));
		} elseif (p('return_type') == 'post-to-array') {
			print '<pre>'.print_r($_POST,true).'</pre>';
		}
	} else {
		print $return_val;
	}
	die;
}


function is($var) {
	return isSet($_POST[$var]);
}
function p($var) {
	return $_POST[$var];
}
?><!DOCTYPE>
<html>
<head>
	<title>Search Ajax</title>
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="../jqSearch.js"></script>
<style type="text/css">
pre {
	border:1px solid gray;
	background:rgb(240,240,240);
	padding:10px;
}
</style>
</head>
<body>

<h1>Search Ajax JQuery Plugin</h1>
<h3>Easiest Way</h3>
<pre>
	// The first parameter is ALWAYS the page to ping as a string
	// simplest way: page to post to, div to populate
	$('#search-1').jqSearch('<?=$post_to?>', 'results');
</pre>
<form name="input-1" id="input-1">
	<fieldset>
		<label for="search-1">Enter A Name (ex: John)</label>
		<input type="text" name="search-1" id="search-1" placeholder="name" />
		<div class="result-div" id="results"></div>
	</fieldset>
	<script type="text/javascript">
		$('#search-1').jqSearch('<?=$post_to?>', 'results');
	</script>
</form>


<h3>Simple Way</h3>
<pre>
	// simple way: page to post and on response function
	$('#search-2').jqSearch('<?=$post_to?>', function(response){
		$('#results2').html(response);
	});
</pre>
<form name="input-2" id="input-2">
	<fieldset>
		<label for="search-1">Enter A Name (ex: John)</label>
		<input type="text" name="search-2" id="search-2" placeholder="name" />
		<div class="result-div" id="results2"></div>
	</fieldset>
	<script type="text/javascript">
		$('#search-2').jqSearch('<?=$post_to?>', function(response){
			$('#results2').html(response);
		});
	</script>
</form>


<h3>Thorough Way:</h3>
<pre>
	// thorough way: add extra post parameters
	var q = {};
	q.query_object = {'return_type':'json'};
	$('#search-3').jqSearch('<?=$post_to?>', q, function(response) {
		$('#results3').html('JSON Response: '+response);
	});
</pre>
<form name="input-3" id="input-3">
	<fieldset>
		<label for="search-1">Enter A Name (ex: John)</label>
		<input type="text" name="search-3" id="search-3" placeholder="name" />
		<div class="result-div" id="results3"></div>
	</fieldset>
	<script type="text/javascript">
		// simplest way: page to post to, div to populate
		var q = {};
		q.query_object = {'return_type':'json'};
		$('#search-3').jqSearch('<?=$post_to?>', q, function(response) {
			$('#results3').html('JSON Response: '+response);
		});
	</script>
</form>

<h3>Full Configuration</h3>
<pre>
	/**
	 * Full query object explained
	 * You can configure the ajax function completely, here are the variables exposed and explained
	 */

	// BASIC PARAMETERS
	//Consider q to be an object
	var q = {};

	// In case you want to change the query value where the original name
	// of the input is changed to meet backend criteria
	// Meaning: $(this).attr('name') == 'search-4' but we want to change the name of the value to 'search-2'
	q.query = 'search-2';

	// The query object is an object of extra variables that are combined into the post object sent to the page
	q.query_object = {
		'return_type':'post-to-array'
	};

	// do something on an empty string
	q.onempty = function() {
		$('#results4').html('Empty string!');
	};

	// CONFIGURATION VARIABLES
	// Consider q.config as an object
	q.config = {};

	// The configuration variables are also available for customization
	// set the default div
	q.config.POP_DIV = '#global-result';
	// set the minimum length of the input string before ajax calls are made - default is 3
	q.config.MIN_LENGTH = 3;
	// set the wait time before the keyup event creates an ajax request
	// the purpose of this is to limit the amount of ajax calls by setting the time wait after a keypress
	q.config.TIMEOUT_SET = 300; // milliseconds
	// set the refresh on blur, allows ajax to be called onblur after a keypress
	q.config.ONBLUR = false;

	// Input Listeners
	// These allow other inputs to trigger ajax events.
	q.listeners = ['#trigger-1', '#trigger-2'];

	$('#search-4').jqSearch('<?=$post_to?>', q, function(response) {
		$('#results4').html(response);
	});
</pre>
<form name="input-4" id="input-4">
	<fieldset>
		<label for="search-4">Enter A Name (ex: John)</label>
		<input type="text" name="search-4" id="search-4" placeholder="name" />
		<div>&nbsp;</div>
		<label for="trigger-1">Listener 1</label>
		<input type="text" name="trigger-1" id="trigger-1" value="1" maxlength="1" size="1" />
		<label for="trigger-2">Listener 2</label>
		<input type="text" name="trigger-2" id="trigger-2" value="2" maxlength="1" size="1" />
		<div class="result-div" id="results4"></div>
	</fieldset>
	<script type="text/javascript">
		// BASIC PARAMETERS
		//Consider q to be an object
		var q = {};

		// In case you want to change the query value where the original name
		// of the input is changed to meet backend criteria
		// Meaning: $(this).attr('name') == 'search-4' but we want to change the name of the value to 'search-2'
		q.query = 'search-2';

		// The query object is an object of extra variables that are combined into the post object sent to the page
		q.query_object = {
			'return_type':'post-to-array'
		};

		// do something on an empty string
		q.onempty = function() {
			$('#results4').html('Empty string!');
		};

		// CONFIGURATION VARIABLES
		// Consider q.config as an object
		q.config = {};

		// The configuration variables are also available for customization
		// set the default div
		q.config.DIV_POP = '#results4';
		// set the minimum length of the input string before ajax calls are made - default is 3
		q.config.MIN_LENGTH = 3;
		// set the wait time before the keyup event creates an ajax request
		// the purpose of this is to limit the amount of ajax calls by setting the time wait after a keypress
		q.config.TIMEOUT_SET = 300; // milliseconds
		// set the refresh on blur, allows ajax to be called onblur after a keypress
		q.config.ONBLUR = false;

		// Input Listeners
		// These allow other inputs to trigger ajax events.
		q.listeners = ['#trigger-1', '#trigger-2'];

		// create a custom loading image
		q.loading_function = function(loading_img, loading_dim, loading_div) {
			alert('loading img');
			$(loading_div).html('<img src="'+loading_img+'" width="'+loading_dim[0]+'" height="'+loading_dim[1]+'" />');
		};

		$('#search-4').jqSearch('<?=$post_to?>', q, function(response) {
			$('#results4').html(response);
		});
	</script>
</form>

</body>
</html>