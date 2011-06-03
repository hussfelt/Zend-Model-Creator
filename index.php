<?php
/**
* @version		1.5.2 index.php hussfelt
* @package		ZendModelCreator
* @copyright	Copyright (C) 2005 - 2011 Hussfelt Consulting AB. All rights reserved.
* @license		GNU/GPL V 1.0
* This is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*
*
*	WHAT?
* 	This file is manly used to avoid the time spent on creating models for
* 	ZendFramework projects. It is soly based on Jersey Consultings model type
* 	as it fit my needs.
*
* 	HOW?
* 	Simply put this file in any given webfolder, configure your parameters
* 	and access the page from the web.
*
* 	RESULT:
* 	For each table you have in your specified database, a row of <textarea>
* 	inputs will be created with the code you need to paste into the files!
* 	Or you can tell Zend Model Creator to create all the files and folders for you
* 	thus saving you the process of doing that as well. Just move the files into
* 	your "models" folder.
* 
* 	For more information check out:
* 	http://hussfelt.net/labs/zend-model-creator
*
* 	FUTURE!
* 	We may add:
* 		* Add "Clean My Appartment" feature
* 		* Add comments for PHPDoc on all generated functions
*
*	CHANGELOG:
*
*	  	20110601 - Version 1.5.3
*  			- Changed behaviour to support tables with letter s in the end, thanks to Rafael Beckel!
*  			- Added function toJSON in DTO
*  
* 		20091206 - Version 1.5.2
* 			- Migrated fetchAll function in DAOCreatorService to use a slightly different fecth method.
* 			  This will make it much easier to join other tables to the SQL statement and then build these
* 			  in the foreach to the object.
* 			- Added support for Double thanks to the patch from Thomas Good.
*
* 		20090112 - Version 1.5.1
* 			- Fixed the Diff function in the DTO, which was completly unusful until now.
* 			  It now also tells what fields was changed, this information will be found in the
* 			  array_keys of the array returned.
*
* 		20090112 - Version 1.5
* 			- Added Create config, DB config located in /db/
* 			- Added Create Default DB Adapterlocated in /db/
*
*  		20081229 - Version 1.4
* 			- Error in DAO and Service compability, where service called function create while it
* 			  was called insert in DAO. Now called create in both files.
* 			- Bug where DAO insert and update function had no '' around array keys, this resulted
* 			  in 'Undefined constant' errors. Fixed.
* 			- DTO Bind function will no longer complain about "Undefined index", as there now is
* 			  an isset() if statement for each array_key before the setter.
* 			- Added getFormattedTime() to GenericDateTime class.
* 			- Diff function bug, did not use same variable for comparison.
*
*  		20081222 - Version 1.3
* 			- A lot of bugfixes, script is almost running as intended now.
* 			- Moved ErrorConstants.php into a new directory called constants.
* 			- GenericDateTime bug in DAOCreatorService where we forgot to make
* 			  dates from db an instance of GenericDateTime class.
*
*  		20080919 - Version 1.2
* 			- Added Static files generator for all Generic classes
* 			- Fixed some minor bugs
* 			- Made the 'require_once' php statements in the generated files
* 			  dynamic, and based on what you specify in your setDirectoryStructure setting
*
* 		20080917 - Version 1.1
* 			- Fixed ExceptionService
* 			- Removed whitespaces in the beginning of generated files
* 			- Errorconstants had no end semicolon on each row
*
* 		20080917 - Version 1.0
* 			- Added ExceptionService
* 			- Added write to files functionallity based on the configuration
* 			  sent to $ZendModelCreator->setDirectoryStructure()
* 			- Changed some basic comments
*
* 		20080916 - Version 0.9
* 			- Added all basic functionallity, except exceptionservice
* 			  and the possibility of writing to files
*
*/
// Includes
require_once 'ZendModelCreator/ZendModelCreator.php';

/**
 * Settings
 */
// Create types
$SETTINGS['types']['create_dto'] = true;
$SETTINGS['types']['create_dao'] = true;
$SETTINGS['types']['create_entity'] = true;
$SETTINGS['types']['create_service'] = true;
$SETTINGS['types']['create_interface'] = true;
$SETTINGS['types']['create_errorconstants'] = true;
$SETTINGS['types']['create_exception'] = true;
$SETTINGS['types']['create_genericfiles'] = true;

// MySQL settings
$SETTINGS['mysql_host'] = "localhost";
$SETTINGS['mysql_user'] = "root";
$SETTINGS['mysql_password'] = "";
$SETTINGS['mysql_db'] = "";

// Setup the model creator service with our specified settings
$ZendModelCreator = new ZendModelCreator($SETTINGS);
// Get the generated PHP code from our services
$ZendModelCreator->getDataFromServices();

/**
 * Setup the directory structure you want to use,
 * these settings also affect the "require_once" statements
 * throughout the code, therefore you need to specify these
 * settings, even if you are not using the function
 * $ZendModelCreator->writePHPCreatedModelData();
 */
$ZendModelCreator->setDirectoryStructure(
	array(
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
	)
);


/**
 * Either runt getPHPCreatedModelData to get the
 * data for each file outputted in an HTML <textarea> element.
 */
//echo $ZendModelCreator->getPHPCreatedModelData();

/**
 * Or run writePHPCreatedModelData to write all php-files
 * to the specified directorys set with setDirectoryStructure.
 */
$ZendModelCreator->writePHPCreatedModelData();

/**
 * Echo out EOF so that we know the script run all the way through.
 */
echo "EOF.";
?>