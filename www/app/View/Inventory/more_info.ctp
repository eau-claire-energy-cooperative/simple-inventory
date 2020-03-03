<?php 
		echo $this->Html->script("fancybox/jquery.mousewheel-3.0.6.pack.js",false);
		echo $this->Html->script("fancybox/jquery.fancybox.js",false);
		echo $this->Html->css('jquery.fancybox.css');
?>

<script type="text/javascript">
    $(document).ready(function(){
    	checkRunning();
		setInterval(checkRunning,40 * 1000);
		
		$(".popup").fancybox({
		maxWidth	: 600,
		maxHeight	: 400,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
		});
	});

	function checkRunning(){
		$.getJSON('<?php echo $this->webroot ?>ajax/checkRunning/<?php echo $computer['Computer']['ComputerName'] ?>',function(data){
			if(data.received == data.transmitted)
			{
				if(<?php echo $settings['show_computer_commands']?>)
				{
					$('#is_running').html('<a href="#" onClick="shutdown(\'<?php echo $computer['Computer']['ComputerName'] ?>\',false)">Shutdown</a> | <a href="#" onClick="shutdown(\'<?php echo $computer['Computer']['ComputerName'] ?>\',true)">Restart</a>');
				}
				else
				{
					$('#is_running').html('Running');
				}
				$('#is_running').removeClass('red');
			}
			else
			{
				if(<?php echo $settings['show_computer_commands']?>)
				{
					$('#is_running').html('<a href="#" onClick="wol(\'<?php echo $computer['Computer']['MACaddress'] ?>\')">Turn On</a>');
					$('#is_running').removeClass('red');
				}
				else
				{
					$('#is_running').html('Not Running');
					$('#is_running').addClass('red');
				}
			}
		});
	}

	function expandTable(id){
		
		$('#' + id + ' tr').each(function(index){
			if(index != 0)
			{
				$(this).toggle();
			}
		});
		
		return false;
	}
	
	function shutdown(host,shouldRestart){
		
		if(confirm('Shutdown or Restart this computer?'))
		{
			$.ajax('<?php echo $this->webroot ?>ajax/shutdown/' + host + '/' + shouldRestart);
		}
		return false;
	}
	
	function wol(mac){
		$.ajax('<?php echo $this->webroot ?>ajax/wol?mac=' + mac);
	}
	
</script>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Computer Specs</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <?php foreach($tables as $aTable): ?>
            <table class="table table-striped">
              <tr>
                <?php foreach($aTable as $attribute): ?>
                  <th style="width: 250px;"><?php echo $validAttributes[$attribute] ?></th>
                <?php endforeach; ?>
                <?php
                  $tableCount = count($aTable); 
                  while($tableCount < 5): ?>
                  <th style="width: 250px;"></th>
                  <?php $tableCount ++; ?>
                <?php endwhile; ?>
              </tr>
              <tr>
                <?php foreach($aTable as $attribute): ?>
                  <td><?php echo $this->AttributeDisplay->displayAttribute($attribute,$computer)?></td>
                <?php endforeach; ?>
                <?php
                  $tableCount = count($aTable); 
                  while($tableCount < 5): ?>
                  <td></td>
                  <?php $tableCount ++; ?>
                <?php endwhile; ?>
              </tr>
            </table>
            <?php endforeach; ?>
          </div>
        </div>
      </div>    
  </div>
</div>

<div class="row">
  <div class="col-xl-4 col-md-4 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-left">
          <div class="col mr-2">
            <div class="h5 font-weight-bold text-uppercase mb-1">Actions</div>
            <div class="p mb-0 font-weight-bold text-gray-800">
              <?php echo $this->Html->link('Edit', array('action' => 'edit', $computer['Computer']['id'])); ?><br>
              <?php echo $this->Html->link('Decommission', array('action' => 'confirmDecommission', $computer['Computer']['id'])); ?><br>
              <?php echo $this->Form->postLink(
                'Delete',
                array('action' => 'delete', $computer['Computer']['id']),
                array('confirm' => 'Are you sure?')); ?><br>
              <?php if(file_exists(WWW_ROOT . '/drivers/' . str_replace(' ','_',$computer['Computer']['Model']) . '.zip')): ?>
                <?php echo $this->Html->link("Download Drivers","/drivers/" . str_replace(' ','_',$computer['Computer']['Model']) . ".zip") ?>
              <?php else: ?>
                <?php echo $this->Html->link("Upload Drivers",'/ajax/uploadDrivers/' . $computer['Computer']['id'],array('class'=>'popup fancybox.ajax')) ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-folder fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-8 col-md-8 mb-4">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Licenses</h6>
      </div>
      <div class="card-body">
        <?php foreach($computer['License'] as $aLicense): ?>
        <div class="row">
          <div class="col-md-3"><?php echo $aLicense['ProgramName'] ?></div>
          <div class="col-md-8"><?php echo $aLicense['LicenseKey'] ?></div>
        </div>
        <?php endforeach ?>
      </div>
    </div>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?php if($computer['Computer']['notes'] != ''): ?>
          <?php echo $computer['Computer']['notes']?></td>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
 
<?php if(count($programs) > 0): ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><a href="#" onClick="$('#programs').toggle(); return false">Programs</a></h6>
      </div>
      <div class="card-body">
        <table id="programs" class="table table-striped" style="display:none">
          <?php foreach ($programs as $post): ?>
          <tr>
          <?php 
              $row_class = '';
  
              if(key_exists($post['Programs']['program'],$restricted_programs))
              {
                $row_class = 'restricted';
              }
          ?>
          <td class="<?php echo $row_class ?>"> <?php echo $this->Html->link( $post['Programs']['program'] . " v" . $post["Programs"]["version"], '/search/searchProgram/' . $post['Programs']['program']); ?></td>
        </tr>
        <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif;?>

 <?php if(count($services) > 0): ?>
 <div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><a href="#" onClick="$('#services').toggle(); return false">Services</a></h6>
      </div>
      <div class="card-body">
        <table id="services" class="table table-striped" style="display:none">
          <?php foreach ($services as $post): ?>
          <tr>
            <td><?php echo $this->Html->link( $post['Service']['name'] , '/search/searchService/' . $post['Service']['name']); ?></td>
            <td><?php echo $post['Service']['startmode'] ?></td>
            <td><?php echo $post['Service']['status'] ?></td>
        </tr>
        <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
 </div>
 <?php endif ?>
 
