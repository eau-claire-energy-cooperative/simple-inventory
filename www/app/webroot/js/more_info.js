$(document).ready(function(){
	checkRunning();
	setInterval(checkRunning,40 * 1000);
	
	$('a.delete-computer').confirm({
	    content: "Are you sure you want to delete this computer?",
	    buttons: {
	        yes: function(){
	            location.href = this.$target.attr('href');
	        },
	        cancel: function(){
	        	
	        }
	    }
	});
});