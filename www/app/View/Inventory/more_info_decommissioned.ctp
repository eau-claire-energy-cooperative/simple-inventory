
<table>
    <tr>
        <th style="width: 200px;">Computer Name</th>
        <th style="width: 250px;">Tag</th>
        <th style="width: 250px;">Current User</th>
        <th style="width: 250px;">Serial Number</th>
        <th style="width: 250px;">Asset ID</th>
     
    </tr>

    <tr>
        <td><?php echo $decommissioned['Decommissioned']['ComputerName']?></td>
        <td><?php echo $decommissioned['Location']['location']; ?></td>
         
          <td><?php echo $decommissioned['Decommissioned']['CurrentUser']?></td>
           <td><?php echo $decommissioned['Decommissioned']['SerialNumber']?></td>
            <td><?php echo $decommissioned['Decommissioned']['AssetId']?> </td>

    </tr>

</table>

<table>
    <tr>
        <th style="width: 200px;">Model</th>
        <th style="width: 250px;">Operating System</th>
        <th style="width: 250px;">CPU</th>
        <th style="width: 250px;">Memory</th>
        <th style="width: 250px;">Number of Monitors</th>
     
    </tr>
	    <tr>
        <td> <?php echo $decommissioned['Decommissioned']['Model']; ?></td>
       
        <td><?php echo $decommissioned['Decommissioned']['OS']; ?></td> <!--  $comparisonID,$columnID,$modelID,$nameID -->
      
          <td><?php echo $decommissioned['Decommissioned']['CPU']?></td>
    
           <td> <?php echo $decommissioned['Decommissioned']['Memory'] . " GB"; ?></td>
        

             <td> <?php echo $decommissioned['Decommissioned']['NumberOfMonitors']; ?></td>
         </tr>
        
	
</table>

<table>
    <tr>
        <th style="width: 200px;">IP Address</th>
        <th style="width: 250px;">MAC Address</th>
 		<th style="width: 250px;">Last Updated</th>
 		<th style="width: 250px;"></th>
     	<th style="width: 250px;"></th>
     	
    </tr>
	    <tr>
        <td><?php echo $decommissioned['Decommissioned']['IPaddress']?></td>
         <td><?php echo $decommissioned['Decommissioned']['MACaddress']?></td>
         <td><?php echo $this->Time->niceShort($decommissioned['Decommissioned']['LastUpdated']);?></td>
            <td></td>
            <td></td>
         
            
       </tr>
 </table> 
 

<table>
    <tr>
        <th style="width: 200px;">Wiped Hard Drive</th>
        <th style="width: 250px;">Recycled</th>
 		<th style="width: 250px;">Redeployed As</th>
 		<th style="width: 500px;">Notes</th>

     
    </tr>
	<tr>
        <td><?php echo $decommissioned['Decommissioned']['WipedHD']?></td>
        <td><?php echo $decommissioned['Decommissioned']['Recycled']?></td>
        <td><?php echo $decommissioned['Decommissioned']['RedeployedAs'];?></td>
        <td><?php echo $decommissioned['Decommissioned']['notes'];?></td>    
    </tr>
 </table> 
 