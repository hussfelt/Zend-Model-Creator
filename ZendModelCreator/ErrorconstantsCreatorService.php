<?php
/**
 * Code generator for Interface
 * 
 * @author Henrik Hussfelt
 * @since 2008-09-16
 *
 */

class ErrorconstantsCreatorService {

	/**
	 * Generates Errorconstants
	 *
	 * @return string formatted code ready to use
	 * 
	 */
	public function createErrorconstants($parameterArray) {
		$this->_generateClassHeader();
		$this->_generateContent($parameterArray);
		$this->_generateClassFooter();
		return $this->_data;
	}

	private function _generateClassHeader() {
		$this->_data .= "<?php
/**
 * ErrorContants used in the application
 * 
 * @author ZendModelCreator ".ZendModelCreator::getVersion()."
 * @licence GNU/GPL V 1.0
 * @contact ".ZendModelCreator::getContact()."
 * @since " . date("Y-m-d") . "
 *
 */

class ErrorConstants {
";
	}

	private function _generateContent($parameterArray) {
		$x = 100;
		foreach ($parameterArray as $func) {
			$this->_data .= "\tconst $func = $x;\n";
			$x += 1;
		}
	}

	private function _generateClassFooter() {
		$this->_data .= "
}
?>";
	}
}
?>