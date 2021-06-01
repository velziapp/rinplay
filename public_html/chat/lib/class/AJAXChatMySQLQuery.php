<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license GNU Affero General Public License
 * @link https://blueimp.net/ajax/
 */

// Class to perform SQL (mysql) queries:
class AJAXChatMySQLQuery {

	var $_connectionID;
	var $_sql = '';
	var $_result = 0;
	var $_errno = 0;
	var $_error = '';

	// Constructor:
	function AJAXChatMySQLQuery($sql, $connectionID = null) {
		$this->_sql = trim($sql);
		$this->_connectionID = $connectionID;
		if($this->_connectionID) {
			$this->_result = mysqli_query( $this->_connectionID, $this->_sql);
			if(!$this->_result) {
				$this->_errno = mysqli_errno($this->_connectionID);
				$this->_error = mysqli_error($this->_connectionID);
			}
		} else {
			$this->_result = mysqli_query($GLOBALS["___mysqli_ston"], $this->_sql);
			if(!$this->_result) {
				$this->_errno = mysqli_errno($GLOBALS["___mysqli_ston"]);
				$this->_error = mysqli_error($GLOBALS["___mysqli_ston"]);
			}	
		}
	}

	// Returns true if an error occured:
	function error() {
		// Returns true if the Result-ID is valid:
		return !(bool)($this->_result);
	}

	// Returns an Error-String:
	function getError() {
		if($this->error()) {
			$str  = 'Query: '	 .$this->_sql  ."\n";
			$str .= 'Error-Report: '	.$this->_error."\n";
			$str .= 'Error-Code: '.$this->_errno;
		} else {
			$str = "No errors.";
		}
		return $str;
	}

	// Returns the content:
	function fetch() {
		if($this->error()) {
			return null;
		} else {
			return mysqli_fetch_assoc($this->_result);
		}
	}

	// Returns the number of rows (SELECT or SHOW):
	function numRows() {
		if($this->error()) {
			return null;
		} else {
			return mysqli_num_rows($this->_result);
		}
	}

	// Returns the number of affected rows (INSERT, UPDATE, REPLACE or DELETE):
	function affectedRows() {
		if($this->error()) {
			return null;
		} else {
			return mysqli_affected_rows($this->_connectionID);
		}
	}

	// Frees the memory:
	function free() {
		@((mysqli_free_result($this->_result) || (is_object($this->_result) && (get_class($this->_result) == "mysqli_result"))) ? true : false);
	}
	
}
?>