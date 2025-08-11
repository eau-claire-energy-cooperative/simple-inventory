<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Original Values</h6>
        </div>
        <div class="card-body">
          <pre>
<?= json_encode($entry['orig_as_json'], JSON_PRETTY_PRINT) ?>
          </pre>
        </div>
      </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-success shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Updated Values</h6>
        </div>
        <div class="card-body">
          <pre>
<?= json_encode($entry['updated_as_json'], JSON_PRETTY_PRINT) ?>
          </pre>
        </div>
      </div>
  </div>
</div>
