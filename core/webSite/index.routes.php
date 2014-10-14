<?php
/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */



$app->match('/requestaccess', "site\controllers\IndexController::requestAccess") 
		->before($appUserRequired)
		->before($canChangeSite); 
$app->match('/requestaccess/', "site\controllers\IndexController::requestAccess") 
		->before($appUserRequired)
		->before($canChangeSite); 

$app->match('/places', "site\controllers\IndexController::places") ; 
$app->match('/places/', "site\controllers\IndexController::places") ; 

$app->match('/currentuser', "site\controllers\IndexController::currentUser") ; 
$app->match('/currentuser/', "site\controllers\IndexController::currentUser") ; 

$app->match('/mytimezone', "site\controllers\IndexController::myTimeZone") ; 
$app->match('/mytimezone/', "site\controllers\IndexController::myTimeZone") ; 

$app->match('/history', "site\controllers\HistoryController::index") ; 
$app->match('/history/', "site\controllers\HistoryController::index") ; 

$app->match('/event', "site\controllers\EventListController::index") ; 
$app->match('/event/', "site\controllers\EventListController::index") ; 
$app->match('/event/calendar', "site\controllers\EventListController::calendarNow") ; 
$app->match('/event/calendar/', "site\controllers\EventListController::calendarNow") ; 
$app->match('/event/calendar/{year}/{month}', "site\controllers\EventListController::calendar")
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/event/calendar/{year}/{month}/', "site\controllers\EventListController::calendar")
		->assert('year', '\d+')
		->assert('month', '\d+') ; 

$app->match('/event/creatingThisNewEvent.json',"site\controllers\EventNewController::creatingThisNewEvent")
		->before($permissionCalendarChangeRequired);

$app->match('/event/new', "site\controllers\EventNewController::newEvent")
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 
$app->match('/event/new/go', "site\controllers\EventNewController::newEventGo")
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 

$app->match('/event/{slug}', "site\controllers\EventController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/event/{slug}/', "site\controllers\EventController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/event/{slug}/myAttendance.json', "site\controllers\EventController::myAttendanceJson")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($appUserRequired);  
$app->match('/event/{slug}/userAttendance.html', "site\controllers\EventController::userAttendanceHtml")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/event/{slug}/history', "site\controllers\EventController::history")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/event/{slug}/edit', "site\controllers\EventController::editSplash")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 
$app->match('/event/{slug}/edit/details', "site\controllers\EventController::editDetails")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 
$app->match('/event/{slug}/edit/venue', "site\controllers\EventController::editVenue")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/event/{slug}/edit/venue.json', "site\controllers\EventController::editVenueJson")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/event/{slug}/edit/venue/new', "site\controllers\EventController::editVenueNew")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/event/{slug}/edit/area', "site\controllers\EventController::editArea")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/event/{slug}/edit/area.json', "site\controllers\EventController::editAreaJson")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/event/{slug}/edit/future', "site\controllers\EventController::editFuture")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 
$app->match('/event/{slug}/delete', "site\controllers\EventController::delete")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 
$app->match('/event/{slug}/undelete', "site\controllers\EventController::undelete")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 
$app->match('/event/{slug}/cancel', "site\controllers\EventController::cancel")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/event/{slug}/uncancel', "site\controllers\EventController::uncancel")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/event/{slug}/rollback/{timestamp}', "site\controllers\EventController::rollback")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('rollback', '\d+')
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 
$app->match('/event/{slug}/recur', "site\controllers\EventController::recur")
		->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/recur/', "site\controllers\EventController::recur")
		->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/recur/weekly', "site\controllers\EventController::recurWeekly")
		->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/recur/weekly/', "site\controllers\EventController::recurWeekly")
		->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/recur/monthly', "site\controllers\EventController::recurMonthly")
		->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/recur/monthly/', "site\controllers\EventController::recurMonthly")
		->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/recur/monthlyLast', "site\controllers\EventController::recurMonthlyLast")
		->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/recur/monthlyLast/', "site\controllers\EventController::recurMonthlyLast")
		->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/moveToArea', "site\controllers\EventController::moveToArea")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)		
		->before($canChangeSite); 
$app->match('/event/{slug}/edit/tags', "site\controllers\EventController::editTags")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featureTagRequired)
		->before($canChangeSite); 
$app->match('/event/{slug}/edit/groups', "site\controllers\EventController::editGroups")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featureGroupRequired)
		->before($canChangeSite);
