<?php

require 'FileSessionData.php';

// instantiate the file session reader
$FileSession = FileSessionData::singleton();
// get all saved session ids
$sess_ids = $FileSession->getIds();

echo "Session ids: ";
var_dump($sess_ids);


// loop through each session id on the server and show session data
foreach($sess_ids as $id) 
{
  // get the session data
  if ($data = $FileSession->get($id))
  {
    // dump session array
    var_dump($data);
    // dump serialized session
    var_dump(FileSessionData::encode($data));
    exit; // lets limit to just one for testing
  }
}
