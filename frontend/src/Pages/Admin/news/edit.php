<h2>Redije nouvel</h2>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="edit-form">
    <!-- ВАШИ СУЩЕСТВУЮЩИЕ ПОЛЯ БЕЗ ИЗМЕНЕНИЙ -->
    <div class="mb-3">
        <label for="newsHeadline" class="form-label">News Headline *</label>
        <textarea type="text" class="form-control" id="newsHeadline" name="headline" required><?= htmlspecialchars($article['headline'])?></textarea>
    </div>
    
    <div class="mb-3">
        <label for="newsCategory" class="form-label">Category *</label>
        <select class="form-control" id="newsCategory" name='category'>
            <option value="new">New category</option>
            <option value='Politics'>Politik</option>
            <option value='Security'>Sekirite</option>
            <option value='Society'>Sosyete</option>
            <option value='Diplomatie'>Diplomasi</option>
            <option value='International'>Entènasyonal</option>
            <option value='Economy'>Ekonomi</option>
            <option value='Finance'>Finans & Envestisman</option>
            <option value='Entertainment'>Divètisman</option>
            <option value='Environment'>Anviwònman</option>
            <option value='Family'>Fanmi</option>
            <option value='Culture'>Kilti</option>
            <option value='Music & Video'>Mizik e Videyo</option>
            <option value='Cinema'>Sinema</option>
            <option value='Mode'>Mòd & Estil</option>
            <option value='Personality'>Pèsonalite</option>
            <option value='Technology'>Teknoloji</option>
            <option value='Kitchen'>Kizin & Resèt</option>
            <option value='Trip'>Vwayaj</option>
            <option value='Health'>Sante</option>
            <option value='Sport'>Espò</option>
            <option value='Education'>Edikasyon</option>
            <option value='Religion'>Relijyon</option>
        </select>
        <script>
        	// dynamically select option
            const options = document.querySelectorAll("#newsCategory option");
            let selectedOpt = (Array.from(options)).find(el => el.value === '<?= $article['category']?>');
            if(selectedOpt){
                //console.log(document.querySelector(`#newsCategory option[value="${selectedOpt.value}"]`));
                document.querySelector(`#newsCategory option[value="${selectedOpt.value}"]`).selected = true;
            }
            
        </script>
    </div>
    
    <div class="mb-3">
        <label for="newsTitle" class="form-label">Tit nouvel *</label>
        <input type="text" class="form-control" id="newsTitle" name="title" 
               value="<?= htmlspecialchars($article['title'] ?? '') ?>" required>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="newsDate" class="form-label">Dat nouvel *</label>
                <input type="date" class="form-control" id="newsDate" name="created_at" 
                       value="<?= explode(' ', $article['published_at'] ?? $article['created_at'])[0] ?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="news_image" class="form-label">Imaj nouvel</label>
                <input type="file" class="form-control" id="news_image" name="news_image" accept="image/*">
                <?php if ($article['image_url']): ?>
                <div class="mt-2">
                    <p>Imaj aktyel:</p>
                    <img src="<?= $article['image_url'] ?>" width="200" class="img-thumbnail">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- ЕДИНСТВЕННОЕ ИЗМЕНЕНИЕ: добавляем класс tinymce-editor -->
    <div class="mb-3">
        <label for="fullContent" class="form-label">Full Content *</label>
        <textarea class="form-control tinymce-editor" id="fullContent" data-input-id="content"><?= $article['content'] ?></textarea>
        <input type="hidden" name="content" id="content"/>
    </div>
    
    <!-- ВАШ СУЩЕСТВУЮЩИЙ ЧЕКБОКС БЕЗ ИЗМЕНЕНИЙ -->
    <div class="mb-3">
        <div class="col-md-6">
            <label for="cb-filter" class="form-label check">
                <input type="checkbox" class="form-control" id="cb-filter" <?= $article['position'] !== 'no_pos' ? 'checked' : ''?>/>
                <span class="checkmark"></span>
                add to main page content
            </label>
            <div class="mb-3" style="display: <?= $article['position'] !== 'no_pos' ? 'block' : 'none'?>">
                <label for="mpitem-pos">select position</label>
                <select id="mpitem-pos" class="form-control" name="position" disabled>
                    <option value="newsSlide" <?= $article['position'] === 'newsSlide' &&  'selected'?>>Slide</option>
                    <option value="fadeNews" <?= $article['position'] === 'fadeNews' &&  'selected'?>>Fade</option>
                </select>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="publish-cb">Publish</label>
        <input type="checkbox" id="publish-cb" name="publish-cb" <?= $article['published_at'] !== null ? 'checked' : ''?>/>
        <input type="number" min="0" max="1" id="publish" name="publish" value="<?= $article['published_at'] !== null?>" hidden/>
        <script>
            let publishCheckbox = document.querySelector("input#publish-cb");
            let publishInput = document.querySelector("input#publish");
            publishCheckbox?.addEventListener('change', function(){
                publishInput.value = Number(this.checked);
            });
        </script>
    </div>
    
    <button type="button" id="submitBtn" class="btn btn-primary">Renouvle imaj</button>
    <a href="<?= BASE_URL ?>/admin/news" class="btn btn-secondary">Anile</a>
</form>

<script>
// ВАШ СУЩЕСТВУЮЩИЙ СКРИПТ БЕЗ ИЗМЕНЕНИЙ
newsCategory = document.querySelector("select[id='newsCategory']");
if(newsCategory){
    if(newsCategory.value == "new"){
        let inpElem = document.querySelector("input[id='category']");
        if(inpElem){
            inpElem.classList.remove('d-none');
            newsCategory.classList.add('d-none');
        }
    }
    newsCategory.addEventListener("change", function(){
        if(newsCategory.value == "new"){
            if(inpElem){
                inpElem.classList.remove('d-none');
                newsCategory.classList.add('d-none');
            }
        }
    })
}

// Показ/скрытие позиции (добавляем)
document.getElementById('cb-filter').addEventListener('change', function() {
    const positionDiv = document.querySelector('#mpitem-pos').parentNode;
    positionDiv.style.display = this.checked ? 'block' : 'none';
    document.querySelector('#mpitem-pos').disabled = !this.checked;
});

// document.getElementById('submitBtn').addEventListener('click', function(){
//     const fullContent = tinymce ? tinymce.get('fullContent').getContent() : document.getElementById('fullContent').value;
//     if(fullContent.trim().length === 0){
//         showError("Content is empty");
//         return;
//     }
//     document.getElementById('content').value = fullContent;
//     document.querySelector('.edit-form').requestSubmit();
// });
</script>