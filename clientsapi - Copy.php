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
		->withClientCustomerId($_GET['id'])
        ->build();
    self::runExample(new AdWordsServices(), $session);

    
  }
  public static function runExample(AdWordsServices $adWordsServices, AdWordsSession $session) {
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
		
		 /*  foreach ($page->getLinks() as $link) {
				$managerCustomerId = strval($link->getManagerCustomerId());
				$customerIdsToChildLinks[$managerCustomerId][] = $link;
				$clientCustomerId = strval($link->getClientCustomerId());
				$customerIdsToParentLinks[$clientCustomerId] = $link;
		   }*/
$clients = array();
?>
<table border=1 align="center">
	<tr>
		<th>Id</th>
		<th>client Name</th>
		<th>client Id</th>
		<th>Location</th>
		<th>Service/Products</th>
		<th>Channels</th>
		<th>Spend</th>
		<th>Revenue</th>
		<th>Orders</th>
		<th>Leads</th>
	</tr>
<?php
		   foreach ($page->getEntries() as $account) {
				// $clients[] = array('name'=>$account->getName(),'custId'=> $account->getCustomerId());
				// $clients['custId'] = $account->getCustomerId();
				if($account->getCustomerId()!=$_GET['id']){
				?>
				<tr>
					<td><?php  //echo $row['id']; ?></td>
					<td><?php if($account->getCanManageClients()==1){ ?><a href="clientsapi.php?id=<?php  echo $account->getCustomerId(); ?>"><?php  echo $account->getName(); ?></a> <?php }else{  ?><a href="campaignsapi.php?id=<?php  echo $account->getCustomerId(); ?>"><?php  echo $account->getName(); ?></a><?php } ?></td>
					<td><?php  echo $account->getCustomerId(); ?></td>
					<td>
						 
					</td>
					<td>
						 
					</td>
					<td>
						 
					</td>
				</tr>
				<?php
					}
				}


 
		// do {
		
		//} while ($selector->getPaging()->getStartIndex() < $totalNumEntries);
		
  }
}
GetAccountHierarchy::main();