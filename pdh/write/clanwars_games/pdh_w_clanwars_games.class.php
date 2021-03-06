<?php
/*
* Project:		EQdkp-Plus
* License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:		2007
* Date:			$Date: 2013-09-10 22:04:59 +0200 (Di, 10 Sep 2013) $
* -----------------------------------------------------------------------
* @author		$Author: godmod $
* @copyright	2006-2011 EQdkp-Plus Developer Team
* @link			http://eqdkp-plus.com
* @package		eqdkpplus
* @version		$Rev: 13558 $
*
* $Id: pdh_w_clanwars_games.class.php 13558 2013-09-10 20:04:59Z godmod $
*/

if(!defined('EQDKP_INC')) {
	die('Do not access this file directly.');
}

if(!class_exists('pdh_w_clanwars_games')) {
	class pdh_w_clanwars_games extends pdh_w_generic {

		public function __construct() {
			parent::__construct();
		}
		
		public function add_game($arrData) {
			$arrData['icon'] = str_replace($this->pfh->FileLink('game_icons', 'clanwars', 'absolute'), '', $arrData['icon']);
				
			$arrSet = array(
				'name'			=> $arrData['name'],
				'version'		=> $arrData['version'],
				'icon'			=> $arrData['icon'],
				'pubdate'		=> $arrData['pubdate'],
				'genre'			=> $arrData['genre'],
				'company'		=> $arrData['company'],
				'website'		=> $arrData['website'],
				'usk'			=> $arrData['usk'],
			);
			
			$objQuery = $this->db->prepare("INSERT INTO __clanwars_games :p")->set($arrSet)->execute();
			
			if($objQuery) {
				$id = $objQuery->insertId;
				$this->pdh->enqueue_hook('clanwars_games_update', array($id));
				return $id;
			}
			return false;
		}

		public function update_game($id, $arrData) {
			$arrData['icon'] = str_replace($this->pfh->FileLink('game_icons', 'clanwars', 'absolute'), '', $arrData['icon']);
			
			$arrSet = array(
				'name'			=> $arrData['name'],
				'version'		=> $arrData['version'],
				'icon'			=> $arrData['icon'],
				'pubdate'		=> $arrData['pubdate'],
				'genre'			=> $arrData['genre'],
				'company'		=> $arrData['company'],
				'website'		=> $arrData['website'],
				'usk'			=> $arrData['usk'],
			);
			
			$objQuery = $this->db->prepare("UPDATE __clanwars_games :p WHERE id =?")->set($arrSet)->execute($id);
			
			if($objQuery) {				
				$this->pdh->enqueue_hook('clanwars_games_update', array($id));
				return true;
			}
			return false;
		}

		public function delete_game($intGameID) {
			
			$objQuery = $this->db->prepare("DELETE FROM __clanwars_games WHERE id = ?;")->execute($intGameID);

			if($objQuery) {
				$this->pdh->enqueue_hook('clanwars_games_update', array($intGameID));
				return true;
			}

			return false;
		}
		
		
		public function enable($intGameID){
			$arrSet = array('enabled' => 1);
			$objQuery = $this->db->prepare("UPDATE __clanwars_games :p WHERE id =?")->set($arrSet)->execute($intGameID);
			
			$this->pdh->enqueue_hook('clanwars_games_update');
		}
		
		public function disable($intGameID){
			$arrSet = array('enabled' => 0);
			$objQuery = $this->db->prepare("UPDATE __clanwars_games :p WHERE id =?")->set($arrSet)->execute($intGameID);
				
			$this->pdh->enqueue_hook('clanwars_games_update');
		}
		
		public function reset() {
			$this->db->query("TRUNCATE TABLE __clanwars_games;");
			$this->pdh->enqueue_hook('clanwars_games_update');
		}
	}
}
?>