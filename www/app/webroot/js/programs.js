$(document).ready(function() {
  $('#dataTable').DataTable({
	  paging: true, 
	  pageLength: 100,
	  dom: '<"top"ifp>rt',
	  columnDefs: [
		  {"searchable": false, "targets": [-1]}
	  ]
  });
});
