<?php
// Include the header file
include ("www/header.inc.php");
?>
	<style>
		 #map {
            height: 300px;
            width: 100%;
        }

        .text-shadow {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
  }

	</style>
  <div class="baniere d-flex justify-content-center align-items-center flex-column position-relative text-light">
  <div class="container pb-5 text-center">
    <h1 class="fw-bold text-shadow">Bike Stores</h1>
    <p class="lead text-shadow">Welcome to our website</p>
    <button class="btn btn-outline-light mt-3">
      Shop Now
    </button>
  </div>
</div>




  </div>

  <div class="container">
    <h2 class="mt-5 fw-semibold">Our Products</h2>
    <div class="filter-container float-right">
      <div class="form-inline d-flex align-items-center justify-content-end gap-4">
        <div class="form-group mr-2">
          <label for="filter-brand" class="mr-2">Filter by Brand:</label>
          <select id="filter-brand" class="form-control">
            <option value="">All Brands</option>
          </select>
        </div>
        <div class="form-group mr-2">
          <label for="filter-category" class="mr-2">Filter by Category:</label>
          <select id="filter-category" class="form-control">
            <option value="">All Categories</option>
          </select>
        </div>
        <div class="form-group mr-2">
          <label for="filter-model-year" class="mr-2">Filter by Model Year:</label>
          <select id="filter-model-year" class="form-control">
            <option value="">All Model Year</option>
          </select>
        </div>
        <div class="form-group">
          <label for="filter-price-min" class="mr-2">Min Price:</label>
          <input type="number" id="filter-price-min" class="form-control" placeholder="Enter minimum price">
        </div>
        <div class="form-group">
          <label for="filter-price-max" class="mr-2">Max Price:</label>
          <input type="number" id="filter-price-max" class="form-control" placeholder="Enter maximum price">
        </div>
      </div>
    </div>
    <table class="table mt-2">
      <thead class="bg-dark text-light">
        <tr>
          <th>Name</th>
          <th>Brand</th>
          <th>Category</th>
          <th>Model Year</th>
          <th>List Price</th>
        </tr>
      </thead>
      <tbody id="api-data">
        
      </tbody>
    </table>
	<h2 class="mt-4">Visit us</h2>
	<div id="map" class="mb-5"></div>
  </div>
  <script>
    // Variable to store all data fetched from the API
    var allData = []; 

    // jQuery document ready function
    $(document).ready(function(){
        // AJAX request to fetch data from the API
        $.ajax({
            url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/Products/',
            method: 'GET',
            dataType: 'json',
            success: function(data){
                // Store fetched data in the allData variable
                allData = data; 
                // Populate filters with data and display all data
                populateFilters(allData);
                displayData(allData);
            },
            error: function(xhr, status, error){
                // Log error message and display error message in the table
                console.error(error);
                $('#api-data').html('<tr><td colspan="6">Error fetching data from API</td></tr>');
            }
        });

        // Event handler for filter change events
        $('#filter-brand, #filter-category, #filter-model-year, #filter-price').on('change keyup', function(){
            // Call filterData function when filter values change
            filterData();
        });
    });

    // Function to populate filters with data
    function populateFilters(data) {
        var brands = [];
        var categories = [];
        var modelYears = [];

        // Extract unique brand, category, and model year values from the data
        $.each(data, function (index, item) {
            if (brands.indexOf(item.brand) === -1) {
                brands.push(item.brand);
            }
            if (categories.indexOf(item.category) === -1) {
                categories.push(item.category);
            }
            if (modelYears.indexOf(item['model year']) === -1) {
                modelYears.push(item['model year']);
            }
        });

        // Generate options for brand filter
        var brandOptions = '<option value="">All Brands</option>';
        $.each(brands, function (index, brand) {
            brandOptions += '<option value="' + brand + '">' + brand + '</option>';
        });
        $('#filter-brand').html(brandOptions);

        // Generate options for category filter
        var categoryOptions = '<option value="">All Categories</option>';
        $.each(categories, function (index, category) {
            categoryOptions += '<option value="' + category + '">' + category + '</option>';
        });
        $('#filter-category').html(categoryOptions);

        // Generate options for model year filter
        var modelYearOptions = '<option value="">All Model Years</option>';
        $.each(modelYears, function (index, modelYear) {
            modelYearOptions += '<option value="' + modelYear + '">' + modelYear + '</option>';
        });
        $('#filter-model-year').html(modelYearOptions);
    }

    // Function to display data in the table
    function displayData(data) {
        $('#api-data').empty();
        $.each(data, function(index, item){
            var row = '<tr>' +
                        '<td>' + item.name + '</td>' +
                        '<td>' + item.brand + '</td>' +
                        '<td>' + item.category + '</td>' +
                        '<td>' + item['model year'] + '</td>' +
                        '<td>' + item['list price'] + '$ </td>' +
                    '</tr>';
            $('#api-data').append(row);
        });
    }

    // Function to filter data based on filter values
    function filterData() {
        var brandFilter = $('#filter-brand').val();
        var categoryFilter = $('#filter-category').val();
        var modelYearFilter = $('#filter-model-year').val();
        var minPriceFilter = parseFloat($('#filter-price-min').val());
        var maxPriceFilter = parseFloat($('#filter-price-max').val());

        // Filter the data based on filter values
        var filteredData = allData.filter(function (item) {
            var brandMatch = brandFilter === '' || item.brand === brandFilter;
            var categoryMatch = categoryFilter === '' || item.category === categoryFilter;
            var modelYearMatch = modelYearFilter === '' || item['model year'] === parseInt(modelYearFilter);
            var priceMatch = true;
            
            if (!isNaN(minPriceFilter)) {
                priceMatch = priceMatch && parseFloat(item['list price']) >= minPriceFilter;
            }
            if (!isNaN(maxPriceFilter)) {
                priceMatch = priceMatch && parseFloat(item['list price']) <= maxPriceFilter;
            }

            return brandMatch && categoryMatch && modelYearMatch && priceMatch;
        });

        // Display the filtered data
        displayData(filteredData);
    }

    // Event handler for filter change events
    $('#filter-brand, #filter-category, #filter-model-year, #filter-price-min, #filter-price-max').on('change keyup', function(){
        // Call filterData function when filter values change
        filterData();
    });
