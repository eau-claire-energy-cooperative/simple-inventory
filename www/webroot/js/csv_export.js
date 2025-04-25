/*
Exports a DataTables table object as a CSV
export will take in to account the full table contents (all rows/columns) and also adhere to
search filtering
*/
function exportDataTableToCSV(table, filename = 'download.csv') {
    // get the header
    var headers = table.columns().header().map(function(th) {
      return $(th).text().trim();
    }).toArray();

    var csvContent = headers.join(',') + "\n";

    table.rows({search: 'applied'}).every(function(rowId, tableLoop, rowLoop) {
      var rowNode = this.data(); // Get the row information
      var rowData = [];

      rowNode.forEach(function(cellData, index) {

        // may be an object with display and sort data
        if(typeof cellData === "object" && 'display' in cellData)
        {
          cellData = cellData.display;
        }

        // trim all html and keep text only
        var cellText = $('<div>').html(cellData).text().trim();
        rowData.push('"' + cellText + '"');

      });

      csvContent = csvContent + rowData.join(',') + "\n";
    });

    // Create a Blob from the CSV string and trigger a download
    let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    let link = document.createElement('a');
    if (link.download !== undefined) { // Feature detection
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }
}

/*
Exports an HTML table as a CSV
export will only export visible rows/columns and also adhere to
search filtering
*/
function exportTableToCSV(tableId, filename = 'download.csv') {
    let table = document.getElementById(tableId);
    let rows = table.rows;
    let csvContent = '';

    for (let rowIndex = 0; rowIndex < rows.length; rowIndex++) {
        let row = rows[rowIndex];
        let rowData = [];

        // Loop through each cell in the row and extract its text
        for (let cellIndex = 0; cellIndex < row.cells.length; cellIndex++) {
            rowData.push('"' + row.cells[cellIndex].innerText + '"');
        }
        csvContent += rowData.join(',') + "\n";
    }

    // Create a Blob from the CSV string and trigger a download
    let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    let link = document.createElement('a');
    if (link.download !== undefined) { // Feature detection
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }
}
