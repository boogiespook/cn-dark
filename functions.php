<?php
function putToggles($domain) {
$qq = "SELECT capability.id as id, capability.description as capability, flag.description as flag from capability,flag where domain_id = $domain and capability.flag_id = flag.id ORDER BY capability;";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());
while ($row = pg_fetch_assoc($result)) {
if ($row['flag'] == "green") {
	$checked = "checked";
} else {
	$checked = "";
}

print '          	<li class="toggle-label"> 
<label class="pf-c-switch" for="' . $row['id'] . '">
  <input class="pf-c-switch__input" type="checkbox" name="capability-' . $row['id'] . '" id="' . $row['id'] . '" aria-labelledby="' . $row['id'] . '-on" ' . $checked . ' />
  <span class="pf-c-switch__toggle">
    <span class="pf-c-switch__toggle-icon">
      <i class="fas fa-check" aria-hidden="true"></i>
    </span>
  </span>
  <p class="toggle-capability">' . $row['capability'] . '</p>
  </li>
</label>';
}
}


function putAperture($domainId) {
# Get total number of capabilities
$capabilityCount = "select count(capability) as total, domain.description from capability,domain where domain.id = capability.domain_id and domain.id ='" . $domainId . "' group by domain.description;";
$capabilityCountTotal = pg_query($capabilityCount) or die('Error message: ' . pg_last_error());
$capabilityRow = pg_fetch_assoc($capabilityCountTotal);
$totalCapabilities = $capabilityRow['total'];
if ($totalCapabilities != "") {
$capabilityName = $capabilityRow['description'];
$greenCount = "select count(flag_id) as totalgreen from capability where domain_id = '" . $domainId . "' and flag_id = '2'";
$greenTotal = pg_query($greenCount) or die('Error message: ' . pg_last_error());
$greens = pg_fetch_assoc($greenTotal);
$totalGreens = $greens['totalgreen'];

$percentComplete = ($totalGreens / $totalCapabilities) * 100;

# If greens < total, add red aperture
if ($totalGreens < $totalCapabilities) {
print "<img src=images/aperture-red-closed.png title='" . round($percentComplete) . "% Compliant'>";
} else {
print "<img src=images/aperture-green.png title='" . round($percentComplete) . "% Compliant'>";
}
} else {
print "<img src=images/aperture-red-closed.png>";
}
}

function putIcon($colour, $capability) {
if ($colour == 'green') {
print '
<span class="pf-c-icon pf-m-inline">
  <span class="pf-c-icon__content  pf-m-success">
    <i class="fas fa-check-circle" aria-hidden="true"></i>
  </span>
</span>&nbsp;<span>' . $capability . "</span><br><br>";
} else {
print '
<span class="pf-c-icon pf-m-inline">
  <span class="pf-c-icon__content pf-m-danger">
    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
  </span>
</span>&nbsp;<span>' . $capability . "</span><br><br>";
}
}

function putProfileOptions() {
$qq = "select id, name from profiles order by id desc";
$profilesCall = pg_query($qq) or die('Error message: ' . pg_last_error());
print '<div class="pf-c-toggle-group ">';
while ($row = pg_fetch_assoc($profilesCall)) {
print '
<div class="pf-c-toggle-group__item">
    <button onclick="location.href=\'index.php?profile=' . $row['id'] . '&name=' . $row['name'] . '\'" class="pf-c-toggle-group__button" type="button">
      <span class="pf-c-toggle-group__text">' . $row['name'] . '</span>
    </button>
  </div>

';
	}
print "</div>";
}

function getDomainsByProfile($profile) {
# First get the domain IDs based  on the profile
$domains = "select array_to_json(domains) as domain from profiles where id = '" . $profile . "'";
$selectedDomains = pg_query($domains) or die('Error message: ' . pg_last_error());
$selectedDomainsArray = pg_fetch_array($selectedDomains);
$selectedDomains = json_decode($selectedDomainsArray[0]);
#while ($row = pg_fetch_assoc($selectedDomains)) {
#  $domainArray = json_decode($row['domain']);
#	## Loop through each domain getting capabilities
#	foreach($domainArray as $domain) {
#		print $domain;	
#	}
##  print_r($domainArray);
#}
return $selectedDomains;
}

function putToggleItems() {
$selectDomains = "select * from domain ORDER by description;";
$domainResults = pg_query($selectDomains) or die('Error message: ' . pg_last_error());
$i = 1; 
while ($row = pg_fetch_assoc($domainResults)) {
print '
<div class="pf-c-card pf-m-selectable-raised pf-m-rounded" id="card-' . $i . '">
          <div class="pf-c-card__title">
            <p id="card-' . $i . '-check-label">' . $row["description"] . '</p>
            <div class="pf-c-content">
              <small>Key Capabilities</small>
            </div>
          </div>
          <div class="pf-c-card__body">
          <div class="pf-c-content">
          ';
$qq = "select capability.id as id, capability.description as capability, flag.description as flag from capability,flag where domain_id = '" . $row['id'] . "' and capability.flag_id = flag.id ORDER by capability;";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());
while ($row = pg_fetch_assoc($result)) {
if ($row['flag'] == "green") {
	$checked = "checked";
} else {
	$checked = "";
}

## Check if there is an integration for the capability
$integrationQuery = "select count(*) as total from integrations where capability_id = '" . $row['id'] . "'";

$integrationResult = pg_query($integrationQuery) or die('Error message: ' . pg_last_error());
$intCount = pg_fetch_assoc($integrationResult);

if ($intCount['total'] > 0) {
$toggleClass = "toggle-capability-integration";
} else {
$toggleClass = "toggle-capability";
}

print '          	<div class="toggle-label"> 
<label class="pf-c-switch" for="' . $row['id'] . '">
  <input class="pf-c-switch__input" type="checkbox" name="capability-' . $row['id'] . '" id="' . $row['id'] . '" aria-labelledby="' . $row['id'] . '-on" ' . $checked . ' />
  <span class="pf-c-switch__toggle">
    <span class="pf-c-switch__toggle-icon">
      <i class="fas fa-check" aria-hidden="true"></i>
    </span>
  </span>
  <p class="' . $toggleClass . '">' . $row['capability'] . '</p>
  </div>
</label>';
}          
print '          	         	
         </div>
          </div>
        </div>';	
$i++;        
}

}

?>