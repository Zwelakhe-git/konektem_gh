<div class="bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden">
    <div class="service-img-cont">
        <img class="service-img w-full object-cover"
        src="<?= $service['image_url'] ?? $service['image_url'] ?>"
        alt="Brand Identity"/>
    </div>
    <div class="p-5">
    <h2 class="text-lg font-semibold mb-1"><?= $service['name']?></h2>
    <p class="text-sm text-purple-600 mb-2">By ...</p>

    <div class="flex justify-between items-center mt-4">
        <span class="text-xl font-bold text-purple-600">$</span>
        <a href="?p=orderservice&id=<?= $service['id']?>"
        class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition"
        >
        Make an order
        </a>
    </div>
    </div>
</div>