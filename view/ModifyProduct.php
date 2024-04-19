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
    <h1>Modify Product</h1>
    <div class="row">
        <div class="col-md-12">
            <select id="productSelect" class="form-select mb-3">
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form id="modifyProductForm">
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name:</label>
                    <input type="text" class="form-control" id="productName" name="productName">
                </div>
                <div class="mb-3">
                    <label for="brand" class="form-label">Brand:</label>
                    <select id="brand" class="form-select" name="brand">
                    </select>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category:</label>
                    <select id="category" class="form-select" name="category">
                    </select>
                </div>
                <div class="mb-3">
                    <label for="modelYear" class="form-label">Model Year:</label>
                    <input type="text" class="form-control" id="modelYear" name="modelYear">
                </div>
                <div class="mb-3">
                    <label for="listPrice" class="form-label">List Price:</label>
                    <input type="text" class="form-control" id="listPrice" name="listPrice">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary mt-3">Modify</button>
                </div>
            </form>
        </div>
    </div>
    <div id="responseMessage" class="mt-3"></div>
</div>

<?php include("www/footer.inc.php"); ?>

<script>
    // Fetch products data from the specified URL and populate the product select dropdown
$.getJSON('https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Products', function(data) {
    $.each(data, function(key, product) {
        // Append each product as an option to the product select dropdown
        $('#productSelect').append('<option value="' + product.id + '">' + product.name + '</option>');
    });
});

// Fetch brands data from the specified URL and populate the brand select dropdown
$.getJSON('https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Brand', function(data) {
    $.each(data, function(key, brand) {
        // Append each brand as an option to the brand select dropdown
        $('#brand').append('<option value="' + brand.id + '">' + brand.name + '</option>');
    });
});

// Fetch categories data from the specified URL and populate the category select dropdown
$.getJSON('https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Categorie', function(data) {
    $.each(data, function(key, category) {
        // Append each category as an option to the category select dropdown
        $('#category').append('<option value="' + category.id + '">' + category.name + '</option>');
    });
});

// This code block executes when the product select dropdown value changes
$('#productSelect').change(function() {
    var selectedProductId = $(this).val();
    if (selectedProductId) {
        // AJAX request to fetch details of the selected product
        $.ajax({
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/product/' + selectedProductId + "/",
            type: 'GET',
            dataType: 'json',
            success: function(product) {
                // Populate input fields with the fetched product details
                $('#productName').val(product[0]["name"]);
                $('#modelYear').val(product[0]["model year"]);
                $('#listPrice').val(product[0]["list price"]);
            },
            error: function(xhr, status, error) {
                // Log error message to console if fetching product details fails
                console.error('Error fetching product details:', error);
            }
        });
    } else {
        // Clear input fields if no product is selected
        $('#productName').val('');
        $('#modelYear').val('');
        $('#listPrice').val('');
    }
});

// This code block executes when the modify product form is submitted
$('#modifyProductForm').submit(function(event) {
    // Prevent the default form submission behavior
    event.preventDefault(); 

    // Retrieve form field values
    var productId = $('#productSelect').val();
    var productName = $('#productName').val();
    var brandId = $('#brand').val();
    var categoryId = $('#category').val();
    var modelYear = $('#modelYear').val();
    var listPrice = $('#listPrice').val();

    // Construct product data object
    var productData = {
        "id": productId,
        "product_name": productName,
        "brand_id": brandId,
        "categorie_id": categoryId,
        "model_year": modelYear,
        "list_price": listPrice
    };

    // AJAX request to update product details
    $.ajax({
        url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Product/'+productId+'/e8f1997c763/',
        type: 'PUT',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(productData),
        success: function(response) {
            // Display success message and reset form on success
            $('#responseMessage').html('<div class="alert alert-success" role="alert">The data has been modified successfully</div>');
            $('#modifyProductForm')[0].reset();
        },
        error: function(xhr, status, error) {
            // Display error message on failure
            $('#responseMessage').html('<div class="alert alert-danger" role="alert">Error: ' + xhr.responseText + '</div>');
            alert('Error modifying product');
        }
    });
});

</script>
