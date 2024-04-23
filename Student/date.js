window.onload = function() {
    var today = new Date();
    var pastWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);

    // Format dates to 'YYYY-MM-DD' for HTML date input compatibility
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

    // Set the min and max attributes for the start date
    document.getElementById('startDate').min = formatDate(pastWeek);
    document.getElementById('endDate').min = formatDate(pastWeek);
    
    // Optionally, set the end date min or max if necessary
    // document.getElementById('endDate').min = formatDate(today); // For example
};
