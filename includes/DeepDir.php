<?php

// corrected for 'include_nested' flag

class DeepDir{

  var $dir;
  var $files;

  function DeepDir( $params = array() ){
    $this->files = array();
    $this->dirFILO = new FILO();
    
    if ( isset( $params['dir'] ) ){
      $this->setDir( $params['dir'] );
    }
    else{
      $this->dir = '';
    }
    $this->includeNested = $params['include_nested'];
  }

  function setDir( $dir ){
    $this->dir = $dir;
    $this->files = array();
    $this->dirFILO->zero();
    $this->dirFILO->push( $this->dir );
  }


  function load(){
    while( $this->curDir = $this->dirFILO->pop() ){
      $this->loadFromCurDir();
      if ( ! $this->includeNested ) break;
    }
  }

  function loadFromCurDir(){
    if ( $handle = @opendir( $this->curDir ) ){
      while ( false !== ( $file = readdir( $handle ) ) ){
        if ( $file == "." || $file == ".." ) continue;
        $filePath = $this->curDir . '/' . $file;
        $fileType = filetype( $filePath );
        if ( $fileType == 'dir' ){
          $this->dirFILO->push( $filePath );
          continue;
        }
        $this->files[] = $filePath;
      }
      closedir( $handle );
    }
    else{
      echo 'error open dir "'.$this->curDir.'"';
    }
  }

} // end class


//================================
// stack: First In Last Out
//
class FILO{

  var $elements;
  
  function FILO(){
    $this->zero();
  }

  function push( $elm ){
    array_push( $this->elements, $elm );
  }

  function pop(){
    return array_pop( $this->elements );
  }

  function zero(){
    $this->elements = array();
  }

} // end class FILO

?>