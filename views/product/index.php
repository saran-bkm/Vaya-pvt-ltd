<?php include("../layouts/header.php"); ?>
<?php include("../layouts/sidebar.php"); ?>

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center mt-1">
            <h4 class="mb-0">Product List</h4>
            <a href="add.php" class="btn btn-primary">Add Product</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="product_table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        load_data();
    });

    function load_data(){
            let data = {
                action:"LIST"
            }
            $.ajax({
                url: "../../model/product.php",
                type: "POST",
                data: data,
                success: function (res) {
                    let response = JSON.parse(res);
                    $("#tbody").html(response.data)
                }
            });
        }

        function editProduct(id){
            window.location.href = "edit.php?id=" + id;
        }


        function deleteProduct(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.isConfirmed) {
                    let data = { action: "DELETE", id: id };
                    $.ajax({
                        url: "../../model/product.php",
                        type: "POST",
                        data: data,
                        success: function(res){
                            let response = JSON.parse(res);

                            if(response.status){
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );

                                load_data();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(){
                            Swal.fire(
                                'Error!',
                                "AJAX request failed!",
                                'error'
                            );
                        }
                    });
                }
            });
        }


</script>

<?php include("../layouts/footer.php"); ?>
