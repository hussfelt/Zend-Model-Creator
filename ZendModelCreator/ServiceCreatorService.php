<?php
/**
 * Code generator for Services
 *
 * @author Henrik Hussfelt
 * @since 2008-09-16
 *
 */

class ServiceCreatorService {

	public $primary_key = '';

	/**
	 * Generates a Service class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createService($className, $parameterArray) {
		$this->_generateClassHeader($className);
		$this->_setPrimaryKey($parameterArray['primary_key']);
		$this->_generateRetrieve($className);
		$this->_generateRetrieves($className);
		$this->_generateCreate($className);
		$this->_generateUpdate($className);
		$this->_generateDelete($className);
		$this->_generateClassFooter();
		return $this->_data;
	}

	private function _setPrimaryKey($primary_key) {
		$this->primary_key = $primary_key;
	}

	private function _generateClassHeader($className) {
		$className = strtolower($className);
		$folderName = strtolower($className);
		$this->_data .= "<?php
/**
* Implementation of $className Service
*
* @author ZendModelCreator ".ZendModelCreator::getVersion()."
* @licence GNU/GPL V 2.0
* @contact ".ZendModelCreator::getContact()."
* @since " . date("Y-m-d") . "
*
**/

require_once('$folderName/".ZendModelCreator::$directoryStructure['DirectoryStructure']['DTO']."/".str_replace("[tbl]",ucfirst(strtolower($className)),ZendModelCreator::$directoryStructure['FileNames']['INT'])."');
require_once('$folderName/".ZendModelCreator::$directoryStructure['DirectoryStructure']['DAO']."/".str_replace("[tbl]",ucfirst(strtolower($className)),ZendModelCreator::$directoryStructure['FileNames']['DAO'])."');
require_once('$folderName/".ZendModelCreator::$directoryStructure['DirectoryStructure']['EXC']."/".str_replace("[tbl]",ucfirst(strtolower($className)),ZendModelCreator::$directoryStructure['FileNames']['EXC'])."');
require_once('".ZendModelCreator::$directoryStructure['DirectoryStructure']['CON']."/ErrorConstants.php');

class ".substr(str_replace('[tbl]',ucfirst($className),ZendModelCreator::$directoryStructure['FileNames']['SRV']),0,-4)." implements ".substr(str_replace('[tbl]',ucfirst($className),ZendModelCreator::$directoryStructure['FileNames']['INT']),0,-4)."
{

";
	}

	private function _generateRetrieve($className) {
		$className = ucfirst(strtolower($className));
		// add to interface queue
		ZendModelCreator::$interface[strtolower($className)][] = "\tpublic static function retrieve$className(\$$this->primary_key);\n";
		ZendModelCreator::$errorconstants["ErrorConstants::RETRIEVE_".strtoupper($className)."_ERROR"] = "RETRIEVE_".strtoupper($className)."_ERROR";
		$this->_data.="\t/**\n";
		$this->_data.="\t* Retrieve a row as object from the database\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param \$$this->primary_key\n";
		$this->_data.="\t* @return object ".$className."DTO\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic static function retrieve$className(\$$this->primary_key)\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\ttry {\n";
		$this->_data.="\t\t\treturn $className"."DAO::fetch(\$$this->primary_key);\n";
		$this->_data.="\t\t} catch (Exception \$e) {\n";
		$this->_data.="\t\t\tthrow new $className"."ServiceException(\$e->getMessage(), ErrorConstants::RETRIEVE_".strtoupper($className)."_ERROR);\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\t\treturn null;\n";
		$this->_data.="\t}\n\n";
	}

