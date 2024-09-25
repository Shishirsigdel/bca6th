<?php 
session_start();
include('includes/config.php');
error_reporting(0);

{
}
?>

    <head>
<style>
    #takeanote{
        background-color: lightgrey;
    }

</style>
    </head>
    <d>
    <div id="takeanote">

    
    <h2>Take a Note</h2>
</div>
<div id="textarea">

</div>
    <textarea id="noteArea"  placeholder="Type your note here..." style="height: 500px" background color="grey"></textarea><br>
    </div>
    <button id="saveButton">Save Note</button>

    <div id="notesList">
        <h3>Your Notes:</h3>
        <ul id="notes"></ul>
    </div>

    <script>
        const noteArea = document.getElementById('noteArea');
        const saveButton = document.getElementById('saveButton');
        const notesList = document.getElementById('notes');

        // Function to save the note  
        function saveNote() {
            const noteText = noteArea.value.trim();
            if (noteText) {
                const listItem = document.createElement('li');
                listItem.textContent = noteText;
                notesList.appendChild(listItem);
                noteArea.value = '200'; // Clear the text area  
            } else {
                alert('Please enter a note before saving.');
            }
        }

        // Attach the event handler to the button  
        saveButton.addEventListener('click', saveNote);
    </script>
            

           

        
    </body>
</html>