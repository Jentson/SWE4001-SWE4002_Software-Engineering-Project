// Generic function to validate a field
function validateField(value, regex, errorMessage) {
    if (value === "") {
        return errorMessage; // Return error message if value is empty
    } else {
        if (!regex.test(value)) {
            return errorMessage; // Return error message if value doesn't match regex
        }
    }
    return ""; // Return empty string if no error
}

// Function to validate staff login
function validateStaffLogin() {
    var staff_id = document.forms["StaffLogin"]["Staffid"].value;
    var password = document.forms["StaffLogin"]["Staffpass"].value;

    var idError = validateField(staff_id, /^\d{6}$/, "Please enter a valid Staff ID (6 digits).");
    var passError = validateField(password, /^[a-zA-Z0-9]+$/, "Special characters are not allowed in the password.");

    if (idError !== "" || passError !== "") {
        alert(idError + "\n" + passError);
        return false;
    }
    return true;
}

// Function to validate student login
function validateStudentLogin() {
    var student_id = document.forms["StudentLogin"]["Studentid"].value;
    var password = document.forms["StudentLogin"]["Studentpass"].value;

    var idError = validateField(student_id, /^[Jj][0-9]{8}$/, "Please enter a valid Student ID.");
    var passError = validateField(password, /^[a-zA-Z0-9]+$/, "Special characters are not allowed in the password.");

    if (idError !== "" || passError !== "") {
        alert(idError + "\n" + passError);
        return false;
    }
    return true;
}

// Function to validate adding staff
function validateAddStaff() {
    var staff_id = document.forms["addStaff"]["staff_id"].value;
    var staff_name = document.forms["addStaff"]["staff_name"].value;
    var staff_email = document.forms["addStaff"]["staff_email"].value;
    var staff_pass = document.forms["addStaff"]["staff_pass"].value;

    var idError = validateField(staff_id, /^\d{6}$/, "Staff ID must be 6 digits.");
    var nameError = validateField(staff_name, /^[a-zA-Z0-9\s]+$/, "Special characters are not allowed in the name.");
    var emailError = validateField(staff_email, /^[^\s@]+@newinti\.edu\.my$/, "Please enter a valid email address.");
    var passError = validateField(staff_pass, /^[a-zA-Z0-9]+$/, "Special characters are not allowed in the password.");

    if (idError !== "" || nameError !== "" || emailError !== "" || passError !== "") {
        alert(idError + "\n" + nameError + "\n" + emailError + "\n" + passError);
        return false;
    }
    return true;
}

// Function to validate adding student
function validateAddStudent() {
    var stud_id = document.forms["addStudent"]["stud_id"].value;
    var stud_name = document.forms["addStudent"]["stud_name"].value;
    var stud_email = document.forms["addStudent"]["stud_email"].value;
    var stud_pass = document.forms["addStudent"]["stud_pass"].value;

    var idError = validateField(stud_id, /^[Jj]\d{8}$/, "Student ID must start with 'J' followed by 8 digits.");
    var emailError = validateField(stud_email, /^[Jj]\d{8}@student\.newinti\.edu\.my$/, "Please enter a valid email address.");
    var nameError = validateField(stud_name, /^[a-zA-Z0-9\s]+$/, "Special characters are not allowed in the name.");
    var passError = validateField(stud_pass, /^[a-zA-Z0-9]+$/, "Special characters are not allowed in the password.");


    if (idError !== "" || nameError !== "" || emailError !== "" || passError !== "") {
        alert(idError + "\n" + nameError + "\n" + emailError + "\n" + passError);
        return false;
    }
    return true;
}