</script>
<script>
    // jQuery document ready function
    $(document).ready(function() {
        // Variable to store the client's IP address
        let ip;

        // AJAX request to fetch the client's IP address
        $.ajax({
            url: 'https://api.bigdatacloud.net/data/client-ip',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Store the client's IP address
                ip = data.ipString;
                // Call the function to make a second API call with the IP address
                callSecondAPI(ip);
            },
            error: function(xhr, status, error) {
                // Log error message and display error message in the table
                console.log(error);
                $('#api-data').html('<tr><td colspan="6">Error fetching data from API</td></tr>');
            }
        });

        // Function to make a second API call with the IP address
        function callSecondAPI(ip) {
            $.ajax({
                url: 'https://api.apibundle.io/ip-lookup',
                method: 'GET',
                dataType: 'json',
                data: {
                    apikey: '66c2d96aaecd4b8eaf2fc995d579ade5',
                    ip: ip
                },
                success: function(data) {
                    // Extract latitude and longitude from the API response
                    var latitude = data.latitude;
                    var longitude = data.longitude;
                    // Call the function to fetch stores based on latitude and longitude
                    fetchStores(latitude, longitude);
                },
                error: function(xhr, status, error) {
                    // Log error message
                    console.error(error);
                }
            });
        }

        // Function to fetch stores based on latitude and longitude
        function fetchStores(latitude, longitude) {
            $.ajax({
                url: 'https://dev-lecordi223.users.info.unicaen.fr/sae401/StoresApi.php?action=getStores',
                method: 'GET',
                dataType: 'json',
                success: function(storesData) {
                    // Call the function to display stores on the map
                    displayStoresOnMap(storesData, latitude, longitude);
                },
                error: function(xhr, status, error) {
                    // Log error message
                    console.error(error);
                }
            });
        }

        // Function to display stores on the map
        function displayStoresOnMap(storesData, latitude, longitude) {
            // Create a Leaflet map and set its view to the given latitude and longitude
            var map = L.map('map').setView([latitude, longitude], 2);

            // Add OpenStreetMap tiles to the map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Add a marker for the client's location
            L.marker([latitude, longitude]).addTo(map);

            // Iterate through each store and add a marker for each on the map
            storesData.forEach(function(store) {
                var address = store.street + ", " + store.city + ", " + store.state + ", " + store.zip_code;
                // AJAX request to get latitude and longitude for the store's address
                $.ajax({
                    url: 'https://api.opencagedata.com/geocode/v1/json',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        key: '18ae06b67e984329aee67113667083d0',
                        q: address
                    },
                    success: function(data) {
                        // If coordinates are found, add a marker for the store on the map
                        if (data.results.length > 0) {
                            var lat = data.results[0].geometry.lat;
                            var lng = data.results[0].geometry.lng;
                            L.marker([lat, lng]).addTo(map).bindPopup('<b>' + store.name + '</b><br>' + address);
                        } else {
                            // Log an error if coordinates are not found for the address
                            console.error("Coordinates not found for address: " + address);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Log error message
                        console.error(error);
                    }
                });
            });
        }
    });
</script>


<?php
// Include the footer file
include ("www/footer.inc.php");
?>