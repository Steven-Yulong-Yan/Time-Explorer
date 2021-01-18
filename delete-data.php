
<?php 
session_start();
session_unset();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(),'',0,'/');
session_regenerate_id(true);

// Remove cache files
$filesToDelete = glob('cache/*');
foreach($filesToDelete as $fileToDelete){
  if(is_file($fileToDelete))
    unlink($fileToDelete);
}

// Redirect back to home
header("Location: index.php?time=".date_timestamp_get($date));

?>
