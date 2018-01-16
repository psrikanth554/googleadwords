<?php 
include('db.php');
$query = "select * from adgroups where campaign_id = ".$_GET['id'];
$result = $link->query($query);

if ($result->num_rows > 0) {
 ?>

<table border=1 align="center">
	<tr>
		<th>Id</th>
		<th>AdGroup Name</th>
 		<th>Default Max CPC</th>
		<th>Clicks</th>
 		<th>Impressions</th>
 		<th>CTR</th>
		<th>Avg CPC</th>
		<th>Cost</th>
		<th>Group Type</th>
 
	</tr>
<?php  while($row = $result->fetch_assoc()) { ?>
	<tr>
		<td><?php  echo $row['id']; ?></td>
		<td><a href="keywords.php?id=<?php  echo $row['id']; ?>"><?php  echo $row['adgroup_name']; ?></a></td>
 		<td><?php  echo $row['default_max_cpc']; ?></td>
		<td><?php  echo $row['clicks']; ?></td>
 		<td><?php  echo $row['impressions']; ?></td>
		<td><?php if($row['impressions']!=0){  echo round((($row['clicks']/$row['impressions'])*100),2)."%"; }else{ echo "0%"; }?></td>
  		<td>&#8377;<?php  echo $row['avg_cpc']; ?></td>
		<td>&#8377;<?php  echo $row['cost']; ?></td>
		<td><?php  echo $row['ad_group_type']; ?></td>
		 
 	</tr>
	<?php } ?>
</table>
<?php
 //}
} else {
    echo "0 results";
}