<?
// This file was generated automatically - do not edit nor deposit

// Copyright Colubris Network Inc. 2006 */

// This code is provided "as is", without any warranty of any kind, either
// expressed or implied, including but not limited to, any implied warranty
// of merchantibility or fitness for any purpose.
// In no event will Colubris Networks Inc. or any party who distributed
// the code be liable for damages or for any claim(s) by any other party,
// including but not limited to, any lost profits, lost data or data rendered
// inaccurate, losses sustained by third parties, or any other special,
// incidental or consequential damages arising out of the use or inability to
// use the program, even if the possibility of such damages has been advised
// against.
// The entire risk as to the quality, the performance, and the fitness of the
// program for any particular purpose lies with the party using the code.


class SoapApi extends SoapClient
{
//-------------------------------------------------------------------------------
// function for flushing wsdl cache
//
    static function ClearWSDLCache()
    {
        //  clearing WSDL cache
        ini_set("soap.wsdl_cache_enabled", "0");
    }

//-------------------------------------------------------------------------------
// implementation for function UpdateRadioState()
//
    function soapUpdateRadioState($deviceId, $state)
    {
        //   Update the radios status 
        $rc = $this->UpdateRadioState(array("deviceId" => $deviceId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioState()
//
    function soapGetRadioState($deviceId)
    {
        //   Get the radio state 
        $rc = $this->GetRadioState(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioMode()
//
    function soapUpdateRadioMode($deviceId, $radioOperatingMode, $radioPhyType)
    {
        //   Update the radio operational mode and physical mode. 
        $rc = $this->UpdateRadioMode(array("deviceId" => $deviceId, "radioOperatingMode" => $radioOperatingMode, "radioPhyType" => $radioPhyType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioMode()
//
    function soapGetRadioMode($deviceId)
    {
        //   Get the radio operational mode and physical mode. 
        $rc = $this->GetRadioMode(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioAutoChannelInterval()
//
    function soapUpdateRadioAutoChannelInterval($deviceId, $radioInterval)
    {
        //   Update radio interval for channel setting re-evaluation. 
        $rc = $this->UpdateRadioAutoChannelInterval(array("deviceId" => $deviceId, "radioInterval" => $radioInterval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioAutoChannelInterval()
//
    function soapGetRadioAutoChannelInterval($deviceId)
    {
        //   Get radio interval for channel setting re-evaluation. 
        $rc = $this->GetRadioAutoChannelInterval(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioAutoChannelTimeOfDay()
//
    function soapUpdateRadioAutoChannelTimeOfDay($deviceId, $timeOfDay)
    {
        //   Update radio time of day for channel setting re-evaluation. 
        $rc = $this->UpdateRadioAutoChannelTimeOfDay(array("deviceId" => $deviceId, "timeOfDay" => $timeOfDay));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioAutoChannelTimeOfDay()
//
    function soapGetRadioAutoChannelTimeOfDay($deviceId)
    {
        //   Get radio time of day for channel setting re-evaluation. 
        $rc = $this->GetRadioAutoChannelTimeOfDay(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioPowerControl()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateRadioPowerControl($deviceId, $powerControlMode, $powerDBm, $radioInterval)
    {
        //   Update radio power control. 
        $rc = $this->UpdateRadioPowerControl(array("deviceId" => $deviceId, "powerControlMode" => $powerControlMode, "powerDBm" => $powerDBm, "radioInterval" => $radioInterval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioPowerControl()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetRadioPowerControl($deviceId)
    {
        //   Get radio power control. 
        $rc = $this->GetRadioPowerControl(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioRTSThreshold()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateRadioRTSThreshold($deviceId, $state, $bytes)
    {
        //   Update radio Request To Send threshold. 
        $rc = $this->UpdateRadioRTSThreshold(array("deviceId" => $deviceId, "state" => $state, "bytes" => $bytes));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioRTSThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetRadioRTSThreshold($deviceId)
    {
        //   Get Radio Request To Send threshold 
        $rc = $this->GetRadioRTSThreshold(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioMultiCastTxRate()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateRadioMultiCastTxRate($deviceId, $speed)
    {
        //   Update radio multicast transmit rate 
        $rc = $this->UpdateRadioMultiCastTxRate(array("deviceId" => $deviceId, "speed" => $speed));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioMultiCastTxRate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetRadioMultiCastTxRate($deviceId)
    {
        //   Get radio multicast transmit rate 
        $rc = $this->GetRadioMultiCastTxRate(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioAntennaSelection()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateRadioAntennaSelection($deviceId, $antenna)
    {
        //   Update radio antenna selection. 
        $rc = $this->UpdateRadioAntennaSelection(array("deviceId" => $deviceId, "antenna" => $antenna));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioAntennaSelection()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetRadioAntennaSelection($deviceId)
    {
        //   Get radio antenna selection. 
        $rc = $this->GetRadioAntennaSelection(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioAntennaGain()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateRadioAntennaGain($deviceId, $gain)
    {
        //   Update radio antenna gain. 
        $rc = $this->UpdateRadioAntennaGain(array("deviceId" => $deviceId, "gain" => $gain));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioAntennaGain()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetRadioAntennaGain($deviceId)
    {
        //   Get radio antenna gain. 
        $rc = $this->GetRadioAntennaGain(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioAPDistance()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateRadioAPDistance($deviceId, $distance)
    {
        //   Update radio distance between Access Points. 
        $rc = $this->UpdateRadioAPDistance(array("deviceId" => $deviceId, "distance" => $distance));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioAPDistance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetRadioAPDistance($deviceId)
    {
        //   Get radio distance between Access Points. 
        $rc = $this->GetRadioAPDistance(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioChannel()
//
    function soapUpdateRadioChannel($deviceId, $autoState, $channel)
    {
        //   Update radio frequency channel. 
        $rc = $this->UpdateRadioChannel(array("deviceId" => $deviceId, "autoState" => $autoState, "channel" => $channel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioChannel()
//
    function soapGetRadioChannel($deviceId)
    {
        //   Get radio frequency channel. 
        $rc = $this->GetRadioChannel(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddRadioDisabledChannel()
//
    function soapAddRadioDisabledChannel($deviceId, $channel)
    {
        //  Add the specified channel to the list of channels that are not allowed to be selected by the Auto Channel algorithm.
        $rc = $this->AddRadioDisabledChannel(array("deviceId" => $deviceId, "channel" => $channel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteRadioDisabledChannel()
//
    function soapDeleteRadioDisabledChannel($deviceId, $channel)
    {
        //  Delete the specified channel from the list of channels that are not allowed to be selected by the Auto Channel algorithm.
        $rc = $this->DeleteRadioDisabledChannel(array("deviceId" => $deviceId, "channel" => $channel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioDisabledChannels()
//
    function soapGetRadioDisabledChannels($deviceId)
    {
        //  Get the list of channels that are not allowed to be selected by the Auto Channel algorithm.
        $rc = $this->GetRadioDisabledChannels(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioBeaconInterval()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateRadioBeaconInterval($deviceId, $interval)
    {
        //   Update the radio beacon interval. 
        $rc = $this->UpdateRadioBeaconInterval(array("deviceId" => $deviceId, "interval" => $interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioBeaconInterval()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetRadioBeaconInterval($deviceId)
    {
        //   Get beacon interval. 
        $rc = $this->GetRadioBeaconInterval(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioMaximumClients()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateRadioMaximumClients($deviceId, $clients)
    {
        //   Update the radio maximum number of clients. 
        $rc = $this->UpdateRadioMaximumClients(array("deviceId" => $deviceId, "clients" => $clients));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioMaximumClients()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetRadioMaximumClients($deviceId)
    {
        //   Get the maximum number of clients. 
        $rc = $this->GetRadioMaximumClients(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLinkSetting()
//
    function soapUpdateLinkSetting($deviceId, $speed, $duplex)
    {
        //   Update port link settings. 
        $rc = $this->UpdateLinkSetting(array("deviceId" => $deviceId, "speed" => $speed, "duplex" => $duplex));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLinkSetting()
//
    function soapGetLinkSetting($deviceId)
    {
        //   Get port link setting. 
        $rc = $this->GetLinkSetting(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePortDataRateLimit()
//
    function soapUpdatePortDataRateLimit($portId, $state, $rxRate, $txRate)
    {
        //   Update Port Data Rate Limit. (Bandwidth Control) 
        $rc = $this->UpdatePortDataRateLimit(array("portId" => $portId, "state" => $state, "rxRate" => $rxRate, "txRate" => $txRate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPortDataRateLimit()
//
    function soapGetPortDataRateLimit($portId)
    {
        //   Get Port Data Rate Limit. (Bandwidth Control) 
        $rc = $this->GetPortDataRateLimit(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePortDataRateLevel()
//
    function soapUpdatePortDataRateLevel($portId, $veryHighMinTx, $veryHighMinRx, $veryHighMaxTx, $veryHighMaxRx, $highMinTx, $highMinRx, $highMaxTx, $highMaxRx, $normalMinTx, $normalMinRx, $normalMaxTx, $normalMaxRx, $lowMinTx, $lowMinRx, $lowMaxTx, $lowMaxRx)
    {
        //   Update Port Data Rate Level setting. 
        $rc = $this->UpdatePortDataRateLevel(array("portId" => $portId, "veryHighMinTx" => $veryHighMinTx, "veryHighMinRx" => $veryHighMinRx, "veryHighMaxTx" => $veryHighMaxTx, "veryHighMaxRx" => $veryHighMaxRx, "highMinTx" => $highMinTx, "highMinRx" => $highMinRx, "highMaxTx" => $highMaxTx, "highMaxRx" => $highMaxRx, "normalMinTx" => $normalMinTx, "normalMinRx" => $normalMinRx, "normalMaxTx" => $normalMaxTx, "normalMaxRx" => $normalMaxRx, "lowMinTx" => $lowMinTx, "lowMinRx" => $lowMinRx, "lowMaxTx" => $lowMaxTx, "lowMaxRx" => $lowMaxRx));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPortDataRateLevel()
//
    function soapGetPortDataRateLevel($portId)
    {
        //   Get port data rate level setting. 
        $rc = $this->GetPortDataRateLevel(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDWDSProvisioningGroup()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapUpdateDWDSProvisioningGroup($actAsAlternateMaster, $useMultipleRadios)
    {
        //   Override DWDS provisioning group settings. 
        $rc = $this->UpdateDWDSProvisioningGroup(array("actAsAlternateMaster" => $actAsAlternateMaster, "useMultipleRadios" => $useMultipleRadios));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDWDSGroup()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapUpdateDWDSGroup($index, $name, $state, $radios, $dwdsMode, $groupId, $allowedDowntime, $minimumSNR, $SNRPerHop, $securityState, $securityMode, $wepKey, $psk, $maxLinks)
    {
        //   Update DWDS group settings. 
        $rc = $this->UpdateDWDSGroup(array("index" => $index, "name" => $name, "state" => $state, "radios" => $radios, "dwdsMode" => $dwdsMode, "groupId" => $groupId, "allowedDowntime" => $allowedDowntime, "minimumSNR" => $minimumSNR, "SNRPerHop" => $SNRPerHop, "securityState" => $securityState, "securityMode" => $securityMode, "wepKey" => $wepKey, "psk" => $psk, "maxLinks" => $maxLinks));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddDWDSIPQoSProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddDWDSIPQoSProfile($qosProfileName)
    {
        //   Add IP QoS profile to DWDS. Internal use only.
        $rc = $this->AddDWDSIPQoSProfile(array("qosProfileName" => $qosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteDWDSIPQoSProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteDWDSIPQoSProfile($qosProfileName)
    {
        //   Delete IP QoS profile to DWDS. Internal use only.
        $rc = $this->DeleteDWDSIPQoSProfile(array("qosProfileName" => $qosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllDWDSIPQoSProfiles()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteAllDWDSIPQoSProfiles()
    {
        //   Remove all IP QoS Profiles referenced by DWDS. Internal use only.
        $rc = $this->DeleteAllDWDSIPQoSProfiles(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWDS()
//
    function soapUpdateWDS($wdsName, $state, $speed, $remoteMacAddr, $deviceId)
    {
        //   Update wireless link settings. 
        $rc = $this->UpdateWDS(array("wdsName" => $wdsName, "state" => $state, "speed" => $speed, "remoteMacAddr" => $remoteMacAddr, "deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWDS()
//
    function soapGetWDS($wdsName)
    {
        //   Get Wireless Link Settings. 
        $rc = $this->GetWDS(array("wdsName" => $wdsName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWDSSecurityMode()
//
    function soapUpdateWDSSecurityMode($wdsName, $securityEncryption)
    {
        //   Update Wireless Link Security Mode. 
        $rc = $this->UpdateWDSSecurityMode(array("wdsName" => $wdsName, "securityEncryption" => $securityEncryption));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWDSSecurityMode()
//
    function soapGetWDSSecurityMode($wdsName)
    {
        //   Get wireless link security mode. 
        $rc = $this->GetWDSSecurityMode(array("wdsName" => $wdsName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWDSWepSecurity()
//
    function soapUpdateWDSWepSecurity($wdsName, $wepKey)
    {
        //   Update Wireless Link WEP Security settings. 
        $rc = $this->UpdateWDSWepSecurity(array("wdsName" => $wdsName, "wepKey" => $wepKey));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWDSWepSecurity()
//
    function soapGetWDSWepSecurity($wdsName)
    {
        //   Get Wireless Link WEP Security settings. 
        $rc = $this->GetWDSWepSecurity(array("wdsName" => $wdsName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWDSPSK()
//
    function soapUpdateWDSPSK($wdsName, $psk)
    {
        //   Update Wireless Link PSK security settings. 
        $rc = $this->UpdateWDSPSK(array("wdsName" => $wdsName, "psk" => $psk));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWDSPSK()
//
    function soapGetWDSPSK($wdsName)
    {
        //   Get Wireless Link PSK security settings. 
        $rc = $this->GetWDSPSK(array("wdsName" => $wdsName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWDSQoS()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateWDSQoS($wdsName, $trafficPriority)
    {
        //   Update Wireless Link Quality Of Service settings. 
        $rc = $this->UpdateWDSQoS(array("wdsName" => $wdsName, "trafficPriority" => $trafficPriority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWDSQoS()
//
    function soapGetWDSQoS($wdsName)
    {
        //   Get Quality Of Service settings for Wireless Links. 
        $rc = $this->GetWDSQoS(array("wdsName" => $wdsName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioAckDistance()
//
    function soapUpdateRadioAckDistance($deviceId, $distance)
    {
        //   Update Radio Acknowledge Distance settings. 
        $rc = $this->UpdateRadioAckDistance(array("deviceId" => $deviceId, "distance" => $distance));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioAckDistance()
//
    function soapGetRadioAckDistance($deviceId)
    {
        //   Get Radio Acknowledge distance settings. 
        $rc = $this->GetRadioAckDistance(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVLAN()
//
    function soapUpdateVLAN($networkProfileName, $assignationMode, $ipAddress, $ipMask, $ipGateway, $natState)
    {
        //   Update VLAN settings. 
        $rc = $this->UpdateVLAN(array("networkProfileName" => $networkProfileName, "assignationMode" => $assignationMode, "ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway, "natState" => $natState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVLANPort()
//
    function soapUpdateVLANPort($networkProfileName, $portId)
    {
        //   Update VLAN settings. 
        $rc = $this->UpdateVLANPort(array("networkProfileName" => $networkProfileName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVLAN()
//
    function soapGetVLAN($networkProfileName)
    {
        //   Get VLAN settings. 
        $rc = $this->GetVLAN(array("networkProfileName" => $networkProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddVLAN()
//
    function soapAddVLAN($networkProfileName, $portId, $assignationMode, $ipAddress, $ipMask, $ipGateway, $natState)
    {
        //   Add a VLAN interface. 
        $rc = $this->AddVLAN(array("networkProfileName" => $networkProfileName, "portId" => $portId, "assignationMode" => $assignationMode, "ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway, "natState" => $natState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteVLAN()
//
    function soapDeleteVLAN($networkProfileName)
    {
        //   Delete VLAN interface. 
        $rc = $this->DeleteVLAN(array("networkProfileName" => $networkProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllVLANs()
//
    function soapDeleteAllVLANs()
    {
        //   Delete all VLAN interfaces. 
        $rc = $this->DeleteAllVLANs(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVLANList()
//
    function soapGetVLANList()
    {
        //   Get VLAN list. 
        $rc = $this->GetVLANList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateGRE()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateGRE($greName, $localTunnelIP, $remoteTunnelIP, $tunnelIPMask, $peerIPAddress)
    {
        //   Update GRE interface settings. 
        $rc = $this->UpdateGRE(array("greName" => $greName, "localTunnelIP" => $localTunnelIP, "remoteTunnelIP" => $remoteTunnelIP, "tunnelIPMask" => $tunnelIPMask, "peerIPAddress" => $peerIPAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateGREName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateGREName($oldGreName, $newGreName)
    {
        //   Update GRE interface name. 
        $rc = $this->UpdateGREName(array("oldGreName" => $oldGreName, "newGreName" => $newGreName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetGRE()
//
    function soapGetGRE($greName)
    {
        //   Get GRE interface settings. 
        $rc = $this->GetGRE(array("greName" => $greName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddGRE()
//
    function soapAddGRE($greName, $localTunnelIP, $remoteTunnelIP, $tunnelIPMask, $peerIPAddress)
    {
        //   Add GRE interface. 
        $rc = $this->AddGRE(array("greName" => $greName, "localTunnelIP" => $localTunnelIP, "remoteTunnelIP" => $remoteTunnelIP, "tunnelIPMask" => $tunnelIPMask, "peerIPAddress" => $peerIPAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteGRE()
//
    function soapDeleteGRE($greName)
    {
        //   Delete GRE interface. 
        $rc = $this->DeleteGRE(array("greName" => $greName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllGREs()
//
    function soapDeleteAllGREs()
    {
        //   Delete all GRE interfaces. 
        $rc = $this->DeleteAllGREs(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetGREList()
//
    function soapGetGREList()
    {
        //   Get GRE list. 
        $rc = $this->GetGREList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateEthernetStaticAddr()
//
    function soapUpdateEthernetStaticAddr($ethName, $ipAddress, $ipMask)
    {
        //   Update ethernet IP address. 
        $rc = $this->UpdateEthernetStaticAddr(array("ethName" => $ethName, "ipAddress" => $ipAddress, "ipMask" => $ipMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetEthernetStaticAddr()
//
    function soapGetEthernetStaticAddr($ethName)
    {
        //  Get Ethernet IP address.
        $rc = $this->GetEthernetStaticAddr(array("ethName" => $ethName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateEthernetNAT()
//
    function soapUpdateEthernetNAT($portId, $natState, $natPortRangeState, $natPortRangeSize)
    {
        //   Update NAT settings for Ethernet port. 
        $rc = $this->UpdateEthernetNAT(array("portId" => $portId, "natState" => $natState, "natPortRangeState" => $natPortRangeState, "natPortRangeSize" => $natPortRangeSize));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateConsolePasswordReset()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateConsolePasswordReset($state)
    {
        //   Update the Console Password Reset feature. 
        $rc = $this->UpdateConsolePasswordReset(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetConsolePasswordResetState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetConsolePasswordResetState()
    {
        //   Get Console Password Reset state. 
        $rc = $this->GetConsolePasswordResetState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetEthernetNAT()
//
    function soapGetEthernetNAT($portId)
    {
        //   Get Ethernet NAT settings. 
        $rc = $this->GetEthernetNAT(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateEthernetDHCPClientId()
//
    function soapUpdateEthernetDHCPClientId($portId, $clientId)
    {
        //   Update Ethernet DHPC Client Identifier. 
        $rc = $this->UpdateEthernetDHCPClientId(array("portId" => $portId, "clientId" => $clientId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetEthernetDHCPClientId()
//
    function soapGetEthernetDHCPClientId($portId)
    {
        //   Get Ethernet DHCP Client Identifier. 
        $rc = $this->GetEthernetDHCPClientId(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddEthernetAlternateIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapAddEthernetAlternateIP($portId, $ipAddress)
    {
        //   Add Ethernet alternate IP address. 
        $rc = $this->AddEthernetAlternateIP(array("portId" => $portId, "ipAddress" => $ipAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteEthernetAlternateIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteEthernetAlternateIP($portId, $ipAddress)
    {
        //   Delete Ethernet alternate IP address. 
        $rc = $this->DeleteEthernetAlternateIP(array("portId" => $portId, "ipAddress" => $ipAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetEthernetAlternateIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetEthernetAlternateIP($portId)
    {
        //   Get Ethernet alternate IP addresses. 
        $rc = $this->GetEthernetAlternateIP(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateEthernetDiscoveryProtocol()
//
    function soapUpdateEthernetDiscoveryProtocol($portId, $state, $deviceId)
    {
        //   Update Ethernet Discovery Protocol settings. 
        $rc = $this->UpdateEthernetDiscoveryProtocol(array("portId" => $portId, "state" => $state, "deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetEthernetDiscoveryProtocol()
//
    function soapGetEthernetDiscoveryProtocol($portId)
    {
        //   Get Ethernet Discovery Protocol settings. 
        $rc = $this->GetEthernetDiscoveryProtocol(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWirelessIPV6RAFilteringState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateWirelessIPV6RAFilteringState($state)
    {
        //  Update IPv6 RA filtering settings. 
        $rc = $this->UpdateWirelessIPV6RAFilteringState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessIPV6RAFilteringState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetWirelessIPV6RAFilteringState()
    {
        //   Get IPv6 RA filtering settings.
        $rc = $this->GetWirelessIPV6RAFilteringState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWirelessIGMPSnoopingHelpersState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateWirelessIGMPSnoopingHelpersState($state)
    {
        //   Update IGMP snooping helpers settings. 
        $rc = $this->UpdateWirelessIGMPSnoopingHelpersState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessIGMPSnoopingHelpersState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetWirelessIGMPSnoopingHelpersState()
    {
        //   Get IGMP snooping helpers settings. 
        $rc = $this->GetWirelessIGMPSnoopingHelpersState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateRIP($interfaceName, $state, $mode)
    {
        //   Update RIP settings. 
        $rc = $this->UpdateRIP(array("interfaceName" => $interfaceName, "state" => $state, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetRIP($interfaceName)
    {
        //   Get RIP settings. 
        $rc = $this->GetRIP(array("interfaceName" => $interfaceName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateEthernetIPAssignationMode()
//
    function soapUpdateEthernetIPAssignationMode($portId, $assignationMode)
    {
        //   Update Ethernet IP address assignation mode. 
        $rc = $this->UpdateEthernetIPAssignationMode(array("portId" => $portId, "assignationMode" => $assignationMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetEthernetIPAssignationMode()
//
    function soapGetEthernetIPAssignationMode($portId)
    {
        //   Get IP address assignation mode for Ethernet port.. 
        $rc = $this->GetEthernetIPAssignationMode(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateBridgeSpanningTreeProtocol()
//
    function soapUpdateBridgeSpanningTreeProtocol($bridgeName, $state, $priority)
    {
        //   Update Bridge Spanning Tree Protocol settings. 
        $rc = $this->UpdateBridgeSpanningTreeProtocol(array("bridgeName" => $bridgeName, "state" => $state, "priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetBridgeSpanningTreeProtocol()
//
    function soapGetBridgeSpanningTreeProtocol($bridgeName)
    {
        //   Get Bridge Spanning Tree Protocol settings. 
        $rc = $this->GetBridgeSpanningTreeProtocol(array("bridgeName" => $bridgeName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVLANBridgeSpanningTreeProtocol()
//
    function soapUpdateVLANBridgeSpanningTreeProtocol($bridgeName, $state)
    {
        //   Update VLAN Bridge Spanning Tree Protocol settings. 
        $rc = $this->UpdateVLANBridgeSpanningTreeProtocol(array("bridgeName" => $bridgeName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVLANBridgeSpanningTreeProtocolWithPriority()
//
    function soapUpdateVLANBridgeSpanningTreeProtocolWithPriority($bridgeName, $state, $priority)
    {
        //   Update VLAN Bridge Spanning Tree Protocol settings. 
        $rc = $this->UpdateVLANBridgeSpanningTreeProtocolWithPriority(array("bridgeName" => $bridgeName, "state" => $state, "priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVLANBridgeSpanningTreeProtocolWithPriority()
//
    function soapGetVLANBridgeSpanningTreeProtocolWithPriority($bridgeName)
    {
        //   Get VLAN Bridge Spanning Tree Protocol settings. 
        $rc = $this->GetVLANBridgeSpanningTreeProtocolWithPriority(array("bridgeName" => $bridgeName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVLANBridgeSpanningTreeProtocol()
//
    function soapGetVLANBridgeSpanningTreeProtocol($bridgeName)
    {
        //   Get Bridge Spanning Tree Protocol settings. 
        $rc = $this->GetVLANBridgeSpanningTreeProtocol(array("bridgeName" => $bridgeName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteDHCPCommand()
//
    function soapExecuteDHCPCommand($ethName, $command)
    {
        //   Execute DHCP renew/release command. 
        $rc = $this->ExecuteDHCPCommand(array("ethName" => $ethName, "command" => $command));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddPPTPClient()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapAddPPTPClient($pptpName, $state, $serverAddr, $domainName, $autoRouteDiscoveryState, $lcpEchoRequestState, $username, $password, $natState)
    {
        //   Add a PPTP client interface. 
        $rc = $this->AddPPTPClient(array("pptpName" => $pptpName, "state" => $state, "serverAddr" => $serverAddr, "domainName" => $domainName, "autoRouteDiscoveryState" => $autoRouteDiscoveryState, "lcpEchoRequestState" => $lcpEchoRequestState, "username" => $username, "password" => $password, "natState" => $natState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePPTPClient()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdatePPTPClient($pptpName, $state, $serverAddr, $domainName, $autoRouteDiscoveryState, $lcpEchoRequestState, $username, $password, $natState)
    {
        //   Update PPTP client interface settings. 
        $rc = $this->UpdatePPTPClient(array("pptpName" => $pptpName, "state" => $state, "serverAddr" => $serverAddr, "domainName" => $domainName, "autoRouteDiscoveryState" => $autoRouteDiscoveryState, "lcpEchoRequestState" => $lcpEchoRequestState, "username" => $username, "password" => $password, "natState" => $natState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPPTPClient()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetPPTPClient($pptpName)
    {
        //   Get PPTP client interface settings. 
        $rc = $this->GetPPTPClient(array("pptpName" => $pptpName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeletePPTPClient()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeletePPTPClient($pptpName)
    {
        //   Delete PPTP client interface. 
        $rc = $this->DeletePPTPClient(array("pptpName" => $pptpName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddPPPOE()
//
    function soapAddPPPOE($pppoeName, $username, $password, $mru, $mtu, $autoReconnectState, $unNumberedModeState)
    {
        //   Add a PPPOE (client) interface. 
        $rc = $this->AddPPPOE(array("pppoeName" => $pppoeName, "username" => $username, "password" => $password, "mru" => $mru, "mtu" => $mtu, "autoReconnectState" => $autoReconnectState, "unNumberedModeState" => $unNumberedModeState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePPPOE()
//
    function soapUpdatePPPOE($pppoeName, $username, $password, $mru, $mtu, $autoReconnectState, $unNumberedModeState)
    {
        //   Update PPPOE interface settings. 
        $rc = $this->UpdatePPPOE(array("pppoeName" => $pppoeName, "username" => $username, "password" => $password, "mru" => $mru, "mtu" => $mtu, "autoReconnectState" => $autoReconnectState, "unNumberedModeState" => $unNumberedModeState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPPPOE()
//
    function soapGetPPPOE($pppoeName)
    {
        //   Get PPPOE interface settings. 
        $rc = $this->GetPPPOE(array("pppoeName" => $pppoeName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeletePPPOE()
//
    function soapDeletePPPOE($pppoeName)
    {
        //   Delete PPPOE interface. 
        $rc = $this->DeletePPPOE(array("pppoeName" => $pppoeName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecutePPPOERestart()
//
    function soapExecutePPPOERestart($pppoeName)
    {
        //   Execute restart command of PPPOE interface. 
        $rc = $this->ExecutePPPOERestart(array("pppoeName" => $pppoeName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddVirtualSC()
//
    function soapAddVirtualSC($vscName)
    {
        //   Add a new Virtual SC. 
        $rc = $this->AddVirtualSC(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSC()
//
    function soapUpdateVirtualSC($oldVSCName, $newVSCName)
    {
        //   Update Virtual SC. 
        $rc = $this->UpdateVirtualSC(array("oldVSCName" => $oldVSCName, "newVSCName" => $newVSCName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteVirtualSC()
//
    function soapDeleteVirtualSC($vscName)
    {
        //   Delete Virtual SC. 
        $rc = $this->DeleteVirtualSC(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllVirtualSCs()
//
    function soapDeleteAllVirtualSCs()
    {
        //   Delete all Virtual SC. 
        $rc = $this->DeleteAllVirtualSCs(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCUniqueId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCUniqueId($vscName)
    {
        //   Retreive the virtual sc unique id. 
        $rc = $this->GetVirtualSCUniqueId(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCUserRate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCUserRate($vscName, $state, $rxRate, $txRate)
    {
        //   Update Virtual SC user data rates. 
        $rc = $this->UpdateVirtualSCUserRate(array("vscName" => $vscName, "state" => $state, "rxRate" => $rxRate, "txRate" => $txRate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCUserRate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCUserRate($vscName)
    {
        //   Get Virtual SC user data rates. 
        $rc = $this->GetVirtualSCUserRate(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCBandwidthControl()
//
    function soapUpdateVirtualSCBandwidthControl($vscName, $priority)
    {
        //   Update Virtual SC Bandwidth Control settings. 
        $rc = $this->UpdateVirtualSCBandwidthControl(array("vscName" => $vscName, "priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCBandwidthControl()
//
    function soapGetVirtualSCBandwidthControl($vscName)
    {
        //   Get Virtual SC Bandwidth Control settings. 
        $rc = $this->GetVirtualSCBandwidthControl(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCWirelessQoS()
//
    function soapUpdateVirtualSCWirelessQoS($vscName, $priority, $diffServ, $wmmAdvertising)
    {
        //   Update Virtual SC wireless QoS settings. 
        $rc = $this->UpdateVirtualSCWirelessQoS(array("vscName" => $vscName, "priority" => $priority, "diffServ" => $diffServ, "wmmAdvertising" => $wmmAdvertising));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCWirelessQoS()
//
    function soapGetVirtualSCWirelessQoS($vscName)
    {
        //   Get Virtual SC Wireless QoS settings. 
        $rc = $this->GetVirtualSCWirelessQoS(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCLocationAware()
//
    function soapUpdateVirtualSCLocationAware($vscName, $groupName, $contentType)
    {
        //   Update Virtual SC Location Aware settings. 
        $rc = $this->UpdateVirtualSCLocationAware(array("vscName" => $vscName, "groupName" => $groupName, "contentType" => $contentType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCLocationAware()
//
    function soapGetVirtualSCLocationAware($vscName)
    {
        //   Get Virtual SC Location Aware settings. 
        $rc = $this->GetVirtualSCLocationAware(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCIPFiltersState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateVirtualSCIPFiltersState($vscName, $state)
    {
        //   Update Virtual SC IP Filters state. 
        $rc = $this->UpdateVirtualSCIPFiltersState(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCIPFiltersState()
//
    function soapGetVirtualSCIPFiltersState($vscName)
    {
        //   Get Virtual SC IP Filters state. 
        $rc = $this->GetVirtualSCIPFiltersState(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddVirtualSCIPFilter()
//
    function soapAddVirtualSCIPFilter($vscName, $ipAddress, $ipMask)
    {
        //   Add Virtual SC IP filters. 
        $rc = $this->AddVirtualSCIPFilter(array("vscName" => $vscName, "ipAddress" => $ipAddress, "ipMask" => $ipMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCIPFilters()
//
    function soapGetVirtualSCIPFilters($vscName)
    {
        //   Get Virtual SC IP filters. 
        $rc = $this->GetVirtualSCIPFilters(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteVirtualSCIPFilter()
//
    function soapDeleteVirtualSCIPFilter($vscName, $ipAddress, $ipMask)
    {
        //   Delete Virtual SC IP filter. 
        $rc = $this->DeleteVirtualSCIPFilter(array("vscName" => $vscName, "ipAddress" => $ipAddress, "ipMask" => $ipMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllVirtualSCIPFilters()
//
    function soapDeleteAllVirtualSCIPFilters($vscName)
    {
        //   Delete all IP filters for Virtual SC. 
        $rc = $this->DeleteAllVirtualSCIPFilters(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCMACFiltersMode()
//
    function soapUpdateVirtualSCMACFiltersMode($vscName, $state, $allow)
    {
        //   Update Virtual SC MAC filters mode settings. 
        $rc = $this->UpdateVirtualSCMACFiltersMode(array("vscName" => $vscName, "state" => $state, "allow" => $allow));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCMACFiltersMode()
//
    function soapGetVirtualSCMACFiltersMode($vscName)
    {
        //   Get Virtual SC MAC filters mode settings. 
        $rc = $this->GetVirtualSCMACFiltersMode(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddVirtualSCMACFilter()
//
    function soapAddVirtualSCMACFilter($vscName, $macAddr)
    {
        //   Add MAC filter to Virtual SC. 
        $rc = $this->AddVirtualSCMACFilter(array("vscName" => $vscName, "macAddr" => $macAddr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCMACFilters()
//
    function soapGetVirtualSCMACFilters($vscName)
    {
        //   Get Virtual SC MAC filters. 
        $rc = $this->GetVirtualSCMACFilters(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteVirtualSCMACFilter()
//
    function soapDeleteVirtualSCMACFilter($vscName, $macAddr)
    {
        //   Delete MAC filter for Virtual SC. 
        $rc = $this->DeleteVirtualSCMACFilter(array("vscName" => $vscName, "macAddr" => $macAddr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllVirtualSCMACFilters()
//
    function soapDeleteAllVirtualSCMACFilters($vscName)
    {
        //   Delete all MAC filters for Virtual SC. 
        $rc = $this->DeleteAllVirtualSCMACFilters(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCMACBasedAuth()
//
    function soapUpdateVirtualSCMACBasedAuth($vscName, $state, $localAuthState, $radiusAuthState, $authenticationRadiusName, $radiusAccountingState, $accountingRadiusName)
    {
        //   Update Virtual SC MAC-based authentication settings. 
        $rc = $this->UpdateVirtualSCMACBasedAuth(array("vscName" => $vscName, "state" => $state, "localAuthState" => $localAuthState, "radiusAuthState" => $radiusAuthState, "authenticationRadiusName" => $authenticationRadiusName, "radiusAccountingState" => $radiusAccountingState, "accountingRadiusName" => $accountingRadiusName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCMACBasedAuth()
//
    function soapGetVirtualSCMACBasedAuth($vscName)
    {
        //   Get Virtual SC MAC based authentication settings. 
        $rc = $this->GetVirtualSCMACBasedAuth(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCWEP()
//
    function soapUpdateVirtualSCWEP($vscName, $key1, $key2, $key3, $key4, $transmissionKey, $format)
    {
        //   Update Virtual SC WEP settings. 
        $rc = $this->UpdateVirtualSCWEP(array("vscName" => $vscName, "key1" => $key1, "key2" => $key2, "key3" => $key3, "key4" => $key4, "transmissionKey" => $transmissionKey, "format" => $format));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCWEP()
//
    function soapGetVirtualSCWEP($vscName)
    {
        //   Get Virtual SC WEP settings. 
        $rc = $this->GetVirtualSCWEP(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCSecurity()
//
    function soapUpdateVirtualSCSecurity($vscName, $wirelessProtection, $authenticationState, $localAuthenticationState, $activeDirectoryAuthenticationState, $radiusAuthenticationState, $radiusAuthenticationServer, $radiusAccountingState, $radiusAccountingServer)
    {
        //   Update Virtual SC security settings. 
        $rc = $this->UpdateVirtualSCSecurity(array("vscName" => $vscName, "wirelessProtection" => $wirelessProtection, "authenticationState" => $authenticationState, "localAuthenticationState" => $localAuthenticationState, "activeDirectoryAuthenticationState" => $activeDirectoryAuthenticationState, "radiusAuthenticationState" => $radiusAuthenticationState, "radiusAuthenticationServer" => $radiusAuthenticationServer, "radiusAccountingState" => $radiusAccountingState, "radiusAccountingServer" => $radiusAccountingServer));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCSecurity()
//
    function soapGetVirtualSCSecurity($vscName)
    {
        //   Get Virtual SC security settings. 
        $rc = $this->GetVirtualSCSecurity(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCPSK()
//
    function soapUpdateVirtualSCPSK($vscName, $psk)
    {
        //   Update Virtual SC pre-shared key. 
        $rc = $this->UpdateVirtualSCPSK(array("vscName" => $vscName, "psk" => $psk));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCPSK()
//
    function soapGetVirtualSCPSK($vscName)
    {
        //   Get Virtual SC pre-shared key. 
        $rc = $this->GetVirtualSCPSK(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCWPAAtMSC()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapUpdateVirtualSCWPAAtMSC($vscName, $wpaAtMSCState)
    {
        //   Update Virtual SC WPA at MSC settings. 
        $rc = $this->UpdateVirtualSCWPAAtMSC(array("vscName" => $vscName, "wpaAtMSCState" => $wpaAtMSCState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCWPAAtMSC()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetVirtualSCWPAAtMSC($vscName)
    {
        //   Get Virtual SC WPA at MSC settings. 
        $rc = $this->GetVirtualSCWPAAtMSC(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCSSID()
//
    function soapUpdateVirtualSCSSID($vscName, $ssid)
    {
        //   Update Virtual SC SSID. 
        $rc = $this->UpdateVirtualSCSSID(array("vscName" => $vscName, "ssid" => $ssid));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCSSID()
//
    function soapGetVirtualSCSSID($vscName)
    {
        //   Get Virtual SC SSID settings. 
        $rc = $this->GetVirtualSCSSID(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCBeacon()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateVirtualSCBeacon($vscName, $dtimCount, $txPowerAdvertiseState)
    {
        //   Update Virtual SC Beacon settings. 
        $rc = $this->UpdateVirtualSCBeacon(array("vscName" => $vscName, "dtimCount" => $dtimCount, "txPowerAdvertiseState" => $txPowerAdvertiseState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCBeacon()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetVirtualSCBeacon($vscName)
    {
        //   Get Virtual SC Beacon settings. 
        $rc = $this->GetVirtualSCBeacon(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCBroadcastFiltering()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateVirtualSCBroadcastFiltering($vscName, $state)
    {
        //   Update Virtual SC broadcast filtering state. 
        $rc = $this->UpdateVirtualSCBroadcastFiltering(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCBroadcastFiltering()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetVirtualSCBroadcastFiltering($vscName)
    {
        //   Get Virtual SC broadcast filtering state. 
        $rc = $this->GetVirtualSCBroadcastFiltering(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCBandSteering()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateVirtualSCBandSteering($vscName, $state)
    {
        //   Update Virtual SC band steering state. 
        $rc = $this->UpdateVirtualSCBandSteering(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCBandSteering()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetVirtualSCBandSteering($vscName)
    {
        //   Get Virtual SC band steering state. 
        $rc = $this->GetVirtualSCBandSteering(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCDataRate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapUpdateVirtualSCDataRate($vscName, $minimumRate, $maximumRate)
    {
        //   Update Virtual SC data rate. 
        $rc = $this->UpdateVirtualSCDataRate(array("vscName" => $vscName, "minimumRate" => $minimumRate, "maximumRate" => $maximumRate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCDataRate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetVirtualSCDataRate($vscName)
    {
        //   Get Virtual SC data rate. 
        $rc = $this->GetVirtualSCDataRate(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCBroadcast()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateVirtualSCBroadcast($vscName, $state)
    {
        //   Update Virtual SC broadcast setting. 
        $rc = $this->UpdateVirtualSCBroadcast(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCBroadcast()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetVirtualSCBroadcast($vscName)
    {
        //   Get Virtual SC broadcast setting. 
        $rc = $this->GetVirtualSCBroadcast(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCMaxAssociation()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateVirtualSCMaxAssociation($vscName, $maxAssociation)
    {
        //   Update Virtual SC maximum number of wireless associations. 
        $rc = $this->UpdateVirtualSCMaxAssociation(array("vscName" => $vscName, "maxAssociation" => $maxAssociation));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCMaxAssociation()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetVirtualSCMaxAssociation($vscName)
    {
        //   Get Virtual SC maximum number of wireless associations. 
        $rc = $this->GetVirtualSCMaxAssociation(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCRadioState()
//
// WARNING: function not supported by following board types:
// CN10xx.
    function soapUpdateVirtualSCRadioState($vscName, $state)
    {
        //   Update Virtual SC radio state setting. 
        $rc = $this->UpdateVirtualSCRadioState(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCRadioState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx.
    function soapGetVirtualSCRadioState($vscName)
    {
        //   Get Virtual SC radio state setting. 
        $rc = $this->GetVirtualSCRadioState(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCPublicForwarding()
//
    function soapUpdateVirtualSCPublicForwarding($vscName, $forwardingMode)
    {
        //   Update Virtual SC public forwarding setting. 
        $rc = $this->UpdateVirtualSCPublicForwarding(array("vscName" => $vscName, "forwardingMode" => $forwardingMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCPublicForwarding()
//
    function soapGetVirtualSCPublicForwarding($vscName)
    {
        //   Get Virtual SC public forwarding settings. 
        $rc = $this->GetVirtualSCPublicForwarding(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCWirelessToLANBridging()
//
    function soapUpdateVirtualSCWirelessToLANBridging($vscName, $state)
    {
        //   Update Virtual SC wireless to LAN bridging settings. 
        $rc = $this->UpdateVirtualSCWirelessToLANBridging(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCWirelessToLANBridging()
//
    function soapGetVirtualSCWirelessToLANBridging($vscName)
    {
        //   Get Virtual SC Wireless to LAN bridging settings. 
        $rc = $this->GetVirtualSCWirelessToLANBridging(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCHTMLRedirect()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCHTMLRedirect($vscName, $state)
    {
        //   Update Virtual SC users "HTML redirection to login page" setting. 
        $rc = $this->UpdateVirtualSCHTMLRedirect(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCHTMLRedirect()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCHTMLRedirect($vscName)
    {
        //   Get Virtual SC users "HTML redirection to login page" setting. 
        $rc = $this->GetVirtualSCHTMLRedirect(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCAccessControl()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCAccessControl($vscName, $state)
    {
        //   Update Virtual SC access-control settings. 
        $rc = $this->UpdateVirtualSCAccessControl(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCAccessControl()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCAccessControl($vscName)
    {
        //   Get Virtual SC access-control setting. 
        $rc = $this->GetVirtualSCAccessControl(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCUseForAuthentication()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateVirtualSCUseForAuthentication($vscName, $state)
    {
        //   Update Virtual SC "use for authentication" state. 
        $rc = $this->UpdateVirtualSCUseForAuthentication(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCUseForAuthentication()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetVirtualSCUseForAuthentication($vscName)
    {
        //   Get Virtual SC "use for authentication" state. 
        $rc = $this->GetVirtualSCUseForAuthentication(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCUseAccessController()
//
    function soapUpdateVirtualSCUseAccessController($vscName, $state)
    {
        //   Update Virtual SC "use access controller" setting. 
        $rc = $this->UpdateVirtualSCUseAccessController(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCUseAccessController()
//
    function soapGetVirtualSCUseAccessController($vscName)
    {
        //   Get Virtual SC "use access controller" setting. 
        $rc = $this->GetVirtualSCUseAccessController(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCHTMLAuthentication()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCHTMLAuthentication($vscName, $htmlState, $localHTMLState, $radiusHTMLState, $authenticationRadiusName, $authenticationTimeout, $radiusAccountingState, $accountingRadiusName, $activeDirectoryHTMLState)
    {
        //   Update Virtual SC HTML authentication settings. 
        $rc = $this->UpdateVirtualSCHTMLAuthentication(array("vscName" => $vscName, "htmlState" => $htmlState, "localHTMLState" => $localHTMLState, "radiusHTMLState" => $radiusHTMLState, "authenticationRadiusName" => $authenticationRadiusName, "authenticationTimeout" => $authenticationTimeout, "radiusAccountingState" => $radiusAccountingState, "accountingRadiusName" => $accountingRadiusName, "activeDirectoryHTMLState" => $activeDirectoryHTMLState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCHTMLAuthentication()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCHTMLAuthentication($vscName)
    {
        //   Get Virtual SC HTML authentication settings. 
        $rc = $this->GetVirtualSCHTMLAuthentication(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCVPNAuthentication()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateVirtualSCVPNAuthentication($vscName, $vpnState, $localVPNState, $radiusVPNState, $authenticationRadiusName, $authenticationTimeout, $radiusAccountingState, $accountingRadiusName, $activeDirectoryVPNState)
    {
        //   Update Virtual SC VPN authentication settings. 
        $rc = $this->UpdateVirtualSCVPNAuthentication(array("vscName" => $vscName, "vpnState" => $vpnState, "localVPNState" => $localVPNState, "radiusVPNState" => $radiusVPNState, "authenticationRadiusName" => $authenticationRadiusName, "authenticationTimeout" => $authenticationTimeout, "radiusAccountingState" => $radiusAccountingState, "accountingRadiusName" => $accountingRadiusName, "activeDirectoryVPNState" => $activeDirectoryVPNState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCVPNAuthentication()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetVirtualSCVPNAuthentication($vscName)
    {
        //   Get Virtual SC VPN authentication settings. 
        $rc = $this->GetVirtualSCVPNAuthentication(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCWirelessSecurityFilterMode()
//
    function soapUpdateVirtualSCWirelessSecurityFilterMode($vscName, $state, $mode)
    {
        //   Update Virtual SC Wireless Security Filter mode. 
        $rc = $this->UpdateVirtualSCWirelessSecurityFilterMode(array("vscName" => $vscName, "state" => $state, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCWirelessSecurityFilterMode()
//
    function soapGetVirtualSCWirelessSecurityFilterMode($vscName)
    {
        //   Get Virtual SC Wireless Security Filter mode. 
        $rc = $this->GetVirtualSCWirelessSecurityFilterMode(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCWirelessSecurityFilterMAC()
//
    function soapUpdateVirtualSCWirelessSecurityFilterMAC($vscName, $macAddr)
    {
        //   Update Virtual SC wireless MAC security filter settings. 
        $rc = $this->UpdateVirtualSCWirelessSecurityFilterMAC(array("vscName" => $vscName, "macAddr" => $macAddr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCWirelessSecurityFilterMAC()
//
    function soapGetVirtualSCWirelessSecurityFilterMAC($vscName)
    {
        //   Get Virtual SC Wireless MAC Security Filter settings. 
        $rc = $this->GetVirtualSCWirelessSecurityFilterMAC(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCWirelessSecurityFilterCustom()
//
    function soapUpdateVirtualSCWirelessSecurityFilterCustom($vscName, $outFilter, $inFilter)
    {
        //   Update Virtual SC Wireless Custom Security Filters. 
        $rc = $this->UpdateVirtualSCWirelessSecurityFilterCustom(array("vscName" => $vscName, "outFilter" => $outFilter, "inFilter" => $inFilter));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCWirelessSecurityFilterCustom()
//
    function soapGetVirtualSCWirelessSecurityFilterCustom($vscName)
    {
        //   Get Virtual SC Wireless Custom Security filters. 
        $rc = $this->GetVirtualSCWirelessSecurityFilterCustom(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddVirtualSCIngressMapping()
//
    function soapAddVirtualSCIngressMapping($vscName, $interfaceName)
    {
        //   Add Virtual SC ingress mapping interface. This function is disabled for teaming.
        $rc = $this->AddVirtualSCIngressMapping(array("vscName" => $vscName, "interfaceName" => $interfaceName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteVirtualSCIngressMapping()
//
    function soapDeleteVirtualSCIngressMapping($vscName, $interfaceName)
    {
        //   Delete Virtual SC ingress mapping interface. This function is disabled for teaming.
        $rc = $this->DeleteVirtualSCIngressMapping(array("vscName" => $vscName, "interfaceName" => $interfaceName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllVirtualSCIngressMappings()
//
    function soapDeleteAllVirtualSCIngressMappings($vscName)
    {
        //   Delete all Virtual SC ingress mapping interfaces. This function is disabled for teaming.
        $rc = $this->DeleteAllVirtualSCIngressMappings(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCIngressMapping()
//
    function soapGetVirtualSCIngressMapping($vscName)
    {
        //   Get Virtual SC list of ingress mapping interfaces. This function is disabled for teaming.
        $rc = $this->GetVirtualSCIngressMapping(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCEgressMapping()
//
    function soapUpdateVirtualSCEgressMapping($vscName, $defaultInterface, $interceptedInterface, $authenticatedInterface)
    {
        //   Update Virtual SC egress mapping interface. 
        $rc = $this->UpdateVirtualSCEgressMapping(array("vscName" => $vscName, "defaultInterface" => $defaultInterface, "interceptedInterface" => $interceptedInterface, "authenticatedInterface" => $authenticatedInterface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCEgressMapping()
//
    function soapGetVirtualSCEgressMapping($vscName)
    {
        //   Get Virtual SC egress mapping interface. 
        $rc = $this->GetVirtualSCEgressMapping(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCRealmState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCRealmState($vscName, $realmAuthenticationState, $realmAccountingState)
    {
        //   Update Virtual SC REALM state and REALM accounting state. 
        $rc = $this->UpdateVirtualSCRealmState(array("vscName" => $vscName, "realmAuthenticationState" => $realmAuthenticationState, "realmAccountingState" => $realmAccountingState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCRealmState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCRealmState($vscName)
    {
        //   Get Virtual SC REALM state. 
        $rc = $this->GetVirtualSCRealmState(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCIdentityStationsByIPOnlyState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCIdentityStationsByIPOnlyState($vscName, $state)
    {
        //   Update Virtual SC "Ignore stations based on IP address only" setting. 
        $rc = $this->UpdateVirtualSCIdentityStationsByIPOnlyState(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCIdentityStationsByIPOnlyState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCIdentityStationsByIPOnlyState($vscName)
    {
        //   Get Virtual SC "Ignore stations based on IP address only" setting. 
        $rc = $this->GetVirtualSCIdentityStationsByIPOnlyState(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCAccessControlLocalNASId()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateVirtualSCAccessControlLocalNASId($vscName, $state, $nasId)
    {
        //   Update Virtual SC Local NAS Id setting. 
        $rc = $this->UpdateVirtualSCAccessControlLocalNASId(array("vscName" => $vscName, "state" => $state, "nasId" => $nasId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCAccessControlLocalNASId()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetVirtualSCAccessControlLocalNASId($vscName)
    {
        //   Get Virtual SC Local NAS Id setting. 
        $rc = $this->GetVirtualSCAccessControlLocalNASId(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddVirtualSCLocalUser()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapAddVirtualSCLocalUser($vscName, $username, $password, $idleTimeout, $sessionTimeout, $maxUserConnections)
    {
        //   Add local user. 
        $rc = $this->AddVirtualSCLocalUser(array("vscName" => $vscName, "username" => $username, "password" => $password, "idleTimeout" => $idleTimeout, "sessionTimeout" => $sessionTimeout, "maxUserConnections" => $maxUserConnections));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCLocalUser()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateVirtualSCLocalUser($vscName, $username, $password, $idleTimeout, $sessionTimeout, $maxUserConnections)
    {
        //   Update local user settings. 
        $rc = $this->UpdateVirtualSCLocalUser(array("vscName" => $vscName, "username" => $username, "password" => $password, "idleTimeout" => $idleTimeout, "sessionTimeout" => $sessionTimeout, "maxUserConnections" => $maxUserConnections));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteVirtualSCLocalUser()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapDeleteVirtualSCLocalUser($vscName, $username)
    {
        //   Delete local user. 
        $rc = $this->DeleteVirtualSCLocalUser(array("vscName" => $vscName, "username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCLocalUser()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetVirtualSCLocalUser($vscName, $username)
    {
        //   Get local user settings. 
        $rc = $this->GetVirtualSCLocalUser(array("vscName" => $vscName, "username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCLocalUsersList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetVirtualSCLocalUsersList($vscName)
    {
        //   Get list of existing local users 
        $rc = $this->GetVirtualSCLocalUsersList(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteLogoutVirtualSCLocalUser()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapExecuteLogoutVirtualSCLocalUser($vscName, $username)
    {
        //   Logout a local user 
        $rc = $this->ExecuteLogoutVirtualSCLocalUser(array("vscName" => $vscName, "username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddRADIUSProfile()
//
    function soapAddRADIUSProfile($vscName, $radiusName, $retryInterval, $authenticationPort, $accountingPort, $authenticationMethod, $nasId, $primaryServerFirstState, $primaryServerAddr, $secret, $accountingRetryTimeoutState, $accountingRetryTimeout, $useMessageAuthenticator, $forceNasPortToVLANID)
    {
        //   Add RADIUS profile. 
        $rc = $this->AddRADIUSProfile(array("vscName" => $vscName, "radiusName" => $radiusName, "retryInterval" => $retryInterval, "authenticationPort" => $authenticationPort, "accountingPort" => $accountingPort, "authenticationMethod" => $authenticationMethod, "nasId" => $nasId, "primaryServerFirstState" => $primaryServerFirstState, "primaryServerAddr" => $primaryServerAddr, "secret" => $secret, "accountingRetryTimeoutState" => $accountingRetryTimeoutState, "accountingRetryTimeout" => $accountingRetryTimeout, "useMessageAuthenticator" => $useMessageAuthenticator, "forceNasPortToVLANID" => $forceNasPortToVLANID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSProfile()
//
    function soapUpdateRADIUSProfile($vscName, $radiusName, $retryInterval, $authenticationPort, $accountingPort, $authenticationMethod, $nasId, $primaryServerFirstState, $primaryServerAddr, $secret, $accountingRetryTimeoutState, $accountingRetryTimeout, $useMessageAuthenticator, $forceNasPortToVLANID)
    {
        //   Update a RADIUS profile. 
        $rc = $this->UpdateRADIUSProfile(array("vscName" => $vscName, "radiusName" => $radiusName, "retryInterval" => $retryInterval, "authenticationPort" => $authenticationPort, "accountingPort" => $accountingPort, "authenticationMethod" => $authenticationMethod, "nasId" => $nasId, "primaryServerFirstState" => $primaryServerFirstState, "primaryServerAddr" => $primaryServerAddr, "secret" => $secret, "accountingRetryTimeoutState" => $accountingRetryTimeoutState, "accountingRetryTimeout" => $accountingRetryTimeout, "useMessageAuthenticator" => $useMessageAuthenticator, "forceNasPortToVLANID" => $forceNasPortToVLANID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSProfileName()
//
    function soapUpdateRADIUSProfileName($vscName, $oldRadiusProfileName, $newRadiusProfileName)
    {
        //   Update a RADIUS profile name. 
        $rc = $this->UpdateRADIUSProfileName(array("vscName" => $vscName, "oldRadiusProfileName" => $oldRadiusProfileName, "newRadiusProfileName" => $newRadiusProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteRADIUSProfile()
//
    function soapDeleteRADIUSProfile($vscName, $radiusName)
    {
        //   Delete a RADIUS profile. 
        $rc = $this->DeleteRADIUSProfile(array("vscName" => $vscName, "radiusName" => $radiusName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllRADIUSProfiles()
//
    function soapDeleteAllRADIUSProfiles($vscName)
    {
        //   Delete all RADIUS profiles. 
        $rc = $this->DeleteAllRADIUSProfiles(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllRADIUSRealms()
//
    function soapDeleteAllRADIUSRealms($vscName)
    {
        //   Delete all RADIUS realms. 
        $rc = $this->DeleteAllRADIUSRealms(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSProfile()
//
    function soapGetRADIUSProfile($vscName, $radiusName)
    {
        //   Get RADIUS profile settings. 
        $rc = $this->GetRADIUSProfile(array("vscName" => $vscName, "radiusName" => $radiusName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSProfileUniqueId()
//
    function soapGetRADIUSProfileUniqueId($vscName, $radiusName)
    {
        //   Retreive the RADIUS Profile's unique id. 
        $rc = $this->GetRADIUSProfileUniqueId(array("vscName" => $vscName, "radiusName" => $radiusName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSProfileList()
//
    function soapGetRADIUSProfileList($vscName)
    {
        //  Get the RADIUS profile List.
        $rc = $this->GetRADIUSProfileList(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSProfileServer()
//
    function soapUpdateRADIUSProfileServer($vscName, $radiusName, $serverId, $serverAddr, $secret)
    {
        //   Update RADIUS server in a RADIUS profile. 
        $rc = $this->UpdateRADIUSProfileServer(array("vscName" => $vscName, "radiusName" => $radiusName, "serverId" => $serverId, "serverAddr" => $serverAddr, "secret" => $secret));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSProfileServer()
//
    function soapGetRADIUSProfileServer($vscName, $radiusName, $serverId)
    {
        //   Get RADIUS server settings in a RADIUS profile. 
        $rc = $this->GetRADIUSProfileServer(array("vscName" => $vscName, "radiusName" => $radiusName, "serverId" => $serverId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSServerAllowedAuthenticationMethod()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateRADIUSServerAllowedAuthenticationMethod($authenticationMethod, $state)
    {
        //  Update RADIUS Server Allowed Authentication Methods settings.
        $rc = $this->UpdateRADIUSServerAllowedAuthenticationMethod(array("authenticationMethod" => $authenticationMethod, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerAllowedAuthenticationMethod()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerAllowedAuthenticationMethod($authenticationMethod)
    {
        //   Get RADIUS Server Allowed Authentication Method state. You can't use authentication method "All" in this function. 
        $rc = $this->GetRADIUSServerAllowedAuthenticationMethod(array("authenticationMethod" => $authenticationMethod));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSServerProxyDetectSSIDFromNASIdState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateRADIUSServerProxyDetectSSIDFromNASIdState($state)
    {
        //  Update the SSID detection from NAS Id state for the RADIUS Server.
        $rc = $this->UpdateRADIUSServerProxyDetectSSIDFromNASIdState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerProxyDetectSSIDFromNASIdState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerProxyDetectSSIDFromNASIdState()
    {
        //  Get the SSID detection from NAS Id state for the RADIUS Server.
        $rc = $this->GetRADIUSServerProxyDetectSSIDFromNASIdState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSServerProxyAccountingSessionNumber()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateRADIUSServerProxyAccountingSessionNumber($accountingSessionNumber)
    {
        //  Update the accounting session number for the RADIUS Server.
        $rc = $this->UpdateRADIUSServerProxyAccountingSessionNumber(array("accountingSessionNumber" => $accountingSessionNumber));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerProxyAccountingSessionNumber()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerProxyAccountingSessionNumber()
    {
        //  Get the accounting session number for the RADIUS Server.
        $rc = $this->GetRADIUSServerProxyAccountingSessionNumber(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSServerClientAuthorizationState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateRADIUSServerClientAuthorizationState($state)
    {
        //  Update the client authorization state for the RADIUS Server.
        $rc = $this->UpdateRADIUSServerClientAuthorizationState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerClientAuthorizationState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerClientAuthorizationState()
    {
        //  Get the client authorization state for the RADIUS Server.
        $rc = $this->GetRADIUSServerClientAuthorizationState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddRADIUSServerClientAuthorization()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddRADIUSServerClientAuthorization($ipAddress, $ipNetmask, $sharedSecret)
    {
        //  Add an authirization for a client IP in the RADIUS Server.
        $rc = $this->AddRADIUSServerClientAuthorization(array("ipAddress" => $ipAddress, "ipNetmask" => $ipNetmask, "sharedSecret" => $sharedSecret));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteRADIUSServerClientAuthorization()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteRADIUSServerClientAuthorization($ipAddress, $ipNetmask)
    {
        //  Delete an authirization for a client IP in the RADIUS Server.
        $rc = $this->DeleteRADIUSServerClientAuthorization(array("ipAddress" => $ipAddress, "ipNetmask" => $ipNetmask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllRADIUSServerClientAuthorization()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllRADIUSServerClientAuthorization()
    {
        //  Delete all client authirization in the RADIUS Server.
        $rc = $this->DeleteAllRADIUSServerClientAuthorization(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerClientAuthorizationList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerClientAuthorizationList()
    {
        //  Get the RADIUS Server Client Authirization List.
        $rc = $this->GetRADIUSServerClientAuthorizationList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSServerDefaultSecretState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateRADIUSServerDefaultSecretState($state)
    {
        //  Update the default secret state for the RADIUS Server.
        $rc = $this->UpdateRADIUSServerDefaultSecretState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerDefaultSecretState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerDefaultSecretState()
    {
        //  Get the default secret state for the RADIUS Server.
        $rc = $this->GetRADIUSServerDefaultSecretState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSServerDefaultSecret()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateRADIUSServerDefaultSecret($sharedSecret)
    {
        //  Update the default shared secret used by the RADIUS Server. This function is functionaly equivalent to UpdateAccessControlledSecret.
        $rc = $this->UpdateRADIUSServerDefaultSecret(array("sharedSecret" => $sharedSecret));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerDefaultSecret()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerDefaultSecret()
    {
        //   Get the default shared secret used by the RADIUS Server. This function is funcionaly equivalent to GetAccessControllerSecret. 
        $rc = $this->GetRADIUSServerDefaultSecret(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllSubsection()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllSubsection($subsectionType)
    {
        //   Delete all subsections of a given type. Note: when using "User_Account", this function also logs them out. 
        $rc = $this->DeleteAllSubsection(array("subsectionType" => $subsectionType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddAccountProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddAccountProfile($profileName)
    {
        //  Add a new account profile.
        $rc = $this->AddAccountProfile(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAccountProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAccountProfile($profileName)
    {
        //  Delete a account profile.
        $rc = $this->DeleteAccountProfile(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllAccountProfiles()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllAccountProfiles()
    {
        //  Delete all account profiles.
        $rc = $this->DeleteAllAccountProfiles(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccountProfileName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateAccountProfileName($oldName, $newName)
    {
        //  Rename an account profile.
        $rc = $this->UpdateAccountProfileName(array("oldName" => $oldName, "newName" => $newName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileUniqueId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileUniqueId($profileName)
    {
        //  Get a account profile unique id.
        $rc = $this->GetAccountProfileUniqueId(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileList()
    {
        //  Get the account profile List.
        $rc = $this->GetAccountProfileList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccountProfileAccessControlState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateAccountProfileAccessControlState($profileName, $accessControlled)
    {
        //  Update the account profile access control state.
        $rc = $this->UpdateAccountProfileAccessControlState(array("profileName" => $profileName, "accessControlled" => $accessControlled));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileAccessControlState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileAccessControlState($profileName)
    {
        //  Get the account profile access control state.
        $rc = $this->GetAccountProfileAccessControlState(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccountProfileBooleanAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateAccountProfileBooleanAttribute($profileName, $attribute, $presence, $booleanAttr)
    {
        //  Update account profile boolean attributes.
        $rc = $this->UpdateAccountProfileBooleanAttribute(array("profileName" => $profileName, "attribute" => $attribute, "presence" => $presence, "booleanAttr" => $booleanAttr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileBooleanAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileBooleanAttribute($profileName, $attribute)
    {
        //  Get account profile boolean attribute.
        $rc = $this->GetAccountProfileBooleanAttribute(array("profileName" => $profileName, "attribute" => $attribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccountProfileIntAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateAccountProfileIntAttribute($profileName, $attribute, $presence, $intAttr)
    {
        //  Update account profile interger attributes.
        $rc = $this->UpdateAccountProfileIntAttribute(array("profileName" => $profileName, "attribute" => $attribute, "presence" => $presence, "intAttr" => $intAttr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileIntAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileIntAttribute($profileName, $attribute)
    {
        //  Get account profile int attribute.
        $rc = $this->GetAccountProfileIntAttribute(array("profileName" => $profileName, "attribute" => $attribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccountProfileStringAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateAccountProfileStringAttribute($profileName, $attribute, $presence, $stringAttr)
    {
        //  Update account profile string attributes.
        $rc = $this->UpdateAccountProfileStringAttribute(array("profileName" => $profileName, "attribute" => $attribute, "presence" => $presence, "stringAttr" => $stringAttr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileStringAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileStringAttribute($profileName, $attribute)
    {
        //  Get account profile string attribute.
        $rc = $this->GetAccountProfileStringAttribute(array("profileName" => $profileName, "attribute" => $attribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccountProfileBandwidthLevelAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateAccountProfileBandwidthLevelAttribute($profileName, $attribute, $presence, $bandwidthAttr)
    {
        //  Update account profile bandwidth level attributes.
        $rc = $this->UpdateAccountProfileBandwidthLevelAttribute(array("profileName" => $profileName, "attribute" => $attribute, "presence" => $presence, "bandwidthAttr" => $bandwidthAttr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileBandwidthLevelAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileBandwidthLevelAttribute($profileName, $attribute)
    {
        //  Get account profile bandwidth level attribute.
        $rc = $this->GetAccountProfileBandwidthLevelAttribute(array("profileName" => $profileName, "attribute" => $attribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccountProfileTerminationActionAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateAccountProfileTerminationActionAttribute($profileName, $attribute, $presence, $terminationAttr)
    {
        //  Update account profile termination action attributes.
        $rc = $this->UpdateAccountProfileTerminationActionAttribute(array("profileName" => $profileName, "attribute" => $attribute, "presence" => $presence, "terminationAttr" => $terminationAttr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileTerminationActionAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileTerminationActionAttribute($profileName, $attribute)
    {
        //  Get account profile termination action attribute.
        $rc = $this->GetAccountProfileTerminationActionAttribute(array("profileName" => $profileName, "attribute" => $attribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddAccountProfileCustomAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddAccountProfileCustomAttribute($profileName, $attributeName, $vsaState, $vendorId, $attributeType, $attributeFormat, $attributeValue)
    {
        //  Add a custom attribute to a account profile.
        $rc = $this->AddAccountProfileCustomAttribute(array("profileName" => $profileName, "attributeName" => $attributeName, "vsaState" => $vsaState, "vendorId" => $vendorId, "attributeType" => $attributeType, "attributeFormat" => $attributeFormat, "attributeValue" => $attributeValue));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAccountProfileCustomAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAccountProfileCustomAttribute($profileName, $attributeName)
    {
        //  Delete a custom attribute from a account profile.
        $rc = $this->DeleteAccountProfileCustomAttribute(array("profileName" => $profileName, "attributeName" => $attributeName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllAccountProfileCustomAttributes()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllAccountProfileCustomAttributes($profileName)
    {
        //  Delete all custom attributes from a account profile.
        $rc = $this->DeleteAllAccountProfileCustomAttributes(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccountProfileCustomAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateAccountProfileCustomAttribute($profileName, $attributeName, $vsaState, $vendorId, $attributeType, $attributeFormat, $attributeValue)
    {
        //  Update a custom attribute from a account profile.
        $rc = $this->UpdateAccountProfileCustomAttribute(array("profileName" => $profileName, "attributeName" => $attributeName, "vsaState" => $vsaState, "vendorId" => $vendorId, "attributeType" => $attributeType, "attributeFormat" => $attributeFormat, "attributeValue" => $attributeValue));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccountProfileCustomAttributeList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAccountProfileCustomAttributeList($profileName)
    {
        //  Get the custom attribute list from a account profile.
        $rc = $this->GetAccountProfileCustomAttributeList(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddUserAccount()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddUserAccount($username, $password, $activeState, $accessControlledState)
    {
        //  Add a new user account.
        $rc = $this->AddUserAccount(array("username" => $username, "password" => $password, "activeState" => $activeState, "accessControlledState" => $accessControlledState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteUserAccount()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteUserAccount($username)
    {
        //  Delete an user account.
        $rc = $this->DeleteUserAccount(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllUserAccounts()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllUserAccounts()
    {
        //  Delete all user accounts.
        $rc = $this->DeleteAllUserAccounts(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccount()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccount($username, $password, $activeState, $accessControlledState)
    {
        //  Update an user account.
        $rc = $this->UpdateUserAccount(array("username" => $username, "password" => $password, "activeState" => $activeState, "accessControlledState" => $accessControlledState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccount()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccount($username)
    {
        //  Get the user account settings. This also returns all effectives attributes from account profiles (no custom attribute though).
        $rc = $this->GetUserAccount(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountUniqueId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountUniqueId($userName)
    {
        //   Retreive the User Account's unique Id. 
        $rc = $this->GetUserAccountUniqueId(array("userName" => $userName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountName($oldUsername, $newUsername)
    {
        //  Rename an user account.
        $rc = $this->UpdateUserAccountName(array("oldUsername" => $oldUsername, "newUsername" => $newUsername));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetUserAccountList()
    {
        //  Get the user account list.
        $rc = $this->GetUserAccountList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountMaxConcurrentSession()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountMaxConcurrentSession($username, $maxConcurrentSessions)
    {
        //  Update user account maximum concurrent session number. This affects Access Controlled sessions only. 
        $rc = $this->UpdateUserAccountMaxConcurrentSession(array("username" => $username, "maxConcurrentSessions" => $maxConcurrentSessions));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountMaxConcurrentSession()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountMaxConcurrentSession($username)
    {
        //   Get user account maximum concurrent session number. This affects Access Controlled sessions only. 
        $rc = $this->GetUserAccountMaxConcurrentSession(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountChargeableUserIdentity()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountChargeableUserIdentity($username, $chargeableUserIdentityPresence, $chargeableUserIdentityValue)
    {
        //  Update user account chargeable user identity.
        $rc = $this->UpdateUserAccountChargeableUserIdentity(array("username" => $username, "chargeableUserIdentityPresence" => $chargeableUserIdentityPresence, "chargeableUserIdentityValue" => $chargeableUserIdentityValue));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountChargeableUserIdentity()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountChargeableUserIdentity($username)
    {
        //  Get user account chargeable user identity.
        $rc = $this->GetUserAccountChargeableUserIdentity(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountIdleTimeout()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountIdleTimeout($username, $idleTimeoutPresence, $idleTimeoutValue)
    {
        //  Update user account idle timeout.
        $rc = $this->UpdateUserAccountIdleTimeout(array("username" => $username, "idleTimeoutPresence" => $idleTimeoutPresence, "idleTimeoutValue" => $idleTimeoutValue));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountIdleTimeout()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountIdleTimeout($username)
    {
        //  Get user account idle timeout.
        $rc = $this->GetUserAccountIdleTimeout(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountReauthenticationPeriod()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountReauthenticationPeriod($username, $reauthenticationPeriodPresence, $reauthenticationPeriodValue)
    {
        //  Update user account reauthentication period (used only on accounts that are always valid).
        $rc = $this->UpdateUserAccountReauthenticationPeriod(array("username" => $username, "reauthenticationPeriodPresence" => $reauthenticationPeriodPresence, "reauthenticationPeriodValue" => $reauthenticationPeriodValue));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountReauthenticationPeriod()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountReauthenticationPeriod($username)
    {
        //  Get user account reauthentication period (only on "always valid" accounts).
        $rc = $this->GetUserAccountReauthenticationPeriod(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountValidity()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountValidity($username, $validityMode, $validityString)
    {
        //  Update user account validity period (for AC account only). This function is disabled for teaming.
        $rc = $this->UpdateUserAccountValidity(array("username" => $username, "validityMode" => $validityMode, "validityString" => $validityString));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountValidity()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountValidity($username)
    {
        //  Get user account validity period. If the mode is Subscription_Plan or Always_Valid, the validityDataTime is set to Unix epoch. If the mode is not Subscription_Plan, planIdex is set to NONE.
        $rc = $this->GetUserAccountValidity(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountValidityMethod()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountValidityMethod($username, $validityMode)
    {
        //  Update user account validity period (for AC account only).
        $rc = $this->UpdateUserAccountValidityMethod(array("username" => $username, "validityMode" => $validityMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountValidityMethod()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountValidityMethod($username)
    {
        //  Get user account validity mode (for AC account only).
        $rc = $this->GetUserAccountValidityMethod(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountValiditySubscriptionPlan()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountValiditySubscriptionPlan($username, $planName)
    {
        //  Update user account validity subscription plan (for AC account only).
        $rc = $this->UpdateUserAccountValiditySubscriptionPlan(array("username" => $username, "planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountValiditySubscriptionPlan()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountValiditySubscriptionPlan($username)
    {
        //  Get user account validity subscription plan (for AC account only).
        $rc = $this->GetUserAccountValiditySubscriptionPlan(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountValidityDateTime()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountValidityDateTime($username, $dateTime)
    {
        //  Update user account validity time (for AC account only).
        $rc = $this->UpdateUserAccountValidityDateTime(array("username" => $username, "dateTime" => $dateTime));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountValidityDateTime()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountValidityDateTime($username)
    {
        //  Get user account validity time (for AC account only).
        $rc = $this->GetUserAccountValidityDateTime(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountEgressVLAN()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountEgressVLAN($username, $egressVLANPresence, $egressVLANId)
    {
        //  Update user account Egress VLAN (for non AC account only).
        $rc = $this->UpdateUserAccountEgressVLAN(array("username" => $username, "egressVLANPresence" => $egressVLANPresence, "egressVLANId" => $egressVLANId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountEgressVLAN()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountEgressVLAN($username)
    {
        //  Get user account Egress VLAN (for non AC Accounts).
        $rc = $this->GetUserAccountEgressVLAN(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountRemovalSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountRemovalSettings($username, $invalidRemovalState, $invalidRemovalHours, $inactiveRemovalState, $inactiveRemovalHours)
    {
        //  Update user account removal settings. This function is disabled for teaming.
        $rc = $this->UpdateUserAccountRemovalSettings(array("username" => $username, "invalidRemovalState" => $invalidRemovalState, "invalidRemovalHours" => $invalidRemovalHours, "inactiveRemovalState" => $inactiveRemovalState, "inactiveRemovalHours" => $inactiveRemovalHours));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountRemovalSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountRemovalSettings($username)
    {
        //  Get user account removal settings. This function is disabled for teaming.
        $rc = $this->GetUserAccountRemovalSettings(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountAccessControlledRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountAccessControlledRestriction($username, $accountProfileRestrictionState, $vscRestrictionState)
    {
        //  Update user account AC account profile restriction state and virtual sc restriction state.
        $rc = $this->UpdateUserAccountAccessControlledRestriction(array("username" => $username, "accountProfileRestrictionState" => $accountProfileRestrictionState, "vscRestrictionState" => $vscRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountAccessControlledAccountProfileRestriction($username, $accountProfileRestrictionState)
    {
        //  Update user account AC account profile restriction state.
        $rc = $this->UpdateUserAccountAccessControlledAccountProfileRestriction(array("username" => $username, "accountProfileRestrictionState" => $accountProfileRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountAccessControlledVirtualSCRestriction($username, $vscRestrictionState)
    {
        //  Update user account AC vsc restriction state.
        $rc = $this->UpdateUserAccountAccessControlledVirtualSCRestriction(array("username" => $username, "vscRestrictionState" => $vscRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountAccessControlledRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountAccessControlledRestriction($username)
    {
        //  Get user account AC restriction states.
        $rc = $this->GetUserAccountAccessControlledRestriction(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountNonAccessControlledRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountNonAccessControlledRestriction($username, $accountProfileRestrictionState, $vscRestrictionState)
    {
        //  Update user account non AC account profile restriction state and virtual sc restriction state.
        $rc = $this->UpdateUserAccountNonAccessControlledRestriction(array("username" => $username, "accountProfileRestrictionState" => $accountProfileRestrictionState, "vscRestrictionState" => $vscRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountNonAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountNonAccessControlledAccountProfileRestriction($username, $accountProfileRestrictionState)
    {
        //  Update user account Non AC account profile restriction state.
        $rc = $this->UpdateUserAccountNonAccessControlledAccountProfileRestriction(array("username" => $username, "accountProfileRestrictionState" => $accountProfileRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAccountNonAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateUserAccountNonAccessControlledVirtualSCRestriction($username, $vscRestrictionState)
    {
        //  Update user account Non AC vsc restriction state.
        $rc = $this->UpdateUserAccountNonAccessControlledVirtualSCRestriction(array("username" => $username, "vscRestrictionState" => $vscRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountNonAccessControlledRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountNonAccessControlledRestriction($username)
    {
        //  Get user account non AC restriction states.
        $rc = $this->GetUserAccountNonAccessControlledRestriction(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddUserAccountAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddUserAccountAccessControlledAccountProfileRestriction($username, $accountProfileName)
    {
        //  Add an AC Account Profile Restriction to an User Account.
        $rc = $this->AddUserAccountAccessControlledAccountProfileRestriction(array("username" => $username, "accountProfileName" => $accountProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteUserAccountAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteUserAccountAccessControlledAccountProfileRestriction($username, $accountProfileName)
    {
        //  Delete an AC Account Profile Restriction from an User Account.
        $rc = $this->DeleteUserAccountAccessControlledAccountProfileRestriction(array("username" => $username, "accountProfileName" => $accountProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllUserAccountAccessControlledAccountProfileRestrictions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllUserAccountAccessControlledAccountProfileRestrictions($username)
    {
        //  Delete all AC Account Profiles Restriction from an User Account.
        $rc = $this->DeleteAllUserAccountAccessControlledAccountProfileRestrictions(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountAccessControlledAccountProfileRestrictionList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountAccessControlledAccountProfileRestrictionList($username)
    {
        //  Get the AC Account Profile Restriction list from an User Account.
        $rc = $this->GetUserAccountAccessControlledAccountProfileRestrictionList(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddUserAccountAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddUserAccountAccessControlledVirtualSCRestriction($username, $vscName)
    {
        //  Add an AC VirtualSC Restriction to an User Account.
        $rc = $this->AddUserAccountAccessControlledVirtualSCRestriction(array("username" => $username, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteUserAccountAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteUserAccountAccessControlledVirtualSCRestriction($username, $vscName)
    {
        //  Delete an AC VirtualSC Restriction from an User Account.
        $rc = $this->DeleteUserAccountAccessControlledVirtualSCRestriction(array("username" => $username, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllUserAccountAccessControlledVirtualSCRestrictions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllUserAccountAccessControlledVirtualSCRestrictions($username)
    {
        //  Delete all AC VirtualSC Restrictions from an User Account.
        $rc = $this->DeleteAllUserAccountAccessControlledVirtualSCRestrictions(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountAccessControlledVirtualSCRestrictionList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountAccessControlledVirtualSCRestrictionList($username)
    {
        //  Get the AC VirtualSC Restriction list from an User Account.
        $rc = $this->GetUserAccountAccessControlledVirtualSCRestrictionList(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddUserAccountNonAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddUserAccountNonAccessControlledAccountProfileRestriction($username, $accountProfileName)
    {
        //  Add an Non AC Account Profile Restriction to an User Account.
        $rc = $this->AddUserAccountNonAccessControlledAccountProfileRestriction(array("username" => $username, "accountProfileName" => $accountProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteUserAccountNonAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteUserAccountNonAccessControlledAccountProfileRestriction($username, $accountProfileName)
    {
        //  Delete an Non AC Account Profile Restriction from an User Account.
        $rc = $this->DeleteUserAccountNonAccessControlledAccountProfileRestriction(array("username" => $username, "accountProfileName" => $accountProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllUserAccountNonAccessControlledAccountProfileRestrictions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllUserAccountNonAccessControlledAccountProfileRestrictions($username)
    {
        //  Delete all Non AC Account Profile Restrictions from an User Account.
        $rc = $this->DeleteAllUserAccountNonAccessControlledAccountProfileRestrictions(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountNonAccessControlledAccountProfileRestrictionList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountNonAccessControlledAccountProfileRestrictionList($username)
    {
        //  Get the Non AC Account Profile Restriction list from an User Account.
        $rc = $this->GetUserAccountNonAccessControlledAccountProfileRestrictionList(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddUserAccountNonAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddUserAccountNonAccessControlledVirtualSCRestriction($username, $vscName)
    {
        //  Add an Non AC VirtualSC Restriction to an User Account.
        $rc = $this->AddUserAccountNonAccessControlledVirtualSCRestriction(array("username" => $username, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteUserAccountNonAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteUserAccountNonAccessControlledVirtualSCRestriction($username, $vscName)
    {
        //  Delete an Non AC VirtualSC Restriction from an User Account.
        $rc = $this->DeleteUserAccountNonAccessControlledVirtualSCRestriction(array("username" => $username, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllUserAccountNonAccessControlledVirtualSCRestrictions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllUserAccountNonAccessControlledVirtualSCRestrictions($username)
    {
        //  Delete all Non AC VirtualSC Restrictions from an User Account.
        $rc = $this->DeleteAllUserAccountNonAccessControlledVirtualSCRestrictions(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountNonAccessControlledVirtualSCRestrictionList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountNonAccessControlledVirtualSCRestrictionList($username)
    {
        //  Get the Non AC VirtualSC Restriction list from an User Account.
        $rc = $this->GetUserAccountNonAccessControlledVirtualSCRestrictionList(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteUserAccountLogout()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapExecuteUserAccountLogout($username)
    {
        //  Logout an User Account.
        $rc = $this->ExecuteUserAccountLogout(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAccountStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetUserAccountStatus($username)
    {
        //  Get the status of an User Account.
        $rc = $this->GetUserAccountStatus(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteBackupUserAccountsPersistentData()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH,
// OPTIMIST.
    function soapExecuteBackupUserAccountsPersistentData()
    {
        //   Save data associated with user accounts. 
        $rc = $this->ExecuteBackupUserAccountsPersistentData(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteUserAccountRenewPlan()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH,
// OPTIMIST.
    function soapExecuteUserAccountRenewPlan($username)
    {
        //   Renew the user account's subscription plan. 
        $rc = $this->ExecuteUserAccountRenewPlan(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSubscriptionPlan()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddSubscriptionPlan($planName)
    {
        //  Add a new subscription plan. This function is disabled for teaming.
        $rc = $this->AddSubscriptionPlan(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSubscriptionPlan()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteSubscriptionPlan($planName)
    {
        //  Delete a subscription plan. This function is disabled for teaming.
        $rc = $this->DeleteSubscriptionPlan(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllSubscriptionPlans()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllSubscriptionPlans()
    {
        //  Delete all subscription plans. This function is disabled for teaming.
        $rc = $this->DeleteAllSubscriptionPlans(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanName($oldPlanName, $newPlanName)
    {
        //  Rename a subscription plan. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanName(array("oldPlanName" => $oldPlanName, "newPlanName" => $newPlanName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanUniqueId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanUniqueId($planName)
    {
        //  Get a subscription plan unique id. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanUniqueId(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanList()
    {
        //  Get the list of all subscription plan. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanOnlineTimeState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanOnlineTimeState($planName, $onlineTimeState)
    {
        //   Update subscription plan online time state. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanOnlineTimeState(array("planName" => $planName, "onlineTimeState" => $onlineTimeState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanValidityPeriodState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanValidityPeriodState($planName, $validityPeriodState)
    {
        //   Update subscription plan validity period state. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanValidityPeriodState(array("planName" => $planName, "validityPeriodState" => $validityPeriodState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanOnlineTimeState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanOnlineTimeState($planName)
    {
        //   Get subscription plan online time state. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanOnlineTimeState(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanValidityPeriodState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanValidityPeriodState($planName)
    {
        //   Get subscription plan validity period state. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanValidityPeriodState(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanOnlineTime()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanOnlineTime($planName, $onlineTime, $onlineTimeUnit)
    {
        //  Update subscription plan online time settings. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanOnlineTime(array("planName" => $planName, "onlineTime" => $onlineTime, "onlineTimeUnit" => $onlineTimeUnit));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanOnlineTime()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanOnlineTime($planName)
    {
        //  Get subscription plan online time settings. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanOnlineTime(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanValidityPeriodMethodState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanValidityPeriodMethodState($planName, $validityPeriodMethod, $state)
    {
        //  Update subscription plan validity period method states. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanValidityPeriodMethodState(array("planName" => $planName, "validityPeriodMethod" => $validityPeriodMethod, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanValidityPeriodMethodState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanValidityPeriodMethodState($planName, $validityPeriodMethod)
    {
        //  Get subscription plan validity period method state. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanValidityPeriodMethodState(array("planName" => $planName, "validityPeriodMethod" => $validityPeriodMethod));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanValidityPeriodFor()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanValidityPeriodFor($planName, $forTime, $forTimeUnit)
    {
        //  Update subscription plan validity period "For" field. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanValidityPeriodFor(array("planName" => $planName, "forTime" => $forTime, "forTimeUnit" => $forTimeUnit));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanValidityPeriodFor()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanValidityPeriodFor($planName)
    {
        //  Get subscription plan validity period "For" field. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanValidityPeriodFor(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanValidityPeriodBetween()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanValidityPeriodBetween($planName, $betweenStart, $betweenEnd)
    {
        //  Update subscription plan validity period "Between" field. Accounts using this subscription plan are only valid in a certain period of the day if this filed is enabled. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanValidityPeriodBetween(array("planName" => $planName, "betweenStart" => $betweenStart, "betweenEnd" => $betweenEnd));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanValidityPeriodBetween()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanValidityPeriodBetween($planName)
    {
        //  Get subscription plan validity period "Between" field. Accounts using this subscription plan are only valid in a certain period of the day if this filed is enabled. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanValidityPeriodBetween(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanValidityPeriodFrom()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanValidityPeriodFrom($planName, $fromDateTime)
    {
        //  Update subscription plan validity period "From" field. Accounts using this subscription plan are only valid from this day and time (ISO8601 format) if this filed is enabled. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanValidityPeriodFrom(array("planName" => $planName, "fromDateTime" => $fromDateTime));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanValidityPeriodFrom()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanValidityPeriodFrom($planName)
    {
        //  Get subscription plan validity period "From" field. Accounts using this subscription plan are only valid from this day and time (ISO8601 format) if this filed is enabled. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanValidityPeriodFrom(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanValidityPeriodUntil()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanValidityPeriodUntil($planName, $untilDateTime)
    {
        //  Update subscription plan validity period "Until" field. Accounts using this subscription plan are only valid until this day and time (ISO8601 format) if this filed is enabled. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanValidityPeriodUntil(array("planName" => $planName, "untilDateTime" => $untilDateTime));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanValidityPeriodUntil()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanValidityPeriodUntil($planName)
    {
        //  Get subscription plan validity period "Until" field. Accounts using this subscription plan are only valid until this day and time (ISO8601 format) if this filed is enabled. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanValidityPeriodUntil(array("planName" => $planName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanBooleanAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanBooleanAttribute($planName, $attribute, $presence, $booleanAttr)
    {
        //   Update subscription plan boolean attribute. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanBooleanAttribute(array("planName" => $planName, "attribute" => $attribute, "presence" => $presence, "booleanAttr" => $booleanAttr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanBooleanAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanBooleanAttribute($planName, $attribute)
    {
        //  Get subscription plan boolean attribute. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanBooleanAttribute(array("planName" => $planName, "attribute" => $attribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanIntAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanIntAttribute($planName, $attribute, $presence, $intAttr)
    {
        //   Update subscription plan integer attribute. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanIntAttribute(array("planName" => $planName, "attribute" => $attribute, "presence" => $presence, "intAttr" => $intAttr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanIntAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanIntAttribute($planName, $attribute)
    {
        //  Get subscription plan integer attribute. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanIntAttribute(array("planName" => $planName, "attribute" => $attribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSubscriptionPlanBandwidthLevelAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSubscriptionPlanBandwidthLevelAttribute($planName, $attribute, $presence, $bandwidthAttr)
    {
        //  Update subscription plan bandwidth level attributes. This function is disabled for teaming.
        $rc = $this->UpdateSubscriptionPlanBandwidthLevelAttribute(array("planName" => $planName, "attribute" => $attribute, "presence" => $presence, "bandwidthAttr" => $bandwidthAttr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSubscriptionPlanBandwidthLevelAttribute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetSubscriptionPlanBandwidthLevelAttribute($planName, $attribute)
    {
        //  Get subscription plan bandwidth level attribute. This function is disabled for teaming.
        $rc = $this->GetSubscriptionPlanBandwidthLevelAttribute(array("planName" => $planName, "attribute" => $attribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectorySettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectorySettings($netbiosName, $windowsDomain, $checkLDAPAttribute, $ldapAttribute)
    {
        //   Update global active directory settings. The Domain NetBios name is assumed to be the first part of the Windows Domain name. Use UpdateActiveDirectoryFullSettings if you have a custom Domain NetBios name.
        $rc = $this->UpdateActiveDirectorySettings(array("netbiosName" => $netbiosName, "windowsDomain" => $windowsDomain, "checkLDAPAttribute" => $checkLDAPAttribute, "ldapAttribute" => $ldapAttribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryFullSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryFullSettings($netbiosName, $domainNetbios, $windowsDomain, $checkLDAPAttribute, $ldapAttribute)
    {
        //   Update global active directory settings. 
        $rc = $this->UpdateActiveDirectoryFullSettings(array("netbiosName" => $netbiosName, "domainNetbios" => $domainNetbios, "windowsDomain" => $windowsDomain, "checkLDAPAttribute" => $checkLDAPAttribute, "ldapAttribute" => $ldapAttribute));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectorySettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectorySettings()
    {
        //   Get global active directory settings. Use GetActiveDirectoryFullSettings to get the complete settings.
        $rc = $this->GetActiveDirectorySettings(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryFullSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryFullSettings()
    {
        //   Get global active directory settings. 
        $rc = $this->GetActiveDirectoryFullSettings(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteActiveDirectoryJoinRealm()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapExecuteActiveDirectoryJoinRealm($username, $password)
    {
        //   Join an active directory realm. 
        $rc = $this->ExecuteActiveDirectoryJoinRealm(array("username" => $username, "password" => $password));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddActiveDirectoryGroup()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddActiveDirectoryGroup($groupName, $accessControlledState, $activeState)
    {
        //   Add an active directory group. 
        $rc = $this->AddActiveDirectoryGroup(array("groupName" => $groupName, "accessControlledState" => $accessControlledState, "activeState" => $activeState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteActiveDirectoryGroup()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteActiveDirectoryGroup($groupName)
    {
        //   Delete an active directory group. 
        $rc = $this->DeleteActiveDirectoryGroup(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllActiveDirectoryGroups()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllActiveDirectoryGroups()
    {
        //   Delete all active directory groups. 
        $rc = $this->DeleteAllActiveDirectoryGroups(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroup()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroup($groupName, $accessControlledState, $activeState)
    {
        //   Update an active directory group. 
        $rc = $this->UpdateActiveDirectoryGroup(array("groupName" => $groupName, "accessControlledState" => $accessControlledState, "activeState" => $activeState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupName($oldGroupName, $newGroupName)
    {
        //   Update an active directory group name. 
        $rc = $this->UpdateActiveDirectoryGroupName(array("oldGroupName" => $oldGroupName, "newGroupName" => $newGroupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ResetActiveDirectoryDefaultGroupNames()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapResetActiveDirectoryDefaultGroupNames()
    {
        //   Reset active directory default group names. 
        $rc = $this->ResetActiveDirectoryDefaultGroupNames(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupPushBackPriority()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupPushBackPriority($groupName)
    {
        //   Push an active directory group back in the priority list. 
        $rc = $this->UpdateActiveDirectoryGroupPushBackPriority(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupPriorityList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupPriorityList()
    {
        //   Get Active Directory Group list in piority order. 
        $rc = $this->GetActiveDirectoryGroupPriorityList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroup()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroup($groupName)
    {
        //   Get an active directory group settings. 
        $rc = $this->GetActiveDirectoryGroup(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupList()
    {
        //   Get active directory group list. 
        $rc = $this->GetActiveDirectoryGroupList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupNonAccessControlledEgressInterface()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupNonAccessControlledEgressInterface($groupName, $egressVLANPresence, $egressVLANId)
    {
        //   Update active directory group egress interface settings (for Non Access Controlled Group). 
        $rc = $this->UpdateActiveDirectoryGroupNonAccessControlledEgressInterface(array("groupName" => $groupName, "egressVLANPresence" => $egressVLANPresence, "egressVLANId" => $egressVLANId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupNonAccessControlledEgressInterface()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupNonAccessControlledEgressInterface($groupName)
    {
        //   Get active directory group egress interface settings (for Non Access Controlled Group). 
        $rc = $this->GetActiveDirectoryGroupNonAccessControlledEgressInterface(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupAccessControlledRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupAccessControlledRestriction($groupName, $accountProfileRestrictionState, $virtualSCRestrictionState)
    {
        //   Update active directory group access controlled restriction states (on Account Profiles and Virtual SCs). 
        $rc = $this->UpdateActiveDirectoryGroupAccessControlledRestriction(array("groupName" => $groupName, "accountProfileRestrictionState" => $accountProfileRestrictionState, "virtualSCRestrictionState" => $virtualSCRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupAccessControlledAccountProfileRestriction($groupName, $accountProfileRestrictionState)
    {
        //   Update active directory group access controlled restriction state on Account Profiles. 
        $rc = $this->UpdateActiveDirectoryGroupAccessControlledAccountProfileRestriction(array("groupName" => $groupName, "accountProfileRestrictionState" => $accountProfileRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupAccessControlledVirtualSCRestriction($groupName, $virtualSCRestrictionState)
    {
        //   Update active directory group access controlled restriction state on Virtual SCs. 
        $rc = $this->UpdateActiveDirectoryGroupAccessControlledVirtualSCRestriction(array("groupName" => $groupName, "virtualSCRestrictionState" => $virtualSCRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupAccessControlledRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupAccessControlledRestriction($groupName)
    {
        //   Get active directory group restrictions states (for Account Profiles and Virtual SCs). 
        $rc = $this->GetActiveDirectoryGroupAccessControlledRestriction(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddActiveDirectoryGroupAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddActiveDirectoryGroupAccessControlledAccountProfileRestriction($groupName, $profileName)
    {
        //   Add an access controlled account profile restriction to an active directory group. 
        $rc = $this->AddActiveDirectoryGroupAccessControlledAccountProfileRestriction(array("groupName" => $groupName, "profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteActiveDirectoryGroupAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteActiveDirectoryGroupAccessControlledAccountProfileRestriction($groupName, $profileName)
    {
        //   Delete an access controlled account profile restriction from an active directory group. 
        $rc = $this->DeleteActiveDirectoryGroupAccessControlledAccountProfileRestriction(array("groupName" => $groupName, "profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllActiveDirectoryGroupAccessControlledAccountProfileRestrictions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllActiveDirectoryGroupAccessControlledAccountProfileRestrictions($groupName)
    {
        //   Delete all access controlled account profile restrictions from an active directory group. 
        $rc = $this->DeleteAllActiveDirectoryGroupAccessControlledAccountProfileRestrictions(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupAccessControlledAccountProfileRestrictionList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupAccessControlledAccountProfileRestrictionList($groupName)
    {
        //   Get the access controlled account profile restriction list for an active directory group. 
        $rc = $this->GetActiveDirectoryGroupAccessControlledAccountProfileRestrictionList(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddActiveDirectoryGroupAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddActiveDirectoryGroupAccessControlledVirtualSCRestriction($groupName, $vscName)
    {
        //   Add an access controlled virtual sc restriction to an active directory group. 
        $rc = $this->AddActiveDirectoryGroupAccessControlledVirtualSCRestriction(array("groupName" => $groupName, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteActiveDirectoryGroupAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteActiveDirectoryGroupAccessControlledVirtualSCRestriction($groupName, $vscName)
    {
        //   Delete an access controlled virtual sc restriction from an active directory group. 
        $rc = $this->DeleteActiveDirectoryGroupAccessControlledVirtualSCRestriction(array("groupName" => $groupName, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllActiveDirectoryGroupAccessControlledVirtualSCRestrictions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllActiveDirectoryGroupAccessControlledVirtualSCRestrictions($groupName)
    {
        //   Delete all access controlled virtual sc restrictions from an active directory group. 
        $rc = $this->DeleteAllActiveDirectoryGroupAccessControlledVirtualSCRestrictions(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupAccessControlledVirtualSCRestrictionList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupAccessControlledVirtualSCRestrictionList($groupName)
    {
        //   Get the access controlled virtual sc restriction list for an active directory group. 
        $rc = $this->GetActiveDirectoryGroupAccessControlledVirtualSCRestrictionList(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupNonAccessControlledRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupNonAccessControlledRestriction($groupName, $accountProfileRestrictionState, $virtualSCRestrictionState)
    {
        //   Update active directory group non access controlled restriction states (on Account Profiles and Virtual SCs). 
        $rc = $this->UpdateActiveDirectoryGroupNonAccessControlledRestriction(array("groupName" => $groupName, "accountProfileRestrictionState" => $accountProfileRestrictionState, "virtualSCRestrictionState" => $virtualSCRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupNonAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupNonAccessControlledAccountProfileRestriction($groupName, $accountProfileRestrictionState)
    {
        //   Update active directory group non access controlled restriction state on Account Profiles. 
        $rc = $this->UpdateActiveDirectoryGroupNonAccessControlledAccountProfileRestriction(array("groupName" => $groupName, "accountProfileRestrictionState" => $accountProfileRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActiveDirectoryGroupNonAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateActiveDirectoryGroupNonAccessControlledVirtualSCRestriction($groupName, $virtualSCRestrictionState)
    {
        //   Update active directory group non access controlled restriction state on Virtual SCs. 
        $rc = $this->UpdateActiveDirectoryGroupNonAccessControlledVirtualSCRestriction(array("groupName" => $groupName, "virtualSCRestrictionState" => $virtualSCRestrictionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupNonAccessControlledRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupNonAccessControlledRestriction($groupName)
    {
        //   Get active directory group restrictions states (for Account Profiles and Virtual SCs). 
        $rc = $this->GetActiveDirectoryGroupNonAccessControlledRestriction(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddActiveDirectoryGroupNonAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddActiveDirectoryGroupNonAccessControlledAccountProfileRestriction($groupName, $profileName)
    {
        //   Add a non access controlled account profile restriction to an active directory group. 
        $rc = $this->AddActiveDirectoryGroupNonAccessControlledAccountProfileRestriction(array("groupName" => $groupName, "profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteActiveDirectoryGroupNonAccessControlledAccountProfileRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteActiveDirectoryGroupNonAccessControlledAccountProfileRestriction($groupName, $profileName)
    {
        //   Delete a non access controlled account profile restriction from an active directory group. 
        $rc = $this->DeleteActiveDirectoryGroupNonAccessControlledAccountProfileRestriction(array("groupName" => $groupName, "profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllActiveDirectoryGroupNonAccessControlledAccountProfileRestrictions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllActiveDirectoryGroupNonAccessControlledAccountProfileRestrictions($groupName)
    {
        //   Delete all non access controlled account profile restrictions from an active directory group. 
        $rc = $this->DeleteAllActiveDirectoryGroupNonAccessControlledAccountProfileRestrictions(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupNonAccessControlledAccountProfileRestrictionList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupNonAccessControlledAccountProfileRestrictionList($groupName)
    {
        //   Get the non access controlled account profile restriction list for an active directory group. 
        $rc = $this->GetActiveDirectoryGroupNonAccessControlledAccountProfileRestrictionList(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddActiveDirectoryGroupNonAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddActiveDirectoryGroupNonAccessControlledVirtualSCRestriction($groupName, $vscName)
    {
        //   Add a non access controlled virtual sc restriction to an active directory group. 
        $rc = $this->AddActiveDirectoryGroupNonAccessControlledVirtualSCRestriction(array("groupName" => $groupName, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteActiveDirectoryGroupNonAccessControlledVirtualSCRestriction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteActiveDirectoryGroupNonAccessControlledVirtualSCRestriction($groupName, $vscName)
    {
        //   Delete a non access controlled virtual sc restriction from an active directory group. 
        $rc = $this->DeleteActiveDirectoryGroupNonAccessControlledVirtualSCRestriction(array("groupName" => $groupName, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllActiveDirectoryGroupNonAccessControlledVirtualSCRestrictions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllActiveDirectoryGroupNonAccessControlledVirtualSCRestrictions($groupName)
    {
        //   Delete all non access controlled virtual sc restrictions from an active directory group. 
        $rc = $this->DeleteAllActiveDirectoryGroupNonAccessControlledVirtualSCRestrictions(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetActiveDirectoryGroupNonAccessControlledVirtualSCRestrictionList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetActiveDirectoryGroupNonAccessControlledVirtualSCRestrictionList($groupName)
    {
        //   Get the non access controlled virtual sc restriction list for an active directory group. 
        $rc = $this->GetActiveDirectoryGroupNonAccessControlledVirtualSCRestrictionList(array("groupName" => $groupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddLocalConfigAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapAddLocalConfigAccessList($vscName, $position, $accessList)
    {
        //   Add a Access-List rule to local configurations settings. 
        $rc = $this->AddLocalConfigAccessList(array("vscName" => $vscName, "position" => $position, "accessList" => $accessList));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteLocalConfigAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapDeleteLocalConfigAccessList($vscName, $accessList)
    {
        //   Delete Access-List rule in local configuration. 
        $rc = $this->DeleteLocalConfigAccessList(array("vscName" => $vscName, "accessList" => $accessList));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetLocalConfigAccessList($vscName)
    {
        //   Get local configuration Access-List. 
        $rc = $this->GetLocalConfigAccessList(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigUseAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateLocalConfigUseAccessList($vscName, $useAccessList)
    {
        //   Update local config Access-List usage. 
        $rc = $this->UpdateLocalConfigUseAccessList(array("vscName" => $vscName, "useAccessList" => $useAccessList));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigUseAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetLocalConfigUseAccessList($vscName)
    {
        //   Get local configuration Access-List usage. 
        $rc = $this->GetLocalConfigUseAccessList(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigUseAccessListUnauth()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH.
    function soapUpdateLocalConfigUseAccessListUnauth($vscName, $useAccessList)
    {
        //   Update local config Access-List Unauth usage. 
        $rc = $this->UpdateLocalConfigUseAccessListUnauth(array("vscName" => $vscName, "useAccessList" => $useAccessList));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigUseAccessListUnauth()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH.
    function soapGetLocalConfigUseAccessListUnauth($vscName)
    {
        //   Get local configuration Access-List Unauth usage. 
        $rc = $this->GetLocalConfigUseAccessListUnauth(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigHTTPProxyUpstream()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH.
    function soapUpdateLocalConfigHTTPProxyUpstream($vscName, $httpProxyUpstream)
    {
        //   Update local config HTTP Proxy Upstream usage. 
        $rc = $this->UpdateLocalConfigHTTPProxyUpstream(array("vscName" => $vscName, "httpProxyUpstream" => $httpProxyUpstream));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigHTTPProxyUpstream()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH.
    function soapGetLocalConfigHTTPProxyUpstream($vscName)
    {
        //   Get local configuration HTTP Proxy Upstream usage. 
        $rc = $this->GetLocalConfigHTTPProxyUpstream(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigSSLCertificateURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigSSLCertificateURL($vscName, $certificate)
    {
        //   Update local config SSL certificate URL. 
        $rc = $this->UpdateLocalConfigSSLCertificateURL(array("vscName" => $vscName, "certificate" => $certificate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigSSLCertificateURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigSSLCertificateURL($vscName)
    {
        //   Get local config SSL certificate URL. 
        $rc = $this->GetLocalConfigSSLCertificateURL(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigConfigFileURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigConfigFileURL($vscName, $url)
    {
        //   Update local config configuration file URL. 
        $rc = $this->UpdateLocalConfigConfigFileURL(array("vscName" => $vscName, "url" => $url));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigConfigFileURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigConfigFileURL($vscName)
    {
        //   Get local configuration configuration file URL. 
        $rc = $this->GetLocalConfigConfigFileURL(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddLocalConfigMACAddressAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapAddLocalConfigMACAddressAccessList($vscName, $macAddress)
    {
        //   Add local config MAC address Access-List. 
        $rc = $this->AddLocalConfigMACAddressAccessList(array("vscName" => $vscName, "macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteLocalConfigMACAddressAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteLocalConfigMACAddressAccessList($vscName, $macAddress)
    {
        //   Delete local config MAC address Access-List. 
        $rc = $this->DeleteLocalConfigMACAddressAccessList(array("vscName" => $vscName, "macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigMACAddressAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetLocalConfigMACAddressAccessList($vscName)
    {
        //   Get local config MAC address Access-List. 
        $rc = $this->GetLocalConfigMACAddressAccessList(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserAccountingInterimUpdate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserAccountingInterimUpdate($vscName, $userProfile, $interimUpdate, $state)
    {
        //   Update local config user Accounting Interim Update settings. 
        $rc = $this->UpdateLocalConfigDefaultUserAccountingInterimUpdate(array("vscName" => $vscName, "userProfile" => $userProfile, "interimUpdate" => $interimUpdate, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserAccountingInterimUpdate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserAccountingInterimUpdate($vscName, $userProfile)
    {
        //   Get local config user Accounting Interim Update settings. 
        $rc = $this->GetLocalConfigDefaultUserAccountingInterimUpdate(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserQuota()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserQuota($vscName, $userProfile, $maxOutputPackets, $maxInputPackets, $maxOutputBytes, $maxInputBytes, $maxTotalPackets, $maxTotalBytes)
    {
        //   Update local config user traffic quotas. 
        $rc = $this->UpdateLocalConfigDefaultUserQuota(array("vscName" => $vscName, "userProfile" => $userProfile, "maxOutputPackets" => $maxOutputPackets, "maxInputPackets" => $maxInputPackets, "maxOutputBytes" => $maxOutputBytes, "maxInputBytes" => $maxInputBytes, "maxTotalPackets" => $maxTotalPackets, "maxTotalBytes" => $maxTotalBytes));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserQuota()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserQuota($vscName, $userProfile)
    {
        //   Get local configuration user traffic quotas. 
        $rc = $this->GetLocalConfigDefaultUserQuota(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserTimeouts()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserTimeouts($vscName, $userProfile, $idleTimeout, $sessionTimeout)
    {
        //   Update local configuration user timeouts. 
        $rc = $this->UpdateLocalConfigDefaultUserTimeouts(array("vscName" => $vscName, "userProfile" => $userProfile, "idleTimeout" => $idleTimeout, "sessionTimeout" => $sessionTimeout));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserTimeouts()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserTimeouts($vscName, $userProfile)
    {
        //   Get local config user timeouts. 
        $rc = $this->GetLocalConfigDefaultUserTimeouts(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddLocalConfigDNATServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapAddLocalConfigDNATServer($vscName, $dnatServer)
    {
        //  Add a local configuration DNAT Server. 
        $rc = $this->AddLocalConfigDNATServer(array("vscName" => $vscName, "dnatServer" => $dnatServer));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteLocalConfigDNATServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteLocalConfigDNATServer($vscName, $dnatServer)
    {
        //  Delete a local configuration DNAT Server. 
        $rc = $this->DeleteLocalConfigDNATServer(array("vscName" => $vscName, "dnatServer" => $dnatServer));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDNATServerList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetLocalConfigDNATServerList($vscName)
    {
        //  Get local configuration DNAT Server List. 
        $rc = $this->GetLocalConfigDNATServerList(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserSMTPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserSMTPServer($vscName, $userProfile, $smtpServer)
    {
        //   Update local configuration user SMTP server. 
        $rc = $this->UpdateLocalConfigDefaultUserSMTPServer(array("vscName" => $vscName, "userProfile" => $userProfile, "smtpServer" => $smtpServer));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserSMTPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserSMTPServer($vscName, $userProfile)
    {
        //   Get local configuration user SMTP server. 
        $rc = $this->GetLocalConfigDefaultUserSMTPServer(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserOneToOneNAT()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserOneToOneNAT($vscName, $userProfile, $state)
    {
        //   Update local configuration user one to one NAT settings. 
        $rc = $this->UpdateLocalConfigDefaultUserOneToOneNAT(array("vscName" => $vscName, "userProfile" => $userProfile, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserOneToOneNAT()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserOneToOneNAT($vscName, $userProfile)
    {
        //  Get local configuration user one to one NAT settings.
        $rc = $this->GetLocalConfigDefaultUserOneToOneNAT(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserUsePublicIPSubnet()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserUsePublicIPSubnet($vscName, $userProfile, $state)
    {
        //   Update local configuration user use public IP subnet setting. 
        $rc = $this->UpdateLocalConfigDefaultUserUsePublicIPSubnet(array("vscName" => $vscName, "userProfile" => $userProfile, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserUsePublicIPSubnet()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserUsePublicIPSubnet($vscName, $userProfile)
    {
        //  Get local configuration user use public IP subnet setting.
        $rc = $this->GetLocalConfigDefaultUserUsePublicIPSubnet(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigInternalPagesURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigInternalPagesURLs($vscName, $loginPageURL, $transportPageURL, $sessionPageURL, $failPageURL, $logoURL, $messagesFileURL)
    {
        //   Update local configuration URL's for internal pages. 
        $rc = $this->UpdateLocalConfigInternalPagesURLs(array("vscName" => $vscName, "loginPageURL" => $loginPageURL, "transportPageURL" => $transportPageURL, "sessionPageURL" => $sessionPageURL, "failPageURL" => $failPageURL, "logoURL" => $logoURL, "messagesFileURL" => $messagesFileURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigInternalPagesURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigInternalPagesURLs($vscName)
    {
        //   Get local configuration URL's for internal pages. 
        $rc = $this->GetLocalConfigInternalPagesURLs(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigCustomPagesURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigCustomPagesURL($vscName, $customPagesURL)
    {
        //   Update local configuration URL for custom pages. 
        $rc = $this->UpdateLocalConfigCustomPagesURL(array("vscName" => $vscName, "customPagesURL" => $customPagesURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigCustomPagesURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigCustomPagesURL($vscName)
    {
        //   Get local configuration URL for custom pages. 
        $rc = $this->GetLocalConfigCustomPagesURL(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigRedirectPageURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigRedirectPageURL($vscName, $redirectPageURL)
    {
        //   Update local configuration URL for redirect page. 
        $rc = $this->UpdateLocalConfigRedirectPageURL(array("vscName" => $vscName, "redirectPageURL" => $redirectPageURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigRedirectPageURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigRedirectPageURL($vscName)
    {
        //   Get local configuration URL's for redirect page. 
        $rc = $this->GetLocalConfigRedirectPageURL(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigExternalURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigExternalURLs($vscName, $welcomeURL, $goodbyeURL, $loginErrorURL, $loginURL)
    {
        //   Update local configuration external URL's. 
        $rc = $this->UpdateLocalConfigExternalURLs(array("vscName" => $vscName, "welcomeURL" => $welcomeURL, "goodbyeURL" => $goodbyeURL, "loginErrorURL" => $loginErrorURL, "loginURL" => $loginURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigRedirectURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigRedirectURL($vscName, $redirectURL)
    {
        //   Update local configuration REDIRECT-URL. 
        $rc = $this->UpdateLocalConfigRedirectURL(array("vscName" => $vscName, "redirectURL" => $redirectURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigExternalURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigExternalURLs($vscName)
    {
        //   Get local configuration external URL's.
        $rc = $this->GetLocalConfigExternalURLs(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigRedirectURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigRedirectURL($vscName)
    {
        //   Get local configuration REDIRECT-URL.
        $rc = $this->GetLocalConfigRedirectURL(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigNOCAuthenticationCertificateURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigNOCAuthenticationCertificateURL($vscName, $sslCertificateURL, $sslCACertificateURL)
    {
        //   Update local configuration settings for NOC SSL certificates. 
        $rc = $this->UpdateLocalConfigNOCAuthenticationCertificateURL(array("vscName" => $vscName, "sslCertificateURL" => $sslCertificateURL, "sslCACertificateURL" => $sslCACertificateURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigNOCAuthenticationCertificateURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigNOCAuthenticationCertificateURL($vscName)
    {
        //   Get local configuration NOC authentication settings. 
        $rc = $this->GetLocalConfigNOCAuthenticationCertificateURL(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigIPASSLoginURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigIPASSLoginURL($vscName, $loginURL)
    {
        //   Update local configuration IPASS login URL. 
        $rc = $this->UpdateLocalConfigIPASSLoginURL(array("vscName" => $vscName, "loginURL" => $loginURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigIPASSLoginURL()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigIPASSLoginURL($vscName)
    {
        //   Get local config default iPASS login URL. 
        $rc = $this->GetLocalConfigIPASSLoginURL(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserBandwidthLevel()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserBandwidthLevel($vscName, $userProfile, $bandwidthLevel)
    {
        //   Update local config default user bandwidth level. 
        $rc = $this->UpdateLocalConfigDefaultUserBandwidthLevel(array("vscName" => $vscName, "userProfile" => $userProfile, "bandwidthLevel" => $bandwidthLevel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserBandwidthLevel()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserBandwidthLevel($vscName, $userProfile)
    {
        //   Get local config default user bandwidth level. 
        $rc = $this->GetLocalConfigDefaultUserBandwidthLevel(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserMaxRates()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserMaxRates($vscName, $userProfile, $inputRate, $outputRate)
    {
        //   Update local config default user maximum input and output rates. 
        $rc = $this->UpdateLocalConfigDefaultUserMaxRates(array("vscName" => $vscName, "userProfile" => $userProfile, "inputRate" => $inputRate, "outputRate" => $outputRate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserMaxRates()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserMaxRates($vscName, $userProfile)
    {
        //   Get local config default user max input and output rates. 
        $rc = $this->GetLocalConfigDefaultUserMaxRates(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserUseAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserUseAccessList($vscName, $userProfile, $useListName)
    {
        //   Update local config default user use access list. 
        $rc = $this->UpdateLocalConfigDefaultUserUseAccessList(array("vscName" => $vscName, "userProfile" => $userProfile, "useListName" => $useListName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserUseAccessList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserUseAccessList($vscName, $userProfile)
    {
        //   Get local config default user use access list. 
        $rc = $this->GetLocalConfigDefaultUserUseAccessList(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDefaultUserURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDefaultUserURLs($vscName, $userProfile, $welcomeURL, $goodbyeURL)
    {
        //   Update local config default user welcome and goodbye URLs. 
        $rc = $this->UpdateLocalConfigDefaultUserURLs(array("vscName" => $vscName, "userProfile" => $userProfile, "welcomeURL" => $welcomeURL, "goodbyeURL" => $goodbyeURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDefaultUserURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDefaultUserURLs($vscName, $userProfile)
    {
        //   Get local config default user welcome and goodbye URLs. 
        $rc = $this->GetLocalConfigDefaultUserURLs(array("vscName" => $vscName, "userProfile" => $userProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigWebServerStatusURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigWebServerStatusURLs($vscName, $primaryURL, $secondaryURL)
    {
        //   Update local config primary and secondary web server status URLs. 
        $rc = $this->UpdateLocalConfigWebServerStatusURLs(array("vscName" => $vscName, "primaryURL" => $primaryURL, "secondaryURL" => $secondaryURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigWebServerStatusURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigWebServerStatusURLs($vscName)
    {
        //   Get local config primary and secondary web server status URLs. 
        $rc = $this->GetLocalConfigWebServerStatusURLs(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigDnatServerStatusURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigDnatServerStatusURLs($vscName, $primaryURL, $secondaryURL)
    {
        //   Update local config primary and secondary dnat server status URLs. 
        $rc = $this->UpdateLocalConfigDnatServerStatusURLs(array("vscName" => $vscName, "primaryURL" => $primaryURL, "secondaryURL" => $secondaryURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigDnatServerStatusURLs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigDnatServerStatusURLs($vscName)
    {
        //   Get local config primary and secondary dnat server status URLs. 
        $rc = $this->GetLocalConfigDnatServerStatusURLs(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigWispRoamingAccessProcedure()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigWispRoamingAccessProcedure($vscName, $accessProcedure)
    {
        //   Update local config wisp roaming access procedure. 
        $rc = $this->UpdateLocalConfigWispRoamingAccessProcedure(array("vscName" => $vscName, "accessProcedure" => $accessProcedure));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigWispRoamingAccessProcedure()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigWispRoamingAccessProcedure($vscName)
    {
        //   Get local config wisp roaming access procedure. 
        $rc = $this->GetLocalConfigWispRoamingAccessProcedure(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalConfigUHHIHeaderContent()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocalConfigUHHIHeaderContent($vscName, $uhhiHeader)
    {
        //   Update local config UHHI header content. 
        $rc = $this->UpdateLocalConfigUHHIHeaderContent(array("vscName" => $vscName, "uhhiHeader" => $uhhiHeader));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalConfigUHHIHeaderContent()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocalConfigUHHIHeaderContent($vscName)
    {
        //   Get local config UHHI header content. 
        $rc = $this->GetLocalConfigUHHIHeaderContent(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSystemInfo()
//
    function soapGetSystemInfo()
    {
        //   Get system information. 
        $rc = $this->GetSystemInfo(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAdminAccount()
//
    function soapUpdateAdminAccount($newUsername, $newPassword)
    {
        //   Update administrateur account settings. 
        $rc = $this->UpdateAdminAccount(array("newUsername" => $newUsername, "newPassword" => $newPassword));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateOperatorAccount()
//
    function soapUpdateOperatorAccount($newUsername, $newPassword)
    {
        //   Update web operator account settings. 
        $rc = $this->UpdateOperatorAccount(array("newUsername" => $newUsername, "newPassword" => $newPassword));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteFactoryReset()
//
    function soapExecuteFactoryReset()
    {
        //   Perform a reset fo factory defaults settings. 
        $rc = $this->ExecuteFactoryReset(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteConfigurationReset()
//
    function soapExecuteConfigurationReset()
    {
        //   Perform a reset fo factory defaults settings. 
        $rc = $this->ExecuteConfigurationReset(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSOAPVersion()
//
    function soapGetSOAPVersion()
    {
        //   Get SOAP version. 
        $rc = $this->GetSOAPVersion(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPortMACAddress()
//
    function soapGetPortMACAddress($portId)
    {
        //   Get port MAC Address. 
        $rc = $this->GetPortMACAddress(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateCountryCode()
//
    function soapUpdateCountryCode($countryCode)
    {
        //   Update Country Code. In case of a controller, use ControlledNetworkUpdateCountryCode.
        $rc = $this->UpdateCountryCode(array("countryCode" => $countryCode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetCountryCode()
//
    function soapGetCountryCode()
    {
        //   Get Country Code. In case of a controller, use ControlledNetworkGetCountryCode. 
        $rc = $this->GetCountryCode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateCountryCode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateCountryCode($level, $entityName, $countryCode)
    {
        //   Update Country Code. 
        $rc = $this->ControlledNetworkUpdateCountryCode(array("level" => $level, "entityName" => $entityName, "countryCode" => $countryCode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetCountryCode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetCountryCode($level, $entityName)
    {
        //   Get Country Code. 
        $rc = $this->ControlledNetworkGetCountryCode(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateCountryCodeInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateCountryCodeInheritance($level, $entityName, $state)
    {
        //   Update the country code inheritance. 
        $rc = $this->ControlledNetworkUpdateCountryCodeInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetCountryCodeInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetCountryCodeInheritance($level, $entityName)
    {
        //   Get the country code inheritance state. 
        $rc = $this->ControlledNetworkGetCountryCodeInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddRADIUSAuthenticationRealm()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapAddRADIUSAuthenticationRealm($vscName, $radiusName, $realmName, $useRegexState)
    {
        //   Add authentication REALM to RADIUS profile. 
        $rc = $this->AddRADIUSAuthenticationRealm(array("vscName" => $vscName, "radiusName" => $radiusName, "realmName" => $realmName, "useRegexState" => $useRegexState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteRADIUSAuthenticationRealm()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteRADIUSAuthenticationRealm($vscName, $radiusName, $realmName)
    {
        //   Delete REALM in a RADIUS profile. 
        $rc = $this->DeleteRADIUSAuthenticationRealm(array("vscName" => $vscName, "radiusName" => $radiusName, "realmName" => $realmName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSAuthenticationRealmRegexState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateRADIUSAuthenticationRealmRegexState($vscName, $radiusName, $regexState)
    {
        //    Update the regex usage state for authentication realms. 
        $rc = $this->UpdateRADIUSAuthenticationRealmRegexState(array("vscName" => $vscName, "radiusName" => $radiusName, "regexState" => $regexState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSAuthenticationRealm()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetRADIUSAuthenticationRealm($vscName, $radiusName)
    {
        //   Get list of RADIUS authentication REALMs for RADIUS profile. 
        $rc = $this->GetRADIUSAuthenticationRealm(array("vscName" => $vscName, "radiusName" => $radiusName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateManagementInterfaceAccess()
//
    function soapUpdateManagementInterfaceAccess($managementInterface, $lanAccessState, $wirelessAccessState, $vpnAccessState, $internetAccessState)
    {
        //   Update Management Interface authorized access. 
        $rc = $this->UpdateManagementInterfaceAccess(array("managementInterface" => $managementInterface, "lanAccessState" => $lanAccessState, "wirelessAccessState" => $wirelessAccessState, "vpnAccessState" => $vpnAccessState, "internetAccessState" => $internetAccessState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetManagementInterfaceAccess()
//
    function soapGetManagementInterfaceAccess($managementInterface)
    {
        //   Get Management Interface authorized access. 
        $rc = $this->GetManagementInterfaceAccess(array("managementInterface" => $managementInterface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddManagementInterfaceAddressRestriction()
//
    function soapAddManagementInterfaceAddressRestriction($managementInterface, $ipAddress, $ipMask)
    {
        //   Add Management Interface Address Restriction. 
        $rc = $this->AddManagementInterfaceAddressRestriction(array("managementInterface" => $managementInterface, "ipAddress" => $ipAddress, "ipMask" => $ipMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteManagementInterfaceAddressRestriction()
//
    function soapDeleteManagementInterfaceAddressRestriction($managementInterface, $ipAddress, $ipMask)
    {
        //   Delete Management Interface Address Restriction. 
        $rc = $this->DeleteManagementInterfaceAddressRestriction(array("managementInterface" => $managementInterface, "ipAddress" => $ipAddress, "ipMask" => $ipMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllManagementInterfaceAddressRestrictions()
//
    function soapDeleteAllManagementInterfaceAddressRestrictions($managementInterface)
    {
        //   Delete all Management Interface Address Restriction. 
        $rc = $this->DeleteAllManagementInterfaceAddressRestrictions(array("managementInterface" => $managementInterface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetManagementInterfaceAddressRestriction()
//
    function soapGetManagementInterfaceAddressRestriction($managementInterface)
    {
        //   Get Management Interface Address Restrictions. 
        $rc = $this->GetManagementInterfaceAddressRestriction(array("managementInterface" => $managementInterface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddManagementInterfaceAuthorizedAccess()
//
    function soapAddManagementInterfaceAuthorizedAccess($managementInterface, $interfaceName)
    {
        //   Add Management Interface Authorized Access. 
        $rc = $this->AddManagementInterfaceAuthorizedAccess(array("managementInterface" => $managementInterface, "interfaceName" => $interfaceName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteManagementInterfaceAuthorizedAccess()
//
    function soapDeleteManagementInterfaceAuthorizedAccess($managementInterface, $interfaceName)
    {
        //   Delete management interface Authorized Access. 
        $rc = $this->DeleteManagementInterfaceAuthorizedAccess(array("managementInterface" => $managementInterface, "interfaceName" => $interfaceName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllManagementInterfaceAuthorizedAccesses()
//
    function soapDeleteAllManagementInterfaceAuthorizedAccesses($managementInterface)
    {
        //   Delete all management interface Authorized Accesses. 
        $rc = $this->DeleteAllManagementInterfaceAuthorizedAccesses(array("managementInterface" => $managementInterface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetManagementInterfaceAuthorizedAccess()
//
    function soapGetManagementInterfaceAuthorizedAccess($managementInterface)
    {
        //   Get Management interface authorized interfaces. 
        $rc = $this->GetManagementInterfaceAuthorizedAccess(array("managementInterface" => $managementInterface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSOAPInterface()
//
    function soapUpdateSOAPInterface($sslState, $useClientCertificateState, $authenticationRequiredState, $authenticationUsername, $authenticationPassword, $callRate, $portNumber)
    {
        //   Update SOAP configuration interface settings. 
        $rc = $this->UpdateSOAPInterface(array("sslState" => $sslState, "useClientCertificateState" => $useClientCertificateState, "authenticationRequiredState" => $authenticationRequiredState, "authenticationUsername" => $authenticationUsername, "authenticationPassword" => $authenticationPassword, "callRate" => $callRate, "portNumber" => $portNumber));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSOAPInterface()
//
    function soapGetSOAPInterface()
    {
        //   Get SOAP configuration interface settings. 
        $rc = $this->GetSOAPInterface(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSOAPServerState()
//
    function soapUpdateSOAPServerState($state)
    {
        //   Update SOAP interface state. 
        $rc = $this->UpdateSOAPServerState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessAssociatedClient()
//
    function soapGetWirelessAssociatedClient($deviceId)
    {
        //   Get list of associated associated clients. 
        $rc = $this->GetWirelessAssociatedClient(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAuthenticatedUsers()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetAuthenticatedUsers()
    {
        //   Get list of authenticated users. 
        $rc = $this->GetAuthenticatedUsers(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSelectedAuthenticatedUsers()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetSelectedAuthenticatedUsers($filterType, $filterVal)
    {
        //   Get filtered list of authenticated users. The filter uses Regular Expressions. 
        $rc = $this->GetSelectedAuthenticatedUsers(array("filterType" => $filterType, "filterVal" => $filterVal));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddIPSECPolicy()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapAddIPSECPolicy($name, $phase1Mode, $mode, $portId, $encryption, $perfectForwardSecrecyState, $peerIPAddress)
    {
        //   Add IPSec Policy. 
        $rc = $this->AddIPSECPolicy(array("name" => $name, "phase1Mode" => $phase1Mode, "mode" => $mode, "portId" => $portId, "encryption" => $encryption, "perfectForwardSecrecyState" => $perfectForwardSecrecyState, "peerIPAddress" => $peerIPAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIPSECPolicy()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateIPSECPolicy($name, $state, $phase1Mode, $mode, $portId, $encryption, $perfectForwardSecrecyState, $peerIPAddress)
    {
        //   Update IPSec Policy settings. 
        $rc = $this->UpdateIPSECPolicy(array("name" => $name, "state" => $state, "phase1Mode" => $phase1Mode, "mode" => $mode, "portId" => $portId, "encryption" => $encryption, "perfectForwardSecrecyState" => $perfectForwardSecrecyState, "peerIPAddress" => $peerIPAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPSECPolicy()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetIPSECPolicy($name)
    {
        //   Get IPSec Policy settings. 
        $rc = $this->GetIPSECPolicy(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteIPSECPolicy()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteIPSECPolicy($name)
    {
        //   Delete IPSec Policy. 
        $rc = $this->DeleteIPSECPolicy(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIPSECPeerInfo()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateIPSECPeerInfo($name, $peerIPAddress, $peerIdType, $peerId, $dnsIPAddress, $domainName)
    {
        //   Update IPSEC peer informations. 
        $rc = $this->UpdateIPSECPeerInfo(array("name" => $name, "peerIPAddress" => $peerIPAddress, "peerIdType" => $peerIdType, "peerId" => $peerId, "dnsIPAddress" => $dnsIPAddress, "domainName" => $domainName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPSECPeerInfo()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetIPSECPeerInfo($name)
    {
        //   Get IPSEC peer information. 
        $rc = $this->GetIPSECPeerInfo(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIPSECSecurityInfo()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateIPSECSecurityInfo($name, $incomingPhase2Mode, $incomingSubnet, $incomingMask, $incomingNatState, $outgoingPhase2Mode, $outgoingSubnet, $outgoingMask)
    {
        //   Update IPSEC security information. 
        $rc = $this->UpdateIPSECSecurityInfo(array("name" => $name, "incomingPhase2Mode" => $incomingPhase2Mode, "incomingSubnet" => $incomingSubnet, "incomingMask" => $incomingMask, "incomingNatState" => $incomingNatState, "outgoingPhase2Mode" => $outgoingPhase2Mode, "outgoingSubnet" => $outgoingSubnet, "outgoingMask" => $outgoingMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPSECSecurityInfo()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetIPSECSecurityInfo($name)
    {
        //   Get IPSEC security information. 
        $rc = $this->GetIPSECSecurityInfo(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIPSECAuthenticationMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateIPSECAuthenticationMode($name, $mode, $psk)
    {
        //   Update IPSEC authentication mode settings. 
        $rc = $this->UpdateIPSECAuthenticationMode(array("name" => $name, "mode" => $mode, "psk" => $psk));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPSECAuthenticationMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetIPSECAuthenticationMode($name)
    {
        //   Get IPSEC authentication mode settings for policy. 
        $rc = $this->GetIPSECAuthenticationMode(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIPSECVLANMapping()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateIPSECVLANMapping($internetInterfaceMapping, $lanInterfaceMapping)
    {
        //   Update IPSEC VLAN mapping. 
        $rc = $this->UpdateIPSECVLANMapping(array("internetInterfaceMapping" => $internetInterfaceMapping, "lanInterfaceMapping" => $lanInterfaceMapping));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPSECVLANMapping()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetIPSECVLANMapping()
    {
        //   Get IPSEC VLAN mapping. 
        $rc = $this->GetIPSECVLANMapping(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWEBServerPort()
//
    function soapUpdateWEBServerPort($httpsPort, $httpPort)
    {
        //   Update WEB configuration server port settings. 
        $rc = $this->UpdateWEBServerPort(array("httpsPort" => $httpsPort, "httpPort" => $httpPort));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWEBServerPort()
//
    function soapGetWEBServerPort()
    {
        //   Get WEB configuration server port settings. 
        $rc = $this->GetWEBServerPort(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWEBServerLoginControl()
//
    function soapUpdateWEBServerLoginControl($AdminKickOutAllowed)
    {
        //   Update WEB server login control settings. 
        $rc = $this->UpdateWEBServerLoginControl(array("AdminKickOutAllowed" => $AdminKickOutAllowed));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWEBServerLoginControl()
//
    function soapGetWEBServerLoginControl()
    {
        //   Get WEB server login control settings. 
        $rc = $this->GetWEBServerLoginControl(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWEBServerOperatorLoginControl()
//
    function soapUpdateWEBServerOperatorLoginControl($operatorKickOutAllowed)
    {
        //   Update WEB server login control settings for operator account. 
        $rc = $this->UpdateWEBServerOperatorLoginControl(array("operatorKickOutAllowed" => $operatorKickOutAllowed));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWEBServerOperatorLoginControl()
//
    function soapGetWEBServerOperatorLoginControl()
    {
        //   Get WEB server login control settings for operator account. 
        $rc = $this->GetWEBServerOperatorLoginControl(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAdminAuthentication()
//
    function soapUpdateAdminAuthentication($localAuthState, $radiusAuthState, $authenticationRadiusName)
    {
        //   Update administrator authentication settings. 
        $rc = $this->UpdateAdminAuthentication(array("localAuthState" => $localAuthState, "radiusAuthState" => $radiusAuthState, "authenticationRadiusName" => $authenticationRadiusName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAdminAuthentication()
//
    function soapGetAdminAuthentication()
    {
        //   Get administrator authentication settings. 
        $rc = $this->GetAdminAuthentication(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWEBServerAutoRefresh()
//
    function soapUpdateWEBServerAutoRefresh($state, $interval)
    {
        //   Update Web server auto-refresh settings. 
        $rc = $this->UpdateWEBServerAutoRefresh(array("state" => $state, "interval" => $interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWEBServerAutoRefresh()
//
    function soapGetWEBServerAutoRefresh()
    {
        //   Get Web server auto-refresh settings. 
        $rc = $this->GetWEBServerAutoRefresh(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWEBServerInactivityLogout()
//
    function soapUpdateWEBServerInactivityLogout($state, $timeout)
    {
        //   Update Web server inactivity logout settings. 
        $rc = $this->UpdateWEBServerInactivityLogout(array("state" => $state, "timeout" => $timeout));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWEBServerInactivityLogout()
//
    function soapGetWEBServerInactivityLogout()
    {
        //   Get Web server inactivity logout settings. 
        $rc = $this->GetWEBServerInactivityLogout(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWEBServerSecurityPolicies()
//
    function soapUpdateWEBServerSecurityPolicies($standard)
    {
        //   Update Web server security policies setting. 
        $rc = $this->UpdateWEBServerSecurityPolicies(array("standard" => $standard));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWEBServerSecurityPolicies()
//
    function soapGetWEBServerSecurityPolicies()
    {
        //   Get Web server security policies setting. 
        $rc = $this->GetWEBServerSecurityPolicies(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWEBServerLockingControl()
//
    function soapUpdateWEBServerLockingControl($loginTries, $lockTimeout)
    {
        //   Update Web server locking settings. 
        $rc = $this->UpdateWEBServerLockingControl(array("loginTries" => $loginTries, "lockTimeout" => $lockTimeout));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWEBServerLockingControl()
//
    function soapGetWEBServerLockingControl()
    {
        //   Get Web server locking settings. 
        $rc = $this->GetWEBServerLockingControl(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPInterface()
//
    function soapUpdateSNMPInterface($systemName, $location, $contact, $communityName, $readOnlyName)
    {
        //   Update SNMP configuration interface settings. 
        $rc = $this->UpdateSNMPInterface(array("systemName" => $systemName, "location" => $location, "contact" => $contact, "communityName" => $communityName, "readOnlyName" => $readOnlyName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPInterface()
//
    function soapGetSNMPInterface()
    {
        //   Get SNMP configuration interface settings. 
        $rc = $this->GetSNMPInterface(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPAgentInterfaceState()
//
    function soapUpdateSNMPAgentInterfaceState($agentState)
    {
        //   Update SNMP agent interface state. 
        $rc = $this->UpdateSNMPAgentInterfaceState(array("agentState" => $agentState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPAgentInterfaceState()
//
    function soapGetSNMPAgentInterfaceState()
    {
        //   Get SNMP agent state. 
        $rc = $this->GetSNMPAgentInterfaceState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPAgentInterfacePort()
//
    function soapUpdateSNMPAgentInterfacePort($snmpPort)
    {
        //   Update SNMP agent interface port. 
        $rc = $this->UpdateSNMPAgentInterfacePort(array("snmpPort" => $snmpPort));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPAgentInterfacePort()
//
    function soapGetSNMPAgentInterfacePort()
    {
        //   Get SNMP agent port. 
        $rc = $this->GetSNMPAgentInterfacePort(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPAgentInterfaceProtocolVersion()
//
    function soapUpdateSNMPAgentInterfaceProtocolVersion($version1, $version2c, $version3)
    {
        //   Update SNMP agent version settings. 
        $rc = $this->UpdateSNMPAgentInterfaceProtocolVersion(array("version1" => $version1, "version2c" => $version2c, "version3" => $version3));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPAgentInterfaceProtocolVersion()
//
    function soapGetSNMPAgentInterfaceProtocolVersion()
    {
        //   Get SNMP agent version settings. 
        $rc = $this->GetSNMPAgentInterfaceProtocolVersion(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapInterface()
//
    function soapUpdateSNMPTrapInterface($trapState, $communityName)
    {
        //   Update SNMP Trap settings. 
        $rc = $this->UpdateSNMPTrapInterface(array("trapState" => $trapState, "communityName" => $communityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapInterface()
//
    function soapGetSNMPTrapInterface()
    {
        //   Get SNMP Trap settings. 
        $rc = $this->GetSNMPTrapInterface(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSNMPTrapDestination()
//
    function soapAddSNMPTrapDestination($hostIP, $portNumber, $version, $community, $username)
    {
        //   Add SNMP trap destination. 
        $rc = $this->AddSNMPTrapDestination(array("hostIP" => $hostIP, "portNumber" => $portNumber, "version" => $version, "community" => $community, "username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapDestination()
//
    function soapUpdateSNMPTrapDestination($hostIP, $portNumber, $version, $community, $username)
    {
        //   Update SNMP trap destination. 
        $rc = $this->UpdateSNMPTrapDestination(array("hostIP" => $hostIP, "portNumber" => $portNumber, "version" => $version, "community" => $community, "username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSNMPTrapDestination()
//
    function soapDeleteSNMPTrapDestination($hostIP, $portNumber)
    {
        //   Delete SNMP trap destination. 
        $rc = $this->DeleteSNMPTrapDestination(array("hostIP" => $hostIP, "portNumber" => $portNumber));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllSNMPTrapDestinations()
//
    function soapDeleteAllSNMPTrapDestinations()
    {
        //   Delete all SNMP trap destinations. 
        $rc = $this->DeleteAllSNMPTrapDestinations(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapDestination()
//
    function soapGetSNMPTrapDestination($hostIP, $portNumber)
    {
        //   Get SNMP trap destinations. 
        $rc = $this->GetSNMPTrapDestination(array("hostIP" => $hostIP, "portNumber" => $portNumber));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapDestinationList()
//
    function soapGetSNMPTrapDestinationList()
    {
        //   Get the list of all SNMP trap destinations. 
        $rc = $this->GetSNMPTrapDestinationList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSNMPUser()
//
    function soapAddSNMPUser($username, $password, $security, $accesslevel)
    {
        //   Add SNMP v3 user. 
        $rc = $this->AddSNMPUser(array("username" => $username, "password" => $password, "security" => $security, "accesslevel" => $accesslevel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPUser()
//
    function soapUpdateSNMPUser($username, $password, $security, $accesslevel)
    {
        //   Update SNMP v3 user. 
        $rc = $this->UpdateSNMPUser(array("username" => $username, "password" => $password, "security" => $security, "accesslevel" => $accesslevel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPUserName()
//
    function soapUpdateSNMPUserName($oldUsername, $newUsername)
    {
        //   Update SNMP v3 user name. 
        $rc = $this->UpdateSNMPUserName(array("oldUsername" => $oldUsername, "newUsername" => $newUsername));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSNMPUser()
//
    function soapDeleteSNMPUser($username)
    {
        //   Delete SNMP user. 
        $rc = $this->DeleteSNMPUser(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllSNMPUsers()
//
    function soapDeleteAllSNMPUsers()
    {
        //   Delete all SNMP users. 
        $rc = $this->DeleteAllSNMPUsers(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPUser()
//
    function soapGetSNMPUser($username)
    {
        //   Get SNMP user settings. 
        $rc = $this->GetSNMPUser(array("username" => $username));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPUserList()
//
    function soapGetSNMPUserList()
    {
        //   Get the list of SNMP users. 
        $rc = $this->GetSNMPUserList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapAuthentication()
//
    function soapUpdateSNMPTrapAuthentication($trapOnSNMPAuthenticationFailureState, $trapOnManagementAuthenticationFailureState, $trapOnManagementAuthenticationSuccessState, $trapOnManagementLogoutState)
    {
        //   Update settings for SNMP authentication traps. 
        $rc = $this->UpdateSNMPTrapAuthentication(array("trapOnSNMPAuthenticationFailureState" => $trapOnSNMPAuthenticationFailureState, "trapOnManagementAuthenticationFailureState" => $trapOnManagementAuthenticationFailureState, "trapOnManagementAuthenticationSuccessState" => $trapOnManagementAuthenticationSuccessState, "trapOnManagementLogoutState" => $trapOnManagementLogoutState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapAuthentication()
//
    function soapGetSNMPTrapAuthentication()
    {
        //   Get settings for SNMP authentication traps. 
        $rc = $this->GetSNMPTrapAuthentication(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapMaintenance()
//
    function soapUpdateSNMPTrapMaintenance($trapOnFirmwareUpdateState, $trapOnConfigurationUpdateState, $trapOnConfigurationChangeState, $trapOnCertificateAboutToExpiredState, $trapOnCertificateExpiredState)
    {
        //   Update settings for maintenance SNMP traps. 
        $rc = $this->UpdateSNMPTrapMaintenance(array("trapOnFirmwareUpdateState" => $trapOnFirmwareUpdateState, "trapOnConfigurationUpdateState" => $trapOnConfigurationUpdateState, "trapOnConfigurationChangeState" => $trapOnConfigurationChangeState, "trapOnCertificateAboutToExpiredState" => $trapOnCertificateAboutToExpiredState, "trapOnCertificateExpiredState" => $trapOnCertificateExpiredState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapMaintenance()
//
    function soapGetSNMPTrapMaintenance()
    {
        //   Get settings for SNMP maintenance traps. 
        $rc = $this->GetSNMPTrapMaintenance(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapSyslog()
//
    function soapUpdateSNMPTrapSyslog($trapOnMessageSeverityState, $severity, $trapOnRegularExpressionState, $expression)
    {
        //   Update Syslog SNMP traps settings. 
        $rc = $this->UpdateSNMPTrapSyslog(array("trapOnMessageSeverityState" => $trapOnMessageSeverityState, "severity" => $severity, "trapOnRegularExpressionState" => $trapOnRegularExpressionState, "expression" => $expression));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapSyslog()
//
    function soapGetSNMPTrapSyslog()
    {
        //   Get Syslog SNMP traps settings. 
        $rc = $this->GetSNMPTrapSyslog(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapHeartbeat()
//
    function soapUpdateSNMPTrapHeartbeat($heartbeatState, $heartbeat)
    {
        //   Update Heart Beat SNMP trap settings. 
        $rc = $this->UpdateSNMPTrapHeartbeat(array("heartbeatState" => $heartbeatState, "heartbeat" => $heartbeat));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapHeartbeat()
//
    function soapGetSNMPTrapHeartbeat()
    {
        //   Get Heart Beat SNMP trap settings. 
        $rc = $this->GetSNMPTrapHeartbeat(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapWireless()
//
    function soapUpdateSNMPTrapWireless($trapOnSNRState, $snrThreshold, $snrInterval, $trapOnNewAssociationState, $associationInterval, $trapOnUnautorizedAPState, $unauthorizedInterval)
    {
        //   Update Wireless SNMP traps settings. 
        $rc = $this->UpdateSNMPTrapWireless(array("trapOnSNRState" => $trapOnSNRState, "snrThreshold" => $snrThreshold, "snrInterval" => $snrInterval, "trapOnNewAssociationState" => $trapOnNewAssociationState, "associationInterval" => $associationInterval, "trapOnUnautorizedAPState" => $trapOnUnautorizedAPState, "unauthorizedInterval" => $unauthorizedInterval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapWireless()
//
    function soapGetSNMPTrapWireless()
    {
        //   Get Wireless SNMP traps settings. 
        $rc = $this->GetSNMPTrapWireless(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapSatellite()
//
    function soapUpdateSNMPTrapSatellite($trapOnNewSatelliteDetectedState, $trapOnSatelliteUnreachableState)
    {
        //   Update Satellite SNMP traps settings. 
        $rc = $this->UpdateSNMPTrapSatellite(array("trapOnNewSatelliteDetectedState" => $trapOnNewSatelliteDetectedState, "trapOnSatelliteUnreachableState" => $trapOnSatelliteUnreachableState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapSatellite()
//
    function soapGetSNMPTrapSatellite()
    {
        //   Get Satellite SNMP traps settings. 
        $rc = $this->GetSNMPTrapSatellite(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapClientEvent()
//
    function soapUpdateSNMPTrapClientEvent($trapOnAssociationSuccessState, $trapOnAssociationFailureState, $trapOnReassociationSuccessState, $trapOnReassociationFailureState, $trapOnDeassociationSuccessState, $trapOnDeassociationFailureState, $trapOnAuthenticationSuccessState, $trapOnAuthenticationFailureState, $trapOnDeauthenticationSuccessState, $trapOnDeauthenticationFailureState)
    {
        //   Update Client events SNMP traps settings. 
        $rc = $this->UpdateSNMPTrapClientEvent(array("trapOnAssociationSuccessState" => $trapOnAssociationSuccessState, "trapOnAssociationFailureState" => $trapOnAssociationFailureState, "trapOnReassociationSuccessState" => $trapOnReassociationSuccessState, "trapOnReassociationFailureState" => $trapOnReassociationFailureState, "trapOnDeassociationSuccessState" => $trapOnDeassociationSuccessState, "trapOnDeassociationFailureState" => $trapOnDeassociationFailureState, "trapOnAuthenticationSuccessState" => $trapOnAuthenticationSuccessState, "trapOnAuthenticationFailureState" => $trapOnAuthenticationFailureState, "trapOnDeauthenticationSuccessState" => $trapOnDeauthenticationSuccessState, "trapOnDeauthenticationFailureState" => $trapOnDeauthenticationFailureState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapClientEvent()
//
    function soapGetSNMPTrapClientEvent()
    {
        //   Get Client events SNMP traps settings. 
        $rc = $this->GetSNMPTrapClientEvent(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapEquipment()
//
    function soapUpdateSNMPTrapEquipment($trapOnNetworkTraceStatusChangeState, $trapOnVPNUserConnectionStateChange, $trapOnLinkStateChange, $trapOnTimeServerSyncFailureState)
    {
        //   Update equipment trap settings. 
        $rc = $this->UpdateSNMPTrapEquipment(array("trapOnNetworkTraceStatusChangeState" => $trapOnNetworkTraceStatusChangeState, "trapOnVPNUserConnectionStateChange" => $trapOnVPNUserConnectionStateChange, "trapOnLinkStateChange" => $trapOnLinkStateChange, "trapOnTimeServerSyncFailureState" => $trapOnTimeServerSyncFailureState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapEquipment()
//
    function soapGetSNMPTrapEquipment()
    {
        //   Get equipment SNMP traps settings. 
        $rc = $this->GetSNMPTrapEquipment(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapDeviceManagement()
//
    function soapUpdateSNMPTrapDeviceManagement($trapOnDeviceStateChangeState, $trapOnDeviceAuthorizationFailureState, $trapOnDeviceSecurityFailureState, $trapOnDeviceFirmwareFailureState, $trapOnDeviceConfigurationFailureState)
    {
        //   Update device management trap settings. 
        $rc = $this->UpdateSNMPTrapDeviceManagement(array("trapOnDeviceStateChangeState" => $trapOnDeviceStateChangeState, "trapOnDeviceAuthorizationFailureState" => $trapOnDeviceAuthorizationFailureState, "trapOnDeviceSecurityFailureState" => $trapOnDeviceSecurityFailureState, "trapOnDeviceFirmwareFailureState" => $trapOnDeviceFirmwareFailureState, "trapOnDeviceConfigurationFailureState" => $trapOnDeviceConfigurationFailureState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapDeviceManagement()
//
    function soapGetSNMPTrapDeviceManagement()
    {
        //   Get device management trap settings. 
        $rc = $this->GetSNMPTrapDeviceManagement(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapServiceControllerManagement()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateSNMPTrapServiceControllerManagement($trapOnServiceControllerStateChange)
    {
        //   Update service controller management trap settings. 
        $rc = $this->UpdateSNMPTrapServiceControllerManagement(array("trapOnServiceControllerStateChange" => $trapOnServiceControllerStateChange));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapServiceControllerManagement()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetSNMPTrapServiceControllerManagement()
    {
        //   Get service controller management trap settings. 
        $rc = $this->GetSNMPTrapServiceControllerManagement(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPTrapBillingRecords()
//
    function soapUpdateSNMPTrapBillingRecords($trapOnBillingRecordsTransmissionFailureState, $trapOnBillingRecordsTransmissionStoppedState)
    {
        //   Update billing records trap settings. 
        $rc = $this->UpdateSNMPTrapBillingRecords(array("trapOnBillingRecordsTransmissionFailureState" => $trapOnBillingRecordsTransmissionFailureState, "trapOnBillingRecordsTransmissionStoppedState" => $trapOnBillingRecordsTransmissionStoppedState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSNMPTrapBillingRecords()
//
    function soapGetSNMPTrapBillingRecords()
    {
        //   Get billing records trap settings. 
        $rc = $this->GetSNMPTrapBillingRecords(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSNMPResponder()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateSNMPResponder($state)
    {
        //   Update SNMP responder configuration. 
        $rc = $this->UpdateSNMPResponder(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalLog()
//
    function soapUpdateLocalLog($filterOperator, $messageFilterState, $notMessageState, $message, $severityFilterState, $notSeverityState, $severity, $processFilterState, $notProcessState, $process)
    {
        //   Update local system log settings. 
        $rc = $this->UpdateLocalLog(array("filterOperator" => $filterOperator, "messageFilterState" => $messageFilterState, "notMessageState" => $notMessageState, "message" => $message, "severityFilterState" => $severityFilterState, "notSeverityState" => $notSeverityState, "severity" => $severity, "processFilterState" => $processFilterState, "notProcessState" => $notProcessState, "process" => $process));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalLog()
//
    function soapGetLocalLog()
    {
        //   Get configuration settings for local system log.
        $rc = $this->GetLocalLog(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddRemoteLog()
//
    function soapAddRemoteLog($name, $state, $host, $port, $protocol, $prefix, $facility, $source, $filterOperator, $messageFilterState, $notMessageState, $message, $severityFilterState, $notSeverityState, $severity, $processFilterState, $notProcessState, $process)
    {
        //   Add remote syslog profile. 
        $rc = $this->AddRemoteLog(array("name" => $name, "state" => $state, "host" => $host, "port" => $port, "protocol" => $protocol, "prefix" => $prefix, "facility" => $facility, "source" => $source, "filterOperator" => $filterOperator, "messageFilterState" => $messageFilterState, "notMessageState" => $notMessageState, "message" => $message, "severityFilterState" => $severityFilterState, "notSeverityState" => $notSeverityState, "severity" => $severity, "processFilterState" => $processFilterState, "notProcessState" => $notProcessState, "process" => $process));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRemoteLog()
//
    function soapUpdateRemoteLog($oldProfileName, $newProfileName, $state, $host, $port, $protocol, $prefix, $facility, $source, $filterOperator, $messageFilterState, $notMessageState, $message, $severityFilterState, $notSeverityState, $severity, $processFilterState, $notProcessState, $process)
    {
        //   Update remote syslog profile. 
        $rc = $this->UpdateRemoteLog(array("oldProfileName" => $oldProfileName, "newProfileName" => $newProfileName, "state" => $state, "host" => $host, "port" => $port, "protocol" => $protocol, "prefix" => $prefix, "facility" => $facility, "source" => $source, "filterOperator" => $filterOperator, "messageFilterState" => $messageFilterState, "notMessageState" => $notMessageState, "message" => $message, "severityFilterState" => $severityFilterState, "notSeverityState" => $notSeverityState, "severity" => $severity, "processFilterState" => $processFilterState, "notProcessState" => $notProcessState, "process" => $process));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteRemoteLog()
//
    function soapDeleteRemoteLog($name)
    {
        //   Delete remote system log profile. 
        $rc = $this->DeleteRemoteLog(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllRemoteLogs()
//
    function soapDeleteAllRemoteLogs()
    {
        //   Delete all remote system log profiles. 
        $rc = $this->DeleteAllRemoteLogs(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRemoteLog()
//
    function soapGetRemoteLog($name)
    {
        //   Get remote system log profile. 
        $rc = $this->GetRemoteLog(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRemoteLogList()
//
    function soapGetRemoteLogList()
    {
        //   Get remote system log profile list. 
        $rc = $this->GetRemoteLogList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePersistentInformationBackupPeriod()
//
    function soapUpdatePersistentInformationBackupPeriod($period)
    {
        //   Update the persistent information backup period. Minimum 30 minutes, maximum 1440 minutes (24 hours) and default 60 minutes. This function is disabled for teaming.
        $rc = $this->UpdatePersistentInformationBackupPeriod(array("period" => $period));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPersistentInformationBackupPeriod()
//
    function soapGetPersistentInformationBackupPeriod()
    {
        //   Get the persistent information backup period. This function is disabled for teaming.
        $rc = $this->GetPersistentInformationBackupPeriod(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePersistentInformationBackupState()
//
    function soapUpdatePersistentInformationBackupState($state)
    {
        //   Update the persistent information backup state. This function is disabled for teaming.
        $rc = $this->UpdatePersistentInformationBackupState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPersistentInformationBackupState()
//
    function soapGetPersistentInformationBackupState()
    {
        //   Get the persistent information backup state. This function is disabled for teaming.
        $rc = $this->GetPersistentInformationBackupState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateCLIInterface()
//
    function soapUpdateCLIInterface($sshState, $serialPortState)
    {
        //   Update CLI interface settings. 
        $rc = $this->UpdateCLIInterface(array("sshState" => $sshState, "serialPortState" => $serialPortState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetCLIInterface()
//
    function soapGetCLIInterface()
    {
        //   Get CLI interface settings. 
        $rc = $this->GetCLIInterface(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateCLIAuthentication()
//
    function soapUpdateCLIAuthentication($method)
    {
        //   Update CLI authentication settings. 
        $rc = $this->UpdateCLIAuthentication(array("method" => $method));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetCLIAuthentication()
//
    function soapGetCLIAuthentication()
    {
        //   Get CLI authentication settings. 
        $rc = $this->GetCLIAuthentication(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSerialPort()
//
    function soapUpdateSerialPort($baudRate, $hardwareFlowControlState)
    {
        //   Update serial port settings. 
        $rc = $this->UpdateSerialPort(array("baudRate" => $baudRate, "hardwareFlowControlState" => $hardwareFlowControlState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSerialPort()
//
    function soapGetSerialPort()
    {
        //   Get serial port settings. 
        $rc = $this->GetSerialPort(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTimezoneRule()
//
    function soapUpdateTimezoneRule($ruleIndex, $ruleFormat, $month, $day, $weekday, $atTime, $save)
    {
        //   Update timezone rule settings. 
        $rc = $this->UpdateTimezoneRule(array("ruleIndex" => $ruleIndex, "ruleFormat" => $ruleFormat, "month" => $month, "day" => $day, "weekday" => $weekday, "atTime" => $atTime, "save" => $save));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTimezoneRule()
//
    function soapGetTimezoneRule($ruleIndex)
    {
        //   Get timezone rule settings. 
        $rc = $this->GetTimezoneRule(array("ruleIndex" => $ruleIndex));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTimeServerMode()
//
    function soapUpdateTimeServerMode($ntpServerState, $timezone, $ntpProtocol, $autoAdjustClockForDSTState, $useCustomDSTRulesState)
    {
        //   Update Time Server mode settings. 
        $rc = $this->UpdateTimeServerMode(array("ntpServerState" => $ntpServerState, "timezone" => $timezone, "ntpProtocol" => $ntpProtocol, "autoAdjustClockForDSTState" => $autoAdjustClockForDSTState, "useCustomDSTRulesState" => $useCustomDSTRulesState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTimeServerMode()
//
    function soapGetTimeServerMode()
    {
        //   Get Time Server mode settings. 
        $rc = $this->GetTimeServerMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddTimeServer()
//
    function soapAddTimeServer($timeServer)
    {
        //   Add time server. 
        $rc = $this->AddTimeServer(array("timeServer" => $timeServer));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteTimeServer()
//
    function soapDeleteTimeServer($timeServer)
    {
        //   Delete time server. 
        $rc = $this->DeleteTimeServer(array("timeServer" => $timeServer));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllTimeServers()
//
    function soapDeleteAllTimeServers()
    {
        //   Delete all time servers. 
        $rc = $this->DeleteAllTimeServers(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTimeServer()
//
    function soapGetTimeServer()
    {
        //   Get list of time servers. 
        $rc = $this->GetTimeServer(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalTime()
//
    function soapUpdateLocalTime($dateTime)
    {
        //   Update local time. 
        $rc = $this->UpdateLocalTime(array("dateTime" => $dateTime));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalTime()
//
    function soapGetLocalTime()
    {
        //   Get Local Time. 
        $rc = $this->GetLocalTime(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDNSServers()
//
    function soapUpdateDNSServers($overrideState, $ipServer1, $ipServer2, $ipServer3, $dnsCacheState, $dnsSwitchOnServFail, $dnsSwitchOver, $dnsInterceptionState)
    {
        //   Update DNS servers settings. 
        $rc = $this->UpdateDNSServers(array("overrideState" => $overrideState, "ipServer1" => $ipServer1, "ipServer2" => $ipServer2, "ipServer3" => $ipServer3, "dnsCacheState" => $dnsCacheState, "dnsSwitchOnServFail" => $dnsSwitchOnServFail, "dnsSwitchOver" => $dnsSwitchOver, "dnsInterceptionState" => $dnsInterceptionState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDNSServers()
//
    function soapGetDNSServers()
    {
        //   Get DNS servers settings. 
        $rc = $this->GetDNSServers(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDNSLogoutSettings()
//
    function soapUpdateDNSLogoutSettings($hostName, $ipAddress)
    {
        //   Update DNS Logout host name and IP Address. 
        $rc = $this->UpdateDNSLogoutSettings(array("hostName" => $hostName, "ipAddress" => $ipAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDNSLogoutSettings()
//
    function soapGetDNSLogoutSettings()
    {
        //   Get DNS Logout host name and IP Address. 
        $rc = $this->GetDNSLogoutSettings(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSecurity8021X()
//
    function soapUpdateSecurity8021X($keyGroupeState, $keyInterval, $reAuthState, $reAuthInterval, $reAuthTerminationState, $supplicantTimeout, $quietPeriod)
    {
        //   Update 802.1X security settings. 
        $rc = $this->UpdateSecurity8021X(array("keyGroupeState" => $keyGroupeState, "keyInterval" => $keyInterval, "reAuthState" => $reAuthState, "reAuthInterval" => $reAuthInterval, "reAuthTerminationState" => $reAuthTerminationState, "supplicantTimeout" => $supplicantTimeout, "quietPeriod" => $quietPeriod));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSecurity8021X()
//
    function soapGetSecurity8021X()
    {
        //   Get 802.1X security settings.
        $rc = $this->GetSecurity8021X(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function Update8021XRADIUSAccountingStartDelay()
//
    function soapUpdate8021XRADIUSAccountingStartDelay($radiusAccountingStartDelay)
    {
        //   Update 802.1X RADIUS accounting start delay. 
        $rc = $this->Update8021XRADIUSAccountingStartDelay(array("radiusAccountingStartDelay" => $radiusAccountingStartDelay));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function Get8021XRADIUSAccountingStartDelay()
//
    function soapGet8021XRADIUSAccountingStartDelay()
    {
        //   Get 802.1X RADIUS accounting start delay.
        $rc = $this->Get8021XRADIUSAccountingStartDelay(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddIPRoute()
//
    function soapAddIPRoute($ipAddress, $ipMask, $ipGateway, $ipMetric)
    {
        //   Add IP route. To add a default route, you must use 0.0.0.0 as ipAddress and ipMask.
        $rc = $this->AddIPRoute(array("ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway, "ipMetric" => $ipMetric));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteIPRoute()
//
    function soapDeleteIPRoute($ipAddress, $ipMask, $ipGateway, $ipMetric)
    {
        //   Delete IP route. Even if "get" function returns "default", you must use "0.0.0.0" as ipAddress to delete a default route.
        $rc = $this->DeleteIPRoute(array("ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway, "ipMetric" => $ipMetric));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllIPRoutes()
//
    function soapDeleteAllIPRoutes()
    {
        //   Delete all IP routes.
        $rc = $this->DeleteAllIPRoutes(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPRouteList()
//
    function soapGetIPRouteList()
    {
        //   Get list of IP routes. 
        $rc = $this->GetIPRouteList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPGatewayList()
//
    function soapGetIPGatewayList()
    {
        //   Get list of IP active routes. 
        $rc = $this->GetIPGatewayList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPersistentPPTPIPRouteList()
//
    function soapGetPersistentPPTPIPRouteList()
    {
        //   Get list of persistent IP routes for PPTP client. 
        $rc = $this->GetPersistentPPTPIPRouteList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddPersistentPPTPIPRoute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapAddPersistentPPTPIPRoute($ipAddress, $ipMask)
    {
        //   Add a persistent IP route for PPTP client.
        $rc = $this->AddPersistentPPTPIPRoute(array("ipAddress" => $ipAddress, "ipMask" => $ipMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeletePersistentPPTPIPRoute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeletePersistentPPTPIPRoute($ipAddress, $ipMask)
    {
        //   Delete a persistent IP route for PPTP client.
        $rc = $this->DeletePersistentPPTPIPRoute(array("ipAddress" => $ipAddress, "ipMask" => $ipMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPersistentGREIPRouteList()
//
    function soapGetPersistentGREIPRouteList()
    {
        //   Get list of persistent IP routes used by GRE tunnels. 
        $rc = $this->GetPersistentGREIPRouteList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddPersistentGREIPRoute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapAddPersistentGREIPRoute($ipAddress, $ipMask, $ipGateway)
    {
        //   Add a persistent IP route for GRE tunnels.
        $rc = $this->AddPersistentGREIPRoute(array("ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeletePersistentGREIPRoute()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeletePersistentGREIPRoute($ipAddress, $ipMask, $ipGateway)
    {
        //   Delete a persistent IP route for GRE tunnels.
        $rc = $this->DeletePersistentGREIPRoute(array("ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllPersistentGREIPRoutes()
//
    function soapDeleteAllPersistentGREIPRoutes()
    {
        //   Delete all persistent IP routes for GRE tunnels.
        $rc = $this->DeleteAllPersistentGREIPRoutes(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSecurityAuth()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateSecurityAuth($authenticationModeState, $radiusName, $username, $password, $authenticationInterval, $accountingState)
    {
        //   Update Security settings for device Authentication. 
        $rc = $this->UpdateSecurityAuth(array("authenticationModeState" => $authenticationModeState, "radiusName" => $radiusName, "username" => $username, "password" => $password, "authenticationInterval" => $authenticationInterval, "accountingState" => $accountingState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSecurityAuth()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetSecurityAuth()
    {
        //   Get Security settings for device authentication. 
        $rc = $this->GetSecurityAuth(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccessControllerSecret()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateAccessControllerSecret($secret)
    {
        //   Update Access Controller shared secret. This function is functionaly equivalent to UpdateRADIUSServerDefaultSecret. 
        $rc = $this->UpdateAccessControllerSecret(array("secret" => $secret));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccessControllerSecret()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetAccessControllerSecret()
    {
        //   Get Access Controller shared secret. This function is functionaly equivalent to GetRADIUSServerDefaultSecret. 
        $rc = $this->GetAccessControllerSecret(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccessControllerPorts()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateAccessControllerPorts($httpsPort, $httpPort)
    {
        //   Update Access Controller port settings. 
        $rc = $this->UpdateAccessControllerPorts(array("httpsPort" => $httpsPort, "httpPort" => $httpPort));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccessControllerPorts()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetAccessControllerPorts()
    {
        //   Get Access Controller ports. 
        $rc = $this->GetAccessControllerPorts(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccessControllerSecureLogin()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateAccessControllerSecureLogin($state)
    {
        //   Update Access Controller secure login settings. 
        $rc = $this->UpdateAccessControllerSecureLogin(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccessControllerSecureLogin()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetAccessControllerSecureLogin()
    {
        //   Get Access Controller secure login. 
        $rc = $this->GetAccessControllerSecureLogin(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccessControllerSSLv2Login()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateAccessControllerSSLv2Login($state)
    {
        //   Update Access Controller sslv2 login settings. 
        $rc = $this->UpdateAccessControllerSSLv2Login(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccessControllerSSLv2Login()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetAccessControllerSSLv2Login()
    {
        //   Get Access Controller sslv2 login. 
        $rc = $this->GetAccessControllerSSLv2Login(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePublicAccessWebSiteOptions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdatePublicAccessWebSiteOptions($subscriptionPlanPurchase, $userAccountCreation, $maxUserAccountCreation, $maxUserAccountCreationDelay, $deleteInvalid, $deleteInvalidDelay, $deleteNotActivated, $deleteNotActivatedDelay, $freeAccessAllowed, $freeAccessValidityTimeout)
    {
        //   Update Public access Web site options. This function is disabled for teaming.
        $rc = $this->UpdatePublicAccessWebSiteOptions(array("subscriptionPlanPurchase" => $subscriptionPlanPurchase, "userAccountCreation" => $userAccountCreation, "maxUserAccountCreation" => $maxUserAccountCreation, "maxUserAccountCreationDelay" => $maxUserAccountCreationDelay, "deleteInvalid" => $deleteInvalid, "deleteInvalidDelay" => $deleteInvalidDelay, "deleteNotActivated" => $deleteNotActivated, "deleteNotActivatedDelay" => $deleteNotActivatedDelay, "freeAccessAllowed" => $freeAccessAllowed, "freeAccessValidityTimeout" => $freeAccessValidityTimeout));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPublicAccessWebSiteOptions()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetPublicAccessWebSiteOptions()
    {
        //   Get Public access Web site options. This function is disabled for teaming.
        $rc = $this->GetPublicAccessWebSiteOptions(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocationConfiguration()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLocationConfiguration($locationId, $locationName)
    {
        //   Update  WISPr location configuration settings. 
        $rc = $this->UpdateLocationConfiguration(array("locationId" => $locationId, "locationName" => $locationName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocationConfiguration()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLocationConfiguration()
    {
        //   Get WISPr location configuration settings. 
        $rc = $this->GetLocationConfiguration(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWISPRoamingConfiguration()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateWISPRoamingConfiguration($loginURL, $logoffURL, $abortLoginURL)
    {
        //   Update WISPr login, logoff and abort login URLs configuration settings. 
        $rc = $this->UpdateWISPRoamingConfiguration(array("loginURL" => $loginURL, "logoffURL" => $logoffURL, "abortLoginURL" => $abortLoginURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWISPRoamingConfiguration()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetWISPRoamingConfiguration()
    {
        //   Get WISPr login, logout and abort login URLs configuration settings. 
        $rc = $this->GetWISPRoamingConfiguration(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSecuritySensor()
//
    function soapUpdateSecuritySensor($securitySensorState, $sensorMode, $ipAddress, $nbOfRetry, $timeout)
    {
        //   Update Security Sensor settings. 
        $rc = $this->UpdateSecuritySensor(array("securitySensorState" => $securitySensorState, "sensorMode" => $sensorMode, "ipAddress" => $ipAddress, "nbOfRetry" => $nbOfRetry, "timeout" => $timeout));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSecuritySensor()
//
    function soapGetSecuritySensor()
    {
        //   Get Security Sensor settings. 
        $rc = $this->GetSecuritySensor(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccessControllerAddress()
//
    function soapUpdateAccessControllerAddress($defaultGatewayState, $macAddr)
    {
        //   Update Access Controller MAC Address. 
        $rc = $this->UpdateAccessControllerAddress(array("defaultGatewayState" => $defaultGatewayState, "macAddr" => $macAddr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccessControllerAddress()
//
    function soapGetAccessControllerAddress()
    {
        //   Get Access Controller MAC Address. 
        $rc = $this->GetAccessControllerAddress(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccessControllerStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetAccessControllerStatus()
    {
        //   Get Access Controller Status. 
        $rc = $this->GetAccessControllerStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateClientStationSecurity()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateClientStationSecurity($vscName, $allowAccessRADIUSDownState, $interval, $nbOfRetry)
    {
        //   Update Client Station Security settings. 
        $rc = $this->UpdateClientStationSecurity(array("vscName" => $vscName, "allowAccessRADIUSDownState" => $allowAccessRADIUSDownState, "interval" => $interval, "nbOfRetry" => $nbOfRetry));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetClientStationSecurity()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetClientStationSecurity($vscName)
    {
        //   Get Client Station security settings. 
        $rc = $this->GetClientStationSecurity(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteForceSecurityAuth()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapExecuteForceSecurityAuth()
    {
        //   Requesting device to perform a RADIUS authentication. 
        $rc = $this->ExecuteForceSecurityAuth(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLastAuthenticatedTime()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetLastAuthenticatedTime()
    {
        //   Get the last authenticaed time. 
        $rc = $this->GetLastAuthenticatedTime(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddNATStaticMapping()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapAddNATStaticMapping($portNb, $protocol, $translatedIPAddress, $translatedPortNb)
    {
        //   Add NAT static mapping entry. 
        $rc = $this->AddNATStaticMapping(array("portNb" => $portNb, "protocol" => $protocol, "translatedIPAddress" => $translatedIPAddress, "translatedPortNb" => $translatedPortNb));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteNATStaticMapping()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteNATStaticMapping($portNb, $protocol)
    {
        //   Delete NAT static mapping entry. 
        $rc = $this->DeleteNATStaticMapping(array("portNb" => $portNb, "protocol" => $protocol));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllNATStaticMappings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteAllNATStaticMappings()
    {
        //   Delete all NAT static mapping entries. 
        $rc = $this->DeleteAllNATStaticMappings(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetNATStaticMappingList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetNATStaticMappingList()
    {
        //   Get all NAT static mapping entries. 
        $rc = $this->GetNATStaticMappingList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetNATStaticMapping()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetNATStaticMapping($portNb, $protocol)
    {
        //   Get NAT static mapping entry. 
        $rc = $this->GetNATStaticMapping(array("portNb" => $portNb, "protocol" => $protocol));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateFirewallMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateFirewallMode($firewallMode, $firewallLevel)
    {
        //   Update Firewall mode. 
        $rc = $this->UpdateFirewallMode(array("firewallMode" => $firewallMode, "firewallLevel" => $firewallLevel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetFirewallMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetFirewallMode()
    {
        //   Get Firewall mode. 
        $rc = $this->GetFirewallMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWirelessNeighborhoodScanMode()
//
    function soapUpdateWirelessNeighborhoodScanMode($scanState, $scanPeriod)
    {
        //   Update Wireless Neighborhood scan mode. 
        $rc = $this->UpdateWirelessNeighborhoodScanMode(array("scanState" => $scanState, "scanPeriod" => $scanPeriod));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessNeighborhoodScanMode()
//
    function soapGetWirelessNeighborhoodScanMode()
    {
        //   Get Wireless Neighborhood Firewall scan mode. 
        $rc = $this->GetWirelessNeighborhoodScanMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessNeighborhoodAuthorizedAPListURL()
//
    function soapGetWirelessNeighborhoodAuthorizedAPListURL()
    {
        //   Get URL for list of Authorized APs in Wireless Neighborhood. 
        $rc = $this->GetWirelessNeighborhoodAuthorizedAPListURL(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWirelessNeighborhoodAuthorizedAPListURL()
//
    function soapUpdateWirelessNeighborhoodAuthorizedAPListURL($authorizedAPListURL)
    {
        //   Update URL for list of Authorized APs in Wireless Neighborhood. 
        $rc = $this->UpdateWirelessNeighborhoodAuthorizedAPListURL(array("authorizedAPListURL" => $authorizedAPListURL));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteSwapLanInternetJacks()
//
    function soapExecuteSwapLanInternetJacks()
    {
        //   Swap LAN/Internet jacks. 
        $rc = $this->ExecuteSwapLanInternetJacks(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateScheduledFirmwareUpdate()
//
    function soapUpdateScheduledFirmwareUpdate($scheduleState, $day, $time, $url)
    {
        //   Update Firmware Scheduled Updates Settings. 
        $rc = $this->UpdateScheduledFirmwareUpdate(array("scheduleState" => $scheduleState, "day" => $day, "time" => $time, "url" => $url));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetScheduledFirmwareUpdate()
//
    function soapGetScheduledFirmwareUpdate()
    {
        //   Get Firmware Scheduled Updates Settings. 
        $rc = $this->GetScheduledFirmwareUpdate(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteSystemRestart()
//
    function soapExecuteSystemRestart()
    {
        //   Execute a System Restart. 
        $rc = $this->ExecuteSystemRestart(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateNetworkTraceSettings()
//
    function soapUpdateNetworkTraceSettings($destination, $url, $timeout, $numberOfPackets, $packetSize, $filter)
    {
        //  Update Network Trace Settings. 
        $rc = $this->UpdateNetworkTraceSettings(array("destination" => $destination, "url" => $url, "timeout" => $timeout, "numberOfPackets" => $numberOfPackets, "packetSize" => $packetSize, "filter" => $filter));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetNetworkTraceSettings()
//
    function soapGetNetworkTraceSettings()
    {
        //  Get Network Trace Settings. 
        $rc = $this->GetNetworkTraceSettings(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPLeasesStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetDHCPLeasesStatus()
    {
        //   Get the list of DHCP leases. 
        $rc = $this->GetDHCPLeasesStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessPortsStatus()
//
    function soapGetWirelessPortsStatus()
    {
        //   Get Wireless Ports status. 
        $rc = $this->GetWirelessPortsStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetWirelessPortsStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetWirelessPortsStatus($macAddress)
    {
        //   Get Wireless Ports status of a remote AP. 
        $rc = $this->ControlledNetworkGetWirelessPortsStatus(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessAssociatedClientStatus()
//
    function soapGetWirelessAssociatedClientStatus()
    {
        //   Get Wireless Associated Clients status. 
        $rc = $this->GetWirelessAssociatedClientStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetWirelessAssociatedClientStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetWirelessAssociatedClientStatus($entityName)
    {
        //   Get Wireless Associated Clients status. 
        $rc = $this->ControlledNetworkGetWirelessAssociatedClientStatus(array("entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessAssociatedClientDataRates()
//
    function soapGetWirelessAssociatedClientDataRates()
    {
        //   Get Wireless Associated Clients data rates status. 
        $rc = $this->GetWirelessAssociatedClientDataRates(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetWirelessAssociatedClientDataRates()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetWirelessAssociatedClientDataRates($entityName)
    {
        //   Get Wireless Associated Clients data rates status. 
        $rc = $this->ControlledNetworkGetWirelessAssociatedClientDataRates(array("entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetInterfaceStatus()
//
    function soapGetInterfaceStatus()
    {
        //   Get Interface status. 
        $rc = $this->GetInterfaceStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetInterfaceStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetInterfaceStatus($entityName)
    {
        //   Get Interface status. 
        $rc = $this->ControlledNetworkGetInterfaceStatus(array("entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetWirelessBSSID()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH,
// GOLDFISH.
    function soapControlledNetworkGetWirelessBSSID($entityName)
    {
        //   Get Wireless BSSID status and statistics of a remote AP. 
        $rc = $this->ControlledNetworkGetWirelessBSSID(array("entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessClientNeighborhoodStatus()
//
    function soapGetWirelessClientNeighborhoodStatus()
    {
        //   Get list of APs detected by the Wireless driver. Those AP were passively detected.
        $rc = $this->GetWirelessClientNeighborhoodStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessNeighborhoodStatus()
//
    function soapGetWirelessNeighborhoodStatus()
    {
        //   Get list of APs detected by the Wireless driver. Those AP were passively detected.
        $rc = $this->GetWirelessNeighborhoodStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessRogueAPStatus()
//
    function soapGetWirelessRogueAPStatus()
    {
        //   Get list of Rogue APs, that is detected APs not in the list of official APs.
        $rc = $this->GetWirelessRogueAPStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSatelliteListStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetSatelliteListStatus()
    {
        //   Get Detected Satellites status. 
        $rc = $this->GetSatelliteListStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWDSInterfacesStatus()
//
    function soapGetWDSInterfacesStatus()
    {
        //   Get status for WDS interfaces.
        $rc = $this->GetWDSInterfacesStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDWDSPossiblePeerListStatus()
//
    function soapGetDWDSPossiblePeerListStatus()
    {
        //  Get list of detected unlinked DWDS peers.
        $rc = $this->GetDWDSPossiblePeerListStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAuthenticatedAccessControlledUserList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAuthenticatedAccessControlledUserList()
    {
        //   Get list of authenticated users (AC). 
        $rc = $this->GetAuthenticatedAccessControlledUserList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAuthenticatedNonAccessControlledUserList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetAuthenticatedNonAccessControlledUserList()
    {
        //   Get list of authenticated users (non-AC). 
        $rc = $this->GetAuthenticatedNonAccessControlledUserList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerTotalStatistics()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerTotalStatistics()
    {
        //  Get the RADIUS server total statistics.
        $rc = $this->GetRADIUSServerTotalStatistics(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSServerClientStatistics()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetRADIUSServerClientStatistics()
    {
        //  Get the RADIUS server per client statistics.
        $rc = $this->GetRADIUSServerClientStatistics(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCDHCPRelay()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCDHCPRelay($vscName, $primaryIP, $secondaryIP, $circuitID, $remoteID, $giAddr, $giMask, $state, $forwardToEgressInterface)
    {
        //   Update DHCP relay on Virtual SC settings. 
        $rc = $this->UpdateVirtualSCDHCPRelay(array("vscName" => $vscName, "primaryIP" => $primaryIP, "secondaryIP" => $secondaryIP, "circuitID" => $circuitID, "remoteID" => $remoteID, "giAddr" => $giAddr, "giMask" => $giMask, "state" => $state, "forwardToEgressInterface" => $forwardToEgressInterface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCDHCPRelay()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCDHCPRelay($vscName)
    {
        //   Get DHCP relay on Virtual SC settings. 
        $rc = $this->GetVirtualSCDHCPRelay(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCDHCPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCDHCPServer($vscName, $rangeBegin, $rangeEnd, $dnsAddr, $gatewayAddr, $subnetAddr, $maskAddr, $state)
    {
        //   Update DHCP server on Virtual SC settings. 
        $rc = $this->UpdateVirtualSCDHCPServer(array("vscName" => $vscName, "rangeBegin" => $rangeBegin, "rangeEnd" => $rangeEnd, "dnsAddr" => $dnsAddr, "gatewayAddr" => $gatewayAddr, "subnetAddr" => $subnetAddr, "maskAddr" => $maskAddr, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddDHCPFixedLease()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapAddDHCPFixedLease($MACAddress, $IPAddress, $UID)
    {
        //   Add DHCP Fixed Lease. 
        $rc = $this->AddDHCPFixedLease(array("MACAddress" => $MACAddress, "IPAddress" => $IPAddress, "UID" => $UID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteDHCPFixedLease()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteDHCPFixedLease($MACAddress, $IPAddress, $UID)
    {
        //   Delete DHCP Fixed Lease. 
        $rc = $this->DeleteDHCPFixedLease(array("MACAddress" => $MACAddress, "IPAddress" => $IPAddress, "UID" => $UID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPFixedLeases()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetDHCPFixedLeases()
    {
        //   Get DHCP Fixed Leases. 
        $rc = $this->GetDHCPFixedLeases(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCDHCPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCDHCPServer($vscName)
    {
        //   Get DHCP server on Virtual SC settings. 
        $rc = $this->GetVirtualSCDHCPServer(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPAddressAllocationMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetIPAddressAllocationMode()
    {
        //   Get IP Address Allocation mode.
        $rc = $this->GetIPAddressAllocationMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIPAddressAllocationMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateIPAddressAllocationMode($addressAllocationMode)
    {
        //   Update IP Address Allocation mode.
        $rc = $this->UpdateIPAddressAllocationMode(array("addressAllocationMode" => $addressAllocationMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetDHCPServer()
    {
        //   Get DHCP Server settings. 
        $rc = $this->GetDHCPServer(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDHCPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateDHCPServer($domainName, $leaseDuration, $firstIPAddressInRange, $lastIPAddressInRange, $subnetMask, $gatewayIPAddress, $domainNameServers)
    {
        //   Update DHCP Server settings. 
        $rc = $this->UpdateDHCPServer(array("domainName" => $domainName, "leaseDuration" => $leaseDuration, "firstIPAddressInRange" => $firstIPAddressInRange, "lastIPAddressInRange" => $lastIPAddressInRange, "subnetMask" => $subnetMask, "gatewayIPAddress" => $gatewayIPAddress, "domainNameServers" => $domainNameServers));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDHCPServerDiscoveryState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateDHCPServerDiscoveryState($state)
    {
        //   Update DHCP Server Service Controller discovery state. 
        $rc = $this->UpdateDHCPServerDiscoveryState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLogoutHtmlUserOnDiscover()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateLogoutHtmlUserOnDiscover($state)
    {
        //   Update logout html user on discover settings. 
        $rc = $this->UpdateLogoutHtmlUserOnDiscover(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLogoutHtmlUserOnDiscover()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetLogoutHtmlUserOnDiscover()
    {
        //   Get logout html user on discover settings. 
        $rc = $this->GetLogoutHtmlUserOnDiscover(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPServerDiscoveryState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetDHCPServerDiscoveryState()
    {
        //  Get DHCP Server Service Controller discovery state. 
        $rc = $this->GetDHCPServerDiscoveryState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddDHCPServerDiscoveryAddress()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapAddDHCPServerDiscoveryAddress($ipAddress)
    {
        //  Add DHCP Server Service Controller discovery address. 
        $rc = $this->AddDHCPServerDiscoveryAddress(array("ipAddress" => $ipAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteDHCPServerDiscoveryAddress()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteDHCPServerDiscoveryAddress($ipAddress)
    {
        //  Delete DHCP Server Service Controller discovery address. 
        $rc = $this->DeleteDHCPServerDiscoveryAddress(array("ipAddress" => $ipAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPServerDiscoveryAddress()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetDHCPServerDiscoveryAddress()
    {
        //  Get DHCP Server Service Controller discovery address. 
        $rc = $this->GetDHCPServerDiscoveryAddress(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPRelay()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetDHCPRelay()
    {
        //   Get DHCP Relay settings. 
        $rc = $this->GetDHCPRelay(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDHCPRelay()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateDHCPRelay($primaryServerIPAddress, $secondaryServerIPAddress, $circuitID, $remoteID)
    {
        //   Update DHCP Relay settings. 
        $rc = $this->UpdateDHCPRelay(array("primaryServerIPAddress" => $primaryServerIPAddress, "secondaryServerIPAddress" => $secondaryServerIPAddress, "circuitID" => $circuitID, "remoteID" => $remoteID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPRelayRequestListen()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetDHCPRelayRequestListen()
    {
        //   Get the DHCP Relay interface listen state. 
        $rc = $this->GetDHCPRelayRequestListen(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDHCPRelayRequestListen()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateDHCPRelayRequestListen($listenOnLAN, $listenOnClientDataTunnel)
    {
        //   Update DHCP Relay interface listen state. 
        $rc = $this->UpdateDHCPRelayRequestListen(array("listenOnLAN" => $listenOnLAN, "listenOnClientDataTunnel" => $listenOnClientDataTunnel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPRelayInternetPortExtensionState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetDHCPRelayInternetPortExtensionState()
    {
        //   Get the DHCP Relay "Extend Internet Port" state. 
        $rc = $this->GetDHCPRelayInternetPortExtensionState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDHCPRelayInternetPortExtensionState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateDHCPRelayInternetPortExtensionState($extendInternetPort)
    {
        //   Update DHCP Relay "Extend Internet Port" state. 
        $rc = $this->UpdateDHCPRelayInternetPortExtensionState(array("extendInternetPort" => $extendInternetPort));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddIPQosProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddIPQosProfile($ipQosProfileName, $protocol, $startPort, $endPort, $priority)
    {
        //   Add IP QOS profile. 
        $rc = $this->AddIPQosProfile(array("ipQosProfileName" => $ipQosProfileName, "protocol" => $protocol, "startPort" => $startPort, "endPort" => $endPort, "priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIPQosProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateIPQosProfile($ipQosProfileName, $protocol, $startPort, $endPort, $priority)
    {
        //   Update a IP QOS profile. 
        $rc = $this->UpdateIPQosProfile(array("ipQosProfileName" => $ipQosProfileName, "protocol" => $protocol, "startPort" => $startPort, "endPort" => $endPort, "priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIPQosProfileName()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateIPQosProfileName($oldIPQosProfileName, $newIPQosProfileName)
    {
        //   Update a IP QOS profile name. 
        $rc = $this->UpdateIPQosProfileName(array("oldIPQosProfileName" => $oldIPQosProfileName, "newIPQosProfileName" => $newIPQosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPQosProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetIPQosProfile($ipQosProfileName)
    {
        //   Get IP QOS Profile settings. 
        $rc = $this->GetIPQosProfile(array("ipQosProfileName" => $ipQosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPQosProfileUniqueId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetIPQosProfileUniqueId($profileName)
    {
        //   Retreive the IP QoS Profile's unique Id. 
        $rc = $this->GetIPQosProfileUniqueId(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteIPQosProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteIPQosProfile($ipQosProfileName)
    {
        //   Delete IP QOS profile. 
        $rc = $this->DeleteIPQosProfile(array("ipQosProfileName" => $ipQosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllIPQosProfiles()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteAllIPQosProfiles()
    {
        //   Delete all IP QOS profiles. 
        $rc = $this->DeleteAllIPQosProfiles(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIPQosProfileList()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetIPQosProfileList()
    {
        //   Get IP QOS profile list. 
        $rc = $this->GetIPQosProfileList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCList()
//
    function soapGetVirtualSCList()
    {
        //   Get list of existing Virtual SCs 
        $rc = $this->GetVirtualSCList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPlaceHolderURLEncodingMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetPlaceHolderURLEncodingMode()
    {
        //   Get current URLEncoding mode of %o placeholder. 
        $rc = $this->GetPlaceHolderURLEncodingMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePlaceHolderURLEncodingMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdatePlaceHolderURLEncodingMode($state)
    {
        //   Update URLEncoding mode for placeholder %o. 
        $rc = $this->UpdatePlaceHolderURLEncodingMode(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateClientStationSecurityAllowAnyIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateClientStationSecurityAllowAnyIP($vscName, $allowAnyIPState, $useDynamicIPState)
    {
        //   Update Client Station Security settings. 
        $rc = $this->UpdateClientStationSecurityAllowAnyIP(array("vscName" => $vscName, "allowAnyIPState" => $allowAnyIPState, "useDynamicIPState" => $useDynamicIPState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetClientStationSecurityAllowAnyIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetClientStationSecurityAllowAnyIP($vscName)
    {
        //   Get Client Station security settings. 
        $rc = $this->GetClientStationSecurityAllowAnyIP(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateClientStationSecurityHttpProxy()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateClientStationSecurityHttpProxy($vscName, $httpProxyState, $httpProxyForHTMLOnlyState)
    {
        //   Update Client Station Security settings. 
        $rc = $this->UpdateClientStationSecurityHttpProxy(array("vscName" => $vscName, "httpProxyState" => $httpProxyState, "httpProxyForHTMLOnlyState" => $httpProxyForHTMLOnlyState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetClientStationSecurityHttpProxy()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetClientStationSecurityHttpProxy($vscName)
    {
        //   Get Client Station security settings. 
        $rc = $this->GetClientStationSecurityHttpProxy(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateClientStationSecuritySmtpProxy()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateClientStationSecuritySmtpProxy($vscName, $smtpProxyState)
    {
        //   Update Client Station Security settings. 
        $rc = $this->UpdateClientStationSecuritySmtpProxy(array("vscName" => $vscName, "smtpProxyState" => $smtpProxyState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetClientStationSecuritySmtpProxy()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetClientStationSecuritySmtpProxy($vscName)
    {
        //   Get Client Station security settings. 
        $rc = $this->GetClientStationSecuritySmtpProxy(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateNOCReplyMessageState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateNOCReplyMessageState($vscName, $nocReplyMessageState)
    {
        //   Update NOC reply message setting. 
        $rc = $this->UpdateNOCReplyMessageState(array("vscName" => $vscName, "nocReplyMessageState" => $nocReplyMessageState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetNOCReplyMessageState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetNOCReplyMessageState($vscName)
    {
        //   Get current setting for NOC reply message. 
        $rc = $this->GetNOCReplyMessageState(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCMACAuthTimeout()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateVirtualSCMACAuthTimeout($vscName, $macAuthTimeout)
    {
        //   Update timeout value for MAC authentication. 
        $rc = $this->UpdateVirtualSCMACAuthTimeout(array("vscName" => $vscName, "macAuthTimeout" => $macAuthTimeout));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCMACAuthTimeout()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCMACAuthTimeout($vscName)
    {
        //   Get timeout value for MAC authentication. 
        $rc = $this->GetVirtualSCMACAuthTimeout(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetMACAuthTimeout()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetMACAuthTimeout($vscName)
    {
        //   Get timeout value for MAC authentication. 
        $rc = $this->GetMACAuthTimeout(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAutomationHTTPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateAutomationHTTPServer($automationHTTPServerIPAddress, $automationHTTPServerPort)
    {
        //   Update settings for Automation HTTP Server. 
        $rc = $this->UpdateAutomationHTTPServer(array("automationHTTPServerIPAddress" => $automationHTTPServerIPAddress, "automationHTTPServerPort" => $automationHTTPServerPort));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAutomationHTTPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetAutomationHTTPServer()
    {
        //   Get current settings for Automation HTTP server. Internal Use Only. 
        $rc = $this->GetAutomationHTTPServer(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRADIUSCalledStationIdPort()
//
    function soapUpdateRADIUSCalledStationIdPort($port)
    {
        //  Specifies which port's MAC address to  use for filling Called-Station-ID.
        $rc = $this->UpdateRADIUSCalledStationIdPort(array("port" => $port));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRADIUSCalledStationIdPort()
//
    function soapGetRADIUSCalledStationIdPort()
    {
        //  Get port which MAC address will be used for filling Called-Station-ID.
        $rc = $this->GetRADIUSCalledStationIdPort(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserAgentFilteringMode()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateUserAgentFilteringMode($state)
    {
        //   Update user-agent filtering mode 
        $rc = $this->UpdateUserAgentFilteringMode(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserAgentFilteringMode()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetUserAgentFilteringMode()
    {
        //   Get current user-agent filtering mode 
        $rc = $this->GetUserAgentFilteringMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddBlockedUserAgent()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddBlockedUserAgent($userAgent)
    {
        //   Add a user agent to block. 
        $rc = $this->AddBlockedUserAgent(array("userAgent" => $userAgent));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteBlockedUserAgent()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteBlockedUserAgent($userAgent)
    {
        //   Delete a user agent from the blocked agents list. 
        $rc = $this->DeleteBlockedUserAgent(array("userAgent" => $userAgent));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllBlockedUserAgents()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteAllBlockedUserAgents()
    {
        //   Remove all user agents.
        $rc = $this->DeleteAllBlockedUserAgents(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetBlockedUserAgents()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetBlockedUserAgents()
    {
        //   Get blocked user agents list. 
        $rc = $this->GetBlockedUserAgents(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPMSRoomInformation()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetPMSRoomInformation($roomNumber)
    {
        //   Get information a list of guests currently checked in for this room. 
        $rc = $this->GetPMSRoomInformation(array("roomNumber" => $roomNumber));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecutePMSBillToRoom()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapExecutePMSBillToRoom($transactionNumber, $guestLastName, $guestFirstName, $reservationNumber, $roomNumber, $itemNumber, $description, $chargeAmount, $taxAmount1, $taxAmount2, $taxAmount3, $totalAmount, $currencyCode, $custom1, $custom2, $custom3, $custom4, $custom5)
    {
        //   Post a charge to a specific room. 
        $rc = $this->ExecutePMSBillToRoom(array("transactionNumber" => $transactionNumber, "guestLastName" => $guestLastName, "guestFirstName" => $guestFirstName, "reservationNumber" => $reservationNumber, "roomNumber" => $roomNumber, "itemNumber" => $itemNumber, "description" => $description, "chargeAmount" => $chargeAmount, "taxAmount1" => $taxAmount1, "taxAmount2" => $taxAmount2, "taxAmount3" => $taxAmount3, "totalAmount" => $totalAmount, "currencyCode" => $currencyCode, "custom1" => $custom1, "custom2" => $custom2, "custom3" => $custom3, "custom4" => $custom4, "custom5" => $custom5));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPMSOperationalStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetPMSOperationalStatus()
    {
        //   Get the operational status of the PMS connection. 
        $rc = $this->GetPMSOperationalStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPMSStatistics()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetPMSStatistics()
    {
        //   Get the statistics for the PMS connection. 
        $rc = $this->GetPMSStatistics(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateNOCAuthenticationMode()
//
    function soapUpdateNOCAuthenticationMode($state)
    {
        //   Update NOC Authentication mode 
        $rc = $this->UpdateNOCAuthenticationMode(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetNOCAuthenticationMode()
//
    function soapGetNOCAuthenticationMode()
    {
        //   Get current NOC Authentication mode 
        $rc = $this->GetNOCAuthenticationMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateCoordinatedModeConfigRevision()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapUpdateCoordinatedModeConfigRevision($configRevision)
    {
        //   Update the coordinated mode persistent configuration revision. 
        $rc = $this->UpdateCoordinatedModeConfigRevision(array("configRevision" => $configRevision));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetCoordinatedModeConfigRevision()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetCoordinatedModeConfigRevision()
    {
        //   Get the coordinated mode persistent configuration revision. 
        $rc = $this->GetCoordinatedModeConfigRevision(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetControlledOperationalModeState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetControlledOperationalModeState()
    {
        //   Get the controlled operational mode state
        $rc = $this->GetControlledOperationalModeState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteApplyRadioConfiguration()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapExecuteApplyRadioConfiguration()
    {
        //   Apply radio(s) configuration. 
        $rc = $this->ExecuteApplyRadioConfiguration(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteSwitchOperationalMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapExecuteSwitchOperationalMode()
    {
        //   Switches the unit's operational mode. 
        $rc = $this->ExecuteSwitchOperationalMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCL2FastAuthentication()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapUpdateVirtualSCL2FastAuthentication($vscName, $state)
    {
        //   Update state of WPA2 opportunistic key caching. 
        $rc = $this->UpdateVirtualSCL2FastAuthentication(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCL3MobilityState()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapUpdateVirtualSCL3MobilityState($vscName, $state)
    {
        //   Update state of Layer 3 mobility. 
        $rc = $this->UpdateVirtualSCL3MobilityState(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCL3Mobility()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapUpdateVirtualSCL3Mobility($vscName, $state, $homeNetworkSelectionMethod, $homeNetworkSelectionFallbackMethod)
    {
        //   Update Layer 3 mobility settings. 
        $rc = $this->UpdateVirtualSCL3Mobility(array("vscName" => $vscName, "state" => $state, "homeNetworkSelectionMethod" => $homeNetworkSelectionMethod, "homeNetworkSelectionFallbackMethod" => $homeNetworkSelectionFallbackMethod));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCL2FastAuthentication()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetVirtualSCL2FastAuthentication($vscName)
    {
        //   Get state of WPA2 opportunistic key caching. 
        $rc = $this->GetVirtualSCL2FastAuthentication(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCL3MobilityState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetVirtualSCL3MobilityState($vscName)
    {
        //   Get state of Layer 3 mobility. 
        $rc = $this->GetVirtualSCL3MobilityState(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCL3Mobility()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetVirtualSCL3Mobility($vscName)
    {
        //   Get Layer 3 mobility settings. 
        $rc = $this->GetVirtualSCL3Mobility(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioChannelAndMode()
//
    function soapUpdateRadioChannelAndMode($deviceId, $autoState, $channel, $radioOperatingMode, $radioPhyType)
    {
        //   Update radio frequency channel. 
        $rc = $this->UpdateRadioChannelAndMode(array("deviceId" => $deviceId, "autoState" => $autoState, "channel" => $channel, "radioOperatingMode" => $radioOperatingMode, "radioPhyType" => $radioPhyType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioChannelAndMode()
//
    function soapGetRadioChannelAndMode($deviceId)
    {
        //   Get radio frequency channel and mode. 
        $rc = $this->GetRadioChannelAndMode(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioDescription()
//
    function soapUpdateRadioDescription($deviceId, $description)
    {
        //   Update radio description. 
        $rc = $this->UpdateRadioDescription(array("deviceId" => $deviceId, "description" => $description));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioDescription()
//
    function soapGetRadioDescription($deviceId)
    {
        //   Get radio description. 
        $rc = $this->GetRadioDescription(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCRadioAssignation()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateVirtualSCRadioAssignation($vscName, $stateRadio1, $stateRadio2, $stateRadio3)
    {
        //   Update Virtual SC Radio Assignation. 
        $rc = $this->UpdateVirtualSCRadioAssignation(array("vscName" => $vscName, "stateRadio1" => $stateRadio1, "stateRadio2" => $stateRadio2, "stateRadio3" => $stateRadio3));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCRadioAssignation()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetVirtualSCRadioAssignation($vscName)
    {
        //   Get Virtual SC Radio Assignation. 
        $rc = $this->GetVirtualSCRadioAssignation(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteBeginProvisioningConfiguration()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapExecuteBeginProvisioningConfiguration()
    {
        //   Begin provisioning configuration. Must be called prior to any provisioning command.
        $rc = $this->ExecuteBeginProvisioningConfiguration(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteEndProvisioningConfiguration()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapExecuteEndProvisioningConfiguration()
    {
        //   End provisioning configuration. Must be called after provisioning configuration is complete. Internal use only.
        $rc = $this->ExecuteEndProvisioningConfiguration(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningDiscoveryState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningDiscoveryState($state)
    {
        //   Update provisioning discovery state. Internal use only.
        $rc = $this->UpdateProvisioningDiscoveryState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetProvisioningDiscoveryState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetProvisioningDiscoveryState()
    {
        //   Get provisioning discovery state. Internal use only.
        $rc = $this->GetProvisioningDiscoveryState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningInterface()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningInterface($state, $connectivityMode, $vlanState, $vlanId, $assignationMode)
    {
        //   Update interface provisioning. Internal use only.
        $rc = $this->UpdateProvisioningInterface(array("state" => $state, "connectivityMode" => $connectivityMode, "vlanState" => $vlanState, "vlanId" => $vlanId, "assignationMode" => $assignationMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddProvisioningInterfaceConnectivityMode()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddProvisioningInterfaceConnectivityMode($connectivityMode)
    {
        //   Add a connectivity mode to the interface provisioning. Internal use only.
        $rc = $this->AddProvisioningInterfaceConnectivityMode(array("connectivityMode" => $connectivityMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetProvisioningInterface()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetProvisioningInterface()
    {
        //   Get provisioned interface settings. Internal use only. 
        $rc = $this->GetProvisioningInterface(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningInterfaceStaticIP()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningInterfaceStaticIP($ipAddress, $ipMask, $ipGateway)
    {
        //   Update provisioned interface static IP addressing. Internal use only.
        $rc = $this->UpdateProvisioningInterfaceStaticIP(array("ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetProvisioningInterfaceStaticIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetProvisioningInterfaceStaticIP()
    {
        //   Get provisioned interface static IP addressing. Internal use only.
        $rc = $this->GetProvisioningInterfaceStaticIP(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningDWDS()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningDWDS($radioId, $radioPhyType, $antennaArraySelection, $groupId, $securityState, $securityMode, $key, $autoFindGroupIdState)
    {
        //   Update DWDS provisioning. Internal use only.
        $rc = $this->UpdateProvisioningDWDS(array("radioId" => $radioId, "radioPhyType" => $radioPhyType, "antennaArraySelection" => $antennaArraySelection, "groupId" => $groupId, "securityState" => $securityState, "securityMode" => $securityMode, "key" => $key, "autoFindGroupIdState" => $autoFindGroupIdState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningCountryCode()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningCountryCode($countryCode)
    {
        //   Update Provisioning Country Code. Internal use only.
        $rc = $this->UpdateProvisioningCountryCode(array("countryCode" => $countryCode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningDNSDomain()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningDNSDomain($domain)
    {
        //   Update provisioning domain name setting. Internal use only.
        $rc = $this->UpdateProvisioningDNSDomain(array("domain" => $domain));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetProvisioningDNSDomain()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetProvisioningDNSDomain()
    {
        //   Get provisioning domain name. Internal use only.
        $rc = $this->GetProvisioningDNSDomain(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddProvisioningDNSServer()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddProvisioningDNSServer($server)
    {
        //   Add DNS server for provisioning. Internal use only.
        $rc = $this->AddProvisioningDNSServer(array("server" => $server));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllProvisioningDNSServers()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteAllProvisioningDNSServers()
    {
        //   Remove all provisioning DNS servers. Internal use only.
        $rc = $this->DeleteAllProvisioningDNSServers(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningServiceIPState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningServiceIPState($state)
    {
        //   Update provisioning service IP state. Internal use only.
        $rc = $this->UpdateProvisioningServiceIPState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetProvisioningServiceIPState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetProvisioningServiceIPState()
    {
        //   Get provisioning service IP state. Internal use only.
        $rc = $this->GetProvisioningServiceIPState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddProvisioningServiceIPAddress()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddProvisioningServiceIPAddress($server)
    {
        //   Add IP address of service entity. Internal use only.
        $rc = $this->AddProvisioningServiceIPAddress(array("server" => $server));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllProvisioningServiceIPAddresses()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteAllProvisioningServiceIPAddresses()
    {
        //   Remove all provisioning service IP addresses. Internal use only.
        $rc = $this->DeleteAllProvisioningServiceIPAddresses(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningServiceDNSNameState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningServiceDNSNameState($state)
    {
        //   Update provisioning DNS name state. Internal use only.
        $rc = $this->UpdateProvisioningServiceDNSNameState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetProvisioningServiceDNSNameState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetProvisioningServiceDNSNameState()
    {
        //   Get provisioning service DNS name state. Internal use only.
        $rc = $this->GetProvisioningServiceDNSNameState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddProvisioningServiceDNS()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddProvisioningServiceDNS($name)
    {
        //   Add DNS name of service entity. Internal use only.
        $rc = $this->AddProvisioningServiceDNS(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllProvisioningServiceDNS()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteAllProvisioningServiceDNS()
    {
        //   Remove all provisioning service DNS. Internal use only.
        $rc = $this->DeleteAllProvisioningServiceDNS(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteSwitchOperationalModeKeepProvisioning()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteSwitchOperationalModeKeepProvisioning()
    {
        //   Switches the unit's operational mode and keep provisioning settings. 
        $rc = $this->ExecuteSwitchOperationalModeKeepProvisioning(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateProvisioningIEEE8021X()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateProvisioningIEEE8021X($state, $eapMethod, $eapIdentity, $eapPassword, $anonymousIdentity)
    {
        //   Update IEEE 802.1X provisioning. Internal use only.
        $rc = $this->UpdateProvisioningIEEE8021X(array("state" => $state, "eapMethod" => $eapMethod, "eapIdentity" => $eapIdentity, "eapPassword" => $eapPassword, "anonymousIdentity" => $anonymousIdentity));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetProvisioningIEEE8021X()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetProvisioningIEEE8021X()
    {
        //   Get provisioned IEEE 802.1X settings. Internal use only. 
        $rc = $this->GetProvisioningIEEE8021X(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateServiceAvailability()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateServiceAvailability($shutdownVSCServices, $shutdownSwitchPorts)
    {
        //   Update service availability. Controls behavior of services upon management entity lost. Internal use only.
        $rc = $this->UpdateServiceAvailability(array("shutdownVSCServices" => $shutdownVSCServices, "shutdownSwitchPorts" => $shutdownSwitchPorts));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetServiceAvailability()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetServiceAvailability()
    {
        //   Get service availability state. Internal use only.
        $rc = $this->GetServiceAvailability(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteStartSSHDebugSession()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteStartSSHDebugSession()
    {
        //   Requesting device to start an SSL debug session. Internal use only.
        $rc = $this->ExecuteStartSSHDebugSession(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddL3MobilitySubnet()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddL3MobilitySubnet($l3MobilitySubnet)
    {
        //  Add an L3 Mobility subnet. Internal use only.
        $rc = $this->AddL3MobilitySubnet(array("l3MobilitySubnet" => $l3MobilitySubnet));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteL3MobilitySubnet()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteL3MobilitySubnet($l3MobilitySubnet)
    {
        //  Delete an L3 Mobility subnet. Internal use only.
        $rc = $this->DeleteL3MobilitySubnet(array("l3MobilitySubnet" => $l3MobilitySubnet));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddGroup()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddGroup($grpName)
    {
        //  This function adds a new group in the controller.
        $rc = $this->ControlledNetworkAddGroup(array("grpName" => $grpName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteGroup()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteGroup($grpName)
    {
        //  This function removes a group from the controller
        $rc = $this->ControlledNetworkDeleteGroup(array("grpName" => $grpName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllGroups()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllGroups()
    {
        //  This function removes all groups from the controller configuration. For internal use only.
        $rc = $this->ControlledNetworkDeleteAllGroups(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateGroup()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateGroup($oldGroupName, $newGroupName)
    {
        //  This function renames a group.
        $rc = $this->ControlledNetworkUpdateGroup(array("oldGroupName" => $oldGroupName, "newGroupName" => $newGroupName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetGroupList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetGroupList()
    {
        //  This function returns the list of all groups' names.
        $rc = $this->ControlledNetworkGetGroupList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddAP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddAP($apName, $macAddr, $productType, $contact, $location, $grpName)
    {
        //  This function adds a new AP in the controller.
        $rc = $this->ControlledNetworkAddAP(array("apName" => $apName, "macAddr" => $macAddr, "productType" => $productType, "contact" => $contact, "location" => $location, "grpName" => $grpName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAP($macAddr)
    {
        //  This function removes an AP from the controller
        $rc = $this->ControlledNetworkDeleteAP(array("macAddr" => $macAddr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllAPs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllAPs()
    {
        //  This function removes all aps from the controller configuration. For internal use only.
        $rc = $this->ControlledNetworkDeleteAllAPs(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateAP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateAP($macAddr, $newAPName, $productType, $contact, $location)
    {
        //   This function update the information about an AP from the controller. No needs to synchronize after this call. 
        $rc = $this->ControlledNetworkUpdateAP(array("macAddr" => $macAddr, "newAPName" => $newAPName, "productType" => $productType, "contact" => $contact, "location" => $location));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateAPGroup()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateAPGroup($macAddr, $grpName)
    {
        //   This function change the group of an AP. You need to synchronize the AP after this call. 
        $rc = $this->ControlledNetworkUpdateAPGroup(array("macAddr" => $macAddr, "grpName" => $grpName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetAP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetAP($macAddr)
    {
        //  This function get the information about an AP from the controller
        $rc = $this->ControlledNetworkGetAP(array("macAddr" => $macAddr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddVirtualSCBinding()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddVirtualSCBinding($grpName, $vscProfile, $egressNetworkState, $networkProfileName, $activeRadio, $locationAwareGroup)
    {
        //  This function adds a new VSC binding in the controller.
        $rc = $this->ControlledNetworkAddVirtualSCBinding(array("grpName" => $grpName, "vscProfile" => $vscProfile, "egressNetworkState" => $egressNetworkState, "networkProfileName" => $networkProfileName, "activeRadio" => $activeRadio, "locationAwareGroup" => $locationAwareGroup));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateVirtualSCBinding()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateVirtualSCBinding($grpName, $vscProfile, $egressNetworkState, $networkProfileName, $activeRadio, $locationAwareGroup)
    {
        //  This function updates a VSC binding in the controller.
        $rc = $this->ControlledNetworkUpdateVirtualSCBinding(array("grpName" => $grpName, "vscProfile" => $vscProfile, "egressNetworkState" => $egressNetworkState, "networkProfileName" => $networkProfileName, "activeRadio" => $activeRadio, "locationAwareGroup" => $locationAwareGroup));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteVirtualSCBinding()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteVirtualSCBinding($grpName, $vscProfile)
    {
        //  This function delete a VSC binding in the controller.
        $rc = $this->ControlledNetworkDeleteVirtualSCBinding(array("grpName" => $grpName, "vscProfile" => $vscProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllVirtualSCBindings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllVirtualSCBindings($grpName)
    {
        //  This function delete all VSC bindingsin the controller. Internal use only.
        $rc = $this->ControlledNetworkDeleteAllVirtualSCBindings(array("grpName" => $grpName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetVirtualSCBinding()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetVirtualSCBinding($grpName, $vscProfile)
    {
        //  This function get the settings of a VSC binding.
        $rc = $this->ControlledNetworkGetVirtualSCBinding(array("grpName" => $grpName, "vscProfile" => $vscProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetVirtualSCBindingList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetVirtualSCBindingList($grpName)
    {
        //  This function get the list VSC binding for a group.
        $rc = $this->ControlledNetworkGetVirtualSCBindingList(array("grpName" => $grpName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteAction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteAction($level, $entityName, $action)
    {
        //  This function execute an action on a list of AP determined by the action.
        $rc = $this->ControlledNetworkExecuteAction(array("level" => $level, "entityName" => $entityName, "action" => $action));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteActionOnAPList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteActionOnAPList($macList, $action)
    {
        //  This function execute an action on a list of AP.
        $rc = $this->ControlledNetworkExecuteActionOnAPList(array("macList" => $macList, "action" => $action));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteRemoveAndRediscover()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteRemoveAndRediscover($level, $entityName, $state)
    {
        //  This function execute a remove and rediscover action on a list of AP determined by the state. The remove part does not affect configuration.
        $rc = $this->ControlledNetworkExecuteRemoveAndRediscover(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetFilteredAPList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetFilteredAPList($level, $entityName, $state)
    {
        //  This function returns a list of filtered APs. It can filter by ControlledNetworkLevel/entityName and current state.
        $rc = $this->ControlledNetworkGetFilteredAPList(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetConfiguredAPList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetConfiguredAPList()
    {
        //  This function returns a list of configured APs.
        $rc = $this->ControlledNetworkGetConfiguredAPList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetDiscoveredAPStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetDiscoveredAPStatus($level, $entityName, $state)
    {
        //   Get the status of discovered APs. 
        $rc = $this->ControlledNetworkGetDiscoveredAPStatus(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteSystemAction()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteSystemAction($macAddr, $action)
    {
        //  This function execute an action on a specific AP. Action possible are : Restart, Reset (to factory default) and Switch (to autonomous mode).
        $rc = $this->ControlledNetworkExecuteSystemAction(array("macAddr" => $macAddr, "action" => $action));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateAPLog()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateAPLog($level, $entityName, $filterOperator, $messageFilterState, $notMessageState, $message, $severityFilterState, $notSeverityState, $severity, $processFilterState, $notProcessState, $process)
    {
        //  This function change the filters applied on logs for a certain entity
        $rc = $this->ControlledNetworkUpdateAPLog(array("level" => $level, "entityName" => $entityName, "filterOperator" => $filterOperator, "messageFilterState" => $messageFilterState, "notMessageState" => $notMessageState, "message" => $message, "severityFilterState" => $severityFilterState, "notSeverityState" => $notSeverityState, "severity" => $severity, "processFilterState" => $processFilterState, "notProcessState" => $notProcessState, "process" => $process));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetAPLog()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetAPLog($level, $entityName)
    {
        //  This function get the filters applied on logs for a certain entity
        $rc = $this->ControlledNetworkGetAPLog(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateAPLogInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateAPLogInheritance($level, $entityName, $state)
    {
        //  This function set the inheritance state of local logs settings for an entity
        $rc = $this->ControlledNetworkUpdateAPLogInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetAPLogInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetAPLogInheritance($level, $entityName)
    {
        //  This function get the inheritance state of local logs settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetAPLogInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLocalMeshProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateLocalMeshProfile($level, $entityName, $index, $name, $state, $dualRadios, $tripleRadios, $dwdsMode, $groupId, $allowedDowntime, $minimumSNR, $SNRPerHop, $securityState, $securityMode, $wepKey, $psk, $maxLinks)
    {
        //   Update Local mesh profile settings. 
        $rc = $this->ControlledNetworkUpdateLocalMeshProfile(array("level" => $level, "entityName" => $entityName, "index" => $index, "name" => $name, "state" => $state, "dualRadios" => $dualRadios, "tripleRadios" => $tripleRadios, "dwdsMode" => $dwdsMode, "groupId" => $groupId, "allowedDowntime" => $allowedDowntime, "minimumSNR" => $minimumSNR, "SNRPerHop" => $SNRPerHop, "securityState" => $securityState, "securityMode" => $securityMode, "wepKey" => $wepKey, "psk" => $psk, "maxLinks" => $maxLinks));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLocalMeshProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetLocalMeshProfile($level, $entityName, $index)
    {
        //   Get Local mesh profile settings. 
        $rc = $this->ControlledNetworkGetLocalMeshProfile(array("level" => $level, "entityName" => $entityName, "index" => $index));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLocalMeshProfileInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLocalMeshProfileInheritance($level, $entityName, $index, $state)
    {
        //  This function set the inheritance state of local mesh profile settings for an entity
        $rc = $this->ControlledNetworkUpdateLocalMeshProfileInheritance(array("level" => $level, "entityName" => $entityName, "index" => $index, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLocalMeshProfileInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetLocalMeshProfileInheritance($level, $entityName, $index)
    {
        //  This function get the inheritance state of local mesh profile settings for an entity
        $rc = $this->ControlledNetworkGetLocalMeshProfileInheritance(array("level" => $level, "entityName" => $entityName, "index" => $index));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLocalMeshProvisioningProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateLocalMeshProvisioningProfile($level, $entityName, $alternateMaster, $useAllRadios)
    {
        //   Update Local mesh provisioning profile settings. 
        $rc = $this->ControlledNetworkUpdateLocalMeshProvisioningProfile(array("level" => $level, "entityName" => $entityName, "alternateMaster" => $alternateMaster, "useAllRadios" => $useAllRadios));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLocalMeshProvisioningProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetLocalMeshProvisioningProfile($level, $entityName)
    {
        //   Get Local mesh provisioning profile settings. 
        $rc = $this->ControlledNetworkGetLocalMeshProvisioningProfile(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkResetLocalMeshProfileStates()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkResetLocalMeshProfileStates($level, $entityName)
    {
        //   Disable all local mesh profiles. 
        $rc = $this->ControlledNetworkResetLocalMeshProfileStates(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLocalMeshProvisioningProfileInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLocalMeshProvisioningProfileInheritance($level, $entityName, $state)
    {
        //  This function set the inheritance state of local mesh provisioning settings for an entity
        $rc = $this->ControlledNetworkUpdateLocalMeshProvisioningProfileInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLocalMeshProvisioningProfileInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetLocalMeshProvisioningProfileInheritance($level, $entityName)
    {
        //  This function get the inheritance state of local mesh provisioning settings for an entity
        $rc = $this->ControlledNetworkGetLocalMeshProvisioningProfileInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLocalMeshIPQoS()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateLocalMeshIPQoS($level, $entityName, $trafficPriority)
    {
        //   Update Local mesh IP QoS settings. 
        $rc = $this->ControlledNetworkUpdateLocalMeshIPQoS(array("level" => $level, "entityName" => $entityName, "trafficPriority" => $trafficPriority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLocalMeshIPQoS()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetLocalMeshIPQoS($level, $entityName)
    {
        //   Get Local mesh IP QoS settings. 
        $rc = $this->ControlledNetworkGetLocalMeshIPQoS(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddLocalMeshIPQoSProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkAddLocalMeshIPQoSProfile($level, $entityName, $profileName)
    {
        //   Add a Local mesh IP QoS profile. 
        $rc = $this->ControlledNetworkAddLocalMeshIPQoSProfile(array("level" => $level, "entityName" => $entityName, "profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteLocalMeshIPQoSProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkDeleteLocalMeshIPQoSProfile($level, $entityName, $profileName)
    {
        //   Delete a Local mesh IP QoS profile. 
        $rc = $this->ControlledNetworkDeleteLocalMeshIPQoSProfile(array("level" => $level, "entityName" => $entityName, "profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllLocalMeshIPQoSProfiles()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkDeleteAllLocalMeshIPQoSProfiles($level, $entityName)
    {
        //   Delete all Local mesh IP QoS profiles. 
        $rc = $this->ControlledNetworkDeleteAllLocalMeshIPQoSProfiles(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLocalMeshIPQoSProfileList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetLocalMeshIPQoSProfileList($level, $entityName)
    {
        //   Get Local mesh IP QoS profile list. 
        $rc = $this->ControlledNetworkGetLocalMeshIPQoSProfileList(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLocalMeshIPQoSInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLocalMeshIPQoSInheritance($level, $entityName, $state)
    {
        //  This function set the inheritance state of local mesh ip qos settings for an entity
        $rc = $this->ControlledNetworkUpdateLocalMeshIPQoSInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLocalMeshIPQoSInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetLocalMeshIPQoSInheritance($level, $entityName)
    {
        //  This function get the inheritance state of local mesh ip qos settings for an entity
        $rc = $this->ControlledNetworkGetLocalMeshIPQoSInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningInterface()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningInterface($level, $entityName, $state, $vlanState, $vlanId, $assignationMode)
    {
        //  This function change the provisioning interface settings.
        $rc = $this->ControlledNetworkUpdateProvisioningInterface(array("level" => $level, "entityName" => $entityName, "state" => $state, "vlanState" => $vlanState, "vlanId" => $vlanId, "assignationMode" => $assignationMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningInterfaceStaticIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningInterfaceStaticIP($level, $entityName, $ipAddress, $ipMask, $ipGateway)
    {
        //  This function change the static IP settings of the provisioning interface.
        $rc = $this->ControlledNetworkUpdateProvisioningInterfaceStaticIP(array("level" => $level, "entityName" => $entityName, "ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningInterface()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningInterface($level, $entityName)
    {
        //  This function get the provisioning interface settings.
        $rc = $this->ControlledNetworkGetProvisioningInterface(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningInterfaceStaticIP()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningInterfaceStaticIP($level, $entityName)
    {
        //  This function get the provisioning interface static IP settings.
        $rc = $this->ControlledNetworkGetProvisioningInterfaceStaticIP(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddProvisioningInterfaceConnectivityMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddProvisioningInterfaceConnectivityMode($level, $entityName, $connectivityMode)
    {
        //  Add a connectivity mode for provisioning.
        $rc = $this->ControlledNetworkAddProvisioningInterfaceConnectivityMode(array("level" => $level, "entityName" => $entityName, "connectivityMode" => $connectivityMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteProvisioningInterfaceConnectivityMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteProvisioningInterfaceConnectivityMode($level, $entityName, $connectivityMode)
    {
        //  Remove a connectivity mode for provisioning.
        $rc = $this->ControlledNetworkDeleteProvisioningInterfaceConnectivityMode(array("level" => $level, "entityName" => $entityName, "connectivityMode" => $connectivityMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllProvisioningInterfaceConnectivityMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllProvisioningInterfaceConnectivityMode($level, $entityName)
    {
        //  Reset connectivity mode to default "Wired" for provisioning.
        $rc = $this->ControlledNetworkDeleteAllProvisioningInterfaceConnectivityMode(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningInterfaceConnectivityModeList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningInterfaceConnectivityModeList($level, $entityName)
    {
        //  Get the connectivity mode list.
        $rc = $this->ControlledNetworkGetProvisioningInterfaceConnectivityModeList(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningCountryCode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningCountryCode($level, $entityName, $countryCode)
    {
        //  This function change country code for provisioning.
        $rc = $this->ControlledNetworkUpdateProvisioningCountryCode(array("level" => $level, "entityName" => $entityName, "countryCode" => $countryCode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningLocalMeshSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningLocalMeshSettings($level, $entityName, $autoFindGroupIdState, $groupId, $securityState, $securityMode, $key)
    {
        //  This function local mesh settings for provisioning.
        $rc = $this->ControlledNetworkUpdateProvisioningLocalMeshSettings(array("level" => $level, "entityName" => $entityName, "autoFindGroupIdState" => $autoFindGroupIdState, "groupId" => $groupId, "securityState" => $securityState, "securityMode" => $securityMode, "key" => $key));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningLocalMeshSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningLocalMeshSettings($level, $entityName)
    {
        //  This function local mesh settings for provisioning.
        $rc = $this->ControlledNetworkGetProvisioningLocalMeshSettings(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningLocalMeshRadioConfiguration()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningLocalMeshRadioConfiguration($level, $entityName, $productType, $radioId, $radioPhyType, $antennaArraySelection)
    {
        //  This function updates local mesh radio configuration for provisioning.
        $rc = $this->ControlledNetworkUpdateProvisioningLocalMeshRadioConfiguration(array("level" => $level, "entityName" => $entityName, "productType" => $productType, "radioId" => $radioId, "radioPhyType" => $radioPhyType, "antennaArraySelection" => $antennaArraySelection));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningLocalMeshRadioConfiguration()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningLocalMeshRadioConfiguration($level, $entityName, $productType)
    {
        //  This function gets local mesh radio configuration for provisioning.
        $rc = $this->ControlledNetworkGetProvisioningLocalMeshRadioConfiguration(array("level" => $level, "entityName" => $entityName, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningInterfaceInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningInterfaceInheritance($level, $entityName, $state)
    {
        //  This function set the inheritance state of provisioning interface settings for an entity
        $rc = $this->ControlledNetworkUpdateProvisioningInterfaceInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningInterfaceInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningInterfaceInheritance($level, $entityName)
    {
        //  This function get the inheritance state of provisioning interface settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetProvisioningInterfaceInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningIEEE8021X()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningIEEE8021X($level, $entityName, $state, $eapMethod, $eapIdentity, $eapPassword, $anonymousIdentity)
    {
        //  This function change the provisioning IEEE 802.1X settings.
        $rc = $this->ControlledNetworkUpdateProvisioningIEEE8021X(array("level" => $level, "entityName" => $entityName, "state" => $state, "eapMethod" => $eapMethod, "eapIdentity" => $eapIdentity, "eapPassword" => $eapPassword, "anonymousIdentity" => $anonymousIdentity));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningIEEE8021X()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningIEEE8021X($level, $entityName)
    {
        //  This function get the provisioning IEEE 802.1X settings.
        $rc = $this->ControlledNetworkGetProvisioningIEEE8021X(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningDiscoveryState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningDiscoveryState($level, $entityName, $state)
    {
        //  This function change the provisioning's state for discovery.
        $rc = $this->ControlledNetworkUpdateProvisioningDiscoveryState(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningDiscoveryState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningDiscoveryState($level, $entityName)
    {
        //  This function get the provisioning's state for discovery.
        $rc = $this->ControlledNetworkGetProvisioningDiscoveryState(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningServiceDNSState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningServiceDNSState($level, $entityName, $state)
    {
        //  This function change the provisioning's state for service DNS.
        $rc = $this->ControlledNetworkUpdateProvisioningServiceDNSState(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningServiceDNSState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningServiceDNSState($level, $entityName)
    {
        //  This function get the provisioning's state for service DNS.
        $rc = $this->ControlledNetworkGetProvisioningServiceDNSState(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningServiceIPState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningServiceIPState($level, $entityName, $state)
    {
        //  This function change the provisioning's state for service IP.
        $rc = $this->ControlledNetworkUpdateProvisioningServiceIPState(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningServiceIPState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningServiceIPState($level, $entityName)
    {
        //  This function get the provisioning's state for service IP.
        $rc = $this->ControlledNetworkGetProvisioningServiceIPState(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningDomainName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningDomainName($level, $entityName, $domainName)
    {
        //  This function change the provisioning domain name.
        $rc = $this->ControlledNetworkUpdateProvisioningDomainName(array("level" => $level, "entityName" => $entityName, "domainName" => $domainName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningDomainName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningDomainName($level, $entityName)
    {
        //  This function get the provisioning domain name.
        $rc = $this->ControlledNetworkGetProvisioningDomainName(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddProvisioningServiceDNSName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddProvisioningServiceDNSName($level, $entityName, $dnsName)
    {
        //  This function add a new dns name to search for.
        $rc = $this->ControlledNetworkAddProvisioningServiceDNSName(array("level" => $level, "entityName" => $entityName, "dnsName" => $dnsName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteProvisioningServiceDNSName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteProvisioningServiceDNSName($level, $entityName, $dnsName)
    {
        //  This function delete a dns name.
        $rc = $this->ControlledNetworkDeleteProvisioningServiceDNSName(array("level" => $level, "entityName" => $entityName, "dnsName" => $dnsName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllProvisioningServiceDNSNames()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllProvisioningServiceDNSNames($level, $entityName)
    {
        //  This function delete all provisioning service dns names. Internal Use only.
        $rc = $this->ControlledNetworkDeleteAllProvisioningServiceDNSNames(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningServiceDNSName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningServiceDNSName($level, $entityName)
    {
        //  This function get the provisioning dns names.
        $rc = $this->ControlledNetworkGetProvisioningServiceDNSName(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddProvisioningServiceIPAddress()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddProvisioningServiceIPAddress($level, $entityName, $ipAddress)
    {
        //  This function add a new IP address to search for.
        $rc = $this->ControlledNetworkAddProvisioningServiceIPAddress(array("level" => $level, "entityName" => $entityName, "ipAddress" => $ipAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteProvisioningServiceIPAddress()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteProvisioningServiceIPAddress($level, $entityName, $ipAddress)
    {
        //  This function delete an IP address.
        $rc = $this->ControlledNetworkDeleteProvisioningServiceIPAddress(array("level" => $level, "entityName" => $entityName, "ipAddress" => $ipAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllProvisioningServiceIPAddresses()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllProvisioningServiceIPAddresses($level, $entityName)
    {
        //  This function delete all provisioning service IP addresses. Internal Use Only.
        $rc = $this->ControlledNetworkDeleteAllProvisioningServiceIPAddresses(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningServiceIPAddress()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningServiceIPAddress($level, $entityName)
    {
        //  This function get the provisioning ip addresses.
        $rc = $this->ControlledNetworkGetProvisioningServiceIPAddress(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningServiceDNSServers()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningServiceDNSServers($level, $entityName, $primaryDNS, $secondaryDNS)
    {
        //  This function update the provisioning DNS servers.
        $rc = $this->ControlledNetworkUpdateProvisioningServiceDNSServers(array("level" => $level, "entityName" => $entityName, "primaryDNS" => $primaryDNS, "secondaryDNS" => $secondaryDNS));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningServiceDNSServers()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningServiceDNSServers($level, $entityName)
    {
        //  This function get the provisioning DNS servers.
        $rc = $this->ControlledNetworkGetProvisioningServiceDNSServers(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateProvisioningDiscoveryInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateProvisioningDiscoveryInheritance($level, $entityName, $state)
    {
        //  This function set the inheritance state of provisioning discovery settings for an entity
        $rc = $this->ControlledNetworkUpdateProvisioningDiscoveryInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetProvisioningDiscoveryInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetProvisioningDiscoveryInheritance($level, $entityName)
    {
        //  This function get the inheritance state of provisioning discovery settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetProvisioningDiscoveryInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateServiceAvailability()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateServiceAvailability($level, $entityName, $state)
    {
        //  This function updates the service availability. It controls behavior of Acces point services upon management entity lost (Controller).
        $rc = $this->ControlledNetworkUpdateServiceAvailability(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetServiceAvailability()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetServiceAvailability($level, $entityName)
    {
        //  This function gets the service availability state.
        $rc = $this->ControlledNetworkGetServiceAvailability(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortServiceAvailability()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortServiceAvailability($level, $entityName, $state)
    {
        //  This function updates the service availability. It controls behavior of switch port services upon management entity lost (Controller).
        $rc = $this->ControlledNetworkUpdateSwitchPortServiceAvailability(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortServiceAvailability()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortServiceAvailability($level, $entityName)
    {
        //  This function gets the service availability state for switch ports.
        $rc = $this->ControlledNetworkGetSwitchPortServiceAvailability(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateServiceAvailabilityInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateServiceAvailabilityInheritance($level, $entityName, $state)
    {
        //  This function sets the inheritance state of the service availability settings for an entity
        $rc = $this->ControlledNetworkUpdateServiceAvailabilityInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetServiceAvailabilityInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetServiceAvailabilityInheritance($level, $entityName)
    {
        //  This function get the inheritance state of the service availability settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetServiceAvailabilityInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioChannelAndMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioChannelAndMode($level, $entityName, $radioId, $productType, $radioState, $autoChannelState, $channel, $radioOperatingMode, $radioPhyType)
    {
        //  This function update the radio's channel and mode.
        $rc = $this->ControlledNetworkUpdateRadioChannelAndMode(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "radioState" => $radioState, "autoChannelState" => $autoChannelState, "channel" => $channel, "radioOperatingMode" => $radioOperatingMode, "radioPhyType" => $radioPhyType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioChannelAndMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioChannelAndMode($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's channel and mode.
        $rc = $this->ControlledNetworkGetRadioChannelAndMode(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioAutoChannelInterval()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioAutoChannelInterval($level, $entityName, $radioId, $productType, $interval)
    {
        //  This function update the radio's auto channel interval.
        $rc = $this->ControlledNetworkUpdateRadioAutoChannelInterval(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "interval" => $interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioAutoChannelInterval()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioAutoChannelInterval($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's auto channel interval.
        $rc = $this->ControlledNetworkGetRadioAutoChannelInterval(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioAutoChannelTimeOfDay()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioAutoChannelTimeOfDay($level, $entityName, $radioId, $productType, $timeOfDay)
    {
        //  This function update the radio's auto channel time of day.
        $rc = $this->ControlledNetworkUpdateRadioAutoChannelTimeOfDay(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "timeOfDay" => $timeOfDay));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioAutoChannelTimeOfDay()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioAutoChannelTimeOfDay($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's auto channel time of day.
        $rc = $this->ControlledNetworkGetRadioAutoChannelTimeOfDay(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddRadioDisabledChannel()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddRadioDisabledChannel($level, $entityName, $radioId, $productType, $channel)
    {
        //  This function add a channel to exclusion list.
        $rc = $this->ControlledNetworkAddRadioDisabledChannel(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "channel" => $channel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteRadioDisabledChannel()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteRadioDisabledChannel($level, $entityName, $radioId, $productType, $channel)
    {
        //  This function removes a channel to exclusion list.
        $rc = $this->ControlledNetworkDeleteRadioDisabledChannel(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "channel" => $channel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllRadioDisabledChannels()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllRadioDisabledChannels($level, $entityName, $radioId, $productType)
    {
        //  This function removes all channels from exclusion list.
        $rc = $this->ControlledNetworkDeleteAllRadioDisabledChannels(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioDisabledChannelList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioDisabledChannelList($level, $entityName, $radioId, $productType)
    {
        //  This function gets the channels exclusion list.
        $rc = $this->ControlledNetworkGetRadioDisabledChannelList(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioAckDistance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioAckDistance($level, $entityName, $radioId, $productType, $distance)
    {
        //   This function update Radio Acknowledge Distance settings. 
        $rc = $this->ControlledNetworkUpdateRadioAckDistance(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "distance" => $distance));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioAckDistance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioAckDistance($level, $entityName, $radioId, $productType)
    {
        //   This function get Radio Acknowledge Distance settings. 
        $rc = $this->ControlledNetworkGetRadioAckDistance(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioPowerControl()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioPowerControl($level, $entityName, $radioId, $productType, $powerControlMode, $powerdBm, $interval)
    {
        //  This function update the radio's power control.
        $rc = $this->ControlledNetworkUpdateRadioPowerControl(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "powerControlMode" => $powerControlMode, "powerdBm" => $powerdBm, "interval" => $interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioPowerControl()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioPowerControl($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's power control.
        $rc = $this->ControlledNetworkGetRadioPowerControl(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioRTSThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioRTSThreshold($level, $entityName, $radioId, $productType, $state, $bytes)
    {
        //  This function update the radio's RTS threshold.
        $rc = $this->ControlledNetworkUpdateRadioRTSThreshold(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "state" => $state, "bytes" => $bytes));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioRTSThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioRTSThreshold($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's RTS threshold.
        $rc = $this->ControlledNetworkGetRadioRTSThreshold(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioMultiCastTxRate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioMultiCastTxRate($level, $entityName, $radioId, $productType, $speed)
    {
        //  This function update the radio's Multicast transfert rate.
        $rc = $this->ControlledNetworkUpdateRadioMultiCastTxRate(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "speed" => $speed));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioMultiCastTxRate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioMultiCastTxRate($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's multicast transfert rate.
        $rc = $this->ControlledNetworkGetRadioMultiCastTxRate(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioAntennaSelection()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioAntennaSelection($level, $entityName, $radioId, $productType, $antenna)
    {
        //  This function update the radio's antenna selection.
        $rc = $this->ControlledNetworkUpdateRadioAntennaSelection(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "antenna" => $antenna));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioAntennaSelection()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioAntennaSelection($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's antenna selection.
        $rc = $this->ControlledNetworkGetRadioAntennaSelection(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioAntennaGain()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// GOLDFISH,
// SUNFISH.
    function soapControlledNetworkUpdateRadioAntennaGain($level, $entityName, $radioId, $productType, $gain)
    {
        //  This function update the radio's antenna gain.
        $rc = $this->ControlledNetworkUpdateRadioAntennaGain(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "gain" => $gain));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioAntennaGain()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// GOLDFISH,
// SUNFISH.
    function soapControlledNetworkGetRadioAntennaGain($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's antenna gain.
        $rc = $this->ControlledNetworkGetRadioAntennaGain(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioAPDistance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioAPDistance($level, $entityName, $radioId, $productType, $distance)
    {
        //  This function update the radio's AP distance.
        $rc = $this->ControlledNetworkUpdateRadioAPDistance(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "distance" => $distance));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioAPDistance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioAPDistance($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's AP Distance.
        $rc = $this->ControlledNetworkGetRadioAPDistance(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioBeaconInterval()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioBeaconInterval($level, $entityName, $radioId, $productType, $interval)
    {
        //  This function update the radio's beacon interval.
        $rc = $this->ControlledNetworkUpdateRadioBeaconInterval(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "interval" => $interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioBeaconInterval()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioBeaconInterval($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's beacon interval.
        $rc = $this->ControlledNetworkGetRadioBeaconInterval(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioSpectralinkViewState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioSpectralinkViewState($level, $entityName, $radioId, $productType, $state)
    {
        //  This function update the radio's spectralink view state.
        $rc = $this->ControlledNetworkUpdateRadioSpectralinkViewState(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioSpectralinkViewState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioSpectralinkViewState($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's spectralink view state.
        $rc = $this->ControlledNetworkGetRadioSpectralinkViewState(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioStationDetectionState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioStationDetectionState($level, $entityName, $radioId, $productType, $state)
    {
        //  This function update the station detection state.
        $rc = $this->ControlledNetworkUpdateRadioStationDetectionState(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioStationDetectionState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioStationDetectionState($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio's station detection state.
        $rc = $this->ControlledNetworkGetRadioStationDetectionState(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioGuardInterval()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioGuardInterval($level, $entityName, $radioId, $productType, $mode)
    {
        //  This function update the radio Guard interval (802.11n only).
        $rc = $this->ControlledNetworkUpdateRadioGuardInterval(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioGuardInterval()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioGuardInterval($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio guard interval (802.11n only).
        $rc = $this->ControlledNetworkGetRadioGuardInterval(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioTxBeamForming()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioTxBeamForming($level, $entityName, $radioId, $productType, $state)
    {
        //  This function update the radio tx beam forming.
        $rc = $this->ControlledNetworkUpdateRadioTxBeamForming(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioTxBeamForming()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioTxBeamForming($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio tx beam forming.
        $rc = $this->ControlledNetworkGetRadioTxBeamForming(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioChannelWidth()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioChannelWidth($level, $entityName, $radioId, $productType, $width)
    {
        //  This function update the radio Channel Width (802.11n only).
        $rc = $this->ControlledNetworkUpdateRadioChannelWidth(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "width" => $width));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioChannelWidth()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioChannelWidth($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio Channel Width (802.11n only).
        $rc = $this->ControlledNetworkGetRadioChannelWidth(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioChannelExtension()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioChannelExtension($level, $entityName, $radioId, $productType, $extension)
    {
        //  This function update the radio Channel extension (802.11n 2.4GHz 40MHz width only).
        $rc = $this->ControlledNetworkUpdateRadioChannelExtension(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "extension" => $extension));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioChannelExtension()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioChannelExtension($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio Channel Extension (802.11n only).
        $rc = $this->ControlledNetworkGetRadioChannelExtension(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioMulticastModulationCodingScheme()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioMulticastModulationCodingScheme($level, $entityName, $radioId, $productType, $mcs)
    {
        //  This function update the radio Modulation Coding Scheme - supported values are 1, 2, 5.5, 6, 9, 11, 12, 18, 24, 36, 48, 54, MCS0 to MCS15. This function is deprecated, please use ControlledNetworkUpdateRadioMultiCastTxRate instead.
        $rc = $this->ControlledNetworkUpdateRadioMulticastModulationCodingScheme(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "mcs" => $mcs));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioMulticastModulationCodingScheme()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioMulticastModulationCodingScheme($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio Modulation Coding Scheme. This function is deprecated, please use ControlledNetworkGetRadioMultiCastTxRate instead.
        $rc = $this->ControlledNetworkGetRadioMulticastModulationCodingScheme(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioMIMOAntennaMode()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioMIMOAntennaMode($level, $entityName, $radioId, $productType, $mode)
    {
        //  This function update the radio MIMO Antenna Mode (802.11n only).
        $rc = $this->ControlledNetworkUpdateRadioMIMOAntennaMode(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioMIMOAntennaMode()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioMIMOAntennaMode($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio MIMO Antenna Mode (802.11n only).
        $rc = $this->ControlledNetworkGetRadioMIMOAntennaMode(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioMaximumClients()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioMaximumClients($level, $entityName, $radioId, $productType, $clients)
    {
        //   Update the radio maximum number of clients. 
        $rc = $this->ControlledNetworkUpdateRadioMaximumClients(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "clients" => $clients));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioMaximumClients()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioMaximumClients($level, $entityName, $radioId, $productType)
    {
        //   Get the maximum number of clients. 
        $rc = $this->ControlledNetworkGetRadioMaximumClients(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioDescription()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioDescription($level, $entityName, $radioId, $productType, $description)
    {
        //   Update the radio description. 
        $rc = $this->ControlledNetworkUpdateRadioDescription(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "description" => $description));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioDescription()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioDescription($level, $entityName, $radioId, $productType)
    {
        //   Get the radio description. 
        $rc = $this->ControlledNetworkGetRadioDescription(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioRtsCtsProtection()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// SUNFISH,
// ALLPPC,
// ZURICH.
    function soapControlledNetworkUpdateRadioRtsCtsProtection($level, $entityName, $radioId, $productType, $protection)
    {
        //  This function update the radio RTS/CTS protection (802.11n only).
        $rc = $this->ControlledNetworkUpdateRadioRtsCtsProtection(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType, "protection" => $protection));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioRtsCtsProtectionMode()
//
// WARNING: function not supported by following board types:
// OPTIMIST,
// SOLING,
// SUNFISH,
// ALLPPC,
// ZURICH.
    function soapControlledNetworkGetRadioRtsCtsProtectionMode($level, $entityName, $radioId, $productType)
    {
        //  This function get the radio RTS/CTS Protection Mode.
        $rc = $this->ControlledNetworkGetRadioRtsCtsProtectionMode(array("level" => $level, "entityName" => $entityName, "radioId" => $radioId, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRadioInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRadioInheritance($level, $entityName, $productType, $state)
    {
        //  This function set radio's inheritance state 
        $rc = $this->ControlledNetworkUpdateRadioInheritance(array("level" => $level, "entityName" => $entityName, "productType" => $productType, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRadioInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRadioInheritance($level, $entityName, $productType)
    {
        //  This function get inheritance state of radio's settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetRadioInheritance(array("level" => $level, "entityName" => $entityName, "productType" => $productType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSensor()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSensor($level, $entityName, $discoveryMode, $serverIPAddress, $serverID, $networkDetector)
    {
        //  This function update sensor settings.
        $rc = $this->ControlledNetworkUpdateSensor(array("level" => $level, "entityName" => $entityName, "discoveryMode" => $discoveryMode, "serverIPAddress" => $serverIPAddress, "serverID" => $serverID, "networkDetector" => $networkDetector));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSensor()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSensor($level, $entityName)
    {
        //  This function get sensor's settings.
        $rc = $this->ControlledNetworkGetSensor(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSensorInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSensorInheritance($level, $entityName, $state)
    {
        //  This function set sensor's inheritance state 
        $rc = $this->ControlledNetworkUpdateSensorInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSensorInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSensorInheritance($level, $entityName)
    {
        //  This function get inheritance state of sensor's settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetSensorInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSecurity8021X()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSecurity8021X($level, $entityName, $keyGroupState, $keyInterval, $reAuthState, $reAuthInterval, $reAuthTerminationState, $supplicantTimeout, $quietPeriod)
    {
        //  This function update 802.1x security settings.
        $rc = $this->ControlledNetworkUpdateSecurity8021X(array("level" => $level, "entityName" => $entityName, "keyGroupState" => $keyGroupState, "keyInterval" => $keyInterval, "reAuthState" => $reAuthState, "reAuthInterval" => $reAuthInterval, "reAuthTerminationState" => $reAuthTerminationState, "supplicantTimeout" => $supplicantTimeout, "quietPeriod" => $quietPeriod));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSecurity8021X()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSecurity8021X($level, $entityName)
    {
        //  This function get 802.1x security settings.
        $rc = $this->ControlledNetworkGetSecurity8021X(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdate8021XRADIUSAccountingStartDelay()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapControlledNetworkUpdate8021XRADIUSAccountingStartDelay($level, $entityName, $radiusAccountingStartDelay)
    {
        //  This function update 802.1x RADIUS accounting start delay.
        $rc = $this->ControlledNetworkUpdate8021XRADIUSAccountingStartDelay(array("level" => $level, "entityName" => $entityName, "radiusAccountingStartDelay" => $radiusAccountingStartDelay));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGet8021XRADIUSAccountingStartDelay()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapControlledNetworkGet8021XRADIUSAccountingStartDelay($level, $entityName)
    {
        //  This function get 802.1x RADIUS accounting start delay.
        $rc = $this->ControlledNetworkGet8021XRADIUSAccountingStartDelay(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSecurity8021XInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSecurity8021XInheritance($level, $entityName, $state)
    {
        //  This function set 802.1x security inheritance state 
        $rc = $this->ControlledNetworkUpdateSecurity8021XInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSecurity8021XInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSecurity8021XInheritance($level, $entityName)
    {
        //  This function get inheritance state of 802.1x security settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetSecurity8021XInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRADIUSNASId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRADIUSNASId($level, $entityName, $profileName, $nasID)
    {
        //  This function update RADIUS's settings.
        $rc = $this->ControlledNetworkUpdateRADIUSNASId(array("level" => $level, "entityName" => $entityName, "profileName" => $profileName, "nasID" => $nasID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRADIUSNASId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRADIUSNASId($level, $entityName, $profileName)
    {
        //  This function get RADIUS's settings.
        $rc = $this->ControlledNetworkGetRADIUSNASId(array("level" => $level, "entityName" => $entityName, "profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateRADIUSNASIdInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateRADIUSNASIdInheritance($level, $entityName, $profileName, $state)
    {
        //  This function set RADIUS's inheritance state 
        $rc = $this->ControlledNetworkUpdateRADIUSNASIdInheritance(array("level" => $level, "entityName" => $entityName, "profileName" => $profileName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetRADIUSNASIdInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetRADIUSNASIdInheritance($level, $entityName, $profileName)
    {
        //  This function get inheritance state of RADIUS's settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetRADIUSNASIdInheritance(array("level" => $level, "entityName" => $entityName, "profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddL3MobilitySubnet()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddL3MobilitySubnet($level, $entityName, $l3Subnet)
    {
        //  This function add a L3 mobility subnet.
        $rc = $this->ControlledNetworkAddL3MobilitySubnet(array("level" => $level, "entityName" => $entityName, "l3Subnet" => $l3Subnet));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteL3MobilitySubnet()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteL3MobilitySubnet($level, $entityName, $l3Subnet)
    {
        //  This function remove a L3 mobility subnet.
        $rc = $this->ControlledNetworkDeleteL3MobilitySubnet(array("level" => $level, "entityName" => $entityName, "l3Subnet" => $l3Subnet));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllL3MobilitySubnets()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllL3MobilitySubnets($level, $entityName)
    {
        //  This function remove all L3 mobility subnets. Internal use only
        $rc = $this->ControlledNetworkDeleteAllL3MobilitySubnets(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetL3MobilitySubnetList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetL3MobilitySubnetList($level, $entityName)
    {
        //  This function get the L3 mobility subnet list.
        $rc = $this->ControlledNetworkGetL3MobilitySubnetList(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateL3MobilitySubnetListInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateL3MobilitySubnetListInheritance($level, $entityName, $state)
    {
        //  This function set L3 Mobility subnet list inheritance state 
        $rc = $this->ControlledNetworkUpdateL3MobilitySubnetListInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetL3MobilitySubnetListInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetL3MobilitySubnetListInheritance($level, $entityName)
    {
        //  This function get inheritance state of L3 Mobility subnet list settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetL3MobilitySubnetListInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLLDPSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLLDPSettings($level, $entityName, $state, $interval, $holdtime, $userstring)
    {
        //  This function updates LLDP's settings.
        $rc = $this->ControlledNetworkUpdateLLDPSettings(array("level" => $level, "entityName" => $entityName, "state" => $state, "interval" => $interval, "holdtime" => $holdtime, "userstring" => $userstring));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLLDPSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetLLDPSettings($level, $entityName)
    {
        //  This function gets LLDP's settings.
        $rc = $this->ControlledNetworkGetLLDPSettings(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLLDPMEDSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLLDPMEDSettings($level, $entityName, $location, $interval)
    {
        //  This function updates LLDP's Media endpoint discovery settings.
        $rc = $this->ControlledNetworkUpdateLLDPMEDSettings(array("level" => $level, "entityName" => $entityName, "location" => $location, "interval" => $interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLLDPMEDSettings()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetLLDPMEDSettings($level, $entityName)
    {
        //  This function gets LLDP's Media endpoint discovery settings.
        $rc = $this->ControlledNetworkGetLLDPMEDSettings(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLLDPInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLLDPInheritance($level, $entityName, $state)
    {
        //  This function set LLDP's inheritance state.
        $rc = $this->ControlledNetworkUpdateLLDPInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLLDPInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetLLDPInheritance($level, $entityName)
    {
        //  This function get inheritance state of LLDP's settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetLLDPInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLLDPApplicationProfileInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLLDPApplicationProfileInheritance($level, $entityName, $state)
    {
        //  This function set LLDP's inheritance state.
        $rc = $this->ControlledNetworkUpdateLLDPApplicationProfileInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLLDPApplicationProfileInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetLLDPApplicationProfileInheritance($level, $entityName)
    {
        //  This function get inheritance state of LLDP's settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetLLDPApplicationProfileInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddLLDPCivicAddressType()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddLLDPCivicAddressType($level, $entityName, $type, $valueString)
    {
        //   Add a Civic Address Type. 
        $rc = $this->ControlledNetworkAddLLDPCivicAddressType(array("level" => $level, "entityName" => $entityName, "type" => $type, "valueString" => $valueString));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLLDPCivicAddressType()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLLDPCivicAddressType($level, $entityName, $type, $valueString)
    {
        //   Update a Civic Address Type. 
        $rc = $this->ControlledNetworkUpdateLLDPCivicAddressType(array("level" => $level, "entityName" => $entityName, "type" => $type, "valueString" => $valueString));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteLLDPCivicAddressType()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteLLDPCivicAddressType($level, $entityName, $type)
    {
        //   Delete a Civic Address Type. 
        $rc = $this->ControlledNetworkDeleteLLDPCivicAddressType(array("level" => $level, "entityName" => $entityName, "type" => $type));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllLLDPCivicAddressTypes()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllLLDPCivicAddressTypes($level, $entityName)
    {
        //   Delete all Civic Address Types. 
        $rc = $this->ControlledNetworkDeleteAllLLDPCivicAddressTypes(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddNetworkAssignation()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddNetworkAssignation($level, $entityName, $networkDef)
    {
        //  This function adds an network assignation for selected device(s).
        $rc = $this->ControlledNetworkAddNetworkAssignation(array("level" => $level, "entityName" => $entityName, "networkDef" => $networkDef));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteNetworkAssignation()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteNetworkAssignation($level, $entityName, $networkDef)
    {
        //  This function removes an network assignation for selected device(s).
        $rc = $this->ControlledNetworkDeleteNetworkAssignation(array("level" => $level, "entityName" => $entityName, "networkDef" => $networkDef));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllNetworkAssignations()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllNetworkAssignations($level, $entityName)
    {
        //  This function removes all network assignations of selected devices(s). Internal use only.
        $rc = $this->ControlledNetworkDeleteAllNetworkAssignations(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetNetworkAssignationList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetNetworkAssignationList($level, $entityName)
    {
        //  This function gets the list of network assignation of selected device(s).
        $rc = $this->ControlledNetworkGetNetworkAssignationList(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateNetworkAssignationListInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateNetworkAssignationListInheritance($level, $entityName, $state)
    {
        //  This function sets inheritance state of the list of network assinations of selected device(s) 
        $rc = $this->ControlledNetworkUpdateNetworkAssignationListInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetNetworkAssignationListInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetNetworkAssignationListInheritance($level, $entityName)
    {
        //  This function gets inheritance state of the list of network assignations of selected devices, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetNetworkAssignationListInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateBridgeSpanningTreeProtocol()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateBridgeSpanningTreeProtocol($level, $entityName, $state, $priority)
    {
        //  This function update bridge spanning tree protocol settings.
        $rc = $this->ControlledNetworkUpdateBridgeSpanningTreeProtocol(array("level" => $level, "entityName" => $entityName, "state" => $state, "priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetBridgeSpanningTreeProtocol()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetBridgeSpanningTreeProtocol($level, $entityName)
    {
        //  This function get Bridge Spaning Tree Protocol settings.
        $rc = $this->ControlledNetworkGetBridgeSpanningTreeProtocol(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateVLANBridgeSpanningTreeProtocol()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateVLANBridgeSpanningTreeProtocol($level, $entityName, $state, $priority)
    {
        //  This function update vlan bridge spanning tree protocol settings.
        $rc = $this->ControlledNetworkUpdateVLANBridgeSpanningTreeProtocol(array("level" => $level, "entityName" => $entityName, "state" => $state, "priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetVLANBridgeSpanningTreeProtocol()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetVLANBridgeSpanningTreeProtocol($level, $entityName)
    {
        //  This function get VLAN Bridge Spaning Tree Protocol settings.
        $rc = $this->ControlledNetworkGetVLANBridgeSpanningTreeProtocol(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateBridgeSpanningTreeProtocolInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateBridgeSpanningTreeProtocolInheritance($level, $entityName, $state)
    {
        //  This function set Bridge Spanning Tree Protocol inheritance state 
        $rc = $this->ControlledNetworkUpdateBridgeSpanningTreeProtocolInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetBridgeSpanningTreeProtocolInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetBridgeSpanningTreeProtocolInheritance($level, $entityName)
    {
        //  This function get inheritance state of Bridge Spanning Tree Protocol settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetBridgeSpanningTreeProtocolInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateVLANBridgeSpanningTreeProtocolInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateVLANBridgeSpanningTreeProtocolInheritance($level, $entityName, $state)
    {
        //  This function set VLAN Bridge Spanning Tree Protocol inheritance state 
        $rc = $this->ControlledNetworkUpdateVLANBridgeSpanningTreeProtocolInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetVLANBridgeSpanningTreeProtocolInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetVLANBridgeSpanningTreeProtocolInheritance($level, $entityName)
    {
        //  This function get inheritance state of VLAN Bridge Spanning Tree Protocol settings, the level from which the entity inherits and the parent's name.
        $rc = $this->ControlledNetworkGetVLANBridgeSpanningTreeProtocolInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateWirelessIPV6RAFilteringState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateWirelessIPV6RAFilteringState($level, $entityName, $state)
    {
        //  Update IPv6 RA filtering settings.
        $rc = $this->ControlledNetworkUpdateWirelessIPV6RAFilteringState(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetWirelessIPV6RAFilteringState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetWirelessIPV6RAFilteringState($level, $entityName)
    {
        //   Get IPv6 RA Filtering settings. 
        $rc = $this->ControlledNetworkGetWirelessIPV6RAFilteringState(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateWirelessIPV6RAFilteringInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateWirelessIPV6RAFilteringInheritance($level, $entityName, $state)
    {
        //  This function sets the IPv6 RA Filtering inheritance state.
        $rc = $this->ControlledNetworkUpdateWirelessIPV6RAFilteringInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetWirelessIPV6RAFilteringInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetWirelessIPV6RAFilteringInheritance($level, $entityName)
    {
        //  This function gets the IPv6 RA Filtering inheritance state 
        $rc = $this->ControlledNetworkGetWirelessIPV6RAFilteringInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateWirelessIGMPSnoopingHelpersState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateWirelessIGMPSnoopingHelpersState($level, $entityName, $state)
    {
        //   Update IGMP snooping helpers settings. 
        $rc = $this->ControlledNetworkUpdateWirelessIGMPSnoopingHelpersState(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetWirelessIGMPSnoopingHelpersState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetWirelessIGMPSnoopingHelpersState($level, $entityName)
    {
        //   Get IGMP snooping helpers settings. 
        $rc = $this->ControlledNetworkGetWirelessIGMPSnoopingHelpersState(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateWirelessIGMPSnoopingHelpersInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateWirelessIGMPSnoopingHelpersInheritance($level, $entityName, $state)
    {
        //  This function set IGMP Snopping inheritance state 
        $rc = $this->ControlledNetworkUpdateWirelessIGMPSnoopingHelpersInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetWirelessIGMPSnoopingHelpersInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetWirelessIGMPSnoopingHelpersInheritance($level, $entityName)
    {
        //  This function get IGMP Snopping inheritance state 
        $rc = $this->ControlledNetworkGetWirelessIGMPSnoopingHelpersInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteResetRadioCounters()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteResetRadioCounters($macAddr, $deviceId)
    {
        //  This function reset the counters for the specified Radio device on a specific AP.
        $rc = $this->ControlledNetworkExecuteResetRadioCounters(array("macAddr" => $macAddr, "deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteResetVirtualSCWirelessCounters()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteResetVirtualSCWirelessCounters($macAddr, $vscName, $deviceId)
    {
        //  Reset counters for specified Virtual Service community/Radio device on a specific AP.
        $rc = $this->ControlledNetworkExecuteResetVirtualSCWirelessCounters(array("macAddr" => $macAddr, "vscName" => $vscName, "deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteResetWirelessAssociatedClientCounters()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteResetWirelessAssociatedClientCounters($macAddr, $deviceId, $stationMacAddress)
    {
        //  Reset counters for specified wireless client on a radio on a specific AP.
        $rc = $this->ControlledNetworkExecuteResetWirelessAssociatedClientCounters(array("macAddr" => $macAddr, "deviceId" => $deviceId, "stationMacAddress" => $stationMacAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteResetWirelessAssociatedClientRateCounters()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteResetWirelessAssociatedClientRateCounters($macAddr, $deviceId, $stationMacAddress)
    {
        //  Reset rate counters for specified wireless client on a radio on a specific AP.
        $rc = $this->ControlledNetworkExecuteResetWirelessAssociatedClientRateCounters(array("macAddr" => $macAddr, "deviceId" => $deviceId, "stationMacAddress" => $stationMacAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteResetRadioCounters()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteResetRadioCounters($deviceId)
    {
        //  Reset counters for specified Radio device.
        $rc = $this->ExecuteResetRadioCounters(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteResetWirelessAssociatedClientCounters()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteResetWirelessAssociatedClientCounters($deviceId, $macAddress)
    {
        //  Reset counters for specified Radio device.
        $rc = $this->ExecuteResetWirelessAssociatedClientCounters(array("deviceId" => $deviceId, "macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteResetWirelessAssociatedClientRateCounters()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteResetWirelessAssociatedClientRateCounters($deviceId, $macAddress)
    {
        //  Reset counters for specified Radio device.
        $rc = $this->ExecuteResetWirelessAssociatedClientRateCounters(array("deviceId" => $deviceId, "macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteWirelessDisassociateClient()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteWirelessDisassociateClient($macAddress)
    {
        //  Disassociate wireless client from any radio.
        $rc = $this->ExecuteWirelessDisassociateClient(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteControlledWirelessDisassociateClient()
//
// WARNING: function not supported by following board types:
// ZURICH,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapExecuteControlledWirelessDisassociateClient($macAddress)
    {
        //  Disassociate wireless client from any controlled AP and any radio.
        $rc = $this->ExecuteControlledWirelessDisassociateClient(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteResetVirtualSCWirelessCounters()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteResetVirtualSCWirelessCounters($vscName, $deviceId)
    {
        //  Reset counters for specified Virtual Service community/Radio device.
        $rc = $this->ExecuteResetVirtualSCWirelessCounters(array("vscName" => $vscName, "deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddVirtualSCIPQosProfile()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapAddVirtualSCIPQosProfile($vscName, $ipQosProfileName)
    {
        //  Add the specified IP QOS profile to existing list of IP QOS profiles to be used for the VSC.
        $rc = $this->AddVirtualSCIPQosProfile(array("vscName" => $vscName, "ipQosProfileName" => $ipQosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCIPQosProfiles()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetVirtualSCIPQosProfiles($vscName)
    {
        //   Get Virtual SC IP Qos Profile List. 
        $rc = $this->GetVirtualSCIPQosProfiles(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteVirtualSCIPQosProfile()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapDeleteVirtualSCIPQosProfile($vscName, $ipQosProfileName)
    {
        //  Delete the specified IP QOS profile from existing list of IP QOS profiles to be used for the VSC.
        $rc = $this->DeleteVirtualSCIPQosProfile(array("vscName" => $vscName, "ipQosProfileName" => $ipQosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllVirtualSCIPQosProfiles()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapDeleteAllVirtualSCIPQosProfiles($vscName)
    {
        //  Delete all IP QOS profiles from existing list to be used for the VSC.
        $rc = $this->DeleteAllVirtualSCIPQosProfiles(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCForceDataTunnelingState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateVirtualSCForceDataTunnelingState($vscName, $state)
    {
        //  Update the state of the force data tunneling for the VSC.  Data tunneling is can be forced only for devices which are L2 connected to the LAN port of the Service Controller.
        $rc = $this->UpdateVirtualSCForceDataTunnelingState(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCForceDataTunnelingState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCForceDataTunnelingState($vscName)
    {
        //  Get force data tunneling state for the VSC.
        $rc = $this->GetVirtualSCForceDataTunnelingState(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioSpectralinkViewState()
//
    function soapUpdateRadioSpectralinkViewState($deviceId, $state)
    {
        //   Update the radios status 
        $rc = $this->UpdateRadioSpectralinkViewState(array("deviceId" => $deviceId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioSpectralinkViewState()
//
    function soapGetRadioSpectralinkViewState($deviceId)
    {
        //  Get the SpectralinkView state of specified device.
        $rc = $this->GetRadioSpectralinkViewState(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioStationDetectionState()
//
    function soapUpdateRadioStationDetectionState($deviceId, $state)
    {
        //   Update the station detection state 
        $rc = $this->UpdateRadioStationDetectionState(array("deviceId" => $deviceId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioStationDetectionState()
//
    function soapGetRadioStationDetectionState($deviceId)
    {
        //  Get the station detection state of specified device.
        $rc = $this->GetRadioStationDetectionState(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCDisplay8021xSessionPage()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateVirtualSCDisplay8021xSessionPage($vscName, $state)
    {
        //   Enable/Disable display of session page in 802.1X mode for VSC. 
        $rc = $this->UpdateVirtualSCDisplay8021xSessionPage(array("vscName" => $vscName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCDisplay8021xSessionPage()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetVirtualSCDisplay8021xSessionPage($vscName)
    {
        //   Get the 802.1X session page state for VSC. 
        $rc = $this->GetVirtualSCDisplay8021xSessionPage(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSensor()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateSensor($discoveryMode, $serverIPAddress, $serverID, $networkDetector)
    {
        //   Update Sensor settings 
        $rc = $this->UpdateSensor(array("discoveryMode" => $discoveryMode, "serverIPAddress" => $serverIPAddress, "serverID" => $serverID, "networkDetector" => $networkDetector));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSensor()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetSensor()
    {
        //   Get Sensor settings. 
        $rc = $this->GetSensor(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteUpdateServices()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH.
    function soapExecuteUpdateServices($wirelessServicesState, $licenseServicesState, $implicitLicenseState, $switchPortsState)
    {
        //   Update the wireless and license services and indicate if the device currently use an implicit license (i.e. a license given by the Controller). Internal use only.
        $rc = $this->ExecuteUpdateServices(array("wirelessServicesState" => $wirelessServicesState, "licenseServicesState" => $licenseServicesState, "implicitLicenseState" => $implicitLicenseState, "switchPortsState" => $switchPortsState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCMACBasedCalledStationIdContent()
//
    function soapUpdateVirtualSCMACBasedCalledStationIdContent($vscName, $calledStationIdContent)
    {
        //  
        $rc = $this->UpdateVirtualSCMACBasedCalledStationIdContent(array("vscName" => $vscName, "calledStationIdContent" => $calledStationIdContent));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCMACBasedCalledStationIdContent()
//
    function soapGetVirtualSCMACBasedCalledStationIdContent($vscName)
    {
        //  
        $rc = $this->GetVirtualSCMACBasedCalledStationIdContent(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSC8021XCalledStationIdContent()
//
    function soapUpdateVirtualSC8021XCalledStationIdContent($vscName, $calledStationIdContent)
    {
        //  
        $rc = $this->UpdateVirtualSC8021XCalledStationIdContent(array("vscName" => $vscName, "calledStationIdContent" => $calledStationIdContent));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSC8021XCalledStationIdContent()
//
    function soapGetVirtualSC8021XCalledStationIdContent($vscName)
    {
        //  
        $rc = $this->GetVirtualSC8021XCalledStationIdContent(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteNOCLoginRequest()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapExecuteNOCLoginRequest($userIPAddr, $username, $password)
    {
        //   Sends login request. 
        $rc = $this->ExecuteNOCLoginRequest(array("userIPAddr" => $userIPAddr, "username" => $username, "password" => $password));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteNOCLogout()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapExecuteNOCLogout($sessionID)
    {
        //   Forces a customer logout. 
        $rc = $this->ExecuteNOCLogout(array("sessionID" => $sessionID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteNOCReauthentication()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapExecuteNOCReauthentication($sessionID)
    {
        //   Reauthenticates a customer. 
        $rc = $this->ExecuteNOCReauthentication(array("sessionID" => $sessionID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCPhyDataRate()
//
    function soapUpdateVirtualSCPhyDataRate($vscName, $radioPhyType, $dataRate, $dataRateState)
    {
        //  Change the status of a wireless data rate.
        $rc = $this->UpdateVirtualSCPhyDataRate(array("vscName" => $vscName, "radioPhyType" => $radioPhyType, "dataRate" => $dataRate, "dataRateState" => $dataRateState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCPhyDataRate()
//
    function soapGetVirtualSCPhyDataRate($vscName, $radioPhyType, $dataRate)
    {
        //  Get the status of a wireless data rate.
        $rc = $this->GetVirtualSCPhyDataRate(array("vscName" => $vscName, "radioPhyType" => $radioPhyType, "dataRate" => $dataRate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCPhyDataRatesToDefaultValues()
//
    function soapUpdateVirtualSCPhyDataRatesToDefaultValues($vscName, $radioPhyType)
    {
        //  Set all the data rates for a given PhyType to their default values.
        $rc = $this->UpdateVirtualSCPhyDataRatesToDefaultValues(array("vscName" => $vscName, "radioPhyType" => $radioPhyType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateMaxConcurrentAuthenticatedStations()
//
    function soapUpdateMaxConcurrentAuthenticatedStations($maxConcurrentAuthenticatedStations)
    {
        //   Update the maximum number of Concurrent Authenticated Stations.
        $rc = $this->UpdateMaxConcurrentAuthenticatedStations(array("maxConcurrentAuthenticatedStations" => $maxConcurrentAuthenticatedStations));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetMaxConcurrentAuthenticatedStations()
//
    function soapGetMaxConcurrentAuthenticatedStations()
    {
        //   Get the maximum number of Concurrent Authenticated Stations. 
        $rc = $this->GetMaxConcurrentAuthenticatedStations(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAccessControllerState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateAccessControllerState($accessControllerState)
    {
        //   Update Client Station Security settings. 
        $rc = $this->UpdateAccessControllerState(array("accessControllerState" => $accessControllerState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAccessControllerState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetAccessControllerState()
    {
        //   Get Access Controller State. 
        $rc = $this->GetAccessControllerState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioGuardInterval()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapUpdateRadioGuardInterval($deviceId, $mode)
    {
        //   Update radio Guard Interval (802.11n only). 
        $rc = $this->UpdateRadioGuardInterval(array("deviceId" => $deviceId, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioGuardInterval()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapGetRadioGuardInterval($deviceId)
    {
        //   Get Radio Guard Interval (802.11n only). 
        $rc = $this->GetRadioGuardInterval(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioTxBeamForming()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapUpdateRadioTxBeamForming($deviceId, $state)
    {
        //   Update radio beam forming. 
        $rc = $this->UpdateRadioTxBeamForming(array("deviceId" => $deviceId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioTxBeamForming()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapGetRadioTxBeamForming($deviceId)
    {
        //   Get Radio tx beam forming. 
        $rc = $this->GetRadioTxBeamForming(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioMIMOAntennaMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapUpdateRadioMIMOAntennaMode($deviceId, $mode)
    {
        //   Update radio MIMO Antenna Mode (802.11n only). 
        $rc = $this->UpdateRadioMIMOAntennaMode(array("deviceId" => $deviceId, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioMIMOAntennaMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetRadioMIMOAntennaMode($deviceId)
    {
        //   Get Radio MIMO Antenna Mode (802.11n only). 
        $rc = $this->GetRadioMIMOAntennaMode(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioChannelWidth()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapUpdateRadioChannelWidth($deviceId, $mode)
    {
        //   Update radio Radio Channel Width (802.11n only). 
        $rc = $this->UpdateRadioChannelWidth(array("deviceId" => $deviceId, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioChannelWidth()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapGetRadioChannelWidth($deviceId)
    {
        //   Get Radio Channel Width (802.11n only). 
        $rc = $this->GetRadioChannelWidth(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioChannelExtension()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapUpdateRadioChannelExtension($deviceId, $extension)
    {
        //   Update radio Radio Channel extension (802.11n 2.4GHz 40MHz width only). 
        $rc = $this->UpdateRadioChannelExtension(array("deviceId" => $deviceId, "extension" => $extension));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioChannelExtension()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapGetRadioChannelExtension($deviceId)
    {
        //   Get Radio Channel Extension (802.11n only). 
        $rc = $this->GetRadioChannelExtension(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioMulticastModulationCodingScheme()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapUpdateRadioMulticastModulationCodingScheme($deviceId, $mcs)
    {
        //   Update radio Modulation Coding Scheme - supported values are 1, 2, 5.5, 6, 9, 11, 12, 18, 24, 36, 48, 54, MCS0 to MCS15. This function is deprecated, please use UpdateRadioMultiCastTxRate instead. 
        $rc = $this->UpdateRadioMulticastModulationCodingScheme(array("deviceId" => $deviceId, "mcs" => $mcs));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioMulticastModulationCodingScheme()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapGetRadioMulticastModulationCodingScheme($deviceId)
    {
        //   Get Radio Modulation Coding Scheme. This function is deprecated, please use GetRadioMultiCastTxRate instead. 
        $rc = $this->GetRadioMulticastModulationCodingScheme(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioRtsCtsProtection()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// OPTIMIST.
    function soapUpdateRadioRtsCtsProtection($deviceId, $protection)
    {
        //   Update radio RTS/CTS protection (dual 802.11n radios only). 
        $rc = $this->UpdateRadioRtsCtsProtection(array("deviceId" => $deviceId, "protection" => $protection));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioRtsCtsProtectionMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetRadioRtsCtsProtectionMode($deviceId)
    {
        //   Get Radio RTS/CTS protection Mode (dual 802.11n radios only). 
        $rc = $this->GetRadioRtsCtsProtectionMode(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddDeviceDiscoveryInterface()
//
    function soapAddDeviceDiscoveryInterface($interface)
    {
        //   Add a device discovery interface. 
        $rc = $this->AddDeviceDiscoveryInterface(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddDeviceDiscoveryLogicalInterface()
//
    function soapAddDeviceDiscoveryLogicalInterface($networkProfileName)
    {
        //   Add a device discovery logical interface
        $rc = $this->AddDeviceDiscoveryLogicalInterface(array("networkProfileName" => $networkProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteDeviceDiscoveryInterface()
//
    function soapDeleteDeviceDiscoveryInterface($interface)
    {
        //   Remove a device discovery interface. 
        $rc = $this->DeleteDeviceDiscoveryInterface(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteDeviceDiscoveryLogicalInterface()
//
    function soapDeleteDeviceDiscoveryLogicalInterface($networkProfileName)
    {
        //   Remove a device discovery logical interface. 
        $rc = $this->DeleteDeviceDiscoveryLogicalInterface(array("networkProfileName" => $networkProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllDeviceDiscoveryInterfaces()
//
    function soapDeleteAllDeviceDiscoveryInterfaces()
    {
        //   Remove all device discovery interfaces. 
        $rc = $this->DeleteAllDeviceDiscoveryInterfaces(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDeviceDiscoveryInterfaceList()
//
    function soapGetDeviceDiscoveryInterfaceList()
    {
        //   Get device discovery interfaces. 
        $rc = $this->GetDeviceDiscoveryInterfaceList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDeviceDiscoveryLogicalInterfaceList()
//
    function soapGetDeviceDiscoveryLogicalInterfaceList()
    {
        //   Get device discovery logical interfaces. 
        $rc = $this->GetDeviceDiscoveryLogicalInterfaceList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDeviceDiscoveryPriority()
//
    function soapUpdateDeviceDiscoveryPriority($priority)
    {
        //   Update device discovery priority. 
        $rc = $this->UpdateDeviceDiscoveryPriority(array("priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDeviceDiscoveryPriority()
//
    function soapGetDeviceDiscoveryPriority()
    {
        //   Get device discovery priority. 
        $rc = $this->GetDeviceDiscoveryPriority(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateMobilityControllerDiscovery()
//
    function soapUpdateMobilityControllerDiscovery($state, $primaryControllerState, $primaryControllerAddress)
    {
        //   Update mobility controller discovery settings. 
        $rc = $this->UpdateMobilityControllerDiscovery(array("state" => $state, "primaryControllerState" => $primaryControllerState, "primaryControllerAddress" => $primaryControllerAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetMobilityControllerDiscovery()
//
    function soapGetMobilityControllerDiscovery()
    {
        //   Get mobility controller discovery settings. 
        $rc = $this->GetMobilityControllerDiscovery(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRFIdentification()
//
    function soapUpdateRFIdentification($aeroscoutState)
    {
        //   Update RF Identification settings. 
        $rc = $this->UpdateRFIdentification(array("aeroscoutState" => $aeroscoutState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRFIdentification()
//
    function soapGetRFIdentification()
    {
        //   Get RF Identification settings.
        $rc = $this->GetRFIdentification(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePublicIPSubnetState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdatePublicIPSubnetState($publicIPSubnetState, $publicIPSubnetLeaseDuration)
    {
        //   Update Public IP Subnet State settings. 
        $rc = $this->UpdatePublicIPSubnetState(array("publicIPSubnetState" => $publicIPSubnetState, "publicIPSubnetLeaseDuration" => $publicIPSubnetLeaseDuration));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPublicIPSubnetState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetPublicIPSubnetState()
    {
        //   Get Public IP Subnet State settings.
        $rc = $this->GetPublicIPSubnetState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRememberAndAutoReauthenticateState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateRememberAndAutoReauthenticateState($state, $delay)
    {
        //   Update Remember and Auto Reauthenticate HTML users settings. 
        $rc = $this->UpdateRememberAndAutoReauthenticateState(array("state" => $state, "delay" => $delay));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRememberAndAutoReauthenticateState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetRememberAndAutoReauthenticateState()
    {
        //   Get Remember and Auto Reauthenticate HTML users settings.
        $rc = $this->GetRememberAndAutoReauthenticateState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePaymentService()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdatePaymentService($creditCardState, $currency, $taxRate, $serviceType)
    {
        //   Update Authorize.Net payment service settings. This function is disabled for teaming.
        $rc = $this->UpdatePaymentService(array("creditCardState" => $creditCardState, "currency" => $currency, "taxRate" => $taxRate, "serviceType" => $serviceType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPaymentService()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetPaymentService()
    {
        //   Get Authorize.Net payment service settings. This function is disabled for teaming.
        $rc = $this->GetPaymentService(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAuthorizeNetPaymentService()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateAuthorizeNetPaymentService($paymentURL, $loginID, $transactionKey)
    {
        //   Update Authorize.Net payment service settings. This function is disabled for teaming.
        $rc = $this->UpdateAuthorizeNetPaymentService(array("paymentURL" => $paymentURL, "loginID" => $loginID, "transactionKey" => $transactionKey));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAuthorizeNetPaymentService()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetAuthorizeNetPaymentService()
    {
        //   Get Authorize.Net payment service settings. This function is disabled for teaming.
        $rc = $this->GetAuthorizeNetPaymentService(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWorldPayPaymentService()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateWorldPayPaymentService($paymentURL, $installationID, $paymentResponsePassword)
    {
        //   Update WorldPay payment service settings. This function is disabled for teaming.
        $rc = $this->UpdateWorldPayPaymentService(array("paymentURL" => $paymentURL, "installationID" => $installationID, "paymentResponsePassword" => $paymentResponsePassword));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWorldPayPaymentService()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetWorldPayPaymentService()
    {
        //   Get WorldPay payment service settings. This function is disabled for teaming.
        $rc = $this->GetWorldPayPaymentService(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePayPalPaymentService()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdatePayPalPaymentService($paypalUserID, $paypalPassword, $paypalSignature, $paypalOpMode, $paypalCustomUrl)
    {
        //   Update PayPal payment service settings. This function is disabled for teaming.
        $rc = $this->UpdatePayPalPaymentService(array("paypalUserID" => $paypalUserID, "paypalPassword" => $paypalPassword, "paypalSignature" => $paypalSignature, "paypalOpMode" => $paypalOpMode, "paypalCustomUrl" => $paypalCustomUrl));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPayPalPaymentService()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetPayPalPaymentService()
    {
        //   Get PayPal payment service settings. This function is disabled for teaming.
        $rc = $this->GetPayPalPaymentService(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateControlledAPAuthentication()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateControlledAPAuthentication($state, $authInterval)
    {
        //   Update Controlled AP authentication general settings. 
        $rc = $this->UpdateControlledAPAuthentication(array("state" => $state, "authInterval" => $authInterval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetControlledAPAuthentication()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetControlledAPAuthentication()
    {
        //   Get Controlled AP authentication general settings. 
        $rc = $this->GetControlledAPAuthentication(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteControlledAPAuthentication()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapExecuteControlledAPAuthentication()
    {
        //   Authenticate Controlled AP now. 
        $rc = $this->ExecuteControlledAPAuthentication(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateControlledAPFileAuthenticationList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateControlledAPFileAuthenticationList($state, $fileLocation)
    {
        //   Update Controlled AP file authentication list settings. 
        $rc = $this->UpdateControlledAPFileAuthenticationList(array("state" => $state, "fileLocation" => $fileLocation));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetControlledAPFileAuthenticationList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetControlledAPFileAuthenticationList()
    {
        //   Get Controlled AP file authentication list settings. 
        $rc = $this->GetControlledAPFileAuthenticationList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateControlledAPLocalAuthenticationList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateControlledAPLocalAuthenticationList($state)
    {
        //   Update Controlled AP local authentication list settings. 
        $rc = $this->UpdateControlledAPLocalAuthenticationList(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetControlledAPLocalAuthenticationList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetControlledAPLocalAuthenticationList()
    {
        //   Get Controlled AP local authentication list settings. 
        $rc = $this->GetControlledAPLocalAuthenticationList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateControlledAPRADIUSAuthenticationList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateControlledAPRADIUSAuthenticationList($state, $radiusProfile, $username, $password)
    {
        //   Update Controlled AP RADIUS authentication list settings. 
        $rc = $this->UpdateControlledAPRADIUSAuthenticationList(array("state" => $state, "radiusProfile" => $radiusProfile, "username" => $username, "password" => $password));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetControlledAPRADIUSAuthenticationList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetControlledAPRADIUSAuthenticationList()
    {
        //   Get Controlled AP RADIUS authentication list settings. 
        $rc = $this->GetControlledAPRADIUSAuthenticationList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateControlledAPProvisioning()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateControlledAPProvisioning($state)
    {
        //   Update Controlled AP Provisioning state. 
        $rc = $this->UpdateControlledAPProvisioning(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetControlledAPProvisioning()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetControlledAPProvisioning()
    {
        //   Get Controlled AP Provisioning state. 
        $rc = $this->GetControlledAPProvisioning(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateControlledAPLimit()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateControlledAPLimit($apLimit)
    {
        //   Update the limit of controlled APs. 
        $rc = $this->UpdateControlledAPLimit(array("apLimit" => $apLimit));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetControlledAPLimit()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetControlledAPLimit()
    {
        //   Get the limit of controlled APs. 
        $rc = $this->GetControlledAPLimit(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateControlledAPClientDataTunnel()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateControlledAPClientDataTunnel($tunnelSecurity)
    {
        //   Update the tunnel security settings. 
        $rc = $this->UpdateControlledAPClientDataTunnel(array("tunnelSecurity" => $tunnelSecurity));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetControlledAPClientDataTunnel()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetControlledAPClientDataTunnel()
    {
        //   Get the tunnel security settings. 
        $rc = $this->GetControlledAPClientDataTunnel(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAdsPresentation()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateAdsPresentation($adsPresentationState, $adsPresentationInterval)
    {
        //   Update Ads Presentation settings. 
        $rc = $this->UpdateAdsPresentation(array("adsPresentationState" => $adsPresentationState, "adsPresentationInterval" => $adsPresentationInterval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAdsPresentation()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetAdsPresentation()
    {
        //   Get Ads Presentation settings. 
        $rc = $this->GetAdsPresentation(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRedirectToAdsFrame()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateRedirectToAdsFrame($useFrameForAdsRedirectState)
    {
        //   Update use frame for ads redirect settings. 
        $rc = $this->UpdateRedirectToAdsFrame(array("useFrameForAdsRedirectState" => $useFrameForAdsRedirectState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRedirectToAdsFrame()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetRedirectToAdsFrame()
    {
        //   Get use frame for ads redirect settings 
        $rc = $this->GetRedirectToAdsFrame(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserReauthenticationOnLocationChangeState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateUserReauthenticationOnLocationChangeState($reauthState)
    {
        //   Update user reauthentication on location change state. 
        $rc = $this->UpdateUserReauthenticationOnLocationChangeState(array("reauthState" => $reauthState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserReauthenticationOnLocationChangeState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetUserReauthenticationOnLocationChangeState()
    {
        //   Get user reauthentication on location change state. 
        $rc = $this->GetUserReauthenticationOnLocationChangeState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateBSSIDAllocationMethod()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateBSSIDAllocationMethod($state)
    {
        //   Update the BSSID allocation method. 
        $rc = $this->UpdateBSSIDAllocationMethod(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateCDPState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateCDPState($state)
    {
        //   Update CDP state. 
        $rc = $this->UpdateCDPState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetCDPState()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetCDPState()
    {
        //   Get CDP state. 
        $rc = $this->GetCDPState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTrunkPort()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateTrunkPort($port, $type, $group, $duplex, $speed)
    {
        //   Update the port trunk settings. 
        $rc = $this->UpdateTrunkPort(array("port" => $port, "type" => $type, "group" => $group, "duplex" => $duplex, "speed" => $speed));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTrunkPort()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetTrunkPort($port)
    {
        //   Get the port trunk settings. 
        $rc = $this->GetTrunkPort(array("port" => $port));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVLANMapping()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateVLANMapping($networkProfileName, $portId, $mapping)
    {
        //  Update VLANs by add tag or untagged ports. 
        $rc = $this->UpdateVLANMapping(array("networkProfileName" => $networkProfileName, "portId" => $portId, "mapping" => $mapping));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVLANMapping()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetVLANMapping($networkProfileName, $portId)
    {
        //  Get VLANs mapping. 
        $rc = $this->GetVLANMapping(array("networkProfileName" => $networkProfileName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVLANMappingList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST,
// emPC.
    function soapGetVLANMappingList()
    {
        //   Get VLAN Mapping List. 
        $rc = $this->GetVLANMappingList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllTaggedPortsFromVlan()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapDeleteAllTaggedPortsFromVlan($networkProfileName)
    {
        //   Delete a tagged port from a VLAN. 
        $rc = $this->DeleteAllTaggedPortsFromVlan(array("networkProfileName" => $networkProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllUnTaggedPortsFromVlan()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapDeleteAllUnTaggedPortsFromVlan($networkProfileName)
    {
        //   Delete a untagged port from a VLAN. 
        $rc = $this->DeleteAllUnTaggedPortsFromVlan(array("networkProfileName" => $networkProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSflowGlobalIfIndexes()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapGetSflowGlobalIfIndexes()
    {
        //  Gets a list of the sFlow global ifIndexes.
        $rc = $this->GetSflowGlobalIfIndexes(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSflowAgent()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapGetSflowAgent()
    {
        //  Gets the sFlow Agent version and address.
        $rc = $this->GetSflowAgent(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSflowControllerStats()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapGetSflowControllerStats()
    {
        //   Get the sFlow Controller statistics.
        $rc = $this->GetSflowControllerStats(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSflowDeviceStats()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapGetSflowDeviceStats($deviceMacAddress)
    {
        //  Gets the statistics for an sFlow device.
        $rc = $this->GetSflowDeviceStats(array("deviceMacAddress" => $deviceMacAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSflowReceiverTableRow()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapGetSflowReceiverTableRow($RcvrIndex)
    {
        //  Gets an sFlow Receiver table row. 
        $rc = $this->GetSflowReceiverTableRow(array("RcvrIndex" => $RcvrIndex));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSflowCounterTableRow()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapGetSflowCounterTableRow($DataSource, $Instance)
    {
        //  Gets an sFlow Counter Polling table row. 
        $rc = $this->GetSflowCounterTableRow(array("DataSource" => $DataSource, "Instance" => $Instance));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetSflowSamplingTableRow()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// SUNFISH.
    function soapGetSflowSamplingTableRow($DataSource, $Instance)
    {
        //  Gets an sFlow Flow Sampling table row. 
        $rc = $this->GetSflowSamplingTableRow(array("DataSource" => $DataSource, "Instance" => $Instance));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAgentTables()
//
    function soapDeleteAgentTables()
    {
        //   Delete all tables related with SFLOW for this Agent 
        $rc = $this->DeleteAgentTables(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSflowSampler()
//
    function soapDeleteSflowSampler($DataSource, $Instance)
    {
        //   Delete a sflow sampler 
        $rc = $this->DeleteSflowSampler(array("DataSource" => $DataSource, "Instance" => $Instance));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSflowCounter()
//
    function soapDeleteSflowCounter($DataSource, $Instance)
    {
        //   Delete a sflow counter 
        $rc = $this->DeleteSflowCounter(array("DataSource" => $DataSource, "Instance" => $Instance));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSflowRecevier()
//
    function soapDeleteSflowRecevier($Index)
    {
        //   Delete an sflow receiver 
        $rc = $this->DeleteSflowRecevier(array("Index" => $Index));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSflowReceiverTableRow()
//
    function soapAddSflowReceiverTableRow($Index, $Owner, $TimeOut, $MaximumDatagramSize, $RcvrAddressType, $RcvrAddress, $Port, $DatagramVersion, $CompatibilityMode)
    {
        //   TBD 
        $rc = $this->AddSflowReceiverTableRow(array("Index" => $Index, "Owner" => $Owner, "TimeOut" => $TimeOut, "MaximumDatagramSize" => $MaximumDatagramSize, "RcvrAddressType" => $RcvrAddressType, "RcvrAddress" => $RcvrAddress, "Port" => $Port, "DatagramVersion" => $DatagramVersion, "CompatibilityMode" => $CompatibilityMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSflowCounterTableRow()
//
    function soapAddSflowCounterTableRow($DataSource, $Instance, $Receiver, $Interval)
    {
        //   TBD 
        $rc = $this->AddSflowCounterTableRow(array("DataSource" => $DataSource, "Instance" => $Instance, "Receiver" => $Receiver, "Interval" => $Interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSflowSamplingTableRow()
//
    function soapAddSflowSamplingTableRow($DataSource, $Instance, $Receiver, $PacketSamplingRate, $HeaderSize)
    {
        //   TBD 
        $rc = $this->AddSflowSamplingTableRow(array("DataSource" => $DataSource, "Instance" => $Instance, "Receiver" => $Receiver, "PacketSamplingRate" => $PacketSamplingRate, "HeaderSize" => $HeaderSize));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSflowGlobalTable()
//
    function soapUpdateSflowGlobalTable($Enabled, $ProxyIPAddress)
    {
        //   TBD 
        $rc = $this->UpdateSflowGlobalTable(array("Enabled" => $Enabled, "ProxyIPAddress" => $ProxyIPAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSflow()
//
    function soapUpdateSflow($state)
    {
        //   TBD 
        $rc = $this->UpdateSflow(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSflowSamplingTable()
//
    function soapUpdateSflowSamplingTable($DataSource, $Instance, $Receiver, $PacketSamplingRate, $HeaderSize)
    {
        //   TBD 
        $rc = $this->UpdateSflowSamplingTable(array("DataSource" => $DataSource, "Instance" => $Instance, "Receiver" => $Receiver, "PacketSamplingRate" => $PacketSamplingRate, "HeaderSize" => $HeaderSize));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSflowCounterTable()
//
    function soapUpdateSflowCounterTable($DataSource, $Instance, $Receiver, $Interval)
    {
        //   TBD 
        $rc = $this->UpdateSflowCounterTable(array("DataSource" => $DataSource, "Instance" => $Instance, "Receiver" => $Receiver, "Interval" => $Interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSflowReceiverTable()
//
    function soapUpdateSflowReceiverTable($Index, $Owner, $Timeout, $MaximumDatagramSize, $AddressType, $Address, $Port, $DatagramVersion, $CompatibilityMode)
    {
        //   TBD 
        $rc = $this->UpdateSflowReceiverTable(array("Index" => $Index, "Owner" => $Owner, "Timeout" => $Timeout, "MaximumDatagramSize" => $MaximumDatagramSize, "AddressType" => $AddressType, "Address" => $Address, "Port" => $Port, "DatagramVersion" => $DatagramVersion, "CompatibilityMode" => $CompatibilityMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSflowGlobalIndexTableRow()
//
    function soapAddSflowGlobalIndexTableRow($LocalDataSource, $GlobalDataSource)
    {
        //   TBD 
        $rc = $this->AddSflowGlobalIndexTableRow(array("LocalDataSource" => $LocalDataSource, "GlobalDataSource" => $GlobalDataSource));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSflowGlobalIndex()
//
    function soapDeleteSflowGlobalIndex($Index)
    {
        //   Delete an sflow Global Index Row
        $rc = $this->DeleteSflowGlobalIndex(array("Index" => $Index));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPProcessState()
//
    function soapUpdateLLDPProcessState($state)
    {
        //  Update LLDP's Process State. 
        $rc = $this->UpdateLLDPProcessState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPProcessState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPProcessState()
    {
        //   Get LLDP Process State. 
        $rc = $this->GetLLDPProcessState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPRefreshInterval()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPRefreshInterval()
    {
        //  Get LLDP Refresh Interval Value. 
        $rc = $this->GetLLDPRefreshInterval(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPRefreshInterval()
//
    function soapUpdateLLDPRefreshInterval($interval)
    {
        //  Update LLDP Refresh Interval Value. 
        $rc = $this->UpdateLLDPRefreshInterval(array("interval" => $interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPHoldtimeMultiplier()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPHoldtimeMultiplier()
    {
        //  Get LLDP Holdtime Multiplier Value. 
        $rc = $this->GetLLDPHoldtimeMultiplier(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPHoldtimeMultiplier()
//
    function soapUpdateLLDPHoldtimeMultiplier($holdtime)
    {
        //  Update LLDP Holdtime Multiplier Value. 
        $rc = $this->UpdateLLDPHoldtimeMultiplier(array("holdtime" => $holdtime));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPDynamicNameState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPDynamicNameState($state)
    {
        //  Update LLDP's Dynamic Name State. 
        $rc = $this->UpdateLLDPDynamicNameState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPDynamicNameState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPDynamicNameState()
    {
        //   Get LLDP's Dynamic Name State. 
        $rc = $this->GetLLDPDynamicNameState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPDynamicNameRefreshTime()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPDynamicNameRefreshTime($time)
    {
        //  Update LLDP Dynamic Name Refresh Time Value. 
        $rc = $this->UpdateLLDPDynamicNameRefreshTime(array("time" => $time));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPDynamicNameRefreshTime()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPDynamicNameRefreshTime()
    {
        //  Get LLDP Dynamic Name Refresh Time Value. 
        $rc = $this->GetLLDPDynamicNameRefreshTime(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPDynamicNameUserString()
//
    function soapUpdateLLDPDynamicNameUserString($userstring)
    {
        //  Update LLDP Dynamic Name User String. 
        $rc = $this->UpdateLLDPDynamicNameUserString(array("userstring" => $userstring));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPDynamicNameUserString()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPDynamicNameUserString()
    {
        //  Get LLDP Dynamic Name User String Value. 
        $rc = $this->GetLLDPDynamicNameUserString(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPFastStartCount()
//
    function soapUpdateLLDPFastStartCount($interval)
    {
        //  Update LLDP's MED Fast Start Count. 
        $rc = $this->UpdateLLDPFastStartCount(array("interval" => $interval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPFastStartCount()
//
    function soapGetLLDPFastStartCount()
    {
        //  Get LLDP's MED Fast Start Count. 
        $rc = $this->GetLLDPFastStartCount(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPMEDELINLocation()
//
    function soapUpdateLLDPMEDELINLocation($location)
    {
        //  Update LLDP's MED ELIN Location. 
        $rc = $this->UpdateLLDPMEDELINLocation(array("location" => $location));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPOverLocalMeshState()
//
    function soapUpdateLLDPOverLocalMeshState($state)
    {
        //  Update LLDP Over Local Mesh State. 
        $rc = $this->UpdateLLDPOverLocalMeshState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgentTxState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgentTxState($interface, $state)
    {
        //  Update LLDP Agent's Tx State. 
        $rc = $this->UpdateLLDPAgentTxState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgentTxState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgentTxState($interface)
    {
        //   Get LLDP Agent's Tx State. 
        $rc = $this->GetLLDPAgentTxState(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgentRxState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgentRxState($interface, $state)
    {
        //  Update LLDP Agent's Rx State. 
        $rc = $this->UpdateLLDPAgentRxState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgentRxState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgentRxState($interface)
    {
        //   Get LLDP Agent's Rx State. 
        $rc = $this->GetLLDPAgentRxState(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgentBasicTLVsState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgentBasicTLVsState($interface, $state)
    {
        //  Update LLDP Agent's Basic TLVs State. 
        $rc = $this->UpdateLLDPAgentBasicTLVsState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgentBasicTLVsState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgentBasicTLVsState($interface)
    {
        //   Get LLDP Agent's Basic TLVs State. 
        $rc = $this->GetLLDPAgentBasicTLVsState(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgentPortDescriptionState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgentPortDescriptionState($interface, $state)
    {
        //  Update LLDP Agent's Port Description TLV State. 
        $rc = $this->UpdateLLDPAgentPortDescriptionState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgentPortDescriptionState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgentPortDescriptionState($interface)
    {
        //   Get LLDP Agent's PortDescription TLV State. 
        $rc = $this->GetLLDPAgentPortDescriptionState(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgentSystemNameState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgentSystemNameState($interface, $state)
    {
        //  Update LLDP Agent's System Name TLV State. 
        $rc = $this->UpdateLLDPAgentSystemNameState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgentSystemNameState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgentSystemNameState($interface)
    {
        //   Get LLDP Agent's System Name TLV State. 
        $rc = $this->GetLLDPAgentSystemNameState(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgentSystemDescriptionState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgentSystemDescriptionState($interface, $state)
    {
        //  Update LLDP Agent's SystemDescription TLV State. 
        $rc = $this->UpdateLLDPAgentSystemDescriptionState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgentSystemDescriptionState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgentSystemDescriptionState($interface)
    {
        //   Get LLDP Agent's System Description TLV State. 
        $rc = $this->GetLLDPAgentSystemDescriptionState(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgentSystemCapabilitiesState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgentSystemCapabilitiesState($interface, $state)
    {
        //  Update LLDP Agent's System Capabilities TLV State. 
        $rc = $this->UpdateLLDPAgentSystemCapabilitiesState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgentSystemCapabilitiesState()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgentSystemCapabilitiesState($interface)
    {
        //   Get LLDP Agent's System Capabilities TLV State. 
        $rc = $this->GetLLDPAgentSystemCapabilitiesState(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgent802dot3State()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgent802dot3State($interface, $state)
    {
        //  Update LLDP Agent's 802dot3 TLV State. 
        $rc = $this->UpdateLLDPAgent802dot3State(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgent802dot3State()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgent802dot3State($interface)
    {
        //   Get LLDP Agent's 802dot3 TLV State. 
        $rc = $this->GetLLDPAgent802dot3State(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPAgentManagementIPAddress()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateLLDPAgentManagementIPAddress($interface, $ipAddress)
    {
        //   Update LLDP Agent's management ip address. 
        $rc = $this->UpdateLLDPAgentManagementIPAddress(array("interface" => $interface, "ipAddress" => $ipAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLLDPAgentManagementIPAddress()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetLLDPAgentManagementIPAddress($interface)
    {
        //   Get LLDP Agent's management ip address. 
        $rc = $this->GetLLDPAgentManagementIPAddress(array("interface" => $interface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPMEDCapabilitiesState()
//
    function soapUpdateLLDPMEDCapabilitiesState($interface, $state)
    {
        //  Update LLDP Agent's MED Capabilities State. 
        $rc = $this->UpdateLLDPMEDCapabilitiesState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPMEDNetworkPolicyState()
//
    function soapUpdateLLDPMEDNetworkPolicyState($interface, $state)
    {
        //  Update LLDP Agent's MED Network Policy State. 
        $rc = $this->UpdateLLDPMEDNetworkPolicyState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPMEDLocationState()
//
    function soapUpdateLLDPMEDLocationState($interface, $state)
    {
        //  Update LLDP Agent's MED Location State. 
        $rc = $this->UpdateLLDPMEDLocationState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPMEDPoEState()
//
    function soapUpdateLLDPMEDPoEState($interface, $state)
    {
        //  Update LLDP Agent's MED PoE State. 
        $rc = $this->UpdateLLDPMEDPoEState(array("interface" => $interface, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddLLDPCivicAddressType()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// SOLING,
// CHAMELEON,
// OPTIMIST.
    function soapAddLLDPCivicAddressType($type, $valueString)
    {
        //   Add a Civic Address Type. 
        $rc = $this->AddLLDPCivicAddressType(array("type" => $type, "valueString" => $valueString));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLLDPCivicAddressType()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// SOLING,
// CHAMELEON,
// OPTIMIST.
    function soapUpdateLLDPCivicAddressType($type, $valueString)
    {
        //   Update a Civic Address Type and Value. 
        $rc = $this->UpdateLLDPCivicAddressType(array("type" => $type, "valueString" => $valueString));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteLLDPCivicAddressType()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// SOLING,
// CHAMELEON,
// OPTIMIST.
    function soapDeleteLLDPCivicAddressType($type)
    {
        //   Delete a Civic Address Type. 
        $rc = $this->DeleteLLDPCivicAddressType(array("type" => $type));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllLLDPCivicAddressType()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// SOLING,
// CHAMELEON,
// OPTIMIST.
    function soapDeleteAllLLDPCivicAddressType()
    {
        //   Delete all the Civic Address entries. 
        $rc = $this->DeleteAllLLDPCivicAddressType(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateApplicationProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// SOLING,
// CHAMELEON,
// OPTIMIST.
    function soapUpdateApplicationProfile($profile, $vlan, $mode, $priorityLevel, $diffserv)
    {
        //   Updates the application profile data.
        $rc = $this->UpdateApplicationProfile(array("profile" => $profile, "vlan" => $vlan, "mode" => $mode, "priorityLevel" => $priorityLevel, "diffserv" => $diffserv));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIGMPProxy()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateIGMPProxy($state, $upstreamInterface, $downstreamInterface)
    {
        //   Update IGMP Proxy settings. 
        $rc = $this->UpdateIGMPProxy(array("state" => $state, "upstreamInterface" => $upstreamInterface, "downstreamInterface" => $downstreamInterface));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIGMPProxy()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetIGMPProxy()
    {
        //   Get IGMP Proxy settings. 
        $rc = $this->GetIGMPProxy(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateUserTrackingSettings()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// ZURICH.
    function soapUpdateUserTrackingSettings($state, $serverAddr, $udpPort, $filter)
    {
        //   Update user tracking settings. 
        $rc = $this->UpdateUserTrackingSettings(array("state" => $state, "serverAddr" => $serverAddr, "udpPort" => $udpPort, "filter" => $filter));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetUserTrackingSettings()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// ZURICH.
    function soapGetUserTrackingSettings()
    {
        //   Get user tracking settings. 
        $rc = $this->GetUserTrackingSettings(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortState()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortState($portId, $state)
    {
        //   Update the state of an ethernet port. 
        $rc = $this->UpdateSwitchPortState(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortName()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortName($portId, $name)
    {
        //   Update the name of an ethernet port. 
        $rc = $this->UpdateSwitchPortName(array("portId" => $portId, "name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortFlowControl()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortFlowControl($portId, $state)
    {
        //   Update the state of the flow control on an ethernet port. 
        $rc = $this->UpdateSwitchPortFlowControl(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortPowerForwarding()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortPowerForwarding($portId, $state)
    {
        //   Update the state of the power forwarding state on an ethernet port. 
        $rc = $this->UpdateSwitchPortPowerForwarding(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortPowerLimit()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortPowerLimit($portId, $poePowerLimit)
    {
        //  Update the PoE limit on an ethernet port. 
        $rc = $this->UpdateSwitchPortPowerLimit(array("portId" => $portId, "poePowerLimit" => $poePowerLimit));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortIsolation()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortIsolation($portId, $state)
    {
        //  Update the port isolation on an ethernet port. 
        $rc = $this->UpdateSwitchPortIsolation(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortLoopProtection()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortLoopProtection($portId, $state)
    {
        //  Update the port loop protection state. 
        $rc = $this->UpdateSwitchPortLoopProtection(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortEgressVLANTagging()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortEgressVLANTagging($portId, $state, $vlanId)
    {
        //   Update the Egress VLAN tagging settings of an ethernet port. 
        $rc = $this->UpdateSwitchPortEgressVLANTagging(array("portId" => $portId, "state" => $state, "vlanId" => $vlanId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortSecondaryVLAN()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortSecondaryVLAN($portId, $state, $vlanId)
    {
        //  Update the secondary VLAN state and ID.
        $rc = $this->UpdateSwitchPortSecondaryVLAN(array("portId" => $portId, "state" => $state, "vlanId" => $vlanId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortQosLookupScheme()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortQosLookupScheme($portId, $state, $lookupScheme)
    {
        //   Update the Qos Lookup scheme settings of an ethernet port. 
        $rc = $this->UpdateSwitchPortQosLookupScheme(array("portId" => $portId, "state" => $state, "lookupScheme" => $lookupScheme));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortQosDefaultPriority()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortQosDefaultPriority($portId, $level)
    {
        //   Update the Qos default priority level of an ethernet port. 
        $rc = $this->UpdateSwitchPortQosDefaultPriority(array("portId" => $portId, "level" => $level));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortDeviceAuthenticationRADIUSServer()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortDeviceAuthenticationRADIUSServer($portId, $radiusProfile)
    {
        //   Update the Device authentication radius server of an ethernet port. 
        $rc = $this->UpdateSwitchPortDeviceAuthenticationRADIUSServer(array("portId" => $portId, "radiusProfile" => $radiusProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortDynamicVLANAssignmentState()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortDynamicVLANAssignmentState($portId, $state)
    {
        //   Update the Dynamic VLAN assignment state of an ethernet port. 
        $rc = $this->UpdateSwitchPortDynamicVLANAssignmentState(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortQuarantineVLAN()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortQuarantineVLAN($portId, $state, $vlanId)
    {
        //  Update the quarantine VLAN state and ID.
        $rc = $this->UpdateSwitchPortQuarantineVLAN(array("portId" => $portId, "state" => $state, "vlanId" => $vlanId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPort8021XState()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPort8021XState($portId, $state)
    {
        //  Update the 802.1x authentication state for an ethernet port.
        $rc = $this->UpdateSwitchPort8021XState(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortMACFilterState()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortMACFilterState($portId, $state)
    {
        //  Update the MAC filter state.
        $rc = $this->UpdateSwitchPortMACFilterState(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSwitchPortMACFilterList()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapAddSwitchPortMACFilterList($portId, $macListName)
    {
        //  Add a MAC list to the MAC filter system.
        $rc = $this->AddSwitchPortMACFilterList(array("portId" => $portId, "macListName" => $macListName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSwitchPortMACFilterList()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteSwitchPortMACFilterList($portId, $macListName)
    {
        //  Delete a MAC list from the MAC filter system.
        $rc = $this->DeleteSwitchPortMACFilterList(array("portId" => $portId, "macListName" => $macListName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllSwitchPortMACFilterLists()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapDeleteAllSwitchPortMACFilterLists($portId)
    {
        //  Delete all MAC lists from the MAC filter system.
        $rc = $this->DeleteAllSwitchPortMACFilterLists(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortRADIUSMACAuthenticationState()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortRADIUSMACAuthenticationState($portId, $state)
    {
        //  Update the RADIUS MAC Authentication state.
        $rc = $this->UpdateSwitchPortRADIUSMACAuthenticationState(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortUseVSCState()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortUseVSCState($portId, $state, $vscUniqueID)
    {
        //  Update the VSC usage state.
        $rc = $this->UpdateSwitchPortUseVSCState(array("portId" => $portId, "state" => $state, "vscUniqueID" => $vscUniqueID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortVLANMode()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortVLANMode($portId, $mode)
    {
        //  Update the VLAN mode.
        $rc = $this->UpdateSwitchPortVLANMode(array("portId" => $portId, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortEgressRateLimiting()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortEgressRateLimiting($portId, $state, $rate)
    {
        //  Update the egress rate limiting settings.
        $rc = $this->UpdateSwitchPortEgressRateLimiting(array("portId" => $portId, "state" => $state, "rate" => $rate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortIngressRateLimiting()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortIngressRateLimiting($portId, $state, $rate)
    {
        //  Update the ingress rate limiting settings.
        $rc = $this->UpdateSwitchPortIngressRateLimiting(array("portId" => $portId, "state" => $state, "rate" => $rate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortIngressRateLimitingTrafficType()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortIngressRateLimitingTrafficType($portId, $trafficType)
    {
        //  Update the ingress rate limiting traffic type.
        $rc = $this->UpdateSwitchPortIngressRateLimitingTrafficType(array("portId" => $portId, "trafficType" => $trafficType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortAppProfileState()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortAppProfileState($portId, $state)
    {
        //   Update the state of an ethernet port's application profile. 
        $rc = $this->UpdateSwitchPortAppProfileState(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSwitchPortAppProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapUpdateSwitchPortAppProfile($portId, $profile)
    {
        //   Update the value of an ethernet port's application profile. 
        $rc = $this->UpdateSwitchPortAppProfile(array("portId" => $portId, "profile" => $profile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortInheritance($level, $entityName, $state)
    {
        //   Update the inheritance state all ethernet ports. 
        $rc = $this->ControlledNetworkUpdateSwitchPortInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortInheritance($level, $entityName)
    {
        //   Get the inheritance state all ethernet ports. 
        $rc = $this->ControlledNetworkGetSwitchPortInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortPerPortInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortPerPortInheritance($level, $entityName, $portId, $state)
    {
        //   Update the inheritance state of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortPerPortInheritance(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortPerPortInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortPerPortInheritance($level, $entityName, $portId)
    {
        //   Get the inheritance state of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortPerPortInheritance(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortState($level, $entityName, $portId, $state)
    {
        //   Update the state of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortState(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortState($level, $entityName, $portId)
    {
        //   Get the state of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortState(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortAppProfileState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortAppProfileState($level, $entityName, $portId, $state)
    {
        //   Update the state of an ethernet port's application profile. 
        $rc = $this->ControlledNetworkUpdateSwitchPortAppProfileState(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortAppProfileState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortAppProfileState($level, $entityName, $portId)
    {
        //   Get the state of an ethernet port's Application profile. 
        $rc = $this->ControlledNetworkGetSwitchPortAppProfileState(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortAppProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortAppProfile($level, $entityName, $portId, $profile)
    {
        //   Update the ethernet port's application profile. 
        $rc = $this->ControlledNetworkUpdateSwitchPortAppProfile(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "profile" => $profile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortAppProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortAppProfile($level, $entityName, $portId)
    {
        //   Get the ethernet port's Application profile. 
        $rc = $this->ControlledNetworkGetSwitchPortAppProfile(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateApplicationProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateApplicationProfile($level, $entityName, $profile, $vlanId, $priorityLevel, $mode, $diffserv)
    {
        //   Update the state of an ethernet port's application profile. 
        $rc = $this->ControlledNetworkUpdateApplicationProfile(array("level" => $level, "entityName" => $entityName, "profile" => $profile, "vlanId" => $vlanId, "priorityLevel" => $priorityLevel, "mode" => $mode, "diffserv" => $diffserv));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetApplicationProfile()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetApplicationProfile($level, $entityName, $profile)
    {
        //   Get the state of an ethernet port's Application profile. 
        $rc = $this->ControlledNetworkGetApplicationProfile(array("level" => $level, "entityName" => $entityName, "profile" => $profile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortName($level, $entityName, $portId, $portName)
    {
        //   Update the friendly name of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortName(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "portName" => $portName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortFlowControl()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortFlowControl($level, $entityName, $portId, $state)
    {
        //   Update the state of the flow control on an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortFlowControl(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortFlowControl()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortFlowControl($level, $entityName, $portId)
    {
        //   Get the state of the flow control on an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortFlowControl(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortPowerForwarding()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortPowerForwarding($level, $entityName, $portId, $state)
    {
        //   Update the state of the power forwarding state on an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortPowerForwarding(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortPowerForwarding()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortPowerForwarding($level, $entityName, $portId)
    {
        //   Get the state of the power forwarding state on an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortPowerForwarding(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortPowerLimit()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortPowerLimit($level, $entityName, $portId, $poePowerLimit)
    {
        //   Update the PoE limit on an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortPowerLimit(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "poePowerLimit" => $poePowerLimit));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortPowerLimit()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortPowerLimit($level, $entityName, $portId)
    {
        //   Get the PoE power limit on an ethernet port.
        $rc = $this->ControlledNetworkGetSwitchPortPowerLimit(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortIsolation()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortIsolation($level, $entityName, $portId, $state)
    {
        //   Update the port isolation state. 
        $rc = $this->ControlledNetworkUpdateSwitchPortIsolation(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortIsolation()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortIsolation($level, $entityName, $portId)
    {
        //   Get the port isolation state.
        $rc = $this->ControlledNetworkGetSwitchPortIsolation(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortLoopProtection()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortLoopProtection($level, $entityName, $portId, $state)
    {
        //   Update the port loop protection state. 
        $rc = $this->ControlledNetworkUpdateSwitchPortLoopProtection(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortLoopProtection()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortLoopProtection($level, $entityName, $portId)
    {
        //   Get the port loop protection state.
        $rc = $this->ControlledNetworkGetSwitchPortLoopProtection(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortEgressVLANTagging()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortEgressVLANTagging($level, $entityName, $portId, $state, $vlanId)
    {
        //   Update the Egress VLAN tagging settings of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortEgressVLANTagging(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state, "vlanId" => $vlanId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortEgressVLANTagging()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortEgressVLANTagging($level, $entityName, $portId)
    {
        //   Get the Egress VLAN tagging settings of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortEgressVLANTagging(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortSecondaryVLAN()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortSecondaryVLAN($level, $entityName, $portId, $state, $vlanId)
    {
        //   Update the secondary VLAN state and ID of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortSecondaryVLAN(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state, "vlanId" => $vlanId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortSecondaryVLAN()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortSecondaryVLAN($level, $entityName, $portId)
    {
        //   Get the secondary VLAN state and ID of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortSecondaryVLAN(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortQosLookupScheme()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortQosLookupScheme($level, $entityName, $portId, $state, $lookupScheme)
    {
        //   Update the Qos Lookup scheme settings of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortQosLookupScheme(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state, "lookupScheme" => $lookupScheme));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortQosLookupScheme()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortQosLookupScheme($level, $entityName, $portId)
    {
        //   Get the Qos Lookup scheme settings of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortQosLookupScheme(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortQosDefaultPriority()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortQosDefaultPriority($level, $entityName, $portId, $priorityLevel)
    {
        //   Update the Qos default priority level of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortQosDefaultPriority(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "priorityLevel" => $priorityLevel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortQosDefaultPriority()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortQosDefaultPriority($level, $entityName, $portId)
    {
        //   Get the Qos default priority level of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortQosDefaultPriority(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortDeviceAuthenticationRADIUSServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortDeviceAuthenticationRADIUSServer($level, $entityName, $portId, $radiusProfile)
    {
        //   Update the Device authentication radius server of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortDeviceAuthenticationRADIUSServer(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "radiusProfile" => $radiusProfile));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortDeviceAuthenticationRADIUSServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortDeviceAuthenticationRADIUSServer($level, $entityName, $portId)
    {
        //   Get the Device authentication radius server of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortDeviceAuthenticationRADIUSServer(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortDynamicVLANAssignmentState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortDynamicVLANAssignmentState($level, $entityName, $portId, $state)
    {
        //   Update the Dynamic VLAN assignment state of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortDynamicVLANAssignmentState(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortDynamicVLANAssignmentState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortDynamicVLANAssignmentState($level, $entityName, $portId)
    {
        //   Get the Dynamic VLAN assignment state of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortDynamicVLANAssignmentState(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortVLANMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortVLANMode($level, $entityName, $portId, $mode)
    {
        //   Update the VLAN mode of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortVLANMode(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "mode" => $mode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortVLANMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortVLANMode($level, $entityName, $portId)
    {
        //   Get the VLAN mode of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortVLANMode(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortMACFilterState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortMACFilterState($level, $entityName, $portId, $state)
    {
        //   Update the MAC filter state of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortMACFilterState(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortMACFilterState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortMACFilterState($level, $entityName, $portId)
    {
        //   Get the MAC Filter state of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortMACFilterState(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkAddSwitchPortMACFilterList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkAddSwitchPortMACFilterList($level, $entityName, $portId, $macListName)
    {
        //   Add a MAC filter list to an ethernet port. 
        $rc = $this->ControlledNetworkAddSwitchPortMACFilterList(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "macListName" => $macListName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteSwitchPortMACFilterList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteSwitchPortMACFilterList($level, $entityName, $portId, $macListName)
    {
        //   Delete a MAC filter list from an ethernet port. 
        $rc = $this->ControlledNetworkDeleteSwitchPortMACFilterList(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "macListName" => $macListName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkDeleteAllSwitchPortMACFilterLists()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkDeleteAllSwitchPortMACFilterLists($level, $entityName, $portId)
    {
        //   Delete all MAC filter lists from an ethernet port. 
        $rc = $this->ControlledNetworkDeleteAllSwitchPortMACFilterLists(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortMACFilterList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortMACFilterList($level, $entityName, $portId)
    {
        //   Get the MAC filter lists from an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortMACFilterList(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortQuarantineVLAN()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortQuarantineVLAN($level, $entityName, $portId, $state, $vlanId)
    {
        //   Update the quarantine VLAN state and ID of an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortQuarantineVLAN(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state, "vlanId" => $vlanId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortQuarantineVLAN()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortQuarantineVLAN($level, $entityName, $portId)
    {
        //   Get the quarantine VLAN state and ID of an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortQuarantineVLAN(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPort8021XState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPort8021XState($level, $entityName, $portId, $state)
    {
        //   Update the 802.1x authentication state for an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPort8021XState(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPort8021XState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPort8021XState($level, $entityName, $portId)
    {
        //   Get the 802.1x authentication state for an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPort8021XState(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortRADIUSMACAuthenticationState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortRADIUSMACAuthenticationState($level, $entityName, $portId, $state)
    {
        //   Update the RADIUS MAC Authentication state for an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortRADIUSMACAuthenticationState(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortRADIUSMACAuthenticationState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortRADIUSMACAuthenticationState($level, $entityName, $portId)
    {
        //   Get the RADIUS MAC Authentication state for an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortRADIUSMACAuthenticationState(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortUseVSCState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortUseVSCState($level, $entityName, $portId, $state, $vscName)
    {
        //   Update the VSC used by an ethernet port. 
        $rc = $this->ControlledNetworkUpdateSwitchPortUseVSCState(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state, "vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortUseVSCState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortUseVSCState($level, $entityName, $portId)
    {
        //   Get the VSC used by an ethernet port. 
        $rc = $this->ControlledNetworkGetSwitchPortUseVSCState(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortEgressRateLimiting()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortEgressRateLimiting($level, $entityName, $portId, $state, $rate)
    {
        //   Update the egress rate limiting settings. 
        $rc = $this->ControlledNetworkUpdateSwitchPortEgressRateLimiting(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state, "rate" => $rate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortEgressRateLimiting()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortEgressRateLimiting($level, $entityName, $portId)
    {
        //   Get the egress rate limiting settings. 
        $rc = $this->ControlledNetworkGetSwitchPortEgressRateLimiting(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortIngressRateLimiting()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortIngressRateLimiting($level, $entityName, $portId, $state, $rate)
    {
        //   Update the ingress rate limiting settings. 
        $rc = $this->ControlledNetworkUpdateSwitchPortIngressRateLimiting(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "state" => $state, "rate" => $rate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortIngressRateLimiting()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortIngressRateLimiting($level, $entityName, $portId)
    {
        //   Get the ingress rate limiting settings. 
        $rc = $this->ControlledNetworkGetSwitchPortIngressRateLimiting(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateSwitchPortIngressRateLimitingTrafficType()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateSwitchPortIngressRateLimitingTrafficType($level, $entityName, $portId, $trafficType)
    {
        //   Update the ingress rate limiting traffic type. 
        $rc = $this->ControlledNetworkUpdateSwitchPortIngressRateLimitingTrafficType(array("level" => $level, "entityName" => $entityName, "portId" => $portId, "trafficType" => $trafficType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetSwitchPortIngressRateLimitingTrafficType()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetSwitchPortIngressRateLimitingTrafficType($level, $entityName, $portId)
    {
        //   Get the ingress rate limiting traffic type. 
        $rc = $this->ControlledNetworkGetSwitchPortIngressRateLimitingTrafficType(array("level" => $level, "entityName" => $entityName, "portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWebContentFiles()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetWebContentFiles()
    {
        //   Get Web Content File list. 
        $rc = $this->GetWebContentFiles(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteResetWebContentFiles()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapExecuteResetWebContentFiles()
    {
        //   Reset Web Content Files to factory. 
        $rc = $this->ExecuteResetWebContentFiles(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateIperfServerState()
//
    function soapUpdateIperfServerState($state)
    {
        //   Update the Iperf server state. 
        $rc = $this->UpdateIperfServerState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetIperfServerState()
//
    function soapGetIperfServerState()
    {
        //   Get the Iperf server state. 
        $rc = $this->GetIperfServerState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateIperfServerState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// SOLING,
// CHAMELEON,
// OPTIMIST,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateIperfServerState($macAddress, $state)
    {
        //   Update the Iperf server state on a controlled device. 
        $rc = $this->ControlledNetworkUpdateIperfServerState(array("macAddress" => $macAddress, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetIperfServerState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// SOLING,
// CHAMELEON,
// OPTIMIST,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetIperfServerState($macAddress)
    {
        //   Get the Iperf server state of a controlled device. 
        $rc = $this->ControlledNetworkGetIperfServerState(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDiscoveredSCDevices()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// ALLPPC,
// ALLPPC26,
// OPTIMIST.
    function soapGetDiscoveredSCDevices()
    {
        //   Get the list of Slave sc. 
        $rc = $this->GetDiscoveredSCDevices(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteOpenGlobalTransaction()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteOpenGlobalTransaction()
    {
        //   Start a global transaction. 
        $rc = $this->ExecuteOpenGlobalTransaction(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteOpenGlobalTransactionWithTimeout()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteOpenGlobalTransactionWithTimeout($maxQueuedDelay, $timeoutBeforeCommit)
    {
        //   Start a global transaction. 
        $rc = $this->ExecuteOpenGlobalTransactionWithTimeout(array("maxQueuedDelay" => $maxQueuedDelay, "timeoutBeforeCommit" => $timeoutBeforeCommit));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteCloseGlobalTransaction()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteCloseGlobalTransaction($transactionClose)
    {
        //   Close a global transaction. 
        $rc = $this->ExecuteCloseGlobalTransaction(array("transactionClose" => $transactionClose));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteResetGlobalTransaction()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapExecuteResetGlobalTransaction()
    {
        //   Reset a global transaction. 
        $rc = $this->ExecuteResetGlobalTransaction(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLEDsOperatingMode()
//
    function soapUpdateLEDsOperatingMode($ledsOperatingMode)
    {
        //   Update the LEDs operating mode. 
        $rc = $this->UpdateLEDsOperatingMode(array("ledsOperatingMode" => $ledsOperatingMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLEDsOperatingMode()
//
    function soapGetLEDsOperatingMode()
    {
        //   Get the LEDs operating mode. 
        $rc = $this->GetLEDsOperatingMode(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLEDsOperatingMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkUpdateLEDsOperatingMode($level, $entityName, $ledsOperatingMode)
    {
        //   Update the LEDs operating mode of a controlled device. 
        $rc = $this->ControlledNetworkUpdateLEDsOperatingMode(array("level" => $level, "entityName" => $entityName, "ledsOperatingMode" => $ledsOperatingMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLEDsOperatingMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkGetLEDsOperatingMode($level, $entityName)
    {
        //   Get the LEDs operating mode of a controlled device. 
        $rc = $this->ControlledNetworkGetLEDsOperatingMode(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateLEDsOperatingModeInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateLEDsOperatingModeInheritance($level, $entityName, $state)
    {
        //  This function set the inheritance state of leds operating mode settings for an entity.
        $rc = $this->ControlledNetworkUpdateLEDsOperatingModeInheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetLEDsOperatingModeInheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetLEDsOperatingModeInheritance($level, $entityName)
    {
        //  This function get the inheritance state of leds operating mode for an entity.
        $rc = $this->ControlledNetworkGetLEDsOperatingModeInheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddMACList()
//
    function soapAddMACList($listName, $macList)
    {
        //   Add a MAC address list. 
        $rc = $this->AddMACList(array("listName" => $listName, "macList" => $macList));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateMACList()
//
    function soapUpdateMACList($listName, $macList)
    {
        //   Update a MAC list. 
        $rc = $this->UpdateMACList(array("listName" => $listName, "macList" => $macList));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateMACListName()
//
    function soapUpdateMACListName($oldListName, $newListName)
    {
        //   Update a MAC list Name. 
        $rc = $this->UpdateMACListName(array("oldListName" => $oldListName, "newListName" => $newListName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteMACList()
//
    function soapDeleteMACList($listName)
    {
        //   Delete MAC list. 
        $rc = $this->DeleteMACList(array("listName" => $listName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllMACLists()
//
    function soapDeleteAllMACLists()
    {
        //   Delete all MAC lists. 
        $rc = $this->DeleteAllMACLists(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetMACListList()
//
    function soapGetMACListList()
    {
        //   Get all MAC lists. 
        $rc = $this->GetMACListList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetMACList()
//
    function soapGetMACList($macListName)
    {
        //   Get MAC list settings. 
        $rc = $this->GetMACList(array("macListName" => $macListName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVirtualSCIngressType()
//
    function soapUpdateVirtualSCIngressType($vscName, $interfaceType)
    {
        //   Update Virtual SC Ingress Type. This function is disabled for teaming.
        $rc = $this->UpdateVirtualSCIngressType(array("vscName" => $vscName, "interfaceType" => $interfaceType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVirtualSCIngressType()
//
    function soapGetVirtualSCIngressType($vscName)
    {
        //   Get Virtual SC Ingress Type settings. This function is disabled for teaming.
        $rc = $this->GetVirtualSCIngressType(array("vscName" => $vscName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddStationProfile()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapAddStationProfile($state, $profileName, $wlanName, $apMACAddress, $scanningMode)
    {
        //   Add a station profile. 
        $rc = $this->AddStationProfile(array("state" => $state, "profileName" => $profileName, "wlanName" => $wlanName, "apMACAddress" => $apMACAddress, "scanningMode" => $scanningMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteStationProfile()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapDeleteStationProfile($profileName)
    {
        //   Delete a station profile. 
        $rc = $this->DeleteStationProfile(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateStationProfile()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateStationProfile($state, $profileName, $wlanName, $apMACAddress, $scanningMode)
    {
        //   Update a station profile. 
        $rc = $this->UpdateStationProfile(array("state" => $state, "profileName" => $profileName, "wlanName" => $wlanName, "apMACAddress" => $apMACAddress, "scanningMode" => $scanningMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStationProfile()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetStationProfile($profileName)
    {
        //   Get station profile settings. 
        $rc = $this->GetStationProfile(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateStationProfileSecurity()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateStationProfileSecurity($profileName, $keySource, $encryptionType)
    {
        //   Update wireless security for a station profile.
        $rc = $this->UpdateStationProfileSecurity(array("profileName" => $profileName, "keySource" => $keySource, "encryptionType" => $encryptionType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStationProfileSecurity()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetStationProfileSecurity($profileName)
    {
        //   Get station profile security settings (key source and encryption type). 
        $rc = $this->GetStationProfileSecurity(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateStationProfileWEPEncryptionKeys()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateStationProfileWEPEncryptionKeys($profileName, $key1, $key2, $key3, $key4, $keyFormat, $transmissionKey, $authentication)
    {
        //   Update encryption keys for the station profile wireless security.
        $rc = $this->UpdateStationProfileWEPEncryptionKeys(array("profileName" => $profileName, "key1" => $key1, "key2" => $key2, "key3" => $key3, "key4" => $key4, "keyFormat" => $keyFormat, "transmissionKey" => $transmissionKey, "authentication" => $authentication));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStationProfileWEPEncryptionKeys()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetStationProfileWEPEncryptionKeys($profileName)
    {
        //   Get station profile encryption keys. 
        $rc = $this->GetStationProfileWEPEncryptionKeys(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateStationProfileEAPMethod()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateStationProfileEAPMethod($profileName, $eapMethod, $userName, $password, $anonymous, $validateServerCertificate, $commonName, $tlsIdentity, $tlsCertificate)
    {
        //   Update EAP method for a station profile. Also set a username and a password to well authenticate the user.
        $rc = $this->UpdateStationProfileEAPMethod(array("profileName" => $profileName, "eapMethod" => $eapMethod, "userName" => $userName, "password" => $password, "anonymous" => $anonymous, "validateServerCertificate" => $validateServerCertificate, "commonName" => $commonName, "tlsIdentity" => $tlsIdentity, "tlsCertificate" => $tlsCertificate));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStationProfileEAPMethod()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetStationProfileEAPMethod($profileName)
    {
        //   Get station profile EAP method. 
        $rc = $this->GetStationProfileEAPMethod(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateStationProfilePSKKey()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateStationProfilePSKKey($profileName, $key)
    {
        //   Update PSK key for a station profile.
        $rc = $this->UpdateStationProfilePSKKey(array("profileName" => $profileName, "key" => $key));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStationProfilePSKKey()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetStationProfilePSKKey($profileName)
    {
        //   Get station profile PSK key. 
        $rc = $this->GetStationProfilePSKKey(array("profileName" => $profileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioFastRoamingThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateRadioFastRoamingThreshold($deviceId, $state, $threshold)
    {
        //   Update fast roaming threshold. 
        $rc = $this->UpdateRadioFastRoamingThreshold(array("deviceId" => $deviceId, "state" => $state, "threshold" => $threshold));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioFastRoamingThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioFastRoamingThreshold($deviceId)
    {
        //   Get Radio Fast Roaming Threshold 
        $rc = $this->GetRadioFastRoamingThreshold(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioFastRoamingDeltaThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateRadioFastRoamingDeltaThreshold($deviceId, $state, $deltaThreshold)
    {
        //   Update fast roaming delta threshold. 
        $rc = $this->UpdateRadioFastRoamingDeltaThreshold(array("deviceId" => $deviceId, "state" => $state, "deltaThreshold" => $deltaThreshold));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioFastRoamingDeltaThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioFastRoamingDeltaThreshold($deviceId)
    {
        //   Get Radio Fast Roaming Delta Threshold 
        $rc = $this->GetRadioFastRoamingDeltaThreshold(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioFastRoamingThresholdCount()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateRadioFastRoamingThresholdCount($deviceId, $count)
    {
        //   Update fast roaming threshold count. 
        $rc = $this->UpdateRadioFastRoamingThresholdCount(array("deviceId" => $deviceId, "count" => $count));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioFastRoamingThresholdCount()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioFastRoamingThresholdCount($deviceId)
    {
        //   Get Radio Fast Roaming Threshold Count
        $rc = $this->GetRadioFastRoamingThresholdCount(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioMinimumRSSIThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateRadioMinimumRSSIThreshold($deviceId, $state, $rssiThreshold)
    {
        //   Update minimum RSSI threshold. 
        $rc = $this->UpdateRadioMinimumRSSIThreshold(array("deviceId" => $deviceId, "state" => $state, "rssiThreshold" => $rssiThreshold));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioMinimumRSSIThreshold()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioMinimumRSSIThreshold($deviceId)
    {
        //   Get Radio Minimum RSSI Threshold
        $rc = $this->GetRadioMinimumRSSIThreshold(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioScanChannelDelay()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateRadioScanChannelDelay($deviceId, $delay)
    {
        //   Update Scan Channel Delay. 
        $rc = $this->UpdateRadioScanChannelDelay(array("deviceId" => $deviceId, "delay" => $delay));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioScanChannelDelay()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioScanChannelDelay($deviceId)
    {
        //   Get Radio Scan Channel Delay
        $rc = $this->GetRadioScanChannelDelay(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioFastScanChannelDelay()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateRadioFastScanChannelDelay($deviceId, $delay)
    {
        //   Update Fast Scan Channel Delay. 
        $rc = $this->UpdateRadioFastScanChannelDelay(array("deviceId" => $deviceId, "delay" => $delay));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioFastScanChannelDelay()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioFastScanChannelDelay($deviceId)
    {
        //   Get Radio Fast Scan Channel Delay
        $rc = $this->GetRadioFastScanChannelDelay(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioRoamingPersistance()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateRadioRoamingPersistance($deviceId, $state, $persistence)
    {
        //   Update roaming persistance. 
        $rc = $this->UpdateRadioRoamingPersistance(array("deviceId" => $deviceId, "state" => $state, "persistence" => $persistence));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioRoamingPersistance()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioRoamingPersistance($deviceId)
    {
        //   Get Radio Roaming Persistance 
        $rc = $this->GetRadioRoamingPersistance(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddRadioRestrictedChannel()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapAddRadioRestrictedChannel($deviceId, $restrictedChannel)
    {
        //   Add radio restricted channels. 
        $rc = $this->AddRadioRestrictedChannel(array("deviceId" => $deviceId, "restrictedChannel" => $restrictedChannel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteRadioRestrictedChannel()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapDeleteRadioRestrictedChannel($deviceId, $restrictedChannel)
    {
        //  Delete the specified channel from the list of restricted channels.
        $rc = $this->DeleteRadioRestrictedChannel(array("deviceId" => $deviceId, "restrictedChannel" => $restrictedChannel));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioRestrictedChannels()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioRestrictedChannels($deviceId)
    {
        //   Get Radio Restricted Channels 
        $rc = $this->GetRadioRestrictedChannels(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateRadioProfile()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateRadioProfile($deviceId, $state, $radioOperatingMode, $radioPhyType, $delay, $rssiState, $rssiThreshold, $fastRoamingState, $fastRoamingThreshold, $roamingPersistenceState, $persistance, $powerDBm, $rtsThresholdState, $bytes)
    {
        //   Update Radio Profile 
        $rc = $this->UpdateRadioProfile(array("deviceId" => $deviceId, "state" => $state, "radioOperatingMode" => $radioOperatingMode, "radioPhyType" => $radioPhyType, "delay" => $delay, "rssiState" => $rssiState, "rssiThreshold" => $rssiThreshold, "fastRoamingState" => $fastRoamingState, "fastRoamingThreshold" => $fastRoamingThreshold, "roamingPersistenceState" => $roamingPersistenceState, "persistance" => $persistance, "powerDBm" => $powerDBm, "rtsThresholdState" => $rtsThresholdState, "bytes" => $bytes));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetRadioProfile()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetRadioProfile($deviceId)
    {
        //   Get Radio Profile 
        $rc = $this->GetRadioProfile(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWirelessNAT()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateWirelessNAT($portId, $state)
    {
        //   Update network address translation (NAT).
        $rc = $this->UpdateWirelessNAT(array("portId" => $portId, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessNAT()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetWirelessNAT($portId)
    {
        //   Get network address translation status for the wireless port. 
        $rc = $this->GetWirelessNAT(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWirelessDHCPClientId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateWirelessDHCPClientId($portId, $clientID)
    {
        //   Configure DHCP Client.
        $rc = $this->UpdateWirelessDHCPClientId(array("portId" => $portId, "clientID" => $clientID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessDHCPClientId()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetWirelessDHCPClientId($portId)
    {
        //   Get DHCP client for the wireless port. 
        $rc = $this->GetWirelessDHCPClientId(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWirelessIPAssignationMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateWirelessIPAssignationMode($portId, $assignationMode)
    {
        //   Update Wireless IP address assignation mode. 
        $rc = $this->UpdateWirelessIPAssignationMode(array("portId" => $portId, "assignationMode" => $assignationMode));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessIPAssignationMode()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetWirelessIPAssignationMode($portId)
    {
        //   Get IP address assignation mode for Wireless port.. 
        $rc = $this->GetWirelessIPAssignationMode(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWirelessStaticAddr()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateWirelessStaticAddr($portId, $ipAddress, $addressMask, $host)
    {
        //   Configure static IP Address.
        $rc = $this->UpdateWirelessStaticAddr(array("portId" => $portId, "ipAddress" => $ipAddress, "addressMask" => $addressMask, "host" => $host));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessStaticAddr()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetWirelessStaticAddr($portId)
    {
        //   Get wireless static address. 
        $rc = $this->GetWirelessStaticAddr(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWapBSSID()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// DRAGONFLY,
// ALLPPC26.
    function soapGetWapBSSID($deviceId)
    {
        //  Get wpa supplicant current BSSID.
        $rc = $this->GetWapBSSID(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteBlockWapBSSID()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// DRAGONFLY,
// ALLPPC26.
    function soapExecuteBlockWapBSSID($deviceId, $bssid)
    {
        //  Block wireless access point BSSID.
        $rc = $this->ExecuteBlockWapBSSID(array("deviceId" => $deviceId, "bssid" => $bssid));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteUnblockWapBSSID()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// DRAGONFLY,
// ALLPPC26.
    function soapExecuteUnblockWapBSSID($deviceId, $bssid)
    {
        //  Unblock wireless access point BSSID.
        $rc = $this->ExecuteUnblockWapBSSID(array("deviceId" => $deviceId, "bssid" => $bssid));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteClearAllBlockedWapBSSIDs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// DRAGONFLY,
// ALLPPC26.
    function soapExecuteClearAllBlockedWapBSSIDs($deviceId)
    {
        //  Clear wpa supplicant blocked list of all entries.
        $rc = $this->ExecuteClearAllBlockedWapBSSIDs(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteReconnectWirelessClient()
//
// WARNING: function not supported by following board types:
// ZURICH,
// GOLDFISH,
// SUNFISH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// DRAGONFLY,
// ALLPPC26.
    function soapExecuteReconnectWirelessClient($deviceId)
    {
        //  Reassociate a wireless client and add the current BSSID to a black list.
        $rc = $this->ExecuteReconnectWirelessClient(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetAPCertificate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetAPCertificate()
    {
        //   Get the AP certificate. This certificate is being presented during authentication process (when WAB associates to the AP). 
        $rc = $this->GetAPCertificate(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddCertificate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapAddCertificate($certificateType, $password)
    {
        //   adding certificate to certificates store. 
        $rc = $this->AddCertificate(array("certificateType" => $certificateType, "password" => $password));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteCertificate()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteCertificate($certificateType, $certID)
    {
        //   delete certificate from certificates store. 
        $rc = $this->DeleteCertificate(array("certificateType" => $certificateType, "certID" => $certID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetCertificatesIDs()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetCertificatesIDs($maxCert, $certificateType)
    {
        //   get certificates IDs from certificates stores
        $rc = $this->GetCertificatesIDs(array("maxCert" => $maxCert, "certificateType" => $certificateType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetCertificateUsage()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetCertificateUsage($certID, $maxServ)
    {
        //   get certificate usage 
        $rc = $this->GetCertificateUsage(array("certID" => $certID, "maxServ" => $maxServ));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateCertificateUsage()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateCertificateUsage($todo, $certificateType, $certID, $service)
    {
        //   update certificate usage 
        $rc = $this->UpdateCertificateUsage(array("todo" => $todo, "certificateType" => $certificateType, "certID" => $certID, "service" => $service));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function Get8021xCertificatesNames()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGet8021xCertificatesNames($certificateType)
    {
        //   Get 802.1x certificates names
        $rc = $this->Get8021xCertificatesNames(array("certificateType" => $certificateType));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function Remove8021xCertificateByName()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapRemove8021xCertificateByName($certificateName)
    {
        //   Remove an 802.1x certificate by name. User must specify the certificate name.
        $rc = $this->Remove8021xCertificateByName(array("certificateName" => $certificateName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateCRLInCA()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapUpdateCRLInCA($certID)
    {
        //   Update CRL in CA store. 
        $rc = $this->UpdateCRLInCA(array("certID" => $certID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteCRLFromCA()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapDeleteCRLFromCA($certID)
    {
        //   Delete CRL from CA store. 
        $rc = $this->DeleteCRLFromCA(array("certID" => $certID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function InstallFirmware()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapInstallFirmware()
    {
        //   Install new firmware. 
        $rc = $this->InstallFirmware(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetMiltopeNumbers()
//
    function soapGetMiltopeNumbers()
    {
        //   Get MILTOPE Serial number, MILTOPE H/W part number and MILTOPE Operating System S/W part number. 
        $rc = $this->GetMiltopeNumbers(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDiscretePinStatus()
//
    function soapGetDiscretePinStatus()
    {
        //   Get MILTOPE discrete pin (turned on or off). 
        $rc = $this->GetDiscretePinStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStrapState()
//
    function soapGetStrapState()
    {
        //   Get MILTOPE IP strap state. 
        $rc = $this->GetStrapState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateMiltopeOPC()
//
    function soapUpdateMiltopeOPC($miltopeOPC)
    {
        //   Set MILTOPE OPC P/N. 
        $rc = $this->UpdateMiltopeOPC(array("miltopeOPC" => $miltopeOPC));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDHCPClientInfo()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH.
    function soapGetDHCPClientInfo($portId)
    {
        //   Get DHCP client info (assigned by DHCP Server). 
        $rc = $this->GetDHCPClientInfo(array("portId" => $portId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddStationProfileIPQosProfile()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapAddStationProfileIPQosProfile($stationProfileName, $ipQosProfileName)
    {
        //  Add the specified IP QOS profile to existing list of IP QOS profiles to be used for a profile station.
        $rc = $this->AddStationProfileIPQosProfile(array("stationProfileName" => $stationProfileName, "ipQosProfileName" => $ipQosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStationProfileIPQosProfiles()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetStationProfileIPQosProfiles($stationProfileName)
    {
        //   Get station profile IP Qos Profile List. 
        $rc = $this->GetStationProfileIPQosProfiles(array("stationProfileName" => $stationProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteStationProfileIPQosProfile()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapDeleteStationProfileIPQosProfile($stationProfileName, $ipQosProfileName)
    {
        //  Delete the specified IP QOS profile from existing list of IP QOS profiles to be used for the station profile.
        $rc = $this->DeleteStationProfileIPQosProfile(array("stationProfileName" => $stationProfileName, "ipQosProfileName" => $ipQosProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateStationProfileWirelessQoS()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapUpdateStationProfileWirelessQoS($stationProfileName, $priority, $diffServ)
    {
        //  Update Station Profile wireless QoS settings.
        $rc = $this->UpdateStationProfileWirelessQoS(array("stationProfileName" => $stationProfileName, "priority" => $priority, "diffServ" => $diffServ));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStationProfileWirelessQoS()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetStationProfileWirelessQoS($stationProfileName)
    {
        //  Get Station Profile wireless QoS settings.
        $rc = $this->GetStationProfileWirelessQoS(array("stationProfileName" => $stationProfileName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStationProfilesList()
//
// WARNING: function not supported by following board types:
// ZURICH.
    function soapGetStationProfilesList($deviceId)
    {
        //  Get list of station profiles.
        $rc = $this->GetStationProfilesList(array("deviceId" => $deviceId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateL2TPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateL2TPServer($state, $mode, $presharedKey)
    {
        //   Update L2TP VPN Server state and settings. 
        $rc = $this->UpdateL2TPServer(array("state" => $state, "mode" => $mode, "presharedKey" => $presharedKey));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetL2TPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetL2TPServer()
    {
        //   Get L2TP VPN Server state and settings. 
        $rc = $this->GetL2TPServer(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePPTPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdatePPTPServer($state)
    {
        //   Update PPTP VPN Server state. 
        $rc = $this->UpdatePPTPServer(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPPTPServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetPPTPServer()
    {
        //   Get PPTP VPN Server state. 
        $rc = $this->GetPPTPServer(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVPNAddressPool()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateVPNAddressPool($source, $maxConnections, $staticFirstAddress, $port, $clientId, $externalDHCPAddress)
    {
        //   Update VPN Address Pool settings. 
        $rc = $this->UpdateVPNAddressPool(array("source" => $source, "maxConnections" => $maxConnections, "staticFirstAddress" => $staticFirstAddress, "port" => $port, "clientId" => $clientId, "externalDHCPAddress" => $externalDHCPAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetVPNAddressPool()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetVPNAddressPool()
    {
        //   Get VPN Address Pool settings. 
        $rc = $this->GetVPNAddressPool(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddWebServerMIMEType()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapAddWebServerMIMEType($extension, $mimeType, $textBased)
    {
        //   Add a MIME type to the Web server settings. 
        $rc = $this->AddWebServerMIMEType(array("extension" => $extension, "mimeType" => $mimeType, "textBased" => $textBased));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateWebServerMIMEType()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateWebServerMIMEType($oldExtension, $newExtension, $mimeType, $textBased)
    {
        //   Update a MIME type in the Web server settings. 
        $rc = $this->UpdateWebServerMIMEType(array("oldExtension" => $oldExtension, "newExtension" => $newExtension, "mimeType" => $mimeType, "textBased" => $textBased));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteWebServerMIMEType()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapDeleteWebServerMIMEType($extension)
    {
        //   Delete a MIME type from the Web server settings. 
        $rc = $this->DeleteWebServerMIMEType(array("extension" => $extension));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllWebServerMIMETypes()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapDeleteAllWebServerMIMETypes()
    {
        //   Delete all MIME types (non-hardcoded) from the Web server settings. 
        $rc = $this->DeleteAllWebServerMIMETypes(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWebServerMIMETypeList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetWebServerMIMETypeList()
    {
        //   Get the MIME type list. 
        $rc = $this->GetWebServerMIMETypeList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddNetworkProfile()
//
    function soapAddNetworkProfile($name, $vlanIDState, $vlanID)
    {
        //   Add a network profile. 
        $rc = $this->AddNetworkProfile(array("name" => $name, "vlanIDState" => $vlanIDState, "vlanID" => $vlanID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateNetworkProfile()
//
    function soapUpdateNetworkProfile($oldName, $newName, $vlanIDState, $vlanID)
    {
        //   Update network profile. 
        $rc = $this->UpdateNetworkProfile(array("oldName" => $oldName, "newName" => $newName, "vlanIDState" => $vlanIDState, "vlanID" => $vlanID));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateDefaultNetwork()
//
    function soapUpdateDefaultNetwork($networkName, $defaultState)
    {
        //   Update default network. 
        $rc = $this->UpdateDefaultNetwork(array("networkName" => $networkName, "defaultState" => $defaultState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteNetworkProfile()
//
    function soapDeleteNetworkProfile($name)
    {
        //   Delete network profile. 
        $rc = $this->DeleteNetworkProfile(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllNetworkProfiles()
//
    function soapDeleteAllNetworkProfiles()
    {
        //   Delete all network profiles. 
        $rc = $this->DeleteAllNetworkProfiles(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetNetworkProfileList()
//
    function soapGetNetworkProfileList()
    {
        //   Get all network profiles. 
        $rc = $this->GetNetworkProfileList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetNetworkProfile()
//
    function soapGetNetworkProfile($name)
    {
        //   Get network profile settings. 
        $rc = $this->GetNetworkProfile(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddNetworkAssignation()
//
    function soapAddNetworkAssignation($networkDef)
    {
        //   Add a network assignation. 
        $rc = $this->AddNetworkAssignation(array("networkDef" => $networkDef));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteNetworkAssignation()
//
    function soapDeleteNetworkAssignation($networkDef)
    {
        //  This function removes a network assignation.
        $rc = $this->DeleteNetworkAssignation(array("networkDef" => $networkDef));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateTeamState($state)
    {
        //   Update teaming state. 
        $rc = $this->UpdateTeamState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetTeamState()
    {
        //   Get teaming state. 
        $rc = $this->GetTeamState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamConnectivity()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateTeamConnectivity($interface, $vlanState, $vlanId, $vlanIPAddress, $vlanMask)
    {
        //   Update team connectivity settings. 
        $rc = $this->UpdateTeamConnectivity(array("interface" => $interface, "vlanState" => $vlanState, "vlanId" => $vlanId, "vlanIPAddress" => $vlanIPAddress, "vlanMask" => $vlanMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamConnectivity()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetTeamConnectivity()
    {
        //   Get team connectivity settings. 
        $rc = $this->GetTeamConnectivity(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamManagerState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateTeamManagerState($state)
    {
        //   Update team Manager state. 
        $rc = $this->UpdateTeamManagerState(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamManagerState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetTeamManagerState()
    {
        //   Get team manager state. 
        $rc = $this->GetTeamManagerState(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamManagementInterface()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateTeamManagementInterface($name, $ipAddress, $mask, $interface, $vlanName)
    {
        //   Update team management interface settings. 
        $rc = $this->UpdateTeamManagementInterface(array("name" => $name, "ipAddress" => $ipAddress, "mask" => $mask, "interface" => $interface, "vlanName" => $vlanName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamManagementInterface()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetTeamManagementInterface()
    {
        //   Get team management interface settings. 
        $rc = $this->GetTeamManagementInterface(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateActionId()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING.
    function soapUpdateActionId($actionId)
    {
        //   Update the action id used to send back status about MAP. 
        $rc = $this->UpdateActionId(array("actionId" => $actionId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddTeamMemberInternal()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapAddTeamMemberInternal($name, $macAddress, $bridgeIPAddress, $serial, $uniqueId, $contact, $location)
    {
        //   Add a new member to the controller team. 
        $rc = $this->AddTeamMemberInternal(array("name" => $name, "macAddress" => $macAddress, "bridgeIPAddress" => $bridgeIPAddress, "serial" => $serial, "uniqueId" => $uniqueId, "contact" => $contact, "location" => $location));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddTeamMember()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapAddTeamMember($name, $macAddress, $contact, $location)
    {
        //   Add a new member to the controller team. 
        $rc = $this->AddTeamMember(array("name" => $name, "macAddress" => $macAddress, "contact" => $contact, "location" => $location));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamMember()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapUpdateTeamMember($macAddress, $newName, $contact, $location)
    {
        //   Update a member of the controller team. 
        $rc = $this->UpdateTeamMember(array("macAddress" => $macAddress, "newName" => $newName, "contact" => $contact, "location" => $location));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamMemberInternal()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapUpdateTeamMemberInternal($macAddress, $bridgeIPAddress, $newName, $contact, $location)
    {
        //   Update a member of the controller team. 
        $rc = $this->UpdateTeamMemberInternal(array("macAddress" => $macAddress, "bridgeIPAddress" => $bridgeIPAddress, "newName" => $newName, "contact" => $contact, "location" => $location));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamMember()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapGetTeamMember($macAddress)
    {
        //   Get the settings for a member of the controller team. 
        $rc = $this->GetTeamMember(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteTeamMember()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapDeleteTeamMember($macAddress)
    {
        //   Remove a member from the controller team. 
        $rc = $this->DeleteTeamMember(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamMemberList()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapGetTeamMemberList()
    {
        //   Get the list of team members. 
        $rc = $this->GetTeamMemberList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllTeamMembers()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapDeleteAllTeamMembers()
    {
        //   Delete all team members. 
        $rc = $this->DeleteAllTeamMembers(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamMemberConfigRevision()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapUpdateTeamMemberConfigRevision($macAddress, $configRevision)
    {
        //   Update the team member configuration revision. 
        $rc = $this->UpdateTeamMemberConfigRevision(array("macAddress" => $macAddress, "configRevision" => $configRevision));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamMemberLog()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapUpdateTeamMemberLog($macAddress, $filterOperator, $messageFilterState, $notMessageState, $message, $severityFilterState, $notSeverityState, $severity, $processFilterState, $notProcessState, $process)
    {
        //   Update the team member logging configuration. 
        $rc = $this->UpdateTeamMemberLog(array("macAddress" => $macAddress, "filterOperator" => $filterOperator, "messageFilterState" => $messageFilterState, "notMessageState" => $notMessageState, "message" => $message, "severityFilterState" => $severityFilterState, "notSeverityState" => $notSeverityState, "severity" => $severity, "processFilterState" => $processFilterState, "notProcessState" => $notProcessState, "process" => $process));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamMemberLog()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapGetTeamMemberLog($macAddress)
    {
        //   Get the team member logging configuration. 
        $rc = $this->GetTeamMemberLog(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamMemberVLANStaticIP()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapUpdateTeamMemberVLANStaticIP($macAddress, $vlanName, $ipAddress, $ipMask, $ipGateway)
    {
        //   Update the team member VLAN static IP Address. 
        $rc = $this->UpdateTeamMemberVLANStaticIP(array("macAddress" => $macAddress, "vlanName" => $vlanName, "ipAddress" => $ipAddress, "ipMask" => $ipMask, "ipGateway" => $ipGateway));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamMemberVLANStaticIP()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapGetTeamMemberVLANStaticIP($macAddress, $vlanName)
    {
        //   Get the team member VLAN static IP Address. 
        $rc = $this->GetTeamMemberVLANStaticIP(array("macAddress" => $macAddress, "vlanName" => $vlanName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateTeamMemberGRETunnel()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapUpdateTeamMemberGRETunnel($macAddress, $greName, $localTunnelIP, $remoteTunnelIP, $tunnelIPMask)
    {
        //   Update the team member GRE IP Address. 
        $rc = $this->UpdateTeamMemberGRETunnel(array("macAddress" => $macAddress, "greName" => $greName, "localTunnelIP" => $localTunnelIP, "remoteTunnelIP" => $remoteTunnelIP, "tunnelIPMask" => $tunnelIPMask));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTeamMemberGRETunnel()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapGetTeamMemberGRETunnel($macAddress, $greName)
    {
        //   Get the team member GRE tunnel IP Addresses. 
        $rc = $this->GetTeamMemberGRETunnel(array("macAddress" => $macAddress, "greName" => $greName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteTeamJoin()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapExecuteTeamJoin($teamIdentifier)
    {
        //  Have a candidate controller join a team and become member using the specified team ID. 
        $rc = $this->ExecuteTeamJoin(array("teamIdentifier" => $teamIdentifier));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteTeamRelease()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapExecuteTeamRelease()
    {
        //  Release a controller from its current team.
        $rc = $this->ExecuteTeamRelease(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ExecuteToggleControlledAPDiscoveryAndServices()
//
// WARNING: function not supported by following board types:
// CN10xx,
// SUNFISH,
// OPTIMIST,
// SOLING,
// ALLPPC,
// ALLPPC26,
// ZURICH,
// CHAMELEON.
    function soapExecuteToggleControlledAPDiscoveryAndServices($state)
    {
        //   Prevent a controller from discovering APs during a full config. 
        $rc = $this->ExecuteToggleControlledAPDiscoveryAndServices(array("state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddMACLockout()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddMACLockout($macAddress)
    {
        //   Add MAC lockout address. 
        $rc = $this->AddMACLockout(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteMACLockout()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteMACLockout($macAddress)
    {
        //   Delete MAC lockout address. 
        $rc = $this->DeleteMACLockout(array("macAddress" => $macAddress));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetMACLockoutList()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetMACLockoutList()
    {
        //   Get the MAC lockout addresses list. 
        $rc = $this->GetMACLockoutList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllMACLockout()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteAllMACLockout()
    {
        //   Delete all MAC lockout addresses. 
        $rc = $this->DeleteAllMACLockout(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateAllMACLockout()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateAllMACLockout($macList)
    {
        //   Update all MAC lockout addresses.. 
        $rc = $this->UpdateAllMACLockout(array("macList" => $macList));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddLocalMeshProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddLocalMeshProfile($name, $state)
    {
        //   Add a Local mesh profile. 
        $rc = $this->AddLocalMeshProfile(array("name" => $name, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalMeshProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateLocalMeshProfile($oldName, $newName, $state)
    {
        //   Update a Local mesh profile. 
        $rc = $this->UpdateLocalMeshProfile(array("oldName" => $oldName, "newName" => $newName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalMeshProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetLocalMeshProfile($name)
    {
        //   Get the Local mesh profile state. 
        $rc = $this->GetLocalMeshProfile(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteLocalMeshProfile()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteLocalMeshProfile($name)
    {
        //   Delete a Local mesh profile. 
        $rc = $this->DeleteLocalMeshProfile(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalMeshProfileList()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetLocalMeshProfileList()
    {
        //   Get the list of Local mesh profiles. 
        $rc = $this->GetLocalMeshProfileList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalMeshProfileStaticAddressing()
//
    function soapUpdateLocalMeshProfileStaticAddressing($name, $speed, $remoteMacAddr)
    {
        //   Update local mesh static addressing settings. 
        $rc = $this->UpdateLocalMeshProfileStaticAddressing(array("name" => $name, "speed" => $speed, "remoteMacAddr" => $remoteMacAddr));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalMeshProfileStaticAddressing()
//
    function soapGetLocalMeshProfileStaticAddressing($name)
    {
        //   Get local mesh static addressing settings. 
        $rc = $this->GetLocalMeshProfileStaticAddressing(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalMeshProfileDynamicAddressing()
//
    function soapUpdateLocalMeshProfileDynamicAddressing($name, $mode, $meshId, $minimumSNR, $snrCostPerHop, $allowedDowntime, $maximumLinks, $discoveryTime, $promiscuousModeState, $promiscuousModeStandoffTime, $preserveMasterLink, $allowForcedLinks)
    {
        //   Update local mesh dynamic addressing settings. 
        $rc = $this->UpdateLocalMeshProfileDynamicAddressing(array("name" => $name, "mode" => $mode, "meshId" => $meshId, "minimumSNR" => $minimumSNR, "snrCostPerHop" => $snrCostPerHop, "allowedDowntime" => $allowedDowntime, "maximumLinks" => $maximumLinks, "discoveryTime" => $discoveryTime, "promiscuousModeState" => $promiscuousModeState, "promiscuousModeStandoffTime" => $promiscuousModeStandoffTime, "preserveMasterLink" => $preserveMasterLink, "allowForcedLinks" => $allowForcedLinks));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalMeshProfileDynamicAddressing()
//
    function soapGetLocalMeshProfileDynamicAddressing($name)
    {
        //   Get local mesh dynamic addressing settings. 
        $rc = $this->GetLocalMeshProfileDynamicAddressing(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalMeshProfileAddressingType()
//
    function soapUpdateLocalMeshProfileAddressingType($name, $type)
    {
        //   Update local mesh addressing type. 
        $rc = $this->UpdateLocalMeshProfileAddressingType(array("name" => $name, "type" => $type));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalMeshProfileAddressingType()
//
    function soapGetLocalMeshProfileAddressingType($name)
    {
        //   Get local mesh addressing type. 
        $rc = $this->GetLocalMeshProfileAddressingType(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddLocalMeshProfileDynamicPort()
//
    function soapAddLocalMeshProfileDynamicPort($name, $port)
    {
        //   Add a port to use with local mesh. 
        $rc = $this->AddLocalMeshProfileDynamicPort(array("name" => $name, "port" => $port));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteLocalMeshProfileDynamicPort()
//
    function soapDeleteLocalMeshProfileDynamicPort($name, $port)
    {
        //   Delete a port to use with local mesh. 
        $rc = $this->DeleteLocalMeshProfileDynamicPort(array("name" => $name, "port" => $port));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalMeshProfileStaticPort()
//
    function soapUpdateLocalMeshProfileStaticPort($name, $port)
    {
        //   Update port in use with static local mesh. 
        $rc = $this->UpdateLocalMeshProfileStaticPort(array("name" => $name, "port" => $port));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalMeshProfilePortList()
//
    function soapGetLocalMeshProfilePortList($name)
    {
        //   Get port in use with local mesh. 
        $rc = $this->GetLocalMeshProfilePortList(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLocalMeshProfilePolicyManager()
//
    function soapUpdateLocalMeshProfilePolicyManager($name, $policyManagerState, $enforceNodeLimitState, $nodeLimit)
    {
        //   Update local mesh policy manager settings. 
        $rc = $this->UpdateLocalMeshProfilePolicyManager(array("name" => $name, "policyManagerState" => $policyManagerState, "enforceNodeLimitState" => $enforceNodeLimitState, "nodeLimit" => $nodeLimit));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLocalMeshProfilePolicyManager()
//
    function soapGetLocalMeshProfilePolicyManager($name)
    {
        //   Get local mesh policy manager settings. 
        $rc = $this->GetLocalMeshProfilePolicyManager(array("name" => $name));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdatePresentLocalWelcomePage()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdatePresentLocalWelcomePage($presentLocalWelcomePage)
    {
        //   Update the presentation of the local welcome page settings. 
        $rc = $this->UpdatePresentLocalWelcomePage(array("presentLocalWelcomePage" => $presentLocalWelcomePage));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetPresentLocalWelcomePage()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetPresentLocalWelcomePage()
    {
        //   Get the presentation of the local welcome page settings. 
        $rc = $this->GetPresentLocalWelcomePage(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function InstallLicense()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapInstallLicense()
    {
        //   Install a new license file. 
        $rc = $this->InstallLicense(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ActivateLicense()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapActivateLicense()
    {
        //   Activate the license file. 
        $rc = $this->ActivateLicense(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeactivateLicense()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapDeactivateLicense()
    {
        //   Deactivate the license file. 
        $rc = $this->DeactivateLicense(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function RemoveLicense()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapRemoveLicense()
    {
        //   Remove the license file. 
        $rc = $this->RemoveLicense(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLicenseList()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH.
    function soapGetLicenseList()
    {
        //   Get the features included in the license file. 
        $rc = $this->GetLicenseList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateLanPortIEEE8021X()
//
    function soapUpdateLanPortIEEE8021X($state, $eapMethod, $eapIdentity, $eapPassword, $anonymousIdentity)
    {
        //   Update LAN Port IEEE 802.1X configuration on APs.
        $rc = $this->UpdateLanPortIEEE8021X(array("state" => $state, "eapMethod" => $eapMethod, "eapIdentity" => $eapIdentity, "eapPassword" => $eapPassword, "anonymousIdentity" => $anonymousIdentity));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetLanPortIEEE8021X()
//
    function soapGetLanPortIEEE8021X()
    {
        //   Get LAN Port IEEE 802.1X configuration on APs.
        $rc = $this->GetLanPortIEEE8021X(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateVlanPort2()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapUpdateVlanPort2($useVlanPort2, $vlanId)
    {
        //   Update the VLAN on port2. The traffic arrives untagged in Port2 and goes tagged on Port1. 
        $rc = $this->UpdateVlanPort2(array("useVlanPort2" => $useVlanPort2, "vlanId" => $vlanId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateVLANPort2()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateVLANPort2($level, $entityName, $useVlanPort2, $vlanId)
    {
        //   Update the VLAN on port2. The traffic arrives untagged in Port2 and goes tagged on Port1. 
        $rc = $this->ControlledNetworkUpdateVLANPort2(array("level" => $level, "entityName" => $entityName, "useVlanPort2" => $useVlanPort2, "vlanId" => $vlanId));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetVLANPort2()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetVLANPort2($level, $entityName)
    {
        //   Get the VLAN on port2 settings. 
        $rc = $this->ControlledNetworkGetVLANPort2(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkUpdateVLANPort2Inheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkUpdateVLANPort2Inheritance($level, $entityName, $state)
    {
        //   Update the VLAN on port2 inheritance. 
        $rc = $this->ControlledNetworkUpdateVLANPort2Inheritance(array("level" => $level, "entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkGetVLANPort2Inheritance()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapControlledNetworkGetVLANPort2Inheritance($level, $entityName)
    {
        //   Get the VLAN on port2 inheritance state. 
        $rc = $this->ControlledNetworkGetVLANPort2Inheritance(array("level" => $level, "entityName" => $entityName));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateFtpServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateFtpServer($state, $username, $password)
    {
        //   Update ftp server settings. 
        $rc = $this->UpdateFtpServer(array("state" => $state, "username" => $username, "password" => $password));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetFtpServer()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetFtpServer()
    {
        //   Get ftp server settings. 
        $rc = $this->GetFtpServer(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateManagementConsole()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapUpdateManagementConsole($state, $hostname)
    {
        //   Update PMM Client settings. 
        $rc = $this->UpdateManagementConsole(array("state" => $state, "hostname" => $hostname));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetManagementConsole()
//
// WARNING: function not supported by following board types:
// ZURICH,
// CN10xx,
// SUNFISH,
// ALLPPC,
// ALLPPC26.
    function soapGetManagementConsole()
    {
        //   Get PMM Client settings. 
        $rc = $this->GetManagementConsole(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function ControlledNetworkExecuteWaitAPState()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26.
    function soapControlledNetworkExecuteWaitAPState($entityName, $state)
    {
        //   Wait for an AP to be in a given state before returning. 
        $rc = $this->ControlledNetworkExecuteWaitAPState(array("entityName" => $entityName, "state" => $state));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddDSCPMapping()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapAddDSCPMapping($tagValue, $priority)
    {
        //   Add DSCP Mapping. 
        $rc = $this->AddDSCPMapping(array("tagValue" => $tagValue, "priority" => $priority));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteDSCPMapping()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteDSCPMapping($tagValue)
    {
        //   Delete DSCP Mapping. 
        $rc = $this->DeleteDSCPMapping(array("tagValue" => $tagValue));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteAllDSCPMappings()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapDeleteAllDSCPMappings()
    {
        //   Delete all DSCP Mappings. 
        $rc = $this->DeleteAllDSCPMappings(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetDSCPMappingList()
//
// WARNING: function not supported by following board types:
// SUNFISH.
    function soapGetDSCPMappingList()
    {
        //   Get the list of DSCP Mappings. 
        $rc = $this->GetDSCPMappingList(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateStatsPollingParamters()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapUpdateStatsPollingParamters($pollPeriod, $pollAPNumber, $tcpTimeout, $httpTimeout)
    {
        //   Configure the stats polling parameters. 
        $rc = $this->UpdateStatsPollingParamters(array("pollPeriod" => $pollPeriod, "pollAPNumber" => $pollAPNumber, "tcpTimeout" => $tcpTimeout, "httpTimeout" => $httpTimeout));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetStatsPollingParamters()
//
// WARNING: function not supported by following board types:
// ZURICH,
// OPTIMIST,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// SUNFISH.
    function soapGetStatsPollingParamters()
    {
        //   Get the stats polling parameters. 
        $rc = $this->GetStatsPollingParamters(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function UpdateSimAPState()
//
    function soapUpdateSimAPState($simApIndex, $newState)
    {
        //  AP simulator: start/stop a simulated AP.
        $rc = $this->UpdateSimAPState(array("simApIndex" => $simApIndex, "newState" => $newState));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function DeleteSimAp()
//
    function soapDeleteSimAp($simApIndex)
    {
        //  AP simulator: remove a simulated AP.
        $rc = $this->DeleteSimAp(array("simApIndex" => $simApIndex));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function AddSimAp()
//
    function soapAddSimAp($simApIndex, $simApProductType, $simApMaxClientCount, $simApInitialClientCount, $simApNewClientInterval, $simApNewClientsPerInterval, $simApClientCycling, $simApStatsInterval, $simApStatsPerInterval)
    {
        //  AP simulator: add a new simulated AP.
        $rc = $this->AddSimAp(array("simApIndex" => $simApIndex, "simApProductType" => $simApProductType, "simApMaxClientCount" => $simApMaxClientCount, "simApInitialClientCount" => $simApInitialClientCount, "simApNewClientInterval" => $simApNewClientInterval, "simApNewClientsPerInterval" => $simApNewClientsPerInterval, "simApClientCycling" => $simApClientCycling, "simApStatsInterval" => $simApStatsInterval, "simApStatsPerInterval" => $simApStatsPerInterval));

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetWirelessPortsPacketsStatus()
//
    function soapGetWirelessPortsPacketsStatus()
    {
        //  Get Wireless Packets Status
        $rc = $this->GetWirelessPortsPacketsStatus(array());

        return $rc;
    }


//-------------------------------------------------------------------------------
// implementation for function GetTrunkPortStatus()
//
// WARNING: function not supported by following board types:
// ZURICH,
// SUNFISH,
// SOLING,
// CHAMELEON,
// ALLPPC,
// ALLPPC26,
// OPTIMIST,
// emPC.
    function soapGetTrunkPortStatus()
    {
        //   Get static and dynamic port trunks. 
        $rc = $this->GetTrunkPortStatus(array());

        return $rc;
    }

};
?>
