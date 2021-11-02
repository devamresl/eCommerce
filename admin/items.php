<?php

    ob_start();

    session_start();

    $pageTitle = "Itmes";

    if (isset($_SESSION["Username"])) {

        include "init.php";

        $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

        if ($do == "Manage") { // Manage Items Page 

            $stmt = $con->prepare("SELECT
                                        items.*,
                                        categories.Name AS category_name, 
                                        users.Username
                                    FROM 
                                        items
                                    INNER JOIN 
                                        categories 
                                    ON 
                                        categories.ID = items.Cat_ID
                                    INNER JOIN 
                                        users 
                                    ON 
                                        users.UserID = items.Member_ID
                                    ORDER BY
                                        item_ID DESC");
            // Execute the statement
            $stmt->execute();
            // Assign to variable

            $items = $stmt->fetchAll();

            if (!empty($items)) {
        
        ?>

            <h1 class="text-center">Manage Items</h1>

            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>

                        <?php

                            foreach($items as $item) {

                                echo "<tr>";
                                    echo "<td>" . $item["item_ID"] . "</td>";
                                    echo "<td>" . $item["Name"] . "</td>";
                                    echo "<td>" . $item["Description"] . "</td>";
                                    echo "<td>" . $item["Price"] . "</td>";
                                    echo "<td>" . $item["Add_Date"] . "</td>";
                                    echo "<td>" . $item["category_name"] . "</td>";
                                    echo "<td>" . $item["Username"] . "</td>";
                                    echo "<td>
                                            <a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-times'></i> Delete</a>";
                                            if ($item["Approve"] == 0) {
                                                echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' 
                                                class='btn btn-info activate'>
                                                <i class='fa fa-check'></i> Approve</a>";
                                            }
                                    echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                    </table>
                </div>
                <a href="items.php?do=Add" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New Item
                </a>
            </div> <?php } else {
                echo '<div class="container">';
                    echo '<div class="alert alert-info nice-message">There\'s No Items To Show</div>';
                    echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New Item
                    </a>';
                echo '</div>';
            } ?>

    <?php

        } elseif ($do == "Add") { ?>

            <h1 class="text-center">Add New Item</h1>

            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start Name Field-->
                    <div class="form-group form-group-lg">
                        <div class="row">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="form-control" 
                                    placeholder="Name Of The Item" 
                                    >
                            </div>
                        </div>
                    </div>
                    <!-- End Name Field-->
                    <!-- Start Description Field-->
                    <div class="form-group form-group-lg">
                        <div class="row">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-4">
                                <input 
                                    type="text" 
                                    name="description" 
                                    class="form-control" 
                                    placeholder="Description Of The Item" 
                                    >
                            </div>
                        </div>
                    </div>
                    <!-- End Description Field-->
                    <!-- Start Price Field-->
                    <div class="form-group form-group-lg">
                        <div class="row">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10 col-md-4">
                                <input 
                                    type="text" 
                                    name="price" 
                                    class="form-control" 
                                    placeholder="Price Of The Item" 
                                    >
                            </div>
                        </div>
                    </div>
                    <!-- End Price Field-->
                    <!-- Start Country Made Field-->
                    <div class="form-group form-group-lg">
                        <div class="row">
                            <label class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10 col-md-4">
                                <input 
                                    type="text" 
                                    name="country" 
                                    class="form-control" 
                                    placeholder="Country Of Made" 
                                    >
                            </div>
                        </div>
                    </div>
                    <!-- End Country Made Field-->
                    <!-- Start Status Field-->
                    <div class="form-group form-group-lg">
                        <div class="row">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="status">
                                    <option value="0">...</option>
                                    <option value="1">New</option>
                                    <option value="2">Like New</option>
                                    <option value="3">Used</option>
                                    <option value="4">Vary Old</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- End Status Field-->
                    <!-- Start Members Field-->
                    <div class="form-group form-group-lg">
                        <div class="row">
                            <label class="col-sm-2 control-label">Members</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="members">
                                    <option value="0">...</option>
                                    <?php
                                        $allMembers = getAllFrom("*", "users", "", "", "UserID");
                                        foreach ($allMembers as $user) {
                                            echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- End Members Field-->
                    <!-- Start Categories Field-->
                    <div class="form-group form-group-lg">
                        <div class="row">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="category">
                                    <option value="0">...</option>
                                    <?php
                                        $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                                        foreach ($allCats as $cat) {
                                            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                            $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                            foreach ($childCats as $child) {
                                                echo "<option value='" . $child['ID'] . "'>--- " . $child['Name'] . "</option>";
                                            }
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
                            <label class="col-sm-2 control-label">Tags</label>
                            <div class="col-sm-10 col-md-4">
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
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Add Item" class="btn btn-primary btn-md">
                            </div>
                        </div>
                    </div>
                    <!-- End Submit Field-->
                </form>
            </div>
            <?php
        } elseif ($do == "Insert") {

            // Inasert Items Page

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo '<h1 class="text-center">Update Items</h1>';
                echo '<div class="container">';
                // Get Variables From The Form

                $name       = $_POST["name"];
                $desc       = $_POST["description"];
                $price      = $_POST["price"];
                $country    = $_POST["country"];
                $status     = $_POST["status"];
                $member     = $_POST["members"];
                $cat        = $_POST["category"];
                $tags       = $_POST["tags"];

                // Validate The Form

                $formErrors = array();

                if (empty($name)) {

                    $formErrors[] = "Name can't Be <strong>empty</strong>";

                }

                if (empty($desc)) {

                    $formErrors[] = "Description can't Be <strong>empty</strong>";

                }

                if (empty($price)) {

                    $formErrors[] = "Price Can't Be <strong>empty</strong>";

                }

                if (empty($country)) {

                    $formErrors[] = "Country Can't Be <strong>empty</strong>";

                }

                if ($member == 0) {

                    $formErrors[] = "You Must Choose The <strong>Member</strong>";

                }

                if ($cat == 0) {

                    $formErrors[] = "You Must Choose The <strong>Status</strong>";

                }

                if ($status == 0) {

                    $formErrors[] = "You Must Choose The <strong>Category</strong>";

                }

                // Loop Into Errors Array And Echo It

                foreach($formErrors as $error) {

                    echo "<div class='alert alert-danger'>" . $error . "</div>";

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
                            "zcat"      => $cat,
                            "zmember"   => $member,
                            "ztags"     => $tags

                        ));
                        // Echo Success Message
                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";

                        redirectHome($theMsg, "back");

                }

            } else {

                echo "<div class='container'>";

                $theMsg =  "<div class='alert alert-danger'>Sorry you can't browse this page directly</div>";

                redirectHome($theMsg);

                echo "</div>";
            }

            echo "</div>";

        } elseif ($do == "Edit") { // Edit Page

            // Check If Get Request item id Is Numeric & The Integer Value Of It

            $itemid = (isset($_GET["itemid"]) && is_numeric($_GET["itemid"])) ? intval($_GET["itemid"]) : 0;
            // Select All Data Depend This ID
            $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");
            // Execute Query
            $stmt->execute(array($itemid));
            // Fetch The Data
            $item = $stmt->fetch();
            // The Row Count
            $count = $stmt->rowCount();
            // If There's Such ID Show The Form
            if ($count > 0) { ?>

                <h1 class="text-center">Edit New Item</h1>

                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                        <!-- Start Name Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-4">
                                    <input 
                                        type="text" 
                                        name="name" 
                                        class="form-control" 
                                        placeholder="Name Of The Item" 
                                        value="<?php echo $item['Name'] ?>"
                                        >
                                </div>
                            </div>
                        </div>
                        <!-- End Name Field-->
                        <!-- Start Description Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-4">
                                    <input 
                                        type="text" 
                                        name="description" 
                                        class="form-control" 
                                        placeholder="Description Of The Item"
                                        value="<?php echo $item['Description'] ?>"
                                        >
                                </div>
                            </div>
                        </div>
                        <!-- End Description Field-->
                        <!-- Start Price Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10 col-md-4">
                                    <input 
                                        type="text" 
                                        name="price" 
                                        class="form-control" 
                                        placeholder="Price Of The Item"
                                        value="<?php echo $item['Price'] ?>"
                                        >
                                </div>
                            </div>
                        </div>
                        <!-- End Price Field-->
                        <!-- Start Country Made Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Country</label>
                                <div class="col-sm-10 col-md-4">
                                    <input 
                                        type="text" 
                                        name="country" 
                                        class="form-control" 
                                        placeholder="Country Of Made"
                                        value="<?php echo $item['Country_Made'] ?>"
                                        >
                                </div>
                            </div>
                        </div>
                        <!-- End Country Made Field-->
                        <!-- Start Status Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10 col-md-4">
                                    <select name="status">
                                        <option value="1" <?php if ($item['Status'] == 1) { echo "selected"; } ?>>New</option>
                                        <option value="2" <?php if ($item['Status'] == 2) { echo "selected"; } ?>>Like New</option>
                                        <option value="3" <?php if ($item['Status'] == 3) { echo "selected"; } ?>>Used</option>
                                        <option value="4" <?php if ($item['Status'] == 4) { echo "selected"; } ?>>Vary Old</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- End Status Field-->
                        <!-- Start Members Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Members</label>
                                <div class="col-sm-10 col-md-4">
                                    <select name="members">
                                        <?php
                                            $stmt = $con->prepare("SELECT * FROM users");
                                            $stmt->execute();
                                            $users = $stmt->fetchAll();
                                            foreach ($users as $user) {
                                                echo "<option value='" . $user['UserID'] . "'"; 
                                                if ($item['Member_ID'] == $user['UserID']) { echo 'selected'; } 
                                                echo">" . $user['Username'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- End Members Field-->
                        <!-- Start Categories Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Category</label>
                                <div class="col-sm-10 col-md-4">
                                    <select name="category">
                                        <?php
                                            $stmt2 = $con->prepare("SELECT * FROM categories");
                                            $stmt2->execute();
                                            $cats = $stmt2->fetchAll();
                                            foreach ($cats as $cat) {
                                                echo "<option value='" . $cat['ID'] . "'";
                                                if ($item['Cat_ID'] == $cat['ID']) { echo "selected"; } 
                                                echo">" . $cat['Name'] . "</option>";
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
                                <label class="col-sm-2 control-label">Tags</label>
                                <div class="col-sm-10 col-md-4">
                                    <input 
                                        type="text" 
                                        name="tags" 
                                        class="form-control" 
                                        placeholder="Separate Tags With Comma (,)" 
                                        value="<?php echo $item['tags'] ?>">
                                </div>
                            </div>
                        </div>
                        <!-- End Tags Field-->
                        <!-- Start Submit Field-->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Save Item" class="btn btn-primary btn-md">
                                </div>
                            </div>
                        </div>
                        <!-- End Submit Field-->
                    </form>

                    <?php

                    // Select all users except page admin
                $stmt = $con->prepare("SELECT 
                                            comments.*, users.Username AS Member
                                        FROM 
                                            comments
                                        INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.user_id
                                        WHERE item_id = ?");
                // Execute the statement
                $stmt->execute(array($itemid));
                // Assign to variable

                $rows = $stmt->fetchAll();

                if (!empty($rows)) {
            
            ?>

                <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comments</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>

                            <?php

                                foreach($rows as $row) {

                                    echo "<tr>";
                                        echo "<td>" . $row["comment"] . "</td>";
                                        echo "<td>" . $row["Member"] . "</td>";
                                        echo "<td>" . $row["comment_date"] . "</td>";
                                        echo "<td>
                                                <a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' 
                                                class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                                <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' 
                                                class='btn btn-danger confirm'><i class='fa fa-times'></i> Delete</a>";

                                                if ($row["status"] == 0) {
                                                    echo "<a href='comments.php?do=Activate&comid=" . $row['c_id'] . "' 
                                                    class='btn btn-info activate'>
                                                    <i class='fa fa-check'></i> Approve</a>";
                                                }

                                        echo "</td>";
                                    echo "</tr>";

                                }

                            ?>
                        </table>
                    </div>
                    <?php }  ?>
                </div>
            
        <?php 
            // If There's No Such Id Show Error Message
            } else {

                echo "<div class='container'>";

                $theMsg = "<div class='alert alert-danger'>There's No Such ID</div>";

                redirectHome($theMsg);

                echo "</div>";

            }

        } elseif ($do == "Update") { // Update Item Page

                echo '<h1 class="text-center">Update Item</h1>';
                echo '<div class="container">';

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Get Variables From The Form

                    $id         = $_POST["itemid"];
                    $name       = $_POST["name"];
                    $desc       = $_POST["description"];
                    $price      = $_POST["price"];
                    $country    = $_POST["country"];
                    $status     = $_POST["status"];
                    $member     = $_POST["members"];
                    $cat        = $_POST["category"];
                    $tags       = $_POST["tags"];

                    // Validate The Form

                    $formErrors = array();

                    if (empty($name)) {

                        $formErrors[] = "Name Can't Be <strong>Empty</strong>";

                    }

                    if (empty($desc)) {

                        $formErrors[] = "Description Can't Be <strong>Empty</strong>";

                    }

                    if (empty($price)) {

                        $formErrors[] = "Price Can't Be <strong>Empty</strong>";

                    }

                    if (empty($country)) {

                        $formErrors[] = "Country Made Can't Be <strong>Empty</strong>";

                    }

                    if ($status == 0) {

                        $formErrors[] = "You Must Choose The <strong>Status</strong>";

                    }

                    if ($member == 0) {

                        $formErrors[] = "You Must Choose The <strong>Member</strong>";

                    }

                    if ($cat == 0) {

                        $formErrors[] = "You Must Choose The <strong>Category</strong>";

                    }

                    // Loop Into Errors Array And Echo It

                    foreach($formErrors as $error) {

                        echo "<div class='alert alert-danger'>" . $error . "</div>";

                    }

                    // Check If There's No Error Proceed The Update Operation

                    if (empty($formErrors)) {

                        // Update The Database With This Info

                        $stmt = $con->prepare("UPDATE 
                                                    items 
                                                SET 
                                                    Name = ?, 
                                                    Description = ?, 
                                                    Price = ?, 
                                                    Country_Made = ?,
                                                    Status = ?,
                                                    Member_ID = ?,
                                                    Cat_ID = ?,
                                                    tags = ?
                                                WHERE 
                                                    item_ID = ?");
                        $stmt->execute(array($name, $desc, $price, $country, $status, $member, $cat, $tags, $id));

                        // Echo Success Message
                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Update</div>";

                        redirectHome($theMsg, "back");

                    }

                } else {

                    $theMsg = "<div class='alert alert-danger'>Sorry you can't browse this page directly</div>";

                    redirectHome($theMsg);
                }

                echo "</div>";

        } elseif ($do == "Delete") { // Delete Item Page

                echo '<h1 class="text-center">Delete Item</h1>';
                echo '<div class="container">';

                    // Check If Get Request Item Id Is Numeric & The Integer Value Of It

                    $itemid = (isset($_GET["itemid"]) && is_numeric($_GET["itemid"])) ? intval($_GET["itemid"]) : 0;
                    // Select All Data Depend This ID
                    $check = checkItem("item_ID", "items", $itemid);
                    echo $check;
                    // If There's Such ID Show The Form
                    if ($check > 0) {

                        $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid");

                        $stmt->bindParam(":zid" , $itemid);

                        $stmt->execute();

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Delete</div>";

                        redirectHome($theMsg, "Back");

                    } else {

                        $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";

                        redirectHome($theMsg);

                    }

                echo "</div>";

        } elseif ($do == "Approve") { // Approve Item Page

                echo '<h1 class="text-center">Approve Item</h1>';
                echo '<div class="container">';

                    // Check If Get Request userid Is Numeric & The Integer Value Of It

                    $itemid = (isset($_GET["itemid"]) && is_numeric($_GET["itemid"])) ? intval($_GET["itemid"]) : 0;
                    // Select All Data Depend This ID
                    $check = checkItem("item_ID", "items", $itemid);
                    echo $check;
                    // If There's Such ID Show The Form
                    if ($check > 0) {

                        $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");

                        $stmt->execute(array($itemid));

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";

                        redirectHome($theMsg, "Back");

                    } else {

                        $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";

                        redirectHome($theMsg);

                    }

                echo "</div>";

        } 

        include $tpl . "footer.php";
        
        } else {

            header("Location: index.php");

        }

    ob_end_flush();
