<section class="min-h-screen px-4 py-10">
    <h1 class="text-3xl md:text-5xl font-bold text-center mb-10">
    Eksplore Sevis
    </h1>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto">
    <?php foreach($services as $service):?>
        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden service-card" data-itemid="<?= $service['id']?>">
            <div class="service-img-cont">
                <img class="service-img w-full object-cover"
                    src="<?= $service['image_url']?>"
                    alt="Brand Identity"
                    loading="lazy"
                />
                <div class="image-fallback d-none h-100 w-100">
                    <i class="fa-solid fa-image"></i>
                </div>
            </div>
            <div class="p-5">
                <h2 class="text-lg font-semibold mb-1"><?= $service['name']?></h2>
                <p class="text-sm text-purple-600 mb-2">By ...</p>

                <div class="flex justify-between items-center mt-4">
                <span class="text-xl font-bold text-purple-600">$<?= $service['price'] ?? 0?></span>
                <a class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition buy-btn">
                    Make an order
                </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div><div class="works-container">
        <div class="section-title">
            <h1>Travay Nou Yo</h1>
        </div>
        <div class="works-list">
        <?php foreach($konektemWorksImages as $image_url):?>
            <div class="works-l1">
                <div class="works-img-container">
                    <img src="<?= $image_url?>" loading="lazy"/>
                </div>
            </div>
        <?php endforeach;?>
        </div>
    </div>
    <br/>

</section>
<script>
    let buyBtns = document.querySelectorAll('.service-card .buy-btn');
    buyBtns.forEach(btn => {
        btn.addEventListener('click', async(e)=>{
            try {
                const initialText = btn.textContent;
                btn.innerHTML = `<i class="fa-solid fa-spinner"></i>`;
                body = {
                    id: e.target.closest('.service-card').dataset.itemid,
                    type: 'service'
                };
                const response = await fetch('/store/api/create-order-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(body)
                });
                const result = await response.json();
                btn.innerHTML = '';
                btn.textContent = initialText;
                if(!response.ok || !result.success){
                    showError(result.message ?? 'Server error');
                    return;
                }
                //showSuccess(base64UrlDecode(result.token.split('.')[1]));
                if(!result.token){
                    showError('Failed to create order token');
                    return;
                }
                window.location.href = `/store/counter?token=${result.token}`;

            } catch(err){
                console.error(err);
                showError(err);
            }
        })
    });
</script>

