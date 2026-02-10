                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Meeting Minutes</h1>

                    <div class="card shadow mb-4">
                        <div class="card-body py-3">
                            <form method="get" action="<?= base_url('meetingminutes'); ?>" class="form-inline flex-wrap align-items-center">
                                <?php if (!empty($filterCampus)): ?>
                                <input type="hidden" name="campus" value="<?= htmlspecialchars($filterCampus); ?>">
                                <?php endif; ?>
                                <?php if (!empty($filterMinistry)): ?>
                                <input type="hidden" name="ministry" value="<?= htmlspecialchars($filterMinistry); ?>">
                                <?php endif; ?>
                                <label class="mr-2 mb-1 mb-md-0">Search</label>
                                <input type="search" name="q" class="form-control form-control-sm mr-2 mb-1 mb-md-0" placeholder="Search in summary and minutes…" value="<?= htmlspecialchars($searchQuery ?? ''); ?>" style="min-width: 220px;">
                                <button type="submit" class="btn btn-primary btn-sm mb-1 mb-md-0 mr-2">Search</button>
                                <a href="<?= base_url('meetingminutes'); ?>" class="btn btn-light btn-sm mb-1 mb-md-0">Clear</a>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <form method="get" action="<?= base_url('meetingminutes'); ?>" class="form-inline flex-wrap align-items-center">
                                <label class="mr-2 mb-1 mb-md-0">Filter by: Campus</label>
                                <select name="campus" class="form-control form-control-sm mr-3 mb-1 mb-md-0">
                                    <option value="">All</option>
                                    <?php foreach (($campuses ?? []) as $c): ?>
                                        <?php $name = trim((string) ($c['campus_name'] ?? '')); if ($name === '') { continue; } ?>
                                        <option value="<?= htmlspecialchars($name); ?>"<?= (isset($filterCampus) && $filterCampus === $name) ? ' selected' : ''; ?>><?= htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label class="mr-2 mb-1 mb-md-0">Ministry</label>
                                <select name="ministry" class="form-control form-control-sm mr-2 mb-1 mb-md-0">
                                    <option value="">All</option>
                                    <?php foreach (($ministries ?? []) as $m): ?>
                                        <?php $name = trim((string) ($m['ministry'] ?? '')); if ($name === '') { continue; } ?>
                                        <option value="<?= htmlspecialchars($name); ?>"<?= (isset($filterMinistry) && $filterMinistry === $name) ? ' selected' : ''; ?>><?= htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm mb-1 mb-md-0">Apply</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Campus</th>
                                            <th>Ministry</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($meetings)): ?>
                                            <?php foreach ($meetings as $row): ?>
                                                <?php
                                                $createdAt = $row['created_at'] ?? null;
                                                $dateFormatted = $createdAt ? (is_numeric($createdAt) ? date('Y-m-d', $createdAt) : date('Y-m-d', strtotime($createdAt))) : '—';
                                                $fullName = trim(($row['chair_first_name'] ?? '') . ' ' . ($row['chair_last_name'] ?? '')) ?: '—';
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($dateFormatted); ?></td>
                                                    <td><?= htmlspecialchars($fullName); ?></td>
                                                    <td><?= htmlspecialchars($row['campus_name'] ?? '—'); ?></td>
                                                    <td><?= htmlspecialchars($row['ministry'] ?? '—'); ?></td>
                                                    <td>
                                                        <a href="<?= base_url('meetingminutes/view/' . (int)($row['id'] ?? 0)); ?>" class="btn btn-primary btn-sm">View</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No meetings found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">
                    <script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
                    <script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'); ?>"></script>

                    <script>
                    // Initialize DataTables for Meeting Minutes table
                    $(document).ready(function () {
                        $('#dataTable').DataTable({
                            order: [[0, 'desc']]
                        });
                    });
                    </script>
