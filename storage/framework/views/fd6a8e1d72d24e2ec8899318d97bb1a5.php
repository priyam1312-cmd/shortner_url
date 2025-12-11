<?php $__env->startSection('title', 'Generated Short URLs'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Generated Short URLs</h5>
        
        <?php if(auth()->user()->isSales() || auth()->user()->isManager()): ?>
        <div class="mb-4 p-3 bg-light rounded">
            <h6 class="mb-3 fw-semibold">Generate Short URL</h6>
            <form action="<?php echo e(route('urls.store')); ?>" method="POST" class="d-flex gap-2">
                <?php echo csrf_field(); ?>
                <input type="url" name="long_url" class="form-control" placeholder="e.g. https://sembark.com/travel-software/features/best-itinerary-builder" required>
                <button type="submit" class="btn btn-primary">Generate</button>
            </form>
        </div>
        <?php endif; ?>
        
        <div class="mb-4 p-3 bg-light rounded">
            <h6 class="mb-3 fw-semibold">View and Download based on Date Interval</h6>
            <div class="d-flex gap-2 align-items-end flex-wrap">
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label mb-1">Date Filter</label>
                    <form action="<?php echo e(route('urls.index')); ?>" method="GET" id="dateFilterForm">
                        <select name="date_filter" class="form-select" onchange="document.getElementById('dateFilterForm').submit()">
                            <option value="today" <?php echo e(request('date_filter') == 'today' ? 'selected' : ''); ?>>Today</option>
                            <option value="last_week" <?php echo e(request('date_filter') == 'last_week' ? 'selected' : ''); ?>>Last Week</option>
                            <option value="last_month" <?php echo e(request('date_filter') == 'last_month' ? 'selected' : ''); ?>>Last Month</option>
                            <option value="this_month" <?php echo e(request('date_filter') == 'this_month' || !request('date_filter') ? 'selected' : ''); ?>>This Month</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form action="<?php echo e(route('urls.download')); ?>" method="GET">
                        <input type="hidden" name="date_filter" value="<?php echo e(request('date_filter', 'this_month')); ?>">
                        <button type="submit" class="btn btn-primary">Download</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Short URL</th>
                        <th>Long URL</th>
                        <th><?php if(auth()->user()->isAdmin()): ?>User <?php elseif(auth()->user()->isSuperAdmin()): ?>Company <?php else: ?> Hits <?php endif; ?></th>
                        <th>Created On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $shortUrls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(url('/s/' . $url->short_code)); ?>" target="_blank" class="text-decoration-none">
                                <?php echo e(url('/s/' . $url->short_code)); ?>

                            </a>
                        </td>
                        <td>
                            <span class="text-muted" title="<?php echo e($url->long_url); ?>">
                                <?php echo e(\Illuminate\Support\Str::limit($url->long_url, 60)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <span class="badge bg-info"><?php echo e($url->user->name ?? 'N/A'); ?></span>
                            <?php elseif(auth()->user()->isSuperAdmin()): ?>
                                <span class="badge bg-warning text-dark"><?php echo e($url->company->name ?? 'N/A'); ?></span>
                            <?php else: ?>
                                <span class="badge bg-success"><?php echo e($url->hits); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($url->created_at->format('d M \'y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No short URLs found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing <?php echo e($shortUrls->firstItem() ?? 0); ?> of total <?php echo e($shortUrls->total()); ?>

            </div>
            <div>
                <?php echo e($shortUrls->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\shortner_url\resources\views/urls/index.blade.php ENDPATH**/ ?>