<!DOCTYPE html>
  <html lang="en-us" class="pf-theme-dark">
    <head>
      <meta charSet="utf-8"/>
      <meta http-equiv="x-ua-compatible" content="ie=edge"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
      <title data-react-helmet="true">CrowsNest Toggle</title>
      <link rel="stylesheet" href="css/brands.css" />
      <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="css/tabs.css" />
      <link rel="stylesheet" href="css/patternfly.css" />
      <link rel="stylesheet" href="css/patternfly-addons.css" />
    </head>

    <body>
<?php

class DotEnv
{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    protected $path;


    public function __construct(string $path)
    {
        if(!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        $this->path = $path;
    }

    public function load() :void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
               $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

(new DotEnv(__DIR__ . '/.env'))->load();

$pg_host = getenv('PG_HOST');
$pg_db = getenv('PG_DATABASE');
$pg_user = getenv('PG_USER');
$pg_passwd = getenv('PG_PASSWORD');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");
include 'functions.php';

?>    
    
    
    
<div class="pf-c-page">
  <header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <div class="pf-c-page__header-brand-toggle">
                  </div>
                  <a class="pf-c-page__header-brand-link">
                  <img class="pf-c-brand" src="images/crowsnest-banner.png" alt="CrowsNest logo" />
                  </a>
                  
                </div>
               
              </header>

<main class="pf-c-page__main" tabindex="-1">  
    <section class="pf-c-page__main-section pf-m-full-height">
<div class="tabset">

  <!-- Tab 1 -->
  <!-- Tab 4 -->
  <input type="radio" name="tabset" id="tab1" aria-controls="dashboard" checked>
  <label for="tab1" >Dashboard</label>

  <input type="radio" name="tabset" id="tab2" aria-controls="toggle">
  <label for="tab2" >CrowsNest Toggle</label>
  <!-- Tab 2 -->
  <input type="radio" name="tabset" id="tab3" aria-controls="integrations">
  <label for="tab3" >Integrations</label>
  <!-- Tab 3 -->
  <input type="radio" name="tabset" id="tab4" aria-controls="methods">
  <label for="tab4" >Integration Methods</label>

  <input type="radio" name="tabset" id="tab5" aria-controls="domains">
  <label for="tab5" >Domains</label>

  <input type="radio" name="tabset" id="tab6" aria-controls="capabilities">
  <label for="tab6" >Capabilities</label>


<!-- Start of Toggle -->  
  <div class="tab-panels">

<!--  Start of Dashboard -->  
    <section id="dashboard" class="tab-panel">

    <p id="dashboard" class="pf-c-title pf-m-3xl">CrowsNest Dashboard </p>

    <section class="pf-c-page__main-section pf-m-fill">
      <div class="pf-l-gallery pf-m-gutter">
<?php
## Get domains & capabilities
$getDomains = "select domain.description, domain.id from domain ORDER BY domain.description;";
$domainResult = pg_query($getDomains) or die('Error message: ' . pg_last_error());
# putAperture($row['id'])
$i = 1;

while ($row = pg_fetch_assoc($domainResult)) {
print '  
<div class="pf-c-card pf-m-selectable-raised pf-m-rounded" id="card-' . $i . '">
<div class="pf-c-card__header">';
putAperture($row['id']);
print '
</div>
<div class="pf-c-card__title">
            <p id="card-' . $i . '-check-label">'. $row['description'] . '</p>
            <div class="pf-c-content">
              <small>Key Capabilities</small>
            </div>
          </div>
          <div class="pf-c-card__body">
          <div class="pf-c-content">';
	$getCapabilities = "select capability.id as id, capability.description as capability, flag.description as flag from capability,flag where domain_id = '" . $row['id'] . "' and capability.flag_id = flag.id ORDER BY capability;";
	$capabilityResult = pg_query($getCapabilities) or die('Error message: ' . pg_last_error());
	while ($capRow = pg_fetch_assoc($capabilityResult)) {
       print putIcon($capRow['flag'], $capRow['capability']);
     }
       $i++;
print "</div></div></div>";
}

?>
</section>
<button  onClick="window.location.reload();" class="pf-c-button pf-m-primary" type="button">Refresh</button>
 </section>
  <!--  End of Dashboard -->  

    <!-- Start of Toggle -->
    
    <section id="toggle" class="tab-panel">

<form id="toggle" class="pf-c-form" action="updateToggle.php" >
    <p class="pf-c-title pf-m-3xl">CrowsNest Toggle</p>
    <p>Items in <span class="blue">Blue</span> indicate that an integration is in place for that capability</p>
      <div class="pf-l-gallery pf-m-gutter">

<!-- CHANGE TO GET DYNAMIC NAMES -->
 <?php putToggleItems(); ?>
        
  <div class="pf-c-form__group pf-m-action">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Submit Updates</button>
    </div>
  </div>        
</form>  

<!-- End of Toggle -->     
  </section>
    <section id="integrations" class="tab-panel">
<!-- Start of Integrations -->
    <p id="integrations" class="pf-c-title pf-m-2xl">Current Integrations</p>
<table class="pf-c-table pf-m-grid-lg" role="grid" aria-label="This is a sortable table example" id="table-sortable">
  <thead>
    <tr role="row">
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Capability</span>
          </div>
        </button>
      </th>
      <th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Integration Name</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>
<th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Success Criteria</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>
<th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Last Update</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>      

<th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Delete</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>

 <th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text"></span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>        
    </tr>
  </thead>
  <tbody role="rowgroup">
<?php
$qq = "SELECT integrations.integration_id, capability.description as capability , integrations.integration_name, integrations.url as url, integrations.last_update as updated, success_criteria from capability,integrations WHERE integrations.capability_id = capability.id";
#print $qq;
$result = pg_query($qq) or die('Error message: ' . pg_last_error());

## Add to table
##       <td role="cell" data-label="updated"><button class="pf-c-button pf-m-primary pf-m-small" type="button">Run Integration</button></td>


while ($row = pg_fetch_assoc($result)) {
print '
    <tr role="row">
      <td role="cell" data-label="Capability">' . $row['capability'] . '</td>
      <td role="cell" data-label="Integration">' . $row['integration_name'] . '</td>
      <td role="cell" data-label="Success Criteria">' . $row['success_criteria'] . '</td>
      <td role="cell" data-label="updated">' . $row['updated'] . '</td>
      <td role="cell" data-label="deleteIntegration"> <a aria-label="Delete" href="delete.php?id=' . $row['integration_id'] . '&table=integrations&idColumn=integration_id" class="confirmation"> <i class="fa fa-trash"></i></a> </td>
    </tr>
';
}
?>
  </tbody>
</table>
<br>
<!--  Start of Add Integrations -->
    <p id="integrations" class="pf-c-title pf-m-2xl">Add Integration</p>

<form class="pf-c-form" action="addIntegration.php">
 <div class="pf-l-grid pf-m-all-6-col-on-md pf-m-gutter">

  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Integration Name</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" required type="text" id="integration-name" name="integration-name" aria-describedby="horizontal-form-name-helper2" />
    </div>
  </div>
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="endpoint-url">
        <span class="pf-c-form__label-text">URL endpoint</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" type="text" id="endpoint-url" name="endpoint-url" required/>
    </div>
  </div>
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="capability-id">
        <span class="pf-c-form__label-text">Capability</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <select class="pf-c-form-control" id="capability-id" name="capability-id">
      <?php
      $qq = "select domain.description as domain, capability.description as capability, capability.id as capabilityId from domain,capability where domain.id = capability.domain_id;";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());
while ($row = pg_fetch_assoc($result)) {
$str = $row['domain'] . " - " . $row['capability'];
print '
<option value="' . $row['capabilityid'] . '">' . $str . '</option>
';		
}
      ?>
     </select>
    </div>
  </div>
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="integration_method_id">
        <span class="pf-c-form__label-text">Integration Method</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <select class="pf-c-form-control" id="integration_method_id" name="integration_method_id">
      <?php
      $qq = "select integration_method_name, id from integration_methods;";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());
