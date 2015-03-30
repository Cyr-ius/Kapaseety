<html>
<head>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>

</head>
<body>
<table id="example" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </tfoot>
    </table>
<button id="b" name="bb" value="Mon Btn"/>Mon Btn</button>
<script>
obj =[
        {
            "name": "Tiger Nixon",
            "position": "System Architect",
            "salary": "$320,800",
            "start_date": "2011\/04\/25",
            "office": "Edinburgh",
            "extn": "5421"
        },
        {
            "name": "Tiger Nixon",
            "position": "System Architect",
            "salary": "$320,800",
            "start_date": "2011\/04\/25",
            "office": "Edinburgh",
            "extn": "5421"
        }];
$(document).ready(function() {
    $('#example').dataTable( {
        "data": obj,
        "columns": [
            { "data": "name" },
            { "data": "position" },
            { "data": "office" },
            { "data": "extn" },
            { "data": "start_date" },
            { "data": "salary" }
        ]
    } );
} );

$("#b").click(function(){

$('#example').dataTable( {
  "ajax": function (data, callback, settings) {
    callback(
      JSON.parse( localStorage.getItem('dataTablesData') )
    );
  }
} );

});


</script>
</body>
</html>
