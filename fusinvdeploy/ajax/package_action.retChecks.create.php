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
   @author    Alexandre Delaunay
   @co-author
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");
Session::checkLoginUser();

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

$render = $_GET['render'];
$commandIdName = $render."CommandId";

if(isset($HTTP_RAW_POST_DATA)){
   $retcheck = get_object_vars(json_decode($HTTP_RAW_POST_DATA));
   $retcheck = $retcheck[$render.'retChecks'];

   $commandId = $retcheck->$commandIdName;
   $type = $retcheck->type;
   $value = $retcheck->value;
} else {
   exit;
}

$commandstatus = new PluginFusinvdeployAction_Commandstatus();

$data = array( 'type'   => $type,
               'value'  => $value,
               'plugin_fusinvdeploy_commands_id'     => $commandId);

$newId = $commandstatus->add($data);

$sql = "SELECT plugin_fusinvdeploy_commands_id as $commandIdName, id, type, value
         FROM `glpi_plugin_fusinvdeploy_actions_commandstatus`
         WHERE id = $newId";
$qry  = $DB->query($sql);

$res  = array();
while($row = $DB->fetch_array($qry)) {
   $res[$render.'retChecks'][] = $row;
}
echo "{success:true, ".substr(json_encode($res),1, -1)."}";

?>
