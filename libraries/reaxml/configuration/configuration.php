<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: configuration.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 

class ReaxmlConfiguration {
	
	public $log_dir;
	public $log_url;
	public $input_dir;
	public $input_url;
	public $work_dir;
	public $work_url;
	public $done_dir;
	public $done_url;
	public $error_dir;
	public $error_url;
	public $usemap = 0;
    public $default_country;

    public function __get($name){
        $params = JComponentHelper::getParams('com_reaxmlimport');
        return $params->get($name);
    }

    public function get($name,$default=null){
        $params = JComponentHelper::getParams('com_reaxmlimport');
        return $params->get($name,$default);
    }

}