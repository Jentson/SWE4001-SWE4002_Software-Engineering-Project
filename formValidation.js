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
    var session = document.forms["addStudent"]["session"].value;
    var address = document.forms["addStudent"]["address"].value;
    var programme = document.forms["addStudent"]["programme"].value;
    var phone = document.forms["addStudent"]["phone"].value;
    var semester = document.forms["addStudent"]["semester"].value;
    var major = document.forms["addStudent"]["major"].value;

    var idError = validateField(stud_id, /^[Jj]\d{8}$/, "Student ID must start with 'J' followed by 8 digits.");
    var emailError = validateField(stud_email, /^[Jj]\d{8}@student\.newinti\.edu\.my$/, "Please enter a valid email address.");
    var nameError = validateField(stud_name, /^[a-zA-Z0-9\s]+$/, "Special characters are not allowed in the name.");
    var passError = validateField(stud_pass, /^[a-zA-Z0-9]+$/, "Special characters are not allowed in the password.");
    var sessionError = validateField(session, /^[a-zA-Z]{1,15}$/, "Session must be between 1 and 15 characters.");
    var addressError = validateField(address, /^[a-zA-Z0-9\s\.,\-]{1,80}$/, "Address must be between 1 and 80 characters.");
    var programmeError = validateField(programme, /^[a-zA-Z]{1,15}$/, "Programme must be between 1 and 15 characters.");
    var phoneError = validateField(phone, /^60\d{9,10}$/, "Phone must start with '60' followed by 9 or 10 digits.");
    var semesterError = validateField(semester, /^\d{1,2}$/, "Semester must be 1 or 2 digits.");
    var majorError = validateField(major, /^[a-zA-Z\s]{1,30}$/, "Major must be between 1 and 30 characters.");
    var departmentError = "";
    if (department === "") {
        departmentError = "Please select a department.";
    }

    if (idError !== "" || nameError !== "" || emailError !== "" || passError !== "" || 
        sessionError !== "" || addressError !== "" || programmeError !== "" ||
        phoneError !== "" || semesterError !== "" || majorError !== "" || departmentError !== "") {
        alert(idError + "\n" + nameError + "\n" + emailError + "\n" + passError + "\n" +
              sessionError + "\n" + addressError + "\n" + programmeError + "\n" +
              phoneError + "\n" + semesterError + "\n" + majorError + "\n" + departmentError);
        return false;
    }
    return true;
}
