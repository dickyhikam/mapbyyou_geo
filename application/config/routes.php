<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login';
$route['translate_uri_dashes'] = FALSE;

$route['Deactivated'] = 'Pending/deactivated';
$route['Register2'] = 'Test';
$route['Login/version'] = 'Login/index_version';
$route['Login/user'] = 'Login/index_user';

$route['Claim/work/(:any)'] = 'Claim/index_detil';
$route['MyWork/update_work/(:any)'] = 'MyWork/index_detil';

//Data Master
$route['Menu'] = 'DM_Menu';
$route['Category'] = 'DM_Category';
$route['Team'] = 'DM_Team';
$route['Level'] = 'DM_Level';
$route['Country'] = 'DM_Country';
$route['Project'] = 'DM_Project';
$route['Version'] = 'DM_Version';

//Setting
$route['AccessMenu'] = 'ST_AccessMenu';

//User Management
$route['User'] = 'UM_PersonalData';
$route['User/detail/(:any)'] = 'UM_PersonalData/index_detil';

//QC
$route['QCDownload'] = 'QC_Download';
$route['QualityControl'] = 'QC_Submit';
$route['QualityControl/detail/(:any)'] = 'QC_Submit/index_detil';
$route['ResultQC'] = 'QC_Result';
$route['ResultQC/detail/(:any)'] = 'QC_Result/index_detil';
$route['ResultQC/edit/(:any)'] = 'QC_Result/index_edit';

//Error
$route['404_override'] = 'PageError/error_page';
$route['AccessDenied'] = 'PageError/error_access';