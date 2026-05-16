/* ══════════════════════════════════════════════════════
   admin-custom.js — Base SaaS
   Vanilla JS uniquement. Toutes les fonctions sont nommées
   et appelées dans le listener DOMContentLoaded du layout.
   ══════════════════════════════════════════════════════ */

/* ─── Tooltips ──────────────────────────────────────── */
function initTooltips() {
  // Les tooltips sont gérés en CSS pur via [data-tooltip]::after
  // Cette fonction peut être étendue pour des tooltips dynamiques
}

/* ─── Modals ────────────────────────────────────────── */
function initModals() {
  // Ouvrir une modal via data-modal-target="#id"
  document.querySelectorAll('[data-modal-target]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var target = document.querySelector(btn.dataset.modalTarget);
      if (target) target.classList.add('open');
    });
  });

  // Fermer via data-modal-close ou clic sur backdrop
  document.addEventListener('click', function(e) {
    if (e.target.closest('[data-modal-close]')) {
      var modal = e.target.closest('[data-modal]');
      if (modal) modal.classList.remove('open');
    }
    if (e.target.classList.contains('sa-modal-backdrop')) {
      e.target.classList.remove('open');
    }
  });

  // Fermer avec Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.sa-modal-backdrop.open').forEach(function(m) {
        m.classList.remove('open');
      });
    }
  });
}

/* ─── Cards collapsibles ────────────────────────────── */
function initCollapsibles() {
  document.querySelectorAll('[data-collapsible]').forEach(function(card) {
    var header = card.querySelector('.sa-card-collapsible-header');
    if (!header) return;

    header.addEventListener('click', function() {
      var isOpen = card.classList.contains('open');
      var body   = card.querySelector('[id^="collapse-"]');
      if (!body) return;

      if (isOpen) {
        body.style.display = 'none';
        card.classList.remove('open');
        var btn = header.querySelector('.sa-collapse-btn');
        if (btn) btn.setAttribute('aria-expanded', 'false');
      } else {
        body.style.display = '';
        card.classList.add('open');
        var btn = header.querySelector('.sa-collapse-btn');
        if (btn) btn.setAttribute('aria-expanded', 'true');
      }
    });
  });
}

/* ─── Dark mode ─────────────────────────────────────── */
function initDarkMode() {
  var toggle = document.getElementById('saDarkToggle');
  var icon   = document.getElementById('saDarkIcon');

  function applyTheme(dark) {
    if (dark) {
      document.body.classList.add('dark');
      if (icon) { icon.classList.remove('ti-moon'); icon.classList.add('ti-sun'); }
    } else {
      document.body.classList.remove('dark');
      if (icon) { icon.classList.remove('ti-sun'); icon.classList.add('ti-moon'); }
    }
  }

  // Restaurer la préférence sauvegardée
  applyTheme(localStorage.getItem('sa-dark') === '1');

  if (toggle) {
    toggle.addEventListener('click', function() {
      var isDark = document.body.classList.toggle('dark');
      localStorage.setItem('sa-dark', isDark ? '1' : '0');
      applyTheme(isDark);
    });
  }
}

/* ─── Sidebar mobile ────────────────────────────────── */
function initSidebarMobile() {
  var sidebar  = document.getElementById('saSidebar');
  var toggle   = document.getElementById('saSidebarToggle');
  var close    = document.getElementById('saSidebarClose');
  var overlay  = document.getElementById('saSidebarOverlay');

  function openSidebar() {
    if (sidebar)  sidebar.classList.add('open');
    if (overlay)  overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    if (sidebar)  sidebar.classList.remove('open');
    if (overlay)  overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (toggle)  toggle.addEventListener('click', openSidebar);
  if (close)   close.addEventListener('click', closeSidebar);
  if (overlay) overlay.addEventListener('click', closeSidebar);

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSidebar();
  });
}

/* ─── Search / filtre live ──────────────────────────── */
function initSearchFilter() {
  document.querySelectorAll('.sa-search-input[data-search-target]').forEach(function(input) {
    input.addEventListener('input', function() {
      var target = document.querySelector(input.dataset.searchTarget);
      if (!target) return;

      var query = input.value.toLowerCase().trim();
      var rows  = target.querySelectorAll('tr');

      rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.style.display = (query === '' || text.indexOf(query) !== -1) ? '' : 'none';
      });
    });
  });
}

