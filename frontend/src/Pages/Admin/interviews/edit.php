<h2>Modifye Entèvyou</h2>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="edit-form">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="title" class="form-label">Tit Entèvyou *</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= htmlspecialchars($interview['title'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Dat Entèvyou *</label>
                <input type="date" class="form-control" id="date" name="interview_date" value="<?= $interview['interview_date']?>" required >
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="person-name">Non</label>
                        <input type="text" class="form-control" id="person-name" name="guest_name" placeholder="name of interviewee"
                               value="<?= $interview['guest_name'] ?? 'unknown guest'?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="person-title">Tit</label>
                        <input type="text" class="form-control" id="person-title" name="guest_title" placeholder="tit entèvyou a"
                               value="<?= $interview['guest_title'] ?? 'unknown title'?>">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="description-inp" class="form-label">Deskripsyon</label>
                <textarea class="form-control tinymce-editor" id="description" name="description-inp" data-input-id="description" rows="4"><?= htmlspecialchars($interview['description'] ?? '') ?></textarea>
                <input type="hidden" id="description" name="description"/>
            </div>
            
            <div class="mb-3">
                    <label for="video-file" class="form-label">videyo</label>
                    <input type="file" accept="video/*" data-preview="videoPreview" class="form-control" id="video-file" name="interview_video" >
                    
                    <div class="mt-3 video-preview">
                        <video id="videoPreview" <?= $interview['video_url'] ? 'src="' . $interview['video_url'] .'"': 'class="d-none"' ?> style="width: 100%" controls>
                        </video>
                    </div>
                </div>
        </div>
        
        <div class="col-md-4">
            <div class="mb-3">
                <label for="interview_image" class="form-label">Imaj Entèvyou</label>
                <input type="file" class="form-control" id="interview_image" data-preview="imagePreview" data-no-preview-fallback="noImagePreview" name="interview_image" 
                       accept="image/*" />
                
                <div class="mt-2">
                    <p>Imaj aktyel:</p>
                    <img src="<?= $interview['image_url'] ?>" width="200" id="imagePreview" class="img-thumbnail <?= !$interview['image_url'] ? 'd-none' : ''?>">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                        <label class="form-check-label" for="remove_image">
                            Siprime imaj aktyel la
                        </label>
                    </div>
                </div>
                <div class="mt-2 text-center <?= $interview['image_url'] ? 'd-none' : ''?>" id="noImagePreview">
                    <div class="text-muted border rounded p-4">
                        <i class="fas fa-image fa-2x mb-2"></i>
                        <br>Pa gen imaj
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Enfomasyon Entèvyou</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>ID:</strong> <?= $interview['id'] ?? 'Nouvo' ?>
                    </div>
                    <div class="mb-2">
                        <strong>Vizit:</strong> <?= $interview['views'] ?? 0 ?>
                    </div>
                    <div class="mb-2">
                        <strong>Pataj:</strong> <?= $interview['shares'] ?? 0 ?>
                    </div>
                    <div class="mb-2">
                        <strong>Renmen:</strong> <?= $interview['likes'] ?? 0 ?>
                    </div>
                    <div class="mb-2">
                        <strong>Dat Kreye:</strong><br>
                        <?= date('d.m.Y H:i', strtotime($interview['created_at'] ?? 'now')) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Dènye Modifye:</strong><br>
                        <?= date('d.m.Y H:i', strtotime($interview['updated_at'] ?? 'now')) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <input type="checkbox" 
            id="position-toggle" 
            onchange="document.querySelector('input#position').disabled = !this.checked;"
            <?= (isset($interview['position']) && $interview['position'] === 'mainpage') ? 'checked' : '' ?>/>
        <label for="position-toggle" class="form-label">ajoute nan paj prensipal</label>
        <input type="hidden" id="position" name="position" value="mainpage" <?= (isset($interview['position']) && $interview['position'] !== 'mainpage') ? 'disabled' : '' ?>/>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Sovgade Chanjman
        </button>
        <a href="<?= BASE_URL?>/admin/interviews" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>Anile
        </a>
        <a class="btn btn-danger float-end del-btn" data-id="<?= $interview['id']?>">
            <i class="fas fa-trash me-2"></i>Efase
        </a>
    </div>
</form>