$app->match('/event/{slug}/media', "site\controllers\EventController::media")
	->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($appFileStoreRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/media/add/existing', "site\controllers\EventController::mediaAddExisting")
	->assert('slug', FRIENDLY_SLUG_REGEX)
	->before($permissionCalendarChangeRequired)
	->before($appFileStoreRequired)
	->before($canChangeSite);
$app->match('/event/{slug}/media/{mediaslug}/remove', "site\controllers\EventController::mediaRemove")
	->assert('slug', FRIENDLY_SLUG_REGEX)
	->assert('mediaslug', '\d+')
	->before($permissionCalendarChangeRequired)
	->before($appFileStoreRequired)
	->before($canChangeSite);


$app->match('/group', "site\controllers\GroupListController::index"); 
$app->match('/group/', "site\controllers\GroupListController::index"); 

$app->match('/group/new/', "site\controllers\GroupNewController::newGroup")
		->before($permissionCalendarChangeRequired)
		->before($featureGroupRequired)
		->before($canChangeSite); 

$app->match('/group/{slug}', "site\controllers\GroupController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/group/{slug}/', "site\controllers\GroupController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/group/{slug}/history', "site\controllers\GroupController::history")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/group/{slug}/media', "site\controllers\GroupController::media")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($appFileStoreRequired)
		->before($canChangeSite); 
$app->match('/group/{slug}/media/add/existing', "site\controllers\GroupController::mediaAddExisting")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($appFileStoreRequired)
		->before($canChangeSite);
$app->match('/group/{slug}/media/{mediaslug}/remove', "site\controllers\GroupController::mediaRemove")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('mediaslug', '\d+')
		->before($permissionCalendarChangeRequired)
		->before($appFileStoreRequired)
		->before($canChangeSite); 
$app->match('/group/{slug}/edit', "site\controllers\GroupController::edit")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featureGroupRequired)
		->before($canChangeSite); 
$app->match('/group/{slug}/newevent', "site\controllers\GroupController::newEvent")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featureGroupRequired)
		->before($canChangeSite); 
$app->match('/group/{slug}/newevent/go', "site\controllers\GroupController::newEventGo")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featureGroupRequired)
		->before($canChangeSite); 
$app->match('/group/{slug}/watch', "site\controllers\GroupController::watch")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($appUserRequired)
		->before($canChangeSite);
$app->match('/group/{slug}/watch/', "site\controllers\GroupController::watch")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($appUserRequired)
		->before($canChangeSite);
$app->match('/group/{slug}/calendar', "site\controllers\GroupController::calendarNow")
		->assert('slug', FRIENDLY_SLUG_REGEX) ; 
$app->match('/group/{slug}/calendar/', "site\controllers\GroupController::calendarNow")
		->assert('slug', FRIENDLY_SLUG_REGEX) ; 
$app->match('/group/{slug}/calendar/{year}/{month}', "site\controllers\GroupController::calendar")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/group/{slug}/calendar/{year}/{month}/', "site\controllers\GroupController::calendar")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/group/{slug}/stopWatchingFromEmail/{userid}/{code}', "site\controllers\GroupController::stopWatchingFromEmail")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('userid', '\d+')
		->before($canChangeSite); 
$app->match('/group/{slug}/newimporturl', "site\controllers\GroupController::newImportURL")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('userid', '\d+')
		->before($featureImporterRequired)
		->before($canChangeSite); 
$app->match('/group/{slug}/importers', "site\controllers\GroupController::importers")
		->assert('slug', FRIENDLY_SLUG_REGEX); 


$app->match('/venue', "site\controllers\VenueListController::index"); 
$app->match('/venue/', "site\controllers\VenueListController::index"); 

$app->match('/venue/new', "site\controllers\VenueNewController::newVenue")
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)
		->before($canChangeSite); 
$app->match('/venue/new/', "site\controllers\VenueNewController::newVenue")
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)
		->before($canChangeSite); 
$app->match('/venue/new/json', "site\controllers\VenueNewController::newVenueJSON")
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)		
		->before($canChangeSite); 
$app->match('/venue/new/json/', "site\controllers\VenueNewController::newVenueJSON")
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)		
		->before($canChangeSite); 



$app->match('/venue/virtual', "site\controllers\VenueVirtualController::show"); 
$app->match('/venue/virtual/', "site\controllers\VenueVirtualController::show");
$app->match('/venue/virtual/calendar', "site\controllers\VenueVirtualController::calendarNow") ; 
$app->match('/venue/virtual/calendar/', "site\controllers\VenueVirtualController::calendarNow") ; 
$app->match('/venue/virtual/calendar/{year}/{month}', "site\controllers\VenueVirtualController::calendar")
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/venue/virtual/calendar/{year}/{month}/', "site\controllers\VenueVirtualController::calendar")
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/venue/virtual/history', "site\controllers\VenueVirtualController::history"); 


