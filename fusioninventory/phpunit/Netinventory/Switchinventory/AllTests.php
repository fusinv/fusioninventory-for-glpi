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
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

class Switchinventory extends PHPUnit_Framework_TestCase {


   public function testSetModuleInventoryOn() {
      $DB = new DB();

      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
         SET `is_active`='1'
         WHERE `modulename`='SNMPQUERY' ";
      $DB->query($query);
      
      $networkEquipment = new NetworkEquipment();
      $a_equipments = $networkEquipment->find();
      foreach ($a_equipments as $id=>$data) {
         $networkEquipment->delete(array('id'=>$id), 1);
      }

   }


   public function testSendinventories() {
      global $DB;
      
      $plugin = new Plugin();
      $plugin->getFromDBbyDir("fusioninventory");
      $plugin->activate($plugin->fields['id']);
      Plugin::load("fusioninventory");
      
      // Add task and taskjob
      $pluginFusioninventoryTask = new PluginFusioninventoryTask();
      $pluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $pluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();

      $input = array();
      $input['entities_id'] = '0';
      $input['name'] = 'snmpquery';
      $tasks_id = $pluginFusioninventoryTask->add($input);

      $input = array();
      $input['plugin_fusioninventory_tasks_id'] = $tasks_id;
      $input['method'] = 'snmpquery';
      $input['status'] = 1;
      $taskjobs_id = $pluginFusioninventoryTaskjob->add($input);

      $input = array();
      $input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $input['itemtype'] = 'NetworkEquipment';
      $input['items_id'] = '1';
      $input['state'] = 1;
      $input['plugin_fusioninventory_agents_id'] = 1;
      $pluginFusioninventoryTaskjobstatus->add($input);
      $input['items_id'] = '2';
      $pluginFusioninventoryTaskjobstatus->add($input);

      $switch1 = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <COMMENTS>Cisco IOS Software, 1841 Software (C1841-ADVIPSERVICESK9-M), Version 12.4(25d), RELEASE SOFTWARE (fc1)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Wed 18-Aug-10 04:40 by prod_rel_team</COMMENTS>
        <ID>53</ID>
        <IPS>
          <IP>172.27.2.22</IP>
          <IP>212.99.4.74</IP>
          <IP>212.99.4.73</IP>
          <IP>172.27.2.21</IP>
        </IPS>
        <LOCATION>RMS Grenoble </LOCATION>
        <MAC>00:00:00:00:00:00</MAC>
        <MODEL>CISCO1841</MODEL>
        <NAME>vpn1.vpn.rms.loc</NAME>
        <SERIAL>FCZ11161074</SERIAL>
        <TYPE>NETWORKING</TYPE>
        <UPTIME>26 days, 02:17:14.06</UPTIME>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>GigabitEthernet1/0/22</IFDESCR>
              <IP>172.27.0.40</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/1</IFDESCR>
          <IFINERRORS>31</IFINERRORS>
          <IFINOCTETS>3088668153</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>15.24 seconds</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Fa0/1</IFNAME>
          <IFNUMBER>2</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>3475169543</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:54:99:62:45</MAC>
        </PORT>
        <PORT>
          <IFDESCR>Null0</IFDESCR>
          <IFINERRORS>0</IFINERRORS>
          <IFINOCTETS>0</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>0.00 seconds</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Nu0</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>0</IFOUTOCTETS>
          <IFSPEED>4294967295</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>1</IFTYPE>
        </PORT>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>GigabitEthernet1/0/23</IFDESCR>
              <IP>172.27.0.40</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/0</IFDESCR>
          <IFINERRORS>232</IFINERRORS>
          <IFINOCTETS>4006858975</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>15.23 seconds</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Fa0/0</IFNAME>
          <IFNUMBER>1</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>1553256247</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:54:99:62:44</MAC>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $switch2 = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <COMMENTS>Cisco IOS Software, C3750 Software (C3750-IPSERVICESK9-M), Version 12.2(55)SE, RELEASE SOFTWARE (fc2)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Sat 07-Aug-10 22:45 by prod_rel_team</COMMENTS>
        <CPU>6</CPU>
        <FIRMWARE>12.2(55)SE</FIRMWARE>
        <ID>1585</ID>
        <IPS>
          <IP>172.27.0.40</IP>
        </IPS>
        <LOCATION>RMS Grenoble </LOCATION>
        <MAC>00:1b:2b:20:40:80</MAC>
        <MEMORY>33</MEMORY>
        <MODEL>WS-C3750G-24T-S</MODEL>
        <NAME>sw1.inf.rms.loc</NAME>
        <RAM>128</RAM>
        <SERIAL>CAT1109RGVK</SERIAL>
        <TYPE>NETWORKING</TYPE>
        <UPTIME>41 days, 06:53:36.46</UPTIME>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>FastEthernet0/0</IFDESCR>
              <IP>212.99.4.74</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>GigabitEthernet1/0/23</IFDESCR>
          <IFINERRORS>0</IFINERRORS>
          <IFINOCTETS>3245688497</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>2 days, 03:56:07.66</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Gi1/0/23</IFNAME>
          <IFNUMBER>10123</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>851136551</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:2b:20:40:97</MAC>
          <TRUNK>0</TRUNK>
        </PORT>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>FastEthernet0/1</IFDESCR>
              <IP>172.27.2.22</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>GigabitEthernet1/0/22</IFDESCR>
          <IFINERRORS>0</IFINERRORS>
          <IFINOCTETS>949702179</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>2 days, 03:56:07.64</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Gi1/0/22</IFNAME>
          <IFNUMBER>10122</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>2633042471</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:2b:20:40:96</MAC>
          <TRUNK>0</TRUNK>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>2</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $networkPort = new NetworkPort();
      
      // * 1. Create switch 1
      $this->testSendinventory("toto", $switch1, 1);
         
      // * 2. Create switch 2 
      $this->testSendinventory("toto", $switch2, 1);
      
      // * 3. update switch 1
      $this->testSendinventory("toto", $switch1, 1);
         
         // CHECK 1 : Check ip of ports
         $a_ports = $networkPort->find("`name`='Fa0/1'");
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "", 'IP of port Fa/01 not right');
         $this->assertEquals($a_port['mac'], "00:1b:54:99:62:45", 'MAC of port Fa/01 not right');
         
