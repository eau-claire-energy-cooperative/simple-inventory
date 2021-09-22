<?php
/***************************************************
* FileUpload Component
*
* Manages uploaded files to be saved to the file system.
*
* @copyright    Copyright 2009, Webtechnick
* @link         http://www.webtechnick.com
* @author       Nick Baker
* @version      1.4
* @license      MIT
*/
class FileUploadComponent extends Component{

  /***************************************************
    * uploadDir is the directory name in the webroot that you want
    * the uploaded files saved to.  default: files which means
    * webroot/files must exist and set to chmod 777
    *
    * @var string
    * @access public
    */
  var $uploadDir = 'drivers';

  /***************************************************
    * fileVar is the name of the key to look in for an uploaded file
    * For this to work you will need to use the
    * $form-input('file', array('type'=>'file));
    *
    * If you are NOT using a model the input must be just the name of the fileVar
    * input type='file' name='file'
    *
    * @var string
    * @access public
    */
  var $fileVar = 'local_file';

  /***************************************************
    * allowedTypes is the allowed types of files that will be saved
    * to the filesystem.  You can change it at anytime without
    * $this->FileUpload->allowedTypes = array('text/plain',etc...);
    *
    * @var array
    * @access public
    */
  var $allowedTypes = array(
    'application/zip',
	'application/x-zip',
	'application/octet-stream',
	'application/x-zip-compressed'
  );


  /***************************************************
    * This will be true if an upload is detected even
    * if it can't be processed due to misconfiguration
    *
    * @var boolean
    * @access public
    */
  var $uploadDetected = false;

  /***************************************************
    * This will hold the uploadedFile array if there is one
    *
    * @var boolean|array
    * @access public
    */
  var $uploadedFile = false;


  var $ext = 'zip';

  /***************************************************
    * data and params are the controller data and params
    *
    * @var array
    * @access public
    */
  var $data = array();
  var $params = array();

  /***************************************************
    * Final file is set on move_uploadedFile success.
    * This is the file name of the final file that was uploaded
    * to the uploadDir directory.
    *
    * @var string
    * @access public
    */
  var $finalFile = null;

  /***************************************************
    * errors holds any errors that occur as string values.
    * this can be access to debug the FileUploadComponent
    *
    * @var array
    * @access public
    */
  var $errors = array();

  /***************************************************
    * Initializes FileUploadComponent for use in the controller
    *
    * @param object $controller A reference to the instantiating controller object
    * @return void
    * @access public
    */
  function initialize(Controller $controller){
    $this->data = $controller->data;
    $this->params = $controller->params;
  }

  /*
    attempts to upload the file and return true/false
  */
  function upload(){
    $result = false;

    $this->uploadDetected = ($this->_multiArrayKeyExists("tmp_name", $this->data) || $this->_multiArrayKeyExists("tmp_name",$this->data));
    $this->uploadedFile = $this->_uploadedFileArray();

    if($this->_checkFile() && $this->_checkType()){
      $result = $this->_processFile();
    }

    return $result;
  }

  /*************************************************
    * removeFile removes a specific file from the uploaded directory
    *
    * @param string $name A reference to the filename to delete from the uploadDirectory
    * @return boolean
    * @access public
    */
  function removeFile($name = null){
    if(!$name) return false;

    $up_dir = WWW_ROOT . $this->uploadDir;
    $target_path = $up_dir . DS . $name;
    if(unlink($target_path)) return true;
    else return false;
  }

  /*************************************************
    * showErrors itterates through the errors array
    * and returns a concatinated string of errors sepearated by
    * the $sep
    *
    * @param string $sep A seperated defaults to <br />
    * @return string
    * @access public
    */
  function showErrors($sep = "<br />"){
    $retval = "";
    foreach($this->errors as $error){
      $retval .= "$error $sep";
    }
    return $retval;
  }


  /**************************************************
    * _processFile takes the detected uploaded file and saves it to the
    * uploadDir specified, it then sets success to true or false depending
    * on the save success of the model (if there is a model).  If there is no model
    * success is meassured on the success of the file being saved to the uploadDir
    *
    * finalFile is also set upon success of an uploaded file to the uploadDir
    *
    * @return void
    * @access private
    */
  function _processFile(){
    $result = false;
	  App::import('Sanitize');

    $up_dir = WWW_ROOT . $this->uploadDir;
    $target_path = $up_dir . DS . $this->data['File']['model'] . '.' . $this->ext;
    $temp_path = substr($target_path, 0, strlen($target_path) - strlen($this->_ext())); //temp path without the ext

    //make sure the file doesn't already exist, if it does, delete the old version

        if(file_exists($target_path)){
            $this->removeFile($this->data['File']['model'] . '.' . $this->ext);
        }

    $save_data = array();
    if(move_uploaded_file($this->uploadedFile['tmp_name'], $target_path)){
      //Final File Name
      $this->finalFile = basename($target_path);

      $result = true;

    }
    else{
      $this->_error('FileUpload::processFile() - Unable to save temp file to file system.');
    }

    return $result;
  }

  /***************************************************
    * Adds error messages to the component
    *
    * @param string $text String of error message to save
    * @return void
    * @access protected
    */
  function _error($text){
    $message = __($text,true);
    $this->errors[] = $message;
    trigger_error($message,E_USER_WARNING);
  }

  /***************************************************
    * Checks if the uploaded type is allowed defined in the allowedTypes
    *
    * @return boolean if type is accepted
    * @access protected
    */
  function _checkType(){
    foreach($this->allowedTypes as $value){
      if(strtolower($this->uploadedFile['type']) == strtolower($value)){
        return true;
      }
    }
    $this->_error("FileUpload::_checkType() {$this->uploadedFile['type']} is not in the allowedTypes array.");
    return false;
  }

  /***************************************************
    * Checks if there is a file uploaded
    *
    * @return void
    * @access protected
    */
  function _checkFile(){
    if($this->uploadedFile && $this->uploadedFile['error'] == UPLOAD_ERR_OK ) return true;
    else return false;
  }

  /***************************************************
    * Returns the extension of the uploaded filename.
    *
    * @return string $extension A filename extension
    * @access protected
    */
  function _ext(){
    return strrchr($this->uploadedFile['name'],".");
  }

  /***************************************************
    * Returns an array of the uploaded file or false if there is not a file
    *
    * @param string $text String of error message to save
    * @return array|boolean Array of uploaded file, or false if no file uploaded
    * @access protected
    */
  function _uploadedFileArray(){

    $retval = isset($this->data['File'][$this->fileVar]) ? $this->data['File'][$this->fileVar] : false;


    if($this->uploadDetected && $retval === false){
      $this->_error("FileUpload: A file was detected, but was unable to be processed due to a misconfiguration of FileUpload. Current config -- fileModel:'{$this->fileModel}' fileVar:'{$this->fileVar}'");
    }
    return $retval;
  }

  /***************************************************
    * Searches through the $haystack for a $key.
    *
    * @param string $needle String of key to search for in $haystack
    * @param array $haystack Array of which to search for $needle
    * @return boolean true if given key is in an array
    * @access protected
    */
  function _multiArrayKeyExists($needle, $haystack) {
    if(is_array($haystack)){
      foreach ($haystack as $key=>$value) {
        if ($needle==$key) {
          return true;
        }
        if (is_array($value)) {
          if($this->_multiArrayKeyExists($needle, $value)){
            return true;
          }
        }
      }
    }
    return false;
  }
}
?>
