<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?=
    //script to load the confirmation dialog
    $this->Html->scriptBlock("$(document).ready(function() {
      $('a.delete-command').confirm({
          title: 'Delete Schedule',
          content: 'Are you sure you want to delete this task?',
          buttons: {
              yes: function(){
                  location.href = this.\$target.attr('href');
              },
              cancel: function(){

              }
          }
      })
     });", ["block"=>true])
?>

<div class="row">
  <div class="col-xl-6">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Current Scheduled Tasks</h6>
      </div>
      <div class="card-body">
        <?php foreach($all_schedules as $schedule): ?>
          <div class="p-3 mb-2 bg-light">
          <div class="row">
            <div class="col-md-8">
              <h1 class="h4 mb-1 text-gray-800"><?= $schedule['command']['name']; ?></h1>
            </div>
            <div class="col-md-4" align="right">
              <a href="<?= $this->Url->build('/manage/schedule/' . $schedule['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-command" data-title="Confirm delete">
                <i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <?php $schedule_params = json_decode($schedule['parameters'], true); ?>
              <ul>
                <li>Schedule: <?= $schedule['schedule'] ?></li>
                <?php foreach(array_keys($schedule_params) as $aKey): ?>
                  <li><?= $aKey . ": " . $schedule_params[$aKey] ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          </div>
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
            <div class="col-sm-8"><?= $command['name'] ?></div>
            <div class="col-sm-4">
              <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build('/ajax/setup_command/' . $command['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add</a>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</div>

<a name="cron"></a>
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
