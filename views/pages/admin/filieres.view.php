<style>
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
            padding: 12px 30px; /* Slightly more padding */
            border-radius: 12px; /* More rounded corners for a softer, modern look */
            box-shadow: 0 6px 20px var(--shadow-color); /* Softer, more diffused shadow */
            display: flex;
            align-items: center;
            gap: 30px; /* More space between filter groups */
            margin-bottom: 20px;
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

<div class="all-filieres-content">

    <div class="filter-bar">
        <div class="filter-group">
            <label for="dep-filter">département:</label>
            <select id="dep-filter" name="dep">
                <?php foreach($departements as $dep):?>

                    <option value="<?= htmlspecialchars($dep["id_deparetement"]) ?>" <?= $id_dep == $dep["id_deparetement"] ? "selected" : "" ?>>
                        <?= htmlspecialchars($dep["title"]) ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <h4>Gestion des filières du département <?= htmlspecialchars($dep_data["title"]) ?></h4>
    <div class="all-filieres-header">

        <?= $filters ?>

    </div>

    <div class="filieres-grid mt-5">

        <?= $content ?>
    
    </div>

</div>

<script>
        const depFilterSelect = document.getElementById('dep-filter');
        const itemListDiv = document.getElementById('itemList');


        // Function to apply filters and re-render
        function applyFilters() {
            const selectedDep = depFilterSelect.value;
            location.href =  "<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>?id_dep="+selectedDep+"&filter=0"
        }

        depFilterSelect.addEventListener('change', applyFilters);

</script>