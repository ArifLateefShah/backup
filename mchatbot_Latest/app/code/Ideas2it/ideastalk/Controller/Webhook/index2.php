<?php
    include('data.php');
?>
<html>
<head>
<!-- <link rel="stylesheet" type="text/css" href="./assets/style.css"> -->
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.jqueryui.min.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.jqueryui.min.js"></script>
</head>
<style>
body{
    margin:0px;
}
.header{
    height:100px;
    max-height:100px;
    width:auto;
    background-color:#ddd;
    text-align:center;
    padding-top:10px;
    margin:0xp;
}
#example tr td {
   padding:3px 5px 5px 10px !important;
}
#example tr:nth-child(odd) {
   background-color:#ccc;
}
</style>
<body>
<div class="header">
   <h1>Ideas-Talk Chat Log</h1>

</div>
<table id="example"  class="display" cellspacing="0" width="100%" cellpadding="0">
        <thead>
            <tr>
                <th>User Id</th>
                <th>Chat</th>
                <th>Date</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>User Id</th>
                <th>Chat</th>
                <th class="date_col">Date</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
                if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $userArray = explode('#-#',$row['userId']);
                    $dateArray = explode('#-#',$row['date']);
                    ?>

            <tr>
                <td style="vertical-align:top;font-weight:bold;"><?php print_r($userArray[0]); ?></td>
                <td><?php print_r($row['chat']); ?></td>
                <td  style="vertical-align:top"><?php print_r($dateArray[0]); ?></td>
            </tr>
            <?php    }
             }
            ?>
        </tbody>
    </table>
</body>
</html>
<script>
$(document).ready(function() {
    $('#example').DataTable({
        "ordering": false
    });
} );
</script>
<?php
  if($conn){
    mysql_close($conn);
  }
 ?>
<!-- $(document).ready(function() {
    var table = $('#example').DataTable();
    table
    .order( [ 2, 'desc' ] )
    .draw();
} ); -->
