function openEditModal(APID, eventName, category, startDate, endDate, venue, status) {
    document.getElementById('editEventId').value = APID;
    document.getElementById('editEventName').value = eventName;
    document.getElementById('editCategory').value = category;
    document.getElementById('editStartDate').value = startDate;
    document.getElementById('editEndDate').value = endDate;
    document.getElementById('editVenue').value = venue;

    var editEventModal = new bootstrap.Modal(document.getElementById('editEventModal'));
    editEventModal.show();
}

function editTransacModal(APID, ref_no, ref_img) {
    document.getElementById('ref_no').value = ref_no;
    document.getElementById('current_ref_img').textContent = ref_img;
    document.getElementById('current_image').value = ref_img;
    document.getElementById('APID').value = APID;

    $('#editTransacModal').modal('show');
}