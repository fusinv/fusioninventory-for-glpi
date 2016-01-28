<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2015 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2015 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"],
        "plugins", "pluginfusioninventorymenu", "inventoryruleimport");

Session::checkLoginUser();
PluginFusioninventoryMenu::displayMenu("mini");

$rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();

if (isset($_GET['resetrules'])) {
   $pfSetup = new PluginFusioninventorySetup();
   $pfSetup->initRules(1);
   Html::back();
}

echo "<center><a href='".$CFG_GLPI['root_doc'] .
         "/plugins/fusioninventory/front/inventoryruleimport.php?resetrules=1' class='vsubmit'>";
echo __('Reset import rules (define only default rules)', 'fusioninventory');
echo "</a></center><br/>";

include (GLPI_ROOT . "/front/rule.common.php");

?>