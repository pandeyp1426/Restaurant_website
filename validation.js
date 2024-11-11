function validateForm() {
    var items = document.querySelectorAll('input[name="selectedItems[]"]:checked');
    var allFieldsValid = true;
    var fieldsFilled = true; // To track if all personal information fields are filled

    // Define the inputs
    var fname = document.getElementById("fname");
    var lname = document.getElementById("lname");
    var street = document.getElementById("street");
    var city = document.getElementById("city");
    var state = document.getElementById("state");
    var zip = document.getElementById("zip");
	var country = document.getElementById("country");

    // Function to check each field and set border color
    function validateField(field) {
        if (!field.value.trim()) {
            field.style.borderColor = "red";
            fieldsFilled = false;
            allFieldsValid = false;
        } else {
            field.style.borderColor = "grey";
        }
    }
    
    // Validate each field
    validateField(fname);
    validateField(lname);
    validateField(street);
    validateField(city);
	validateField(country);

    // If an item is selected but personal info fields are empty, alert the user first
    if (items.length > 0 && !fieldsFilled) {
        alert('Please fill out the order form.');
        return false; // Stop the form submission
    }
    
    // Now check if any items are selected
    if (items.length === 0) {
        alert('Please select at least one item.');
        allFieldsValid = false;
    }

    // Validate the state field
    var stateRegex = /^[A-Za-z]{2}$/; // State must have 2 letters
    if (!stateRegex.test(state.value.trim())) {
        state.style.borderColor = "red";
        alert("State must contain 2 letters");
        allFieldsValid = false;
    } else {
        state.style.borderColor = "grey";
    }

    // Validate the ZIP field
    var zipRegex = /^[0-9]{5}$/; // ZIP must have 5 digits
    if (!zipRegex.test(zip.value.trim())) {
        zip.style.borderColor = "red";
        alert("ZIP must contain 5 numbers");
        allFieldsValid = false;
    } else {
        zip.style.borderColor = "grey";
    }

    // Check the payment method last
    var paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
    var paymentMethodSelected = false;
    paymentMethods.forEach(function(method) {
        if (method.checked) {
            paymentMethodSelected = true;
        }
    });
    
    // If a payment method is not selected, alert the user
    if (!paymentMethodSelected && allFieldsValid) {
        alert('Please select a payment method.');
        allFieldsValid = false;
    }

    return allFieldsValid; // Return the flag, if it's false, form submission will stop
}

document.querySelector('form').onsubmit = validateForm;
