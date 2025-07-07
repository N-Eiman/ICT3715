<?php include('../include/header.php'); 
include('../include/db_connect.php');
?>

    <div class="container">
      <h1>Welcome to Centurion High Locker Booking System</h1>
      <h2>Available Lockers</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Grade</th>
            <th scope="col">Quantity Available</th>
            
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>8</td>
            <td>8</td>

          </tr>
          <tr>
            <td>9</td>
            <td>10</td>
          </tr>
          <tr>
            <td>10</td>
            <td>12</td>
          </tr>
          <tr>
            <td>11</td>
            <td>12</td>
          </tr>
          <tr>
            <td>12</td>
            <td>6</td>
          </tr>
          <!-- Add more lockers as needed -->
        </tbody>
    </div>

<?php include('../include/footer.php'); ?>