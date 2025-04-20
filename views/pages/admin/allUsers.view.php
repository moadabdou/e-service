<style>
        /* Style for the container holding multiple cards (e.g., the 'users grid') */
.users-grid-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Responsive grid */
  gap: 20px; /* Space between cards */
  padding: 20px; /* Padding around the grid area */
}

.user-card {
  background-color: #ffffff; /* White card background */
  border-radius: 8px; /* Rounded corners like buttons */
  border: 1px solid #e9ecef; /* Subtle border */
  padding: 16px;
  display: flex;
  align-items: center; /* Vertically align items */
  gap: 15px; /* Space between avatar, info, and button */
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* Optional subtle shadow */
  transition: box-shadow 0.2s ease-in-out;
}

.user-card:hover {
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slightly enhance shadow on hover */
}

.user-card-avatar img {
  width: 50px; /* Adjust size as needed */
  height: 50px;
  border-radius: 50%; /* Make it circular */
  object-fit: cover; /* Ensure image covers the area nicely */
}

.user-card-info {
  flex-grow: 1; /* Allow info section to take available space */
}

.user-card-name {
  margin: 0 0 4px 0; /* Spacing below name */
  font-size: 1rem; /* Adjust as needed */
  font-weight: 600; /* Make name slightly prominent */
  color: #2A3547; /* Dark text color */
}

.user-card-joined {
  margin: 0;
  font-size: 0.875rem; /* Smaller text for date */
  color: #5A6A85; /* Lighter text color */
}

/* You could add an icon here too:
.user-card-joined::before {
  content: '\f073'; // Example Font Awesome calendar icon
  font-family: 'Font Awesome 5 Free';
  font-weight: 900;
  margin-right: 5px;
}
*/

.user-card-button {
  background-color: #5D87FF; /* Match existing primary blue */
  color: #ffffff; /* White text */
  border: none;
  padding: 8px 16px; /* Adjust padding */
  border-radius: 8px; /* Match card/button roundness */
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s ease;
  white-space: nowrap; /* Prevent button text wrapping */
}

.user-card-button:hover {
  background-color: #4f74e6; /* Slightly darker blue on hover */
}
</style>

<div class="all-users-content">

    <div class="all-users-header">

        <?= $filters ?>

    </div>

    <div class="users-grid">

        <?= $users ?>
    
    </div>


</div>




