<style>

.notification-icon {
  width: 50px;         /* Set desired width */
  height: 50px;        /* Set desired height */
  object-fit: cover;   /* Scales the image while preserving aspect ratio, cropping if necessary */
  /* Use 'contain' if you want to see the whole image without cropping, potentially leaving whitespace */
  /* object-fit: contain; */ 
}

</style>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <div class="d-flex align-items-center mb-3"> 
            <img src="<?= $notification["image_url"] ?>" 
                 alt="Notification Icon" 
                 class="notification-icon flex-shrink-0 me-3 rounded"> 
                 <div>
                <h4 class="card-title mb-1"> 
                    <?= $notification["title"]?>
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.9em;"> 
                <?= $notification["date_time"]?>
                </p>
            </div>
        </div>
        <p class="card-text mt-3"> 
            <?= $notification["content"]?>
        </p>
        
        <hr class="my-4"> 
        <button onclick="history.back()" class="btn btn-outline-secondary"> 
            Go Back 
        </button>
    </div> 
</div>
