<?php


namespace repositories\builders;

use models\SiteModel;
use models\EventModel;
use models\GroupModel;
use models\VenueModel;
use models\TagModel;
use models\ImportURLModel;
use models\UserAccountModel;
use models\EventHistoryModel;
use models\GroupHistoryModel;
use models\VenueHistoryModel;
use models\AreaHistoryModel;
use models\TagHistoryModel;
use models\ImportURLHistoryModel;
use models\API2ApplicationModel;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class HistoryRepositoryBuilder {

	protected $includeTagHistory = true;
	protected $includeEventHistory = true;
	protected $includeGroupHistory = true;
	protected $includeVenueHistory = true;
	protected $includeAreaHistory = true;
	protected $includeImportURLHistory = true;


	public function getIncludeEventHistory() {
		return $this->includeEventHistory;
	}

	public function setIncludeEventHistory($includeEventHistory) {
		$this->includeEventHistory = $includeEventHistory;
	}

	public function getIncludeGroupHistory() {
		return $this->includeGroupHistory;
	}

	public function setIncludeGroupHistory($includeGroupHistory) {
		$this->includeGroupHistory = $includeGroupHistory;
	}

	public function getIncludeTagHistory() {
		return $this->includeTagHistory;
	}

	public function setIncludeTagHistory($includeTagHistory) {
		$this->includeTagHistory = $includeTagHistory;
	}

	public function getIncludeVenueHistory() {
		return $this->includeVenueHistory;
	}

	public function setIncludeVenueHistory($includeVenueHistory) {
		$this->includeVenueHistory = $includeVenueHistory;
	}
	
	public function getIncludeAreaHistory() {
		return $this->includeAreaHistory;
	}

	public function setIncludeAreaHistory($includeAreaHistory) {
		$this->includeAreaHistory = $includeAreaHistory;
	}

	public function getIncludeImportURLHistory() {
		return $this->includeImportURLHistory;
	}

	public function setIncludeImportURLHistory($includeImportURLHistory) {
		$this->includeImportURLHistory = $includeImportURLHistory;
	}

			
	protected $since;
	
	public function setSince($since) {
		$this->since = $since;
	}
	
	/** @var SiteModel **/
	protected $site;
	
	public function setSite(SiteModel $site) {
		$this->site = $site;
		$this->includeEventHistory = true;
		$this->includeGroupHistory = true;
		$this->includeVenueHistory = true;
		$this->includeAreaHistory = true;
		$this->includeTagHistory = true;
		$this->includeImportURLHistory = true;
	}


	/** @var GroupModel **/
	protected $group;
	
	public function setGroup(GroupModel $group) {
		$this->group = $group;
		$this->includeEventHistory = true;
		$this->includeGroupHistory = true;
		$this->includeVenueHistory = false;
		$this->includeAreaHistory = false;
		$this->includeTagHistory = false;
		$this->includeImportURLHistory = true;
	}

	/** @var EventModel **/
	protected $event;
	
	public function setEvent(EventModel $event) {
		$this->event = $event;
		$this->includeEventHistory = true;
		$this->includeGroupHistory = true;
		$this->includeVenueHistory = true;
		$this->includeAreaHistory = false;
		$this->includeTagHistory = false;
		$this->includeImportURLHistory = false;
	}

	/** @var VenueModel **/
	protected $venue;
	
	public function setVenue(VenueModel $venue) {
		$this->venue = $venue;
		$this->includeEventHistory = true;
		$this->includeGroupHistory = false;
		$this->includeVenueHistory = true;
		$this->includeAreaHistory = false;
		$this->includeTagHistory = false;
		$this->includeImportURLHistory = false;
	}
	
	/** @var TagModel **/
	protected $tag;
	
	public function setTag(TagModel $tag) {
		$this->tag = $tag;
		$this->includeEventHistory = false;
		$this->includeGroupHistory = false;
		$this->includeVenueHistory = false;
		$this->includeAreaHistory = false;
		$this->includeTagHistory = true;
		$this->includeImportURLHistory = false;
	}
	
	
	protected $venueVirtualOnly = false;
	
	
	public function setVenueVirtualOnly($value) {
		$this->venueVirtualOnly = $value;
		if ($value) {
			$this->includeEventHistory = true;
			$this->includeGroupHistory = false;
			$this->includeVenueHistory = false;
			$this->includeAreaHistory = false;
			$this->includeTagHistory = false;
			$this->includeImportURLHistory = false;
		}
	}
	
	/** @var UserModel **/
	protected $notUser;
	
	public function setNotUser(UserAccountModel $notUser) {
		$this->notUser = $notUser;
	}
	
	/** @var API2ApplicationModel **/
	protected $api2app;
	
	public function setAPI2Application(API2ApplicationModel $api2app) {
		$this->api2app = $api2app;
	}

		


	protected $limit = 50;
	
	public function fetchAll() {
		global $DB;
		
		$results = array();
		
	
		/////////////////////////// Events History
		
		if ($this->includeEventHistory) {
			$where = array();
			$joins = array();
			$params = array();

			if ($this->event) {
				$where[] = 'event_information.id=:event';
				$params['event'] = $this->event->getId();
			}

			if ($this->group) {
				// We use a seperate table here so if event is in 2 groups and we select events in 1 group that isn't the main group only, 
				// the normal event_in_group table still shows the main group.
				$joins[] =  " JOIN event_in_group AS event_in_group_select ON event_in_group_select.event_id = event_information.id ".
					"AND event_in_group_select.removed_at IS NULL AND event_in_group_select.group_id = :group_id ";
				$params['group_id'] = $this->group->getId();
			}

			if ($this->site) {
				$where[] = 'event_information.site_id =:site';
				$params['site'] = $this->site->getId();
			}

			if ($this->venue) {
				$where[] = 'event_information.venue_id = :venue';
				$params['venue'] = $this->venue->getId();
			}
			
			if ($this->since) {
				$where[] = ' event_history.created_at >= :since ';
				$params['since'] = $this->since->format("Y-m-d H:i:s");
			}
			
			if ($this->notUser) {
				$where[] = 'event_history.user_account_id != :userid ';
				$params['userid'] = $this->notUser->getId();
			}
			
			if ($this->api2app) {
				$where[] = 'event_history.api2_application_id  = :api2app';
				$params['api2app'] = $this->api2app->getId();
			}
			
			if ($this->venueVirtualOnly) {
				// we check both on an OR, that way we get both
				// a) events that were not virtual and became virtual, we get their full history
				// b) events that were virtual and now aren't, we get some of their history
				$where[] = " ( event_information.is_virtual = '1' OR event_history.is_virtual = '1' )";
			}
			
			$sql = "SELECT event_history.*, group_information.title AS group_title,  group_information.id AS group_id,  event_information.slug AS event_slug, user_account_information.username AS user_account_username FROM event_history ".
					" LEFT JOIN user_account_information ON user_account_information.id = event_history.user_account_id ".
					" LEFT JOIN event_information ON event_information.id = event_history.event_id ".
					" LEFT JOIN event_in_group ON event_in_group.event_id = event_information.id AND event_in_group.removed_at IS NULL AND event_in_group.is_main_group = '1' ".
					" LEFT JOIN group_information ON group_information.id = event_in_group.group_id ".
					implode(" ",$joins).
					($where ? " WHERE ".implode(" AND ", $where) : "").
					" ORDER BY event_history.created_at DESC LIMIT ".$this->limit;

			//var_dump($sql); var_dump($params);
			
			$stat = $DB->prepare($sql);
			$stat->execute($params);

			while($data = $stat->fetch()) {
				$eventHistory = new EventHistoryModel();
				$eventHistory->setFromDataBaseRow($data);
				$results[] = $eventHistory;
			}
		}
		
		/////////////////////////// Group History

		if ($this->includeGroupHistory) {
			$where = array();
			$params = array();
			
			if ($this->event && $this->event->getGroupId()) {
				$where[] = 'group_information.id=:group';
				$params['group'] = $this->event->getGroupId();
			} else if ($this->group) {
				$where[] = 'group_information.id =:group';
				$params['group'] = $this->group->getId();
			}

			if ($this->site) {
				$where[] = 'group_information.site_id =:site';
				$params['site'] = $this->site->getId();
			}
			
			if ($this->since) {
				$where[] = ' group_history.created_at >= :since ';
				$params['since'] = $this->since->format("Y-m-d H:i:s");
			}
			
			if ($this->notUser) {
				$where[] = 'group_history.user_account_id != :userid ';
				$params['userid'] = $this->notUser->getId();
			}
			
			if ($this->api2app) {
				$where[] = 'group_history.api2_application_id  = :api2app';
				$params['api2app'] = $this->api2app->getId();
			}
			
			$sql = "SELECT group_history.*, group_information.slug AS group_slug, user_account_information.username AS user_account_username FROM group_history ".
					" LEFT JOIN user_account_information ON user_account_information.id = group_history.user_account_id ".
					" LEFT JOIN group_information ON group_information.id = group_history.group_id ".
					($where ? " WHERE ".implode(" AND ", $where) : "").
					" ORDER BY group_history.created_at DESC LIMIT ".$this->limit;

			//var_dump($sql); var_dump($params);
			
			$stat = $DB->prepare($sql);
			$stat->execute($params);
			
			while($data = $stat->fetch()) {
				$groupHistory = new GroupHistoryModel();
				$groupHistory->setFromDataBaseRow($data);
				$results[] = $groupHistory;
			}
			
		}
		
		/////////////////////////// Venue History

		if ($this->includeVenueHistory) {
			$where = array();
			$params = array();
			
			if ($this->event && $this->event->getVenueId()) {
				$where[] = 'venue_information.id=:venue';
				$params['venue'] = $this->event->getVenueId();
			} else if ($this->venue) {
				$where[] = 'venue_information.id=:venue';
				$params['venue'] = $this->venue->getId();
			}

			if ($this->site) {
				$where[] = 'venue_information.site_id =:site';
				$params['site'] = $this->site->getId();
			}
			
			if ($this->since) {
				$where[] = ' venue_history.created_at >= :since ';
				$params['since'] = $this->since->format("Y-m-d H:i:s");
			}
			
			if ($this->notUser) {
				$where[] = 'venue_history.user_account_id != :userid ';
				$params['userid'] = $this->notUser->getId();
			}
			
			if ($this->api2app) {
				$where[] = 'venue_history.api2_application_id  = :api2app';
				$params['api2app'] = $this->api2app->getId();
			}
			
			$sql = "SELECT venue_history.*, venue_information.slug AS venue_slug, user_account_information.username AS user_account_username FROM venue_history ".
					" LEFT JOIN user_account_information ON user_account_information.id = venue_history.user_account_id ".
					" LEFT JOIN venue_information ON venue_information.id = venue_history.venue_id ".
					($where ? " WHERE ".implode(" AND ", $where) : "").
					" ORDER BY venue_history.created_at DESC LIMIT ".$this->limit;

			//var_dump($sql); var_dump($params);
			
			$stat = $DB->prepare($sql);
			$stat->execute($params);
			
			while($data = $stat->fetch()) {
				$venueHistory = new VenueHistoryModel();
				$venueHistory->setFromDataBaseRow($data);
				$results[] = $venueHistory;
			}
			
		}
		
		/////////////////////////// Area History

		if ($this->includeAreaHistory) {
			$where = array();
			$params = array();
			

			if ($this->site) {
				$where[] = 'area_information.site_id =:site';
				$params['site'] = $this->site->getId();
			}
			
			if ($this->since) {
				$where[] = ' area_history.created_at >= :since ';
				$params['since'] = $this->since->format("Y-m-d H:i:s");
			}
			
			if ($this->notUser) {
				$where[] = 'area_history.user_account_id != :userid ';
				$params['userid'] = $this->notUser->getId();
			}
			
			if ($this->api2app) {
				$where[] = 'area_history.api2_application_id  = :api2app';
				$params['api2app'] = $this->api2app->getId();
			}
			
			$sql = "SELECT area_history.*, area_information.slug AS area_slug, user_account_information.username AS user_account_username FROM area_history ".
					" LEFT JOIN user_account_information ON user_account_information.id = area_history.user_account_id ".
					" LEFT JOIN area_information ON area_information.id = area_history.area_id ".
					($where ? " WHERE ".implode(" AND ", $where) : "").
					" ORDER BY area_history.created_at DESC LIMIT ".$this->limit;

			//var_dump($sql); var_dump($params);
			
			$stat = $DB->prepare($sql);
			$stat->execute($params);
			
			while($data = $stat->fetch()) {
				$areaHistory = new AreaHistoryModel();
				$areaHistory->setFromDataBaseRow($data);
				$results[] = $areaHistory;
			}
			
		}
		
		/////////////////////////// Tags History

		if ($this->includeTagHistory) {
			$where = array();
			$params = array();
			

			if ($this->site) {
				$where[] = 'tag_information.site_id =:site';
				$params['site'] = $this->site->getId();
			}
			
			if ($this->since) {
				$where[] = ' tag_history.created_at >= :since ';
				$params['since'] = $this->since->format("Y-m-d H:i:s");
			}
			
			if ($this->notUser) {
				$where[] = 'tag_history.user_account_id != :userid ';
				$params['userid'] = $this->notUser->getId();
			}
			
			if ($this->api2app) {
				$where[] = 'tag_history.api2_application_id  = :api2app';
				$params['api2app'] = $this->api2app->getId();
			}
			
			$sql = "SELECT tag_history.*, tag_information.slug AS tag_slug, user_account_information.username AS user_account_username FROM tag_history ".
					" LEFT JOIN user_account_information ON user_account_information.id = tag_history.user_account_id ".
					" LEFT JOIN tag_information ON tag_information.id = tag_history.tag_id ".
					($where ? " WHERE ".implode(" AND ", $where) : "").
					" ORDER BY tag_history.created_at DESC LIMIT ".$this->limit;

			//var_dump($sql); var_dump($params);
			
			$stat = $DB->prepare($sql);
			$stat->execute($params);
			
			while($data = $stat->fetch()) {
				$tagHistory = new TagHistoryModel();
				$tagHistory->setFromDataBaseRow($data);
				$results[] = $tagHistory;
			}
			
		}
		
		
		
		/////////////////////////// Import URL History

		if ($this->includeImportURLHistory) {
			$where = array();
			$params = array();
			

			if ($this->site) {
				$where[] = 'import_url_information.site_id =:site';
				$params['site'] = $this->site->getId();
			}
			
			

			if ($this->group) {
				$where[] = 'import_url_information.group_id =:group';
				$params['group'] = $this->group->getId();
			}
			
			
			
			if ($this->since) {
				$where[] = ' import_url_history.created_at >= :since ';
				$params['since'] = $this->since->format("Y-m-d H:i:s");
			}
			
			if ($this->notUser) {
				$where[] = 'import_url_history.user_account_id != :userid ';
				$params['userid'] = $this->notUser->getId();
			}
			
			if ($this->api2app) {
				$where[] = 'import_url_history.api2_application_id  = :api2app';
				$params['api2app'] = $this->api2app->getId();
			}
			
			$sql = "SELECT import_url_history.*, import_url_information.slug AS import_url_slug, ".
					"user_account_information.username AS user_account_username ".
					" FROM import_url_history ".
					" LEFT JOIN user_account_information ON user_account_information.id = import_url_history.user_account_id ".
					" LEFT JOIN import_url_information ON import_url_information.id = import_url_history.import_url_id ".
					($where ? " WHERE ".implode(" AND ", $where) : "").
					" ORDER BY import_url_history.created_at DESC LIMIT ".$this->limit;

			//var_dump($sql); var_dump($params);
			
			$stat = $DB->prepare($sql);
			$stat->execute($params);
			
			while($data = $stat->fetch()) {
				$tagHistory = new ImportURLHistoryModel();
				$tagHistory->setFromDataBaseRow($data);
				$results[] = $tagHistory;
			}
			
		}
		
		
		
		
		////////////////////// Finally sort & truncate

		$usort = function($a, $b) {
			if ($a->getCreatedAtTimeStamp() == $b->getCreatedAtTimeStamp()) {
				return 0;
			} else if ($a->getCreatedAtTimeStamp() > $b->getCreatedAtTimeStamp()) {
				return -1;
			} else {
				return 1;
			}
		};
		
		usort($results, $usort);
		
		array_slice($results, 0, $this->limit);
		
		return $results;
		
		
	}
		
		
	
}


