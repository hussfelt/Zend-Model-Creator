<?php
/**
 * Code generator for ServiceException
 *
 * @author Henrik Hussfelt
 * @since 2008-09-17
 *
 */

class StaticFilesCreatorService {

	/**
	 * Generates a GenericEntity class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createGenericEntity($settings) {
		$this->_generateClassHeader("GenericEntity");
		$this->_generateEnityContent();
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	 * Generates a GenericDAO class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createGenericDAO($settings) {
		$this->_generateClassHeader("GenericDAO");
		$this->_generateDAOContent();
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	 * Generates a GenericDTO class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createGenericDTO($settings) {
		$this->_generateClassHeader("GenericDTO");
		$this->_generateDTOContent();
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	 * Generates a GenericDateTime class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createGenericDateTime($settings) {
		$this->_generateClassHeader("GenericDateTime");
		$this->_generateGenericDateTimeContent();
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	 * Generates a DbFactory Class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createDbFactory($settings) {
		$this->_generateClassHeader("DbFactory");
		$this->_generateDbFactory();
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	 * Generates a SystemConfig Class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createSystemConfig($settings) {
		$this->_generateClassHeader("SystemConfig");
		$this->_generateSystemConfig($settings);
		$this->_generateClassFooter();
		return $this->_data;
	}

	private function _generateClassHeader($className) {
		$this->_data = "<?php
/**
 * $className
 *
 * @author ZendModelCreator ".ZendModelCreator::getVersion()."
 * @licence GNU/GPL V 2.0
 * @contact ".ZendModelCreator::getContact()."
 * @since " . date("Y-m-d") . "
 *
 */

";
	}

	/**
	 * Generates the Enity file content.
	 *
	 */
	private function _generateEnityContent() {
		$this->_data .= "
class GenericEntity extends Zend_Db_Table_Abstract {

    public function fetchAllByAdapter(\$sql, \$bind = array(), \$fetchMode = null) {
		\$result = \$this->getAdapter()->fetchAll(\$sql, \$bind, \$fetchMode);
		return \$result;
    }

";
	}

	/**
	 * Generates the DAO file content.
	 *
	 */
	private function _generateDAOContent() {
		$this->_data .= "
class GenericDAO {

	protected \$_dbConnection = null;

";
	}

	/**
	 * Generates the DTO file content.
	 *
	 */
	private function _generateDTOContent() {
		$this->_data .= "
class GenericDTO {

";
	}

	/**
	 * Generates the GenericDateTime file content.
	 *
	 */
	private function _generateGenericDateTimeContent() {
		$this->_data .= "
require_once 'Zend/Date.php';

class GenericDateTime {

	private \$time;
	/**
	 * Constructs a date
	 *
	 * @param datetime
	 *
	 **/
	public function __construct(\$date=null) {
		if(\$date == null) \$date = time();
		if(!is_int(\$date)) \$this->time = strtotime(\$date);
		else \$this->time = \$date;
	}

	/**
	 * Returns date and time formatted as YYYY-MM-dd HH:mm
	 *
	 * Example: 2008-03-09 09:30:50
	 * @return string date
	 *
	 */
	public function getFormattedDateTime() {
		//return \$this->toString('YYYY-MM-dd H:m:s');
		return date(\"Y-m-d H:i:s\", \$this->time);
	}

	/**
	 * Returns time formatted as hh:mm:ss
	 *
	 * Example: 09:30:50
	 * @return string time
	 *
	 */
	public function getFormattedTime() {
		//return \$this->toString('YYYY-MM-dd H:m:s');
		return date(\"H:i:s\", \$this->time);
	}

	/**
	 * Returns date and time formatted as YYYY-MM-dd H:mm
	 *
	 * Example: 2008-03-09 9:30
	 * @return string date
	 *
	 */
	public function getFormattedDay() {
		//return \$this->toString('YYYY-MM-dd H:m:s');
		return date(\"D\", \$this->time);
	}

	/**
	 * Returns a date formatted as YYYY-MM-dd
	 *
	 * Example: 2008-03-09
	 * @return string date
	 *
	 */
	public function getFormattedDate() {
		return date(\"Y-m-d\", \$this->time);
	}

	public function getTimestamp(){
		return \$this->time;
	}
";
	}

	private function _generateDbFactory() {
		$this->_data .= "
require_once 'Zend/Config.php';
require_once 'Zend/Db.php';
require_once 'Zend/Db/Adapter/Mysqli.php';

class DbFactory {

    private static \$_dbadapter = null;

	/**
     * Returns a studio db adapter.
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public static function getDefaultDbAdapter() {
        if (!isset(self::\$_dbadapter)) {
            \$parameters = array(
                'host'     => SystemConfig::\$db_host,
                'username' => SystemConfig::\$db_username,
                'password' => SystemConfig::\$db_password,
                'dbname'   => SystemConfig::\$db_database
            );
            self::\$_dbadapter = new Project_Db_Adapter_Mysql(\$parameters);
    	}
        return self::\$_dbadapter;
    }
}

/**
 * Wrapping PDO adapter, setting UTF8-encoding
 */
class Project_Db_Adapter_Mysql extends Zend_Db_Adapter_Mysqli {
	  protected function _connect() {
	    if (\$this->_connection)
	      return;
	    parent::_connect();
	    // Set correct encoding
	    \$this->query('SET NAMES utf8');
	  }
";
	}

	private function _generateSystemConfig($settings) {
		$this->_data .= "
class SystemConfig {
    public static \$db_host          = '".$settings['mysql_host']."';
    public static \$db_username      = '".$settings['mysql_user']."';
    public static \$db_password      = '".$settings['mysql_password']."';
    public static \$db_database      = '".$settings['mysql_db']."';
    public static \$db_prefix        = '';
";
	}

	private function _generateClassFooter() {
		$this->_data .= "
}
";
	}
}
?>