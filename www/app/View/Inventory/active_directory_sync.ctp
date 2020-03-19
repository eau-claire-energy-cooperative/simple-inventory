
<p>Use these tools to find computers that may need to be added or decomissioned.</p>
<p class="mb-4">Searching AD Tree: <b><?php echo $baseDN ?></b></p>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Compare AD to Inventory</h6>
      </div>
      <div class="card-body">
        <p>Compare the Simple Inventory list to what is in Active Directory.</p>
        <table class="table table-striped">
          <?php 
            $keys = array_keys($compare_computers);
  
            foreach($keys as $aComputer)
            {
              
              echo "<tr>";
              echo "<td>" . $aComputer . "</td>";
              echo "<td>" . $compare_computers[$aComputer]['value'] . "</td>";
              echo "</tr>";
            }
          ?>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Find Old Computers</h6>
      </div>
      <div class="card-body">
        <p>Find computers that have not logged in to Active Directory in the given certain amount of days.</p>
        <?php if($currentAction == 'find_old'): ?>
          <?php echo $this->Form->input('days_old',array('type' => 'select','onchange'=>'updateDays()','options' => array('30'=>'30 Days','60'=>'60 days','90'=>'90 days','120'=>'120 days'),'selected'=>$days_old,'label'=>false,'style'=>'float:right', 'class'=>'mb-2')); ?>
        <?php endif; ?>
        <table class="table table-striped">
          <?php 
            $keys = array_keys($old_computers);
  
            foreach($keys as $aComputer)
            {
              
              echo "<tr>";
              echo "<td>" . $aComputer . "</td>";
              echo "<td>" . $old_computers[$aComputer]['value'] . "</td>";
              echo "</tr>";
            }
          ?>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function updateDays(){
	window.location.href = '<?php echo $this->webroot ?>inventory/active_directory_sync?days_old=' + $('#days_old').val();
}
</script>
