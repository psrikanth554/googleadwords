<?php 
include('db.php');
$query = "select * from keywords where adgroups_id = ".$_GET['id'];
$result = $link->query($query);

if ($result->num_rows > 0) {
 ?>

<table border=1 align="center">
	<tr>
		<th>Id</th>
		<th>Keywords</th>
 		<th>Max CPC</th>
		<th>Policy Details</th>
		<th>Final Url</th>
		<th>Clicks</th>
 		<th>Impressions</th>
 		<th>CTR</th>
		<th>Avg CPC</th>
		<th>Cost</th>
  
	</tr>
<?php  while($row = $result->fetch_assoc()) { ?>
	<tr>
		<td><?php  echo $row['id']; ?></td>
		<td><a href="#"><?php  echo $row['keyword']; ?></a></td>
 		<td><?php  echo $row['max_cpc']; ?></td>
		<td><?php  echo $row['policy_details']; ?></td>
		<td><?php  echo $row['final_url']; ?></td>
		<td><?php  echo $row['clicks']; ?></td>
 		<td><?php  echo $row['impressions']; ?></td>
		<td><?php if($row['impressions']!=0){  echo round((($row['clicks']/$row['impressions'])*100),2)."%"; }else{ echo "0%"; }?></td>
  		<td>&#8377;<?php if($row['impressions']!=0){  echo round((($row['cost']/$row['impressions'])),2)."%"; }else{ echo "0%"; }?></td>
		<td>&#8377;<?php  echo $row['cost']; ?></td>
 		 
 	</tr>
	<?php } ?>
</table>
<?php
 //}
} else {
    echo "0 results";
}