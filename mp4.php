<?php

if (!defined('ROOT_PAGE')) { die('not allowed'); }

$claim = LBRY::findTopPublicFreeClaim($name);

if ($claim)
{
  $getResult = LBRY::api('get', ['name' => $name, 'claim_id' => $claim['claim_id']]);

  if (isset($getResult['completed']) && $getResult['completed'] && isset($getResult['download_path']))
  {
    $path = $getResult['download_path'];
//    $validType = isset($getResult['content_type']) && in_array($getResult['content_type'], ['image/jpeg', 'image/png']);
    
 
    header('Content-type: video/mp4');
    header('Content-length: ' . filesize($path));
    readfile($getResult['download_path']);
  }
  elseif (isset($getResult['written_bytes']))
  {
    echo 'This video is on it\'s way...<br/>';
    echo 'Received: ' . $getResult['written_bytes'] . " / " . $getResult['total_bytes'] . ' bytes';
  }
  else
  {
    echo 'There seems to be a valid claim, but are having trouble retrieving the content.';
  }
}
elseif (isset($_GET['new']) && $_GET['new'])
{
  echo 'Your video is on the way. It can take a few minutes to reach the blockchain and be public. You can refresh this page to check the progress.';
}
else
{
  echo 'No valid claim for this name. Make one!';
  include './publish.php';
}

exit(0);