$app->match('/venue/{slug}', "site\controllers\VenueController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/venue/{slug}/', "site\controllers\VenueController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/venue/{slug}/edit', "site\controllers\VenueController::edit")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)		
		->before($canChangeSite); 
$app->match('/venue/{slug}/delete', "site\controllers\VenueController::delete")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)		
		->before($canChangeSite); 
$app->match('/venue/{slug}/moveToArea', "site\controllers\VenueController::moveToArea")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)		
		->before($canChangeSite); 
$app->match('/venue/{slug}/calendar', "site\controllers\VenueController::calendarNow") ; 
$app->match('/venue/{slug}/calendar/', "site\controllers\VenueController::calendarNow") ; 
$app->match('/venue/{slug}/calendar/{year}/{month}', "site\controllers\VenueController::calendar")
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/venue/{slug}/calendar/{year}/{month}/', "site\controllers\VenueController::calendar")
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/venue/{slug}/history', "site\controllers\VenueController::history")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/venue/{slug}/media', "site\controllers\VenueController::media")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($appFileStoreRequired)
		->before($canChangeSite);
$app->match('/venue/{slug}/media/add/existing', "site\controllers\VenueController::mediaAddExisting")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($appFileStoreRequired)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/venue/{slug}/media/{mediaslug}/remove', "site\controllers\VenueController::mediaRemove")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('mediaslug', '\d+')
		->before($appFileStoreRequired)
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 


$app->match('/country', "site\controllers\CountryListController::index"); 
$app->match('/country/', "site\controllers\CountryListController::index"); 

$app->match('/country/{slug}', "site\controllers\CountryController::show"); 
$app->match('/country/{slug}/', "site\controllers\CountryController::show"); 
$app->match('/country/{slug}/calendar', "site\controllers\CountryController::calendarNow") ; 
$app->match('/country/{slug}/calendar/', "site\controllers\CountryController::calendarNow") ; 
$app->match('/country/{slug}/calendar/{year}/{month}', "site\controllers\CountryController::calendar")
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/country/{slug}/calendar/{year}/{month}/', "site\controllers\CountryController::calendar")
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/country/{slug}/new', "site\controllers\CountryController::newSplash")
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)
		->before($canChangeSite); 
$app->match('/country/{slug}/newArea', "site\controllers\CountryController::newArea")
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)
		->before($canChangeSite); 
$app->match('/country/{slug}/info.json', "site\controllers\CountryController::infoJson"); 

$app->match('/area/{slug}', "site\controllers\AreaController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/area/{slug}/', "site\controllers\AreaController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/area/{slug}/new', "site\controllers\AreaController::newSplash")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)
		->before($canChangeSite); 
$app->match('/area/{slug}/newArea', "site\controllers\AreaController::newArea")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)
		->before($canChangeSite); 
$app->match('/area/{slug}/edit', "site\controllers\AreaController::edit")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featurePhysicalEventsRequired)
		->before($canChangeSite); 
$app->match('/area/{slug}/calendar', "site\controllers\AreaController::calendarNow")
		->assert('slug', FRIENDLY_SLUG_REGEX) ; 
$app->match('/area/{slug}/calendar/', "site\controllers\AreaController::calendarNow") 
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/area/{slug}/calendar/{year}/{month}', "site\controllers\AreaController::calendar")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/area/{slug}/calendar/{year}/{month}/', "site\controllers\AreaController::calendar")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/area/{slug}/info.json', "site\controllers\AreaController::infoJson")
		->assert('slug', FRIENDLY_SLUG_REGEX); 

$app->match('/watch', "site\controllers\IndexController::watch")
		->before($appUserRequired)
		->before($canChangeSite);
$app->match('/watch/', "site\controllers\IndexController::watch")
		->before($appUserRequired)
		->before($canChangeSite);


$app->match('/tag', "site\controllers\TagListController::index"); 
$app->match('/tag/', "site\controllers\TagListController::index"); 

$app->match('/tag/{slug}', "site\controllers\TagController::show"); 
$app->match('/tag/{slug}/', "site\controllers\TagController::show"); 

$app->match('/admin', "site\controllers\AdminController::index")
		->before($permissionCalendarAdministratorRequired);
