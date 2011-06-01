<?php
/**
* @version		1.5.2 ZendModelCreator.php hussfelt
* @package		ZendModelCreator
* @copyright	Copyright (C) 2005 - 2011 Hussfelt Consulting AB. All rights reserved.
* @license		GNU/GPL V 1.0
* This is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* */
require_once 'DTOCreatorService.php';
require_once 'DAOCreatorService.php';
require_once 'EntityCreatorService.php';
require_once 'ServiceCreatorService.php';
require_once 'InterfaceCreatorService.php';
require_once 'ErrorconstantsCreatorService.php';
require_once 'ServiceExceptionCreator.php';
require_once 'StaticFilesCreatorService.php';

class ZendModelCreator {

	private $_settings = array();
	private $_dbcon = null;
	private $_data = array();

	public static $_version = '1.5.1';
	public static $_contact = 'Hussfelt Consulting AB';
	public static $tables = array();

	public static $interface = array();
	public static $errorconstants = array();
	public static $directoryStructure = array(
		"ContainerDir" => "models",
		"DS" => "/",
		"DirectoryStructure" => array(
					'DTO' => 'api',
					'DAO' => 'dao',
					'ENT' => 'persistence',
					'SRV' => 'service',
					'INT' => 'api',
					'EXC' => 'service',
					'GEN' => 'db',
					'CON' => 'constants'
		),
		"FileNames" => array(
					'DTO' => '[tbl]DTO.php',
					'DAO' => '[tbl]DAO.php',
					'ENT' => '[tbl]Entity.php',
					'SRV' => '[tbl]Service.php',
					'INT' => 'I[tbl]Service.php',
					'EXC' => '[tbl]ServiceException.php'
		)
	);

	public function __construct($settings){
		// Set settings
		$this->_settings = $settings;

		// Open db connection
		if (!$this->_dbcon = mysql_connect($this->getSetting('mysql_host'),$this->getSetting('mysql_user'),$this->getSetting('mysql_password'))) {
			die("Can't connect to database");
		}
		if (!mysql_select_db($this->getSetting('mysql_db'), $this->_dbcon)) {
			die("Can't select database");
		}

		// set tables
		$this->_setTables();
		$this->_setTableData();

		// close connection
		mysql_close($this->_dbcon);
	}

	public function setDirectoryStructure($directoryStructure){
		self::$directoryStructure = $directoryStructure;
	}

	public function getSetting($key) {
		// if setting is not set, die
		if(!isset($this->_settings[$key])) {
			die("Settings not set correctly. [global]");
		}
		return $this->_settings[$key];
	}

	public static function getVersion() {
		return self::$_version;
	}

	public static function getContact() {
		return self::$_contact;
	}

	private function _setTables(){
		// show all tables in selected database
		$result = mysql_query("SHOW tables", $this->_dbcon);
		while($row = mysql_fetch_row($result)) {
			self::$tables[$row[0]] = array();
		}
	}

	private function _setTableData(){
		foreach (self::$tables as $tbl => $junk) {
			$this->devnull = $junk;
			$result = mysql_query("DESCRIBE ".$tbl, $this->_dbcon);

			while($row = mysql_fetch_row($result)) {
				$name = $row[0];
				$type = $row[1];

				// if the fourth description is PRI, this is a primary key and is
				// pushed to self::$table[$tbl]['primary_key']
				if($row[3] == "PRI") {
					self::$tables[$tbl]['primary_key'] = $name;
				}

				// get the datetype of the column and set a proper DTO type
				if(stristr($type, "int")) {
					$type = DTOCreatorService::$INTEGER;
				} elseif(stristr($type, "date")) {
					$type = DTOCreatorService::$DATETIME;
				} elseif(stristr($type, "double")) {
					$type = DTOCreatorService::$DOUBLE;
				} else {
					// everything else
					$type = DTOCreatorService::$STRING;
				}

				// set to global table
				$final = array($name => $type);
				self::$tables[$tbl]['fields'][] = $final;
			}
		}
	}

