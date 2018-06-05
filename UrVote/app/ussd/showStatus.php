<html>
<head>
<link rel="stylesheet" href="styles/morris.css">
<link rel="stylesheet" href="styles/bootstrap.min.css">
<script src="script/jquery-2.2.2.min.js"></script>
<script src="script/raphael-min.js"></script>
<script src="script/morris.min.js"></script>
<meta charset=utf-8 />
<?php
$con=mysql_connect("localhost","root","")or die(mysql_error());
mysql_select_db("yrvotedb")or die(mysql_error());

$tbl1="mbrndtbl";
$tbl2="mostbl";
$tbl3="politbl"; ?>

<title>Show Status</title>
</head>
<body>
</script>
</body>
</html>

<?php
print("<h2 class='text-center'>\nStatus of Mobile Brand\n</h2>");
print("<br><br>");
$result=mysql_query("SELECT * FROM $tbl1") or die(mysql_error());

print("<table class='table table-bordered'> \n");
	while($pr_row=mysql_fetch_row($result))
	{
		print "<tr>";
		foreach($pr_row as $data)
			print "\t <td>$data</td>";
		print "</tr> \n";
	}
	print"</table> \n";
	
$result=mysql_query("SELECT vote,count(*) FROM $tbl1 group by vote") or die(mysql_error());

$data = array();
    
    for ($x = 0; $x < mysql_num_rows($result); $x++) {
        $data[] = mysql_fetch_assoc($result);
    }  

?>
<div id="mbrnd"></div>
<script type="text/javascript">
Morris.Bar({
  barGap:2,
  barSizeRatio:0.2,
  element: 'mbrnd',
  data: <?php echo json_encode($data);?>,
  xkey: 'vote',
  ykeys: ['count(*)'],
  labels: ['Total'],
  resize: false
});
</script>

<?php
print("<br><br>");
print("<h2 class='text-center'>Status of Mobile Operating Systems\n</h2>");
print("<br><br>");

$result=mysql_query("SELECT * FROM $tbl2") or die(mysql_error());

print("<table class='table table-bordered'> \n");
	while($pr_row=mysql_fetch_row($result))
	{
		print "<tr>";
		foreach($pr_row as $data)
			print "\t <td>$data</td>";
		print "</tr> \n";
	}
	print"</table> \n";


$result=mysql_query("SELECT vote,count(*) FROM $tbl2 group by vote") or die(mysql_error());

$data = array();
    
    for ($x = 0; $x < mysql_num_rows($result); $x++) {
        $data[] = mysql_fetch_assoc($result);
    }  

?>
<div id="mOS"></div>
<script type="text/javascript">
Morris.Bar({
  barGap:2,
  barSizeRatio:0.2,
  element: 'mOS',
  data: <?php echo json_encode($data);?>,
  xkey: 'vote',
  ykeys: ['count(*)'],
  labels: ['Total'],
  resize: false
});
</script>
<?php

print("<br><br>");
print("<h2 class='text-center'>Status of CEPA/ETCA agreement\n</h2>");
print("<br><br>");	
$result=mysql_query("SELECT * FROM $tbl3") or die(mysql_error());

print("<table class='table table-bordered'> \n");
	while($pr_row=mysql_fetch_row($result))
	{
		print "<tr>";
		foreach($pr_row as $data)
			print "\t <td>$data</td>";
		print "</tr> \n";
	}
	print"</table> \n";

$result=mysql_query("SELECT vote,count(*) FROM $tbl3 group by vote") or die(mysql_error());

$data = array();
    
    for ($x = 0; $x < mysql_num_rows($result); $x++) {
        $data[] = mysql_fetch_assoc($result);
    }  

?>
<div id="poli"></div>
<script type="text/javascript">
Morris.Bar({
  barGap:1,
  barSizeRatio:0.1,
  element: 'poli',
  data: <?php echo json_encode($data);?>,
  xkey: 'vote',
  ykeys: ['count(*)'],
  labels: ['Total'],
  resize: false
});
</script>
<?php
mysql_close($con);
?>