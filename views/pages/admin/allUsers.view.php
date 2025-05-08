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
    color: white;
}

:root {
            --primary-accent: #5D78FF; /* A vibrant blue, more lively */
            --primary-accent-light: #8ca0ff;
            --primary-accent-dark: #3c56c7;
            --text-color: #333;
            --label-color: #555;
            --border-color: #d0d8e0;
            --background-color: #f7f9fc; /* Slightly different light background */
            --container-bg: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.08);
            --focus-shadow-color: rgba(93, 120, 255, 0.3); /* Accent shadow */
        }

        .filter-bar {
            background-color: var(--container-bg);
            padding: 25px 30px; /* Slightly more padding */
            border-radius: 12px; /* More rounded corners for a softer, modern look */
            box-shadow: 0 6px 20px var(--shadow-color); /* Softer, more diffused shadow */
            display: flex;
            align-items: center;
            gap: 30px; /* More space between filter groups */
            /* border-top: 4px solid var(--primary-accent); /* Optional top accent border */
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group label {
            font-size: 15px;
            color: var(--label-color);
            font-weight: 600; /* Slightly bolder labels */
        }

        .filter-group select {
            padding: 12px 35px 12px 15px; /* Adjusted padding for a taller feel */
            border: 1px solid var(--border-color);
            border-radius: 8px; /* Consistent rounded corners */
            background-color: var(--container-bg);
            font-size: 15px;
            font-weight: 500; /* Slightly bolder select text */
            color: var(--text-color);
            cursor: pointer;
            min-width: 180px; /* Slightly wider dropdowns */
            transition: border-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease-out; /* Added transform transition */

            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            /* Updated SVG arrow with accent color */
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2214%22%20height%3D%2214%22%20fill%3D%22%235D78FF%22%20viewBox%3D%220%200%2016%2016%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M1.646%204.646a.5.5%200%200%201%20.708%200L8%2010.293l5.646-5.647a.5.5%200%200%201%20.708.708l-6%206a.5.5%200%200%201-.708%200l-6-6a.5.5%200%200%201%200-.708z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 0.8em top 50%;
            background-size: 0.75em auto;
        }

        .filter-group select:hover {
            border-color: var(--primary-accent-light); /* Lighter accent on hover */
            box-shadow: 0 4px 12px rgba(93, 120, 255, 0.15); /* Subtle shadow on hover */
            transform: translateY(-2px); /* Slight lift on hover */
        }

        .filter-group select:focus {
            border-color: var(--primary-accent); /* Strong accent on focus */
            outline: 0;
            box-shadow: 0 0 0 0.2rem var(--focus-shadow-color), 0 2px 8px rgba(0,0,0,0.05); /* Combined focus ring and subtle glow */
        }

        /* Optional: Add a subtle hover effect to the whole filter bar */
        .filter-bar:hover {
            /* box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); */ /* Slightly deeper shadow on bar hover */
        }
</style>

<div class="all-users-content">

    <div class="all-users-header">

    <div class="filter-bar">
        <div class="filter-group">
            <label for="role-filter">Rôle:</label>
            <select id="role-filter" name="role">
                <?php foreach($roles as $index => $role):?>

                    <option value="<?= htmlspecialchars($index-1) ?>" <?= $selected_role == $index - 1 ? "selected" : "" ?>>
                        <?= htmlspecialchars($role) ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="status-filter">Statut:</label>
            <select id="status-filter" name="status">
                <?php foreach($status as $index => $status):?>

                    <option value="<?= htmlspecialchars($index) ?>" <?= $selected_status == $index ? "selected" : "" ?>>
                        <?= htmlspecialchars($status) ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </div>
    </div>

    </div>

    <div class="users-grid mt-5">

        <?= $users ?>
    
    </div>

</div>


<script>
        const roleFilterSelect = document.getElementById('role-filter');
        const statusFilterSelect = document.getElementById('status-filter');
        const itemListDiv = document.getElementById('itemList');


        // Function to apply filters and re-render
        function applyFilters() {
            const selectedRole = roleFilterSelect.value;
            const selectedStatus = statusFilterSelect.value;

            location.href =  "<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>?role="+selectedRole+"&status="+selectedStatus
        }

        roleFilterSelect.addEventListener('change', applyFilters);
        statusFilterSelect.addEventListener('change', applyFilters);
</script>
