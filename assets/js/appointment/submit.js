function confirmSubmission() {
    const eventName = document.getElementById('event_name').value.trim();
    const category = document.getElementById('category-select').value.trim();
    const venue = document.getElementById('venue-select').value.trim();
    const startDate = document.getElementById('start_date').value.trim();
    const endDate = document.getElementById('end_date').value.trim();
    const refNo = document.getElementById('reference-no').value.trim();
    const refImg = document.getElementById('exampleFormControlFile1').value.trim();

    if (!eventName || !category || !venue || !startDate || !endDate || !refNo || !refImg) {
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please fill in all fields before submitting.',
            confirmButtonColor: '#d33'
        });
    } else {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this appointment?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00A33C',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('appointmentForm').submit();
            }
        });
    }
}