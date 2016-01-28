<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2014 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (in_array('--help', $_SERVER['argv'])) {
   die("usage: ".$_SERVER['argv'][0]." [ --optimize ]\n");
}

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

include ("../../../inc/includes.php");

// Init debug variable
$_SESSION['glpi_use_mode'] = Session::DEBUG_MODE;
$_SESSION['glpilanguage']  = "en_GB";

if (isset($_SERVER["argv"][1])
        && is_numeric($_SERVER["argv"][1])) {
   $_SESSION['glpiactiveprofile']['id'] = $_SERVER["argv"][1];
   Session::changeProfile($_SERVER["argv"][1]);
}

Session::LoadLanguage();

// Only show errors
$CFG_GLPI["debug_sql"]        = $CFG_GLPI["debug_vars"] = 0;
$CFG_GLPI["use_log_in_files"] = 1;
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
//set_error_handler('userErrorHandlerDebug');

$DB = new DB();
if (!$DB->connected) {
   die("No DB connection\n");
}


/*---------------------------------------------------------------------*/

if (!TableExists("glpi_configs")) {
   die("GLPI not installed\n");
}

$plugin = new Plugin();


require_once (GLPI_ROOT . "/plugins/fusioninventory/install/climigration.class.php");
include (GLPI_ROOT . "/plugins/fusioninventory/install/update.php");
$current_version = pluginFusioninventoryGetCurrentVersion();

$migration = new CliMigration($current_version);

   if (!isset($current_version)) {
      $current_version = 0;
   }
   if ($current_version == '0') {
      $migration->displayWarning("***** Install process of plugin FUSIONINVENTORY *****");
   } else {
      $migration->displayWarning("***** Update process of plugin FUSIONINVENTORY *****");
   }

   $migration->displayWarning("Current FusionInventory version: $current_version");
   $migration->displayWarning("Version to update: ".PLUGIN_FUSIONINVENTORY_VERSION);

   // To prevent problem of execution time
   ini_set("max_execution_time", "0");
   ini_set("memory_limit", "-1");
   ini_set("session.use_cookies","0");
   $mess = '';
   if (($current_version != PLUGIN_FUSIONINVENTORY_VERSION)
        AND $current_version!='0') {
      $mess = "Update needed.";
   } else if ($current_version == PLUGIN_FUSIONINVENTORY_VERSION) {
      $mess = "No migration needed.";
   } else {
      $mess = "installation done.";
   }

   $migration->displayWarning($mess);

   $options = getopt(
      "",
      array(
         "no-models-update",
         "as-user:"
      )
   );

   if (array_key_exists('no-models-update', $options)) {
      define('NO_MODELS_UPDATE', TRUE);
   }

   if (array_key_exists('as-user', $options)) {
      $user = new User();
      $user->getFromDBbyName($options['as-user']);
      $auth = new Auth();
      $auth->auth_succeded = true;
      $auth->user = $user;
      Session::init($auth);
   }
   $plugin->getFromDBbyDir("fusioninventory");
   print("Installing Plugin...\n");
   $plugin->install($plugin->fields['id']);
   print("Install Done\n");
   print("Activating Plugin...\n");
   $plugin->activate($plugin->fields['id']);
   print("Activation Done\n");
   print("Loading Plugin...\n");
   $plugin->load("fusioninventory");
   print("Load Done...\n");


if (in_array('--optimize', $_SERVER['argv'])) {

   $migration->displayTitle(__('Optimizing tables'));

   DBmysql::optimize_tables($migration);

   $migration->displayWarning("Optimize done.");
}
