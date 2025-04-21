<style>
    .profile-content {
        background-color: #ffffff; /* White background for profile card */
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        display: flex;
        gap: 40px; /* Space between picture and info */
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
    }

    .profile-picture-section {
        text-align: center;
        flex-basis: 200px; /* Suggest initial width */
        flex-shrink: 0;
    }

    #profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%; /* Make it circular */
        border: 3px solid #e0e0e0;
        margin-bottom: 15px;
        object-fit: cover; /* Ensure image covers the area well */
    }

    .edit-pic-btn {
        background: none;
        border: 1px solid #ccc;
        color: #555;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9em;
        transition: all 0.3s ease;
    }
    .edit-pic-btn i {
        margin-right: 5px;
    }
    .edit-pic-btn:hover {
        background-color: #f0f0f0;
        border-color: #aaa;
    }

    h2:not(:nth-of-type(1)){
        margin-top: 40px;
    }

    .profile-info-section {
        flex-grow: 1; /* Take remaining space */
    }

    .profile-info-section h2 {
        margin-bottom: 25px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        color: #333;
        font-weight: 500;
    }

    .info-item, .setting-item{
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        font-size: 1em;
        position: relative; /* Needed for absolute positioning of the button if desired */
    }

    .info-item label, .setting-item label{
        font-weight: 600;
        color: #555;
        width: 200px; /* Fixed width for labels */
        flex-shrink: 0; /* Prevent label from shrinking */
    }

    .setting-item label {
        flex-grow: 1;
    }

    .info-value{
        color: #333;
        flex-grow: 1; /* Take available space */
        margin-right: 10px; /* Space before the pen icon */
        padding: 5px 0; /* Add some padding for better click area perception if needed */
    }

    .edit-btn {
        background: none;
        border: none;
        color: #4a90e2; /* Icon color */
        cursor: pointer;
        font-size: 1.5em; /* Adjust icon size */
        padding: 5px;
        margin-left: 10px;
        line-height: 1; /* Ensure icon aligns well */
        transition: color 0.3s ease;
    }

    .edit-btn:hover {
        color: #357abd; /* Darker blue on hover */
    }


    #image-container {
        position: relative;
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
    }
    #crop-mask {
        position: absolute;
        border: 2px dashed #fff;
        cursor: move;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        background: rgba(255, 255, 255, 0.3);
    }
    #image-container img {
        display: block;
        max-width: 100%;
        height: auto;
    }

    #image-container .zoom-controls {
        right: 10px; /* Adjust position */
        z-index: 10; /* Ensure it stays on top */
    }

    #image-container .btn {
        width: 32px;
        height: 32px;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .account-operation-item {
        padding: 0.75rem 0; /* Vertical padding for spacing */
        cursor: pointer; /* Indicate items are clickable */
        font-size: 1.2em;
        border-bottom: 1px solid #eee; /* Subtle separator */
    }
    .account-operation-item:last-child {
        border-bottom: none; /* Remove border from last item */
    }
    .account-operation-item span:last-child {
        color: #6c757d; /* Grey color for the arrow */
        font-weight: bold;
    }
    .account-operation-item.text-danger span:last-child {
        color: inherit; /* Make arrow red for delete option */
    }
    .account-operation-item:hover {
        background-color: #f8f9fa; /* Slight hover effect */
    }
    h5 {
        font-weight: 600; /* Slightly bolder headings */
    }

</style>
<div class="profile-content">
    <div class="profile-picture-section">
        <img src="<?= $profile_picture_url ?>" alt="Photo de Profil" id="profile-pic">
    </div>

    <div class="profile-info-section">
        <?php if ($info) {?>
            <div class="alert alert-<?= $info["type"] ?> text-center mb-5" role="alert">
                <?= $info["msg"] ?>
            </div>
        <?php }?>
        <h2>Informations du Profil</h2>
        <?php foreach ($user_info as $key => $value) {?>
            <div class="info-item">
                <label><?= htmlspecialchars($key) ?></label>
                <span id="user-name" class="info-value"><?= htmlspecialchars($value) ?></span>
                <?php if ( $_SESSION["role"] === "admin" ): ?>
                    <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editInfo">
                        <i class="ti ti-pencil"></i>
                    </button>   
                <?php endif?>
                </div>  
        <?php } ?>
        <?php if ( $_SESSION["role"] === "admin" ): ?>
            <h2 class="mb-3">Opérations du Compte</h5>

            <div class="account-operation-item d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#confirmation" data-operation="reset_password">
                <span>Réinitialiser le mot de passe</span>
                <span>></span>
            </div>

            <div class="account-operation-item d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#confirmation"  data-operation="desactivate_account">
                <span>Désactiver le compte</span>
                <span>></span>
            </div>

            <div class="account-operation-item d-flex justify-content-between align-items-center text-danger" data-bs-toggle="modal" data-bs-target="#confirmation"  data-operation="delete_account">
                <span>Supprimer le compte</span>
                <span>></span>
            </div>   
        <?php endif?>
        
    </div>
    <!-- === End of New Account Operations Section === -->

            
    <div class="modal fade" id="editInfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editInfoLabel">Modifier les données du profil ici</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form action="<?=htmlspecialchars($_SERVER["PHP_SELF"])?>?id=<?= htmlspecialchars($id) ?>" method="POST">
                    <input type="hidden" value="info_update" name="type_request">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="data-field" class="form-label data-key">Champ de données</label>
                            <input type="text" class="form-control data-value" id="data-field" name="value" >
                            <input type="text" class="form-control data-key-input" id="data-field" name="key" hidden>
                            <div id="Help" class="form-text">Assurez-vous que vos données sont valides.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmationLabel">Êtes-vous sûr de cette opération ?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form action="<?=htmlspecialchars($_SERVER["PHP_SELF"])?>?id=<?= htmlspecialchars($id) ?>" method="POST">
                    <input type="hidden" name="type_operation">
                    <input type="hidden" value="account_operation" name="type_request">
                    <div id="Help" class="form-text" style="font-size: 1.1em;padding: 20px;"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const editDataModel = document.getElementById('editInfo')
    if (editDataModel) {
        editDataModel.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const infoElement = event.relatedTarget.parentElement
            const key = infoElement.firstElementChild.textContent;
            const value  = infoElement.children[1].textContent;
            
            editDataModel.querySelector(".data-key").textContent =  key;
            editDataModel.querySelector(".data-key-input").value =  key;
            editDataModel.querySelector(".data-value").value =  value;

        })
    }

    const confirmationModel = document.getElementById('confirmation')
    if (confirmationModel) {
        confirmationModel.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const operationElement = event.relatedTarget;
            const title = operationElement.firstElementChild.textContent;
            confirmationModel.querySelector("input[name='type_operation']").value =  operationElement.getAttribute("data-operation");
            confirmationModel.querySelector("#Help").textContent =  title;

        })
    }

</script>