
<div class="user-card">
    <div class="user-card-avatar">
        <img src="<?= $profile_picture ?>" alt="User Avatar">
    </div>
    <div class="user-card-info">
        <h5 class="user-card-name"><?= htmlspecialchars($full_name) ?></h5>
        <p class="user-card-joined">Inscrit le : <?= htmlspecialchars($join_date) ?></p>
    </div>
    <a class="user-card-button" href="/e-service/internal/members/common/view_profile.php?id=<?= $id ?>">Voir le profil</a>
</div>