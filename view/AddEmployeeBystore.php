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
      // If the role is not 'chief', redirect to the home page
      if($role != 'chief') {
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
    <input type="hidden" id="store_id" name="store_id">
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
    // Initialize variable to store the retrieved store ID
    var storeId = null;
    
    // Function to retrieve store ID from the 'role' cookie
    function getStoreIdFromCookie() {
      // Decode the 'role' cookie value
      var cookieValue = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*;\s*)role\s*=\s*([^;]*).*$)|^.*$/, "$1"));
      
      // Check if the cookie value exists
      if (cookieValue) {
        // Split the cookie value into parts
        var parts = cookieValue.split('&');
        
        // Check if the cookie value has exactly two parts
        if (parts.length === 2) {
          // Decode the store ID from the second part
          var storeId = atob(parts[1]);
          return storeId;
        } else {
          // Log an error if the 'role' cookie is not in the expected format
          console.error("The 'role' cookie is not in the expected format.");
          return null;
        }
      } else {
        // Log an error if the 'role' cookie does not exist
        console.error("The 'role' cookie does not exist.");
        return null;
      }
    }
    
    // Call the function to retrieve store ID from the 'role' cookie
    var storeId = getStoreIdFromCookie();
    
    // Set the value of the store ID input field
    $('#store_id').val(storeId);

    // Submit event handler for the add employee form
    $('#addEmployeeForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serializeArray();
      var jsonData = {};
      
      // Convert form data to JSON format
      $(formData).each(function(index, obj) {
        jsonData[obj.name] = obj.value;
      });
      
      // AJAX request to add an employee to the specified store
      $.ajax({
        type: 'POST',
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/EmployeeBystore/'+storeId+'/e8f1997c763/',
        contentType: 'application/json',
        data: JSON.stringify(jsonData),
        success: function(response) {
          console.log("Test successful");
          // Display success message and reset the form
          $('#responseMessage').html('<div class="alert alert-success" role="alert">Employee added successfully!</div>');
          $('#addEmployeeForm')[0].reset();
        },
        error: function(xhr, status, error) {
          console.log(error);
          console.log(JSON.stringify(jsonData));
          // Display error message
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
