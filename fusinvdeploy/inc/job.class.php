<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   @author    Walid Nouh
   @co-author
   @copyright Copyright (c) 2010-2012 FusionInventory team
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

/**
 * Class to parse agent's requests and build responses
 **/
class PluginFusinvdeployJob {

   static function get($device_id) {
      global $DB;

      $response      = array();
      $taskjoblog    = new PluginFusioninventoryTaskjoblog();
      $taskjobstatus = new PluginFusioninventoryTaskjobstatus();

      //Get the agent ID by his deviceid
      if ($agents_id = PluginFusinvdeployJob::getAgentByDeviceID($device_id)) {

         //Get tasks associated with the agent
         $task_list = $taskjobstatus->getTaskjobsAgent($agents_id);

         foreach ($task_list as $itemtype => $status_list) {

            //Foreach task for this agent build the response array
            foreach ($status_list as $status) {
               //verify whether task is active
               $sql = "SELECT is_active
                  FROM glpi_plugin_fusioninventory_tasks tasks
               LEFT JOIN glpi_plugin_fusioninventory_taskjobs jobs
                  ON jobs.plugin_fusioninventory_tasks_id = tasks.id
               WHERE jobs.id = '".$status['plugin_fusioninventory_taskjobs_id']."'
               AND is_active = '1'";
               $res = $DB->query($sql);
               if ($DB->numrows($res) == 0) break;

               switch ($itemtype) {
                  default:
                     $ordertype = -1;
                     break;

                  //Install a package
                  case 'PluginFusinvdeployDeployinstall':
                     $ordertype = PluginFusinvdeployOrder::INSTALLATION_ORDER;
                     break;

                  //Uninstall a package
                  case 'PluginFusinvdeployDeployuninstall':
                     $ordertype = PluginFusinvdeployOrder::UNINSTALLATION_ORDER;
                     break;
               }
               if ($ordertype != -1) {
                  $orderDetails = PluginFusinvdeployOrder::getOrderDetails($status, $ordertype);
                  if (count($orderDetails) == 0) return false;
                  $response[] = $orderDetails;
               }
            }
         }

      }
      return $response;
   }

   /**
    * Update agent status for a task
    * @param params parameters from the GET HTTP request
    * @return nothing
    */
   static function update($params = array(),$update_job = true) {
      $p['machineid']      = ''; //DeviceId
      $p['part']           = ''; //fragment downloaded
      $p['uuid']           = ''; //Task uuid
      $p['status']         = ''; //status of the task
      $p['currentStep']    = ''; //current step of processing
      $p['msg']            = ''; //Message to be logged
      foreach ($params as $key => $value) {
         $p[$key] = clean_cross_side_scripting_deep($value);
      }

      //Get the agent ID by his deviceid
      $agents_id = PluginFusinvdeployJob::getAgentByDeviceID($p['machineid']);
      if (!$agents_id) {
        die;
      }

     $jobstatus = new PluginFusioninventoryTaskjobstatus();
     $jobstatus->getFromDB($p['uuid']);

     /*if ($update_job) {
        $taskjob = new PluginFusioninventoryTaskjoblog();
        $taskjob->update($jobstatus);
     }*/
     $taskjoblog = new PluginFusioninventoryTaskjoblog();
     $tmp['plugin_fusioninventory_taskjobstatus_id'] = $jobstatus->fields['id'];
     $tmp['itemtype']                                = $jobstatus->fields['itemtype'];
     $tmp['items_id']                                = $jobstatus->fields['items_id'];
     $tmp['date']                                    = date("Y-m-d H:i:s");
     $tmp['comment']                                 = "";
     $tmp['state'] = PluginFusioninventoryTaskjoblog::TASK_RUNNING;

     $options = 0;
     if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
        $options = ENT_IGNORE;
     }

     // add log message
     if ($p['currentStep']) {
        $tmp['comment'] = htmlentities($p['currentStep'], $options, "UTF-8");
     } 

     if (is_array($p['msg'])) {
         if ($tmp['comment'] != "") {
            $tmp['comment'] .= ":<br>";
        }
        foreach ($p['msg'] as $line) {
            $tmp['comment'] .= htmlentities($line, $options, "UTF-8")."<br>";
        }
     } elseif ($p['msg'] != "") {
        if ($tmp['comment'] != "") {
            $tmp['comment'] .= ":<br>";
        }
        $tmp['comment'] .= htmlentities($p['msg'], $options, "UTF-8");
     }

     if ($p['status'] == 'ko') {
        $tmp['state'] = PluginFusioninventoryTaskjoblog::TASK_ERROR;
     }

     $taskjoblog->addTaskjoblog(
        $tmp['plugin_fusioninventory_taskjobstatus_id'],
        $tmp['items_id'],
        $tmp['itemtype'],
        $tmp['state'],
        $tmp['comment']
     );

     //change task to finish and replanned if retry available
     if ($p['status'] != "" && $p['currentStep'] == "" || $p['status'] == "ko") {
        $error = "0";
        if ($p['status'] == 'ko') $error = "1";
        //set status to finished and reinit job
        $taskjobstatus = new PluginFusioninventoryTaskjobstatus;
        $taskjobstatus->changeStatusFinish(
           $jobstatus->fields['id'],
           $jobstatus->fields['items_id'],
           $jobstatus->fields['itemtype'],
           $error
        );
     }
      self::sendOk();
   }

   /**
    * Get an agent ID by his deviceid
    * @param device_id the agent's device_id
    * @return the agent ID if agent found, or false
    */
   static function getAgentByDeviceID($device_id) {
      $result = getAllDatasFromTable('glpi_plugin_fusioninventory_agents',
                                     "`device_id`='$device_id'");
      if (!empty($result)) {
         $agent = array_pop($result);
         return $agent['id'];
      } else {
         return false;
      }
   }

   static function sendOk() {
      header("HTTP/1.1 200",true,200);
   }
}

?>
