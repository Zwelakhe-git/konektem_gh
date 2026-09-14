<h2>Ajoute Entèvyou</h2>

<form method="POST" enctype="multipart/form-data" class="create-form">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="title" class="form-label">Tit Entèvyou *</label>
                <input type="text" class="form-control" id="title" name="title" required 
                       placeholder="Antre tit entèvyou a">
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Dat Entèvyou *</label>
                <input type="date" class="form-control" id="date" name="interview_date" required >
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="person-name">Non</label>
                        <input type="text" class="form-control" id="person-name" name="guest_name" placeholder="name of interviewee">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="person-title">Tit</label>
                        <input type="text" class="form-control" id="person-title" name="guest_title" placeholder="tit entèvyou a">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="description-inp" class="form-label">Deskripsyon</label>
                <textarea class="form-control tinymce-editor" id="description-inp" data-input-id="description" rows="4" 
                          placeholder="Deskripsyon sou entèvyou a..."></textarea>
                <input type="hidden" id="description" name="description"/>
            </div>
            
            
            <div class="mb-3">
                    <label for="videoFile" class="form-label">videyo</label>
                    <input type="file" accept="video/*" data-preview="videoPreview" class="form-control" id="videoFile" name="interview_video" >
                    
                    <div class="mt-3 video-preview">
                        <video id="videoPreview" class="d-none" style="width: 100%" controls>
                        </video>
                    </div>
                </div>
        </div>
        
        <div class="col-md-4">
            <div class="mb-3">
                <label for="interview_image" class="form-label">Imaj Entèvyou</label>
                <input type="file" class="form-control" id="interview_image" data-preview="imagePreview" data-no-preview-fallback="noImagePreview" name="interview_image" 
                       accept="image/*">
                
                <div class="mt-3 text-center">
                    <img id="imagePreview" src="#" alt="Preview imaj" 
                         class="img-thumbnail d-none" style="max-width: 100%; height: 200px; object-fit: cover;">
                    <div id="noImagePreview" class="text-muted border rounded p-4">
                        <i class="fas fa-image fa-2x mb-2"></i>
                        <br>Pa gen imaj
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Enfomasyon</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Stati</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dat Kreye</label>
                        <div class="form-control-plaintext">
                            <?= date('d.m.Y H:i') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <input type="checkbox" id="position-toggle" onchange="document.querySelector('input#position').disabled = !this.checked;"/>
        <label for="position-toggle" class="form-label">ajoute nan paj prensipal</label>
        <input type="hidden" id="position" name="position" value="mainpage" disabled/>
    </div>

    <div class="mt-4">
        <button type="button" id="submitBtn" class="btn btn-primary btn-lg">
            <i class="fas fa-plus-circle me-2"></i>Kreye Entèvyou
        </button>
        <a href="<?= BASE_URL ?>/admin/interviews" class="btn btn-secondary btn-lg">
            <i class="fas fa-times me-2"></i>Anile
        </a>
    </div>
</form>
