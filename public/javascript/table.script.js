document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.data-section').forEach(initDataSection);

  function initDataSection(section) {
    const searchInput   = section.querySelector('.data-search-input');
    const pageSizeSelect = section.querySelector('.data-page-size');
    const pagination    = section.querySelector('.data-pagination');

    const tableRows = Array.from(section.querySelectorAll('.data-table .data-row'));
    const emptyRow  = section.querySelector('.data-table .data-empty-row');

    const cardItems   = Array.from(section.querySelectorAll('.data-cards .data-card-item'));
    const cardsEmpty  = section.querySelector('.data-cards .data-cards-empty');
    const cardsAccordion = section.querySelector('.data-cards .accordion');

    let pageSize = parseInt(section.dataset.defaultPagesize || pageSizeSelect?.value || 10, 10);
    let currentPage = 1;
    let term = '';

    if (pageSizeSelect) pageSizeSelect.value = String(pageSize);

    const norm = (s) =>
      (s ?? '')
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();

    function getSearchValue(el) {
      return norm(el?.dataset?.search || '');
    }

    function getMatchedIndexes() {
      const base = tableRows.length ? tableRows : cardItems;
      const t = norm(term);

      if (!t) return base.map((_, i) => i);

      const matched = [];
      for (let i = 0; i < base.length; i++) {
        if (getSearchValue(base[i]).includes(t)) matched.push(i);
      }
      return matched;
    }

    function applyVisibility(indexSet, baseLen) {
      // Tabla
      if (tableRows.length) {
        tableRows.forEach((row, i) => {
          row.style.display = indexSet.has(i) ? '' : 'none';
        });
      }

      // Cards
      if (cardItems.length) {
        cardItems.forEach((card, i) => {
          card.style.display = indexSet.has(i) ? '' : 'none';
        });
      }

      // Empty states
      const hasAny = indexSet.size > 0;

      if (emptyRow) emptyRow.style.display = hasAny ? 'none' : '';
      if (cardsEmpty) cardsEmpty.style.display = hasAny ? 'none' : '';
      if (cardsAccordion) cardsAccordion.style.display = hasAny ? '' : 'none';
    }

    function buildPagination(totalPages) {
      if (!pagination) return;
      pagination.innerHTML = '';

      const isMobile = window.matchMedia('(max-width: 576px)').matches;
      const siblings = isMobile ? 1 : 2;

      const makeItem = (label, page, disabled = false, active = false, aria = '') => {
        const li = document.createElement('li');
        li.className = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`;

        const a = document.createElement('a');
        a.href = '#';
        a.className = 'page-link';
        a.textContent = label;

        if (aria) a.setAttribute('aria-label', aria);

        a.addEventListener('click', (e) => {
          e.preventDefault();
          if (disabled) return;
          currentPage = page;
          render();
        });

        li.appendChild(a);
        return li;
      };

      const makeEllipsis = () => {
        const li = document.createElement('li');
        li.className = 'page-item disabled';

        const span = document.createElement('span');
        span.className = 'page-link';
        span.textContent = '…';
        span.setAttribute('aria-hidden', 'true');

        li.appendChild(span);
        return li;
      };

      // Prev
      pagination.appendChild(
        makeItem('«', currentPage - 1, currentPage === 1, false, 'Página anterior')
      );

      if (totalPages === 1) {
        pagination.appendChild(makeItem('1', 1, false, true, 'Página 1'));
        pagination.appendChild(makeItem('»', 2, true, false, 'Página siguiente'));
        return;
      }

      const left = Math.max(2, currentPage - siblings);
      const right = Math.min(totalPages - 1, currentPage + siblings);

      // 1
      pagination.appendChild(makeItem('1', 1, false, currentPage === 1, 'Página 1'));

      if (left > 2) pagination.appendChild(makeEllipsis());

      for (let p = left; p <= right; p++) {
        pagination.appendChild(makeItem(String(p), p, false, p === currentPage, `Página ${p}`));
      }

      if (right < totalPages - 1) pagination.appendChild(makeEllipsis());

      // Última
      pagination.appendChild(
        makeItem(String(totalPages), totalPages, false, currentPage === totalPages, `Página ${totalPages}`)
      );

      // Next
      pagination.appendChild(
        makeItem('»', currentPage + 1, currentPage === totalPages, false, 'Página siguiente')
      );
    }

    function render() {
      const matchedIndexes = getMatchedIndexes();
      const totalItems = matchedIndexes.length;

      const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));
      if (currentPage > totalPages) currentPage = totalPages;
      if (currentPage < 1) currentPage = 1;

      const start = (currentPage - 1) * pageSize;
      const end = start + pageSize;
      const visible = new Set(matchedIndexes.slice(start, end));
      const baseLen = (tableRows.length ? tableRows.length : cardItems.length);
      applyVisibility(visible, baseLen);

      buildPagination(totalPages);
    }

    // Eventos
    if (searchInput) {

      let to = null;
      searchInput.addEventListener('input', () => {
        clearTimeout(to);
        to = setTimeout(() => {
          term = searchInput.value || '';
          currentPage = 1;
          render();
        }, 80);
      });
    }

    if (pageSizeSelect) {
      pageSizeSelect.addEventListener('change', () => {
        pageSize = parseInt(pageSizeSelect.value, 10) || 10;
        currentPage = 1;
        render();
      });
    }

  
    const mql = window.matchMedia('(max-width: 576px)');
    mql.addEventListener?.('change', render);

    render();
  }
});
