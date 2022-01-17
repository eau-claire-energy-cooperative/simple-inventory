<?php $isDue = $this->Lifecycle->isDue(date('Y-m-d', strtotime($application['Lifecycle']['last_check'])), $application['Lifecycle']['update_frequency']) ?>
<div class="container">
  <h1 class="h3 mb-2 text-gray-800"><?php echo $application['Applications']['full_name'] ?></h1>
  <div class="row mt-3">
    <div class="col-md-8">
      <?php echo $this->Form->create('Lifecycle', array('url'=>'/applications/upgrade_application'));?>
      <?php echo $this->Form->hidden('id', array('value'=>$application['Lifecycle']['id'])) ?>
      <div class="container mb-2">
      <div class="row">
        <div class="col-md-3"><p class="mt-2"><b>Version: </b></p></div>
        <div class="col-md-5">
          <?php echo $this->Form->input('version', array("label"=>false, 'div'=>false, 'class'=>'form-control', 'value'=>$application['Applications']['version'])); ?>
        </div>
        <div class="col-md-4"><?php echo $this->Form->Submit('Mark Upgraded',array('class'=>'btn btn-success btn-block')) ?></div>
      </div>
      <div class="row">
        <div class="col-md-12"><i>Updating the version number will update the application entry. If an existing application with this version already exists, the update will fail. <?php echo $this->Html->link('Edit this lifecycle', '/applications/edit_lifecycle/' . $application['Lifecycle']['id']) ?> to assign the new version if that is the case.</i></div>
      </div>
      </div>
      <?php echo $this->Form->end() ?>
      <h3>Notes: </h3>
      <?php echo $application['Lifecycle']['notes'] ?>
    </div>
    <div class="col-md-4">
      <div class="card border-left-primary shadow h-500 py-1 mb-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Lifecycle Info</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800">
                <p>Needs Check:
                <?php if($isDue): ?>
                <span class="text-danger">Yes</span>
                <?php else: ?>
                <span class="text-success">No</span>
                <?php endif; ?><br />
                Last Check: <?php echo $this->Time->timeAgoInWords($application['Lifecycle']['last_check'], array('format'=>'m/d/Y')) ?><br />
                Next Check: <?php echo $this->Time->format($this->Lifecycle->getNextDate(date('Y-m-d', strtotime($application['Lifecycle']['last_check'])), $application['Lifecycle']['update_frequency']), '%m/%d/%Y') ?><br />
                Check Frequency: <?php echo $application['Lifecycle']['update_frequency'] ?></p>
              </div>
            </div>
            <div class="col-auto">
              <i class="mdi mdi-update icon-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="card border-left-secondary shadow h-500 py-1">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Application Info</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800">
                <p>Total Versions: <?php echo $this->Html->link($total_versions,'/applications?q=' . $application['Applications']['name']) ?><br />
                Computers Assigned: <?php echo $this->Html->link(count($application['Computer']), '/search/searchApplication/' . $application['Applications']['id']) ?><br />
                Monitoring: <?php echo ($application['Applications']['monitoring'] == 'true') ? "Yes" : "No" ?></p>
              </div>
            </div>
            <div class="col-auto">
              <i class="mdi mdi-application-cog-outline icon-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
