<?php
// Props: $tabs [['label' => '', 'content' => '', 'id' => '']]
$tabs = $tabs ?? [];
if (empty($tabs)) return;
$uid = 'tabs-' . substr(md5(microtime()), 0, 6);
?>
<div class="sa-tabs" id="<?= $uid ?>">
  <div class="sa-tabs-nav" role="tablist">
    <?php foreach ($tabs as $i => $tab): ?>
      <?php $tabId = $uid . '-' . ($tab['id'] ?? $i); ?>
      <button class="sa-tab-btn <?= $i === 0 ? 'active' : '' ?>"
              role="tab"
              data-tab-target="<?= $tabId ?>"
              aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">
        <?php if (!empty($tab['icon'])): ?>
          <i class="ti ti-<?= e($tab['icon']) ?>"></i>
        <?php endif; ?>
        <?= e($tab['label']) ?>
      </button>
    <?php endforeach; ?>
  </div>

  <div class="sa-tabs-content">
    <?php foreach ($tabs as $i => $tab): ?>
      <?php $tabId = $uid . '-' . ($tab['id'] ?? $i); ?>
      <div class="sa-tab-pane <?= $i === 0 ? 'active' : '' ?>"
           id="<?= $tabId ?>"
           role="tabpanel"
           <?= $i !== 0 ? 'style="display:none"' : '' ?>>
        <?= $tab['content'] ?? '' ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
