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


<div class="container">
    <h1 class="mt-5">Update stores informations</h1>
    <form id="modifyStoreForm" class="mt-4">
        <div class="form-group">
            <label for="storeName">Store name:</label>
            <input type="text" class="form-control" id="storeName" name="storeName">
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="street">Street:</label>
            <input type="text" class="form-control" id="street" name="street">
        </div>
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" id="city" name="city">
        </div>
        <div class="form-group">
            <label for="state">State:</label>
            <input type="text" class="form-control" id="state" name="state">
        </div>
        <div class="form-group">
            <label for="zipCode">Postal Code:</label>
            <input type="text" class="form-control" id="zipCode" name="zipCode">
        </div>
        <button type="button" class="btn btn-primary mt-3" onclick="modifyStore()">Modify store</button>
    </form>
    <div id="responseMessage" class="mt-3"></div>
</div>

<?php include("www/footer.inc.php"); ?>

<script>
   // This code block executes when the document is ready
$(document).ready(function() {
    // Retrieve the store ID from the 'role' cookie
    var storeId = getStoreIdFromCookie();
    // If store ID exists, send an AJAX request to fetch store information
    if (storeId) {
        $.ajax({
            url: "https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Store/" + storeId,
            type: "GET",
            success: function(data) {
                // Populate form fields with fetched store information
                $("#storeName").val(data.name);
                $("#phone").val(data.phone);
                $("#email").val(data.email);
                $("#street").val(data.street);
                $("#city").val(data.city);
                $("#state").val(data.state);
                $("#zipCode").val(data.zip_code);
            },
            error: function(xhr, status, error) {
                // Log error message if fetching store information fails
                console.error('Error fetching store information:', error);
            }
        });
    } else {
        // Log an error if unable to retrieve store ID from the cookie
        console.error("Unable to retrieve the store ID from the cookie.");
    }
});

// Function to extract store ID from the 'role' cookie
function getStoreIdFromCookie() {
    var cookieValue = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*;\s*)role\s*=\s*([^;]*).*$)|^.*$/, "$1"));
    if (cookieValue) {
        var parts = cookieValue.split('&');
        if (parts.length === 2) {
            // Decode and return the store ID
            var storeId = atob(parts[1]); 
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

// Function to modify store information
function modifyStore() {
    // Retrieve store ID from the cookie
    var storeId = getStoreIdFromCookie();
    // Construct store data object from form fields
    var storeData = {
        "store_id": getStoreIdFromCookie(), // Use the retrieved store ID
        "store_name": $("#storeName").val(),
        "phone": $("#phone").val(),
        "email": $("#email").val(),
        "street": $("#street").val(),
        "city": $("#city").val(),
        "state": $("#state").val(),
        "zip_code": $("#zipCode").val()
    };
    // Send an AJAX request to update store information
    $.ajax({
        url: "https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Stores/"+storeId+"/e8f1997c763/",
        type: "PUT",
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(storeData),
        success: function(response) {
            // Display success message if store information is updated successfully
            $('#responseMessage').html('<div class="alert alert-success" role="alert">The data has been modified successfully</div>');
        },
        error: function(xhr, status, error) {
            // Display error message if updating store information fails
            $('#responseMessage').html('<div class="alert alert-danger" role="alert">Error: ' + xhr.responseText + '</div>');
        }
    });
}

</script>
