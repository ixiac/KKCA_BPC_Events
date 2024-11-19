<?php
if (isset($_SESSION['swal_message'])) {
    $swalType = $_SESSION['swal_message']['type'];
    $swalTitle = $_SESSION['swal_message']['title'];
    $swalMessage = isset($_SESSION['swal_message']['message']) ? $_SESSION['swal_message']['message'] : '';

    echo "<script>
                Swal.fire({
                    icon: '$swalType',
                    title: '$swalTitle',
                    text: '$swalMessage',
                    confirmButtonColor: '#00A33C',
                    confirmButtonText: 'OK'
                });
            </script>";

    unset($_SESSION['swal_message']);
}
?>