while ($row = pg_fetch_assoc($result)) {
print '
<option value="' . $row['id'] . '">' . $row['integration_method_name'] . '</option>
';		
}
      ?>

     </select>
    </div>
  </div>

  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="username">
        <span class="pf-c-form__label-text">Username</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" type="text" id="username" name="username" />
    </div>
  </div>

 <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="password">
        <span class="pf-c-form__label-text">Password</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" type="text" id="password" name="password" />
    </div>
  </div> 
 
<div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="token">
        <span class="pf-c-form__label-text">Token</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
<textarea class="pf-c-form-control" name="token" id="token"></textarea>    </div>
  </div>  

 <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="success-criteria">
        <span class="pf-c-form__label-text">Success Criteria</span>
      </label>
    </div>
    <p
          class="pf-c-form__helper-text"
          id="form-demo-grid-name-helper"
          aria-live="polite"
        >Success criteria depends on the specific integration. For example it could be a number (such as a %) or boolean (true/false, yes/no)</p>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" type="text" id="success-criteria" name="success-criteria" required/>
    </div>
  </div> 

   <div class="pf-c-form__group pf-m-action">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Add Integration</button>
    </div>
  </div>  
</div>
</form>
    </section>
<!--  End of Add Integrations -->  

<!--  Start of Add Integration Methods -->  
    <section id="methods" class="tab-panel">
