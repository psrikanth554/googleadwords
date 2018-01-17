<?php 

namespace Google\AdsApi\Examples\AdWords\v201710\BasicOperations;

require __DIR__ . '/vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201710\ReportDownloader;
 use Google\AdsApi\AdWords\Reporting\v201710\DownloadFormat;
 use Google\AdsApi\AdWords\ReportSettingsBuilder;
 use Google\AdsApi\Common\OAuth2TokenBuilder;

 include("fb/config.php");
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\Fields\AdsInsightsFields;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\AdFields;
 
include('db.php');
?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style>
  .pagination li{ display:inline;}
  </style>
   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="assets/script.js"></script>
<?php
$perpage = 3;
if(isset($_GET['page']) & !empty($_GET['page'])){
	$curpage = $_GET['page'];
}else{
	$curpage = 1;
}
$PageSql = "SELECT * FROM clients  where status = 1";
$pageres = mysqli_query($link, $PageSql);
$totalres = mysqli_num_rows($pageres);
$start = ($curpage * $perpage) - $perpage;
$endpage = ceil($totalres/$perpage);
$startpage = 0;
$nextpage = $curpage + 1;
$previouspage = $curpage - 1;

$query = "select * from clients where status = 1 LIMIT $start,$perpage";
$result = $link->query($query);

if ($result->num_rows > 0) {
 ?>

<?php include('filter.php'); ?>
<a href="clients.php?type=loc">Show by Locations</a>
 <pre>
<table border=1 align="center">

	<tr>
		<?php if(isset($_POST['from']) && isset($_POST['to'])){ ?>
				<td colspan="8" align="center"> Results From <?php echo @$_POST['from']; ?> to <?php echo @$_POST['to']; ?></td>
		<?php }else{ ?>
				<td colspan="8" align="center"> Results From Last 7 days</td>
		<?php } ?>
	</tr>
	<tr>
		<th>Id</th>
		<th>client Name</th>
		<th>client Id</th>
		<!--<th>Location</th>
		<th>Service/Products</th>-->
		<th>Channels</th>
		<th>Spend</th>
		<?php if(isset($_GET['type'])){ echo "<th>Locations</th>";}  ?>
		<th>Revenue</th>
		<th>Orders</th>
		<th>Leads</th>
	</tr>
<?php  


 while($row = $result->fetch_assoc()) { 
 
	//$location_query  = "SELECT c.location_name FROM clients a INNER JOIN client_location_mapping b ON a.id=b.clients_id and a.id=".$row['id']." INNER JOIN location c ON b.location_id = c.id";
	//$location_result = $link->query($location_query);

	//$services_query  = "SELECT c.name FROM clients a INNER JOIN client_service_mapping b ON a.id=b.clients_id and a.id=".$row['id']." INNER JOIN services_products c ON b. 	services_products_id = c.id";
	//$services_result = $link->query($services_query);
	$channel_query  = "SELECT c.channel_name,c.id FROM clients a INNER JOIN client_channel b ON a.id=b.clients_id and a.id=".$row['id']." INNER JOIN channels c ON b.channels_id = c.id";
	$channel_result = $link->query($channel_query);
?>
	<tr>
		<td><?php  echo $row['id']; ?></td>
		<td><?php if($row['isManager']==1){ ?><a href="clientsapi.php?id=<?php  echo $row['client_channel_id']; ?>"><?php  echo $row['client_name']; ?></a> <?php }else{  ?><a href="campaignsapi.php?id=<?php  echo $row['client_channel_id']; ?>"><?php  echo $row['client_name']; ?></a><?php } ?></td>
				
		<td><?php  echo $row['client_channel_id']; ?></td>
 
		<td>
			<?php  while($channel_row = $channel_result->fetch_assoc()) { 
			if($channel_row['id']==1){
				if($row['isManager']==1){ ?><a href="clientsapi.php?id=<?php  echo $row['client_channel_id']; ?>"><?php  echo $channel_row['channel_name']; ?></a><?php }else{  ?><a href="campaignsapi.php?id=<?php  echo $row['client_channel_id']; ?>"><?php  echo $channel_row['channel_name']; ?></a><?php } ?><br><?php
						 
			}else{
				?><a href="fb/listCampaings.php?id=<?php  echo $row['faceboook_id']; ?>"><?php  echo $channel_row['channel_name']; ?></a><?php
					


			}

					}
			?>
		</td>
		 <?php 
			if(@$_GET['type']!="loc"){  
				echo "<td>";
				 if($row['isManager']!=1 && $row['client_channel_id']!=""){  
					 echo getCost($row['client_channel_id'],@$_POST['from'],@$_POST['to'])."<br>"; 	
				 }
				 if(@$row['faceboook_id']!="") getFacebookAmount($row['faceboook_id'],@$_POST['from'],@$_POST['to']);

				 echo "</td>";
				 


			}else{
			
				if($row['isManager']!=1){  echo getLocations($row['client_channel_id'],@$_POST['from'],@$_POST['to'],$link); }
				if(@$row['faceboook_id']!="") getFacebookAmount($row['faceboook_id'],@$_POST['from'],@$_POST['to']);	
			}?>
		 
	</tr>
	<?php } ?>
	<tr><td colspan="8" align="center">
	  <ul class="pagination">
      <?php if($curpage != $startpage){ ?>
		<li class="page-item">
		  <a class="page-link" href="?page=<?php echo $startpage ?>" tabindex="-1" aria-label="Previous">
			<span aria-hidden="true">&laquo;</span>
			<span class="sr-only">First&nbsp;</span>
		  </a>
		</li>
    <?php } ?>
    <?php if($curpage >= 2){ ?>
		<li class="page-item">&nbsp;<a class="page-link" href="?page=<?php echo $previouspage ?>"><?php echo "Previous"; ?></a>&nbsp;</li>
    <?php } ?>
	<li class="page-item active">&nbsp;<a class="page-link" href="?page=<?php echo $curpage ?>"><?php echo $curpage ?></a>&nbsp;</li>
	<?php if($curpage != $endpage){ ?>
		<li class="page-item">&nbsp;<a class="page-link" href="?page=<?php echo $nextpage ?>"><?php echo "Next"; ?></a>&nbsp;</li>
	<?php } ?>
	    <?php if($curpage != $endpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=<?php echo $endpage ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Last</span>
      </a>
    </li>
    <?php } ?>
  </ul>
</nav>
	<td></tr>
</table>
<?php
 //}
} else {
    echo "0 results";
}
function getCost($clientId,$from=NULL,$to=NULL){

	$oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();

    // Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
		->withClientCustomerId($clientId)
        ->build();

	if($from=="" || $to==""){ $during = 'LAST_7_DAYS'; } 
	else{ 
	 
		//$datef = str_replace('/', '-', $from);
		$dateFrom = date('Ymd', strtotime($from));
		
		//$datet = str_replace('/', '-', $to);
  		$dateTo = date('Ymd', strtotime($to));
 		$during = $dateFrom.",".$dateTo; 
		} 

   $reportQuery = 'select CampaignName,Cost
					from CRITERIA_PERFORMANCE_REPORT
					where ExternalCustomerId = '.$clientId.' and Cost !=0
					during '.$during ;

		$reportDownloader = new ReportDownloader($session);
		$reportSettingsOverride = (new ReportSettingsBuilder())
									->includeZeroImpressions(true)
									->build();
		$reportDownloadResult = $reportDownloader->downloadReportWithAwql($reportQuery, DownloadFormat::CSV, $reportSettingsOverride);
		$data1 = explode("\n", $reportDownloadResult->getAsString());

		$price=0;
		$i=0;

		foreach ($data1 as $campaign) {
			$data = explode(",", $campaign);
 			if($i==0 || $i==1) {}else{  
				if($data[0]=="Total") { $price =  $data[1]; }
			}
			if($i==(count($data1)-2)){  break; }
			$i++;
		}
		echo "&#8377;".round(($price/1000000),2)."";

}

