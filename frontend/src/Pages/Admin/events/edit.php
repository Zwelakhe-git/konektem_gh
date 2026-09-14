<h2>Redije Eveneman</h2>

<form method="POST" enctype="multipart/form-data" class="edit-form">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="title" class="form-label">Non eveneman *</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= htmlspecialchars($event['title'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description-inp" class="form-label">Tit eveneman</label>
                <textarea class="form-control tinymce-editor" id="description-inp" data-input-id="description" rows="4"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
                <input type="hidden" name="description" id="description" />
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="eventDate" class="form-label">Dat eveneman *</label>
                        <input type="date" class="form-control" id="eventDate" name="event_date" 
                               value="<?= $event['event_date'] ?? '' ?>" required min="<?= $event['event_date'] ?? date("now")?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="price" class="form-label">Pri (USD.)</label>
                        <input type="number" class="form-control" id="price" name="price" 
                               min="0" value="<?= $event['price'] ?? 0 ?>">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Lye Eveneman *</label>
                <input type="text" class="form-control" id="location" name="location" 
                       value="<?= htmlspecialchars($event['location'] ?? '') ?>" required>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="mb-3">
                <label for="event_image" class="form-label">Imaj Eveneman</label>
                <input type="file" class="form-control" id="event_image" name="event_image" 
                       accept="image/*" data-preview="eventPreview">
                
                <?php if ($event['image_url']): ?>
                <div class="mt-2">
                    <p>Imaj aktyel:</p>
                    <img src="<?= $event['image_url'] ?>" width="200" class="img-thumbnail" id="eventPreview" loading="lazy">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                        <label class="form-check-label" for="remove_image">
                            Siprime imaj aktyel la
                        </label>
                    </div>
                </div>
                <?php else: ?>
                <div class="mt-2 text-center">
                    <div class="text-muted border rounded p-4">
                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                        <br>Imaj non ensere
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Enfomasyon de eveneman</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>ID:</strong> <?= $event['id'] ?? 'Новый' ?>
                    </div>
                    <div class="mb-2">
                        <strong>Status:</strong>
                        <?php
                        $eventDate = strtotime($event['event_date'] ?? '');
                        $now = time();
                        if ($eventDate > $now) {
                            echo '<span class="badge bg-success">Upcoming</span>';
                        } else {
                            echo '<span class="badge bg-secondary">Passed</span>';
                        }
                        ?>
                    </div>
                    <div class="mb-2">
                        <strong>Created at:</strong><br>
                        <?= date('d.m.Y H:i', strtotime($event['created_at'] ?? 'now')) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
    	<div class="col-md-6">
            <input type="checkbox" id="position-toggle"
             onchange="document.querySelector('input#position').disabled = !this.checked;"
             <?= $event['position'] === 'mainpage' ? 'checked' : ''?>/>
        	<label for="position-toggle" class="form-label">ajoute nan paj prensipal</label>
            <input type="hidden" id="position" name="position" value="mainpage" <?= $event['position'] !== 'mainpage' ? 'disabled' : ''?>/>
        </div>
    </div>
    <div class="mt-4">
        <button type="button" id="submitBtn" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Sovgade chanjman 
        </button>
        <a href="<?= BASE_URL?>/admin/events" class="btn btn-secondary">
            Anile
        </a>
        <a class="btn btn-danger float-end del-btn" data-id="<?= $event['id']?>">
            <i class="fas fa-trash me-2"></i>Efase
        </a>
    </div>
</form>
