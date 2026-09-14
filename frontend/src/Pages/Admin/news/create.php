<h2>Ajoute nouvel</h2>

<form id="newsForm" method="POST" enctype="multipart/form-data" class="create-form">
    <!-- ВСЕ ВАШИ СУЩЕСТВУЮЩИЕ ПОЛЯ БЕЗ ИЗМЕНЕНИЙ -->
    <div class="mb-3">
        <label for="newsHeadline" class="form-label">News Headline *</label>
        <textarea type="text" class="form-control" id="newsHeadline" name="headline" required></textarea>
    </div>
    
    <div class="mb-3">
        <label for="newsCategory" class="form-label">Category *</label>
        <select class="form-control mb-3" id="newsCategory" name='category'>
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
        <input type="text" id="category" name="category" class="form-control d-none" placeholder="politics, entertainment, ..." oninput="this.value = `${this.value[0].toUpperCase()}${this.value.substr(1)}`">
    </div>
    
    <div class="mb-3">
        <label for="newsTitle" class="form-label">Tit Nouvel *</label>
        <input type="text" class="form-control" id="newsTitle" name="title" required>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="newsDate" class="form-label">Dat nouvel *</label>
                <input type="date" class="form-control" id="newsDate" name="created_at" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="news_image" class="form-label">Imaj nouvel (drag and drop)</label>
                <input type="file" class="form-control" data-preview="news-prev-img" id="news_image" name="news_image" accept="image/*">
                <img id="news-prev-img" class="img-thumbnail" style="display:none;margin-top: 10px; width: 150px; height: 150px"/>
            </div>
        </div>
    </div>
    
    <!-- ЕДИНСТВЕННОЕ ИЗМЕНЕНИЕ: добавляем класс tinymce-editor к textarea -->
    <div class="mb-3">
        <label for="fullContent" class="form-label">Full Content *</label>
        <textarea class="form-control tinymce-editor" id="fullContent" data-input-id="content"></textarea>
        <input type="hidden" name="content" id="content"/>
    </div>
    
    <!-- ВАШИ СУЩЕСТВУЮЩИЕ ЧЕКБОКСЫ БЕЗ ИЗМЕНЕНИЙ -->
    <div class="mb-3">
        <div class="col-md-6">
            <label for="cb-filter" class="form-label check">
                <input type="checkbox" class="form-control" id="cb-filter"/>
                <span class="checkmark"></span>
                add to main page content
            </label>
            <div class="mb-3" style="display: none">
                <label for="mp-pos">select position</label>
                <select id="mp-pos" class="form-control" name="position" disabled>
                    <option value="newsSlide">Slide</option>
                    <option value="fadeNews">Fade</option>
                </select>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <input type="checkbox" id="publish-cb" name="publish-cb" />
        <label for="publish-cb">Publish</label>
        <input type="number" min="0" max="1" id="publish" name="publish" value="0" hidden/>
        <script>
            let publishCheckbox = document.querySelector("input#publish-cb");
            let publishInput = document.querySelector("input#publish");
            publishCheckbox?.addEventListener('change', function(){
                publishInput.value = Number(this.checked);
            });
        </script>
    </div>
    <button type="button" id="submitBtn" class="btn btn-primary">Ajoute nouvel</button>
    <a href="<?= BASE_URL?>/admin/news" class="btn btn-secondary">Anile</a>
</form>
<script>
    // ВАШ СУЩЕСТВУЮЩИЙ СКРИПТ БЕЗ ИЗМЕНЕНИЙ
    document.getElementById('newsDate').value = new Date().toISOString().split('T')[0];
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
    
    // Показ/скрытие позиции (добавляем к существующему)
    document.getElementById('cb-filter').addEventListener('change', function() {
        const positionDiv = document.getElementById('mp-pos').parentNode;
        positionDiv.style.display = this.checked ? 'block' : 'none';
        document.getElementById('mp-pos').disabled = !this.checked;
    });

    // document.getElementById('submitBtn').addEventListener('click', function(){
    //     const fullContent = tinymce ? tinymce.get('fullContent').getContent() : document.getElementById('fullContent').value;
    //     if(fullContent.trim().length === 0){
    //         showError("Content is empty");
    //         return;
    //     }
    //     document.getElementById('content').value = fullContent;
    //     document.querySelector('.create-form').requestSubmit();
    // });
</script>