$app->match('/admin/', "site\controllers\AdminController::index")
		->before($permissionCalendarAdministratorRequired);
$app->match('/admin/profile', "site\controllers\AdminController::profile")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/features', "site\controllers\AdminController::features")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/settings', "site\controllers\AdminController::settings")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/visibility', "site\controllers\AdminController::visibility")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/countries', "site\controllers\AdminController::countries")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/media', "site\controllers\AdminController::media")
		->before($appFileStoreRequired)
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/sendemail', "site\controllers\SendEmailNewController::index")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/sendemail/', "site\controllers\SendEmailNewController::index")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/sendemail/{slug}', "site\controllers\SendEmailController::show")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/sendemail/{slug}/', "site\controllers\SendEmailController::show")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);

$app->match('/admin/areas/', "site\controllers\AdminController::areas")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/areas/{countryslug}', "site\controllers\AdminAreasController::index")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/areas/{countryslug}/new', "site\controllers\AdminAreasController::newArea")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/areas/{countryslug}/action', "site\controllers\AdminAreasController::action")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);

$app->match('/admin/tag/', "site\controllers\AdminController::listTags")
		->before($permissionCalendarAdministratorRequired);
$app->match('/admin/tag/new', "site\controllers\AdminController::newTag")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/tag/{slug}', "site\controllers\AdminTagController::show")
		->before($permissionCalendarAdministratorRequired)
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/admin/tag/{slug}/edit', "site\controllers\AdminTagController::edit")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite)
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/admin/tag/{slug}/delete', "site\controllers\AdminTagController::delete")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite)
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/admin/tag/{slug}/undelete', "site\controllers\AdminTagController::undelete")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite)
		->assert('slug', FRIENDLY_SLUG_REGEX); 

$app->match('/admin/usergroup/', "site\controllers\AdminController::listUserGroups")
		->before($permissionCalendarAdministratorRequired);
$app->match('/admin/usergroup/new', "site\controllers\AdminController::newUserGroup")
		->before($permissionCalendarAdministratorRequired)
		->before($canChangeSite);
$app->match('/admin/usergroup/{id}', "site\controllers\AdminUserGroupController::show")
		->before($permissionCalendarAdministratorRequired)
		->assert('slug', FRIENDLY_SLUG_REGEX);
$app->match('/admin/usergroup/{id}/users', "site\controllers\AdminUserGroupController::users")
		->before($permissionCalendarAdministratorRequired)
		->assert('slug', FRIENDLY_SLUG_REGEX);
$app->match('/admin/usergroup/{id}/permissions', "site\controllers\AdminUserGroupController::permissions")
		->before($permissionCalendarAdministratorRequired)
		->assert('slug', FRIENDLY_SLUG_REGEX);

$app->match('/admin/user/', "site\controllers\AdminController::listUsers")
	->before($permissionCalendarAdministratorRequired);
$app->match('/admin/user/{username}/', "site\controllers\AdminUserController::index")
	->before($permissionCalendarAdministratorRequired)
	->before($canChangeSite);

		
		
$app->match('/map', "site\controllers\MapController::index");
$app->match('/map/', "site\controllers\MapController::index");

$app->match('/map/time', "site\controllers\MapTimeController::index");
$app->match('/map/time/', "site\controllers\MapTimeController::index");
$app->match('/map/time/getdata.json', "site\controllers\MapTimeController::getDataJson");


$app->match('/displayboard', "site\controllers\DisplayBoardController::index");
$app->match('/displayboard/', "site\controllers\DisplayBoardController::index");
$app->match('/displayboard/run', "site\controllers\DisplayBoardController::run");
$app->match('/displayboard/run/', "site\controllers\DisplayBoardController::run");

$app->match('/importurl', "site\controllers\ImportURLListController::index"); 
$app->match('/importurl/', "site\controllers\ImportURLListController::index"); 

$app->match('/importurl/{slug}', "site\controllers\ImportURLController::show")
		->assert('slug', '\d+'); 
$app->match('/importurl/{slug}/', "site\controllers\ImportURLController::show")
		->assert('slug', '\d+'); 
$app->match('/importurl/{slug}/edit', "site\controllers\ImportURLController::edit")
		->before($permissionCalendarChangeRequired)
		->before($featureImporterRequired)
		->before($canChangeSite)
		->assert('slug', '\d+'); 
