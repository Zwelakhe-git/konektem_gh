<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>News control</h2>
    <a href="<?= BASE_URL?>/admin/news/create" class="btn btn-primary">
        <i class="fas fa-plus d-none d-md-inline"></i> Ajoute nouvel
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imaj</th>
                    <th>Tit</th>
                    <th>Dat</th>
                    <th>Aksyon</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= $article['id'] ?></td>
                    <td>
                        <?php if ($article['image_url']): ?>
                        <img src="<?= $article['image_url'] ?>" width="50" height="50" style="object-fit: cover;" class="rounded">
                        <?php else: ?>
                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($article['title']) ?></td>
                    <td><?= $article['published_at'] ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/news/<?= $article['id'] ?>/edit" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a 
                           class="btn btn-sm btn-danger del-btn" data-id="<?= $article['id']?>">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>