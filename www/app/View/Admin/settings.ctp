<?php echo $this->Form->create('Setting',array('url'=>'/admin/settings')) ?>
<div align="right" class="mb-2">
	<?php echo $this->Form->Submit('Update',array('class'=>'d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2')) ?>
</div>

<?php if(!Configure::read('Settings.encrypt')): ?>
<div class="alert alert-warning">Settings values are currently <b>not</b> encrypted. Follow the instructions on the <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Encrypting-Settings" target="_blank">documentation wiki</a>. </div>
<?php endif; ?>
<?php
  //convert string to array
  $currentHomeAttributes = explode(',',$settings['home_attributes']);
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">General</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-6">Authentication Method</div>
                <div class="col-md-6"><?php echo $this->Form->select('auth_type',array('local'=>'Local Users','ldap'=>'LDAP Connection'),array('class'=>'custom-select','value'=>$settings['auth_type'],'empty'=>false)) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">Updater Script Auth Key</div>
                <div class="col-sm-6"><?php echo $this->Form->input('api_auth_key',array('class'=>'form-control','label'=>false,'value'=>$settings['api_auth_key'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">Computer Ignore List (comma separated)</div>
                <div class="col-sm-6"><?php echo $this->Form->input('computer_ignore_list',array('class'=>'form-control','label'=>false,'value'=>$settings['computer_ignore_list'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">Enable Device Auto Add</div>
                <div class="col-sm-6"><?php echo $this->Form->select('computer_auto_add',array('true'=>'Yes','false'=>'No'),array('class'=>'custom-select','value'=>$settings['computer_auto_add'],'empty'=>false)) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">Inventory List Fields To Display</div>
                <div class="col-sm-6"><?php echo $this->Form->select('home_attributes',$homeAttributes,array('class'=>'custom-select','multiple'=>true,'label'=>false,'value'=>$currentHomeAttributes)) ?></div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<?php
  //convert string to array
  $displayAttributes = explode(',',$settings['display_attributes']);
?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Device Detail Page</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-6">Show Computer Commands</div>
                <div class="col-md-6"><?php echo $this->Form->select('show_computer_commands',array('true'=>'Yes','false'=>'No'),array('class'=>'custom-select','value'=>$settings['show_computer_commands'],'empty'=>false)) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">DNS Search Domain</div>
                <div class="col-sm-6"><?php echo $this->Form->input('search_domain',array('class'=>'form-control','label'=>false,'value'=>$settings['search_domain'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">Shutdown Computer Message</div>
                <div class="col-sm-6"><?php echo $this->Form->input('shutdown_message',array('class'=>'form-control','label'=>false,'value'=>$settings['shutdown_message'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">Admin Account (Domain account works best)</div>
                <div class="col-sm-6"><?php echo $this->Form->input('domain_username',array('class'=>'form-control','label'=>false,'value'=>$settings['domain_username'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">Admin Password</div>
                <div class="col-sm-6"><?php echo $this->Form->password('domain_password',array('class'=>'form-control','label'=>false,'value'=>$settings['domain_password'])) ?></div>
              </div>
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
          <h6 class="m-0 font-weight-bold text-primary">LDAP Settings</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-6">LDAP Host</div>
                <div class="col-md-6"><?php echo $this->Form->input('ldap_host',array('class'=>'form-control','label'=>false,'value'=>$settings['ldap_host'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">LDAP Port</div>
                <div class="col-sm-6"><?php echo $this->Form->input('ldap_port',array('class'=>'form-control','label'=>false,'value'=>$settings['ldap_port'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">LDAP Authentication Search Base</div>
                <div class="col-sm-6"><?php echo $this->Form->input('ldap_basedn',array('class'=>'form-control','label'=>false,'value'=>$settings['ldap_basedn'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">LDAP Computers Search Base</div>
                <div class="col-sm-6"><?php echo $this->Form->input('ldap_computers_basedn',array('class'=>'form-control','label'=>false,'value'=>$settings['ldap_computers_basedn'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">LDAP Username</div>
                <div class="col-sm-6"><?php echo $this->Form->input('ldap_user',array('class'=>'form-control','label'=>false,'value'=>$settings['ldap_user'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">LDAP Password</div>
                <div class="col-sm-6"><?php echo $this->Form->password('ldap_password',array('class'=>'form-control','label'=>false,'value'=>$settings['ldap_password'])) ?></div>
              </div>
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
          <h6 class="m-0 font-weight-bold text-primary">Mail Settings</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-6">Send Mail As</div>
                <div class="col-md-6"><?php echo $this->Form->input('outgoing_email',array('class'=>'form-control','label'=>false,'value'=>$settings['outgoing_email'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">SMTP Host</div>
                <div class="col-sm-6"><?php echo $this->Form->input('smtp_server',array('class'=>'form-control','label'=>false,'value'=>$settings['smtp_server'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">SMTP Authentication</div>
                <div class="col-sm-6"><?php echo $this->Form->select('smtp_auth',array('true'=>'Yes','false'=>'No'),array('class'=>'form-control','value'=>$settings['smtp_auth'],'empty'=>false)) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">SMTP Username</div>
                <div class="col-sm-6"><?php echo $this->Form->input('smtp_user',array('class'=>'form-control','label'=>false,'value'=>$settings['smtp_user'])) ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6">SMTP Password</div>
                <div class="col-sm-6"><?php echo $this->Form->password('smtp_pass',array('class'=>'form-control','label'=>false,'value'=>$settings['smtp_pass'])) ?></div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<div align="right" class="mb-2">
  <?php echo $this->Form->Submit('Update',array('class'=>'d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2')) ?>
</div>
