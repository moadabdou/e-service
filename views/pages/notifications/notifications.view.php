<style>
    /* Assume body background is light (#ffffff or #f8f9fa) */
    /* Assume base .btn, icon font (like FontAwesome) setup exists */

    .notifications-page-fully-light h1 {
        color: #212529;
        /* Dark heading */
        margin-bottom: 20px;
        font-size: 1.8em;
        font-weight: 600;
    }

    /* --- Controls --- */
    .notification-controls {
        margin-bottom: 25px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .notification-controls .filter-btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        border: none;
    }

    .notification-controls .filter-btn.active {
        background-color: #0d6efd;
        /* Primary blue */
        color: #ffffff;
    }

    .notification-controls .filter-btn:not(.active) {
        background-color: #e9ecef;
        /* Light grey for inactive button */
        color: #495057;
        /* Darker text for inactive button */
        /* Optional subtle border */
        /* border: 1px solid #dee2e6; */
    }

    .notification-controls .mark-all-read-btn {
        margin-left: auto;
        background-color: #0d6efd;
        /* Primary blue */
        color: #ffffff;
        border: none;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    /* --- Notification List --- */
    .notification-list {
        display: flex;
        flex-direction: column;
        /* Remove gap if using borders for separation */
        /* gap: 1px; */
        background-color: #ffffff;
        /* White background for the list area */
        border: 1px solid #dee2e6;
        /* Light grey border around the list */
        border-radius: 8px;
        overflow: hidden;
        /* Clip items to rounded border */
    }

    .notification-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        background-color: #ffffff;
        /* White background for items */
        color: #212529;
        /* Dark text for items */
        position: relative;
        gap: 15px;
        border-bottom: 1px solid #e9ecef;
        /* Light border between items */
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    /* Remove border from the last item */
    .notification-list .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
        /* Slightly off-white hover */
    }

    /* --- Unread Styling --- */
    .notification-item.unread {
        /* Optional: Subtle background tint for unread */
        background-color: #e7f1ff;
        /* Or make title slightly bolder */
        font-weight: 500;
    }

    /* Unread Indicator */
    .notification-indicator {
        width: 8px;
        height: 8px;
        background-color: #0d6efd;
        /* Blue dot */
        border-radius: 50%;
        flex-shrink: 0;
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
        /* Hide by default */
    }

    .notification-item.unread .notification-indicator {
        display: block;
        /* Show only for unread */
    }

    /* Adjust padding for indicator */
    .notification-item {
        padding-left: 30px;
        /* Make space for the dot */
    }


    /* --- Icon/Image --- */
    .notification-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #e9ecef;
        /* Light grey background */
        overflow: hidden;
        color: #495057;
        /* Dark grey text/icon */
        font-weight: 500;
    }

    .notification-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .notification-icon i {
        font-size: 1.2em;
        color: #6c757d;
        /* Medium grey icon color */
    }

    .icon-placeholder {
        font-size: 1.1em;
    }

    /* --- Details (Title, Content, Time) --- */
    .notification-details {
        flex-grow: 1;
        min-width: 0;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 3px;
    }

    .notification-title {
        font-weight: 600;
        /* Bold title */
        color: #212529;
        /* Dark title */
        margin-right: 15px;
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .notification-item.read .notification-title {
        font-weight: 500;
    }

    .notification-time {
        font-size: 0.75em;
        color: #6c757d;
        /* Medium grey timestamp */
        flex-shrink: 0;
    }

    .notification-content {
        font-size: 0.85em;
        color: #495057;
        /* Darker grey content text */
        line-height: 1.4;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 60%;
    }

    /* --- Actions --- */
    .notification-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        opacity: 0;
        transition: opacity 0.2s ease;
        margin-left: auto;
        padding-left: 10px;
    }

    .notification-item:hover .notification-actions {
        opacity: 1;
    }

    .notification-actions .action-btn {
        background: none;
        border: none;
        color: #6c757d;
        /* Medium grey icon color */
        cursor: pointer;
        padding: 5px;
        font-size: 1.5em;
        line-height: 1;
    }

    .notification-actions .action-btn:hover {
        color: #0d6efd;
        /* Blue on hover */
    }

    .notification-actions .action-btn.delete:hover {
        color: #dc3545;
        /* Red for delete hover */
    }

    /* --- Empty State --- */
    .notification-empty {
        text-align: center;
        padding: 50px 20px;
        color: #6c757d;
        /* Medium grey text */
        background-color: #ffffff;
        /* Match item background */
        /* No border needed if it's inside the bordered list */
    }

    .notification-empty i {
        font-size: 3em;
        margin-bottom: 15px;
        display: block;
        color: #adb5bd;
        /* Lighter icon */
    }

    /* --- Refined Pagination Styles --- */

    .pagination-container {
        margin-top: 1.5rem;
    }

    .pagination {
        display: flex;
        /* Use flexbox for alignment */
        padding-left: 0;
        list-style: none;
        justify-content: center;
        gap: 8px;
        /* Creates space between items */
    }

    .page-link {
        position: relative;
        display: flex;
        /* Use flex for centering content if needed (like icons) */
        justify-content: center;
        align-items: center;
        min-width: 40px;
        /* Ensure minimum width for consistency */
        height: 40px;
        /* Ensure consistent height */
        padding: 0 10px;
        /* Horizontal padding, vertical handled by height/align-items */
        font-size: 0.875rem;
        /* Slightly smaller font */
        font-weight: 500;
        /* Medium weight */
        line-height: 1;
        /* Reset line height as height controls vertical size */
        color: #5a6a85;
        /* Default muted text color */
        background-color: #ffffff;
        /* White background for inactive */
        border: 1px solid #dee2e6;
        /* Subtle border for inactive */
        border-radius: 7px;
        /* Rounded corners - adjust to match template */
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        /* Very subtle shadow */
    }

    /* Hover state for inactive items */
    .page-link:hover {
        z-index: 2;
        color: #5D87FF;
        /* Primary blue text on hover */
        background-color: #f8f9fa;
        /* Very light grey background */
        border-color: #ced4da;
        /* Slightly darker border on hover */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.07);
    }

    /* Focus state (accessibility) */
    .page-link:focus {
        z-index: 3;
        outline: 0;
        /* Use the primary color for the focus ring */
        box-shadow: 0 0 0 0.2rem rgba(93, 135, 255, 0.3);
    }

    /* Active page state */
    .page-item.active .page-link {
        z-index: 1;
        color: #fff;
        /* White text */
        background-color: #0d6efd;
        ;
        /* Primary blue background */
        border-color: #5D87FF;
        /* Primary blue border */
        box-shadow: 0 3px 5px rgba(93, 135, 255, 0.3);
        /* More prominent shadow for active */
    }

    .page-item.active .page-link:hover,
    .page-item.active .page-link:focus {
        background-color: #4a70e0;
        /* Slightly darker blue on hover/focus */
        border-color: #4a70e0;
    }

    /* Disabled state */
    .page-item.disabled .page-link {
        color: #adb5bd;
        /* Light grey text */
        pointer-events: none;
        cursor: default;
        background-color: #f8f9fa;
        /* Lightest background */
        border-color: #e9ecef;
        /* Very light border */
        box-shadow: none;
        /* No shadow for disabled */
        opacity: 0.8;
    }

    /* Style for ellipsis (...) */
    .page-link.dots {
        cursor: default;
        color: #adb5bd;
        background-color: transparent;
        /* Make dots backgroundless */
        border-color: transparent;
        box-shadow: none;
    }

    .page-link.dots:hover {
        /* Prevent hover effect on dots */
        background-color: transparent;
        border-color: transparent;
        color: #adb5bd;
    }

    /* Styling for Prev/Next Chevrons */
    .page-link span[aria-hidden="true"] {
        font-size: 1.1rem;
        /* Make chevrons slightly larger */
        font-weight: bold;
    }

    /* Optional: Adjust chevron color on hover/disabled if needed */
    .page-item.disabled .page-link span[aria-hidden="true"] {
        color: #ced4da;
    }

    .page-link:hover span[aria-hidden="true"] {
        /* color: #5D87FF; */
        /* Already handled by general text color hover */
    }
