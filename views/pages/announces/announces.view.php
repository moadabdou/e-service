<style>
 
 .announcement-card {
    width: 95%;
    max-width: 1000px;
    margin: 0 auto;
    background-color: white;
    border: 1px solid #ddd;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    padding: 25px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    min-height: 280px;
    margin-bottom: 40px; /* Space between cards */
 }
        .announcement-card h2 {
            color: #333;
            margin-top: 0;
            font-size: 24px;
            border-bottom: 2px solid #8c7853; /* A rustic looking underline */
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex; /* To align icon and text */
            align-items: center; /* To align icon and text */
        }

        .announcement-card h2 .icon { /* Style for the emoji/icon */
            margin-right: 10px;
            font-size: 28px; /* Slightly larger icon */
        }

        .announcement-card .announcement-title {
            font-size: 20px;
            color: #5a4d39;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .announcement-card .announcement-text {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
            flex-grow: 1; /* Allows text to take available space before details */
            margin-bottom: 20px; /* Space before details if details are present */
        }

        .announcement-card .announcement-details {
            font-size: 14px;
            color: #666;
            margin-top: auto; /* Pushes details to the bottom if announcement-text has flex-grow */
            padding-top: 15px; /* Add some space if pushed to bottom */
            border-top: 1px dashed #eee; /* Optional separator for details */
            font-style: italic;
        }

</style>

<div class="announces">
    <?php foreach ($announces as $announce): ?>
        <div class="announcement-card">
            <div class="header-post" style="    display: flex;justify-content: space-between;">
            <h2>
                    <i class="fas fa-megaphone"></i> <!-- Emoji or icon for the announcement -->
                    Announcement
                </h2>
                <?php if ($announce["id_admin"] === $_SESSION["id_user"]): ?>
                    
                <div class="delete-btn">
                    <button class="btn btn-danger" id="delete-btn" data-id="<?= htmlspecialchars($announce["id_announce"]) ?>">
                        <i class="fas fa-trash"></i> <!-- Font Awesome icon for delete -->
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <div class="announcement-title"><?= htmlspecialchars($announce["title"]) ?></div>
            <div class="announcement-text"><?= nl2br(htmlspecialchars($announce["content"])) ?></div>  <!-- ela wed /n bach irje3 lster -->
            <div class="announcement-details">Created on: <?= htmlspecialchars($announce["create_time"]) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll("#delete-btn");
        deleteButtons.forEach(button => {
            button.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                if (confirm("Are you sure you want to delete this announcement?")) {
                    fetch("/e-service/internal/members/common/announces.php", {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ announce_id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Announcement deleted successfully.");
                            location.reload(); // Reload the page to see the changes
                        } else {
                            alert("Failed to delete announcement.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
                }
            });
        });
    });
</script>