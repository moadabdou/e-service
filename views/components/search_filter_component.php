<?php

function createSearchFilterComponent(
    string $searchPlaceholder = "Rechercher...",
    array $filterOptions = [],
    string $containerId = "listContainer",
    string $itemClass = "filterable-item",
    string $countElementId = "itemCount"
): string {
    ob_start();
?>
<div class="search-filter-component mb-4">
    <!-- Search and filter controls -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <div class="d-flex gap-2 flex-grow-1 align-items-center">
            <div class="input-group" style="max-width: 400px;">
                <span class="input-group-text bg-primary border-end-0">
                    <i class="ti ti-search text-white"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0 ps-2" 
                    placeholder="<?= htmlspecialchars($searchPlaceholder) ?>" aria-label="Search">
            </div>

            <?php foreach ($filterOptions as $filterKey => $filter): ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                    id="<?= $filterKey ?>FilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti <?= $filter['icon'] ?? 'ti-filter' ?> me-1"></i><?= htmlspecialchars($filter['label']) ?>
                </button>
                <ul class="dropdown-menu shadow" aria-labelledby="<?= $filterKey ?>FilterDropdown">
                    <li><a class="dropdown-item <?= $filterKey ?>-filter active" href="#" data-value="all">
                        <?= htmlspecialchars($filter['allLabel'] ?? 'Tous') ?>
                    </a></li>
                    <?php foreach ($filter['options'] as $option): ?>
                    <li><a class="dropdown-item <?= $filterKey ?>-filter" href="#" 
                        data-value="<?= htmlspecialchars($option['value']) ?>">
                        <?= htmlspecialchars($option['label']) ?>
                    </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Item count summary -->
    <div class="text-start text-muted mb-">
        <i class="ti ti-info-circle me-1"></i>
        <span id="<?= $countElementId ?>Number">0</span> élément(s) trouvé(s)
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterableItems = document.querySelectorAll('.<?= $itemClass ?>');
    const countElement = document.getElementById('<?= $countElementId ?>Number');
    const currentFilters = {};

    <?php foreach ($filterOptions as $filterKey => $filter): ?>
    currentFilters['<?= $filterKey ?>'] = 'all';
    <?php endforeach; ?>

    function updateItemsDisplay() {
        let visibleCount = 0;

        filterableItems.forEach(item => {
            const searchTerm = searchInput.value.toLowerCase();
            const searchableText = item.textContent.toLowerCase();
            const matchesSearch = searchableText.includes(searchTerm);

            let matchesAllFilters = true;

            <?php foreach ($filterOptions as $filterKey => $filter): ?>
            const <?= $filterKey ?>Value = item.dataset.<?= $filterKey ?>;
            const matches<?= ucfirst($filterKey) ?> = currentFilters['<?= $filterKey ?>'] === 'all' || 
                <?= $filterKey ?>Value === currentFilters['<?= $filterKey ?>'];
            if (!matches<?= ucfirst($filterKey) ?>) {
                matchesAllFilters = false;
            }
            <?php endforeach; ?>

            if (matchesSearch && matchesAllFilters) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        countElement.textContent = visibleCount;
    }

    if (searchInput) {
        searchInput.addEventListener('input', updateItemsDisplay);
    }

    <?php foreach ($filterOptions as $filterKey => $filter): ?>
    document.querySelectorAll('.<?= $filterKey ?>-filter').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.<?= $filterKey ?>-filter').forEach(filter => {
                filter.classList.remove('active');
            });
            this.classList.add('active');
            currentFilters['<?= $filterKey ?>'] = this.dataset.value;
            updateItemsDisplay();
        });
    });
    <?php endforeach; ?>

    updateItemsDisplay();
});
</script>

<style>
.dropdown-item.active {
    background-color: #e9ecef;
    color: #000;
}
</style>
<?php
    return ob_get_clean();
}