</style>

<div class="notifications-page-fully-light"> <!-- New class for clarity -->
    <h1>Notifications</h1>

    <div class="notification-controls">
        <a class="btn btn-primary filter-btn <?= $type == "all" ? "active" : "" ?>" href="/e-service/internal/members/common/notifications.php?type=all" data-filter="all">All</a>
        <a class="btn btn-secondary filter-btn <?= $type == "unread" ? "active" : "" ?>" data-filter="unread" href="/e-service/internal/members/common/notifications.php?type=unread">Unread</a>
        <a class="btn btn-primary mark-all-read-btn">Mark all as read</a>
    </div>

    <div class="notification-list">
        <?php foreach ($notifications as $notification) { ?>
            <a href=" <?= "/e-service/internal/members/common/read_notification.php?id=" . $notification["id_notification"] ?> ">
                <div class="notification-item <?= $notification["status"] ?>" data-id="<?= $notification["id_notification"] ?>">
                    <div class="notification-indicator"></div>
                    <div class="notification-icon">
                        <img src="<?= $notification["image_url"] ?>" alt="notification">
                    </div>
                    <div class="notification-details">
                        <div class="notification-header">
                            <span class="notification-title"><?= $notification["title"] ?></span>
                            <span class="notification-time"><?= $notification["date_time"] ?></span>
                        </div>
                        <p class="notification-content"><?= $notification["content"] ?></p>
                    </div>
                    <div class="notification-actions">
                        <button class="action-btn notification-delete" title="Delete"><i class="ti ti-trash"></i></button>
                    </div>
                </div>
            </a>
        <?php } ?>

        <?php if (count($notifications) === 0) { ?>
            <div class="notification-empty">
                <i class="fas fa-bell-slash"></i>
                <p>You have no notifications.</p>
            </div>
        <?php } ?>
    </div>
    <nav aria-label="Page navigation example" class="pagination-container">
        <ul class="pagination">
            <!-- Previous Button -->
            <li class="page-item <?= $page === 1? "disabled" : ""?>">
                <a class="page-link" href="/e-service/internal/members/common/notifications.php?type=<?= $type ?>&page=<?= $page-1 ?>" tabindex="-1" aria-disabled="true" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                    <!-- Or use text: Previous -->
                </a>
            </li>

            <li class="page-item active"><a class="page-link" href="/e-service/internal/members/common/notifications.php?type=<?= $type ?>&page=1">start</a></li>
            <li class="page-item"><span class="page-link dots">...</span></li> <!-- Ellipsis -->
            <!-- Page Numbers -->
            <li class="page-item active" aria-current="page">
                <a class="page-link" href="/e-service/internal/members/common/notifications.php?type=<?= $type ?>&page=<?= $page ?>"><?= $page ?></a>
            </li>
            <?php for($i = $page + 1 ; $i <= min( $page+6, $maxPages ); $i++){?> 
                <li class="page-item"><a class="page-link" href="/e-service/internal/members/common/notifications.php?type=<?= $type ?>&page=<?= $i  ?>"><?= $i ?></a></li>
            <?php }?>
            <li class="page-item"><span class="page-link dots">...</span></li> <!-- Ellipsis -->
            <li class="page-item active"><a class="page-link" href="/e-service/internal/members/common/notifications.php?type=<?= $type ?>&page=<?= $maxPages ?>">end</a></li>
            <!-- Next Button -->
            <li class="page-item <?= $page === $maxPages? "disabled" : ""?>" >
                <a class="page-link" href="/e-service/internal/members/common/notifications.php?type=<?= $type ?>&page=<?= $page+1 ?>" aria-label="Next">
                    <span aria-hidden="true">»</span>
                    <!-- Or use text: Next -->
                </a>
            </li>
        </ul>
    </nav>
</div>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="alert-box" class="alert alert-danger d-none" role="alert">
        this is an alert.
    </div>
</div>
<script>
    function showAlert(msg) {
        const alertBox = document.getElementById("alert-box");
        alertBox.classList.remove("d-none");
        alertBox.textContent = msg;
        setTimeout(() => alertBox.classList.add("d-none"), 3000);
    }

    document.querySelectorAll(".notification-delete").forEach(el => {
        el.addEventListener("click", async e => {
            const not_id = el.parentElement.parentElement.getAttribute("data-id");
            const res = await fetch("<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>", {
                method: "DELETE",
                headers: {
                    "Content-type": "application/json"
                },
                body: JSON.stringify({
                    notification_id: not_id
                })
            });

            if (res.status === 200) {
                location.reload();
            } else {
                showAlert("we were not able to delete this  notification");
            }
        })
    })

    document.querySelector(".mark-all-read-btn").addEventListener("click", async e => {
        const res = await fetch("<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>", {
            method: "UPDATE",
            headers: {
                "Content-type": "application/json"
            },
            body: JSON.stringify({
                action: 0
            })
        });

        if (res.status === 200) {
            location.reload();
        } else {
            showAlert("we were not able to perform this action");
        }
    })
</script>