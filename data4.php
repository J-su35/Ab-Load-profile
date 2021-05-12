<?php
require_once 'includes/db.php';

if(isset($_POST['sub']) && isset($_POST['feeder']) && isset($_POST['catRadio']) && isset($_POST['startDate']) && isset($_POST['endDate'])) {
  $catRadio = $_POST['catRadio'];
  $substation = $_POST['sub'];
  $feeder = $_POST['feeder'];
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];

  $sqlQuery = "SELECT substation, feeder, subload, submvar, subvab, subvbc, subvca, subpf, time, date FROM LOAD_2021 WHERE substation = '$substation' AND feeder = '$feeder' AND DATE BETWEEN '$startDate' AND '$endDate'";

  $data = array();

  $stmt = sqlsrv_query($conn, $sqlQuery);
  if($stmt === false) {
      die (print_r(sqlsrv_errors(),true()));
  }

  while($row = sqlsrv_fetch_Array($stmt)) {
      $data[] = $row;
  }

  echo json_encode($data);

  sqlsrv_close($conn);
} else {
  echo "error";
}
?>
