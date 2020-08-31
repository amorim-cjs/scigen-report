<?php

if (isset($_POST['user_id'])){
  
  $this->view->render(users/publicProfile);
}
elseif ($_SESSION['loggedin']){
  $this->view->user = $this->model->getUser($_SESSION['username']);
  $this->view->render(users/profile);
}
else{
  return 404;
}

?>