	public function getDataFromServices() {
		foreach (self::$tables as $table => $data) {
			// clean interface array
			foreach ($this->getSetting('types') as $type => $get_data) {
				if($get_data) {
					// Quit if no primary key is set.
					if (isset($data['primary_key'])) {
						// set object names to Ucfirst then lowercase.
						$table = ucfirst(strtolower($table));
						switch ($type) {
							case "create_dto":
								$DTOService = new DTOCreatorService();
								$this->_data[$table]['DTO'] = $DTOService->createDTO($table,$data);
								break;
							case "create_dao":
								$DAOService = new DAOCreatorService();
								$this->_data[$table]['DAO'] = $DAOService->createDAO($table,$data);
								break;
							case "create_entity":
								$EntityService = new EntityCreatorService();
								$this->_data[$table]['ENT'] = $EntityService->createEntity($table,$data);
								break;
							case "create_service":
								$ServiceService = new ServiceCreatorService();
								$this->_data[$table]['SRV'] = $ServiceService->createService($table,$data);
								break;
							case "create_interface":
								$InterfaceService = new InterfaceCreatorService();
								$this->_data[$table]['INT'] = $InterfaceService->createInterface($table,self::$interface[strtolower($table)]);
								break;
							case "create_exception":
								$ServiceExceptionCreatorService = new ServiceExceptionCreatorService();
								$this->_data[$table]['EXC'] = $ServiceExceptionCreatorService->createServiceException($table);
								break;
							case "create_genericfiles":
								break;
							case "create_errorconstants":
								break;
							default:
								die("Settings not set correctly. [types]");
								break;
						}
					}
				}
			}
		}
		$types = $this->getSetting('types');
		if($types['create_errorconstants']) {
			$ErrorconstantsCreatorService = new ErrorconstantsCreatorService();
			$this->_data['ZENDMODELCREATOR_ERR'] = $ErrorconstantsCreatorService->createErrorconstants(self::$errorconstants);
		}
	}

	public function writePHPCreatedModelData() {
		$DS = self::$directoryStructure['DS'];
		if(!is_dir(self::$directoryStructure['ContainerDir']) && !mkdir(self::$directoryStructure['ContainerDir'])) {
			die("Can't create dir: ".self::$directoryStructure['ContainerDir']);
		}
		$types = $this->getSetting('types');
		// If user specifies that errorconstants file should be created
		if($types['create_errorconstants']) {
			// check if the Constants directory already exist, if not create it
			if(!is_dir(self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['CON']) && !mkdir(self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['CON'])) {
				die("Can't create dir: ".self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['CON']);
			}
			if (!$handle = fopen(self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['CON'].$DS."ErrorConstants.php", 'w+')) {
				die("Cannot open/create file: ".self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['CON'].$DS."ErrorConstants.php");
			}
			// write contents to file
			if (fwrite($handle, $this->_data['ZENDMODELCREATOR_ERR']) === FALSE) {
				die("Cannot write to file: ".self::$directoryStructure['ContainerDir'].$DS."ErrorConstants.php");
			}
			fclose($handle);
			unset($this->_data['ZENDMODELCREATOR_ERR']);
		}
		// If user specifies that generic files should be created
		if($types['create_genericfiles']) {
			// check if the GenericFiles directory already exist, if not create it
			if(!is_dir(self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['GEN']) && !mkdir(self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['GEN'])) {
				die("Can't create dir: ".self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['GEN']);
			}
			// start static files service
			$StaticFilesCreatorService = new StaticFilesCreatorService();
			// which generic files do we have?
			$genericFiles = array(
				"GenericEntity",
				"GenericDAO",
				"GenericDTO",
				"GenericDateTime",
				"DbFactory",
				"SystemConfig"

			);

			foreach ($genericFiles as $genericFile) {
				if (!$handle = fopen(self::$directoryStructure['ContainerDir'].$DS.self::$directoryStructure['DirectoryStructure']['GEN'].$DS.$genericFile.".php", 'w+')) {
					die("Cannot open/create file: ".self::$directoryStructure['ContainerDir'].$DS.$genericFile.".php");
				}
				// write contents to file
				$genFunc = "create".$genericFile;
				if (fwrite($handle, $StaticFilesCreatorService->$genFunc($this->_settings)) === FALSE) {
					die("Cannot write to file: ".self::$directoryStructure['ContainerDir'].$DS.$genericFile.".php");
				}
				fclose($handle);
			}
		}
		foreach ($this->_data as $table => $data) {
			// lowercase the table variable
			$table = strtolower($table);
			// check if the table directory already exist, if not create it
			if(!is_dir(self::$directoryStructure['ContainerDir'].$DS.$table) && !mkdir(self::$directoryStructure['ContainerDir'].$DS.$table)) {
				die("Can't create dir: ".self::$directoryStructure['ContainerDir'].$DS.$table);
			}
			// go through all php files generated in this run
			foreach ($data as $type => $php) {
				// set compete dirname
				$dirName = self::$directoryStructure['ContainerDir'].$DS.$table.$DS.self::$directoryStructure['DirectoryStructure'][$type];
				// set filename
				$fileName = str_replace("[tbl]",ucfirst($table),self::$directoryStructure['FileNames'][$type]);
				// check if the dir is already present, or create
				if(!is_dir($dirName) && !mkdir($dirName)) {
					die("Can't create dir: $dirName");
				}
				// try to open/create file, set content to 0 bytes (empty)
				if (!$handle = fopen($dirName.$DS.$fileName, 'w+')) {
					die("Cannot open/create file: $dirName$DS$fileName");
				}
				// write contents to file
				if (fwrite($handle, $php) === FALSE) {
					die("Cannot write to file: $dirName$DS$fileName");
				}
				fclose($handle);
			}
		}
	}