<p id="integrations" class="pf-c-title pf-m-2xl">Current Integration Methods</p>

<table class="pf-c-table pf-m-grid-lg" role="grid" aria-label="This is a sortable table example" id="table-sortable">
  <thead>
    <tr role="row">
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Integration Method</span>
          </div>
        </button>
      </th>     
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Delete Integration Method</span>
          </div>
        </button>
      </th>     
    </tr>
      </thead>
  <tbody role="rowgroup">
<?php
$qq = "select integration_method_name, id from integration_methods";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());

while ($row = pg_fetch_assoc($result)) {
print '
    <tr role="row">
      <td role="cell" data-label="method">' . $row['integration_method_name'] . '</td>
      <td role="cell" data-label="deleteIntegrationMethod"> <a aria-label="Delete" href="delete.php?id=' . $row['id'] . '&table=integration_methods&idColumn=id" class="confirmation"> <i class="fa fa-trash"></i></a> </td>
    </tr>
';
}
?>
  </tbody>
</table>
<br>
    <p id="integrations" class="pf-c-title pf-m-2xl">Add Integration Method</p>
<form  class="pf-c-form" action="addIntegrationMethod.php">
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Integration Method Name</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" required type="text" id="integration_method" name="integration_method" aria-describedby="horizontal-form-name-helper2" />
    </div>
  </div>
  
     <div class="pf-c-form__group pf-m-action">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Add Integration Method</button>
    </div>
  </div>  
  </form>
  <!--  End of Add Integrations Methods -->  
 </section>

<!--  Start of Domains -->  
    <section id="domains" class="tab-panel">
<div class="pf-l-grid pf-m-gutter">
  <div class="pf-l-grid__item pf-m-6-col">
<p class="pf-c-title pf-m-2xl">Domains</p>
<p class="pf-c-title pf-m-md"><span class="red">WARNING</span> - Deleting a domain will also delete all child capabilities</p>
<table class="pf-c-table pf-m-compact pf-m-grid-md" role="grid" id="table-sortable">
  <thead>
    <tr role="row">
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Domain</span>
          </div>
        </button>
      </th>     
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Delete Domain</span>
          </div>
        </button>
      </th>     
    </tr>
      </thead>
  <tbody role="rowgroup">
<?php
$qq = "select description,id from domain order by description";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());

while ($row = pg_fetch_assoc($result)) {
print '
    <tr role="row">
      <td role="cell" data-label="method">' . $row['description'] . '</td>
      <td role="cell" data-label="deleteDomain"> <a aria-label="Delete" href="delete.php?id=' . $row['id'] . '&table=domain&idColumn=id" class="confirmation"> <i class="fa fa-trash"></i></a> </td>
    </tr>
';
}
?>
  </tbody>