/* ─── Tri de tableaux ───────────────────────────────── */
function initSortableTable() {
  document.querySelectorAll('.sa-table[data-sortable], table.sa-table').forEach(function(table) {
    var headers = table.querySelectorAll('th.sa-th-sortable');
    if (!headers.length) return;

    headers.forEach(function(th, colIndex) {
      th.addEventListener('click', function() {
        var tbody = table.querySelector('tbody');
        if (!tbody) return;

        var rows     = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
        var asc      = th.dataset.sortDir !== 'asc';
        th.dataset.sortDir = asc ? 'asc' : 'desc';

        // Réinitialiser les autres headers
        headers.forEach(function(h) { if (h !== th) h.dataset.sortDir = ''; });

        rows.sort(function(a, b) {
          var aVal = (a.cells[colIndex] ? a.cells[colIndex].textContent : '').trim().toLowerCase();
          var bVal = (b.cells[colIndex] ? b.cells[colIndex].textContent : '').trim().toLowerCase();
          if (aVal < bVal) return asc ? -1 : 1;
          if (aVal > bVal) return asc ? 1  : -1;
          return 0;
        });

        rows.forEach(function(row) { tbody.appendChild(row); });
      });
    });
  });
}

/* ─── Confirmation avant suppression ────────────────── */
function initDeleteConfirm() {
  // Formulaires avec data-confirm
  document.querySelectorAll('form[data-confirm]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      var msg = form.dataset.confirm || 'Confirmer cette action ?';
      if (!confirm(msg)) e.preventDefault();
    });
  });

  // Boutons avec data-confirm (hors form)
  document.querySelectorAll('[data-confirm]:not(form)').forEach(function(el) {
    el.addEventListener('click', function(e) {
      var msg = el.dataset.confirm || 'Confirmer cette action ?';
      if (!confirm(msg)) e.preventDefault();
    });
  });
}

/* ─── Fermeture des alertes ─────────────────────────── */
function initDismissAlerts() {
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('[data-dismiss-alert]');
    if (btn) {
      var alert = btn.closest('[data-alert]');
      if (alert) {
        alert.style.transition = 'opacity 0.2s ease';
        alert.style.opacity = '0';
        setTimeout(function() { alert.remove(); }, 200);
      }
    }
  });
}

/* ─── Stub initCharts ───────────────────────────────── */
function initCharts() {
  // Chaque page initialise ses propres charts via les composants PHP
  // (chart-line.php, chart-bar.php, etc. génèrent le JS inline)
  // Ce stub est présent pour conformité avec la spec.
}

/* ─── Highlight.js ──────────────────────────────────── */
function initHighlight() {
  if (typeof hljs === 'undefined') return;
  document.querySelectorAll('pre code[class*="language-"]').forEach(function(block) {
    hljs.highlightElement(block);
  });
}

/* ─── JSON viewer expand/collapse ───────────────────── */
function initJsonViewer() {
  document.querySelectorAll('[data-json-viewer]').forEach(function(viewer) {
    var expandBtn  = viewer.querySelector('[data-json-expand]');
    var collapseBtn = viewer.querySelector('[data-json-collapse]');

    if (expandBtn) {
      expandBtn.addEventListener('click', function() {
        // Simple : afficher tout le contenu (déjà affiché, feature pour JSON tree)
        viewer.querySelectorAll('.sa-json-node.collapsed').forEach(function(n) {
          n.classList.remove('collapsed');
        });
      });
    }

    if (collapseBtn) {
      collapseBtn.addEventListener('click', function() {
        viewer.querySelectorAll('.sa-json-node').forEach(function(n) {
          n.classList.add('collapsed');
        });
      });
    }
  });
}

