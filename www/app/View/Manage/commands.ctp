<?php 
    echo $this->Html->script("jquery.fancybox.min.js",false);
    echo $this->Html->script("jquery-confirm.min.js",false);
    echo $this->Html->css('jquery.fancybox', array('inline'=>false));
    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));
    
    //script to load the confirmation dialog
    echo $this->Html->scriptBlock("$(document).ready(function() {
  
        $('a.delete-command').confirm({
          content: 'Are you sure you want to delete this task?',
          buttons: {
              yes: function(){
                  location.href = this.\$target.attr('href');
              },
              cancel: function(){
                
              }
          }
        });
     });", array("inline"=>false)) 
?>

<div class="row">
  <div class="col-xl-6">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Current Scheduled Tasks</h6>
      </div>
      <div class="card-body">
        <?php foreach($all_schedules as $schedule): ?>
          <h1 class="h4 mb-1 text-gray-800"><?php echo $schedule['Command']['name']; ?></h1>
            <?php eval("\$schedule_params = " . $schedule['Schedule']['parameters'] . ";"); ?>
        
            <ul>  
              <li>Schedule: <?php echo $schedule['Schedule']['schedule'] ?></li>  
              <?php foreach(array_keys($schedule_params) as $aKey): ?>
                <li><?php echo $aKey . ": " . $schedule_params[$aKey] ?></li>
              <?php endforeach; ?>  
            </ul>
            <p align="right" class="mr-2">
              <a href="<?php echo $this->Html->url('/manage/schedule/' . $schedule['Schedule']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-command"><i class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
            </p>
          <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="col-xl-6">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Available Commands</h6>
      </div>
      <div class="card-body">
        <?php foreach($all_commands as $command): ?>
          <div class="row mb-2">
            <div class="col-sm-8"><?php echo $command['Command']['name'] ?></div>
            <div class="col-sm-4">
              <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/setup_command/' . $command['Command']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-plus fa-sm text-white-50"></i> Add</a>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Cron Syntax</h6>
      </div>
      <div class="card-body">
        <p>A <a href="http://en.wikipedia.org/wiki/Cron ">Cron expression</a> is made up for 5 parts (see below).
        
        <p>.--------------- minute (0 - 59)<br>
        |   .------------ hour (0 - 23)<br>
            |   |   .--------- day of month (1 - 31)<br>
            |   |   |   .------ month (1 - 12) or Jan, Feb ... Dec<br>
            |   |   |   |  .---- day of week (0 - 6) or Sun(0 or 7), Mon(1) ... Sat(6)<br>
            V   V   V   V  V<br>
            *   *   *   *  *</p>
        <p>Example:</p>
        <ul>
          <li>0 */5 ** 1-5 - runs every five hours Monday - Friday</li>
          <li>0,15,30,45 0,15-18 * * * - runs every quarter hour during midnight hour and 3pm-6pm</li>
        </ul>
      </div>
    </div>
  </div>
</div>


