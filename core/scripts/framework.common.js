
var asyncCall = function(delegate) { setTimeout(delegate, 1); };

var setDefault = function(variable, value, andNotValue)
{
	if(typeof andNotValue == 'undefined')
		andNotValue = null;

	if(typeof variable == 'undefined')
		variable = value;
	else if(variable === andNotValue)
		variable = value;

	return variable;
};

var redirect = function(url, speed, fadeOutSelector, forceAnchor)
{
	speed = setDefault(speed, 'slow');
	forceAnchor = setDefault(forceAnchor, setDefault(config.anchors_ajax_navigation, false));

	var callback = function()
	{
		window.location = forceAnchor ?
			(config.baseurl+'#'+url.replace(config.baseurl, '')).replace(/##/g, '#') : url;
	};

	if(!speed || setDefault(config.anchors_ajax_navigation, false))
		callback();
	else
	{
		fadeOutSelector = setDefault(fadeOutSelector, 'div, span, input, select, label, img, a');

		var count = $(fadeOutSelector).size();
		var c = 0;

		$(fadeOutSelector).each(function() {
			$(this).fadeOut(speed, function() {
				c += 1;
			});
		});

		var gone = false;

		setInterval(function() {
			if(c == count && !gone)
			{
				gone = true;
				asyncCall(callback);
			}
		}, 50);
	}
};

var centerscreen = function(obj)
{
	$(obj).css({
		position	: 'absolute',
		top			: ($(window).height() - $(obj).height()) / 2 + 'px',
		left		: ($(window).width() - $(obj).width()) / 2 + 'px'
	});
};

var evalCenterScreenClasses = function()
{
	// need to make it 2 times
	$('.centerscreen').each(function() { centerscreen($(this)); centerscreen($(this)); });
};

$(document).ready(evalCenterScreenClasses);
$(window).resize(evalCenterScreenClasses);

var parseSize = function(size) { return Math.abs(size.substring(0, size.length - 2)); };

var isScrollEnd = function(tolerance, debug)
{
	tolerance = setDefault(tolerance, 0);
	debug = setDefault(debug, false);

	var cur = $(window).scrollTop() + tolerance;
	var max = $(document).height() - $(window).height();

	if(debug)
		alert(cur+'/'+max);

	return cur >= max;
};

var evalAjaxResponse = function(response, options)
{
	var defaultValue = {
		callback: function() { },
		onResponse: function(response) { return true; },
		onMessage: function(message) { alert(message); },
		onData: function(data) { },
		onError: function(err) { alert('Impossible de traiter la réponse.\n\n'+err); },
		onLocation: function(url) { redirect(url); }
	};

	options = setDefault(options, defaultValue);
	options.callback = setDefault(options.callback, defaultValue.callback);
	options.onResponse = setDefault(options.onResponse, defaultValue.onResponse);
	options.onMessage = setDefault(options.onMessage, defaultValue.onMessage);
	options.onData = setDefault(options.onData, defaultValue.onData);
	options.onError = setDefault(options.onError, defaultValue.onError);
	options.onLocation = setDefault(options.onLocation, defaultValue.onLocation);

	try
	{
		response = JSON.parse(response);

		var r = options.onResponse(response.success);

		if(r !== false && r !== 0)
		{
			if(response.message)
				options.onMessage(response.message);

			if(response.location)
				options.onLocation(response.location);
			else if(response.data)
				options.onData(response.data);
		}
	}
	catch(err)
	{
		options.onError(err);
	}

	options.callback();
};

var ajaxRequest = function(url, responseOptions)
{
	$.ajax({
		url: url,
		success: function(response) {
			evalAjaxResponse(response, responseOptions);
		},
		error: function(obj, textStatus, errorThrown) {
			/*------------------------\
			| textStatus values :
			|-------------------------|
			| - timeout
			| - error
			| - abort
			| - parsererror
			\------------------------*/

			var message = false;
			switch(textStatus)
			{
				case 'timeout': message = 'La requête a dépassée le délai imparti'; break;
				//case 'error': message = 'Une erreur est survenue'; break;
				//case 'abort': message = 'La requête a été interrompue'; break;
				case 'parseerror': message = 'Erreur de traitement de la réponse'; break;
			}

			if(typeof responseOptions != 'undefined')
				responseOptions.onError(message);

			if(typeof responseOptions.callback != 'undefined')
				responseOptions.callback();
		}	
	});
};

var scrollToTarget = function(selector, speed, decal, callback)
{
	speed = setDefault(speed, 'slow');
	decal = setDefault(decal, 0);
	callback = setDefault(callback, function() { });

	var callbackCalled = false;

	$('html,body').animate({
		scrollTop: $(selector).offset().top + decal
	}, {
		duration: speed,
		complete: function() {
			if(callbackCalled) // need to check cause callback is called for html and body complete event..
				return;

			callbackCalled = true;
			callback();
		}
	});
};

var scrollTopPage = function(speed, callback) { scrollToTarget('html,body', speed, 0, callback); };
var scrollBottomPage = function(speed, callback)
{
	scrollToTarget('html,body', speed, $(document).height() * 2, callback);
};

var isScrolledIntoView = function(selector, partialview)
{
	partialview = setDefault(partialview, false);

    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).innerHeight();
	var result = false;

	$(selector).each(function() {
		if(!result && $(this).css('display') != 'none')
		{
			var objTop = $(this).offset().top;
			var objBottom = objTop + $(this).outerHeight();

			if(!partialview)
			{
				if((objBottom >= docViewTop) && (objTop <= docViewBottom))
					result = true;
			}
			else
			{
				if(((objBottom >= docViewTop) && (objBottom <= docViewBottom)) ||
					((objTop >= docViewTop) && (objTop <= docViewBottom)))
					result = true;
			}
		}
	});

	return result;
};

