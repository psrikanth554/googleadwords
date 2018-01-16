<?php 

namespace Google\AdsApi\Examples\AdWords\v201710\AccountManagement;

require __DIR__ . '/vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201710\cm\OrderBy;
use Google\AdsApi\AdWords\v201710\cm\Paging;
use Google\AdsApi\AdWords\v201710\cm\Selector;
use Google\AdsApi\AdWords\v201710\cm\SortOrder;
use Google\AdsApi\AdWords\v201710\mcm\ManagedCustomer;
use Google\AdsApi\AdWords\v201710\mcm\ManagedCustomerService;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\v201710\cm\CampaignService;
use Google\AdsApi\AdWords\Reporting\v201710\ReportDownloader;
use Google\AdsApi\AdWords\Reporting\v201710\DownloadFormat;
use Google\AdsApi\AdWords\ReportSettingsBuilder;
use Google\AdsApi\AdWords\v201710\cm\Predicate;
use Google\AdsApi\AdWords\v201710\cm\PredicateOperator;
//include('db.php');

class GetAccountHierarchy {

	
	const PAGE_LIMIT = 500;

	public static function main() {
    // Generate a refreshable OAuth2 credential for authentication.
    $oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();

    // Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
		->withClientCustomerId(2790163755)
        ->build();
    self::runExample(new AdWordsServices(), $session);

    
  }
  public static function runExample(AdWordsServices $adWordsServices, AdWordsSession $session) {
	  $link22 = mysqli_connect('localhost', 'root', '');
if (!$link22) {
    die('Not connected : ' . mysqli_error());
}

// make foo the current db
$db_selected = mysqli_select_db($link22,'adwords');
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysqli_error());
}

	 $managedCustomerService = $adWordsServices->get($session, ManagedCustomerService::class);
	 echo "<pre>";
	// print_r($managedCustomerService);
		$selector = new Selector();
		$selector->setFields(['CustomerId', 'Name','DateTimeZone','AccountLabels','CanManageClients']);
		$selector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
		$selector->setPaging(new Paging(0, self::PAGE_LIMIT));
		$customerIdsToAccounts = [];
		$customerIdsToChildLinks = [];
		$customerIdsToParentLinks = [];
		$totalNumEntries = 0;
		$page = $managedCustomerService->get($selector);
		
	  foreach ($page->getLinks() as $link) {
				$managerCustomerId = strval($link->getManagerCustomerId());
				
				$customerIdsToChildLinks[$managerCustomerId][] = $link;
 				$clientCustomerId = strval($link->getClientCustomerId());
				if($managerCustomerId==2790163755){
					$customerIdsToParentLinks[$clientCustomerId] = $link;
				}
		   } 


		   foreach ($page->getEntries() as $account) {
 				 //$customerIdsToAccounts[strval($account->getCustomerId())] = $account;
				// print_r(array_keys($customerIdsToParentLinks));
 				// echo $account->getCustomerId();
				// echo "<br>";
				 if (@in_array($account->getCustomerId(),array_keys($customerIdsToParentLinks))) {
					// echo $account->getCustomerId()."------".$account->getName();
					// echo "<br>";
					$query = "insert into clients (client_channel_id,client_name) values (".$account->getCustomerId().",'".$account->getName()."')";
					$result = $link22->query($query);
					$last_id = $link22->insert_id;
					$query1 = "insert into client_channel (clients_id,channels_id) values (".$last_id.",1)";
					//$result = $link22->query($query1);
					if ($link22->query($query1) === TRUE) {
						$last_id = $link22->insert_id;
						echo "New record created successfully. Last inserted ID is: " . $last_id;
					} else {
						echo "Error: " . $query1 . "<br>" . $link22->error;
					}


				 }

			}
			//print_r($customerIdsToParentLinks);
			/*foreach ($customerIdsToAccounts as $account) {
				if (@array_key_exists($account->getCustomerId(),$customerIdsToParentLinks)) {
					echo $account->getCustomerId()."------".$account->getName();
					echo "<br>";
				}
			}*/
/*foreach ($page->getEntries() as $account) {
			print_r();

			//echo $query = "insert into clients (client_channel_id,client_name) values (".$account->getCustomerId().",'".$account->getName()."')";
			//$result = $link22->query($query);
}exit;*/
 
 		
  }

}
 GetAccountHierarchy::main();