</table>
<br>
<p id="integrations" class="pf-c-title pf-m-2l">Add Domain</p>
<form  class="pf-c-form" action="addDomain.php">
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Domain Name</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" required type="text" id="domain" name="domain" aria-describedby="horizontal-form-name-helper2" />
    </div>
  </div>
     <div class="pf-c-form__group">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Add Domain</button>
    </div>
  </div>  
  </form>
  </div>
  </div>
</section>
<!--  End of Domains -->  

<!--  Start of Capabilities -->  
<section id="capabilities" class="tab-panel">
<div class="pf-l-grid pf-m-gutter">
  <div class="pf-l-grid__item pf-m-6-col">
<p class="pf-c-title pf-m-2xl">Capabilities</p>
<p class="pf-c-title pf-m-md">Use the tree structure below to view and delete capabilities</p>
<p><i>Note: You can't delete capabilities which have active integrations</i></p>
<br>

<?php
#$qq = "select capability.description as capability,capability.id as capabilityid,capability.domain_id,domain.description as domain from capability, domain WHERE domain.id = capability.domain_id order by domain";
$qq = "select description from domain order by description";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());

while ($row = pg_fetch_assoc($result)) {
print '
<details class="details">
      <summary class="summary">' . $row['description'] . '</summary>
      <ul>';
$qq2 = "select capability.description as capability,capability.id as capabilityid,capability.domain_id,domain.description as domain from capability, domain WHERE domain.id = capability.domain_id and domain.description =  '" . $row['description'] . "' order by domain";      

$result2 = pg_query($qq2) or die('Error message: ' . pg_last_error());

while ($row2 = pg_fetch_assoc($result2)) {

## Check if there is an integration for the capability
$integrationQuery = "select count(*) as total from integrations where capability_id = '" . $row2['capabilityid'] . "'";

$integrationResult = pg_query($integrationQuery) or die('Error message: ' . pg_last_error());
$intCount = pg_fetch_assoc($integrationResult);

if ($intCount['total'] > 0) {
print '<li><span role="cell" data-label="deleteCapability"><i class="fa fa-times"></i></span>&nbsp;&nbsp' . $row2['capability'] . '</li>';
#$toggleClass = "toggle-capability-integration";
} else {
print '<li><span role="cell" data-label="deleteCapability"> <a aria-label="Delete" href="delete.php?id=' . $row2['capabilityid'] . '&table=capability&idColumn=id" class="confirmation"> <i class="fa fa-trash"></i></a></span>&nbsp;&nbsp' . $row2['capability'] . '</li>';
#$toggleClass = "toggle-capability";
}	
	
   }
   print "</ul></details>";
}
?>


</div>

<div class="pf-l-grid__item pf-m-6-col">
<p id="capabilities" class="pf-c-title pf-m-2l">Add Capability</p>
<form  class="pf-c-form" action="addCapability.php">
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Capability Name</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" required type="text" id="capability" name="capability" aria-describedby="horizontal-form-name-helper2" />
    </div>
  </div>
  <div class="pf-c-form__group-control">
  <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Select Domain</span>
      </label>
      <select class="pf-c-form-control" id="domainId" name="domainId">
      <?php
      $qq = "select description,id from domain order by description;";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());
while ($row = pg_fetch_assoc($result)) {
$str = $row['description'];
print '
<option value="' . $row['id'] . '">' . $str . '</option>
';		
}
      ?>
     </select>
    </div>
     <div class="pf-c-form__group">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Add Capability</button>
    </div>
  </div>  
  
  </form>

  </div>
</div>
</div>

</section>
  <!--  End of Capabilities -->  

   
</div>
    </section>

  </main>
</div>   

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete this entry ?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>    
<script type="text/javascript" >
$("form").submit(function () {

    var this_master = $(this);

    this_master.find('input[type="checkbox"]').each( function () {
        var checkbox_this = $(this);


        if( checkbox_this.is(":checked") == true ) {
            checkbox_this.attr('value','1');
        } else {
            checkbox_this.prop('checked',true);
            //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA    
            checkbox_this.attr('value','0');
        }
    })
})
</script>
   
  </body>
</html>