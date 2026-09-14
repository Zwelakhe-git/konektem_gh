<h2>Redije liv</h2>

<form method="POST" enctype="multipart/form-data" class="create-form">
    <div class="mb-3">
        <label for="title" class="form-label">title</label><span class="text-danger">*</span>
        <input type="text" class="form-control" id="title" name="title" placeholder="upload file to edit" readonly required>
    </div>
    <div class="mb-3">
        <label for="author" class="form-label">author</label><span class="text-danger">*</span>
        <input type="text" class="form-control" id="author" name="author" required>
    </div>
    <div class="mb-3">
        <label for="release_date" class="form-label">publish date</label><span class="text-danger">*</span>
        <input type="date" class="form-control" id="release_date" name="release_date" required>
    </div>
    
    <div class="mb-3">
        <label for="isbn" class="form-label">ISBN</label>
        <input type="text" class="form-control" id="isbn" name="isbn">
    </div>
    <div class="mb-3">
        <label for="book-genre">Book Genre</label><span class="text-danger">*</span>
        <input class="form-control" type="text" name="new-book-genre" id="new-genre"
               style="display: none" placeholder="enter book genre"/>
        <select class="form-control" name="book-genre" id="book-genre" required>
            <option value="new-genre">New genre</option>
            <option value="Thriller" selected>Thriller</option>
            <option value="Science">Science</option>
            <option value="Fiction">Fiction</option>
        </select>
        <script>
            let genreOpts = document.querySelector("select[name='book-genre']");
            genreOpts?.addEventListener('change', (e)=>{
                if(genreOpts.value === "new-genre"){
                    let inpEl = document.querySelector("input[name='new-book-genre']");
                    inpEl.style.display = 'block';
                    inpEl.required = true;
                    inpEl.focus = true;
                    genreOpts.disabled = true;
                    genreOpts.style.display = 'none';
                }
            });
        </script>
    </div>
    <div class="mb-3">
        <label for="description-inp" class="form-label">Deskripsyon</label>
        <textarea class="form-control tinymce-editor" id="description-inp" data-input-id="description" rows="5"></textarea>
        <input type="hidden" id="description" name="description"/>
    </div>
    
    <div class="mb-3">
        <label for="book_image" class="form-label">Imaj liv</label>
        <div class="hidden" id="cover-image-inp-field">
            <input type="file" class="form-control" id="book_image" name="book_image" accept="image/*" data-preview="book-prev-img">
            <span id="cover-image-helper">(Add custom cover image)</span>
        </div>
        <img id="book-prev-img" style="display:none;width: 150px; height: 150px; margin-top: 10px"/>
        <i class="fa-solid fa-spinner fa-spin text-primary hidden" id="cover-image-load"></i>
    </div>
    <div class="mb-3">
        <label for="extra_imges" class="form-label">more images</label>
        <input type="checkbox" id="extra_imges" name="extra_imges" disabled>
        <div class="mb-3" style="display: none;">
        	<input type="file" accept="image/*" id="extra-img" name="extra-img" disabled>
            <div class="btns flex row">
                <button type="button" id="ximg-save">save</button>
                <button type="button" id="ximg-del">delete</button>
            </div>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="book_file" class="form-label">File (document)</label><span class="text-danger">*</span>
        <input type="file" accept="application/pdf" class="form-control" id="book_file" name="book_file">
    </div>
    <button type="button" id="submitBtn" class="btn btn-primary">Ajoute liv</button>
    <a href="<?= BASE_URL ?>/admin/books" class="btn btn-secondary">Refize</a>
</form>


