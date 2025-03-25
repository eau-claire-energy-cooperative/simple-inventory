<?php $isDue = $this->Lifecycle->isDue($application['lifecycle']['last_check']->i18nFormat("yyyy-MM-dd HH:mm:ss"), $application['lifecycle']['update_frequency']) ?>
<div class="container">
  <h1 class="h3 mb-2 text-gray-800"><?= $application['full_name'] ?></h1>
  <div class="row mt-3">
    <div class="col-md-8">
      <?= $this->Form->create($application, ['url'=>'/applications/upgrade_application']);?>
      <?= $this->Form->hidden('id', ['value'=>$application['lifecycle']['id']]) ?>
      <div class="container mb-2">
      <div class="row">
        <div class="col-md-3"><p class="mt-2"><b>Version: </b></p></div>
        <div class="col-md-5">
          <?= $this->Form->input('version', ['class'=>'form-control', 'value'=>$application['version']]); ?>
        </div>
        <div class="col-md-4"><?= $this->Form->Submit('Mark Upgraded', ['class'=>'btn btn-success btn-block']) ?></div>
      </div>
      <div class="row">
        <div class="col-md-12"><i>Updating the version number will update the application entry. If an existing application with this version already exists, the update will fail. <?= $this->Html->link('Edit this lifecycle', '/applications/edit_lifecycle/' . $application['lifecycle']['id']) ?> to assign the new version if that is the case.</i></div>
      </div>
      </div>
      <?= $this->Form->end() ?>
      <h3>Notes: </h3>
      <?= $this->Markdown->transform($application['lifecycle']['notes']); ?>
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
                Last Check: <?= $this->Time->timeAgoInWords($application['lifecycle']['last_check'], array('format'=>'M/d/Y')) ?><br />
                Next Check: <?= $this->Time->format($this->Lifecycle->getNextDate($application['lifecycle']['last_check']->i18nFormat("yyyy-MM-dd HH:mm:ss"), $application['lifecycle']['update_frequency']), 'M/d/Y') ?><br />
                Check Frequency: <?= $application['lifecycle']['update_frequency'] ?></p>
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
                <p><?= $this->Html->link("Total Versions",'/applications?q=' . $application['name']) ?>: <?= $total_versions ?>
                <?php if($highest_version != $application['version']): ?> <span class="badge badge-warning">Newer Available</span><?php endif ?><br />
                Install Counts:
                <ul>
                  <li>Older Versions: <?= $older_installs ?></li>
                  <li>Current Version: <?= count($application['computer']) ?></li>
                  <?php if($highest_version != $application['version']): ?>
                  <li>Newer Version: <?= $newer_installs ?></li>
                  <?php endif ?>
                </ul>
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