	public function getPHPCreatedModelData() {
		echo "<table>";
		$types = $this->getSetting('types');
		// If user specifies that errorconstants file should be created
		if($types['create_errorconstants']) {
			echo "\t<tr><th>ErrorConstants</th></tr>\n";
			echo "\t<tr>\n";
			echo "\t\t<td>\n";
			echo "\t\t\t<label for=\"ERRCONST\">ErrorConstants</label><br />\n";
			echo "\t\t\t<textarea id=\"ERRCONST\" rows=\"10\" cols=\"40\">".$this->_data['ZENDMODELCREATOR_ERR']."</textarea>\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";
			unset($this->_data['ZENDMODELCREATOR_ERR']);
		}
		// If user specifies that generic files should be created
		if($types['create_genericfiles']) {
			$StaticFilesCreatorService = new StaticFilesCreatorService();
			// which generic files do we have?
			$genericFiles = array(
				"GenericEntity",
				"GenericDAO",
				"GenericDTO",
				"GenericDateTime"
			);
			echo "\t<tr><th>StaticFiles</th></tr>\n";
			echo "\t<tr>\n";
			foreach ($genericFiles as $genericFile) {
				$genFunc = "create".$genericFile;
				$content = $StaticFilesCreatorService->$genFunc();
				echo "\t\t<td>\n";
				echo "\t\t\t<label for=\"$genericFile\">$genericFile</label><br />\n";
				echo "\t\t\t<textarea id=\"$genericFile\" rows=\"10\" cols=\"40\">".$content."</textarea>\n";
				echo "\t\t</td>\n";
			}
			echo "\t</tr>\n";
		}
		foreach ($this->_data as $table => $data) {
			echo "\t<tr><th>$table</th></tr>\n";
			echo "\t<tr>\n";
			foreach ($data as $type => $php) {
				echo "\t\t<td>\n";
				echo "\t\t\t<label for=\"$type$table\">$type</label><br />\n";
				echo "\t\t\t<textarea id=\"$type$table\" rows=\"10\" cols=\"40\">$php</textarea>\n";
				echo "\t\t</td>\n";
			}
			echo "\t</tr>\n";
		}
		echo "</table>";
	}
}
?>