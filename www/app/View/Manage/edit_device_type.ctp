<script type="text/javascript">
function updateIcon(){
  $('#device_icon_preview').attr('class', 'mdi mdi-' + $('#DeviceTypeIcon').val());
}
</script>
<?php echo $this->Form->create('DeviceType', array('url' => '/manage/editDeviceType')); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>

<?php
  //convert string to array
  $assignedAttributes = explode(',',$this->request->data['DeviceType']['attributes']);
?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <p>To find a device type icon you can search the <a href="https://materialdesignicons.com/" target="_blank">Matrial Design Icons</a> site.</p>
          <div class="row mb-2">
            <div class="col-md-4">Device Type Name: </div>
            <div class="col-md-8"><?php echo $this->Form->input('name',array('label'=>false, 'div'=>false, 'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Icon: </div>
            <div class="col-md-7"><?php echo $this->Form->input('icon',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'onkeyup'=>'updateIcon()')); ?></div>
            <div class="col-md-1"><i class="mdi mdi-<?php echo $this->request->data['DeviceType']['icon'] ?>" style="font-size:24px" id="device_icon_preview"></i></div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Attributes Allowed:</div>
            <div class="col-sm-8"><?php echo $this->Form->select('attributes',$allowedAttributes,array('class'=>'custom-select','multiple'=>true,'label'=>false,'value'=>$assignedAttributes)) ?><br />
            These are attributes that are allowed to be recorded for this type. This does not affect if they are displayed.
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Allow Decomissioning:</div>
            <div class="col-sm-8"><?php echo $this->Form->select('allow_decom',array('true'=>'Yes','false'=>'No'),array('class'=>'custom-select','empty'=>false)) ?><br />
            This controls if decomissioning is allowed for this device type.
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Check Running Status:</div>
            <div class="col-sm-8"><?php echo $this->Form->select('check_running',array('true'=>'Yes','false'=>'No'),array('class'=>'custom-select','empty'=>false)) ?><br />
            On the device status page this will attempt to ping the device and allow other controls if enabled in the <?php echo $this->Html->link('settings', array('action'=>'settings')) ?>.
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Exclude AD Compare</div>
            <div class="col-sm-8"><?php echo $this->Form->select('exclude_ad_sync',array('true'=>'Yes','false'=>'No'),array('class'=>'custom-select','empty'=>false)) ?><br />
            Will exclude this device type when doing Active Directory comparisons for missing devices.
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?php echo $this->Form->Submit('Update',array('class'=>'btn btn-primary btn-block')) ?></div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>
