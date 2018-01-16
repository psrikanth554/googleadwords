<?php 
include('db.php');
$query = "select * from client_account where client_id = ".$_GET['id'];
$result = $link->query($query);

if ($result->num_rows > 0) {
 ?>

<table border=1 align="center">
	<tr>
		<th>Id</th>
		<th>Account Name</th>
		<th>Account Id</th>
		<th>Location</th>
		<th>Service/Products</th>
 		<th>Clicks</th>
		<th>Impressions</th>
		<th>Views</th>
		<th>CTR</th>
		<th>CPC</th>
		<th>CPM</th>
		<th>CPV</th>

	</tr>
<?php  while($row = $result->fetch_assoc()) { 

$location_query  = "SELECT c.location_name FROM client_account a INNER JOIN account_location_mapping b ON a.id=b.account_id and a.id=".$row['id']." INNER JOIN location c ON b.location_id = c.id";
$location_result = $link->query($location_query);

$services_query  = "SELECT c.name FROM client_account a INNER JOIN account_service_mapping b ON a.id=b.account_id and a.id=".$row['id']." INNER JOIN services_products c ON b. 	services_products_id = c.id";
$services_result = $link->query($services_query);

 

?>
	<tr>
		<td><?php  echo $row['id']; ?></td>
		<td><a href="campaigns.php?id=<?php  echo $row['id']; ?>"><?php  echo $row['account_name']; ?></a></td>
		<td><?php  echo $row['account_channel_id']; ?></td>
		<td>
			<?php  while($loc_row = $location_result->fetch_assoc()) {  
						echo $loc_row['location_name']."<br>";
					}
			?>
		</td>
		<td>
			<?php  while($ser_row = $services_result->fetch_assoc()) {  
						echo $ser_row['name']."<br>";
					}
			?>
		</td>
 	</tr>
	<?php } ?>
</table>
<?php
 //}
} else {
    echo "0 results";
}