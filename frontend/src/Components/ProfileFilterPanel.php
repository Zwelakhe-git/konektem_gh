<!-- Filter Panel -->
<div class="collapse mb-4" id="filterPanel">
    <div class="card card-body bg-light">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="categoryFilter" class="form-label">Category</label>
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="music">Music</option>
                    <option value="events">Events</option>
                    <option value="interviews">Interviews</option>
                    <option value="news">News</option>

                    <option value="">All Artists</option>
                    <?php 
                    // Get unique artists from tracks
                    $artists = array_unique(array_column($tracks ?? [], 'artist_name'));
                    foreach ($artists as $artist): 
                    ?>
                    <option value="<?= htmlspecialchars($artist) ?>"><?= htmlspecialchars($artist) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="statusFilter" class="form-label">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="positionFilter" class="form-label">Display Position</label>
                <select class="form-select" id="positionFilter">
                    <option value="">All Positions</option>
                    <option value="mpnews_fade">Main Page (Fade)</option>
                    <option value="mpnews_slide">Main Page (Slide)</option>
                    <option value="mainpage">On Main Page</option>
                    <option value="no_pos">Not on Main Page</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <button class="btn btn-secondary w-100" id="clearFiltersBtn">
                    <i class="fas fa-undo"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>
</div>
