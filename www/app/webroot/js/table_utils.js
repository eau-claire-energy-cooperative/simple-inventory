$(document).ready(function() 
{ 
    $("#tableSort").tablesorter({
        headers: {
			3: {
				sorter: "ram"
			}
        }
        }); 
}); 

$.tablesorter.addParser({
	id: "ram",
	is: function(s){
		return false;
	},
	format: function(s){

		var splitArray = s.split(' ');
		
		return splitArray[0];
	},
	type: 'numeric'
});