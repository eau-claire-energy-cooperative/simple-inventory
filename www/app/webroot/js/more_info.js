$(document).ready(function(){
	checkRunning();
	setInterval(checkRunning,40 * 1000);
	
	$(".popup").fancybox({
	maxWidth	: 600,
	maxHeight	: 400,
	fitToView	: false,
	width		: '70%',
	height		: '70%',
	autoSize	: false,
	closeClick	: false,
	openEffect	: 'none',
	closeEffect	: 'none'
	});
});