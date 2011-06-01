<?php
/**
 * Code generator for Entities
 * 
 * @author Henrik Hussfelt
 * @since 2008-09-16
 *
 */

class EntityCreatorService {

	public $primary_key = '';

	/**
	 * Generates a Entity class
	 *
	 * @return string formatted code ready to use
	 * 
	 */
	public function createEntity($className, $parameterArray) {
		$this->_generateClassHeader($className);
		$this->_setPrimaryKey($parameterArray['primary_key']);
		$this->_generateProtected($className);
		$this->_generateClassFooter();
		return $this->_data;
	}

	private function _setPrimaryKey($primary_key) {
		$this->primary_key = $primary_key;
	}

	private function _generateClassHeader($className) {
		$className = strtolower($className);
		$this->_data .= "<?php
/**
 * Entity mapping class for $className
 * 
 * @author ZendModelCreator ".ZendModelCreator::getVersion()."
 * @licence GNU/GPL V 1.0
 * @contact ".ZendModelCreator::getContact()."
 * @since " . date("Y-m-d") . "
 *
 */

require_once('db/GenericEntity.php');

class ".ucfirst(strtolower($className))."Entity extends GenericEntity {

";
	}

	private function _generateProtected($className) {
		$this->_data .= "\tprotected \$_name\t= '".$className."';\n";
		$this->_data .= "\tprotected \$_primary\t= '".$this->primary_key."';\n";
	}

	private function _generateClassFooter() {
		$this->_data .= "
}
?>";
	}
}
?>