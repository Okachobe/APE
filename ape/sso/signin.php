<?php
/**
 * Sign in with EWU SSO
 * @author Team APE 2013-2014, Tu Nguyen 2017
 * @version 1.0
 */

/*
require_once "CAS/CAS.php";
require_once "../util/check_id.php";

$cas_server_ca_cert_path = 'comodo_combo.pem';
$cas_real_hosts = array('it-adfs01.eastern.ewu.edu',
                        'it-casauth01.eastern.ewu.edu');




// This is Where it forces us to use the SSO to login 


phpCAS::client(SAML_VERSION_1_1,'login.ewu.edu',443,'/cas',false);
phpCAS::setCasServerCACert($cas_server_ca_cert_path);
phpCAS::handleLogoutRequests(true, $cas_real_hosts);
phpCAS::forceAuthentication();

session_start();

$_SESSION['loggedIn'] = true;



//get all attributes from returned object.
//Array includes: "UserType", "Email", "FirstName", "Ewuid", "LastName"
$userAttr = phpCAS::getAttributes();
$_SESSION['ewuid'] = $userAttr["Ewuid"];




//$_SESSION["phpCAS"]["attributes"]["UserType"] = "Admin";
//echo $_SESSION['ewuid'];
//echo $_SESSION["phpCAS"]["attributes"]["UserType"];

if(strcmp($_SESSION["phpCAS"]["attributes"]["UserType"], "Student") == 0)
{
    if(checkStudentExists($_SESSION['ewuid']))
    {
        $_SESSION["phpCAS"]["attributes"]["UserType"] = array();
        array_push($_SESSION["phpCAS"]["attributes"]["UserType"], "Student");
        
    }
    else 
    {
        $_POST["requester_id"] = "999999";
        $_POST["requester_type"] = "System";
        require_once "create_account.php";
        
        createStudentAccount($_SESSION['ewuid'], "Ready");
    }
}
else 
{
    $_SESSION["phpCAS"]["attributes"]["UserType"] = checkFacultyTypes();
}

header('Location: ../view/home/');

function checkFacultyTypes()
{
    $userId = $_SESSION['ewuid'];
    $userTypes = Array();

    $sqlCheckFaculty = "SELECT type 
                        FROM faculty
                        WHERE faculty_id LIKE :faculty_id";
    
    $sqlResult = sqlExecute($sqlCheckFaculty, array('faculty_id'=>$userId), True);
    if(count($sqlResult) == 0)
    {
        if(!isset($GLOBALS["settings"]))
            initializeSettings();
        http_response_code(401);
        die("You don't have an account in the APE system. Please contact " . $GLOBALS["settings"]["contactName"] . " to setup an account");
    }
    else 
    {
        foreach($sqlResult as $theType)
        {
            array_push($userTypes, $theType["type"]);
        }
    }

    return $userTypes;
}
*/

// require_once "../util/check_id.php";
include_once __DIR__ . '/../../vendor/autoload.php';
// use Google_Client;

/*
if (class_exists(Google_Client))
{
    // echo "<h1> got it </h1>";
} else {
    // echo "<h1> Didn't </h1>";
}
 */

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/../../client_secret.json');
$client->addScope('openid profile email');
// $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->setRedirectUri('http://localhost:8080/ape/view/home');
// offline access will give you both an access and refresh token so that
// your app can refresh the access token without user interaction.
$client->setAccessType('offline');
// Using "consent" ensures that your application always receives a refresh token.
// If you are not using offline access, you can omit this.
// $client->setApprovalPrompt("consent");
$client->setPrompt('consent');
$client->setIncludeGrantedScopes(true);   // incremental auth

$auth_url = $client->createAuthUrl();

$_SESSION['loggedIn'] = true;
// echo "<h3>" . $auth_url . "</h1>";
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
?>
