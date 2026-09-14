<h2>Redije liv</h2>

<form method="POST" enctype="multipart/form-data" class="edit-form">
    <div class="mb-3">
        <label for="title" class="form-label">title</label><span class="text-danger">*</span>
        <input type="text" class="form-control" id="title" name="title" 
               value="<?= htmlspecialchars($book['title'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="author" class="form-label">author</label><span class="text-danger">*</span>
        <input type="text" class="form-control" id="author" name="author" 
               value="<?= htmlspecialchars($book['author'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="release_date" class="form-label">publish date</label><span class="text-danger">*</span>
        <input type="date" class="form-control" id="release_date" name="release_date" 
               value="<?= htmlspecialchars($book['release_date'] ?? '') ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="isbn" class="form-label">ISBN</label>
        <input type="text" class="form-control" id="isbn" name="isbn" 
               value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="book-genre">Book Genre</label><span class="text-danger">*</span>
        <input class="form-control" type="text" name="new-book-genre" id="new-genre"
               style="display: none" placeholder="enter book genre"/>
        <select class="form-control" name="book-genre" id="book-genre" required>
            <option value="Thriller" <?= $book['genre'] == "Thriller" ? "selected" : ""?>>Thriller</option>
            <option value="Science" <?= $book['genre'] == "Science" ? "selected" : ""?>>Science</option>
            <option value="Fiction" <?= $book['genre'] == "Fiction" ? "selected" : ""?>>Fiction</option>
            <option value="new-genre">New genre</option>
        </select>
        <script>
            let genreOpts = document.querySelector("select[name='book-genre']");
            genreOpts?.addEventListener('change', (e)=>{
                if(genreOpts.value === "new-genre"){
                    let inpEl = document.querySelector("input[name='new-book-genre']");
                    inpEl.style.display = "block";
                    inpEl.required = true;
                    inpEl.focus = true;
                    genreOpts.disabled = true;
                    genreOpts.style.display = 'none';
                }
            });
            let allOpts = genreOpts.querySelectorAll('option');
            let currOpt = Array.from(allOpts).find(opt => {
                return "<?= $book['genre'] ?>" == opt.value;
            });
            if(currOpt){
                currOpt.selected = true;
            } else if("<?= $book['genre'] ?>".trim().length > 0) {
                let newOpt = document.createElement('option');
                newOpt.value = "<?= $book['genre'] ?>"
                genreOpts.appendChild(newOpt);
                newOpt.selected = true;
            }
        </script>
    </div>
    
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsyon</label>
        <textarea class="form-control tinymce-editor" id="description-inp" data-input-id="description" rows="5"><?= $book['description'] ?? '' ?></textarea>
        <input type="hidden" id="description" name="description"/>
    </div>
    
    <div class="mb-3">
        <label for="book_image" class="form-label">Imaj liv</label>
        <input type="file" class="form-control" id="book_image" name="book_image" accept="image/*" data-preview="book-prev-img">
        <img id="book-prev-img" style="display:none;width: 150px; height: 150px; margin-top: 10px"/>
        <?php if ($book['image_url']): ?>
        <div class="mt-2">
            <p>cover:</p>
            <img src="<?= $book['image_url'] ?>" width="200" class="img-thumbnail" loading="lazy">
        </div>
        <?php endif; ?>
    </div>
    <div class="md-3">
        <div class="d-flex gap-1 items-center">
            <div class="checkbox <?= $book['public'] ? 'checked' : ''?>" data-sz="15">
                <div class="checkbox-fill"></div>
            </div>
            <input type="hidden" min="0" max="1" name="public" id="publish-inp" value="<?= $book['public']?>"/>
            <div>
                <label for="publish-inp">publish</label>
                <p class="input-hint bold">(make available to the public)</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="book_file" class="form-label">File (document)</label><span class="text-danger">*</span>
                <input type="file" class="form-control" id="book_file" name="book_file">
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mt-3">
                <div class="card-body">
                    <h6>Enfomasyon fichye</h6>
                    <?php if ($book['pdfUrl']): ?>
                    <p><strong>fichye:</strong> <?= basename($book['pdfUrl']) ?></p>
                    <p><strong>Tip:</strong> PDF</p>
                    <?php else: ?>
                    <p class="text-muted">Fichye non telechaje</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Ajoute liv</button>
    <a href="<?= BASE_URL?>/user/me/books" class="btn btn-secondary">Refize</a>
</form>