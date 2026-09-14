<section class="min-h-screen px-4 py-10">
    <h1 class="text-3xl md:text-5xl font-bold text-center mb-10">
    Eksplore eveneman
    </h1>

    <!-- Filter Tabs -->
    <div class="flex justify-center mb-8 gap-4 flex-wrap">
    <button class="px-5 py-2 rounded-full bg-purple-600 text-white font-semibold hover:bg-purple-700 transition">All</button>


    </div>

    <!-- Events Grid -->
    <div class="grid gap-6 events-grid bg-white fr1-col sm:grid-cols-2 lg:grid-cols-4 max-w-7xl mx-auto">
    
    <?php foreach($events as $event){?>
        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden"
            style="width: 100%; margin: auto; height: 350px;">
            <div class='evnt-img-container'>
                <img
                    src="${event.image_location}"
                    alt="Bandana Party"
                    class="h-48 w-full"
                />
            </div>
            <div class="p-5">
                <div class="flex justify-between text-sm text-gray-500 mb-3">
                <span><i class="fa-regular fa-calendar-days mr-1"></i>${event.eventDate}</span>
                <span><i class="fa-regular fa-clock mr-1"></i></span>
                </div>
                <p class="text-purple-600 text-sm font-medium mb-1">${event.host ?? "unknown host"}</p>
                <p class="mb-2">${event.title}</p>
                <p class="text-gray-600 text-sm mb-4">${event.location}</p>
                <div class="flex justify-between items-center">
                <span class="text-purple-600 font-bold">\$${event.price}</span>
                <a href="/?p=buytickets&f=event&id=${event.id}"><button
                    class="bg-purple-600 text-white px-3 py-2 rounded-lg hover:bg-purple-700 transition"
                >
                    Peye
                </button></a>
                </div>
            </div>
        </div>
    <?php }?>
    </div>
</section>