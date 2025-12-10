<?php include "../layouts/header.php"; ?>
<?php include "../layouts/sidebar.php"; ?>
<?php

 require_once '../../db_conn/db_conn.php';
 
 $id = $_GET['id'];
 $stmt = $conn->prepare("SELECT * FROM products WHERE id = $id");
 $stmt->execute();
 $d = $stmt->fetch(PDO::FETCH_ASSOC);

 ?>




<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header">
            <h4 class="mb-0">Update Product</h4>
        </div>

        <div class="card-body p-4">
            <form>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" value="<?php echo $d['product_name']?>" onkeyup="validateName()">
                        <small id="err_name" class="text-danger d-none"></small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Price</label>
                        <input type="text" class="form-control" name="price" id="price" value="<?php echo $d['price']?>" onkeyup="validatePrice()">
                        <small id="err_price" class="text-danger d-none"></small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" name="qty" id="qty" value="<?php echo $d['qty']?>" onkeyup="validateQty()">
                        <small id="err_qty" class="text-danger d-none"></small>
                    </div>

                    <div class="col-md-12 d-flex justify-content-end mt-3">
                        <button class="btn btn-success" onclick="Update_product(event, <?php echo $id; ?>)">
                            Update Product
                        </button>
                    </div>



                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function Update_product(event,id) {

        event.preventDefault();

        let validName = validateName();
        let validPrice = validatePrice();
        let validQty = validateQty();

        if (!validName || !validPrice || !validQty) {
            return;
        }

        let data = {
            id:id,
            p_name: $('#product_name').val(),
            price: $('#price').val(),
            qty: $('#qty').val(),
            action: "UPDATE"
        }

        $.ajax({
            url: "../../model/product.php",
            type: "POST",
            data: data,
            success: function (res) {
                let response = JSON.parse(res); 

                if(response.status === true){
                    toastr.success(response.message, response.message);

                    setTimeout(function() {
                        window.location.href = "index.php"; 
                    }, 1500); 
                } else {
                    toastr.error(response.message, "Error");
                }
            },
            error: function() {
                toastr.error("AJAX request failed!", "Error");
            }
        });
    }


    function validateName() {
        let name = $('#product_name').val();
        let nameRegex = /^[A-Za-z ]+$/;

        if (name === "") {
            $('#err_name').text("Product name is required").removeClass("d-none");
            return false;
        } 
        else if (!nameRegex.test(name)) {
            $('#err_name').text("Only letters allowed").removeClass("d-none");
            return false;
        } 
        else {
            $('#err_name').addClass("d-none");
            return true;
        }
    }

    function validatePrice() {
        let price = $('#price').val();
        let numberRegex = /^[0-9]+$/;

        if (price === "") {
            $('#err_price').text("Price is required").removeClass("d-none");
            return false;
        } 
        else if (!numberRegex.test(price)) {
            $('#err_price').text("Price must be numbers only").removeClass("d-none");
            return false;
        } 
        else {
            $('#err_price').addClass("d-none");
            return true;
        }
    }

    function validateQty() {
        let qty = $('#qty').val();
        let numberRegex = /^[0-9]+$/;

        if (qty === "") {
            $('#err_qty').text("Quantity is required").removeClass("d-none");
            return false;
        } 
        else if (!numberRegex.test(qty)) {
            $('#err_qty').text("Quantity must be numbers only").removeClass("d-none");
            return false;
        } 
        else {
            $('#err_qty').addClass("d-none");
            return true;
        }
    }


</script>


<?php include "../layouts/footer.php"; ?>
