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
    <div class="form-group">
        <select id="formSelector" class="form-control">
            <option value="none">-- Select a Form --</option>
            <option value="Categorie">Category</option>
            <option value="Brand">Brand</option>
            <option value="Products">Product</option>
            <option value="Stocks">Stock</option>
            <option value="Stores">Stores</option>
        </select>
    </div>

    <div id="formContainer">
        <div id="CategorieForm" class="hidden">
            <h2>Category</h2>
            <form>
                <div class="form-group">
                    <label for="category_name">Category name:</label>
                    <input type="text" id="category_name" name="category_name" class="form-control mb-3">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add</button>
            </form>
        </div>
        <div id="BrandForm" class="hidden">
            <h2>Brand</h2>
            <form>
                <div class="form-group">
                    <label for="brand_name">Brand name:</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control mb-3">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add</button>
            </form>
        </div>
        <div id="ProductsForm" class="hidden">
            <h2>Product</h2>
            <form>
                <div class="form-group">
                    <label for="product_name">Product :</label>
                    <input type="text" id="product_name" name="product_name" class="form-control mb-3">
                    <select name="brand_id" id="brand_id" class="form-control mb-3"></select>
                    <select name="categorie_id" id="categorie_id" class="form-control mb-3"></select>
                    <label for="model_year">Model Year :</label>
                    <input type="text" id="model_year" name="model_year" class="form-control mb-3">
                    <label for="list_price">List Price :</label>
                    <input type="text" id="list_price" name="list_price" class="form-control mb-3">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add</button>
            </form>
        </div>
        <div id="StocksForm" class="hidden">
            <h2>Stock</h2>
            <form>
                <div class="form-group">
                    <label for="stockField">Stock:</label>
                    <select name="product_id" id="product_id" class="form-control mb-3"></select>
                    <label for="quantity">Quantity :</label>
                    <input type="text" id="quantity" name="quantity" class="form-control mb-3">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add</button>
            </form>
        </div>
        <div id="StoresForm" class="hidden">
            <h2>Stores</h2>
            <form>
                <div class="form-group">
                    <label for="store_name">Stores:</label>
                    <input type="text" id="store_name" name="store_name" class="form-control mb-3">
                    <label for="phone">Stores phone:</label>
                    <input type="text" id="phone" name="phone" class="form-control mb-3">
                    <label for="email">Stores email:</label>
                    <input type="text" id="email" name="email" class="form-control mb-3">
                    <label for="street">Stores street:</label>
                    <input type="text" id="street" name="street" class="form-control mb-3">
                    <label for="city">Stores city:</label>
                    <input type="text" id="city" name="city" class="form-control mb-3">
                    <label for="state">Stores state:</label>
                    <input type="text" id="state" name="state" class="form-control mb-3">
                    <label for="zip_code">Stores zip code:</label>
                    <input type="text" id="zip_code" name="zip_code" class="form-control mb-3">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add</button>
            </form>
        </div>
    </div>
    <div id="responseMessage" class="mt-3"></div>
</div>

<?php
// Include the footer file
include("www/footer.inc.php");
?>


<script>
    $(document).ready(function() {
        // Hide all form sections initially
        $('#formContainer > div').hide();

        // Show selected form section when form selector changes
        $('#formSelector').change(function() {
            var selectedFormId = $(this).val();
            $('#formContainer > div').hide();
            $('#' + selectedFormId + 'Form').show();
            $('#responseMessage').html('<div class="mt-3"></div>');
        });

        // AJAX request to fetch products data
        $.ajax({
            type: 'GET',
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Products',
            success: function(response) {
                var productsSelect = $('#product_id');
                $.each(response, function(index, product) {
                    productsSelect.append('<option value="' + product.id + '">' + product.name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.log(response);
                console.error(error);
            }
        });

        // AJAX request to fetch categories data
        $.ajax({
            type: 'GET',
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Categorie',
            success: function(response) {
                var categoriesSelect = $('#categorie_id');
                $.each(response, function(index, category) {
                    categoriesSelect.append('<option value="' + category.id + '">' + category.name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

        // AJAX request to fetch brands data
        $.ajax({
            type: 'GET',
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Brand',
            success: function(response) {
                var brandsSelect = $('#brand_id');
                var brands = response;
                $.each(response, function(index, brand) {
                    brandsSelect.append('<option value="' + brand.id + '">' + brand.name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

        // Retrieve store ID from cookie
        var storeId = null;
        function getStoreIdFromCookie() {
            var cookieValue = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*;\s*)role\s*=\s*([^;]*).*$)|^.*$/, "$1"));
            if (cookieValue) {
                var parts = cookieValue.split('&');
                if (parts.length === 2) {
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
       
        // Call the function to retrieve store ID from the cookie
        var storeId = getStoreIdFromCookie();

        // Form submission handler
        $('form').submit(function(event) {
            event.preventDefault(); 

            // Check if any input field is empty
            var isEmpty = false;
            $(this).find('input[type="text"]').each(function() {
                if ($(this).val().trim() === '') {
                    isEmpty = true;
                    return false;
                }
            });

            // If any field is empty, display error message
            if (isEmpty) {
                $('#responseMessage').html('<div class="alert alert-danger" role="alert">Please fill out all fields.</div>');
                return;
            }

            // Serialize form data and convert it to JSON format
            var formData = $(this).serializeArray(); 
            var jsonData = {}; 
            $.each(formData, function(index, field) {
                jsonData[field.name] = field.value;
            });

            // Determine action based on form being submitted
            var action = $(this).closest('div[id$="Form"]').attr('id'); 
            var actionSplit = action.split('Form');
            action = actionSplit[0]; 
            if (action == "Stocks") {
                jsonData['store_id'] = storeId;
            }

            // AJAX request to submit form data
            $.ajax({
                type: 'POST',
                url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/' + action + '/e8f1997c763',
                data: JSON.stringify(jsonData), 
                contentType: 'application/json', 
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success" role="alert">The data has been added successfully</div>');
                },
                error: function(xhr, status, error) {
                    $('#responseMessage').html('<div class="alert alert-danger" role="alert">Error: ' + xhr.responseText + '</div>');
                }
            });
        });
    });
</script>

