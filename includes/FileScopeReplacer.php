<?

//=================================================================
//
// Replacer of content of scope of files in directories (nested)
//
// 2004, Ilya Nemihin, nemilya@mail.ru, http://nemilya.by.ru
//
// see usage example in script_test.php
//
// may be some one this will be useful :)
//
//=================================================================


require_once('DeepDir.php');

class FileScopeReplacer{

  var $file_list;
  var $processed_files;

  function FileScopeReplacer( $params ){
    // constructor
    //

    $this->objDir = new DeepDir( $params );
    $this->dir = $params['dir'];
    $this->search_what = $params['search_what'];
    $this->replace_to = $params['replace_to'];
    $this->file_name_match = $params['file_name_match'];
  }

  function doWork(){
    // just work
    //

    $this->processed_files = array();
    $this->_fillFileList();
    foreach( $this->file_list as $file_path ){
      if ( $this->_isFileNameMatch( $this->_getFileNameFromPath( $file_path ) ) ){
        $this->_makeReplacing( $file_path );
        $this->processed_files[] = $file_path;
      }
    }
  }

  function _fillFileList(){
    // used $this->dir
    // to fill $this->file_list
    // 
    $this->objDir->load();
    $this->file_list = $this->objDir->files;
  }

  function _getFileNameFromPath( $file_path ){
    // $file_path - f.e. 'dir/dir2/file.txt'
    // have to return 'file.txt'
    //

    $parts = explode( '/', $file_path );
    return $parts[count($parts)-1]; // taking last

    // ^^^ I agree this is not the best way )
  }

  function _isFileNameMatch( $file_name ){
    // check matching of $file_name to
    // $this->file_name_match
    //
    if ( $this->file_name_match == '') return true;
    return preg_match( $this->file_name_match, $file_name );
  }

  function _makeReplacing( $file_path ){
    // $file_path - is path to file
    // have to replace content of file
    // by
    // $this->search_what replace to
    // $this->replace_to
    //

    $this->_writeFile( 
      $file_path,  
      $this->_performReplacing( $this->_readFile( $file_path ) )
    );

  }

  function _readFile( $file_path ){
    // just reading file's content
    //

    $f = fopen( $file_path, 'r' );
    $content = fread( $f, 100000 );  // <-- yes, this mean 100000 bites limit, you have so big files?
    fclose($f);
    return $content;
  }

  function _writeFile( $file_path, $content ){
    // just writing file's content
    //

    $f = fopen( $file_path, 'w' );
    fputs( $f, $content );
    fclose( $f );
  }

  function _performReplacing( $content ){
    // this function is impement the logic of
    // replacing, in our case this is simplest replacing
    //

    return str_replace( $this->search_what, $this->replace_to, $content );
  }

}


?>