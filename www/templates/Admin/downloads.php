<script type="text/javascript">
function generateDriverScript(){
  var folderPath = $('#local_folder_path').val();
  var zipPath = $('#local_zip_path').val();

  var scriptText = `# replace these paths where you want the drivers saved
$driverPath = "${folderPath}"
$zipPath = "${zipPath}"


# create folder and generate driver zip file
New-Item -ItemType Directory -Force -Path $driverPath
dism /online /export-driver /destination:"$driverPath"
Compress-Archive -Path "$driverPath" -DestinationPath "$zipPath"
Remove-Item -Path "$driverPath" -Recurse -Force`;

  // download the file
  let blob = new Blob([scriptText], { type: 'text/plain;charset=utf-8;' });
  let link = document.createElement('a');
  if (link.download !== undefined) { // Feature detection
      link.href = URL.createObjectURL(blob);
      link.download = "generate_drivers.ps1";
      link.click();
  }
}
</script>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Inventory Updater Script</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
            	<p>The update_inventory.ps1 script can pull the information from a Windows computer and send it to the inventory site via a REST API. The updater should run at login for each computer. This can be most easily accomplished via a <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Updater-Script-via-Group-Policy">Group Policy login script</a> or just adding a call to the existing login script for users. The updater script needs to be somewhere on your network it can be called by all computers.</p>
				      <p>At minimum the script takes 2 parameters; the URL of the inventory site and the API authentication key. The authentication key is set on the <?= $this->Html->link('settings page','/admin/settings') ?> and is "change_me" by default (please change it!). There are other parameters to turn off various functions if you don't need them. An example of how to run it is below, see the script itself for more details.</p>

      				<code>
      					inventory_updater.ps1 -Url http://localhost/inventory -ApiAuthKey <?= $settings['api_auth_key'] ?>
      				</code>

       			  <p class="mt-3"><?= $this->Html->link('Download Inventory Updater', '/files/inventory_updater.ps1',  ['class'=>'btn btn-primary','target'=>'_blank']); ?></p>

           	</div>
          </div>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Driver Uploads</h6>
        </div>
        <div class="card-body">
          <div class="row">
          	<div class="col-md-12">
              <p>Drivers for specific device models can be uploaded for safekeeping. Driver packages can be generated natively in Windows with a few PowerShell commands. Fill in the fields below to create a script that can be run on the local machine. Keep in mind this must be done as an <b>administrator</b> on the local system.</p>
              <p>Uploaded files are named as the device ID and model of the device. If no model name exists the device name is used instead. An example would be <code>2.hp_probook_430_g6.zip</code>. Moving a file directly to the <code>/drivers/</code> folder will also work.</p>
              <div class="row">
                <div class="col-md-4">Folder path to save driver files: </div>
                <div class="col-md-4"><input id="local_folder_path" class="form-control" value="C:\drivers" /></div>
              </div>
              <div class="row mt-2">
                <div class="col-md-4">Path to save driver zip file: </div>
                <div class="col-md-4"><input id="local_zip_path" class="form-control" value="C:\drivers.zip" /></div>
              </div>

              <p class="mt-3"></p>
	           </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <button class="btn btn-primary" onClick="generateDriverScript()">Generate Script</button>
            </div>
            <div class="col-md-6" align="right">
              <?= $this->Html->link('Manage Driver Files', '/admin/manage_drivers', ['class'=>'btn btn-secondary']) ?>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