/* ─── Copier dans le presse-papier ──────────────────── */
function initCopyButtons() {
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('[data-copy-code]');
    if (!btn) return;

    var block = btn.closest('.sa-code-block, .sa-json-viewer, .sa-log-viewer');
    var code  = block ? (block.querySelector('code') || block.querySelector('.sa-log-body')) : null;
    if (!code) return;

    var text = code.innerText || code.textContent || '';

    if (navigator.clipboard) {
      navigator.clipboard.writeText(text).then(function() {
        showCopyFeedback(btn);
      }).catch(function() { fallbackCopy(text, btn); });
    } else {
      fallbackCopy(text, btn);
    }
  });

  // data-copy-target=".selector"
  document.querySelectorAll('[data-copy-target]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var target = document.querySelector(btn.dataset.copyTarget);
      if (!target) return;
      var text = target.innerText || target.textContent || '';
      if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() { showCopyFeedback(btn); });
      } else {
        fallbackCopy(text, btn);
      }
    });
  });
}

function showCopyFeedback(btn) {
  var icon = btn.querySelector('i');
  if (icon) {
    icon.className = 'ti ti-check';
    setTimeout(function() { icon.className = 'ti ti-copy'; }, 1500);
  }
}

function fallbackCopy(text, btn) {
  var ta = document.createElement('textarea');
  ta.value = text;
  ta.style.cssText = 'position:fixed;top:-9999px;';
  document.body.appendChild(ta);
  ta.select();
  try { document.execCommand('copy'); showCopyFeedback(btn); } catch(e) {}
  document.body.removeChild(ta);
}

/* ─── Validation côté client ────────────────────────── */
function initFormValidation() {
  document.querySelectorAll('form[novalidate]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      var valid = true;

      // Nettoyer les erreurs précédentes
      form.querySelectorAll('.sa-field-error-js').forEach(function(el) { el.remove(); });
      form.querySelectorAll('.sa-input-error').forEach(function(el) { el.classList.remove('sa-input-error'); });

      form.querySelectorAll('[required]').forEach(function(field) {
        if (field.value.trim() === '') {
          valid = false;
          field.classList.add('sa-input-error');
          var err = document.createElement('span');
          err.className = 'sa-field-error sa-field-error-js';
          err.textContent = 'Ce champ est requis.';
          field.parentNode.appendChild(err);
        }
      });

      form.querySelectorAll('[type="email"]').forEach(function(field) {
        if (field.value.trim() !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) {
          valid = false;
          field.classList.add('sa-input-error');
          var err = document.createElement('span');
          err.className = 'sa-field-error sa-field-error-js';
          err.textContent = 'Email invalide.';
          field.parentNode.appendChild(err);
        }
      });

      if (!valid) e.preventDefault();
    });
  });
}

/* ─── Dropdowns ─────────────────────────────────────── */
function initDropdowns() {
  document.addEventListener('click', function(e) {
    var trigger = e.target.closest('[data-dropdown]');

    // Fermer tous les autres
    document.querySelectorAll('.sa-dropdown-menu.open').forEach(function(menu) {
      var parent = menu.closest('.sa-dropdown');
      if (!trigger || parent !== trigger) menu.classList.remove('open');
    });

    if (trigger) {
      var dropdownId = trigger.id || trigger.dataset.dropdown;
      var menu;

      if (dropdownId) {
        var container = document.getElementById(dropdownId);
        menu = container ? container.querySelector('.sa-dropdown-menu') : null;
      } else {
        menu = trigger.querySelector('.sa-dropdown-menu');
      }

      if (menu) menu.classList.toggle('open');
    }
  });
}

/* ─── Tabs ──────────────────────────────────────────── */
function initTabs() {
  document.querySelectorAll('.sa-tabs').forEach(function(tabsContainer) {
    tabsContainer.querySelectorAll('.sa-tab-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var targetId = btn.dataset.tabTarget;
        if (!targetId) return;

        // Désactiver tous les boutons et panels du même groupe
        tabsContainer.querySelectorAll('.sa-tab-btn').forEach(function(b) {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        tabsContainer.querySelectorAll('.sa-tab-pane').forEach(function(p) {
          p.classList.remove('active');
          p.style.display = 'none';
        });

        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');

        var pane = document.getElementById(targetId);
        if (pane) {
          pane.classList.add('active');
          pane.style.display = '';
        }
      });
    });
  });
}

