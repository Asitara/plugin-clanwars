<?php
/*
* Project:		EQdkp-Plus
* License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:		2010
* Date:			$Date: 2013-01-29 17:35:08 +0100 (Di, 29 Jan 2013) $
* -----------------------------------------------------------------------
* @author		$Author: wallenium $
* @copyright	2006-2014 EQdkp-Plus Developer Team
* @link			http://eqdkp-plus.eu
* @package		eqdkpplus
* @version		$Rev: 12937 $
*
* $Id: pdh_r_articles.class.php 12937 2013-01-29 16:35:08Z wallenium $
*/

if ( !defined('EQDKP_INC') ){
	die('Do not access this file directly.');
}
				
if ( !class_exists( "pdh_r_clanwars_teams" ) ) {
	class pdh_r_clanwars_teams extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array();
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $clanwars_teams = null;

	public $hooks = array(
		'clanwars_teams_update',
	);		
			
	public $presets = array(
		'clanwars_teams_id' => array('id', array('%intTeamID%'), array()),
		'clanwars_teams_name' => array('name', array('%intTeamID%'), array()),
		'clanwars_teams_description' => array('description', array('%intTeamID%'), array()),
		'clanwars_teams_icon' => array('icon', array('%intTeamID%'), array()),
		'clanwars_teams_members' => array('members', array('%intTeamID%'), array()),
		'clanwars_teams_gameID' => array('gameID', array('%intTeamID%'), array()),
		'clanwars_teams_clanID' => array('clanID', array('%intTeamID%'), array()),
		'clanwars_teams_actions' => array('actions', array('%intTeamID%', '%link_url%', '%link_url_suffix%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_clanwars_teams_table');
			
			$this->clanwars_teams = NULL;
	}
					
	public function init(){
			$this->clanwars_teams	= $this->pdc->get('pdh_clanwars_teams_table');				
					
			if($this->clanwars_teams !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __clanwars_teams');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->clanwars_teams[(int)$drow['id']] = array(
						'id'				=> (int)$drow['id'],
						'name'				=> $drow['name'],
						'description'		=> $drow['description'],
						'icon'				=> $drow['icon'],
						'members'			=> unserialize($drow['members']),
						'gameID'			=> (int)$drow['gameID'],
						'clanID'			=> (int)$drow['clanID'],
					);
				}
				
				$this->pdc->put('pdh_clanwars_teams_table', $this->clanwars_teams, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->clanwars_teams === null) return array();
			return array_keys($this->clanwars_teams);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID];
			}
			return false;
		}
		
		public function get_actions($intTeamID, $baseurl, $url_suffix=''){
			return "<a href='".$baseurl.$this->SID.'&amp;t='.$intTeamID.$url_suffix."'>
				<i class='fa fa-pencil fa-lg' title='".$this->user->lang('edit')."'></i>
			</a>";
		}
				
		/**
		 * Returns id for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype id
		 */
		 public function get_id($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['id'];
			}
			return false;
		}

		/**
		 * Returns name for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype name
		 */
		 public function get_name($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['name'];
			}
			return false;
		}

		/**
		 * Returns description for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype description
		 */
		 public function get_description($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['description'];
			}
			return false;
		}

		/**
		 * Returns icon for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype icon
		 */
		 public function get_icon($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['icon'];
			}
			return false;
		}
		
		public function get_html_icon($intTeamID, $intSize=32){
			$strIcon = $this->get_icon($intTeamID);
			if ($strIcon && strlen($strIcon)){
				$strExtension = pathinfo($strIcon, PATHINFO_EXTENSION);
				$strIconName = md5('team_'.$intTeamID.$strIcon).'_'.intval($intSize).'.'.$strExtension;
				$strThumbnailIcon = $this->pfh->FolderPath('thumbnails', 'clanwars').$strIconName;
				if (is_file($strThumbnailIcon)){
					return '<img src="'.$this->pfh->FolderPath('thumbnails', 'clanwars', 'absolute').$strIconName.'" alt="'.$this->get_name($intTeamID).'"/>';
				} else {
					$strFullImage = $this->pfh->FolderPath('team_icons', 'clanwars').$strIcon;
					$this->pfh->thumbnail($strFullImage, $this->pfh->FolderPath('thumbnails', 'clanwars'), $strIconName, intval($intSize));
					return '<img src="'.$this->pfh->FolderPath('thumbnails', 'clanwars', 'absolute').$strIconName.'" alt="'.$this->get_name($intTeamID).'"/>';
				}
			}
			
			return '';
		}

		/**
		 * Returns members for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype members
		 */
		 public function get_members($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['members'];
			}
			return false;
		}
		
		public function get_html_members($intTeamID){
			$arrMembers = $this->get_members($intTeamID);
			if($arrMembers && is_array($arrMembers) && count($arrMembers)){
				$arrUser = $this->pdh->aget('user', 'name', 0, array($arrMembers));
				return implode($arrUser, ', ');
			}
			return '';
		}

		/**
		 * Returns gameID for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype gameID
		 */
		 public function get_gameID($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['gameID'];
			}
			return false;
		}
		
		public function get_html_gameID($intTeamID){
			return $this->pdh->geth('clanwars_games', 'name', array($this->get_gameID($intTeamID)));
		}

		/**
		 * Returns clanID for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype clanID
		 */
		 public function get_clanID($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['clanID'];
			}
			return false;
		}
		
		public function get_html_clanID($intTeamID){
			return $this->pdh->geth('clanwars_clans', 'name', array($this->get_clanID($intTeamID)));
		}

	}//end class
}//end if
?>