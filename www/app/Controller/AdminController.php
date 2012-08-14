<?php
	
class AdminController extends AppController {
	var $uses = array('Logs','Location');
	var $helpers = array('Html','Session','Time');
	var $paginate = array('limit'=>100, order=>array('Logs.id'=>'desc'));
	
	
	function index(){
		
	}
	
	public function logs()
	{
	 	$this->set('title_for_layout','Logs');
	 	$this->set('logs',$this->paginate('Logs'));
	 	//$this->set('logs', $this->Logs->find('all', array('order'=> array('id DESC'))));// gets all data
	}
	
	public function location() {
	 	$this->set('title_for_layout','Locations');
        $this->set('location', $this->Location->find('all', array('order'=> array('location ASC'))));// gets all data
    }
	
	public function editLocation($id= null) {
		$this->set('title_for_layout','Edit Location');
    	$this->Location->id = $id;
    	
    	if ($this->request->is('get')) {
        	$this->request->data = $this->Location->read();
   		} 
   		else 
   		{
        	if ($this->Location->save($this->request->data)) {
            	$this->Session->setFlash('Your entry has been updated.');
            	$this->redirect(array('action' => 'location'));
        	} 
        	else 
        	{
            	$this->Session->setFlash('Unable to update your entry.');
        	}
   		}
	}
	
	public function addLocation() {
		$this->set('title_for_layout','Add Location');
		
        if ($this->request->is('post')) {
            if ($this->Location->save($this->request->data)) {
                $this->Session->setFlash('Your Entry has been saved.');
                $this->redirect(array('action' => 'location'));
            } else {
                $this->Session->setFlash('Unable to add your Entry.');
            }
        }
    }
    
    public function deleteLocation($id) {
	    if ($this->request->is('get')) {
	        throw new MethodNotAllowedException();
	    }
	    if ($this->Location->delete($id)) {
	        $this->Session->setFlash('The entry with id: ' . $id . ' has been deleted.');
	        $this->redirect(array('action' => 'location'));
	    }
	}
}

?>