$(document).ready(function(){
	$('a.delete-license').confirm({
	    content: "Are you sure you want to delete this license?",
	    buttons: {
	        yes: function(){
	            location.href = this.$target.attr('href');
	        },
	        cancel: function(){
	        	
	        }
	    }
	});
});