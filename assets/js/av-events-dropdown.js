// function updateDropdown(text, selectedItem) {
//     // Update the button text
//     document.getElementById('dropdownMenuButton').innerText = text;

//     // Remove 'active' class from all items and add to selected
//     const items = document.querySelectorAll('.dropdown-item');
//     items.forEach(item => {
//         item.classList.remove('active');
//     });
//     selectedItem.classList.add('active');

//     // Update the event list based on selection
//     const eventList = document.getElementById('eventList');
    
//     // Toggle between 'Your Events' and 'Public Events'
//     if (text === 'Public Events') {
//         eventList.innerHTML = `
//             <ul>
//                 <?php foreach ($public_events as $event) : ?>
//                     <li><?php echo htmlspecialchars($event); ?></li>
//                 <?php endforeach; ?>
//             </ul>`;
//     } else {
//         eventList.innerHTML = `
//             <ul>
//                 <?php foreach ($user_events as $event) : ?>
//                     <li><?php echo htmlspecialchars($event); ?></li>
//                 <?php endforeach; ?>
//             </ul>`;
//     }
// }