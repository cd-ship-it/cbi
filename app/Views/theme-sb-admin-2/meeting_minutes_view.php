                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Meeting Minutes – View</h1>
                    <div class="mt-4">
                        <a href="<?= base_url('meetingminutes'); ?>" class="btn btn-secondary btn-sm">← Back to list</a>
                    </div>

                    <?php
                    $meeting = $meeting ?? [];
                    $fullName = trim(($meeting['chair_first_name'] ?? '') . ' ' . ($meeting['chair_last_name'] ?? '')) ?: '—';
                    $createdAt = $meeting['created_at'] ?? null;
                    $chair_email = $meeting['chair_email'] ?? null;
                    $dateFormatted = $createdAt ? (is_numeric($createdAt) ? date('Y-m-d', $createdAt) : date('Y-m-d', strtotime($createdAt))) : '—';
                    $pastorInCharge = trim((string) ($meeting['Pastor_in_charge'] ?? $meeting['pastor_in_charge'] ?? ''));
                    $attendees = trim((string) ($meeting['Attendees'] ?? $meeting['attendees'] ?? ''));
                    $meetingType = trim((string) ($meeting['meeting_type'] ?? ''));
                    $description = trim((string) ($meeting['Description'] ?? $meeting['description'] ?? ''));
                    $comments = trim((string) ($meeting['Comments'] ?? $meeting['comments'] ?? ''));
                    $filePath   = trim($meeting['file_path'] ?? '');
                    $documentUrl = trim($meeting['document_url'] ?? '');
                    $pastedText = trim((string) ($meeting['pasted_text'] ?? ''));
                    $uploadsBaseUrl = rtrim((string) (getenv('UPLOADS_BASE_URL') ?: 'uploads/'), '/');
                    $isLocalhost = isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || $_SERVER['HTTP_HOST'] === '127.0.0.1');
                    if ($filePath !== '' && $isLocalhost) {
                        $filePathHref = $uploadsBaseUrl . '/' . basename($filePath);
                        $filePathLabel = basename($filePath);
                    } else {
                        $filePathHref = $filePath;
                        $filePathLabel = $filePath;
                    }
                    $fileUrl = (strpos($filePathHref, 'http') === 0) ? $filePathHref : base_url($filePathHref);
                    $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    $isWord = in_array($fileExt, ['doc', 'docx'], true);
                    $viewMinutesHref = ($filePath !== '' && $isWord)
                        ? 'https://view.officeapps.live.com/op/view.aspx?src=' . rawurlencode($fileUrl)
                        : $fileUrl;
                    $hasAny = $filePath !== '' || $documentUrl !== '' || $pastedText !== '';
                    $aiSummaryText = trim((string) ($meeting['ai_summary'] ?? '')) ?: '—';
                    $isPdfMinutes = ($fileExt === 'pdf' && $filePath !== '');
                    ?>
                    <style>
                    @media (min-width: 768px) {
                        .meeting-row .meeting-col-left { flex: 0 0 70%; max-width: 70%; }
                        .meeting-row .meeting-col-right { flex: 0 0 30%; max-width: 30%; }
                    }
                    </style>

                    <div class="row meeting-row">
                        <!-- Left column (70%): AI Summary. On mobile: second (below Meta Data) -->
                        <div class="col-12 col-md-7 meeting-col-left order-2 order-md-1 mb-4 mb-md-0">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Summary by AI</h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($isPdfMinutes): ?>
                                        <p class="mb-0">
                                            No AI summary for PDF file.
                                            Click the
                                            <a href="<?= htmlspecialchars($viewMinutesHref); ?>" target="_blank" rel="noopener">View Minutes</a>
                                            button to see the full minutes.
                                        </p>
                                    <?php else: ?>
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($aiSummaryText)); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Right column (30%): Meta Data. On mobile: first (on top) -->
                        <div class="col-12 col-md-5 meeting-col-right order-1 order-md-2">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Meeting Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3"><strong>Uploaded Date:</strong><br><?= htmlspecialchars($dateFormatted); ?></div>
                                    <div class="mb-3"><strong>Chair's Name:</strong><br><?= htmlspecialchars($fullName); ?></div>
                                    <div class="mb-3"><strong>Campus:</strong><br><?= htmlspecialchars($meeting['campus_name'] ?? '—'); ?></div>
                                    <div class="mb-3"><strong>Ministry:</strong><br><?= htmlspecialchars($meeting['ministry'] ?? '—'); ?></div>
                                    <?php if ($pastorInCharge !== ''): ?>
                                    <div class="mb-3"><strong>Pastor in charge:</strong><br><?= htmlspecialchars($pastorInCharge); ?></div>
                                    <?php endif; ?>
                                    <?php if ($chair_email !== ''): ?>
                                    <div class="mb-3"><strong>Chair Email:</strong><br><?= htmlspecialchars($chair_email); ?></div>
                                    <?php endif; ?>
                                    <?php if ($attendees !== ''): ?>
                                    <div class="mb-3"><strong>Attendees:</strong><br><?= htmlspecialchars($attendees); ?></div>
                                    <?php endif; ?>
                                    <?php if ($meetingType !== ''): ?>
                                    <div class="mb-3"><strong>Meeting type:</strong><br><?= htmlspecialchars($meetingType); ?></div>
                                    <?php endif; ?>
                                    <?php if ($description !== ''): ?>
                                    <div class="mb-3"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($description)); ?></div>
                                    <?php endif; ?>
                                    <?php if ($comments !== ''): ?>
                                    <div class="mb-3"><strong>Comments:</strong><br><?= nl2br(htmlspecialchars($comments)); ?></div>
                                    <?php endif; ?>
                                    <?php if ($hasAny): ?>
                                        <ul class="list-group list-group-flush mt-2 mb-0">
                                            <?php if ($filePath !== ''): ?>
                                                <li class="list-group-item d-flex flex-wrap align-items-center px-0 mb-3">
                                                    <a href="<?= htmlspecialchars($viewMinutesHref); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm mr-2 mb-1">View original Minutes</a>
                                                    <a href="<?= htmlspecialchars($fileUrl); ?>" download class=" mb-1">Download Meeting Minutes</a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($documentUrl !== ''): ?>
                                                <li class="list-group-item d-flex align-items-center px-0 mb-3">
                                                    <a href="<?= htmlspecialchars($documentUrl); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm">View minutes in Google Doc</a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($pastedText !== ''): ?>
                    <p class="mb-3">
                        <a id="show-full-minutes-btn" href="#pasted-meetings" class="btn btn-outline-primary btn-sm" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="pasted-meetings">Show Full Minutes</a>
                    </p>
                    <?php endif; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted mb-0 mt-2">No files or links for this meeting.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($pastedText !== ''): ?>
                    <div id="pasted-meetings" class="card shadow mb-4 collapse">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Meeting Minutes</h6>
                        </div>
                        <div class="card-body">
                            <?= strip_tags($pastedText, '<p><br><strong><b><em><i><ul><ol><li><h1><h2><h3><h4><a><span><div>'); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="<?= base_url('meetingminutes'); ?>" class="btn btn-secondary btn-sm">← Back to list</a>
                    </div>

                    <script>
                    (function () {
                        var btn = document.getElementById('show-full-minutes-btn');
                        var target = document.getElementById('pasted-meetings');
                        if (!btn || !target) return;

                        btn.addEventListener('click', function () {
                            var isOpen = target.classList.contains('show');

                            // If we're about to open it, scroll and change label to "Close"
                            if (!isOpen) {
                                setTimeout(function () {
                                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }, 150);
                                btn.textContent = 'Close Full Minutes';
                            } else {
                                // We're about to close it
                                btn.textContent = 'Show Full Minutes';
                            }
                        });
                    })();
                    </script>
                    
