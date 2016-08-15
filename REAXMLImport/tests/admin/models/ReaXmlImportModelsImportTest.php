<?php
/**
 *
 * @version 0.0.1: ReaXmlImportModelsImportTest.php 13/08/2016T01:55
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/

include_once __DIR__ . '/../../../admin/models/import.php';
include_once __DIR__ . '/../../dbtestcase.php';
use Joomla\Registry\Registry;

class ReaXmlImportModelsImportTest extends \Reaxml_Tests_DatabaseTestCase
{

    /**
     * @since 3.6
     * @before
     */
    public function setUp() {
        parent::setUp ();
    }
    /**
     * @since 3.6
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        return $this->createMySQLXMLDataSet ( __DIR__ . '/../../files/admin-helper-seed.xml' );
    }

    /**
     *
     *
     * @since version 3.6
     * @test
     */

    public function copies_all_extension_parameters_int_configuration_object(){

        //$mockState = $this->getMockBuilder(Registry::class);

        $model = new ReaXmlImportModelsImport();

        $configuration = $model->getConfiguration();

        $this->assertThat($configuration->default_country, $this->equalTo('Australia'));

    }

}
