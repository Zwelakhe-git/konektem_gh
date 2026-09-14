<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Заказы услуг</h2>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h4 class="mb-0"><?= count($orders) ?></h4>
                <small>Всего заказов</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата</th>
                    <th>Услуга</th>
                    <th>Клиент</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></td>
                    <td><?= htmlspecialchars($order['service_name']) ?></td>
                    <td>
                        <?= htmlspecialchars($order['full_name']) ?><br>
                        <small class="text-muted"><?= $order['email'] ?></small>
                    </td>
                    <td><?= $order['phone'] ?></td>
                    <td>
                        <?php
                        $statusColors = [
                            'pending' => 'warning',
                            'confirmed' => 'info',
                            'in_progress' => 'primary',
                            'completed' => 'success',
                            'cancelled' => 'danger'
                        ];
                        $color = $statusColors[$order['status']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $color ?>"><?= $order['status'] ?></span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="viewOrder(<?= $order['id'] ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>