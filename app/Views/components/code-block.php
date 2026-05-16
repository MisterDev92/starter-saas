<?php
// Props: $code, $language, $title
$code     = $code     ?? '';
$language = $language ?? 'php';
$title    = $title    ?? '';
?>
<div class="sa-code-block">
  <?php if ($title !== ''): ?>
    <div class="sa-code-header">
      <span class="sa-code-title"><?= e($title) ?></span>
      <button class="sa-btn sa-btn-xs sa-btn-ghost" data-copy-code title="Copier">
        <i class="ti ti-copy"></i>
      </button>
    </div>
  <?php else: ?>
    <button class="sa-code-copy-btn sa-btn sa-btn-xs sa-btn-ghost" data-copy-code title="Copier">
      <i class="ti ti-copy"></i>
    </button>
  <?php endif; ?>
  <pre class="sa-code-pre"><code class="language-<?= e($language) ?>"><?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?></code></pre>
</div>
