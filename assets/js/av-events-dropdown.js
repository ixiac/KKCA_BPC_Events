function updateDropdown(text, selectedItem) {
    // Update the button text
    document.getElementById('dropdownMenuButton').innerText = text;

    // Remove 'active' class from all items and add to selected
    const items = document.querySelectorAll('.dropdown-item');
    items.forEach(item => {
        item.classList.remove('active');
    });
    selectedItem.classList.add('active');

    // Update the event list based on selection
    const eventList = document.getElementById('eventList');
    if (text === 'School') {
        eventList.innerHTML = `
            <ul>
                <li>Parent-Teacher Conferences</li>
                <li>School Assemblies</li>
                <li>Graduation Ceremonies</li>
                <li>Sports Tournaments</li>
                <li>Cultural Festivals and Fairs</li>
                <li>Open Houses</li>
                <li>Field Trips</li>
                <li>Science and Art Exhibitions</li>
                <li>Fundraising Events</li>
                <li>Workshops and Educational Seminars</li>
            </ul>`;
    } else {
        eventList.innerHTML = `
            <ul>
                <li>Worship Services</li>
                <li>Bible Study Sessions</li>
                <li>Youth and Family Retreats</li>
                <li>Community Outreach Programs</li>
                <li>Special Holiday Services (Christmas, Easter, etc.)</li>
                <li>Workshops and Seminars</li>
                <li>Concerts and Music Events</li>
                <li>Mission and Volunteer Opportunities</li>
                <li>Prayer Meetings</li>
                <li>Social Gatherings and Potlucks</li>
            </ul>`;
    }
}