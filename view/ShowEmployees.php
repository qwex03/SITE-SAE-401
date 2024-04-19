<?php
// Check if 'role' cookie is set
if(isset($_COOKIE['role'])) {
    // Retrieve and decode cookie value
    $cookieValue = urldecode($_COOKIE['role']);
    // Split cookie value into parts
    $parts = explode('&', $cookieValue);
    // Check if there are at least two parts
    if(count($parts) >= 2) {
        // Decode the role from the first part
        $role = base64_decode($parts[0]);
        // Check if the role is not 'it'
        if($role != 'it') {
            // Redirect to home page if role is not 'it'
            header("Location: index.php?action=home");
            exit; // Stop further execution
        }
    }
} else {
    // Redirect to home page if 'role' cookie is not set
    header("Location: index.php?action=home");
    exit; // Stop further execution
}

// Include the header file
include("www/header.inc.php");

?>
<div class="container">
<h2 class="mt-4">All Employees</h2>
<table class="table mt-2">
      <thead class="bg-dark text-light">
        <tr>
          <th>Name</th>
          <th>email</th>
          <th>role</th>
          <th>stores</th>
        </tr>
      </thead>
      <tbody id="api-data">
        
      </tbody>
    </table>
</div>
    <script>
	$(document).ready(function(){
    // AJAX request to fetch data from the API endpoint
    $.ajax({
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/employees/',
        method: 'GET',
        dataType: 'json',
        success: function(data){
            // Call the displayData function to render the fetched data
            displayData(data);
        },
        error: function(xhr, status, error){
            // Log error to console and display error message in the HTML table
            console.log(error);
            $('#api-data').html('<tr><td colspan="4">Error fetching data from API</td></tr>');
        }
    });
	});

	// Function to display data in the HTML table
	function displayData(data) {
		// Clear any existing data in the HTML table
		$('#api-data').empty();
		
		// Iterate through each item in the data array and create table rows
		$.each(data, function(index, item){
			var row = '<tr>' +
						'<td>' + item.name + '</td>' +
						'<td>' + item.email + '</td>' +
						'<td>' + item.role + '</td>' +
						'<td>' + item.stores + '</td>' +
					'</tr>';
			// Append the table row to the HTML table
			$('#api-data').append(row);
		});
	}

  </script>
<?php
include("www/footer.inc.php")
?>
