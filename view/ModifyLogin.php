<?php
// Check if the 'role' cookie is set
if(isset($_COOKIE['role'])) {
    // Decode the cookie value
    $cookieValue = urldecode($_COOKIE['role']);
    // Split the cookie value into parts using '&' as the delimiter
    $parts = explode('&', $cookieValue);
    // Check if there are at least 2 parts
    if(count($parts) >= 2) {
        // Decode the first part (role)
        $role = base64_decode($parts[0]);
        // Check if the decoded role is not one of 'employee', 'chief', or 'it'
        if($role != 'employee' && $role != 'chief' && $role != 'it') {
            // Redirect to the home page and exit
            header("Location: index.php?action=home");
            exit;
        }
    }
} else {
    // If the 'role' cookie is not set, redirect to the home page and exit
    header("Location: index.php?action=home");
    exit;
}

// Include the header file
include("www/header.inc.php");

?>

<div class="container mt-5">
    <h2>Modify Login</h2>
    <form id="modifyLoginForm">
        <div class="form-group">
            <label for="employee_email">Email:</label>
            <input type="email" class="form-control" id="employee_email" placeholder="Enter new email" required>
        </div>
        <div class="form-group">
            <label for="employee_password">Password :</label>
            <input type="password" class="form-control" id="employee_password" placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Modify</button>
    </form>
</div>

<?php
include("www/footer.inc.php");
?>

<script>
    // This code block executes when the document is ready
$(document).ready(function () {
    // This function is triggered when the form with id 'modifyLoginForm' is submitted
    $('#modifyLoginForm').submit(function (event) {
        // Prevent the default form submission behavior
        event.preventDefault();
        
        // Retrieve the value of the 'id' cookie and decode it
        var encodedCookie = document.cookie.replace(/(?:(?:^|.*;\s*)id\s*\=\s*([^;]*).*$)|^.*$/, "$1");
        var decodedCookie;
        if (encodedCookie) {
            decodedCookie = atob(encodedCookie);
            console.log("Decoded value of id cookie:", decodedCookie);
        } else {
            console.log("The id cookie does not exist or is empty.");
        }
        
        // Extract id from the decoded cookie
        var id = decodedCookie;
        
        // Retrieve email and password values from form fields
        var email = $('#employee_email').val();
        var password = $('#employee_password').val();

        // AJAX request to update employee login information
        $.ajax({
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Login/'+id+'/e8f1997c763/', 
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify({ id: id, employee_email: email, employee_password: password }),
            contentType: 'application/json',
            success: function (response) {
                // Log the response from the server
                console.log(response);
            },
            error: function (xhr, status, error) {
                // Log any error that occurs during the AJAX request
                console.error(xhr.responseText);
            }
        });
    });
});

</script>