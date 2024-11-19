function searchEvents(query) {
    $.ajax({
        url: 'modal/search_tables.php',
        type: 'GET',
        data: {
            search: query
        },
        success: function(response) {
            $('#event-table-body').html(response);
        }
    });
}

document.getElementById('status-filter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const rows = document.querySelectorAll('.event-row');
    let hasVisibleRow = false;

    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');

        if (selectedStatus === '' || rowStatus === selectedStatus) {
            row.style.display = '';
            hasVisibleRow = true;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('no-events-row').style.display = hasVisibleRow ? 'none' : '';
});