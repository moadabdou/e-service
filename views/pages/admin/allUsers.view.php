<style>

.user-card {
    display: flex; /* Arrange items horizontally */
    align-items: center; /* Vertically center items */
    background-color: #ffffff; /* White card background */
    border: 1px solid #e5eaef; /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 15px 20px; /* Internal spacing */
    width: 100%; /* Make card take available width */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); /* Subtle shadow */
    transition: box-shadow 0.3s ease; /* Smooth transition for hover */
    margin-bottom: 10px;
}

.user-card:hover {
     box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Slightly more pronounced shadow on hover */
}

.user-card-avatar {
    margin-right: 15px; /* Space between avatar and info */
    flex-shrink: 0; /* Prevent avatar from shrinking */
}

.user-card-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%; /* Circular avatar */
    object-fit: cover; /* Ensure image covers the circle nicely */
    display: block; /* Remove extra space below image */
}

.user-card-info {
    flex-grow: 1; /* Allow info section to take remaining space */
    margin-right: 15px; /* Space between info and button */
}

.user-card-name {
    margin: 0 0 4px 0; /* Adjust margins */
    font-size: 1rem; /* Name font size */
    font-weight: 600; /* Medium-bold weight */
    color: #2A3547; /* Dark text color */
    line-height: 1.3;
}

.user-card-joined {
    margin: 0;
    font-size: 0.85rem; /* Smaller font size for secondary info */
    color: #5A6A85; /* Grey text color */
    line-height: 1.3;
}

.user-card-button {
    background-color: #5D87FF; /* Blue color from the template */
    color: #ffffff; /* White text */
    border: none;
    border-radius: 6px; /* Rounded corners for button */
    padding: 8px 16px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease; /* Smooth background transition */
    white-space: nowrap; /* Prevent button text wrapping */
    flex-shrink: 0; /* Prevent button from shrinking */
}

.user-card-button:hover {
    background-color: #4a6fcc; /* Darker blue on hover */
}
</style>

<div class="all-users-content">

    <div class="all-users-header">

        <?= $filters ?>

    </div>

    <div class="users-grid mt-5">

        <?= $users ?>
    
    </div>

</div>