/* ─── Upload drag & drop preview ────────────────────── */
function initUploadZones() {
  document.querySelectorAll('[data-upload-zone]').forEach(function(zone) {
    var input   = zone.querySelector('.sa-upload-input');
    var preview = zone.querySelector('.sa-upload-preview');
    var body    = zone.querySelector('.sa-upload-body');

    ['dragenter','dragover'].forEach(function(ev) {
      zone.addEventListener(ev, function(e) { e.preventDefault(); zone.classList.add('drag-over'); });
    });
    ['dragleave','drop'].forEach(function(ev) {
      zone.addEventListener(ev, function(e) { zone.classList.remove('drag-over'); });
    });
    zone.addEventListener('drop', function(e) {
      e.preventDefault();
      if (input && e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        showPreview(e.dataTransfer.files[0]);
      }
    });

    if (input) {
      input.addEventListener('change', function() {
        if (input.files.length) showPreview(input.files[0]);
      });
    }

    function showPreview(file) {
      if (!preview || !body) return;
      body.style.display = 'none';
      preview.style.display = '';
      if (file.type.indexOf('image') === 0) {
        var reader = new FileReader();
        reader.onload = function(e) {
          preview.innerHTML = '<img src="' + e.target.result + '" style="max-height:120px;border-radius:4px;">';
        };
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '<p style="font-size:.85rem;">' + file.name + '</p>';
      }
    }
  });
}

/* ─── Notification filter (demo) ────────────────────── */
function initNotifFilter() {
  document.querySelectorAll('[data-notif-filter]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var filter = btn.dataset.notifFilter;
      document.querySelectorAll('[data-notif-filter]').forEach(function(b) { b.classList.remove('active'); });
      btn.classList.add('active');

      document.querySelectorAll('.sa-notif-row').forEach(function(row) {
        if (filter === 'all') {
          row.style.display = '';
        } else if (filter === 'unread') {
          row.style.display = row.classList.contains('sa-unread') ? '' : 'none';
        } else {
          row.style.display = row.dataset.notifType === filter ? '' : 'none';
        }
      });
    });
  });
}

/* ─── Log filter ────────────────────────────────────── */
function initLogFilter() {
  document.querySelectorAll('.sa-log-filter').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var level = btn.dataset.logFilter;
      document.querySelectorAll('.sa-log-filter').forEach(function(b) { b.classList.remove('active'); });
      btn.classList.add('active');

      document.querySelectorAll('.sa-log-line').forEach(function(line) {
        if (level === 'ALL') {
          line.style.display = '';
        } else {
          line.style.display = line.dataset.logLevel === level ? '' : 'none';
        }
      });
    });
  });
}

