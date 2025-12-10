const urlParams = new URLSearchParams(window.location.search);
const selected_year = urlParams.get("year");
const selected_month = urlParams.get("month");

document.addEventListener("DOMContentLoaded", function () {
    let events = []; // Initialize an empty array for events.

    const v = document.getElementById("calendar");

    const r = new Calendar(v, {
        initialView: "dayGridMonth",
    initialDate: `${initialYear}-${String(initialMonth + 1).padStart(2, "0")}-01`, // Use initialDate to set the year and month
    // ...
        events: events,
        plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
        editable: true,
        dateClick: function (e) {
            const clickedDate = e.date;
            const year = clickedDate.getFullYear();
            const month = String(clickedDate.getMonth() + 1).padStart(2, "0"); // Adding 1 because months are zero-based
            const day = String(clickedDate.getDate()).padStart(2, "0");

            const formattedDate = `${year}-${month}-${day}`;
            // Show your modal popup here
            showPRICEBOOK_MODAL(formattedDate);
        },
        headerToolbar: {
            left: "prev,next",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek", // Include the views you want
        },
        buttonText: {
            // today: "Today",
            month: "Month",
            week: "Week",
            day: "Day",
            list: "List",
        },
        eventContent: function (arg) {
            // Assign custom CSS class to events based on their type
            const eventColorClass = arg.event.extendedProps.calendar;
            const eventClassName = `fc-event ${eventColorClass}`;
            return {
                html: `<div class="${eventClassName}">${arg.timeText}</div>`,
            };
        },
    });

    // Assuming you have checkboxes with class "input-filter" for filtering
    const checkboxes = document.querySelectorAll(".input-filter");

    // Function to handle the filtering based on checkboxes
    function filterEvents() {
        const selectedFilters = Array.from(checkboxes)
            .filter((checkbox) => checkbox.checked)
            .map((checkbox) => checkbox.getAttribute("data-value"));

        const filteredEvents = events.filter((event) => {
            if (selectedFilters.length === 0) {
                // If no checkboxes are checked, show all events
                return true;
            } else {
                return selectedFilters.includes(
                    event.extendedProps.calendar.toLowerCase()
                );
            }
        });

        r.removeAllEvents(); // Remove existing events from the calendar
        r.addEventSource(filteredEvents); // Add filtered events to the calendar
    }
    // Attach a click event listener to each checkbox to trigger the filtering
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("click", filterEvents);
    });

    // Initial filtering on page load (if needed)
    filterEvents();

    r.render();

    // Function to assign random colors to checkboxes
    function assignRandomColors() {
        checkboxes.forEach((checkbox) => {
            const randomColor = getRandomColor();
            checkbox.nextElementSibling.style.color = randomColor;
        });
    }

    // Call the function to assign random colors initially
    assignRandomColors();

    // Function to generate a random color
    function getRandomColor() {
        const letters = "0123456789ABCDEF";
        let color = "#";
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
});
