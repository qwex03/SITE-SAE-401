<?php

class Controller {
    private $action;

    // Constructor to initialize the controller with optional arguments
    public function __construct(array $arguments = array()){
        if(!empty($arguments)){
            // Loop through provided arguments and set them as properties
            foreach($arguments as $property=>$argument){
                $this->{$property}=$argument;
            }
        }
    }

    // Magic method to get a property of the object
    public function __get($name){
        return $this->$name;
    }

    // Magic method to set a property of the object
    public function __set($name, $value){
        $this->$name=$value;
    }

    // Method to invoke actions and include corresponding views
    public function invoke() {
        if(isset($this->action)){
            // Depending on the action, include the corresponding view
            if($this->action == "login") {													
                include("view/Login.php");
            } else if ($this->action == "home") {
                include("view/IndexView.php");
            } else if ($this->action == "DetailProduct") {
                include("view/Product.php");
            } else if ($this->action == "LegalNotices") {
                include("view/LegalNotices.php");
            } else if ($this->action == "ShowEmployees") {
                include("view/ShowEmployees.php");
            } else if ($this->action == "AddEmployee") {
                include("view/AddEmployee.php");
            } else if ($this->action == "ShowEmployeesBystore") {
                include("view/showEmployeesBystore.php");
            } else if ($this->action == "AddEmployeesBystore") {
                include("view/AddEmployeeBystore.php");
            } else if ($this->action == "Add") {
                include("view/AddForm.php");
            } else if ($this->action == "Delete") {
                include("view/DeleteForm.php");
            } else if ($this->action == "ModifyCategory") {
                include("view/ModifyCategory.php");
            } else if ($this->action == "ModifyBrand") {
                include("view/ModifyBrand.php");
            } else if ($this->action == "ModifyStores") {
                include("view/ModifyStores.php");
            } else if ($this->action == "ModifyProduct") {
                include("view/ModifyProduct.php");
            } else if ($this->action == "ModifyLogin") {
                include("view/ModifyLogin.php");
            } else if ($this->action == "ModifyStock") {
                include("view/ModifyStocks.php");
            }
        } else {
            // Default action if no specific action is set
            include("view/IndexView.php");
        }
    }
}

?>
