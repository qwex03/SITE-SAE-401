<?php
if(isset($_COOKIE['role'])) {
    $cookieValue = urldecode($_COOKIE['role']);
    $parts = explode('&', $cookieValue);
    if(count($parts) >= 2) {
        $role = base64_decode($parts[0]);
        if($role != 'chief') {
            header("Location: index.php?action=home");
            exit;
        }
    }
  } else {
    header("Location: index.php?action=home");
    exit;
  }
  
include("www/header.inc.php");
?>

<div class="container">
    <h2 class="mt-4">All Employees</h2>
    <table class="table mt-2">
        <thead class="bg-dark text-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Stores</th>
            </tr>
        </thead>
        <tbody id="api-data">
        </tbody>
    </table>
</div>

<script>
   $(document).ready(function(){
    // Function to extract store ID from the 'role' cookie
    function getStoreIdFromCookie() {
        var cookieValue = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*;\s*)role\s*=\s*([^;]*).*$)|^.*$/, "$1"));
        
        if (cookieValue) {
            var parts = cookieValue.split('&');
            if (parts.length === 2) {
                var storeId = atob(parts[1]); // Decode and extract store ID
                return storeId;
            } else {
                console.error("The 'role' cookie is not in the expected format.");
                return null;
            }
        } else {
            console.error("The 'role' cookie does not exist.");
            return null;
        }
    }
   
    // Retrieve store ID from the cookie
    var storeId = getStoreIdFromCookie();
    console.log(storeId);

    // Check if store ID exists
    if (storeId) {
        // AJAX request to fetch employee data based on store ID
        $.ajax({
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/employees/' + storeId,
            method: 'GET',
            dataType: 'json',
            success: function(data){
                // Call displayData function to render fetched data
                displayData(data);
            },
            error: function(xhr, status, error){
                // Log error to console and display error message in HTML table
                console.error(error);
                $('#api-data').html('<tr><td colspan="5">Error fetching data from API</td></tr>');
            }
        });
    } else {
        // Log error if store ID is not found in cookie and display error message in HTML table
        console.error('Store ID not found in cookie');
        $('#api-data').html('<tr><td colspan="5">Store ID not found in cookie</td></tr>');
    }

    // Function to display data in HTML table
    function displayData(data) {
        $('#api-data').empty();
        // Iterate through fetched data and create table rows
        $.each(data, function(index, item){
            var row = '<tr>' +
                          '<td>' + item.name + '</td>' +
                          '<td>' + item.email + '</td>' +
                          '<td>' + item.role + '</td>' +
                          '<td>' + item.stores + '</td>' +
                      '</tr>';
            // Append table rows to HTML table
            $('#api-data').append(row);
        });
    }
});

</script>

<?php
include("www/footer.inc.php");
?>
