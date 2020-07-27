<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

    <div class="tbl_container1">
        <table id="listing" class="table table-bordered table table-hover" cellspacing="0" width="100%">
            <colgroup><col><col><col></colgroup>
            <thead>
                <tr>
                    <tr>
                        <th>Name</th>
                        <th >Salary</th>
                        <th>Age</th>
                    </tr>
                </tr>
            </thead>
            <tbody id="emp_body">
            </tbody>
        </table>
    </div>

 
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/3.2.5/css/tableexport.min.css">
  <!-- 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
  <script src="https://cdn.rawgit.com/eligrey/FileSaver.js/e9d941381475b5df8b7d7691013401e171014e89/FileSaver.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/3.3.5/js/tableexport.min.js"></script> 


<script type="text/javascript">
    
        
$(document).ready(function(){
    function ExportTable(){
                $("#listing").tableExport({
                headings: true,                    // (Boolean), display table headings (th/td elements) in the <thead>
                footers: true,                     // (Boolean), display table footers (th/td elements) in the <tfoot>
                formats: ["xls", "csv", "txt"],    // (String[]), filetypes for the export
                fileName: "id",                    // (id, String), filename for the downloaded file
                bootstrap: true,                   // (Boolean), style buttons using bootstrap
                position: "well" ,                // (top, bottom), position of the caption element relative to table
                ignoreRows: null,                  // (Number, Number[]), row indices to exclude from the exported file
                ignoreCols: null,                 // (Number, Number[]), column indices to exclude from the exported file
                ignoreCSS: ".tableexport-ignore"   // (selector, selector[]), selector(s) to exclude from the exported file
            });
            }

            $.ajax({
                url: "http://dummy.restapiexample.com/api/v1/employees",
                async: true,
                dataType: 'json',
                success: function (response) {
                    console.log(response.data);
                    var tr;
                    for (var i = 0; i < response.data.length; i++) {
                        tr = $('<tr/>');
                        tr.append("<td>" + response.data[i].employee_name + "</td>");
                        tr.append("<td>" + response.data[i].employee_salary + "</td>");
                        tr.append("<td>" + response.data[i].employee_age + "</td>");
                        $('#emp_body').append(tr);
                    }
                    ExportTable();
                }
    });
});
 
         

</script>
</body>
</html>