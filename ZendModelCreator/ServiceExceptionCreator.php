<?php
/**
 * Code generator for ServiceException
 * 
 * @author Henrik Hussfelt
 * @since 2008-09-17
 *
 */

class ServiceExceptionCreatorService {

	/**
	 * Generates a ServiceException class
	 *
	 * @return string formatted code ready to use
	 * 
	 */
	public function createServiceException($className) {
		$this->_generateClassHeader($className);
		$this->_generateContent($className);
		$this->_generateClassFooter();
		return $this->_data;
	}

	private function _generateClassHeader($className) {
		$className = strtolower($className);
		$this->_data .= "<?php
/**
 * $className"."ServiceException extends Exception
 * 
 * @author ZendModelCreator ".ZendModelCreator::getVersion()."
 * @licence GNU/GPL V 1.0
 * @contact ".ZendModelCreator::getContact()."
 * @since " . date("Y-m-d") . "
 *
 */

class ".ucfirst(strtolower($className))."ServiceException extends Exception {

	/** 
	 * Constructor
	 * @param string error message
	 * @param int error code
	 * @return void
	 * @access public
	 */
";
	}

	private function _generateContent($className) {
		$this->_data .= "\tpublic function $className"."ServiceException(\$errorMessage, \$errorCode) {\n";
		$this->_data .= "\t\tparent::__construct ( \$errorMessage, \$errorCode );\n";
		$this->_data .= "\t}\n\n";
	}

	private function _generateClassFooter() {
		$this->_data .= "
}
?>";
	}
}
?>