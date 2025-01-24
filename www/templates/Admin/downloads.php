<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Inventory Updater Script</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
            	<p>The update_inventory.ps1 script actually pulls the information from the computer and sends it to the inventory site via a REST API. The updater should run at login for each computer. This can be most easily accomplished via a <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Updater-Script-via-Group-Policy">Group Policy login script</a> or just adding a call to the existing login script for users. The updater script needs to be somewhere on your network it can be called by all computers.</p>

				<p>At minimum the script takes 2 parameters; the URL of the inventory site and the API authentication key. The authentication key is set on the <?= $this->Html->link('settings page','/admin/settings') ?> and is "pass" by default (please change it!). There are other parameters to turn off various functions if you don't need them. An example of how to run it is below, see the script itself for more details.</p>

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
          <h6 class="m-0 font-weight-bold text-primary">Double Drivers</h6>
        </div>
        <div class="card-body">
          <div class="row">
          	<div class="col-md-12">
	          	<p>You can download the <a href="https://www.majorgeeks.com/files/details/double_driver.html">Double Drivers</a> program from the official source. It is a few years since the last release but it does work on Windows 10 systems. This program will backup all installed drivers on a system and then package them in a zip file that can be uploaded the Simple Inventory system via the Computer Info screens. This will flag the driver package per system model.</p>

	          	<p>If you upload the file to the <b>/app/webroot/drivers</b> directory as <b>double_drivers.zip</b> it will automatically generate a download link below.</p>
	          	<?php if(file_exists(WWW_ROOT . '/drivers/double_drivers.zip')): ?>
	                <p><?= $this->Html->link('Download Double Drivers', '/drivers/double_drivers.zip',  ['class'=>'btn btn-primary']); ?></p>
	            <?php endif; ?>
	        </div>
          </div>
      </div>
    </div>
  </div>
</div>
