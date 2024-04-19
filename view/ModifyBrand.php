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
    <div class="row">
        <div class="col-12">
            <h2>Modify Brand</h2>
            <form>
                <div class="form-group">
                    <label for="brandSelect">Choose Brand :</label>
                    <select id="brandSelect" class="form-control">
                        <option value="">-- Choose Brand --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="brandName">New Brand Name :</label>
                    <input type="text" id="brandName" class="form-control">
                </div>
                <button type="button" class="btn btn-primary mt-3" onclick="updateBrand()">Modify</button>
            </form>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <div id="message"></div>
        </div>
    </div>
</div>


<script>
    // This code block executes when the document is ready
$(document).ready(function() {
    // AJAX request to fetch brands data
    $.ajax({
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Brand', 
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // On success, populate the brand select dropdown with fetched data
            var select = $('#brandSelect');
            select.empty(); // Clear previous options
            select.append($('<option>', { // Add default option
                value: '',
                text: '-- Choose Brand --'
            }));
            // Loop through each brand in the response and append it to the select dropdown
            $.each(response, function(index, brand) {
                select.append($('<option>', {
                    value: brand.id,
                    text: brand.name
                }));
            });
        },
        error: function(xhr, status, error) {
            // Log error message to console if fetching brands fails
            console.error('Error loading brands:', error);
        }
    });
});

// This code block executes when the brand select dropdown value changes
$('#brandSelect').change(function() {
    var brandId = $(this).val(); // Get selected brand ID
    if (brandId) {
        // AJAX request to fetch details of the selected brand
        $.ajax({
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/brand/' + brandId, 
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // On success, update the input field with the fetched brand name
                $('#brandName').val(response.name);
            },
            error: function(xhr, status, error) {
                // Log error message to console if fetching brand details fails
                console.error('Error loading brand:', error);
            }
        });
    } else {
        // Clear the input field if no brand is selected
        $('#brandName').val('');
    }
});

// Function to update brand details
function updateBrand() {
    var brandId = $('#brandSelect').val(); // Get selected brand ID
    var newBrandName = $('#brandName').val(); // Get new brand name

    var data = {
        "id": brandId,
        "brand_name": newBrandName
    };

    // AJAX request to update brand details
    $.ajax({
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Brand/'+brandId+'/e8f1997c763',
        type: 'PUT',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            // On success, display success message
            $('#message').text("Success");
        },
        error: function(xhr, status, error) {
            // On error, display error message
            $('#message').text('Error updating brand.');
        }
    });
}

</script>
<?php include("www/footer.inc.php"); ?>
