<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

class SoftwareUpdateTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function AddAllRules() {
      global $DB;

      $DB->connect();

      // * Add rule ignore
         $rule = new Rule();
         $ruleCriteria = new RuleCriteria();
         $ruleAction = new RuleAction();

         $input = [];
         $input['sub_type']   = 'RuleDictionnarySoftware';
         $input['name']       = 'glpi';
         $input['match']      = 'AND';
         $input['is_active']  = 1;
         $rules_id = $rule->add($input);

         $input = [];
         $input['rules_id']   = $rules_id;
         $input['criteria']   = 'name';
         $input['condition']  = 0;
         $input['pattern']    = 'glpi';
         $ruleCriteria->add($input);

         $input = [];
         $input['rules_id']      = $rules_id;
         $input['action_type']   = 'assign';
         $input['field']         = '_ignore_import';
         $input['value']         = 1;
         $ruleAction->add($input);

      // * Add rule rename software
         $input = [];
         $input['sub_type']   = 'RuleDictionnarySoftware';
         $input['name']       = 'glpi0.85';
         $input['match']      = 'AND';
         $input['is_active']  = 1;
         $rules_id = $rule->add($input);

         $input = [];
         $input['rules_id']   = $rules_id;
         $input['criteria']   = 'name';
         $input['condition']  = 0;
         $input['pattern']    = 'glpi0.85';
         $ruleCriteria->add($input);

         $input = [];
         $input['rules_id']      = $rules_id;
         $input['action_type']   = 'assign';
         $input['field']         = 'name';
         $input['value']         = 'glpi';
         $ruleAction->add($input);

      // * Add rule rename manufacturer
         $input = [];
         $input['sub_type']   = 'RuleDictionnaryManufacturer';
         $input['name']       = 'indepnet';
         $input['match']      = 'AND';
         $input['is_active']  = 1;
         $rules_id = $rule->add($input);

         $input = [];
         $input['rules_id']   = $rules_id;
         $input['criteria']   = 'name';
         $input['condition']  = 0;
         $input['pattern']    = 'indepnet assoce';
         $ruleCriteria->add($input);

         $input = [];
         $input['rules_id']      = $rules_id;
         $input['action_type']   = 'assign';
         $input['field']         = 'name';
         $input['value']         = 'indepnet';
         $ruleAction->add($input);

      // * Add rule Modify version
         $input = [];
         $input['sub_type']   = 'RuleDictionnarySoftware';
         $input['name']       = 'glpi0.85';
         $input['match']      = 'AND';
         $input['is_active']  = 1;
         $rules_id = $rule->add($input);

         $input = [];
         $input['rules_id']   = $rules_id;
         $input['criteria']   = 'name';
         $input['condition']  = 0;
         $input['pattern']    = 'glpi0.85';
         $ruleCriteria->add($input);

         $input = [];
         $input['rules_id']      = $rules_id;
         $input['action_type']   = 'assign';
         $input['field']         = 'version';
         $input['value']         = '0.85';
         $ruleAction->add($input);
   }


   /**
    * @test
    */
   public function AddSoftwareNormal() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'fusioninventory team',
                'NAME'      => 'fusioninventory',
                'VERSION'   => '0.85+1.0',
                'SYSTEM_CATEGORY' => 'devel'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $a_reference = [];
      $a_reference['software']["fusioninventory$$$$0.85+1.0$$$$1$$$$0$$$$0"] = [
               'name'                  => 'fusioninventory',
               'manufacturers_id'      => 1,
               'version'               => '0.85+1.0',
               'is_template_computer'  => 0,
               'is_deleted_computer'   => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => 'devel'
            ];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function AddSoftwareIgnore() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 1;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'indepnet',
                'NAME'      => 'glpi',
                'VERSION'   => '0.85'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $a_reference = [];
      $a_reference['software'] = [];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function AddSoftwareRename() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'indepnet',
                'NAME'      => 'glpi0.85',
                'VERSION'   => '0.85',
                'SYSTEM_CATEGORY' => 'devel'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $a_reference = [];
      $a_reference['software']["glpi$$$$0.85$$$$2$$$$0$$$$0"] = [
               'name'                  => 'glpi',
               'manufacturers_id'      => 2,
               'version'               => '0.85',
               'is_template_computer'  => 0,
               'is_deleted_computer'   => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => 'devel'
            ];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function AddSoftwareRenameManufacturer() {
      global $DB;

      $DB->connect();

      $this->mark_incomplete();
      return;
      // TODO: recode this test (and verify all the tests in this file)

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'indepnet assoce',
                'NAME'      => 'glpi0.85',
                'VERSION'   => '0.85'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $a_reference = [];
      $a_reference['software']["glpi$$$$0.85$$$$2$$$$0$$$$0"] = [
               'name'                  => 'glpi',
               'manufacturers_id'      => 2,
               'version'               => '0.85',
               'is_template_computer'  => 0,
               'is_deleted_computer'   => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0
            ];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function AddSoftwareVersion() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'indepnet',
                'NAME'      => 'glpi0.85',
                'VERSION'   => '0.85',
                'SYSTEM_CATEGORY' => 'devel'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $a_reference = [];
      $a_reference['software']["glpi$$$$0.85$$$$2$$$$0$$$$0"] = [
               'name'                  => 'glpi',
               'manufacturers_id'      => '2',
               'version'               => '0.85',
               'is_template_computer'  => 0,
               'is_deleted_computer'   => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => 'devel'
            ];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function ProcessInstalldate() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'ARCH'             => 'i586',
                'FROM'             => 'registry',
                'GUID'             => 'Audacity_is1',
                'HELPLINK'         => 'http://audacity.sourceforge.net',
                'INSTALLDATE'      => '16/10/2013',
                'NAME'             => 'Audacity 2.0.4',
                'PUBLISHER'        => 'Audacity Team',
                'UNINSTALL_STRING' => '"C:\\Program Files\\Audacity\\unins000.exe\"',
                'URL_INFO_ABOUT'   => 'http://audacity.sourceforge.net',
                'VERSION'          => '2.0.4',
                'VERSION_MAJOR'    => '2',
                'VERSION_MINOR'    => '0',
                'SYSTEM_CATEGORY'  => 'application'
            ];
      $a_software['SOFTWARES'][] = [
                'ARCH'             => 'i586',
                'FROM'             => 'registry',
                'GUID'             => 'AutoItv3',
                'NAME'             => 'AutoIt v3.3.8.1',
                'PUBLISHER'        => 'AutoIt Team',
                'UNINSTALL_STRING' => 'C:\\Program Files\\AutoIt3\\Uninstall.exe',
                'URL_INFO_ABOUT'   => 'http://www.autoitscript.com/autoit3',
                'SYSTEM_CATEGORY'  => 'application'
          ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $a_reference = [];
      $a_reference['software']["audacity 2.0.4$$$$2.0.4$$$$3$$$$0$$$$0"] = [
               'name'                  => 'Audacity 2.0.4',
               'manufacturers_id'      => 3,
               'version'               => '2.0.4',
               'is_template_computer'  => 0,
               'is_deleted_computer'   => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               'date_install'          => '2013-10-16',
               '_system_category'      => 'application'
            ];
      $a_reference['software']["autoit v3.3.8.1$$$$$$$$4$$$$0$$$$0"] = [
               'name'                  => 'AutoIt v3.3.8.1',
               'manufacturers_id'      => 4,
               'version'               => '',
               'is_template_computer'  => 0,
               'is_deleted_computer'   => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => 'application'
      ];
      $this->assertEquals($a_reference, $a_return);

   }


}
