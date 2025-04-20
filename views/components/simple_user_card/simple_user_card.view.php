
<div class="user-card">
  <div class="user-card-avatar">
    <img src="<?= $profile_picture ?>" alt="User Avatar">
  </div>
  <div class="user-card-info">
    <h5 class="user-card-name"><?= htmlspecialchars($full_name) ?></h5>
    <p class="user-card-joined"><?= htmlspecialchars($join_date) ?></p>
  </div>
  <button class="user-card-button">View Profile</button>
</div>