<?php

    ob_start();

    session_start();

    $pageTitle = "Members";

    if (isset($_SESSION['Username'])) {

        include "init.php";

        $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

        // Satrt Manage Page

        if ($do == "Manage") { // Manage Members Page 

            $query = "";

            if (isset($_GET['page']) && $_GET['page'] == "Pending") {

                $query = "AND RegStatus = 0";

            }

            // Select all users except page admin
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
            // Execute the statement
            $stmt->execute();
            // Assign to variable

            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
        
        ?>

            <h1 class="text-center">Manage Members</h1>

            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registerd Date</td>
                            <td>Control</td>
                        </tr>

                        <?php

                            foreach($rows as $row) {

                                echo "<tr>";
                                    echo "<td>" . $row["UserID"] . "</td>";
                                    echo "<td>";
                                    if (empty($row["avatar"])) {
                                        echo "No Image";
                                    } else {
                                        echo "<img src='upload/avatars/" . $row["avatar"] ."' alt='' >";
                                    }
                                    echo "</td>";
                                    echo "<td>" . $row["Username"] . "</td>";
                                    echo "<td>" . $row["Email"] . "</td>";
                                    echo "<td>" . $row["FullName"] . "</td>";
                                    echo "<td>" . $row["Date"] . "</td>";
                                    echo "<td>
                                            <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-times'></i> Delete</a>";

                                            if ($row["RegStatus"] == 0) {
                                                echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' 
                                                class='btn btn-info activate'>
                                                <i class='fa fa-check'></i> Activate</a>";
                                            }

                                    echo "</td>";
                                echo "</tr>";

                            }

                        ?>
                    </table>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
            </div>
                <?php } else {
                    echo '<div class="container">';
                        echo '<div class="alert alert-info nice-message">There\'s No Members To Show</div>';
                        echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
                    echo '</div>';
                } ?>

    <?php } elseif($do == 'Add') { // Add Members Page ?>

            <h1 class="text-center">Add New Member</h1>

                <div class="container">
                    <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                        <!-- Start Username Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Username to login into shop" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- End Username Field-->
                        <!-- Start Password Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="password" name="password" class="form-control password" autocomplete="new-password" placeholder="Password must be hard & complex" required="required">
                                    <i class="show-pass fa fa-eye fa-lg"></i>
                                </div>
                            </div>
                        </div>
                        <!-- End Password Field-->
                        <!-- Start Email Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="email" name="email" class="form-control" placeholder="Email must be valid" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- End Email Field-->
                        <!-- Start Full Name Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Full Name</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" name="full" class="form-control" placeholder="Full name appear in your profile page" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- End Full Name Field-->
                        <!-- Start Avatar Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">User Avatar</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="file" name="avatar" class="form-control" placeholder="Full name appear in your profile page" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- End Avatar Field-->
                        <!-- Start Submit Field-->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                                </div>
                            </div>
                        </div>
                        <!-- End Submit Field-->
                    </form>
                </div>
            

        <?php } elseif ($do == 'Insert') {

            // Inasert Member Page

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo '<h1 class="text-center">Update Member</h1>';
                echo '<div class="container">';

                // Upload Variables

                $avatarName = $_FILES["avatar"]["name"];
                $avatarSize = $_FILES["avatar"]["size"];
                $avatarTmp  = $_FILES["avatar"]["tmp_name"];
                $avatarType = $_FILES["avatar"]["type"];

                // List Of Allowed File Typed To Upload 

                $avatarAllowedExtension  = array("jpeg", "jpg", "png", "gif");

                // Get Avatar Extension

                $result = explode(".", $avatarName);

                $avatarExtension = strtolower(end($result));

                // Get Variables From The Form

                $user   = $_POST["username"];
                $pass   = $_POST["password"];
                $email  = $_POST["email"];
                $name   = $_POST["full"];
                
                $hashpass = sha1($pass);

                // Validate The Form

                $formErrors = array();

                if (strlen($user) < 4) {

                    $formErrors[] = "Username can't Be Less Than <strong>4 Character</strong>";

                }

                if (strlen($user) > 20) {

                    $formErrors[] = "Username can't Be More Than <strong>20 Character</strong>";

                }

                if (empty($user)) {

                    $formErrors[] = "Username Can't Be <strong>Empty</strong>";

                }

                if (empty($pass)) {

                    $formErrors[] = "Password Can't Be <strong>Empty</strong>";

                }

                if (empty($name)) {

                    $formErrors[] = "Full Name Can't Be <strong>Empty</strong>";

                }

                if (empty($email)) {

                    $formErrors[] = "Email Can't Be <strong>Empty</strong>";

                }

                if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
                    $formErrors[] = "This Extension Is Not <strong>Allowed</strong>";
                }

                if (empty($avatarName)) {
                    $formErrors[] = "Avatar Is <strong>Required</strong>";
                }

                if ($avatarSize > 4194304) {
                    $formErrors[] = "Avatar Can't Be Larger Than <strong>4MB</strong>";
                }

                // Loop Into Errors Array And Echo It

                foreach($formErrors as $error) {

                    echo "<div class='alert alert-danger'>" . $error . "</div>";

                }
                
                // Check If There's No Error Proceed The Update Operation

                if (empty($formErrors)) {

                    $avatar = rand(0, 1000000) . "_" . $avatarName;

                    move_uploaded_file($avatarTmp, "upload\avatars\\" . $avatar);

                    // Check if user exist in database

                    $check = checkItem("Username", "users", $user);

                    if ($check == 1) {

                        $theMsg = "<div class='alert alert-danger'>Sorry this user is exist</div>";
                        redirectHome($theMsg, "back");

                    } else {
                        
                        // Insert user info in Database
                        $stmt = $con->prepare("INSERT INTO 
                                            users(Username, Password, Email, FullName, RegStatus, Date, avatar)
                                            VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar)");
                        $stmt->execute(array(
                            
                            "zuser"   => $user,
                            "zpass"   => $hashpass,
                            "zmail"   => $email,
                            "zname"   => $name,
                            "zavatar" => $avatar

                        ));
                        // Echo Success Message
                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";

                        redirectHome($theMsg, "back");

                    }

                }
                
            } else {

                echo "<div class='container'>";

                $theMsg =  "<div class='alert alert-danger'>Sorry you can't browse this page directly</div>";

                redirectHome($theMsg);

                echo "</div>";
            }

            echo "</div>";

            
        } elseif ($do == "Edit") { // Edit Page

            // Check If Get Request userid Is Numeric & The Integer Value Of It

            $userid = (isset($_GET["userid"]) && is_numeric($_GET["userid"])) ? intval($_GET["userid"]) : 0;
            // Select All Data Depend This ID
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            // Execute Query
            $stmt->execute(array($userid));
            // Fetch The Data
            $row = $stmt->fetch();
            // The Row Count
            $count = $stmt->rowCount();
            // If There's Such ID Show The Form
            if ($stmt->rowCount() > 0) { ?>
                <h1 class="text-center">Edit Member</h1>

                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                        <!-- Start Username Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" name="username" class="form-control" value="<?php echo $row["Username"] ?>" autocomplete="off" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- End Username Field-->
                        <!-- Start Password Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="hidden" name="oldpassword" value="<?php echo $row["Password"] ?>">
                                    <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change">
                                </div>
                            </div>
                        </div>
                        <!-- End Password Field-->
                        <!-- Start Email Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="email" name="email" class="form-control" value="<?php echo $row["Email"] ?>" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- End Email Field-->
                        <!-- Start Full Name Field-->
                        <div class="form-group form-group-lg">
                            <div class="row">
                                <label class="col-sm-2 control-label">Full Name</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" name="full" class="form-control" value="<?php echo $row["FullName"] ?>" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- End Username Field-->
                        <!-- Start Submit Field-->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Save" class="btn btn-primary btn-lg">
                                </div>
                            </div>
                        </div>
                        <!-- End Submit Field-->
                    </form>
                </div>
            
        <?php 
            // If There's No Such Id Show Error Message
            } else {

                echo "<div class='container'>";

                $theMsg = "<div class='alert alert-danger'>There's No Such ID</div>";

                redirectHome($theMsg);

                echo "</div>";

            }
        
        } elseif ($do == "Update") { // Update Page

            echo '<h1 class="text-center">Update Member</h1>';
            echo '<div class="container">';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get Variables From The Form

                $id     = $_POST["userid"];
                $user   = $_POST["username"];
                $email  = $_POST["email"];
                $name   = $_POST["full"];

                // Password Trick

                // Condtion ? True : False;

                $pass = empty($_POST["newpassword"]) ? $_POST["oldpassword"] : sha1($_POST["newpassword"]);

                // Validate The Form

                $formErrors = array();

                if (strlen($user) < 4) {

                    $formErrors[] = "Username can't Be Less Than <strong>4 Character</strong>";

                }

                if (strlen($user) > 20) {

                    $formErrors[] = "Username can't Be More Than <strong>20 Character</strong>";

                }

                if (empty($user)) {

                    $formErrors[] = "Username Can't Be <strong>Empty</strong>";

                }

                if (empty($name)) {

                    $formErrors[] = "Full Name Can't Be <strong>Empty</strong>";

                }

                if (empty($email)) {

                    $formErrors[] = "Email Can't Be <strong>Empty</strong>";

                }

                // Loop Into Errors Array And Echo It

                foreach($formErrors as $error) {

                    echo "<div class='alert alert-danger'>" . $error . "</div>";

                }

                // Check If There's No Error Proceed The Update Operation

                if (empty($formErrors)) {

                    $stmt2 = $con->prepare("SELECT 
                                                * 
                                            FROM 
                                                users 
                                            WHERE 
                                                Username = ? 
                                            AND 
                                                UserID != ?");

                    $stmt2->execute(array($user, $id));

                    $count = $stmt2->rowCount();

                    if ($count == 1) {
                        echo "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                        
                        redirectHome($theMsg, "back");
                    } else {
                        // Update The Database With This Info

                        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                        $stmt->execute(array($user, $email, $name, $pass, $id));

                        // Echo Success Message
                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Update</div>";

                        redirectHome($theMsg, "back");
                    }

                }

            } else {

                $theMsg = "<div class='alert alert-danger'>Sorry you can't browse this page directly</div>";

                redirectHome($theMsg);
            }

            echo "</div>";

        } elseif ($do == "Delete") { // Delete Member Page

            echo '<h1 class="text-center">Delete Member</h1>';
            echo '<div class="container">';

                // Check If Get Request userid Is Numeric & The Integer Value Of It

                $userid = (isset($_GET["userid"]) && is_numeric($_GET["userid"])) ? intval($_GET["userid"]) : 0;
                // Select All Data Depend This ID
                $check = checkItem("userid", "users", $userid);
                echo $check;
                // If There's Such ID Show The Form
                if ($check > 0) {

                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

                    $stmt->bindParam("zuser" , $userid);

                    $stmt->execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Delete</div>";

                    redirectHome($theMsg, "Back");

                } else {

                    $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";

                    redirectHome($theMsg);

                }

            echo "</div>";

        } elseif ($do= "Activate") {

            echo '<h1 class="text-center">Activate Member</h1>';
            echo '<div class="container">';

                // Check If Get Request userid Is Numeric & The Integer Value Of It

                $userid = (isset($_GET["userid"]) && is_numeric($_GET["userid"])) ? intval($_GET["userid"]) : 0;
                // Select All Data Depend This ID
                $check = checkItem("userid", "users", $userid);
                echo $check;
                // If There's Such ID Show The Form
                if ($check > 0) {

                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

                    $stmt->execute(array($userid));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";

                    redirectHome($theMsg);

                } else {

                    $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";

                    redirectHome($theMsg);

                }

            echo "</div>";

        }
        
        include $tpl . "footer.php";

    } else {

        header('Location: index.php');

        exit();

    }

    ob_end_flush();
