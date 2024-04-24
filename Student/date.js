window.onload = function() {
    var today = new Date();
    var pastWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);
    var maxDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 30); // Extend max date to 30 days from today

    // Function to format dates to 'YYYY-MM-DD' for HTML date input compatibility
    var formatDate = function(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    };

    var startDateInput = document.getElementById('startDate');
    var endDateInput = document.getElementById('endDate');

    // Initialize inputs
    startDateInput.min = formatDate(pastWeek);
    startDateInput.max = formatDate(maxDate);
    endDateInput.min = formatDate(pastWeek);
    endDateInput.max = formatDate(maxDate);

    // Event listener to update the end date's min attribute when start date changes
    startDateInput.addEventListener('change', function() {
        endDateInput.min = startDateInput.value;
        if (endDateInput.value && endDateInput.value < startDateInput.value) {
            endDateInput.value = startDateInput.value;
        }
    });

    // Event listener to update the start date's max attribute when end date changes
    endDateInput.addEventListener('change', function() {
        startDateInput.max = endDateInput.value;
        if (startDateInput.value && startDateInput.value > endDateInput.value) {
            startDateInput.value = endDateInput.value;
        }
    });
};
