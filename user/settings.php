<?php
session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
} else {
    $sql = "SELECT * FROM customer WHERE CID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row["CID"];

    $countq1 = "SELECT COUNT(*) AS total_appointments FROM appointment WHERE event_by = ?";
    $stmt = $conn->prepare($countq1);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count1 = $result->fetch_assoc();

    $countq2 = "SELECT COUNT(*) AS completed_appointments FROM appointment WHERE event_by = ? AND status = 2";
    $stmt = $conn->prepare($countq2);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count2 = $result->fetch_assoc();

    $active = 'settings';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>


    <style>
        input:focus,
        textarea:focus,
        select:focus {
            border: 1px solid #203b70 !important;
            outline: none;
            box-shadow: 0 0 5px rgba(32, 59, 112, 0.5);
        }

        .input-group-text {
            background-color: #f1f1f1;
            color: gray;
            border: none;
        }

        .disabled {
            cursor: not-allowed;
            pointer-events: none;
        }

        .icon-active {
            color: #00A33C !important;
        }

        input[readonly],
        textarea[readonly],
        select[readonly] {
            background-color: #f1f1f1;
            color: gray;
        }

        input.editable,
        textarea.editable,
        select.editable {
            background-color: white !important;
            color: black;
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>
        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">

                    <div class="logo-header" data-background-color="dark">
                        <a href="index" class="logo">
                            <img
                                src="assets/img/kaiadmin/logo_light.svg"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                </div>
                <?php include("partial/navbar.php"); ?>
            </div>

            <div class="container" style="background-color: #dbdde0 !important;">
                <div class="page-inner">
                    <div class="row">
                        <div class="col me-2 d-flex pt-2 pb-4">
                            <a href="home"
                                style="font-size: 20px; margin-top: 3px; color: gray;">
                                <i class="fas fa-arrow-left me-2"></i>
                            </a>
                            <h3 class="fw-bold mb-3 ms-3">Settings</h3>
                        </div>
                    </div>

                    <!-- Modal for Editing Fields -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Field</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="editFieldInputContainer">
                                        <input type="text" class="form-control" id="editFieldInput" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" id="saveChangesBtn" style="background-color: #00A33C; color: white">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Password Change -->
                    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="passwordModalLabel">Change Password</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="passwordInputContainer">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" class="form-control" id="newPassword" placeholder="Enter password" />

                                        <label for="verifyPassword" class="mt-2">Verify Password</label>
                                        <input type="password" class="form-control" id="verifyPassword" placeholder="Confirm password" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" id="savePasswordChangesBtn" style="background-color: #00A33C; color: white">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Changing icon -->
                    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="fileModalLabel">Confirm Changing Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to upload this profile picture?</p>
                                    <p id="fileName"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-bs-dismiss="modal" style="background-color: #FD3D3D; color: white">Cancel</button>
                                    <button type="button" class="btn" onclick="uploadFile()" style="background-color: #00A33C; color: white">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-round">
                                <div class="card-body px-5">
                                    <div class="row px-3">
                                        <div class="col-md-6 form-group">
                                            <label for="fname" class="ms-1">First Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="fname" value="<?= htmlspecialchars($row['fname']) ?>" readonly>
                                                <span class="input-group-text disabled" onclick="openEditModal('fname')"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="lname" class="ms-1">Last Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="lname" value="<?= htmlspecialchars($row['lname']) ?>" readonly>
                                                <span class="input-group-text disabled" onclick="openEditModal('lname')"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row px-3">
                                        <div class="col-md-5 form-group">
                                            <label for="telno" class="ms-1">Tel No.</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="telno" value="<?= htmlspecialchars($row['tel_no']) ?>" oninput="validateNumber('tel_no')" readonly>
                                                <span class=" input-group-text disabled" onclick="openEditModal('telno')"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <label for="address" class="ms-1">Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="address" value="<?= htmlspecialchars($row['address']) ?>" readonly>
                                                <span class="input-group-text disabled" onclick="openEditModal('address')"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label for="sex" class="ms-1">Sex</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="sex"
                                                    value="<?= isset($row['sex']) ? ($row['sex'] == 0 ? 'Male' : 'Female') : '' ?>"
                                                    readonly>
                                                <span class="input-group-text disabled" onclick="openEditModal('sex')"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="profileFileInput">Profile</label><br>
                                        <input type="file" class="form-control-file disabled" id="profileFileInput" onchange="openFileModal(event)">
                                    </div>
                                    <div class="row px-3">
                                        <div class="col-md-6 form-group">
                                            <label for="username">Username</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($row['username']) ?>" readonly>
                                                <span class="input-group-text disabled" onclick="openEditModal('username')"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password" placeholder="Password" readonly>
                                                <span class="input-group-text disabled" onclick="openPasswordModal()"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ps-3 pe-3">
                                        <div class="col-md-9 form-group">
                                            <label for="email">Email Address</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="email" value="<?= htmlspecialchars($row['email']) ?>" readonly>
                                                <span class="input-group-text disabled" onclick="openEditModal('email')"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="age">Age</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="age" value="<?= htmlspecialchars($row['age'] ?? '') ?>" oninput="validateNumber('age')" readonly>
                                                <span class=" input-group-text disabled" onclick="openEditModal('age')"><i class="icon-pencil"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center mx-2 mt-3">
                                        <button class="btn fs-5 edit-btn" style="width: 20%; background-color: #00A33C; color: white; border-radius: 6px" onclick="enableEditing()">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="card card-profile" style="height: 543px;">
                                <div class="card-header" style="border-bottom: none; height: 185px; background-color: #203b70; border-radius: 10px 10px 0 0">
                                    <div class="profile-picture">
                                        <div class="avatar avatar-xxl mb-3" style="margin-right: 4.2em;">
                                            <img src="<?= htmlspecialchars(empty($row['profile']) ? 'uploads/default_icon.png' : $row['profile']) ?>" alt="..." class="avatar-img rounded-circle"
                                                style="height: 12em; width: 12em;">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body mt-3">
                                    <div class="user-profile text-center mt-5">
                                        <div id="card-fl" class="fs-2"><?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?></div>
                                        <div id="card-as" class="job fs-4"><?= htmlspecialchars($row['age'] ?? '') ?>, <?= isset($row['sex']) ? ($row['sex'] == 0 ? 'Male' : 'Female') : '' ?></div>
                                        <div id="card-em" class="desc fs-4"><?= htmlspecialchars($row['email']) ?></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row user-stats text-center">
                                        <div class="col">
                                            <div class="number"><?php echo $count1['total_appointments']; ?></div>
                                            <div class="title">Events Added</div>
                                        </div>
                                        <div class="col">
                                            <div class="number"><?php echo $count2['completed_appointments']; ?></div>
                                            <div class="title">Events Done</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>
    <?php include("partial/script.php"); ?>

    <script>
        let currentField = null;

        function validateNumber(field) {
            let inputField = document.getElementById(field);
            inputField.value = inputField.value.replace(/[^0-9]/g, '');
        }

        let isEditing = false;

        function enableEditing() {
            const editButton = document.querySelector(".edit-btn");

            if (isEditing) {
                document.querySelectorAll("input[readonly], textarea[readonly], select[readonly]").forEach(input => {
                    input.classList.remove("editable");
                    input.setAttribute("readonly", "true");
                });

                document.querySelectorAll(".input-group-text.icon-active").forEach(icon => {
                    icon.classList.remove("icon-active");
                    icon.classList.add("disabled");
                    icon.style.cursor = "not-allowed";
                });

                const fileInput = document.getElementById("profileFileInput");
                fileInput.classList.add("disabled");
                fileInput.setAttribute("disabled", "true");

                editButton.textContent = "Edit";
                editButton.style.backgroundColor = "#00A33C";
                editButton.style.color = "white";
            } else {
                document.querySelectorAll("input[readonly], textarea[readonly], select[readonly]").forEach(input => {
                    input.classList.add("editable");
                });

                document.querySelectorAll(".input-group-text.disabled").forEach(icon => {
                    icon.classList.remove("disabled");
                    icon.classList.add("icon-active");
                    icon.style.cursor = "pointer";
                });

                const fileInput = document.getElementById("profileFileInput");
                fileInput.classList.remove("disabled");
                fileInput.removeAttribute("disabled");

                editButton.textContent = "Cancel";
                editButton.style.backgroundColor = "#FD3D3D";
                editButton.style.color = "white";
            }

            isEditing = !isEditing;
        }


        function openEditModal(field) {
            currentField = field;
            let currentValue = document.getElementById(field).value;
            let modalBody = document.getElementById("editFieldInputContainer");

            if (field === 'sex') {
                modalBody.innerHTML = `
            <select class="form-control" id="editFieldInput">
                <option value="0" ${currentValue === 'Male' ? 'selected' : ''}>Male</option>
                <option value="1" ${currentValue === 'Female' ? 'selected' : ''}>Female</option>
            </select>
        `;
            } else if (field === 'telno') {
                modalBody.innerHTML = `<input type="text" class="form-control" id="editFieldInput" value="${currentValue}" oninput="validateNumberInput(event)" maxlength="15" />`;
            } else if (field === 'age') {
                modalBody.innerHTML = `<input type="text" class="form-control" id="editFieldInput" value="${currentValue}" oninput="validateNumberInput(event)" maxlength="2" />`;
            } else {
                modalBody.innerHTML = `<input type="text" class="form-control" id="editFieldInput" value="${currentValue}" />`;
            }

            new bootstrap.Modal(document.getElementById("editModal")).show();
        }

        function validateNumberInput(event) {
            event.target.value = event.target.value.replace(/[^0-9]/g, '');
        }

        document.getElementById("saveChangesBtn").addEventListener("click", function() {
            const newValue = document.getElementById("editFieldInput").value;

            if (currentField) {
                let finalValue = newValue;
                if (currentField === 'sex') {
                    finalValue = newValue === 'Male' ? 0 : 1;
                }

                document.getElementById(currentField).value = finalValue;

                fetch("modal/edit_info.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            field: currentField,
                            value: finalValue,
                            userId: <?= $row['CID'] ?>
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Updated successfully!");

                            if (currentField === 'fname' || currentField === 'lname') {
                                document.getElementById("card-fl").textContent =
                                    `${document.getElementById("fname").value} ${document.getElementById("lname").value}`;
                            } else if (currentField === 'age' || currentField === 'sex') {
                                document.getElementById("card-as").textContent =
                                    `${document.getElementById("age").value} ${document.getElementById("sex").value}`;
                            } else if (currentField === 'email') {
                                document.getElementById("card-em").textContent = newValue;
                            } else {
                                alert("Update failed.");
                            }
                        }
                    });

                bootstrap.Modal.getInstance(document.getElementById("editModal")).hide();
            }
        });


        function openPasswordModal() {
            new bootstrap.Modal(document.getElementById("passwordModal")).show();
        }

        document.getElementById("savePasswordChangesBtn").addEventListener("click", function() {
            const newPassword = document.getElementById("newPassword").value;
            const verifyPassword = document.getElementById("verifyPassword").value;

            if (newPassword === verifyPassword) {
                fetch("modal/change_password.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            userId: <?= $row['CID'] ?>,
                            password: newPassword
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Password updated successfully!");
                        } else {
                            alert("Password update failed.");
                        }
                    })
                    .catch(error => alert("Error: " + error));

                bootstrap.Modal.getInstance(document.getElementById("passwordModal")).hide();
            } else {
                alert("Passwords do not match. Please try again.");
            }
        });

        function openFileModal(event) {
            const file = event.target.files[0];

            if (file) {
                document.getElementById('fileName').textContent = `Selected file: ${file.name}`;

                new bootstrap.Modal(document.getElementById('fileModal')).show();
            }
        }

        function uploadFile() {
            const fileInput = document.getElementById('profileFileInput');
            const file = fileInput.files[0];

            if (!file) {
                alert("No file selected.");
                return;
            }

            const formData = new FormData();
            formData.append("profile", file);
            formData.append("userId", <?= $row['CID'] ?>);

            fetch("modal/profile_upload.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Profile picture updated successfully!");
                        // Update the profile image on the page
                        document.querySelector(".avatar-img").src = data.newImagePath;
                    } else {
                        alert("Failed to update profile picture.");
                    }
                })
                .catch(error => {
                    console.error("Error uploading file:", error);
                    alert("Error uploading file.");
                });

            // Close the modal
            bootstrap.Modal.getInstance(document.getElementById('fileModal')).hide();
        }
    </script>

</body>

</html>