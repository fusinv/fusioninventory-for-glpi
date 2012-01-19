<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author Alexandre Delaunay
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployPackage extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][5];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }

   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      if ($this->fields['id'] > 0){
         $this->addStandardTab('PluginFusinvdeployInstall', $ong, $options);
         $this->addStandardTab('PluginFusinvdeployUninstall', $ong, $options);
      }
      $ong['no_all_tab'] = true;
      return $ong;
   }

   function showList() {
      self::title();
      Search::show('PluginFusinvdeployPackage');
   }

   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common']           = $LANG['common'][32];;

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['linkfield']     = 'name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_link'] = $this->getType();

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'id';
      $tab[2]['linkfield'] = '';
      $tab[2]['name']      = $LANG['common'][2];

      $tab[16]['table']     = $this->getTable();
      $tab[16]['field']     = 'comment';
      $tab[16]['linkfield'] = 'comment';
      $tab[16]['name']      = $LANG['common'][25];
      $tab[16]['datatype']  = 'text';

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = $LANG['common'][26];
      $tab[19]['datatype']  = 'datetime';

      $tab[80]['table']     = 'glpi_entities';
      $tab[80]['field']     = 'completename';
      $tab[80]['linkfield'] = 'entities_id';
      $tab[80]['name']      = $LANG['entity'][0];

      $tab[86]['table']     = $this->getTable();
      $tab[86]['field']     = 'is_recursive';
      $tab[86]['linkfield'] = 'is_recursive';
      $tab[86]['name']      = $LANG['entity'][9];
      $tab[86]['datatype']  = 'bool';

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = $LANG['common'][26];
      $tab[19]['datatype']  = 'datetime';

      return $tab;
   }

   function post_addItem() {
      //check whether orders have not already been created
      if (!isset($_SESSION['tmp_clone_package'])) {
         //Create installation & uninstallation order
         PluginFusinvdeployOrder::createOrders($this->fields['id']);
      }
   }

   function cleanDBonPurge() {
      PluginFusinvdeployOrder::cleanForPackage($this->fields['id']);
   }

   function title() {
      global $LANG;

      $buttons = array();
      $title = $LANG['plugin_fusinvdeploy']['package'][5];

      if ($this->canCreate()) {
         $buttons["package.form.php?new=1"] = $LANG['plugin_fusinvdeploy']['package'][26];
         $title = "";
      }

      Html::displayTitle(GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_mini_package.png", $title, $title, $buttons);
   }


   function showForm($ID, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;


      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         $this->check(-1,'w');
         $this->getEmpty();
      }

      $options['colspan'] = 2;
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      echo "<td>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<textarea cols='40' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();
      </script>";

      //load extjs plugins library
      echo "<link rel='stylesheet' type='text/css' href='".GLPI_ROOT.
            "/plugins/fusinvdeploy/lib/extjs/FileChooser/css/styles.css'>";

      echo "<script type='text/javascript'>";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/FileUploadField.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/Spinner.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/SpinnerField.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/GridDragDropRowOrder.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/FileChooser/FileChooser.js";
      echo "</script>";

      return true;
   }

   function getAllDatas()  {
      global $DB;

      $sql = " SELECT id, name
               FROM `".$this->getTable()."`
               ORDER BY name";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
      $i = 0;
      while($row = $DB->fetch_assoc($res)) {
         $json['packages'][$i]['package_id'] = $row['id'];
         $json['packages'][$i]['package_name'] = $row['name'];

         $i++;
      }
      $json['results'] = $nb;

      return json_encode($json);
   }


   static function canEdit($id) {
      global $DB;

      $taskjobs_a = getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobs',
               "definition LIKE '%\"PluginFusinvdeployPackage\":\"".$id."%'");

      foreach ($taskjobs_a as $job) {
         $task = new PluginFusioninventoryTask;
         $task->getFromDB($job['plugin_fusioninventory_tasks_id']);
         if ($task->getField('is_active') == 1) return false;
      }
      return true;
   }

   function pre_deleteItem() {
      global $LANG, $CFG_GLPI;

      //if task use this package, delete denied
      if (!self::canEdit($this->getField('id'))) {
         $task = new PluginFusinvdeployTask;
         $tasks_url = "";
         $taskjobs = getAllDatasFromTable('glpi_plugin_fusinvdeploy_taskjobs',
                  "definition LIKE '%\"PluginFusinvdeployPackage\":\"".$this->getField('id')."%'");
         foreach($taskjobs as $job) {
            $task->getFromDB($job['plugin_fusinvdeploy_tasks_id']);
            $tasks_url .= "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/task.form.php?id="
                  .$job['plugin_fusinvdeploy_tasks_id']."'>".$task->fields['name']."</a>, ";
         }
         $tasks_url = substr($tasks_url, 0, -2);


         addMessageAfterRedirect(str_replace('#task#',
               $tasks_url, $LANG['plugin_fusinvdeploy']['package'][23]));
         Html::redirect(GLPI_ROOT."/plugins/fusinvdeploy/front/package.form.php?id="
               .$this->getField('id'));
         return false;
      }

      return true;
   }

   public function package_clone($new_name = '') {
      global $LANG;

      if ($this->getField('id') < 0) return false;

      $_SESSION['tmp_clone_package'] = true;

      //duplicate package
      $package_oldId = $this->getField('id');
      if ($new_name == "") $new_name = $this->getField('name');
      $params = $this->fields;
      unset($params['id']);
      $params['name'] = $new_name;
      $new_package = new PluginFusinvdeployPackage;
      $package_newId = $new_package->add($params);

      //duplicate orders
      $order_obj = new PluginFusinvdeployOrder;
      $orders = $order_obj->find("plugin_fusinvdeploy_packages_id = '".$package_oldId."'");

      foreach($orders as $order_oldId => $order) {
         //create new order for this new package
         $order_param = array(
            'type' => $order['type'],
            'create_date' => date("Y-m-d H:i:s"),
            'plugin_fusinvdeploy_packages_id' => $package_newId
         );
         $order_newId = $order_obj->add($order_param);
         unset($order_param);


         //duplicate checks
         $check_obj = new PluginFusinvdeployCheck;
         $checks = $check_obj->find("plugin_fusinvdeploy_orders_id = '".$order_oldId."'");
         foreach ($checks as $check_oldId => $check) {
            //create new check for this new order
            unset($check['id']);
            $check['plugin_fusinvdeploy_orders_id'] = $order_newId;
            $check_newId = $check_obj->add($check);
         }

         //duplicate files
         $file_obj = new PluginFusinvdeployFile;
         $files = $file_obj->find("plugin_fusinvdeploy_orders_id = '".$order_oldId."'");
         foreach ($files as $file_oldId => $file) {
            //create new file for this new order
            unset($file['id']);
            $file['plugin_fusinvdeploy_orders_id'] = $order_newId;
            $file_newId = $file_obj->add($file);

            //duplicate fileparts
            $filepart_obj = new PluginFusinvdeployFilepart;
            $fileparts = $filepart_obj->find("plugin_fusinvdeploy_files_id = '".$order_oldId."'");
            foreach ($fileparts as $filepart_oldId => $filepart) {
               //create new filepart for this new file
               unset($filepart['id']);
               $filepart['plugin_fusinvdeploy_orders_id'] = $order_newId;
               $filepart['plugin_fusinvdeploy_files_id'] = $file_newId;
               $filepart_newId = $filepart_obj->add($filepart);
            }
         }

         //duplicate actions
         $action_obj = new PluginFusinvdeployAction;
         $actions = $action_obj->find("plugin_fusinvdeploy_orders_id = '".$order_oldId."'");
         foreach ($actions as $action_oldId => $action) {
            //duplicate actions subitem
            $action_subitem_obj = new $action['itemtype'];
            $action_subitem_oldId = $action['items_id'];
            $action_subitem_obj->getFromDB($action_subitem_oldId);
            $params_subitem = $action_subitem_obj->fields;
            unset($params_subitem['id']);
            $action_subitem_newId = $action_subitem_obj->add($params_subitem);

            //special case for command, we need to duplicate commandstatus and commandenvvariables
            if ($action['itemtype'] == 'PluginFusinvdeployAction_Command') {
               $command_oldId = $action_subitem_oldId;
               $command_newId = $action_subitem_newId;

               //duplicate commandstatus
               $commandstatus_obj = new PluginFusinvdeployAction_Commandstatus;
               $commandstatus = $commandstatus_obj->find("plugin_fusinvdeploy_commands_id = '".$command_oldId."'");
               foreach ($commandstatus as $commandstatus_oldId => $commandstate) {
                  //create new commandstatus for this command
                  unset($commandstate['id']);
                  $commandstate['plugin_fusinvdeploy_commands_id'] = $command_newId;
                  $commandstatus_newId = $commandstatus_obj->add($commandstate);
               }

               //duplicate commandenvvariables
               $commandenvvariables_obj = new PluginFusinvdeployAction_Commandenvvariable;
               $commandenvvariables = $commandenvvariables_obj->find("plugin_fusinvdeploy_commands_id = '".$command_oldId."'");
               foreach ($commandenvvariables as $commandenvvariable_oldId => $commandenvvariable) {
                  //create new commandenvvariable for this command
                  unset($commandenvvariable['id']);
                  $commandenvvariable['plugin_fusinvdeploy_commands_id'] = $command_newId;
                  $commandenvvariable_newId = $commandenvvariables_obj->add($commandenvvariable);
               }
            }

            //create new action for this new order
            unset($action['id']);
            $action['plugin_fusinvdeploy_orders_id'] = $order_newId;
            $action['items_id'] = $action_subitem_newId;
            $action_newId = $action_obj->add($action);
         }
      }

      if (($name=$new_package->getName()) == NOT_AVAILABLE) {
         $new_package->fields['name'] = $new_package->getTypeName()." : ".$LANG['common'][2]
                                 ." ".$new_package->fields['id'];
      }
      $display = (isset($this->input['_no_message_link'])?$new_package->getNameID()
                                                         :$new_package->getLink());

      // Do not display quotes
      Session::addMessageAfterRedirect($LANG['common'][70]."&nbsp;: ".stripslashes($display));

      unset($_SESSION['tmp_clone_package']);

      //exit;

   }

   public static function showEditDeniedMessage($id, $message) {
      global $CFG_GLPI, $CFG_GLPI;

      $task = new PluginFusinvdeployTask;
      $tasks_url = "";

      $taskjobs = getAllDatasFromTable('glpi_plugin_fusinvdeploy_taskjobs',
               "definition LIKE '%\"PluginFusinvdeployPackage\":\"".$id."%'");

      # A task can have more than one taskjobs is an Install and Uninstall function are associated
      # to the same tasks
      $jobs_seen = array();
      foreach($taskjobs as $job) {
         if (isset($jobs_seen[$job['plugin_fusinvdeploy_tasks_id']])) {
            continue;
         }
         $task->getFromDB($job['plugin_fusinvdeploy_tasks_id']);
         $tasks_url .= "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/task.form.php?id="
               .$job['plugin_fusinvdeploy_tasks_id']."'>".$task->fields['name']."</a>, ";
         $jobs_seen[$job['plugin_fusinvdeploy_tasks_id']]=1;
      }
      $tasks_url = substr($tasks_url, 0, -2);

      echo "<div class='box' style='margin-bottom:20px;'>";
      echo "<div class='box-tleft'><div class='box-tright'><div class='box-tcenter'>";
      echo "</div></div></div>";
      echo "<div class='box-mleft'><div class='box-mright'><div class='box-mcenter'>";
      echo str_replace('#task#', $tasks_url, $message);
      echo "</div></div></div>";
      echo "<div class='box-bleft'><div class='box-bright'><div class='box-bcenter'>";
      echo "</div></div></div>";
      echo "</div>";
   }
}

?>
