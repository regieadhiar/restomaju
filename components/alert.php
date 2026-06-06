<?php if (!empty($error)): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm font-medium flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
