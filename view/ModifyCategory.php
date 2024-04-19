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
            <h2>Update Category</h2>
            <form>
                <div class="form-group">
                    <label for="categorySelect">Select category :</label>
                    <select id="categorySelect" class="form-control">
                        <option value="">-- Select category --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="categoryName">Category name :</label>
                    <input type="text" id="categoryName" class="form-control">
                </div>
                <button type="button" class="btn btn-primary mt-3" onclick="updateCategory()">Modify</button>
            </form>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div id="message"></div>
        </div>
    </div>
</div>


<script>
    // This code block executes when the document is ready
$(document).ready(function() {
    // AJAX request to fetch categories data
    $.ajax({
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Categorie', 
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // On success, populate the category select dropdown with fetched data
            var select = $('#categorySelect');
            select.empty(); // Clear previous options
            select.append($('<option>', { // Add default option
                value: '',
                text: '-- Select a category --'
            }));
            // Loop through each category in the response and append it to the select dropdown
            $.each(response, function(index, category) {
                select.append($('<option>', {
                    value: category.id,
                    text: category.name
                }));
            });
        },
        error: function(xhr, status, error) {
            // Log error message to console if fetching categories fails
            console.error('Error loading categories:', error);
        }
    });
});

// This code block executes when the category select dropdown value changes
$('#categorySelect').change(function() {
    var categoryId = $(this).val(); // Get selected category ID
    if (categoryId) {
        // AJAX request to fetch details of the selected category
        $.ajax({
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Category/' + categoryId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // On success, update the input field with the fetched category name
                $('#categoryName').val(response.name);
            },
            error: function(xhr, status, error) {
                // Log error message to console if fetching category details fails
                console.error('Error loading category:', error);
            }
        });
    } else {
        // Clear the input field if no category is selected
        $('#categoryName').val('');
    }
});

// Function to update category details
function updateCategory() {
    var categoryId = $('#categorySelect').val(); // Get selected category ID
    var newCategoryName = $('#categoryName').val(); // Get new category name

    var data = {
        "id": categoryId,
        "category_name": newCategoryName
    };

    // AJAX request to update category details
    $.ajax({
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Category/'+categoryId+'/e8f1997c763',
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
            $('#message').text('Error updating category.');
        }
    });
}

</script>
<?php include("www/footer.inc.php"); ?>
