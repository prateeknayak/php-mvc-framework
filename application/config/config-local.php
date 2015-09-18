<?PHP

/*
|--------------------------------------------------------------------------
| SITE URL AND PROTOCOL PARAMETERS
|--------------------------------------------------------------------------
|
|	Please follow the naming convention and formatting
|
*/
$uri ="/";
$proto 				= "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://";
$baseURL 		= $proto .$_SERVER['SERVER_NAME'].$uri;

/*
|--------------------------------------------------------------------------
| CDN DOMAIN
|--------------------------------------------------------------------------
|
|	This Should Always point to the static folder
|	Please follow the naming convention and formatting
|
*/

$cdnURL			= $baseURL."static/";

/*
|--------------------------------------------------------------------------
| Framework Variable
|--------------------------------------------------------------------------
|
|	This is where magic happens.
|	This path is guarded by Fluffy if you don't have the Elder's please avoid
| 	taking this path.
|
|
*/

$framework = $basePath."framework/";

/*
|--------------------------------------------------------------------------
| Application Variable
|--------------------------------------------------------------------------
|
|	The path to application variable.
|
|
*/
$application = $basePath."application/";