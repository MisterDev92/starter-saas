<?php
// Props: $data (tableau PHP → encodé en JSON)
$data  = $data  ?? [];
$uid   = 'json-' . substr(md5(microtime()), 0, 6);
$json  = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
<div class="sa-json-viewer" id="<?= $uid ?>" data-json-viewer>
  <div class="sa-json-toolbar">
    <button class="sa-btn sa-btn-xs sa-btn-ghost" data-json-expand>Tout déplier</button>
    <button class="sa-btn sa-btn-xs sa-btn-ghost" data-json-collapse>Tout replier</button>
    <button class="sa-btn sa-btn-xs sa-btn-ghost" data-copy-code title="Copier">
      <i class="ti ti-copy"></i>
    </button>
  </div>
  <pre class="sa-code-pre"><code class="language-json"><?= htmlspecialchars($json, ENT_QUOTES, 'UTF-8') ?></code></pre>
</div>
