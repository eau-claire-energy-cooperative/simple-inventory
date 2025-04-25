<div class="alert alert-success mt-2" role="alert">
  <?php if(array_key_exists('escape', $params) && !$params['escape']): ?>
    <?= $message ?>
  <?php else: ?>
    <?php echo h($message) ?>
  <?php endif; ?>
</div>