         $a_ports = $networkPort->find("`name`='Fa0/0'");
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "", 'IP of port Fa0/0 not right');
         $this->assertEquals($a_port['mac'], "00:1b:54:99:62:44", 'MAC of port Fa0/0 not right');
         
         $a_ports = $networkPort->find("`name`='Gi1/0/23'");
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "", 'IP of port Gi1/0/23 not right');
         $this->assertEquals($a_port['mac'], "00:1b:2b:20:40:97", 'MAC of port Gi1/0/23 not right');
         
         $a_ports = $networkPort->find("`name`='Gi1/0/22'");
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "", 'IP of port Gi1/0/22 not right');
         $this->assertEquals($a_port['mac'], "00:1b:2b:20:40:96", 'MAC of port Gi1/0/22 not right');
         
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }



   function testSendinventory($xmlFile='', $xmlstring='', $create='0') {

      if (empty($xmlFile)) {
         echo "testSendinventory with no arguments...\n";
         return;
      }

      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/fusion0.80/plugins/fusioninventory/front/communication.php";
      if (empty($xmlstring)) {
         $xml = simplexml_load_file($xmlFile,'SimpleXMLElement', LIBXML_NOCDATA);
      } else {
         $xml = simplexml_load_string($xmlstring);
      }

      if ($create == '1') {
         // Send prolog for creation of agent in GLPI
         $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
   <REQUEST>
     <DEVICEID>'.$xml->DEVICEID.'</DEVICEID>
     <QUERY>PROLOG</QUERY>
     <TOKEN>CBXTMXLU</TOKEN>
   </REQUEST>';
         $emulatorAgent->sendProlog($input_xml);

         foreach ($xml->CONTENT->DEVICE as $child) {
            foreach ($child->INFO as $child2) {
               if ($child2->TYPE == 'NETWORKING') {
                  // Create switch in asset
                  $NetworkEquipment = new NetworkEquipment();
                  $input = array();
                  if (isset($child2->SERIAL)) {
                     $input['serial']=$child2->SERIAL;
                  } else {
                     $input['name']=$child2->NAME;
                  }
                  $input['entities_id'] = 0;
                  $NetworkEquipment->add($input);
               }
            }
         }
      }
      $input_xml = $xml->asXML();
      $code = $emulatorAgent->sendProlog($input_xml);
      echo $code."\n";
      
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }

}



class Switchinventory_AllTests  {

   public static function suite() {

      $GLPIInstall = new GLPIInstall();
      $Install = new Install();
      $GLPIInstall->testInstall();
      $Install->testInstall(0);
      
      $suite = new PHPUnit_Framework_TestSuite('Switchinventory');
      return $suite;
   }
}

?>