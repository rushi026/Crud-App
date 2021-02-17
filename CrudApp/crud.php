<!-- Database Connection -->

<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "CrudData";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn) {
        die("Failed to connect to the database");
    }

    $insert = false;
    $update = false;
    $delete = false;

    if(isset($_GET['delete'])) {
        $sno = $_GET['delete'];
        $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno;";
        $result = mysqli_query($conn, $sql);
        if($result) {
            $delete = true;
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['snoEdit'])) {
            $sno = $_POST['snoEdit'];
            $title = $_POST['titleEdit'];
            $description = $_POST['descriptionEdit'];
            $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` = $sno";
            $result = mysqli_query($conn, $sql);
            if($result) {
                $update = true;
            }
        }
        else {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
            $result = mysqli_query($conn, $sql);
            if($result) {
                $insert = true;
            }
        }
    }

    // INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'Note 1', 'Temporary note 1', current_timestamp());
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">


    <title>CRUD</title>
</head>


<!---------------------------------------------------------------------------------------->



<body>
                    <!-- MODAL -->

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">

                                <!-- Form Inside Modal -->
                    <form action="/crudapp/crud.php" method="post">
                        <input type="hidden" id="snoEdit" name="snoEdit">
                        <div class="form-group">
                            <label for="titleEdit">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="descriptionEdit">Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Edit Note</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------------------------------------------------------------------------------------------- -->


    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">CRUD</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>


    <!-- -------------------------------------------------------------------------------------------------------------- -->


    <?php
        if($insert) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Successful!</strong> The note has been added successfully!
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                  </div>";
        }
        if($update) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Successful!</strong> The note has been updated successfully!
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                  </div>";
        }
        if($delete) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Successful!</strong> The note has been deleted successfully!
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                  </div>";
        }
    ?>




    <!-- NOTES INPUT -->
    <div class="container my-4">
        <h2>Add a Note</h2>
        <form action="/crudapp/crud.php" method="post">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>

    <!-- -------------------------------------------------------------------------------------------------------------- -->



    <!-- NOTES LIST DISPLAY -->
    <div class="container my-4">

        <table id="myTable">
            <thead>
                <tr>
                    <th scope="col" style="width: 80px;">Sr. No.</th>
                    <th scope="col" style="width: 300px;">Title</th>
                    <th scope="col" style="width: 900px;">Description</th>
                    <th scope="col" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                    $sql = "SELECT * FROM `notes`";
                    $result = mysqli_query($conn, $sql);
                    $num = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <th scope='row'>". $num . "</th>
                                <td>". $row['title'] . "</td>
                                <td>". $row['description'] . "</td>
                                <td> 
                                    <button id='".$row['sno']."' class='edit btn btn-sm btn-primary'>Edit</button> 
                                    <button id='d".$row['sno']."' class='delete btn btn-sm btn-primary'>Delete</button>
                                </td>
                              </tr>";
                    $num += 1;
                    }
                ?>

            </tbody>
        </table>

    </div>
    <hr>
    <!-- -------------------------------------------------------------------------------------------------------------- -->


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>

    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element)=>{
            element.addEventListener("click", (e) => {
                console.log("edit", e.target.parentNode.parentNode);
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                console.log(title);
                console.log(description);

                titleEdit = document.getElementById("titleEdit");
                descriptionEdit = document.getElementById("descriptionEdit");

                titleEdit.value = title;
                descriptionEdit.value = description;

                snoEdit.value = e.target.id;
                console.log(e.target.id);

                $('#editModal').modal('toggle');
            });
        });


        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element)=>{
            element.addEventListener("click", (e) => {
                console.log("delete");
                console.log(e.target.id);
                sno = e.target.id.substr(1,);
                console.log(sno);
                if(confirm("Do you want to delete this note?")) {
                    console.log("yes");
                    window.location = `/crudapp/crud.php?delete=${sno}`;
                }
                else {
                    console.log("no");
                }
            
            });
        });
    </script>
</body>

</html>