<h2>Redije sevis</h2>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="edit-form">
    <div class="mb-3">
        <label for="name" class="form-label">Non sevis</label>
        <input type="text" class="form-control" id="name" name="name" 
               value="<?= htmlspecialchars($service['name'] ?? '') ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsyon</label>
        <textarea class="form-control tinymce-editor" id="description" name="description" rows="5" ><?= ($service['description'] ?? '') ?></textarea>
    </div>
    
    <div class="mb-3">
        <label for="book_image" class="form-label">Imaj sevis</label>
        <input type="file" class="form-control" id="book_image" name="book_image" accept="image/*" data-preview="book-prev-img">
        <img id="book-prev-img" style="display:none;width: 150px; height: 150px; margin-top: 10px"/>
        <?php if ($service['image_url']): ?>
        <div class="mt-2">
            <p>Aktyel sevis:</p>
            <img src="<?= $service['image_url'] ?>" width="200" class="img-thumbnail">
        </div>
        <?php endif; ?>
    </div>
    
    <button type="submit" class="btn btn-primary">Ajoute sevis</button>
    <a href="<?= BASE_URL?>/admin/services" class="btn btn-secondary">Refize</a>
</form>