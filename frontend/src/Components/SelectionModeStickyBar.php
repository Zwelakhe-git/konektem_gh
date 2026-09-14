<!-- Selection Mode Sticky Bar -->
<div id="selectionBar" style="display: none; position: sticky; top: 0; z-index: 1000; background: #fff; border-bottom: 2px solid #28a745; padding: 10px 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                <label class="form-check-label" for="selectAllCheckbox">
                    Select All
                </label>
            </div>
            <span class="badge bg-primary" id="selectedCount">0 selected</span>
        </div>
        
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <label for="positionSelect" class="mb-0 me-1" style="font-weight: 500; font-size: 0.9rem;">Position:</label>
                <select class="form-select form-select-sm" id="positionSelect" style="width: auto;">
                    <!-- Options populated by JavaScript based on current item type -->
                </select>
            </div>
            
            <button class="btn btn-success btn-sm" id="addToMainBtn">
                <i class="fas fa-plus"></i> Add
            </button>
            <button class="btn btn-danger btn-sm" id="removeFromMainBtn">
                <i class="fas fa-minus"></i> Remove
            </button>
            <button class="btn btn-secondary btn-sm" id="cancelSelectionBtn">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </div>
</div>