<?php

  require_once '../_class/Core/Container.php';
  require_once '../_class/Subjects/SubjectManagement/SubjectManagementController.php';
  require_once '../_class/Subjects/SubjectsModel.php';
  require_once '../_class/Subjects/SubjectsRepository.php';
  require_once '../_class/User/UserRepository.php';

  $data = (OBJECT) $_POST['data'];

  $container = new Core\Container();

  $subjectmanagementController = $container->make("subjectmanagementController");

  $subject = $subjectmanagementController->fetchSubject($data->id);

  $obj = new stdClass;

  if ($subject) {
      $obj->subject = $subject;
      $obj->success = true;
  } else {
      $obj->success = false;
  }

  $rtn = json_encode($obj);
  echo $rtn;
