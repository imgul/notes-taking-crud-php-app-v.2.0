<?php
$inserted = false;
$updated = false;
$deleted = false;
$db_connect_error = false;
$conn_error = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Editing a Record
    if (isset($_POST["snoEdit"])) {
        $sno = $_POST["snoEdit"];
        $title = $_POST["editTitle"];
        $description = $_POST["editDesc"];
        include "./config.php";
        if (!$conn) {
            $db_connect_error = true;
        } else {
            $db_connect_error = false;
            $sql = "UPDATE `notes` SET `title`='$title', `description`='$description' WHERE `sno`='$sno'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $updated = true;
            } else {
                $updated = false;
                $conn_error = true;
            }
        }
    } else {
        // Creating New Record
        $title = $_POST["title"];
        $desc = $_POST["desc"];
        include "./config.php";
        if (!$conn) {
            $db_connect_error = true;
        } else {
            $db_connect_error = false;
            $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$desc');";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $inserted = true;
            } else {
                $inserted = false;
                $conn_error = true;
            }
        }
    }
}
?>

<?php
// Deleting a Record
if (isset($_GET["delete"])) {
    $sno = $_GET["delete"];
    include "./config.php";
    if (!$conn) {
        $db_connect_error = true;
    } else {
        $db_connect_error = false;
        $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $deleted = true;
            // header("location:https://localhost/hinotes/index.php");
        } else {
            $deleted = false;
            $conn_error = true;
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Fontawesom CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>hiNotes | Open source notes taking app</title>
</head>

<body>
    <!-- Add Note Modal -->
    <div class="modal fade" id="insertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertModalLabel">Add New Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>
                        <div class="mb-3">
                            <label for="desc" class="form-label">Description</label>
                            <textarea name="desc" class="form-control" id="desc" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn text-light" style="background-color: teal;" data-bs-dismiss="modal">Add Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" name="editTitle" class="form-control" id="editTitle">
                        </div>
                        <div class="mb-3">
                            <label for="editDesc" class="form-label">Description</label>
                            <textarea name="editDesc" class="form-control" id="editDesc" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Don't Save</button>
                        <button type="submit" class="btn text-light" style="background-color: teal;" data-bs-dismiss="modal">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: teal;">
        <div class="container-fluid">
            <a class="navbar-brand" href="/hinotes/index.php">hiNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/hinotes/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Error Handling with alerts -->
    <?php
    if ($inserted) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success.</strong> Your note has been created successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    if ($updated) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success.</strong> Your note has been updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    if ($deleted) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success.</strong> Your note has been deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    if ($db_connect_error) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Sorry!</strong> Your connect to databes and server was not created.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    if ($conn_error) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Sorry!</strong> Your connection with database server is interupted.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    ?>

    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-5">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h2>Add Notes</h2>
                    </div>
                    <!-- <div class="">
                        <button class="btn text-light d-flex justify-content-between align-items-center rounded-pill" style="background-color: teal;" data-bs-toggle="modal" data-bs-target="#insertModal">
                            <i class="fa-solid fa-plus"></i>
                            &nbsp;Add New Note
                        </button>
                    </div> -->
                </div>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title">
                    </div>
                    <div class="mb-3">
                        <label for="desc" class="form-label">Description</label>
                        <textarea name="desc" class="form-control" id="desc" cols="30" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn text-light" style="background-color: teal;" data-bs-dismiss="modal"><i class="fa-solid fa-plus"></i> Add Note</button>
                </form>
            </div>
            <div class="col-7 border-start">
                <h2>All Notes</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        include "./config.php";
                        $sql = "SELECT * FROM `notes`";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $no = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $no = $no + 1;
                                echo '<tr>
                                <th scope="row">' . $no . '</th>
                                <td>' . $row["title"] . '</td>
                                <td>' . $row["description"] . '</td>
                                <td>
                                    <button type="button" class="edit btn btn-sm btn-outline-success" id=' . $row["sno"] . ' data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                        Edit
                                    </button>
                                    <button type="button" id=d' . $row["sno"] . ' class="delete btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="fa-solid fa-trash-can"></i>
                                        Delete
                                    </button>
                                </td>
                                </tr>';
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
    <script>
        edits = document.getElementsByClassName("edit")
        Array.from(edits).forEach(element => {
            element.addEventListener("click", (e) => {
                tr = e.target.parentNode.parentNode
                title = tr.getElementsByTagName("td")["0"].innerText;
                description = tr.getElementsByTagName("td")["1"].innerText;
                editTitle = document.getElementById("editTitle")
                editDesc = document.getElementById("editDesc")
                snoEdit = document.getElementById("snoEdit")
                editTitle.value = title
                editDesc.value = description
                snoEdit.value = e.target.id
            })
        });

        deletes = document.getElementsByClassName("delete")
        Array.from(deletes).forEach(element => {
            element.addEventListener("click", (e) => {
                sno = e.target.id.substr(1, )
                if (confirm("Are you sure you want do delete?")) {
                    window.location = `/hinotes/index.php?delete=${sno}`
                }
            })
        });
    </script>
</body>

</html>