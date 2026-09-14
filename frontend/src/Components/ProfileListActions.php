<!-- Display Toggle -->
<div class="btn-group" role="group" aria-label="Display mode">
    <button type="button" class="btn btn-outline-secondary active" id="gridViewBtn" title="Grid view">
        <i class="fas fa-th-large"></i>
    </button>
    <button type="button" class="btn btn-outline-secondary" id="listViewBtn" title="List view">
        <i class="fas fa-list"></i>
    </button>
</div>

<!-- Filter Button -->
<button class="btn btn-outline-primary" id="filterToggleBtn" data-bs-toggle="collapse" data-bs-target="#filterPanel">
    <i class="fas fa-filter"></i>
    <span class="d-none d-sm-inline">Filter</span>
    <span class="badge bg-primary rounded-pill" id="filterCount">0</span>
</button>

<!-- Selection Mode Button -->
<button class="btn btn-outline-success" id="selectionModeBtn" title="add/remove articles to/from main page">
    <i class="fas fa-check-double"></i>
    <span class="d-none d-sm-inline">Select</span>
</button>