<?php 
include('db.php');
$query = "select * from campaigns where campaign_source_id = ".$_GET['id'];
$result = $link->query($query);

if ($result->num_rows > 0) {
 ?>

<table border=1 align="center">
	<tr>
		<th>Id</th>
		<th>Campaign Name</th>
 		<th>Campaign Budget</th>
		<th>Campaign Type</th>
 		<th>Bid Stratagy Type</th>
		<th>Impressions</th>
		<th>Clicks</th>
		<th>CTR</th>
		<th>Avg CPC</th>
		<th>Cost</th>
 
	</tr>
<?php  while($row = $result->fetch_assoc()) { ?>
	<tr>
		<td><?php  echo $row['id']; ?></td>
		<td><a href="adgroups.php?id=<?php  echo $row['id']; ?>"><?php  echo $row['campaign_title']; ?></a></td>
 		<td>&#8377;<?php  echo $row['campaign_budget']; ?></td>
		<td><?php  echo $row['campaign_type']; ?></td>
		<td><?php  echo $row['bid_strategy_type']; ?></td>
		<td><?php  echo $row['impressions']; ?></td>
		<td><?php  echo $row['interaction']; ?></td>
		<td><?php if($row['impressions']!=0){  echo round((($row['interaction']/$row['impressions'])*100),2)."%"; }else{ echo "0%"; }?></td>
 		<td>&#8377;<?php  echo $row['avg_cpc']; ?></td>
		<td><?php  echo $row['cost']; ?></td>
		 
 	</tr>
	<?php } ?>
</table>
<?php
 //}
} else {
    echo "0 results";
}