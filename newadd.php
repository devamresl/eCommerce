<?php

    ob_start();
    session_start();
    $pageTitle = "Create Nwe Item";

    include "init.php";
    if (isset($_SESSION["user"])) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $formErrors = array();

        $name     = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
        $desc     = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
        $price    = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_INT);
        $country  = filter_var($_POST["country"], FILTER_SANITIZE_STRING);
        $status   = filter_var($_POST["status"], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST["category"], FILTER_SANITIZE_NUMBER_INT);
        $tags     = filter_var($_POST["tags"], FILTER_SANITIZE_STRING);

        if (strlen($name) < 4) {
            $formErrors[] = "Item Title Must Be At least 4 Character";
        }

        if (strlen($desc) < 10) {
            $formErrors[] = "Item Description Must Be At least 10 Character";
        }

        if (strlen($name) < 2) {
            $formErrors[] = "Item Country Must Be At least 2 Character";
        }

        if (empty($price)) {
            $formErrors[] = "Item Price Must Be Not Empty";
        }

        if (empty($status)) {
            $formErrors[] = "Item Status Must Be Not Empty";
        }

        if (empty($category)) {
            $formErrors[] = "Item Category Must Be Not Empty";
        }

        // Check If There's No Error Proceed The Update Operation

        if (empty($formErrors)) {
        
            // Insert user info in Database
            $stmt = $con->prepare("INSERT INTO 
                        items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
                    VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");
            $stmt->execute(array(
                
                "zname"     => $name,
                "zdesc"     => $desc,
                "zprice"    => $price,
                "zcountry"  => $country,
                "zstatus"   => $status,
                "zcat"      => $category,
                "zmember"   => $_SESSION['uid'],
                "ztags"     => $tags

            ));
            // Echo Success Message
            if ($stmt) {
                $successMsg = "Item Has Been Added";
            }

        }

    }

?>
    <h1 class="text-center"><?php echo $pageTitle ?></h1>
    <div class="create-add block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $pageTitle ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <!-- Start Name Field-->
                            <div class="form-group form-group-lg">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input 
                                            pattern=".{4,}"
                                            title="This Field Require At Least 4 Characters"
                                            type="text" 
                                            name="name" 
                                            class="form-control live" 
                                            placeholder="Name Of The Item" 
                                            data-class=".live-title"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <!-- End Name Field-->
                            <!-- Start Description Field-->
                            <div class="form-group form-group-lg">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input 
                                            pattern=".{10,}"
                                            title="This Field Require At Least 10 Characters"
                                            type="text" 
                                            name="description" 
                                            class="form-control live" 
                                            placeholder="Description Of The Item" 
                                            data-class=".live-desc"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <!-- End Description Field-->
                            <!-- Start Price Field-->
                            <div class="form-group form-group-lg">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input 
                                            pattern=".{2,}"
                                            title="This Field Require At Least 2 Characters"
                                            type="text" 
                                            name="price" 
                                            class="form-control live" 
                                            placeholder="Price Of The Item" 
                                            data-class=".live-price"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <!-- End Price Field-->
                            <!-- Start Country Made Field-->
                            <div class="form-group form-group-lg">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                            pattern=".{3,}"
                                            title="This Field Require At Least 3 Characters"
                                            type="text" 
                                            name="country" 
                                            class="form-control" 
                                            placeholder="Country Of Made" 
                                            required>
                                    </div>
                                </div>
                            </div>
                            <!-- End Country Made Field-->
                            <!-- Start Status Field-->
                            <div class="form-group form-group-lg">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="status" required>
                                            <option value="">...</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Vary Old</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- End Status Field-->
                            <!-- Start Categories Field-->
                            <div class="form-group form-group-lg">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="category" required>
                                            <option value="">...</option>
                                            <?php
                                                $cats = getAllFrom("*", "categories", "", "", "ID");
                                                foreach ($cats as $cat) {
                                                    echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- End Categories Field-->
                            <!-- Start TagsField-->
                            <div class="form-group form-group-lg">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input 
                                            type="text" 
                                            name="tags" 
                                            class="form-control" 
                                            placeholder="Separate Tags With Comma (,)" 
                                            >
                                    </div>
                                </div>
                            </div>
                            <!-- End Tags Field-->
                            <!-- Start Submit Field-->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <input type="submit" value="Add Item" class="btn btn-primary btn-md">
                                    </div>
                                </div>
                            </div>
                            <!-- End Submit Field-->
                        </form>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                <span class="price-tag">
                                    $<span class="live-price">0</span>
                                </span>
                                <img class="img-responsive" src="01.png" alt="">
                                <div class="caption">
                                    <h3 class="live-title">Title</h3>
                                    <p class="live-desc">Desc</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start Looping Through Errors -->
                    <?php
                        if (!empty($formErrors)) {

                            foreach ($formErrors as $error) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }

                        }

                        if (isset($successMsg)) {
                            echo '<div class="alert alert-success">' . $successMsg . '</div>';
                        }
                    ?>
                    <!-- End Looping Through Errors -->
                </div>
            </div>
        </div>
    </div>

<?php

    } else {
        header("location: login.php");
        exit();
    }

    include $tpl . "footer.php";
    ob_end_flush();
?>