/* ─── Multi-select ──────────────────────────────────── */
function initMultiSelect() {
  document.querySelectorAll('[data-multiselect]').forEach(function(ms) {
    var control    = ms.querySelector('.sa-ms-control');
    var dropdown   = ms.querySelector('.sa-ms-dropdown');
    var searchInput = ms.querySelector('.sa-ms-search-input');
    var optionsWrap = ms.querySelector('.sa-ms-options');
    var hiddenWrap  = ms.querySelector('.sa-ms-hidden-inputs');
    var tagsWrap    = ms.querySelector('.sa-ms-tags');
    var placeholder = ms.querySelector('.sa-ms-placeholder');
    var name        = ms.dataset.name || '';

    if (!control || !dropdown) return;

    // ── Ouvrir / fermer ───────────────────────────────
    function open() {
      ms.classList.add('open');
      control.setAttribute('aria-expanded', 'true');
      if (searchInput) { searchInput.value = ''; filterOptions(''); searchInput.focus(); }
    }
    function close() {
      ms.classList.remove('open');
      control.setAttribute('aria-expanded', 'false');
    }
    function toggle() { ms.classList.contains('open') ? close() : open(); }

    control.addEventListener('click', function(e) {
      if (e.target.closest('.sa-ms-tag-remove')) return;
      toggle();
    });
    control.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
      if (e.key === 'Escape') close();
    });

    // Fermer si clic extérieur
    document.addEventListener('click', function(e) {
      if (!ms.contains(e.target)) close();
    });

    // ── Recherche ─────────────────────────────────────
    function filterOptions(query) {
      var options  = optionsWrap.querySelectorAll('.sa-ms-option');
      var noResult = optionsWrap.querySelector('.sa-ms-no-results');
      var visible  = 0;
      query = query.toLowerCase().trim();
      options.forEach(function(opt) {
        var label = opt.dataset.msLabel || '';
        var match = query === '' || label.indexOf(query) !== -1;
        opt.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      if (noResult) noResult.style.display = visible === 0 ? '' : 'none';
    }
    if (searchInput) {
      searchInput.addEventListener('input', function() { filterOptions(searchInput.value); });
      searchInput.addEventListener('click', function(e) { e.stopPropagation(); });
    }

    // ── Sélectionner / désélectionner une option ──────
    function refreshTags() {
      var checkboxes = optionsWrap.querySelectorAll('.sa-ms-checkbox:checked');
      // Vider les inputs cachés et les tags
      hiddenWrap.innerHTML = '';
      tagsWrap.innerHTML   = '';
      checkboxes.forEach(function(cb) {
        var opt   = cb.closest('.sa-ms-option');
        var label = opt ? opt.querySelector('.sa-ms-option-label').textContent.trim() : cb.value;
        // Input caché
        var inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = name + '[]';
        inp.value = cb.value;
        hiddenWrap.appendChild(inp);
        // Tag
        var tag = document.createElement('span');
        tag.className       = 'sa-ms-tag';
        tag.dataset.msTag   = cb.value;
        tag.innerHTML = label +
          '<button type="button" class="sa-ms-tag-remove" data-ms-remove="' + cb.value + '" aria-label="Retirer">' +
          '<i class="ti ti-x"></i></button>';
        tagsWrap.appendChild(tag);
      });
      // Placeholder
      if (placeholder) {
        placeholder.style.display = checkboxes.length > 0 ? 'none' : '';
      }
    }

    // La checkbox est dans un <label>, le clic natif la toggle — on écoute uniquement change
    optionsWrap.addEventListener('change', function(e) {
      var cb = e.target;
      if (!cb.classList.contains('sa-ms-checkbox')) return;
      var opt = cb.closest('.sa-ms-option');
      if (opt) opt.classList.toggle('checked', cb.checked);
      refreshTags();
    });

    // ── Supprimer un tag (clic × sur le tag) ──────────
    tagsWrap.addEventListener('click', function(e) {
      var btn = e.target.closest('[data-ms-remove]');
      if (!btn) return;
      var val = btn.dataset.msRemove;
      var cb  = optionsWrap.querySelector('.sa-ms-checkbox[value="' + val + '"]');
      if (cb) {
        cb.checked = false;
        var opt = cb.closest('.sa-ms-option');
        if (opt) opt.classList.remove('checked');
      }
      refreshTags();
    });

    // ── Tout sélectionner ─────────────────────────────
    var btnAll = ms.querySelector('[data-ms-all]');
    if (btnAll) {
      btnAll.addEventListener('click', function(e) {
        e.stopPropagation();
        optionsWrap.querySelectorAll('.sa-ms-option:not([style*="none"]) .sa-ms-checkbox').forEach(function(cb) {
          cb.checked = true;
          var opt = cb.closest('.sa-ms-option');
          if (opt) opt.classList.add('checked');
        });
        refreshTags();
      });
    }

    // ── Effacer ───────────────────────────────────────
    var btnClear = ms.querySelector('[data-ms-clear]');
    if (btnClear) {
      btnClear.addEventListener('click', function(e) {
        e.stopPropagation();
        optionsWrap.querySelectorAll('.sa-ms-checkbox').forEach(function(cb) {
          cb.checked = false;
          var opt = cb.closest('.sa-ms-option');
          if (opt) opt.classList.remove('checked');
        });
        refreshTags();
      });
    }
  });
}

/* ─── Init global ───────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
  initTooltips();
  initModals();
  initCollapsibles();
  initDarkMode();
  initSidebarMobile();
  initSearchFilter();
  initSortableTable();
  initDeleteConfirm();
  initDismissAlerts();
  initHighlight();
  initJsonViewer();
  initCopyButtons();
  initFormValidation();
  initDropdowns();
  initTabs();
  initUploadZones();
  initNotifFilter();
  initLogFilter();
  initMultiSelect();
});
