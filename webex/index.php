<!DOCTYPE html>
<html>

<head>
   <meta charset="utf8">

   <title>Custom Webex Page</title>
   <link rel="stylesheet" href="https://code.s4d.io/widget-recents/production/main.css">
   <link rel="stylesheet" href="https://code.s4d.io/widget-space/production/main.css">

   <script src="https://code.s4d.io/widget-space/production/bundle.js"></script>
   <script src="https://code.s4d.io/widget-recents/production/bundle.js"></script>

   <!-- Add Toastr library for notifications -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

   <style>
      html {
         height: 100%;
      }

      body {
         font-family: 'Arial', sans-serif;
         background: url('webex.jpg'); 
         background-size: cover;
         margin: 0;
         padding: 0;
         display: flex;
         justify-content: center;
         align-items: center;
         min-height: 100vh;
         flex-direction: column; 
      }

      header {
         background-color: #3498db; 
         color: #ffffff; 
         padding: 10px;
         text-align: center;
         border-radius: 10px;
         margin-bottom: 20px;
         font-size: 24px;
      }

      #access-token-form {
         background-color: #3498db; 
         color: #ffffff; 
         padding: 30px;
         text-align: center;
         border-radius: 10px;
      }

      #access-token-form label {
         font-size: 18px;
      }

      #access-token {
         width: 300px;
         padding: 10px;
         font-size: 16px;
      }

      #widgets-container {
         display: none;
         padding: 20px;
         text-align: center;
      }

      #recents {
         width: 300px;
         height: 500px;
         float: left;
         background-color: #ffffff; 
         border: 1px solid #ddd; 
         border-radius: 5px;
         margin-right: 20px;
      }

      #space {
         width: 750px;
         height: 500px;
         background-color: #ffffff; 
         border: 1px solid #ddd; 
         border-radius: 5px;
      }
      #scheduler {
            flex: 1;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: auto;
            padding: 20px;
            margin-top: 20px;
        }

        #scheduling-form {
            text-align: center;
            padding: 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
   </style>
</head>

<body>

      <!-- Header -->
   <header id="welcomeHeader">
    Welcome to Webex
    </header>

   <!-- Access Token Input Form -->
   <div id="access-token-form">
      <label for="access-token">Enter Webex Teams Access Token:</label>
      <br>
      <input type="text" id="access-token" placeholder="Paste your token here">
      <br>
      <button onclick="setAccessToken()">Submit</button>
   </div>

   <!-- Recents and Space Widgets -->
   <div id="widgets-container">
      <div id="recents"></div>
      <div id="space"></div>
      <div id="scheduler">
            <div id="scheduling-form">
                <label for="meeting-topic">Meeting Topic:</label>
                <br>
                <input type="text" id="meeting-topic" name="meeting-topic">
                <br>
                <label for="meeting-datetime">Select Meeting Date and Time:</label>
                <br>
                <input type="datetime-local" id="meeting-datetime" name="meeting-datetime">
                <br>
                <button onclick="scheduleMeeting()">Schedule Meeting</button>
            </div>
        </div>
   </div>

   <script>
      function setAccessToken() {
         const accessTokenInput = document.getElementById('access-token');
         const token = accessTokenInput.value.trim();

         if (token) {
            updateHeader(); // Change the header text
            hideAccessTokenForm();
            initializeWidgets(token);
         } else {
            toastr.error('Please enter a valid access token.');
         }
      }

      function hideAccessTokenForm() {
         const accessTokenForm = document.getElementById('access-token-form');
         accessTokenForm.style.display = 'none';

         const widgetsContainer = document.getElementById('widgets-container');
         widgetsContainer.style.display = 'block';
      }

      function initializeWidgets(token) {
         // Init the Recents widget
         const recentsElement = document.getElementById('recents');
         webex.widget(recentsElement).recentsWidget({
            accessToken: token,
            onEvent: callback
         });

          // Init the Scheduler widget
          const schedulerElement = document.getElementById('scheduler');
            const schedulerWidget = webex.widget(schedulerElement).schedulerWidget({
                accessToken: token,
                containerEl: schedulerElement,
                destinationType: 'personId',
                destinationId: 'YourDestinationId', 
            });


         function callback(type, event) {
            if (type !== "rooms:selected") {
               console.log("new event: " + type);
               toastr.info('Event Received', type);
               return;
            }

            let selectedRoom = event.data.id;
            console.log("room " + selectedRoom + " was selected");

            let spaceElement = document.getElementById('space');

            // Remove existing 'Space' widget (if any)
            try {
               webex.widget(spaceElement).remove().then(function (removed) {
                  if (removed) {
                     console.log('removed!');
                  }
               });
            } catch (err) {
               console.error('could not remove Space widget :-(, continuing...');
            }

            // Inject a new 'Space' widget with the selected room
            webex.widget(spaceElement).spaceWidget({
               accessToken: token,
               destinationType: "spaceId",
               destinationId: selectedRoom,
               activities: { "files": true, "meet": true, "message": true, "people": true },
               initialActivity: 'message',
               secondaryActivitiesFullWidth: false
            });
            spaceWidget.create();
         }

          // Create the widgets
          recentsWidget.create();
            schedulerWidget.create();
      }

      function scheduleMeeting() {
            const meetingTopicInput = document.getElementById('meeting-topic');
            const meetingDatetimeInput = document.getElementById('meeting-datetime');

            const meetingTopic = meetingTopicInput.value.trim();
            const meetingDatetime = meetingDatetimeInput.value;

            // Use the meetingTopic and meetingDatetime for scheduling logic
            console.log('Meeting Topic:', meetingTopic);
            console.log('Scheduled meeting for:', meetingDatetime);

            // You can perform further actions here based on the input values

            // For demonstration purposes, let's display a notification
            toastr.success('Meeting scheduled successfully!', 'Success');
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Your existing DOMContentLoaded logic
        });

      function updateHeader() {
         const welcomeHeader = document.getElementById('welcomeHeader');
         welcomeHeader.innerText = 'Hi, Firdaus';
      }

      
   </script>

</body>

</html>