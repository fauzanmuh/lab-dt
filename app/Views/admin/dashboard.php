<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted mb-0">Total Users</h6>
                    <div class="p-2 bg-primary bg-opacity-10 rounded-circle">
                        <i class="bi bi-people text-primary"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1"><?= $stats['users'] ?? 0 ?></h3>
                <small class="text-muted fw-medium">
                    Registered members
                </small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted mb-0">Total Publications</h6>
                    <div class="p-2 bg-success bg-opacity-10 rounded-circle">
                        <i class="bi bi-journal-text text-success"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1"><?= $stats['publications'] ?? 0 ?></h3>
                <small class="text-muted fw-medium">
                    Research items
                </small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted mb-0">Total Content</h6>
                    <div class="p-2 bg-info bg-opacity-10 rounded-circle">
                        <i class="bi bi-collection text-info"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1"><?= ($stats['news'] ?? 0) + ($stats['gallery'] ?? 0) ?></h3>
                <small class="text-muted fw-medium">
                    News & Gallery
                </small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted mb-0">Pending Approvals</h6>
                    <div class="p-2 bg-warning bg-opacity-10 rounded-circle">
                        <i class="bi bi-hourglass-split text-warning"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1"><?= $stats['pending_approvals'] ?? 0 ?></h3>
                <small class="text-danger fw-medium">
                    Needs attention
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="card-title mb-1 fw-bold">Recent Activity</h5>
                    <p class="text-muted small mb-0">Latest system events and updates</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">User
                                </th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Action
                                </th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Module
                                </th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Time</th>
                                <th
                                    class="text-end pe-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentActivity)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No recent activity found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentActivity as $activity): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar overflow-hidden rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">
                                                    <?php if (!empty($activity['foto_profil'])): ?>
                                                        <img src="/uploads/foto_profil/<?= htmlspecialchars($activity['foto_profil']) ?>"
                                                            alt="Profile" class="w-100 h-100 object-fit-cover">
                                                    <?php else: ?>
                                                        <div
                                                            class="bg-light text-primary w-100 h-100 d-flex align-items-center justify-content-center">
                                                            <?= strtoupper(substr($activity['user_name'], 0, 2)) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <span
                                                    class="fw-bold text-dark"><?= htmlspecialchars($activity['user_name']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-dark"><?= htmlspecialchars($activity['action']) ?></span>
                                                <span class="text-xs text-muted text-truncate" style="max-width: 200px;">
                                                    <?= htmlspecialchars($activity['title']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $moduleClass = match ($activity['module']) {
                                                'News' => 'bg-info-subtle text-info',
                                                'Gallery' => 'bg-primary-subtle text-primary',
                                                'Publications' => 'bg-success-subtle text-success',
                                                default => 'bg-secondary-subtle text-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $moduleClass ?> border"><?= $activity['module'] ?></span>
                                        </td>
                                        <td class="text-secondary text-sm">
                                            <?php
                                            // Simple time formatting
                                            if (strlen($activity['activity_time']) == 4) {
                                                echo $activity['activity_time']; // Year only
                                            } else {
                                                echo date('M d, H:i', strtotime($activity['activity_time']));
                                            }
                                            ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <?php
                                            $statusClass = match ($activity['status']) {
                                                'approved' => 'bg-success-subtle text-success',
                                                'rejected' => 'bg-danger-subtle text-danger',
                                                default => 'bg-warning-subtle text-warning'
                                            };
                                            ?>
                                            <span
                                                class="badge <?= $statusClass ?> border"><?= ucfirst($activity['status']) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>