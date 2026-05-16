<?php
// Props: $label, $type (active|inactive|pending|admin|user|primary|success|danger|warning|info)
$label = $label ?? '';
$type  = $type  ?? 'primary';
?>
<span class="sa-badge sa-badge-<?= e($type) ?>"><?= e($label) ?></span>
