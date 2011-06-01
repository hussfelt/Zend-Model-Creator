<?php
/**
 * Code generator for Interface
 * 
 * @author Henrik Hussfelt
 * @since 2008-09-16
 *
 */

class InterfaceCreatorService {

	public $primary_key = '';

	/**
	 * Generates a Interface
	 *
	 * @return string formatted code ready to use
	 * 
	 */
	public function createInterface($className, $parameterArray) {
		$this->_generateClassHeader($className);
		$this->_generateContent($parameterArray);
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
 * $className Service API
 * 
 * @author ZendModelCreator ".ZendModelCreator::getVersion()."
 * @licence GNU/GPL V 1.0
 * @contact ".ZendModelCreator::getContact()."
 * @since " . date("Y-m-d") . "
 *
 */

interface I".ucfirst($className)."Service {

";
	}

	private function _generateContent($parameterArray) {
		foreach ($parameterArray as $func) {
			$this->_data .= $func;
		}
	}

	private function _generateClassFooter() {
		$this->_data .= "
}
?>";
	}
}
?>