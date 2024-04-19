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
        <h1>Modify stocks</h1>
        <form id="modifyStockForm">
            <div class="form-group">
                <label for="productId">Product:</label>
                <select class="form-control" id="productId" name="productId" required>
                    <!-- Options des produits seront chargées via AJAX -->
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Modify</button>
        </form>

        <div id="response" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Retrieve the store ID from the 'role' cookie
var storeId = getStoreIdFromCookie();

// Function to extract store ID from the 'role' cookie
function getStoreIdFromCookie() {
    // Retrieve the value of the 'role' cookie
    var cookieValue = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*;\s*)role\s*=\s*([^;]*).*$)|^.*$/, "$1"));

    // Check if the cookie value exists
    if (cookieValue) {
        // Split the cookie value into parts using '&' as the delimiter
        var parts = cookieValue.split('&');
        // Check if there are two parts (role and store ID)
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

// This code block executes when the document is ready
$(document).ready(function() {
    // AJAX request to fetch stock data for the current store
    $.ajax({
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Stocks',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Iterate through each stock item
            $.each(data, function(index, product) {
                // Check if the product belongs to the current store
                if (product.store_id == storeId) {
                    // Append the product as an option to the select dropdown
                    $('#productId').append('<option value="' + product.id + '">' + product.name + '</option>');
                }
            });
        },
        error: function() {
            // Display an error message if fetching products fails
            $('#response').html('Error loading products.');
        }
    });

    // This code block executes when the modify stock form is submitted
    $('#modifyStockForm').submit(function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Retrieve the selected product ID
        var productId = $("#productId").val();
        
        // Prepare form data
        var formData = {
            "quantity": $("#productId").val(), // Should this be 'quantity' instead of 'productId'?
        };

        // AJAX request to update stock quantity
        $.ajax({
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Stocks/'+productId+'/e8f1997c763/', 
            type: 'PUT',
            data: JSON.stringify(formData),
            success: function(response) {
                // Display success message
                $('#response').html("Success");
            },
            error: function(xhr, status, error) {
                // Display error message
                $('#response').html('Error');
            }
        });
    });
});

    </script>
<?php
include("www/footer.inc.php");
?>

