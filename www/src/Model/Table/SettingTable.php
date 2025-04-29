<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\ORM\Entity;
use Cake\Utility\Security;
use ArrayObject;

class SettingTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('settings');
  }

  public function beforeSave(EventInterface $event, Entity $entity, ArrayObject $options)
  {
    // decrypt the value, if encrypted
    if(Configure::read('Settings.encrypt') && !empty($entity->value))
    {
      $entity->value = Security::encrypt($entity->value, Configure::read('Settings.encrypt_key'));
    }

    return $entity;
  }
}
?>
