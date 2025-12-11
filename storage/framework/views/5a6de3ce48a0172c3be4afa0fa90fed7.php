<?php $__env->startSection('title', 'Clients'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Clients</h5>
            <a href="<?php echo e(route('superadmin.invite-client')); ?>" class="btn btn-primary">Invite</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Users</th>
                        <th>Total Generated URLs</th>
                        <th>Total URL Hits</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($company->name); ?></strong></td>
                        <td><?php echo e($company->email); ?></td>
                        <td>
                            <span class="badge bg-primary"><?php echo e($company->users_count ?? 0); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo e($company->short_urls_count ?? 0); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-success"><?php echo e($company->short_urls_sum_hits ?? 0); ?></span>
                        </td>
                        <td>
                            <?php if($company->users->first() && $company->users->first()->temp_password): ?>
                                <code class="bg-light text-dark px-2 py-1 rounded" style="font-size: 0.9em;"><?php echo e($company->users->first()->temp_password); ?></code>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No clients found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing <?php echo e($companies->firstItem() ?? 0); ?> of total <?php echo e($companies->total()); ?>

            </div>
            <div>
                <?php echo e($companies->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\shortner_url\resources\views/superadmin/clients.blade.php ENDPATH**/ ?>