$app->match('/importurl/{slug}/enable', "site\controllers\ImportURLController::enable")
		->before($permissionCalendarChangeRequired)
		->before($featureImporterRequired)
		->before($canChangeSite)
		->assert('slug', '\d+'); 
$app->match('/importurl/{slug}/disable', "site\controllers\ImportURLController::disable")
		->before($permissionCalendarChangeRequired)
		->before($featureImporterRequired)
		->before($canChangeSite)
		->assert('slug', '\d+'); 
$app->match('/importurl/{slug}/log', "site\controllers\ImportURLController::log")
		->assert('slug', '\d+'); 

$app->match('/curatedlist', "site\controllers\CuratedListListController::index"); 
$app->match('/curatedlist/', "site\controllers\CuratedListListController::index"); 

$app->match('/curatedlist/new/', "site\controllers\CuratedListNewController::newCuratedList")
		->before($permissionCalendarChangeRequired)
		->before($featureCuratedListRequired)
		->before($canChangeSite); 

$app->match('/curatedlist/{slug}', "site\controllers\CuratedListController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/curatedlist/{slug}/', "site\controllers\CuratedListController::show")
		->assert('slug', FRIENDLY_SLUG_REGEX); 
$app->match('/curatedlist/{slug}/edit', "site\controllers\CuratedListController::edit")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($permissionCalendarChangeRequired)
		->before($featureCuratedListRequired)
		->before($canChangeSite); 
$app->match('/curatedlist/{slug}/curators', "site\controllers\CuratedListController::curators")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->before($canChangeSite); 
$app->match('/curatedlist/{slug}/calendar', "site\controllers\CuratedListController::calendarNow")
		->assert('slug', FRIENDLY_SLUG_REGEX) ; 
$app->match('/curatedlist/{slug}/calendar/', "site\controllers\CuratedListController::calendarNow")
		->assert('slug', FRIENDLY_SLUG_REGEX) ; 
$app->match('/curatedlist/{slug}/calendar/{year}/{month}', "site\controllers\CuratedListController::calendar")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/curatedlist/{slug}/calendar/{year}/{month}/', "site\controllers\CuratedListController::calendar")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('year', '\d+')
		->assert('month', '\d+') ; 
$app->match('/curatedlist/{slug}/event/{eslug}/remove', "site\controllers\CuratedListEventController::remove")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('eslug', '\d+')
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite); 
$app->match('/curatedlist/{slug}/event/{eslug}/add', "site\controllers\CuratedListEventController::add")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('eslug', '\d+')
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite)
		->before($featureCuratedListRequired);
$app->match('/curatedlist/{slug}/group/{gslug}/remove', "site\controllers\CuratedListGroupController::remove")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('gslug', '\d+')
		->before($permissionCalendarChangeRequired)
		->before($canChangeSite);
$app->match('/curatedlist/{slug}/group/{gslug}/add', "site\controllers\CuratedListGroupController::add")
		->assert('slug', FRIENDLY_SLUG_REGEX)
		->assert('gslug', '\d+')
		->before($canChangeSite)
		->before($permissionCalendarChangeRequired)
		->before($featureGroupRequired)
		->before($featureCuratedListRequired);

$app->match('/media', "site\controllers\MediaListController::index")
		->before($appFileStoreRequired); 
$app->match('/media/', "site\controllers\MediaListController::index")
		->before($appFileStoreRequired); 

$app->match('/media/{slug}', "site\controllers\MediaController::show")
		->assert('slug', '\d+')
		->before($appFileStoreRequired); 
$app->match('/media/{slug}/', "site\controllers\MediaController::show")
		->assert('slug', '\d+')
		->before($appFileStoreRequired); 
$app->match('/media/{slug}/thumbnail', "site\controllers\MediaController::imageThumbnail")
		->assert('slug', '\d+')
		->before($appFileStoreRequired); 
$app->match('/media/{slug}/normal', "site\controllers\MediaController::imageNormal")
		->assert('slug', '\d+')
		->before($appFileStoreRequired); 
$app->match('/media/{slug}/full', "site\controllers\MediaController::imageFull")
		->assert('slug', '\d+')
		->before($appFileStoreRequired); 


$app->match('/stopWatchingFromEmail/{userid}/{code}', "site\controllers\IndexController::stopWatchingFromEmail")
		->assert('userid', '\d+')
		->before($canChangeSite); 

// This route is duplicated from the index site so that JavaScript on the Site domain can get to it.
$app->match('/me/notification.json', "index\controllers\CurrentUserController::listNotificationsJson") 
		->before($appUserRequired); 


