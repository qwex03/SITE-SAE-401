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
        // If the role is not 'employee', 'chief', or 'it', redirect to the home page
        if($role != 'employee' && $role != 'chief' && $role != 'it') {
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
    <div class="form-group mb-4">
        <select id="formSelector" class="form-control">
            <option value="none">-- Select a Form --</option>
            <option value="Categorie">Category</option>
            <option value="Brand">Brand</option>
            <option value="Products">Product</option>
            <option value="Stocks">Stock</option>
            <option value="Stores">Stores</option>
        </select>
    </div>
    <div class="form-group mb-4" id="dynamicSelectContainer" style="display: none;">
        <select id="dynamicSelect" class="form-control">
            <option value="">-- Select an Option --</option>
        </select>
    </div>
    <div class="form-group mb-4" id="deleteButtonContainer" style="display: none;">
        <input type="submit" id="deleteButton" class="btn btn-danger" value="Delete">
    </div>
    <div id="responseMessage" class="mt-3"></div>
</div>

<script>
    // Function to retrieve store ID from the 'role' cookie
    function getStoreIdFromCookie() {
        // Decode the 'role' cookie value
        var cookieValue = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*;\s*)role\s*=\s*([^;]*).*$)|^.*$/, "$1"));

        // Check if the cookie value exists
        if (cookieValue) {
            // Split the cookie value into parts
            var parts = cookieValue.split('&');
            // If there are exactly two parts
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

    // Retrieve store ID from the 'role' cookie
    var storeId = getStoreIdFromCookie();

    // jQuery document ready function
    $(document).ready(function() {
        // Change event handler for the form selector
        $('#formSelector').change(function() {
            // Get the selected form value
            var selectedForm = $(this).val();
            // If a form is selected
            if (selectedForm != 'none') {
                // Retrieve store ID from the 'role' cookie again
                var storeId = getStoreIdFromCookie();
                // AJAX request to fetch data based on selected form
                $.ajax({
                    url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/' + selectedForm,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Filter response data if the selected form is 'Stocks'
                        if (selectedForm == 'Stocks') {
                            response = response.filter(function(stock) {
                                return stock.store_id == storeId;
                            });
                        }
                        // Populate dynamic select with the filtered data
                        populateDynamicSelect(response);
                        // Log the number of options in the select element
                        let t = document.querySelectorAll("option");
                        console.log(t.length);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                // Hide dynamic select container and delete button container if no form is selected
                $('#dynamicSelectContainer').hide();
                $('#deleteButtonContainer').hide();
            }
        });

        // Change event handler for the dynamic select element
        $('#dynamicSelect').change(function() {
            // Show the delete button container when an option is selected
            $('#deleteButtonContainer').show();
        });

        // Click event handler for the delete button
        $('#deleteButton').click(function() {
            // Get the selected option ID and form selector value
            var selectedOptionId = $('#dynamicSelect').val();
            var selectedForm = $('#formSelector').val();
            // AJAX request to delete the selected item
            $.ajax({
                url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/delete/' + selectedForm + '/' + selectedOptionId + '/e8f1997c763/',
                type: 'DELETE',
                success: function(response) {
                    // Display success message and trigger form selector change event
                    $('#responseMessage').html('<div class="alert alert-success" role="alert">'+response.message+'</div>');
                    $('#formSelector').change();
                },
                error: function(xhr, status, error) {
                    // Display error message
                    $('#responseMessage').html('<div class="alert alert-danger" role="alert">Error: ' + xhr.responseText + '</div>');
                }
            });
        });

        // Function to populate the dynamic select with data
        function populateDynamicSelect(data) {
            var dynamicSelect = $('#dynamicSelect');
            dynamicSelect.empty();
            $.each(data, function(index, item) {
                dynamicSelect.append($('<option>', {
                    value: item.id,
                    text: item.name
                }));
            });
            $('#dynamicSelectContainer').show();
        }
    });
</script>


<?php
// Include the footer file
include("www/footer.inc.php");
?>
