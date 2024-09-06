<?php
session_start();
require_once 'helpers.php';

if (!isLoggedIn()) {
   redirect('index.php');
} else if (isUserParent()) {
   redirect('parent_home_page.php');
}

if (isset($_POST['offer']) && $_POST['can_offer_without_conflict']) {
   $_SESSION['job_request_id'] = $_POST['offer'];
   redirect('add_offer.php');
}

require_once 'db_connection.php';
require_once 'job_request.php';


$job_requests = getPendingJobRequest($db);
$tutor_job_requests = getJobRequestByTutorId(currentUserId(), $db);
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>Job Request List</title>

   <link rel="stylesheet" href="../css/stylesheet.css">
</head>

<body>

   <?php require_once 'header.php' ?>

   <div class="request" id="req">
      <div class="back-button"><a href="tutor_home_page.php"><button type="submit">&lt;</button></a></div>
      <h2 id="heading">Job Request List</h2>
      <?php
      if (count($job_requests) === 0) {
         echo '<h1>There are no requests yet</h1>';
      } else
         foreach ($job_requests as $request) {
            $can_offer_without_conflict = true;
            echo '
            <div class="request-block">
            <div class="ID">
            <h4>' . $request['id'] . '</h4>
            </div>
            <div class="request-button">
                  <form action="view_job_request_tutor.php" method="post">
                     <input type="hidden" name="can_offer_without_conflict" value="' . $can_offer_without_conflict . '"/>
                     <button type="submit" name="offer" value="' . $request['id'] . '">Send<br>offer</button>
                  </form>
            </div>
         <div>
            <p class="request-p">
               Kid\'s Name: <span>' . $request['name'] . '</span>
               Kid\'s Age: <span> ' . $request['age'] . ' years old</span>
               Type Of Class: <span> ' . $request['type_of_class'] . '</span>
               Start Date - End Date: <span> ' . date('d/m/Y', strtotime($request['start_date'])) . ' - ' . date('d/m/Y', strtotime($request['end_date'])) . '</span>
               Start Time - End Time: <span> ' . date('h:m a', strtotime($request['start_time'])) . ' - ' . date('h:m a', strtotime($request['end_time'])) . '</span>
            </p>
         </div>

      </div>';
         }
      ?>
   </div>

   <footer class="navbar">
      <p> &copy; 2023 Learn online tutoring platform <br>
         <a href="mailto:LearnInfo.sa@gmail.com" style=" color: #8c7569 ;">Contact Us
            <img src="../images/email_icon.png" alt="Contact Us"></a>
      </p>
   </footer>

   <script src="../js/index.js"></script>
</body>

</html>