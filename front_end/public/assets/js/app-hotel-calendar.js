const urlParams = new URLSearchParams(window.location.search);
const getHotelId = urlParams.get("id");
const getRoomTypeId = urlParams.get("rtype_id");

let date = new Date,
  nextDay = new Date((new Date).getTime() + 864e5),
  nextMonth = 11 === date.getMonth() ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1),
  prevMonth = 11 === date.getMonth() ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);

let events = []; // Initialize an empty array for events.

document.addEventListener("DOMContentLoaded", function () {
  // Use Fetch API to load the JSON file.
  fetch("engine/json/__JSON_hotel_calendar_pricebook_details.php?hotel_id=" + getHotelId + '&roomTypeFilter=' + getRoomTypeId)
    .then((response) => response.json())
    .then((data) => {
      // Now you can work with the events data as before.
      events = data;
      const v = document.getElementById("calendar");

      const r = new Calendar(v, {
        initialView: "dayGridMonth",
        dayMaxEvents: 4,
        events: events,
        plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
        editable: true,
        dateClick: function (e) {
          const clickedDate = e.date;
          const year = clickedDate.getFullYear();
          const month = String(clickedDate.getMonth() + 1).padStart(2, '0'); // Adding 1 because months are zero-based
          const day = String(clickedDate.getDate()).padStart(2, '0');

          const formattedDate = `${year}-${month}-${day}`;
						
			var room_type_id = $('#roomTypeFilter').val();
			// Show your modal popup here
			showPRICEBOOK_MODAL(formattedDate, room_type_id);
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
        eventClassNames: function ({
          event: e
        }) {
          const classNames = [];

          // Check if 'calendar' property exists in the extendedProps
          if (e.extendedProps && e.extendedProps.calendar) {
            const calendarColorMap = {
              'rooms': 'fc-event-success',
              'amenities': 'fc-event-warning',
              // Add more calendar-color mappings as needed
            };

            const calendarClassName = calendarColorMap[e.extendedProps.calendar];

            if (calendarClassName) {
              classNames.push(calendarClassName);
            }
          }

          return classNames;
        }
      });

      // Assuming you have checkboxes with class "input-filter" for filtering
      const checkboxes = document.querySelectorAll('.input-filter');
      const viewAllCheckbox = document.getElementById('selectAll'); // Assuming you have an "View All" checkbox

      // Function to handle the "View All" checkbox
      function handleViewAllCheckbox() {
        const isChecked = viewAllCheckbox.checked;

        if (isChecked) {
          r.addEventSource(events);
          // Iterate through the selected checkboxes and uncheck them
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = true;
          });
          viewAllCheckbox.checked = true;
        } else {
          r.removeAllEvents();
          // Iterate through the selected checkboxes and uncheck them
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
          });
        }
      }

      // Attach a click event listener to the "View All" checkbox
      viewAllCheckbox.addEventListener('click', handleViewAllCheckbox);

      // Function to handle the filtering based on checkboxes
      function filterEvents() {

        const selectedFilters = Array.from(checkboxes)
          .filter(checkbox => checkbox.checked && checkbox.id !== 'selectAll') // Exclude the "View All" checkbox
          .map(checkbox => checkbox.getAttribute('data-value'));

        if (selectedFilters != 'rooms,amenities') {
          viewAllCheckbox.checked = false;
        } else {
          viewAllCheckbox.checked = true;
        }
        const filteredEvents = events.filter(event => {
          if (selectedFilters.length === 0 || selectedFilters.includes('all')) {
            // If "View All" is checked or no specific filters are selected, show all events
            if (selectedFilters.length === 0) {
              return false;
            } else {
              return true;
            }
          } else {
            // Adjust this condition based on your data structure and how you want to filter
            return selectedFilters.includes(event.extendedProps.calendar.toLowerCase());
          }
        });

        r.removeAllEvents(); // Remove existing events from the calendar
        r.addEventSource(filteredEvents); // Add filtered events to the calendar
      }

      // Attach a click event listener to each checkbox to trigger the filtering
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('click', filterEvents);
      });

      // Initial filtering on page load (if needed)
      filterEvents();

      r.render();

    }).catch((error) => {
      console.error("Error loading events:", error);
    });
});
