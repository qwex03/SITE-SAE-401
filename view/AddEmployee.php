<?php
// Check if the 'role' cookie is set
if(isset($_COOKIE['role'])) {
  // Decode the cookie value
  $cookieValue = urldecode($_COOKIE['role']);
  // Split the cookie value into parts
  $parts = explode('&', $cookieValue);
  // If there are at least two parts
  if(count($parts) >= 2) {
      // Decode the role from the first part
      $role = base64_decode($parts[0]);
      // If the role is not 'it', redirect to the home page
      if($role != 'it') {
          header("Location: index.php?action=home");
          exit;
      }
  }
} else {
  // If the 'role' cookie is not set, redirect to the home page
  header("Location: index.php?action=home");
  exit;
}

// Include the header file
include("www/header.inc.php");
?>

<div class="container mt-5">
  <h1>Add Employee</h1>
  <form id="addEmployeeForm">
    <div class="form-group">
      <label for="employee_name">Employee Name</label>
      <input type="text" class="form-control" id="employee_name" name="employee_name" required>
    </div>
    <div class="form-group">
      <label for="employee_email">Employee Email</label>
      <input type="email" class="form-control" id="employee_email" name="employee_email" required>
    </div>
    <div class="form-group">
      <label for="employee_password">Password</label>
      <input type="password" class="form-control" id="employee_password" name="employee_password" required>
    </div>
    <div class="form-group">
      <label for="store_id">Store</label>
      <select class="form-control" id="store_id" name="store_id" required>
      </select>
    </div>
    <div class="form-group">
      <label for="employee_role">Employee Role</label>
      <select class="form-control" id="employee_role" name="employee_role" required>
        <option value="chief">Chief</option>
        <option value="employee">Employee</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Add Employee</button>
  </form>
  <div id="responseMessage" class="mt-3"></div>
</div>
<script>
  $(document).ready(function() {
    // AJAX request to fetch store data
    $.ajax({
      type: 'GET',
      url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Stores/',
      success: function(response) {
        var stores = response;
        stores.forEach(function(store) {
          $('#store_id').append('<option value="' + store.id + '">' + store.name + '</option>');
        });
      },
      error: function(xhr, status, error) {
        console.error('Error fetching stores:', error);
      }
    })
    // Submit event handler for the add employee form
    $('#addEmployeeForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serializeArray();
      var jsonData = {};
      $(formData).each(function(index, obj){
          jsonData[obj.name] = obj.value;
      });
      // AJAX request to add employee
      $.ajax({
        type: 'POST',
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Employee/e8f1997c763',
        contentType: 'application/json',
        data: JSON.stringify(jsonData),
        success: function(response) {
            console.log("test réussi");
          $('#responseMessage').html('<div class="alert alert-success" role="alert">Employee added successfully!</div>');
          $('#addEmployeeForm')[0].reset();
        },
        error: function(xhr, status, error) {
            console.log(error);
          console.log(JSON.stringify(jsonData));
          $('#responseMessage').html('<div class="alert alert-danger" role="alert">Error: ' + xhr.responseText + '</div>');
        }
      });
    });
  });
</script>


<?php
// Include the footer file
include("www/footer.inc.php");
?>