function getLocations($clientId,$from=NULL,$to=NULL,$link){

	$oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();

    // Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
		->withClientCustomerId($clientId)
        ->build();

	if($from=="" || $to==""){ $during = 'LAST_7_DAYS'; } 
	else{ 
 			//$datef = str_replace('/', '-', $from);
			$dateFrom = date('Ymd', strtotime($from));
			//$datet = str_replace('/', '-', $to);
			$dateTo = date('Ymd', strtotime($to));
			$during = $dateFrom.",".$dateTo; 
		} 

		$reportQuery = 'select CountryCriteriaId,RegionCriteriaId,Cost from GEO_PERFORMANCE_REPORT where ExternalCustomerId = '.$clientId.' and Cost !=0 during '.$during;
  

		$reportDownloader = new ReportDownloader($session);
		$reportSettingsOverride = (new ReportSettingsBuilder())
 									->build();
		$reportDownloadResult = $reportDownloader->downloadReportWithAwql($reportQuery, DownloadFormat::CSV, $reportSettingsOverride);
		$data1 = explode("\n", $reportDownloadResult->getAsString());
		//  echo "<pre>";
	 //	 print_r($data1);
	//	 exit;

		$price=0;
		$i=0;
		$loc="";
		foreach ($data1 as $campaign) {
			$data = explode(",", $campaign);
 			if($i==0 || $i==1) {  }else{  
				 if($data[0]=="Total") { $price =  $data[2];  }else{
					 //print_r($data);
					// if($data[1]!="--"){
						$query = "select * from google_locations where id = ".$data[0]." GROUP BY country_code";
						$result = $link->query($query);
						if(@$result->num_rows > 0){
							$row = $result->fetch_assoc();
							if( strpos( $loc, $row['Criteria_ID'] ) === false ) {
									$loc .= $row['Criteria_ID']."<br>";
								}
							
						}
					// }
					}

				 
			}
			if($i==(count($data1)-2)){  break; }
			$i++;
		}
		//echo count($data1);
		$cost = explode(",", $data1[count($data1)-2]);
		$price =  $cost [2];

	 	echo "<td>&#8377;".round(($price/1000000),2)."</td>";
		echo "<td>".$loc."</td>";

}

function getFacebookAmount($account_id,$from=NULL,$to=NULL){
 
 	 $account = new AdAccount('act_'.$account_id);
	 if($from=="" || $to==""){ $since = (new \DateTime("-1 week"))->format('Y-m-d'); $until = (new \DateTime())->format('Y-m-d'); } 
	else{ 
 			 //$since = $from;
			 $since = date('Y-m-d', strtotime($from));
			 $until = date('Y-m-d', strtotime($to));
		} 
	 	$params = array(
			'time_range' => array(
			'since' => $since,
			'until' => $until,
			),
	);
		//print_r($params);

	$fields = array(
			AdsInsightsFields::SPEND,
	);

$insights = $account->getInsights($fields, $params)->getLastResponse();
 //print_r($insights->getContent()['data']);
  echo "&#8377;".$insights->getContent()['data'][0]['spend'];
	 
 	
}
?>
   