	private function _generateRetrieves($className) {
		$className = ucfirst(strtolower($className));
		// add to interface queue
		ZendModelCreator::$interface[strtolower($className)][] = "\tpublic static function retrieve$className"."s(\$where=null, \$order=null, \$count=null, \$offset=null);\n";
		ZendModelCreator::$errorconstants["ErrorConstants::RETRIEVE_".strtoupper($className)."_ERROR"] = "RETRIEVE_".strtoupper($className)."_ERROR";
		$this->_data.="\t/**\n";
		$this->_data.="\t* Retrieve a set of rows as objects from the database\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param \$where=null, \$order=null, \$count=null, \$offset=null\n";
		$this->_data.="\t* @return array ".$className."DTOs\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic static function retrieve$className"."s( \$where=null, \$order=null, \$count=null, \$offset=null )\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\ttry {\n";
		$this->_data.="\t\t\treturn $className"."DAO::fetchAll( \$where, \$order, \$count, \$offset );\n";
		$this->_data.="\t\t} catch (Exception \$e) {\n";
		$this->_data.="\t\t\tthrow new $className"."ServiceException(\$e->getMessage(), ErrorConstants::RETRIEVE_".strtoupper($className)."_ERROR);\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\t\treturn null;\n";
		$this->_data.="\t}\n\n";
	}

	private function _generateCreate($className) {
		$className = ucfirst(strtolower($className));
		// add to interface queue
		ZendModelCreator::$interface[strtolower($className)][] = "\tpublic static function create$className($className $".strtolower($className).");\n";
		ZendModelCreator::$errorconstants["ErrorConstants::CREATE_".strtoupper($className)."_ERROR"] = "CREATE_".strtoupper($className)."_ERROR";
		$this->_data.="\t/**\n";
		$this->_data.="\t* Create a row in the database from a ".$className."DTO\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param object $className $".strtolower($className)."\n";
		$this->_data.="\t* @return bool\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic static function create$className( $className $".strtolower($className)." )\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\ttry {\n";
		$this->_data.="\t\t\treturn $className"."DAO::create( $".strtolower($className)." );\n";
		$this->_data.="\t\t} catch (Exception \$e) {\n";
		$this->_data.="\t\t\tthrow new $className"."ServiceException(\$e->getMessage(), ErrorConstants::CREATE_".strtoupper($className)."_ERROR);\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\t\treturn null;\n";
		$this->_data.="\t}\n\n";
	}

	private function _generateUpdate($className) {
		$className = ucfirst(strtolower($className));
		// add to interface queue
		ZendModelCreator::$interface[strtolower($className)][] = "\tpublic static function update$className($className $".strtolower($className).");\n";
		ZendModelCreator::$errorconstants["ErrorConstants::UPDATE_".strtoupper($className)."_ERROR"] = "UPDATE_".strtoupper($className)."_ERROR";
		$this->_data.="\t/**\n";
		$this->_data.="\t* Update a row in the database from a ".$className."DTO\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param object $className $".strtolower($className)."\n";
		$this->_data.="\t* @return bool\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic static function update$className( $className $".strtolower($className)." )\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\ttry {\n";
		$this->_data.="\t\t\treturn $className"."DAO::update( $".strtolower($className)." );\n";
		$this->_data.="\t\t} catch (Exception \$e) {\n";
		$this->_data.="\t\t\tthrow new $className"."ServiceException(\$e->getMessage(), ErrorConstants::UPDATE_".strtoupper($className)."_ERROR);\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\t\treturn null;\n";
		$this->_data.="\t}\n\n";
	}

	private function _generateDelete($className) {
		$className = ucfirst(strtolower($className));
		// add to interface queue
		ZendModelCreator::$interface[strtolower($className)][] = "\tpublic static function delete$className($".$this->primary_key.");\n";
		ZendModelCreator::$errorconstants["ErrorConstants::DELETE_".strtoupper($className)."_ERROR"] = "DELETE_".strtoupper($className)."_ERROR";
		$this->_data.="\t/**\n";
		$this->_data.="\t* Delete a row in the database by primary key\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param int $".$this->primary_key . "\n";
		$this->_data.="\t* @return bool\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic static function delete$className( $".$this->primary_key." )\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\ttry {\n";
		$this->_data.="\t\t\treturn $className"."DAO::delete( $".$this->primary_key." );\n";
		$this->_data.="\t\t} catch (Exception \$e) {\n";
		$this->_data.="\t\t\tthrow new $className"."ServiceException(\$e->getMessage(), ErrorConstants::DELETE_".strtoupper($className)."_ERROR);\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\t\treturn null;\n";
		$this->_data.="\t}\n\n";
	}

	private function _generateClassFooter() {
		$this->_data .= "
}
";
	}
}
?>
