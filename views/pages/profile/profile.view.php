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

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        font-size: 1em;
        position: relative; /* Needed for absolute positioning of the button if desired */
    }

    .info-item label {
        font-weight: 600;
        color: #555;
        width: 200px; /* Fixed width for labels */
        flex-shrink: 0; /* Prevent label from shrinking */
    }

    .info-value {
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

</style>

<div class="profile-content">
    <div class="profile-picture-section">
        <img src="<?= $profile_picture_url ?>" alt="User Profile Picture" id="profile-pic">
            <button class="edit-pic-btn" id="changePictureBtn">
            <i class="fas fa-camera"></i> Change Picture
            <input type="file"  id="fileInput" hidden>
        </button>
    </div>

    <div class="profile-info-section">
        <?php if ($info) {?>
            <div class="alert alert-<?= $info["type"] ?> text-center mb-5" role="alert">
                <?= $info["msg"] ?>
            </div>
        <?php }?>
        <h2>Profile Information</h2>
        <?php foreach ($user_info as $key => $value) {?>
            <div class="info-item">
                <label><?= htmlspecialchars($key) ?></label>
                <span id="user-name" class="info-value"><?= htmlspecialchars($value["value"]) ?></span>
                <?php if($value["self_editable"]){?>
                    <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editInfo">
                        <i class="ti ti-pencil"></i>
                    </button>
                <?php }?>
            </div>  
        <?php } ?>
        
    </div>

    <div class="modal fade" id="editInfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editInfoLabel">Edit your data here </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form action="<?=htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="data-field" class="form-label data-key">data key</label>
                            <input type="text" class="form-control data-value" id="data-field" name="value" >
                            <input type="text" class="form-control data-key-input" id="data-field" name="key" hidden>
                            <div id="Help" class="form-text">Make sure that your data is valid.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            
            </div>
        </div>
    </div>

    <!-- Alert for invalid file -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        <div id="alertInvalidFile" class="alert alert-danger d-none" role="alert">
            Chosen file is invalid. Please select an image.
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Preview Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="image-container"> 
                        <img id="previewImage" src="" class="img-fluid rounded">
                        <div id="crop-mask"></div>
                        <div class="zoom-controls position-absolute top-50 end-0 translate-middle-y">
                            <button class="btn btn-dark btn-sm mb-2" id="zoom-in">+</button>
                            <button class="btn btn-dark btn-sm" id="zoom-out">−</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="uploadProfilePhoto" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST" enctype="multipart/form-data">
                        <input type="text" name="key" value="img" hidden>
                        <input type="file" name="value" id="profilePhotoInput" hidden>
                    </form>
                    <button type="button" id="uploadImage" class="btn btn-secondary" data-bs-dismiss="modal" >Save</button>
                </div>
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


    const imageContainer = document.getElementById('image-container');
    const imageModal = document.getElementById("imageModal");
    const selectedImage = document.getElementById('previewImage');
    const cropMask = document.getElementById('crop-mask');
    const zoomInBtn = document.getElementById("zoom-in");
    const zoomOutBtn = document.getElementById("zoom-out");
    const upLoadImage  = document.getElementById("uploadImage");
    
    let isDragging = false;
    let startX, startY, initialX, initialY;
    let originalMaskSize;

    let scale =  1;

    zoomInBtn.addEventListener("click", ()=>{
        scalCrop(.1, scale < 1 && cropIsIn())
    })
    zoomOutBtn.addEventListener("click", ()=>{
        scalCrop(-.1, scale > .5)
    })


    document.getElementById("changePictureBtn").addEventListener("click", function () {
        document.getElementById("fileInput").click();
    });
    document.getElementById("fileInput").addEventListener("input", function (event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function (e) {
                selectedImage.src = e.target.result;
                new bootstrap.Modal(imageModal).show();
                imageModal.addEventListener("shown.bs.modal", () => {
                    initializeCropMask();
                }, { once: true });
            };
            reader.readAsDataURL(file);
        } else {
            const alertBox = document.getElementById("alertInvalidFile");
            alertBox.classList.remove("d-none");
            setTimeout(() => alertBox.classList.add("d-none"), 3000);
        }
    });

    upLoadImage.addEventListener("click", ()=>{
        cropAndResizeImage()
    })

    function  scalCrop(ratio, valid){
        
        const previousHeight = cropMask.offsetHeight;
        const previousWidth = cropMask.offsetWidth;
        scale =  valid ? scale += ratio : scale;
        cropMask.style.width = `${ originalMaskSize * scale }px`;
        cropMask.style.height = `${ originalMaskSize * scale  }px`;
        cropMask.style.left = `${cropMask.offsetLeft + (previousWidth - cropMask.offsetWidth) / 2}px`;
        cropMask.style.top = `${cropMask.offsetTop + (previousHeight - cropMask.offsetHeight) / 2}px`;
    }

    function cropIsIn(){
        
        return  cropMask.offsetLeft >= 0 &&
                cropMask.offsetTop >= 0 &&
                cropMask.offsetLeft + cropMask.offsetWidth <= imageContainer.offsetWidth &&
                cropMask.offsetTop + cropMask.offsetHeight <= imageContainer.offsetHeight
    }

    function cropAndResizeImage() {
        const croppedCanvas = document.createElement('canvas');
        const croppedCtx = croppedCanvas.getContext('2d');

        const renderedWidth = selectedImage.width;
        const renderedHeight = selectedImage.height;
        const intrinsicWidth = selectedImage.naturalWidth;
        const intrinsicHeight = selectedImage.naturalHeight;
    
        let cropX = cropMask.offsetLeft; // X in rendered size
        let cropY = cropMask.offsetTop; // Y in rendered size
        let cropWidth = cropMask.offsetWidth;
        let cropHeight = cropMask.offsetHeight;
    
        // Convert rendered coordinates to intrinsic (original) coordinates
        const scaleX = intrinsicWidth / renderedWidth;
        const scaleY = intrinsicHeight / renderedHeight;
    
        const sX = cropX * scaleX;
        const sY = cropY * scaleY;
        const sH = cropWidth * scaleX;
        const sW = cropHeight * scaleY;

   
        croppedCanvas.width = 512;
        croppedCanvas.height = 512;
        console.log(cropX, cropY, sW, sH);
        console.log( sX, sY , sW , sH);
        croppedCtx.drawImage(
            selectedImage, 
            sX, sY , sW , sH, 
            0, 0, 512, 512
        );

        const croppedImage = croppedCanvas.toBlob((blob)=> {
            
            const file = new File([blob], 'profile.png', { type: 'image/png' });

            const fileInput = document.getElementById('profilePhotoInput');
            const dataTransfer = new DataTransfer(); 
            dataTransfer.items.add(file); 
            fileInput.files = dataTransfer.files;

            document.getElementById('uploadProfilePhoto').submit(); 

        }, 'image/png');
    }

    function initializeCropMask() {
        const imgWidth = selectedImage.offsetWidth;
        const imgHeight = selectedImage.offsetHeight;
        originalMaskSize = Math.min(imgWidth, imgHeight) ;

        cropMask.style.width = `${originalMaskSize}px`;
        cropMask.style.height = `${originalMaskSize}px`;
        cropMask.style.left = `${(imgWidth - originalMaskSize) / 2}px`;
        cropMask.style.top = `${(imgHeight - originalMaskSize) / 2}px`;
    }

    cropMask.addEventListener('mousedown', function (e) {
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        initialX = cropMask.offsetLeft;
        initialY = cropMask.offsetTop;
        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    });

    function onMouseMove(e) {
        if (!isDragging) return;
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            const newX = initialX + dx;
            const newY = initialY + dy;
            const maxX = selectedImage.offsetWidth - cropMask.offsetWidth;
            const maxY = selectedImage.offsetHeight - cropMask.offsetHeight;
        if (newX >= 0 && newX <= maxX) {
            cropMask.style.left = `${newX}px`;
        }
        if (newY >= 0 && newY <= maxY) {
            cropMask.style.top = `${newY}px`;
        }
    }

    function onMouseUp() {
        isDragging = false;
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
    }


</script>