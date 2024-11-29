function updateDownPayment() {
    const category = document.getElementById("category-select").value;
    const downPaymentText = document.getElementById("down-payment-text");

    let downPayment;

    switch (category) {
        case "Wedding":
            downPayment = "₱5,000 - ₱15,000";
            break;
        case "Baptism":
            downPayment = "₱1000 - ₱1,500";
            break;
        case "Celebrations":
            downPayment = "₱2,000 - ₱5,000";
            break;
        case "Funerals":
            downPayment = "₱3,000 - ₱6,000";
            break;
        case "Community Outreach":
            downPayment = "₱2,000 - ₱5,000";
            break;
        case "Youth Fellowship":
            downPayment = "₱1,000 - ₱5,000";
            break;
        default:
            downPayment = "Please select a category";
            break;
    }
    downPaymentText.textContent = `Down payment: ${downPayment}`;
}