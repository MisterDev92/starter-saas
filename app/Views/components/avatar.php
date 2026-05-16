<?php
// Props: $name, $avatar_url, $size (sm|md|lg)
$name       = $name       ?? 'U';
$avatar_url = $avatar_url ?? '';
$size       = $size       ?? 'md';

// Générer les initiales (2 caractères max)
$parts    = explode(' ', trim($name));
$initials = mb_strtoupper(mb_substr($parts[0], 0, 1));
if (count($parts) > 1) {
    $initials .= mb_strtoupper(mb_substr(end($parts), 0, 1));
}

// Couleur déterministe selon le nom
$colors = ['#4f46e5','#0ea5e9','#22c55e','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6'];
$color  = $colors[abs(crc32($name)) % count($colors)];
?>
<div class="sa-avatar sa-avatar-<?= e($size) ?>"
     style="<?= $avatar_url ? '' : "background:{$color}" ?>"
     title="<?= e($name) ?>">
  <?php if ($avatar_url): ?>
    <img src="<?= e($avatar_url) ?>" alt="<?= e($name) ?>">
  <?php else: ?>
    <span><?= e($initials) ?></span>
  <?php endif; ?>
</div>