if(setDefault(config.anchors_ajax_navigation, false))
{
	var currentAnchor = document.location.hash;
	var loadingAnchor = false;

	var checkAnchor = function()
	{
		if(currentAnchor != document.location.hash)
		{
			if(loadingAnchor)
				return;

			loadingAnchor = true;

			//alert('currentAnchor=['+window.currentAnchor+'],document.location.hash=['+document.location.hash+']');

			currentAnchor = document.location.hash;

			var url = config.baseurl;

			if(currentAnchor)
				url += currentAnchor.substring(1);

			url += (currentAnchor.indexOf('?') == -1 ? '?' : '&')+'ajaxed=1'

			$.ajax({
				url: url,
				success: function(data) {
					var headContent = '';
					var bodyContent = data;

					if(bodyContent.indexOf('</head>') != -1)
						headContent = bodyContent.substring(0, bodyContent.indexOf('</head>'));

					if(headContent.indexOf('<head>') != -1)
						headContent = headContent.substring(headContent.indexOf('<head>') + 6);

					if(bodyContent.indexOf('<body>') != -1)
						bodyContent = bodyContent.substring(bodyContent.indexOf('<body>') + 6);

					if(bodyContent.indexOf('</body>') != -1)
						bodyContent = bodyContent.substring(0, bodyContent.indexOf('</body>'));

					$('body').html(headContent+"\n"+bodyContent);

					loadingAnchor = false;
				}
			});
		}
	};

	checkAnchor();
	setInterval(checkAnchor, 300);
}

var preloadImage = function(path)
{
	var src = (config.baseurl+path.replace(config.baseurl, '')).replace(/\/\//g, '/');

	window.prefetchImages = setDefault(window.prefetchImages, Array());

	var img = new Image();
	img.src = src;

	window.prefetchImages.push(img);

	$(document).ready(function() {
		$('body').prepend(
			$('<img>').css('display', 'none')
				.attr('src', src)
				.attr('alt', '')
		);
	});
};
