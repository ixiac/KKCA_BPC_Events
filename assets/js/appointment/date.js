console.log(unavailableDates);

function disableUnavailableDates() {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");

    unavailableDates.forEach(range => {
        const start = new Date(range.start_date);
        const end = new Date(range.end_date);

        startDateInput.addEventListener("input", () => {
            const selectedDate = new Date(startDateInput.value);
            if (selectedDate >= start && selectedDate <= end) {
                alert("This date is unavailable. Please select a different start date.");
                startDateInput.value = "";
            }
        });

        endDateInput.addEventListener("input", () => {
            const selectedDate = new Date(endDateInput.value);
            if (selectedDate >= start && selectedDate <= end) {
                alert("This date is unavailable. Please select a different end date.");
                endDateInput.value = "";
            }
        });
    });
}

disableUnavailableDates();

function setEndDateMin() {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");

    endDateInput.min = startDateInput.value;
}