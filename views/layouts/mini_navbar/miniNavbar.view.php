<style>
    /* Optional: Custom styles to mimic the inactive state more closely */
    .nav-pills .nav-link:not(.active) {
        background-color: #f8f9fa; /* Light gray background like 'Unread' */
        color: #495057; /* Darker text color for better contrast on light gray */
    }
    /* Optional: Ensure active stays the primary blue */
    .nav-pills .nav-link.active {
            background-color: #0d6efd; /* Standard Bootstrap primary blue */
            color: white;
    }
    /* Optional: Add hover effect for inactive items */
    .nav-pills .nav-link:not(.active):hover {
        background-color: #e9ecef; /* Slightly darker gray on hover */
    }
</style>

<div class="mini-nav mt-4">
    <nav class="mb-3">
        <ul class="nav nav-pills">
            <?php foreach($elements as $index => $element ): ?>
                <li class="nav-item me-2">
                    <a class="nav-link <?= $index == $active? "active" : ""?>" aria-current="page" href="<?= $element[1]?>"><?= $element[0]?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>
